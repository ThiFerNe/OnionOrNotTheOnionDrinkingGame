<?php

namespace logics;

require_once(__DIR__ . "/../integration/DatabaseIntegration.php");


class SessionHasVotedForGameDataLogic
{
    public static function add(int $gid, int $sid, bool $is_upvote) {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "INSERT INTO `session_has_voted_for_gamedata` (`gid`, `sid`, `was_upvote`) VALUES (?, ?, ?);"
        );
        return $preparedStatement->execute(array($gid, $sid, $is_upvote ? 1 : 0));
    }

    public static function update(int $gid, int $sid, bool $is_upvote) {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "UPDATE `session_has_voted_for_gamedata` SET `was_upvote` = ? WHERE `gid` = ? AND `sid` = ?;"
        );
        return $preparedStatement->execute(array($is_upvote ? 1 : 0, $gid, $sid));
    }

    public static function put(int $gid, int $sid, bool $is_upvote) {
        if(self::contains($gid, $sid)) {
            return self::update($gid, $sid, $is_upvote);
        } else {
            return self::add($gid, $sid, $is_upvote);
        }
    }

    public static function contains(int $gid, int $sid) {
        return self::isUpvote($gid, $sid) !== NULL;
    }

    public static function isUpvote(int $gid, int $sid) {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `was_upvote` FROM `session_has_voted_for_gamedata` WHERE `gid` = ? AND `sid` = ?;"
        );
        if($preparedStatement->execute(array($gid, $sid))) {
            if($preparedStatement->rowCount() > 0) {
                return $preparedStatement->fetch(\PDO::FETCH_ASSOC)["was_upvote"];
            }
        }
        return NULL;
    }

    public static function removeByGameDataIdAndUserSessionId(int $gid, int $sid) {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "DELETE FROM `session_has_voted_for_gamedata` WHERE `gid` = ? AND `sid` = ?;"
        );
        return $preparedStatement->execute(array($gid, $sid));
    }

    public static function removeByGameDataId(int $gid) {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "DELETE FROM `session_has_voted_for_gamedata` WHERE `gid` = ?;"
        );
        return $preparedStatement->execute(array($gid));
    }

    public static function removeByUserSessionId(int $sid) {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "DELETE FROM `session_has_voted_for_gamedata` WHERE `sid` = ?;"
        );
        return $preparedStatement->execute(array($sid));
    }
}