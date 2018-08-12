<?php

namespace logics;

require_once(__DIR__ . "/../helper/LogHelper.php");

use \helper\LogHelper as LOG;


class LobbyUsedGamedataLogic
{
    public static function deleteByLobby(int $id) {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "DELETE FROM `lobby_used_gamedata` WHERE `lid` = ?;"
        );
        return $preparedStatement->execute(array($id));
    }

    public static function getMaxUseCountOrZeroByLobbyId(int $lobbyid) {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT MAX(`use_count`) AS `use_count_max` FROM `lobby_used_gamedata` WHERE `lid` = ?;"
        );
        if($preparedStatement->execute(array($lobbyid))) {
            if($preparedStatement->rowCount() > 0) {
                return $preparedStatement->fetch(\PDO::FETCH_ASSOC)["use_count_max"];
            }
        }
        return 0;
    }

    public static function getUseCountByLobbyIdAndGameDataId(int $lobbyid, $gamedataid) {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `use_count` FROM `lobby_used_gamedata` WHERE `lid` = ? AND `gid` = ?;"
        );
        if($preparedStatement->execute(array($lobbyid, $gamedataid))) {
            if($preparedStatement->rowCount() > 0) {
                return $preparedStatement->fetch(\PDO::FETCH_ASSOC)["use_count"];
            }
        }
        return NULL;
    }

    public static function putByLobby(int $lid, int $gid, int $value) {
        if(self::containsByLobby($lid, $gid)) {
            return self::updateByLobby($lid, $gid, $value);
        } else {
            return self::addByLobby($lid, $gid, $value);
        }
    }

    public static function putIncreaseByLobby(int $lid, int $gid) {
        if(self::containsByLobby($lid, $gid)) {
            LOG::TRACE("Going to increase `" . $gid . "` by lobby `" . $lid . "` by 1");
            return self::increaseByLobby($lid, $gid);
        } else {
            LOG::TRACE("Going to add `" . $gid . "` by lobby `" . $lid . "` with 1");
            return self::addByLobby($lid, $gid, 1);
        }
    }

    public static function containsByLobby(int $lid, int $gid) {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `use_count` FROM `lobby_used_gamedata` WHERE `gid` = ? AND `lid` = ?;"
        );
        if($preparedStatement->execute(array($gid, $lid))) {
            return $preparedStatement->rowCount() > 0;
        }
        return FALSE;
    }

    public static function increaseByLobby(int $lid, int $gid) {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "UPDATE `lobby_used_gamedata` SET `use_count` = `use_count` + 1 WHERE `lid` = ? AND `gid` = ?;"
        );
        return $preparedStatement->execute(array($lid, $gid));
    }

    public static function addByLobby(int $lid, int $gid, int $value) {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "INSERT INTO `lobby_used_gamedata` (`lid`, `gid`, `use_count`) VALUES (?, ?, ?);"
        );
        return $preparedStatement->execute(array($lid, $gid, $value));
    }

    public static function updateByLobby(int $lid, int $gid, int $value) {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "UPDATE `lobby_used_gamedata` SET `use_count` = ? WHERE `lid` = ? AND `gid` = ?;"
        );
        return $preparedStatement->execute(array($value, $lid, $gid));
    }

    public static function count(int $lobbyid) {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT COUNT(*) AS anzahl FROM `lobby_used_gamedata` WHERE `lid` = ?;"
        );
        if($preparedStatement->execute(array($lobbyid))) {
            if($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["anzahl"];
            }
        }
        return NULL;
    }
}