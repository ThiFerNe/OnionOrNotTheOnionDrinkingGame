<?php

namespace logics;

require_once(__DIR__ . "/../integration/DatabaseIntegration.php");

class GameDataLogic
{
    public static function count() {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT COUNT(*) AS anzahl FROM `gamedata`;"
        );
        if($preparedStatement->execute(array())) {
            if($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["anzahl"];
            }
        }
        return NULL;
    }
}