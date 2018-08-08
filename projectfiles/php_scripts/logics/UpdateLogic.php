<?php

namespace logics;

require_once(__DIR__ . "/../integration/DatabaseIntegration.php");

require_once(__DIR__ . "/../logics/SessionLogic.php");
require_once(__DIR__ . "/../logics/LobbyLogic.php");
require_once(__DIR__ . "/../logics/SessionHasVotedForGameDataLogic.php");

require_once(__DIR__ . "/../helper/LogHelper.php");

use \helper\LogHelper as LOG;

class UpdateLogic
{
    public static function updateAll()
    {
        // 1. Set Cooldown
        if (self::initializeCooldown() === FALSE) {
            return;
        }
        // 2. Remove unactive users
        self::removeUnactiveUsers();
        // 3. Remove unactive lobbies
        self::removeUnactiveLobbies();
        // 4. Update remaining lobbies
        self::updateLobbies();
    }

    public static function initializeCooldown()
    {
        $exists = FALSE;
        // Check actual cooldown
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `config_value` FROM `onion_config` WHERE `config_key` = 'update_cooldown';"
        );
        if ($preparedStatement->execute(array())) {
            if ($preparedStatement->rowCount() > 0) {
                $exists = TRUE;
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                if (intval($fetched_row["config_value"]) > time()) {
                    return FALSE;
                }
            }
        } else {
            return FALSE;
        }
        // If ok, set new cooldown
        if ($exists) {
            $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
                "UPDATE `onion_config` SET `config_value` = ? WHERE `config_key` = 'update_cooldown';"
            );
        } else {
            $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
                "INSERT INTO `onion_config` (`config_key`, `config_value`) VALUES ('update_cooldown', ?);"
            );
        }
        return $preparedStatement->execute(array(time() + 5));
    }

    public static function removeUnactiveUsers()
    {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "SELECT `id` FROM `session` WHERE `last_active` < ?;"
        );
        if ($preparedStatement->execute(array(time() - (15 * 60)))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_all = $preparedStatement->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($fetched_all as $fetched_row) {
                    SessionLogic::removeSessionByUserSessionId($fetched_row["id"]);
                }
            }
        }
        return FALSE;
    }

    public static function removeUnactiveLobbies()
    {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "SELECT `id` FROM `lobby` WHERE `last_active` < ?;"
        );
        if ($preparedStatement->execute(array(time() - (15 * 60)))) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_all = $preparedStatement->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($fetched_all as $fetched_row) {
                    LobbyLogic::removeLobbyByLobbyId($fetched_row["id"]);
                }
            }
        }
    }

    public static function updateLobbies()
    {
        // Get all lobbies
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `id`, `current_state`, `current_state_on`, `timer` FROM `lobby`;"
        );
        $fetched_lobbies = NULL;
        if ($preparedStatement->execute(array())) {
            if ($preparedStatement->rowCount() > 0) {
                $fetched_lobbies = $preparedStatement->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
        // Process each lobby
        foreach ($fetched_lobbies as $fetched_lobby) {
            $current_state = $fetched_lobby["current_state"];
            $current_id = $fetched_lobby["id"];
            $current_state_on = $fetched_lobby["current_state_on"];
            $timer = $fetched_lobby["timer"];
            switch ($current_state) {
                case LobbyLogic::STATE_START:
                    // Do nothing - Someone has to press START - That will trigger the next step
                    break;
                case LobbyLogic::STATE_QUESTION:
                    // Wait until everyone has answered or the timer has run out
                    // Then switch to Aftermath
                    if (
                        (SessionLogic::everyoneHasAnsweredInLobby($current_id)) ||
                        ($timer >= 0 && ($current_state_on + $timer) < time())
                    ) {
                        LobbyLogic::setCurrentStateByLobbyId($current_id, LobbyLogic::STATE_AFTERMATH);
                    }
                    break;
                case LobbyLogic::STATE_END:
                    // Do nothing - Someone has to EXIT or NEW ROUND - That will trigger the next step
                    break;
                case LobbyLogic::STATE_AFTERMATH:
                    // Wait a specific time and then switch either to next question or END
                    if (($current_state_on + 30) > time()) {
                        break;
                    }
                // NO BREAK; HERE! IT HAS TO FALL THROUGH TO THE DEFAULT!
                default:
                    self::switchToNewQuestionOrEnd($current_id);
            }
        }
        return TRUE;
    }


    public static function switchToNewQuestionOrEnd(int $lobbyid) {
        LOG::DEBUG("Going to switch to new question for lobby " . strval($lobbyid));
        // Here no time will be waited but the next question or END will be switched to
        if (LobbyLogic::hasLobbyUsedEveryQuestion($lobbyid)) {
            LOG::DEBUG("Every question at lobby " . strval($lobbyid) . " has been answered");
            LobbyLogic::setCurrentStateByLobbyId($lobbyid, LobbyLogic::STATE_END);
        } else {
            LOG::DEBUG("NOT every question at lobby " . strval($lobbyid) . " has been answered");
            LobbyLogic::fetchNewQuestionForLobbyByLobbyId($lobbyid);
            LobbyLogic::setCurrentStateByLobbyId($lobbyid, LobbyLogic::STATE_QUESTION);
        }
    }
}