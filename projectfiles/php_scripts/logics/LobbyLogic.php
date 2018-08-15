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

    public static function createLobby(int $timerWanted, int $max_questions, $minimum_score)
    {
        LOG::INFO("Creating new lobby with timerWanted:" . $timerWanted . "; max_questions: " . $max_questions . "; minimum_score: " . $minimum_score);
        $lobbycode = self::generateLobbyCode();
        if ($lobbycode === NULL) {
            return NULL;
        }
        $preparedStatement =
            \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
                "INSERT INTO `lobby` (`lobbycode`, `created_on`, `last_active`, `timer`, `max_questions`, `minimum_score`, `last_time_max_question_use_count`) VALUES (?, ?, ?, ?, ?, ?, ?);"
            );
        if ($preparedStatement->execute(array($lobbycode, time(), time(), $timerWanted, $max_questions, $minimum_score, 0))) {
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

    public static function getLastTimeMaxQuestionUseCountByLobbyId(int $lobbyid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `last_time_max_question_use_count` FROM `lobby` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($lobbyid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["last_time_max_question_use_count"];
            }
        }
        return NULL;
    }

    public static function setLastTimeMaxQuestionUseCountByLobbyId(int $lobbyid, int $lastTimeMaxQUestionUseCount)
    {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "UPDATE `lobby` SET `last_time_max_question_use_count` = ? WHERE `id` = ?;"
        );
        return $preparedStatement->execute(array($lastTimeMaxQUestionUseCount, $lobbyid));
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

    public static function getMinimumScoreByLobbyId(int $lobbyid)
    {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `minimum_score` FROM `lobby` WHERE `id` = ?;"
        );
        if ($preparedStatement->execute(array($lobbyid))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["minimum_score"];
            }
        }
        return NULL;
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
        $minimum_score = self::getMinimumScoreByLobbyId($lobbyid);
        if ($minimum_score !== NULL) {
            if (self::hasLobbyUsedEveryQuestion($lobbyid)) {
                $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
                    "SELECT `id` FROM `gamedata` WHERE (`upvotes` - `downvotes`) >= ?;"
                );
                $data = array(intval($minimum_score));
            } else {
                $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
                    "SELECT `id` FROM `gamedata` WHERE (`upvotes` - `downvotes`) >= ? AND `id` NOT IN (" .
                    "SELECT `gid` AS id FROM `lobby_used_gamedata` WHERE `lid` = ? AND `use_count` = (" .
                    "SELECT MAX(`use_count`) FROM `lobby_used_gamedata` WHERE `lid` = ?" .
                    ")" . ");"
                );
                $data = array(intval($minimum_score), $lobbyid, $lobbyid);
            }
        } else {
            if (self::hasLobbyUsedEveryQuestion($lobbyid)) {
                $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
                    "SELECT `id` FROM `gamedata` WHERE `id`;"
                );
                $data = array();
            } else {
                $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
                    "SELECT `id` FROM `gamedata` WHERE `id` NOT IN (" .
                    "SELECT `gid` AS id FROM `lobby_used_gamedata` WHERE `lid` = ? AND `use_count` = (" .
                    "SELECT MAX(`use_count`) FROM `lobby_used_gamedata` WHERE `lid` = ?" .
                    ")" . ");");
                $data = array($lobbyid, $lobbyid);
            }
        }
        if ($preparedStatement->execute($data)) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_rows = $preparedStatement->fetchAll(\PDO::FETCH_ASSOC);
                $selected_question = $fetched_rows[rand(0, count($fetched_rows) - 1)]["id"];

                $preparedStatement1 = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
                    "UPDATE `lobby` SET `current_gamedata` = ?, `current_questions` = `current_questions` + 1 WHERE `id` = ?;"
                );
                if ($preparedStatement1->execute(array($selected_question, $lobbyid))) {
                    LOG::TRACE("Before Use Count: " . \logics\LobbyUsedGamedataLogic::getUseCountByLobbyIdAndGameDataId($lobbyid, $selected_question));
                    $result = \logics\LobbyUsedGamedataLogic::putIncreaseByLobby($lobbyid, $selected_question);
                    LOG::TRACE("After Use Count: " . \logics\LobbyUsedGamedataLogic::getUseCountByLobbyIdAndGameDataId($lobbyid, $selected_question));
                    return $result;
                }
            }
        }
        return FALSE;
    }

    public static function hasLobbyUsedEveryQuestion(int $lobbyid)
    {
        $minimum_score = self::getMinimumScoreByLobbyId($lobbyid);
        if ($minimum_score !== NULL) {
            $gameDataCount = GameDataLogic::countWithMinimumScore(intval($minimum_score));
        } else {
            $gameDataCount = GameDataLogic::count();
        }
        if ($gameDataCount === NULL) {
            return TRUE;
        }
        $lobbyUsedGameDataCount = LobbyUsedGamedataLogic::count($lobbyid);
        if ($gameDataCount === NULL) {
            return TRUE;
        }
        if ($lobbyUsedGameDataCount < $gameDataCount) {
            return FALSE;
        }
        // Otherwise check the use count
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT DISTINCT `use_count` FROM `lobby_used_gamedata` WHERE `lid` = ?;"
        );
        if ($preparedStatement->execute(array($lobbyid))) {
            // If we get exactly one row which has been grouped, we can say, that every question has been used
            return $preparedStatement->rowCount() == 1;
        }
        return FALSE;
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

    public static function getMaximumQuestionsInThisPlaythrough(int $lobbyid)
    {
        $maxQuestions = self::getMaxQuestionsByLobbyId($lobbyid);
        if ($maxQuestions < 0) {
            $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
                "SELECT COUNT(*) AS `count` FROM `gamedata` WHERE `id` NOT IN (" .
                "SELECT `gid` FROM `lobby_used_gamedata` WHERE `lid` = ? AND `use_count` = (" .
                "SELECT DISTINCT MIN(`use_count`) FROM `lobby_used_gamedata` WHERE `lid` = ?));"
            );
            if ($preparedStatement->execute(array($lobbyid, $lobbyid))) {
                if ($preparedStatement->rowCount() > 0) {
                    return $preparedStatement->fetch(\PDO::FETCH_ASSOC)["count"] + self::getCurrentQuestionsByLobbyId($lobbyid);
                }
            }
        }
        return $maxQuestions;
    }

}