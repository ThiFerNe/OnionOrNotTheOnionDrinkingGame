<?php

namespace logics;

require_once(__DIR__ . "/../integration/DatabaseIntegration.php");

require_once(__DIR__ . "/../logics/GameDataLocalizationLogic.php");

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

    public static function insertQuestions()
    {
        $questions = array(
            array(
                self::HEADLINE => "Boyfriend Ready To Take Relationship To Previous Level",
                self::IS_ONION => 1,
                self::HYPERLINK => "https://local.theonion.com/boyfriend-ready-to-take-relationship-to-previous-level-1819568844",
                self::FURTHER_EXPLANATION => "EXPLAIIIIIIIN!!",
                self::PICTURE => "https://i.kinja-img.com/gawker-media/image/upload/s--mGgZC5ev--/c_fit,f_auto,fl_progressive,q_80,w_470/iggnu22540u3xquulugb.jpg",
                self::LOCALIZATION => array(
                    array(
                        self::LOCALE => \logics\further\LocalizationStore::LOCALE_GERMAN_SHORT,
                        self::HEADLINE => "Fester Freund ist bereit die Beziehung auf die vorherige Stufe zu bringen",
                        self::FURTHER_EXPLANATION => "EXPLAIIIIIIIN"
                    )
                )
            ),
            array(
                self::HEADLINE => "Man grieved at wrong grave for 30 years due to misplaced headstone",
                self::IS_ONION => 0,
                self::HYPERLINK => "https://www.bbc.com/news/uk-england-manchester-45111652",
                self::FURTHER_EXPLANATION => "EXPLAIIIIIIIN!!",
                self::PICTURE => "https://ichef.bbci.co.uk/news/660/cpsprodpb/37A7/production/_102874241_georgesalt.jpg",
                self::LOCALIZATION => array(
                    array(
                        self::LOCALE => \logics\further\LocalizationStore::LOCALE_GERMAN_SHORT,
                        self::HEADLINE => "Mann hat 30 Jahre lang aufgrund eines falsch gesetzten Grabsteins am falschen Grab getrauert",
                        self::FURTHER_EXPLANATION => "EXPLAIIIIIIIN"
                    )
                )
            ),
            array(
                self::HEADLINE => "Climate Researchers Warn Only Hope For Humanity Now Lies In Possibility They Making All Of This Up",
                self::IS_ONION => 1,
                self::HYPERLINK => "https://www.theonion.com/climate-researchers-warn-only-hope-for-humanity-now-lie-1828171232",
                self::FURTHER_EXPLANATION => "EXPLAIN!",
                self::PICTURE => "https://i.kinja-img.com/gawker-media/image/upload/s--y7sD2HrQ--/c_scale,f_auto,fl_progressive,q_80,w_800/dqxcvdc7drqhz0bifa1x.jpg",
                self::LOCALIZATION => array(
                    array(
                        self::LOCALE => \logics\further\LocalizationStore::LOCALE_GERMAN_SHORT,
                        self::HEADLINE => "Klimaforcher warnen die einzige Hoffnung für die Menschheit läge nun darin, dass sie sich das ganze nur ausgedacht hätten",
                        self::FURTHER_EXPLANATION => "EXPLAIN"
                    )
                )
            )
        );
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "INSERT INTO `gamedata` (`headline`, `is_onion`, `hyperlink`, `further_explanation`, `picture`) VALUES (?, ?, ?, ?, ?);"
        );
        $preparedStatement2 = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT MAX(`id`) AS `mid` FROM `gamedata`;"
        );
        foreach ($questions as $question) {
            if (!$preparedStatement->execute(array(
                $question[self::HEADLINE],
                $question[self::IS_ONION],
                $question[self::HYPERLINK],
                $question[self::FURTHER_EXPLANATION],
                $question[self::PICTURE]
            ))) {
                return FALSE;
            }
            if (!$preparedStatement2->execute(array())) {
                return FALSE;
            }
            if ($preparedStatement2->rowCount() <= 0) {
                return FALSE;
            }
            $gid = $preparedStatement2->fetch(\PDO::FETCH_ASSOC)["mid"];
            foreach ($question[self::LOCALIZATION] as $current_localization) {
                if(!\logics\GameDataLocalizationLogic::add(
                    $gid,
                    $current_localization[self::LOCALE],
                    $current_localization[self::HEADLINE],
                    $current_localization[self::FURTHER_EXPLANATION]
                )) {
                    return FALSE;
                }
            }
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
              `headline` text CHARACTER SET utf8 NOT NULL,
              `is_onion` tinyint(1) NOT NULL,
              `hyperlink` text CHARACTER SET utf8,
              `further_explanation` text CHARACTER SET utf8,
              `picture` varchar(256) DEFAULT NULL,
              `upvotes` bigint(20) NOT NULL DEFAULT '0',
              `downvotes` bigint(20) NOT NULL DEFAULT '0'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
            "ALTER TABLE `lobby`
              ADD PRIMARY KEY (`id`);",
            "ALTER TABLE `lobby`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;",
            "CREATE TABLE `lobby_used_gamedata` (
              `gid` int(11) NOT NULL,
              `lid` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
            "CREATE TABLE `session` (
              `id` int(11) NOT NULL,
              `session_id` varchar(128) CHARACTER SET utf8 NOT NULL,
              `username` varchar(64) CHARACTER SET utf8 NOT NULL,
              `last_active` int(11) NOT NULL,
              `in_lobby` int(11) NOT NULL,
              `points` int(11) NOT NULL DEFAULT '0',
              `only_watcher` tinyint(1) NOT NULL DEFAULT 0,
              `actual_answer_is_onion` int(11) NOT NULL DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
            "ALTER TABLE `session`
              ADD PRIMARY KEY (`id`);",
            "ALTER TABLE `session`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;",
            "CREATE TABLE `session_has_voted_for_gamedata` (
              `gid` int(11) NOT NULL,
              `sid` int(11) NOT NULL,
              `was_upvote` tinyint(1) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
            "CREATE TABLE `onion_config` ( `config_key` VARCHAR(128) NOT NULL , `config_value` VARCHAR(256) NOT NULL , PRIMARY KEY (`config_key`)) ENGINE = InnoDB;",
            "commit;"
        );
        foreach ($sqlqueries as $sqlquery) {
            \integration\DatabaseIntegration::getWriteInstance()->getConnection()->exec($sqlquery);
        }
        return TRUE;
    }
}