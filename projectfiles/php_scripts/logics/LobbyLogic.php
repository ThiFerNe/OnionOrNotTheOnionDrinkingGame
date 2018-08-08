<?php

namespace logics;

require_once(__DIR__ . "/../integration/DatabaseIntegration.php");

require_once(__DIR__ . "/../helper/LogHelper.php");

require_once("GameDataLogic.php");
require_once("LobbyUsedGamedataLogic.php");

use \helper\LogHelper as LOG;

class LobbyLogic
{

    public const STATE_START = 0;
    public const STATE_QUESTION = 1;
    public const STATE_AFTERMATH = 2;
    public const STATE_END = 3;

    public static function createLobby(int $timerWanted, int $max_questions)
    {
        $lobbycode = self::generateLobbyCode();
        if ($lobbycode === NULL) {
            return NULL;
        }
        $preparedStatement =
            \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
                "INSERT INTO `lobby` (`lobbycode`, `created_on`, `last_active`, `timer`, `max_questions`) VALUES (?, ?, ?, ?, ?);"
            );
        if ($preparedStatement->execute(array($lobbycode, time(), time(), $timerWanted, $max_questions))) {
            return $lobbycode;
        }
        return NULL;
    }

    public static function generateLobbyCode(int $length = 4)
    {
        $pool = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
        $fail_count = 0;
        do {
            $code = "";
            for ($i = 0; $i < $length; $i++) {
                $code .= strval($pool[rand(0, count($pool) - 1)]);
            }
            $fail_count++;
        } while (self::doesLobbyExistByLobbyCode($code) && $fail_count <= 40);
        if ($fail_count > 40) {
            LOG::ERROR("Could not create lobby code after 40 tries!");
            return NULL;
        }
        LOG::TRACE("Created lobby code \"" . $code . "\"!");
        return $code;
    }

    public static function getLobbyIdByLobbyCode(string $lobbycode)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `id` FROM `lobby` WHERE `lobbycode` = ?;"
        );
        if ($preparedStatement->execute(array($lobbycode))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["id"];
            }
        }
        return NULL;
    }

    public static function doesLobbyExistByLobbyCode(string $lobbycode)
    {
        return self::getLobbyIdByLobbyCode($lobbycode) !== NULL;
    }

    public static function getLobbyCodeByLobbyId(int $lobbyid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `lobbycode` FROM `lobby` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($lobbyid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["lobbycode"];
            }
        }
        return NULL;
    }

    public static function getCreatedOnByLobbyId(int $lobbyid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `created_on` FROM `lobby` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($lobbyid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["created_on"];
            }
        }
        return NULL;
    }

    public static function getLastActiveByLobbyId(int $lobbyid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `last_active` FROM `lobby` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($lobbyid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["last_active"];
            }
        }
        return NULL;
    }

    public static function setLastActiveByLobbyId(int $lobbyid, int $lastActive)
    {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "UPDATE `lobby` SET `last_active` = ? WHERE `id` = ?;"
        );
        return $preparedStatement->execute(array($lastActive, $lobbyid));
    }

    public static function getCurrentStateByLobbyId(int $lobbyid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `current_state` FROM `lobby` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($lobbyid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["current_state"];
            }
        }
        return NULL;
    }

    public static function setCurrentStateByLobbyId(int $lobbyid, int $currentState)
    {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "UPDATE `lobby` SET `current_state` = ? WHERE `id` = ?;"
        );
        return $preparedStatement->execute(array($currentState, $lobbyid));
    }

    public static function getCurrentStateOnByLobbyId(int $lobbyid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `current_state_on` FROM `lobby` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($lobbyid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["current_state_on"];
            }
        }
        return NULL;
    }

    public static function setCurrentStateOnByLobbyId(int $lobbyid, int $currentStateOn)
    {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "UPDATE `lobby` SET `current_state_on` = ? WHERE `id` = ?;"
        );
        return $preparedStatement->execute(array($currentStateOn, $lobbyid));
    }

    public static function getCurrentGameDataByLobbyId(int $lobbyid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `current_gamedata` FROM `lobby` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($lobbyid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["current_gamedata"];
            }
        }
        return NULL;
    }

    public static function setCurrentGameDataByLobbyId(int $lobbyid, int $currentGameData)
    {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "UPDATE `lobby` SET `current_gamedata` = ? WHERE `id` = ?;"
        );
        return $preparedStatement->execute(array($currentGameData, $lobbyid));
    }

    public static function unsetCurrentGameDataByLobbyId(int $lobbyid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "UPDATE `lobby` SET `current_gamedata` = NULL WHERE `id` = ?;"
        );
        return $preparedStatement->execute(array($lobbyid));
    }

    public static function getMaxQuestionsByLobbyId(int $lobbyid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `max_questions` FROM `lobby` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($lobbyid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["max_questions"];
            }
        }
        return NULL;
    }

    public static function getCurrentQuestionsByLobbyId(int $lobbyid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `current_questions` FROM `lobby` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($lobbyid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["current_questions"];
            }
        }
        return NULL;
    }

    public static function setCurrentQuestionsByLobbyId(int $lobbyid, int $currentQuestions)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "UPDATE `lobby` SET `current_questions` = ? WHERE `id` = ?;"
        );
        return $preparedStatement->execute(array($currentQuestions, $lobbyid));
    }

    public static function getTimerByLobbyId(int $lobbyid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `timer` FROM `lobby` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($lobbyid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["timer"];
            }
        }
        return NULL;
    }

    public static function getEndRankingByLobbyId(int $lobbyid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `end_ranking` FROM `lobby` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($lobbyid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["end_ranking"];
            }
        }
        return NULL;
    }

    public static function setEndRankingByLobbyId(int $lobbyid, string $endRanking)
    {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "UPDATE `lobby` SET `end_ranking` = ? WHERE `id` = ?;"
        );
        return $preparedStatement->execute(array($endRanking, $lobbyid));
    }

    public static function unsetEndRankingByLobbyId(int $lobbyid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "UPDATE `lobby` SET `end_ranking` = NULL WHERE `id` = ?;"
        );
        return $preparedStatement->execute(array($lobbyid));
    }

    public static function removeLobbyByLobbyId(int $lobbyid)
    {
        LobbyUsedGamedataLogic::deleteByLobby($lobbyid);
        SessionLogic::removeSessionsByLobbyId($lobbyid);
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "DELETE FROM `lobby` WHERE `id` = ?;"
        );
        return $preparedStatement->execute(array($lobbyid));
    }

    public static function fetchNewQuestionForLobbyByLobbyId(int $lobbyid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `id` FROM `gamedata` WHERE `id` NOT IN (SELECT `gid` AS id FROM `lobby_used_gamedata` WHERE `lid` = ?);"
        );
        if ($preparedStatement->execute(array($lobbyid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_rows = $preparedStatement->fetchAll(\PDO::FETCH_ASSOC);
                $selected_question = $fetched_rows[rand(0, count($fetched_rows) - 1)]["id"];

                $preparedStatement1 = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
                    "UPDATE `lobby` SET `current_gamedata` = ?, `current_questions` = `current_questions` + 1 WHERE `id` = ?;"
                );
                $preparedStatement2 = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
                    "INSERT INTO `lobby_used_gamedata` (`gid`, `lid`) VALUES (?, ?);"
                );
                return $preparedStatement1->execute(array($selected_question, $lobbyid)) &&
                    $preparedStatement2->execute(array($selected_question, $lobbyid));
            }
        }
        return FALSE;
    }

    public static function hasLobbyUsedEveryQuestion(int $lobbyid)
    {
        $gameDataCount = GameDataLogic::count();
        if ($gameDataCount === NULL) {
            return TRUE;
        }
        $lobbyUsedGameDataCount = LobbyUsedGamedataLogic::count($lobbyid);
        if ($gameDataCount === NULL) {
            return TRUE;
        }
        return $lobbyUsedGameDataCount >= $gameDataCount;
    }

    public static function hasLobbyUsedEnoughQuestions(int $lobbyid)
    {
        $maxQuestions = self::getMaxQuestionsByLobbyId($lobbyid);
        if ($maxQuestions < 0) {
            return FALSE;
        }
        $currentQuestions = self::getCurrentQuestionsByLobbyId($lobbyid);
        return $currentQuestions >= $maxQuestions;
    }

}