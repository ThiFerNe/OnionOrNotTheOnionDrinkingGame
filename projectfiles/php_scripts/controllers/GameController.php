<?php

namespace controllers;

require_once("AbstractController.php");

require_once(__DIR__ . "/../actions/ViewAction.php");

require_once(__DIR__ . "/../helper/VariousHelper.php");
require_once(__DIR__ . "/../helper/LogHelper.php");

require_once(__DIR__ . "/../logics/SessionLogic.php");
require_once(__DIR__ . "/../logics/FrontEndRequestAcrossMessagesLogic.php");

use \logics\FrontEndRequestAcrossMessagesLogic as FERequestAcrossMLogic;
use \helper\LogHelper as LOG;

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

        if (!empty($_POST["start_game"])) {
            $lobbyid = \logics\SessionLogic::getInLobbyByPhpSessionId(session_id());
            if ($lobbyid !== NULL) {
                $current_state = \logics\LobbyLogic::getCurrentStateByLobbyId($lobbyid);
                if (intval($current_state) === \logics\LobbyLogic::STATE_START) {
                    \logics\UpdateLogic::switchToNewQuestionOrEnd($lobbyid);
                    LOG::DEBUG("Successfully switched to new question or end");
                } else {
                    LOG::ERROR("Current state is not the START state");
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
            foreach($users as $user) {
                $user_name = \logics\SessionLogic::getUsernameByUserSessionId($user);
                $user_is_watching = \logics\SessionLogic::isOnlyWatcherByUserSessionId($user);
                // TODO:
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