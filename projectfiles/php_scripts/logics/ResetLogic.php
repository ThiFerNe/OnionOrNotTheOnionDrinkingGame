<?php

namespace logics;

require_once(__DIR__ . "/../integration/DatabaseIntegration.php");

require_once(__DIR__ . "/../logics/GameDataLocalizationLogic.php");

require_once(__DIR__ . "/../helper/LogHelper.php");

use \helper\LogHelper as LOG;

class ResetLogic
{

    public const SETUP_CHECK_FILE = "website_is_setup";

    const HEADLINE = "headline";
    const IS_ONION = "is_onion";
    const HYPERLINK = "hyperlink";
    const FURTHER_EXPLANATION = "further_explanation";
    const PICTURE = "picture";
    const LOCALIZATION = "localization";
    const LOCALE = "locale";

    /**
     * @return bool|boolean True if the website does not allow reset
     */
    public static function isWebsiteBeenSetup()
    {
        return file_exists(self::SETUP_CHECK_FILE);
    }

    /**
     * @return bool
     */
    public static function doReset()
    {
        if (self::resetDatabase() && self::insertQuestions()) {
            touch(self::SETUP_CHECK_FILE);
            return TRUE;
        }
        return FALSE;
    }

    public static function insertQuestionsFile(string $filepath)
    {
        $full_contents = file_get_contents($filepath);
        if ($full_contents === FALSE) {
            LOG::FATAL("Could not load file \"" . $filepath . "\"");
            return FALSE;
        }
        $full_data = json_decode($full_contents, TRUE);
        if ($full_data === NULL) {
            LOG::FATAL("Could not decode JSON from file \"" . $filepath . "\"");
            return FALSE;
        }
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "INSERT INTO `gamedata` (" .
            "`reddit_id`, `headline`, `is_onion`, `subreddit`, `subreddit_id`, `selftext`, " .
            "`hyperlink`, `reddit_permalink`, `over_18`, `picture`, " .
            "`picture_height`, `picture_width`, `thumbnail`, `thumbnail_width`, `reddit_upvotes`, " .
            "`reddit_downvotes`, `reddit_score`" .
            ") VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);"
        );
        $preparedStatement2 = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT MAX(`id`) AS `mid` FROM `gamedata`;"
        );
        foreach ($full_data as $current_data) {
            if (!$preparedStatement->execute(array(
                $current_data["id"],
                $current_data["title"],
                $current_data["subreddit"] == "TheOnion",
                $current_data["subreddit"],
                $current_data["subreddit_id"],
                $current_data["selftext"],
                $current_data["url"],
                $current_data["permalink"],
                $current_data["over_18"] === TRUE ? 1 : 0,
                $current_data["image_url"],
                array_key_exists("image_height", $current_data) && $current_data["image_height"] !== NULL ? $current_data["image_height"] : -1,
                array_key_exists("image_width", $current_data) && $current_data["image_width"] !== NULL ? $current_data["image_width"] : -1,
                $current_data["thumbnail"],
                array_key_exists("thumbnail_width", $current_data) && $current_data["thumbnail_width"] !== NULL ? $current_data["thumbnail_width"] : -1,
                $current_data["ups"],
                $current_data["downs"],
                $current_data["score"]
            ))) {
                $errInf = $preparedStatement->errorInfo();
                LOG::FATAL("Could not insert \"" . $current_data["id"] . "\" from \"" . $filepath .
                    "\" into database \"" . json_encode($current_data) . "\" with error \"" . $errInf[0] . "\" + \""
                     . $errInf[1] . "\" + \"" . $errInf[2] . "\"");
                return FALSE;
            }
        }
        return TRUE;
    }

    public static function insertQuestions()
    {
        if (self::insertQuestionsFile(__DIR__ . '/../data/TheOnion.json') === FALSE) {
            return FALSE;
        }
        if (self::insertQuestionsFile(__DIR__ . '/../data/NotTheOnion.json') === FALSE) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * @return bool
     */
    public static function resetDatabase()
    {
        $sqlqueries = array(
            "DROP TABLE `gamedata`;",
            "DROP TABLE `gamedata_localization`;",
            "DROP TABLE `lobby`;",
            "DROP TABLE `lobby_used_gamedata`;",
            "DROP TABLE `session`;",
            "DROP TABLE `session_has_voted_for_gamedata`;",
            "CREATE TABLE `gamedata` (
              `id` int(11) NOT NULL,
              `reddit_id` varchar(6) NOT NULL,
              `headline` text CHARACTER SET utf8 NOT NULL,
              `is_onion` tinyint(1) NOT NULL,
              `subreddit` text CHARACTER SET utf8,
              `subreddit_id` varchar(9) NOT NULL,
              `selftext` text CHARACTER SET utf8,
              `hyperlink` text CHARACTER SET utf8,
              `reddit_permalink` text CHARACTER SET utf8,
              `over_18` tinyint(1) NOT NULL DEFAULT '0',
              `further_explanation` text CHARACTER SET utf8,
              `picture` varchar(256) DEFAULT NULL,
              `picture_height` int(11) NOT NULL DEFAULT '-1',
              `picture_width` int(11) NOT NULL DEFAULT '-1',
              `thumbnail` varchar(256) DEFAULT NULL,
              `thumbnail_width` int(11) NOT NULL DEFAULT '-1',
              `upvotes` bigint(20) NOT NULL DEFAULT '0',
              `downvotes` bigint(20) NOT NULL DEFAULT '0',
              `reddit_upvotes` bigint(20) NOT NULL DEFAULT '0',
              `reddit_downvotes` bigint(20) NOT NULL DEFAULT '0',
              `reddit_score` bigint(20) NOT NULL DEFAULT '0'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
            "ALTER TABLE `gamedata`
              ADD PRIMARY KEY (`id`);",
            "ALTER TABLE `gamedata`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;",
            "CREATE TABLE `gamedata_localization` (
              `id` int(11) NOT NULL,
              `gid` int(11) NOT NULL,
              `locale` varchar(8) DEFAULT NULL,
              `headline` text CHARACTER SET utf8,
              `further_explanation` text CHARACTER SET utf8
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
            "ALTER TABLE `gamedata_localization`
              ADD PRIMARY KEY (`id`);",
            "ALTER TABLE `gamedata_localization`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;",
            "CREATE TABLE `lobby` (
              `id` int(11) NOT NULL,
              `lobbycode` varchar(16) CHARACTER SET utf8 NOT NULL,
              `created_on` int(11) NOT NULL,
              `last_active` int(11) NOT NULL,
              `current_state` int(11) DEFAULT 0,
              `current_state_on` int(11),
              `current_gamedata` int(11),
              `max_questions` int(11) NOT NULL,
              `current_questions` int(11) NOT NULL DEFAULT 0,
              `minimum_score` int(11) DEFAULT NULL,
              `timer` int(11) NOT NULL DEFAULT '-1',
              `end_ranking` text CHARACTER SET utf8
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
            "ALTER TABLE `lobby`
              ADD PRIMARY KEY (`id`);",
            "ALTER TABLE `lobby`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;",
            "CREATE TABLE `lobby_used_gamedata` (
              `gid` int(11) NOT NULL,
              `lid` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
            "CREATE TABLE `session` (
              `id` int(11) NOT NULL,
              `session_id` varchar(128) CHARACTER SET utf8 NOT NULL,
              `username` varchar(64) CHARACTER SET utf8 NOT NULL,
              `last_active` int(11) NOT NULL,
              `in_lobby` int(11) NOT NULL,
              `points` int(11) NOT NULL DEFAULT '0',
              `only_watcher` tinyint(1) NOT NULL DEFAULT 0,
              `actual_answer_is_onion` int(11) NOT NULL DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
            "ALTER TABLE `session`
              ADD PRIMARY KEY (`id`);",
            "ALTER TABLE `session`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;",
            "CREATE TABLE `session_has_voted_for_gamedata` (
              `gid` int(11) NOT NULL,
              `sid` int(11) NOT NULL,
              `was_upvote` tinyint(1) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
            "CREATE TABLE `onion_config` ( `config_key` VARCHAR(128) NOT NULL , `config_value` VARCHAR(256) NOT NULL , PRIMARY KEY (`config_key`)) ENGINE = InnoDB;",
            "commit;"
        );
        foreach ($sqlqueries as $sqlquery) {
            \integration\DatabaseIntegration::getWriteInstance()->getConnection()->exec($sqlquery);
        }
        return TRUE;
    }
}