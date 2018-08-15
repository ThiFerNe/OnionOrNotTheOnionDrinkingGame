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
    public const AFTERMATH_TIME = 30;

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
        return $preparedStatement->execute(array(time() + 1));
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
                    LOG::TRACE("Removing Session \"" . $fetched_row["id"] . "\" because of inactivity");
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
                    LOG::TRACE("Removing Lobby \"" . $fetched_row["id"] . "\" because of inactivity");
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
            if (count(SessionLogic::getUserSessionIdsByLobbyId($current_id)) > 0) {
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
                            self::switchToAftermath($current_id);
                        }
                        break;
                    case LobbyLogic::STATE_END:
                        // Do nothing - Someone has to EXIT or NEW ROUND - That will trigger the next step
                        break;
                    case LobbyLogic::STATE_AFTERMATH:
                        // Wait a specific time and then switch either to next question or END
                        if(!self::everyoneInTheLobbyWantsToSkipAftermath($current_id)) {
                            if (($current_state_on + self::AFTERMATH_TIME) > time()) {
                                break;
                            }
                        }
                    // NO BREAK; HERE! IT HAS TO FALL THROUGH TO THE DEFAULT!
                    default:
                        self::switchToNewQuestionOrEnd($current_id);
                }
            } else if (LobbyLogic::getCurrentStateByLobbyId($current_id) != LobbyLogic::STATE_END) {
                LOG::DEBUG("No one is inside Lobby " . strval($current_id) . ", switching to END");
                LobbyLogic::setCurrentStateByLobbyId($current_id, LobbyLogic::STATE_END);
                LobbyLogic::setCurrentStateOnByLobbyId($current_id, time());
            }
        }
        return TRUE;
    }

    public static function everyoneInTheLobbyWantsToSkipAftermath(int $lobbyid) {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `id` FROM `session` WHERE `in_lobby` = ? AND `wants_to_skip_aftermath` = 0;"
        );
        if($preparedStatement->execute(array($lobbyid))) {
            return $preparedStatement->rowCount() == 0;
        }
        return FALSE;
    }

    public static function switchToAftermath(int $lobbyid)
    {
        LOG::DEBUG("Going to switch to aftermath for lobby " . strval($lobbyid));
        // GIVE POINTS TO EVERYONE
        $currentIsOnion = GameDataLogic::isOnionByGameDataId(LobbyLogic::getCurrentGameDataByLobbyId($lobbyid));
        LOG::DEBUG("In lobby " . strval($lobbyid) . " the curren is " . ($currentIsOnion ? " " : "not ") . "onion");
        $userids = SessionLogic::getUserSessionIdsByLobbyId($lobbyid);
        if ($userids !== NULL) {
            $last_right_user = NULL;
            $user_count = 0;
            $right_count = 0;
            foreach ($userids as $userid) {
                if (SessionLogic::isOnlyWatcherByUserSessionId($userid)) {
                    continue;
                }
                $user_count++;
                $actual_answer = SessionLogic::getActualAnswerIsOnionByUserSessionId($userid);
                if (
                    ($actual_answer == -1 && $currentIsOnion == FALSE) ||
                    ($actual_answer == 1 && $currentIsOnion == TRUE)
                ) {
                    LOG::DEBUG($userid . " got it right with answer " . $actual_answer . " in lobby " . strval($lobbyid));
                    SessionLogic::addPointsByUserSessionId($userid, 100);
                    $right_count++;
                    $last_right_user = $userid;
                } else {
                    LOG::DEBUG($userid . " did not get it right with answer " . $actual_answer . " in lobby " . strval($lobbyid));
                }
            }
            if ($right_count == 1 && $user_count > 1) {
                // Reward best user extra
                LOG::DEBUG($last_right_user . " was the only one who got it right in lobby " . strval($lobbyid));
                SessionLogic::addPointsByUserSessionId($last_right_user, 50);
            }

            $points_array = array();
            foreach ($userids as $userid) {
                if (SessionLogic::isOnlyWatcherByUserSessionId($userid)) {
                    continue;
                }
                array_push($points_array, SessionLogic::getPointsByUserSessionId($userid));
            }
            $points_array = array_unique($points_array, SORT_NUMERIC);
            sort($points_array, SORT_NUMERIC);
            $current_rank = 1;
            $current_rank_counter = 1;
            $ranking = array();
            for ($i = count($points_array) - 1; $i >= 0; $i--) {
                $current_points = $points_array[$i];
                $users_with_that_points = 0;
                foreach ($userids as $userid) {
                    if (SessionLogic::isOnlyWatcherByUserSessionId($userid)) {
                        continue;
                    }
                    if ($current_points == SessionLogic::getPointsByUserSessionId($userid)) {
                        $ranking[$current_rank_counter] = array(
                            "name" => SessionLogic::getUsernameByUserSessionId($userid),
                            "rank" => $current_rank,
                            "points" => $current_points
                        );
                        $users_with_that_points++;
                        $current_rank_counter++;
                    }
                }
                $current_rank += $users_with_that_points;
            }
            LobbyLogic::setEndRankingByLobbyId($lobbyid, json_encode($ranking));
        }
        // CHANGE STATE
        LobbyLogic::setCurrentStateByLobbyId($lobbyid, LobbyLogic::STATE_AFTERMATH);
        LobbyLogic::setCurrentStateOnByLobbyId($lobbyid, time());
    }

    public static function switchToNewQuestionOrEnd(int $lobbyid)
    {
        LOG::DEBUG("Going to switch to new question for lobby " . strval($lobbyid));
        // RESET ANSWERS
        $userids = SessionLogic::getUserSessionIdsByLobbyId($lobbyid);
        if ($userids !== NULL) {
            foreach ($userids as $userid) {
                SessionLogic::setActualAnswerIsOnionByUserSessionId($userid, 0);
                SessionLogic::setWantsToSkipAftermathByUserSessionId($userid, FALSE);
            }
        }
        // Here no time will be waited but the next question or END will be switched to
        LOG::TRACE("Standing in front of heavy decision");
        LOG::TRACE("> Has Lobby Used Every Question: " . (LobbyLogic::hasLobbyUsedEveryQuestion($lobbyid) === TRUE ? "true" : "false"));
        LOG::TRACE("> Last Time Lobby Max Question Use Count: " . \logics\LobbyLogic::getLastTimeMaxQuestionUseCountByLobbyId($lobbyid));
        LOG::TRACE("> Max Lobby Used Gamedata Use Count: " . \logics\LobbyUsedGamedataLogic::getMaxUseCountOrZeroByLobbyId($lobbyid));
        LOG::TRACE("> Has Lobby Used Enough Questions: " . (LobbyLogic::hasLobbyUsedEnoughQuestions($lobbyid) === TRUE ? "true" : "false"));
        if ((LobbyLogic::hasLobbyUsedEveryQuestion($lobbyid) && (
                    \logics\LobbyLogic::getLastTimeMaxQuestionUseCountByLobbyId($lobbyid) != \logics\LobbyUsedGamedataLogic::getMaxUseCountOrZeroByLobbyId($lobbyid)
                )) || LobbyLogic::hasLobbyUsedEnoughQuestions($lobbyid)) {
            LOG::DEBUG("Every question at lobby " . strval($lobbyid) . " has been answered");
            LobbyLogic::setCurrentStateByLobbyId($lobbyid, LobbyLogic::STATE_END);
            LobbyLogic::setCurrentStateOnByLobbyId($lobbyid, time());
        } else {
            LOG::DEBUG("NOT every question at lobby " . strval($lobbyid) . " has been answered");
            if(!LobbyLogic::fetchNewQuestionForLobbyByLobbyId($lobbyid)) {
                LOG::ERROR("Something failed while fetching a new question!");
            }
            LobbyLogic::setCurrentStateByLobbyId($lobbyid, LobbyLogic::STATE_QUESTION);
            LobbyLogic::setCurrentStateOnByLobbyId($lobbyid, time());
        }
    }
}