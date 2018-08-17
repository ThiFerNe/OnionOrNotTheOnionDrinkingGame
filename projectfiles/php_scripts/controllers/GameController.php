<?php

namespace controllers;

require_once("AbstractController.php");

require_once(__DIR__ . "/../actions/ViewAction.php");

require_once(__DIR__ . "/../helper/VariousHelper.php");
require_once(__DIR__ . "/../helper/LogHelper.php");

require_once(__DIR__ . "/../logics/SessionLogic.php");
require_once(__DIR__ . "/../logics/FrontEndRequestAcrossMessagesLogic.php");
require_once(__DIR__ . "/../logics/LobbyUsedGamedataLogic.php");

use \logics\FrontEndRequestAcrossMessagesLogic as FERequestAcrossMLogic;
use \helper\LogHelper as LOG;
use logics\LobbyLogic;

class GameController extends AbstractController
{
    public const PREFIX = "GameController_";

    public const SUFFIX_CURRENT_STATE = "Suffix_Current_State";
    public const SUFFIX_IS_WATCHER = "Is_Watcher";
    public const SUFFIX_SESSION_LAST_ACTIVE = "Session_Last_Active";
    public const SUFFIX_LOBBY_LAST_ACTIVE = "Lobby_Last_Active";
    public const SUFFIX_LOBBY_CODE = "Lobby_Code";
    public const SUFFIX_USER_NAME = "User_Name";
    public const SUFFIX_USERS_LIST = "Users_List";
    public const SUFFIX_QUESTION_HEADLINE = "Question_Headline";
    public const SUFFIX_QUESTION_ID = "Question_Id";
    public const SUFFIX_REMAINING_TIME = "Remaining_Time";
    public const SUFFIX_QUESTION_IS_ONION = "Question_Is_Onion";
    public const SUFFIX_QUESTION_HYPERLINK = "Question_Hyperlink";
    public const SUFFIX_QUESTION_FURTHER_EXPLANATION = "Question_Further_Explanation";
    public const SUFFIX_QUESTION_PICTURE = "Question_Picture";
    public const SUFFIX_QUESTION_USER_ANSWER_WAS_CORRECT = "Question_User_Answer_Was_Correct";
    public const SUFFIX_END_RANKING = "End_Ranking";

    public const USERS_LIST_USERNAME = "Users_List_Username";
    public const USERS_LIST_IS_WATCHING = "Users_List_Is_Watching";
    public const USERS_LIST_POINTS = "Users_List_Points";
    public const USERS_LIST_USERID = "Users_List_UserId";
    public const USERS_LIST_HAS_ANSWERED = "Users_List_Has_Answered";
    public const USERS_LIST_HAS_ANSWERED_CORRECT = "Users_List_Has_Answered_Correct";

    public function getUniqueControllerPrefix()
    {
        return self::PREFIX;
    }

    public function action(string $requestSubUri)
    {
        global $_RESPONSE;
        if (!\logics\SessionLogic::isUserInLobbyByPhpSessionId(session_id())) {
            return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix());
        }

        \logics\SessionLogic::setLastActiveByPhpSessionId(session_id(), time());
        \logics\LobbyLogic::setLastActiveByLobbyId(\logics\SessionLogic::getInLobbyByPhpSessionId(session_id()), time());

