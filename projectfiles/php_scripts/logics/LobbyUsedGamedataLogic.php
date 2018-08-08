<?php

namespace logics;


class LobbyUsedGamedataLogic
{
    public static function deleteByLobby(int $id) {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "DELETE FROM `lobby_used_gamedata` WHERE `lid` = ?;"
        );
        return $preparedStatement->execute(array($id));
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