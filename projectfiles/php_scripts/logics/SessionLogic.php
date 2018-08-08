<?php

namespace logics;

require_once(__DIR__ . "/../helper/LogHelper.php");

require_once(__DIR__ . "/../integration/DatabaseIntegration.php");

use \helper\LogHelper as LOG;


class SessionLogic
{

    public static function createSession(string $sessionId, string $username_new_game, string $invite_code, bool $just_watch_new_game)
    {
        if (self::isUserInLobbyByPhpSessionId($sessionId)) {
            LOG::ERROR("Session Id is already registered and should be inside a lobby!");
            return FALSE;
        }
        $lobby_id = LobbyLogic::getLobbyIdByLobbyCode($invite_code);
        if ($lobby_id === NULL) {
            LOG::ERROR("Given lobby code does not exist! (\"" . $invite_code . "\")");
            return FALSE;
        }
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "INSERT INTO `session` (`session_id`, `username`, `last_active`, `in_lobby`, `only_watcher`) VALUES (?, ?, ?, ?, ?);"
        );
        return $preparedStatement->execute(array($sessionId, $username_new_game, time(), $lobby_id, $just_watch_new_game ? 1 : 0));
    }

    public static function getUserSessionIdByPhpSessionId(string $phpsessionid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `id` FROM `session` WHERE `session_id` = ?;"
        );
        if ($preparedStatement->execute(array($phpsessionid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["id"];
            }
        }
        return NULL;
    }

    public static function isUserInLobbyByPhpSessionId(string $phpsessionid)
    {
        return self::getUserSessionIdByPhpSessionId($phpsessionid) !== NULL;
    }

    public static function getUserSessionIdsByLobbyId(int $lobbyid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `id` FROM `session` WHERE `in_lobby` = ?;"
        );
        if ($preparedStatement->execute(array($lobbyid))) {
            $output = array();
            $fetched_rows = $preparedStatement->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($fetched_rows as $fetched_row) {
                array_push($output, $fetched_row["id"]);
            }
            return $output;
        }
        return NULL;
    }

    public static function getPhpSessionIdByUserSessionId(int $usersessionid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `session_id` FROM `session` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($usersessionid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["session_id"];
            }
        }
        return NULL;
    }

    public static function getUsernameByUserSessionId(int $usersessionid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `username` FROM `session` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($usersessionid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["username"];
            }
        }
        return NULL;
    }

    public static function getUsernameByPhpSessionId(string $phpsessionid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `username` FROM `session` WHERE `session_id` = ?;"
        );
        if ($preparedStatement->execute(array($phpsessionid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["username"];
            }
        }
        return NULL;
    }

    public static function getLastActiveByUserSessionId(int $usersessionid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `last_active` FROM `session` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($usersessionid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["last_active"];
            }
        }
        return NULL;
    }

    public static function getLastActiveByPhpSessionId(string $phpsessionid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `last_active` FROM `session` WHERE `session_id` = ?;"
        );
        if ($preparedStatement->execute(array($phpsessionid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["last_active"];
            }
        }
        return NULL;
    }

    public static function setLastActiveByUserSessionId(string $usersessionid, int $lastActive) {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "UPDATE `session` SET `last_active` = ? WHERE `id` = ?;"
        );
        return $preparedStatement->execute(array($lastActive, $usersessionid));
    }

    public static function setLastActiveByPhpSessionId(string $phpsessionid, int $lastActive) {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "UPDATE `session` SET `last_active` = ? WHERE `session_id` = ?;"
        );
        return $preparedStatement->execute(array($lastActive, $phpsessionid));
    }

    public static function getInLobbyByUserSessionId(int $usersessionid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `in_lobby` FROM `session` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($usersessionid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["in_lobby"];
            }
        }
        return NULL;
    }

    public static function getInLobbyByPhpSessionId(string $phpsessionid)
    {
        LOG::DEBUG("Getting in_lobby for PhpSessionId \"" . $phpsessionid . "\"");
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `in_lobby` FROM `session` WHERE `session_id` = ?;"
        );
        if ($preparedStatement->execute(array($phpsessionid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["in_lobby"];
            }
        }
        return NULL;
    }

    public static function getPointsByUserSessionId(int $usersessionid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `points` FROM `session` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($usersessionid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["points"];
            }
        }
        return NULL;
    }

    public static function setPointsByUserSessionId(int $usersessionid, int $points) {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "UPDATE `session` SET `points` = ? WHERE `id` = ?;"
        );
        return $preparedStatement->execute(array($points, $usersessionid));
    }

    public static function addPointsByUserSessionId(int $usersessionid, int $points) {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "UPDATE `session` SET `points` = `points` + ? WHERE `id` = ?;"
        );
        return $preparedStatement->execute(array($points, $usersessionid));
    }

    public static function isOnlyWatcherByUserSessionId(int $usersessionid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `only_watcher` FROM `session` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($usersessionid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["only_watcher"] == 1;
            }
        }
        return NULL;
    }

    public static function isOnlyWatcherByPhpSessionId(string $phpsessionid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `only_watcher` FROM `session` WHERE `session_id` = ?;"
        );
        if ($preparedStatement->execute(array($phpsessionid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["only_watcher"] == 1;
            }
        }
        return NULL;
    }

    public static function getActualAnswerIsOnionByUserSessionId(int $usersessionid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `actual_answer_is_onion` FROM `session` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($usersessionid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["actual_answer_is_onion"];
            }
        }
        return NULL;
    }

    public static function setActualAnswerIsOnionByUserSessionId(int $usersessionid, int $actualAnswerIsOnion)
    {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "UPDATE `session` SET `actual_answer_is_onion` = ? WHERE `id` = ?;"
        );
        return $preparedStatement->execute(array($actualAnswerIsOnion, $usersessionid));
    }

    public static function removeSessionByPhpSessionId(string $phpsessionid)
    {
        $usersessionid = self::getUserSessionIdByPhpSessionId($phpsessionid);
        if($usersessionid === NULL) {
            return NULL;
        }
        return self::removeSessionByUserSessionId($usersessionid);
    }

    public static function removeSessionByUserSessionId(int $usersessionid)
    {
        SessionHasVotedForGameDataLogic::removeByUserSessionId($usersessionid);
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "DELETE FROM `session` WHERE `id` = ?;"
        );
        return $preparedStatement->execute(array($usersessionid));
    }

    public static function removeSessionsByLobbyId(int $lobbyid)
    {
        $usersessionids = self::getUserSessionIdsByLobbyId($lobbyid);
        foreach ($usersessionids as $usersessionid) {
            self::removeSessionByUserSessionId($usersessionid);
        }
    }

    public static function everyoneHasAnsweredInLobby(int $lobbyid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT COUNT(*) AS anzahl FROM `session` WHERE `in_lobby` = ? AND `actual_answer_is_onion` = 0 AND `only_watcher` = 0;"
        );
        if ($preparedStatement->execute(array($lobbyid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["anzahl"] == 0;
            }
        }
        return FALSE;
    }

    public static function handleDownvoteByPhpSessionId(string $sessionid, int $gid) {

    }

    public static function handleUpvoteByPhpSessionId(string $sessionid, int $gid) {

    }
}