<?php

namespace controllers;

require_once("AbstractController.php");

require_once(__DIR__ . "/../actions/ViewAction.php");

require_once(__DIR__ . "/../helper/VariousHelper.php");
require_once(__DIR__ . "/../helper/LogHelper.php");

require_once(__DIR__ . "/../logics/SessionLogic.php");
require_once(__DIR__ . "/../logics/LobbyLogic.php");
require_once(__DIR__ . "/../logics/FrontEndRequestAcrossMessagesLogic.php");

use \logics\FrontEndRequestAcrossMessagesLogic as FERequestAcrossMLogic;

use \helper\LogHelper as LOG;

class IndexController extends AbstractController
{
    public const PREFIX = "IndexController_";

    public function getUniqueControllerPrefix()
    {
        return self::PREFIX;
    }

    public function action(string $requestSubUri)
    {
        if (\logics\SessionLogic::isUserInLobbyByPhpSessionId(session_id())) {
            LOG::INFO("Current session is in lobby, redirecting");
            return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix(). "game");
        } else {
            if (!empty($_POST["start_or_join_game"])) {
                $error_occurred = FALSE;
                $username_new_game = NULL;
                if (empty($_POST["username_new_game"])) {
                    $error_occurred = TRUE;
                    FERequestAcrossMLogic::appendMessage(
                        FERequestAcrossMLogic::TYPE_ERROR,
                        FERequestAcrossMLogic::MESSAGE_INDEXCONTROLLER_ERROR_USERNAME_MISSING,
                        self::PREFIX
                    );
                } else {
                    $username_new_game = $_POST["username_new_game"];
                    if (strlen($username_new_game) > 64) {
                        $error_occurred = TRUE;
                        FERequestAcrossMLogic::appendMessage(
                            FERequestAcrossMLogic::TYPE_ERROR,
                            FERequestAcrossMLogic::MESSAGE_INDEXCONTROLLER_ERROR_USERNAME_TOO_LONG,
                            self::PREFIX
                        );
                    }
                }
                $just_watch_new_game = isset($_POST["just_watch_new_game"]);
                $create_lobby = NULL;
                $invite_code = NULL;
                if (empty($_POST["invite_code"])) {
                    $create_lobby = TRUE;
                } else {
                    $create_lobby = FALSE;
                    $invite_code = $_POST["invite_code"];
                    if (strlen($invite_code) > 16) {
                        $error_occurred = TRUE;
                        FERequestAcrossMLogic::appendMessage(
                            FERequestAcrossMLogic::TYPE_ERROR,
                            FERequestAcrossMLogic::MESSAGE_INDEXCONTROLLER_ERROR_CODE_TOO_LONG,
                            self::PREFIX
                        );
                    }
                }
                $timer_wanted = NULL;
                if (empty($_POST["timer_wanted"])) {
                    $timer_wanted = -1;
                } else {
                    $timer_wanted = intval($_POST["timer_wanted"]);
                    if ($_POST["timer_wanted"] !== strval($timer_wanted)) {
                        $error_occurred = TRUE;
                        FERequestAcrossMLogic::appendMessage(
                            FERequestAcrossMLogic::TYPE_ERROR,
                            FERequestAcrossMLogic::MESSAGE_INDEXCONTROLLER_ERROR_TIMER_INVALID,
                            self::PREFIX
                        );
                    } else if ($timer_wanted > 600) {
                        $error_occurred = TRUE;
                        FERequestAcrossMLogic::appendMessage(
                            FERequestAcrossMLogic::TYPE_ERROR,
                            FERequestAcrossMLogic::MESSAGE_INDEXCONTROLLER_ERROR_TIMER_TOO_MUCH_TIME,
                            self::PREFIX
                        );
                    }
                }

                if ($error_occurred) {
                    return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix());
                } else {
                    if ($create_lobby) {
                        if(($invite_code = \logics\LobbyLogic::createLobby($timer_wanted))===NULL) {
                            FERequestAcrossMLogic::appendMessage(
                                FERequestAcrossMLogic::TYPE_ERROR,
                                FERequestAcrossMLogic::MESSAGE_INDEXCONTROLLER_ERROR_CREATING_LOBBY_FAILED,
                                self::PREFIX
                            );
                            return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix());
                        }
                    }
                    if(\logics\SessionLogic::createSession(session_id(), $username_new_game, $invite_code, $just_watch_new_game)) {
                        return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix(). "game");
                    } else {
                        FERequestAcrossMLogic::appendMessage(
                            FERequestAcrossMLogic::TYPE_ERROR,
                            FERequestAcrossMLogic::MESSAGE_INDEXCONTROLLER_ERROR_JOINING_LOBBY_FAILED,
                            self::PREFIX
                        );
                        return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix());
                    }
                }
            }

            return new \actions\ViewAction("IndexView");
        }
    }

}