        if (\logics\LobbyLogic::getCurrentStateByLobbyId(\logics\SessionLogic::getInLobbyByPhpSessionId(session_id())) == \logics\LobbyLogic::STATE_START &&
            !empty($_POST["start_game"])) {
            $lobbyid = \logics\SessionLogic::getInLobbyByPhpSessionId(session_id());
            if ($lobbyid !== NULL) {
                $current_state = \logics\LobbyLogic::getCurrentStateByLobbyId($lobbyid);
                if (intval($current_state) === \logics\LobbyLogic::STATE_START
                    && \logics\LobbyLogic::getCurrentQuestionsByLobbyId($lobbyid) == 0
                ) {
                    \logics\UpdateLogic::switchToNewQuestionOrEnd($lobbyid);
                    LOG::DEBUG("Successfully switched to new question or end");
                } else {
                    LOG::ERROR("Current state is not the START state or current questions are not zero");
                    FERequestAcrossMLogic::appendMessage(
                        FERequestAcrossMLogic::TYPE_ERROR,
                        FERequestAcrossMLogic::MESSAGE_GAMECONTROLLER_ERROR_WRONG_STATE,
                        self::PREFIX
                    );
                }
            } else {
                LOG::ERROR("Current lobbyid is NULL");
                FERequestAcrossMLogic::appendMessage(
                    FERequestAcrossMLogic::TYPE_ERROR,
                    FERequestAcrossMLogic::MESSAGE_GAMECONTROLLER_ERROR_LOBBYID_NULL,
                    self::PREFIX
                );
            }
            return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix() . "game");
        }

        if (\logics\LobbyLogic::getCurrentStateByLobbyId(
                \logics\SessionLogic::getInLobbyByPhpSessionId(session_id())) == \logics\LobbyLogic::STATE_QUESTION) {
            if ((array_key_exists("set_onion", $_POST) && !empty($_POST["set_onion"]) ||
                (array_key_exists("set_not_the_onion", $_POST) && !empty($_POST["set_not_the_onion"])))) {
                if (!empty($_POST["question_id"])) {
                    if ($_POST["question_id"] === strval(intval($_POST["question_id"]))) {
                        if ($_POST["question_id"] == \logics\LobbyLogic::getCurrentGameDataByLobbyId(\logics\SessionLogic::getInLobbyByPhpSessionId(session_id()))) {
                            if (array_key_exists("set_not_the_onion", $_POST) && !empty($_POST["set_not_the_onion"])) {
                                \logics\SessionLogic::setActualAnswerIsOnionByUserSessionId(
                                    \logics\SessionLogic::getUserSessionIdByPhpSessionId(session_id()), -1);
                            } else if (array_key_exists("set_onion", $_POST) && !empty($_POST["set_onion"])) {
                                \logics\SessionLogic::setActualAnswerIsOnionByUserSessionId(
                                    \logics\SessionLogic::getUserSessionIdByPhpSessionId(session_id()), 1);
                            } else {
                                LOG::ERROR("Both - set_not_the_onion and set_onion - have been set");
                                FERequestAcrossMLogic::appendMessage(
                                    FERequestAcrossMLogic::TYPE_ERROR,
                                    FERequestAcrossMLogic::MESSAGE_GAMECONTROLLER_ERROR_BOTH_OPTIONS_WERE_WANTED,
                                    self::PREFIX
                                );
                            }
                        } else {
                            LOG::ERROR("Invalid question_id (does not exist)");
                            FERequestAcrossMLogic::appendMessage(
                                FERequestAcrossMLogic::TYPE_ERROR,
                                FERequestAcrossMLogic::MESSAGE_GAMECONTROLLER_ERROR_INVALID_QUESTION_ID_NOT_EXISTS,
                                self::PREFIX
                            );
                        }
                    } else {
                        LOG::ERROR("Invalid question_id (no number)");
                        FERequestAcrossMLogic::appendMessage(
                            FERequestAcrossMLogic::TYPE_ERROR,
                            FERequestAcrossMLogic::MESSAGE_GAMECONTROLLER_ERROR_INVALID_QUESTION_ID_NO_NUMBER,
                            self::PREFIX
                        );
                    }
                } else {
                    LOG::ERROR("Missing question_id");
                    FERequestAcrossMLogic::appendMessage(
                        FERequestAcrossMLogic::TYPE_ERROR,
                        FERequestAcrossMLogic::MESSAGE_GAMECONTROLLER_ERROR_MISSING_QUESTION_ID,
                        self::PREFIX
                    );
                }
                return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix() . "game");
            }
        }

        if (\logics\LobbyLogic::getCurrentStateByLobbyId(
                \logics\SessionLogic::getInLobbyByPhpSessionId(session_id())) == \logics\LobbyLogic::STATE_AFTERMATH) {
            if ((array_key_exists("upvote", $_POST) && !empty($_POST["upvote"]) ||
                (array_key_exists("downvote", $_POST) && !empty($_POST["downvote"])))) {
                if (!empty($_POST["question_id"])) {
                    if ($_POST["question_id"] === strval(intval($_POST["question_id"]))) {
                        if ($_POST["question_id"] == \logics\LobbyLogic::getCurrentGameDataByLobbyId(\logics\SessionLogic::getInLobbyByPhpSessionId(session_id()))) {
                            if (array_key_exists("downvote", $_POST) && !empty($_POST["downvote"])) {
                                \logics\SessionLogic::handleDownvoteByPhpSessionId(session_id(), intval($_POST["question_id"]));
                            } else if (array_key_exists("upvote", $_POST) && !empty($_POST["upvote"])) {
                                \logics\SessionLogic::handleUpvoteByPhpSessionId(session_id(), intval($_POST["question_id"]));
                            } else {
                                LOG::ERROR("Both - set_not_the_onion and set_onion - have been set");
                                FERequestAcrossMLogic::appendMessage(
                                    FERequestAcrossMLogic::TYPE_ERROR,
                                    FERequestAcrossMLogic::MESSAGE_GAMECONTROLLER_ERROR_BOTH_OPTIONS_WERE_WANTED,
                                    self::PREFIX
                                );
                            }
                        } else {
                            LOG::ERROR("Invalid question_id (does not exist)");
                            FERequestAcrossMLogic::appendMessage(
                                FERequestAcrossMLogic::TYPE_ERROR,
                                FERequestAcrossMLogic::MESSAGE_GAMECONTROLLER_ERROR_INVALID_QUESTION_ID_NOT_EXISTS,
                                self::PREFIX
                            );
                        }
                    } else {
                        LOG::ERROR("Invalid question_id (no number)");
                        FERequestAcrossMLogic::appendMessage(
                            FERequestAcrossMLogic::TYPE_ERROR,
                            FERequestAcrossMLogic::MESSAGE_GAMECONTROLLER_ERROR_INVALID_QUESTION_ID_NO_NUMBER,
                            self::PREFIX
                        );
                    }
                } else {
                    LOG::ERROR("Missing question_id");
                    FERequestAcrossMLogic::appendMessage(
                        FERequestAcrossMLogic::TYPE_ERROR,
                        FERequestAcrossMLogic::MESSAGE_GAMECONTROLLER_ERROR_MISSING_QUESTION_ID,
                        self::PREFIX
                    );
                }
                return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix() . "game");
            }
            if (array_key_exists("skip", $_GET) && !empty($_GET["skip"])) {
                \logics\SessionLogic::setWantsToSkipAftermathByPhpSessionId(session_id(), TRUE);
                return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix() . "game");
            }
        }

        if (\logics\LobbyLogic::getCurrentStateByLobbyId(\logics\SessionLogic::getInLobbyByPhpSessionId(session_id())) == \logics\LobbyLogic::STATE_END &&
            !empty($_POST["next_round"])) {
            $lobbyid = \logics\SessionLogic::getInLobbyByPhpSessionId(session_id());

            // Clear Current GameData and Ranking
            \logics\LobbyLogic::unsetCurrentGameDataByLobbyId($lobbyid);
            \logics\LobbyLogic::unsetEndRankingByLobbyId($lobbyid);
            \logics\LobbyLogic::setCurrentQuestionsByLobbyId($lobbyid, 0);
            \logics\LobbyLogic::setLastTimeMaxQuestionUseCountByLobbyId($lobbyid, \logics\LobbyUsedGamedataLogic::getMaxUseCountOrZeroByLobbyId($lobbyid));

            // Clear Lobby Used GameData
            //UPDATE: NOT NEEDED, BECAUSE THE DATA WILL BE USED BY THE NEXT ROUND
            //\logics\LobbyUsedGamedataLogic::deleteByLobby($lobbyid);

            // Clear data from Sessions
            $users_in_lobby = \logics\SessionLogic::getUserSessionIdsByLobbyId($lobbyid);
            foreach ($users_in_lobby as $user_in_lobby) {
                \logics\SessionLogic::setPointsByUserSessionId($user_in_lobby, 0);
                \logics\SessionLogic::setActualAnswerIsOnionByUserSessionId($user_in_lobby, 0);
            }

            // SWITCH TO START
            \logics\LobbyLogic::setCurrentStateByLobbyId($lobbyid, \logics\LobbyLogic::STATE_START);
            \logics\LobbyLogic::setCurrentStateOnByLobbyId($lobbyid, time());

            // REDIRECT
            return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix() . "game");
        }

        // If the request did not be a GET, redirect!
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix() . "game");
        }

        $lobbyid = \logics\SessionLogic::getInLobbyByPhpSessionId(session_id());
        if ($lobbyid !== NULL) {
            $_RESPONSE[self::PREFIX . self::SUFFIX_CURRENT_STATE] = \logics\LobbyLogic::getCurrentStateByLobbyId($lobbyid);
            $_RESPONSE[self::PREFIX . self::SUFFIX_IS_WATCHER] = \logics\SessionLogic::isOnlyWatcherByPhpSessionId(session_id());
            $_RESPONSE[self::PREFIX . self::SUFFIX_SESSION_LAST_ACTIVE] = \logics\SessionLogic::getLastActiveByPhpSessionId(session_id());
            $_RESPONSE[self::PREFIX . self::SUFFIX_LOBBY_LAST_ACTIVE] = \logics\LobbyLogic::getLastActiveByLobbyId($lobbyid);
            $_RESPONSE[self::PREFIX . self::SUFFIX_LOBBY_CODE] = \logics\LobbyLogic::getLobbyCodeByLobbyId($lobbyid);
            $_RESPONSE[self::PREFIX . self::SUFFIX_USER_NAME] = \logics\SessionLogic::getUsernameByPhpSessionId(session_id());
            $_RESPONSE[self::PREFIX . self::SUFFIX_USERS_LIST] = array();
            $users = \logics\SessionLogic::getUserSessionIdsByLobbyId($lobbyid);
            foreach ($users as $user) {
                $user_name = \logics\SessionLogic::getUsernameByUserSessionId($user);
                $user_is_watching = \logics\SessionLogic::isOnlyWatcherByUserSessionId($user);
                $points = \logics\SessionLogic::getPointsByUserSessionId($user);
                $has_answered = \logics\SessionLogic::getActualAnswerIsOnionByUserSessionId($user) != 0;
                $current_game_data = \logics\LobbyLogic::getCurrentGameDataByLobbyId($lobbyid);
                if ($current_game_data !== NULL) {
                    $current_is_onion = \logics\GameDataLogic::isOnionByGameDataId($current_game_data);
                    $answer_of_user = \logics\SessionLogic::getActualAnswerIsOnionByUserSessionId($user);
                    $has_answered_correct =
                        (($current_is_onion == TRUE && $answer_of_user == 1) || ($current_is_onion == FALSE && $answer_of_user == -1));
                } else {
                    $has_answered_correct = FALSE;
                }
                array_push($_RESPONSE[self::PREFIX . self::SUFFIX_USERS_LIST], array(
                    self::USERS_LIST_USERNAME => $user_name,
                    self::USERS_LIST_IS_WATCHING => $user_is_watching,
                    self::USERS_LIST_POINTS => $points,
                    self::USERS_LIST_USERID => $user,
                    self::USERS_LIST_HAS_ANSWERED => $has_answered,
                    self::USERS_LIST_HAS_ANSWERED_CORRECT => $has_answered_correct
                ));
            }

            switch (\logics\LobbyLogic::getCurrentStateByLobbyId($lobbyid)) {
                case \logics\LobbyLogic::STATE_START:
                    break;
                case \logics\LobbyLogic::STATE_QUESTION:
                    $_RESPONSE[self::PREFIX . self::SUFFIX_QUESTION_HEADLINE] =
                        \logics\GameDataLogic::getHeadlineByGameDataId(\logics\LobbyLogic::getCurrentGameDataByLobbyId($lobbyid));
                    $_RESPONSE[self::PREFIX . self::SUFFIX_QUESTION_ID] = \logics\LobbyLogic::getCurrentGameDataByLobbyId($lobbyid);
                    if (\logics\LobbyLogic::getTimerByLobbyId($lobbyid) >= 0) {
                        $_RESPONSE[self::PREFIX . self::SUFFIX_REMAINING_TIME] =
                            (\logics\LobbyLogic::getCurrentStateOnByLobbyId($lobbyid) + \logics\LobbyLogic::getTimerByLobbyId($lobbyid)) - time();
                        if ($_RESPONSE[self::PREFIX . self::SUFFIX_REMAINING_TIME] < 0) {
                            $_RESPONSE[self::PREFIX . self::SUFFIX_REMAINING_TIME] = 0;
                        }
                    }
                    break;
                case \logics\LobbyLogic::STATE_AFTERMATH:
                    $current_game_data = \logics\LobbyLogic::getCurrentGameDataByLobbyId($lobbyid);
                    $_RESPONSE[self::PREFIX . self::SUFFIX_QUESTION_HEADLINE] =
                        \logics\GameDataLogic::getHeadlineByGameDataId($current_game_data);
                    $_RESPONSE[self::PREFIX . self::SUFFIX_QUESTION_ID] = \logics\LobbyLogic::getCurrentGameDataByLobbyId($lobbyid);
                    $_RESPONSE[self::PREFIX . self::SUFFIX_QUESTION_IS_ONION] =
                        \logics\GameDataLogic::isOnionByGameDataId($current_game_data);
                    $_RESPONSE[self::PREFIX . self::SUFFIX_QUESTION_HYPERLINK] =
                        \logics\GameDataLogic::getHyperlinkByGameDataId($current_game_data);
                    $_RESPONSE[self::PREFIX . self::SUFFIX_QUESTION_FURTHER_EXPLANATION] =
                        \logics\GameDataLogic::getFurtherExplanationByGameDataId($current_game_data);
                    $_RESPONSE[self::PREFIX . self::SUFFIX_QUESTION_PICTURE] =
                        \logics\GameDataLogic::getPictureByGameDataId($current_game_data);
                    $_RESPONSE[self::PREFIX . self::SUFFIX_REMAINING_TIME] =
                        (\logics\LobbyLogic::getCurrentStateOnByLobbyId($lobbyid) + \logics\UpdateLogic::AFTERMATH_TIME) - time();
                    $current_is_onion = $_RESPONSE[self::PREFIX . self::SUFFIX_QUESTION_IS_ONION];
                    $answer_of_user = \logics\SessionLogic::getActualAnswerIsOnionByUserSessionId(\logics\SessionLogic::getUserSessionIdByPhpSessionId(session_id()));
                    $_RESPONSE[self::PREFIX . self::SUFFIX_QUESTION_USER_ANSWER_WAS_CORRECT] =
                        (($current_is_onion == TRUE && $answer_of_user == 1) || ($current_is_onion == FALSE && $answer_of_user == -1));
                    if ($_RESPONSE[self::PREFIX . self::SUFFIX_REMAINING_TIME] < 0) {
                        $_RESPONSE[self::PREFIX . self::SUFFIX_REMAINING_TIME] = 0;
                    }
                    break;
                case \logics\LobbyLogic::STATE_END:
                    $endRanking = \logics\LobbyLogic::getEndRankingByLobbyId($lobbyid);
                    if ($endRanking !== NULL) {
                        $_RESPONSE[self::PREFIX . self::SUFFIX_END_RANKING] = json_decode($endRanking, TRUE);
                    } else {
                        $_RESPONSE[self::PREFIX . self::SUFFIX_END_RANKING] = array();
                    }
                    break;
            }

            return new \actions\ViewAction("GameView");
        } else {
            LOG::ERROR("Current lobbyid is NULL");
            FERequestAcrossMLogic::appendMessage(
                FERequestAcrossMLogic::TYPE_ERROR,
                FERequestAcrossMLogic::MESSAGE_GAMECONTROLLER_ERROR_LOBBYID_NULL,
                self::PREFIX
            );
        }
        return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix() . "exit");
    }

}