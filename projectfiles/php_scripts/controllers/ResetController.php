<?php

namespace controllers;

require_once("AbstractController.php");

require_once(__DIR__ . "/../actions/ViewAction.php");
require_once(__DIR__ . "/../actions/RedirectAction.php");

require_once(__DIR__ . "/../helper/VariousHelper.php");
require_once(__DIR__ . "/../helper/LogHelper.php");

require_once(__DIR__ . "/../logics/CSRFLogic.php");
require_once(__DIR__ . "/../logics/FrontEndRequestAcrossMessagesLogic.php");

use \logics\FrontEndRequestAcrossMessagesLogic as FERequestAAcrossMLogic;
use \helper\LogHelper as LOG;

class ResetController extends AbstractController
{
    public const PREFIX = "ResetController_";

    public const SUFFIX_CSRF_VALUE = "CSRF_Value";

    public function getUniqueControllerPrefix()
    {
        return self::PREFIX;
    }

    public function action(string $requestSubUri)
    {
        global $_RESPONSE;

        if (\logics\ResetLogic::isWebsiteBeenSetup()) {
            LOG::ERROR("Reset has been called, but the site has been set up!");
            return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix());
        }

        LOG::TRACE("Going into Reset mode...");

        if (!empty($_POST["reset"])) {
            if (\logics\CSRFLogic::check($_POST["csrf_value"], self::PREFIX)) {
                \logics\CSRFLogic::reset(self::PREFIX);
                // Check values
                $post_values_correct = TRUE;

                $database_server_host = NULL;
                if(!empty($_POST["database_server_host"])) {
                    $database_server_host = $_POST["database_server_host"];
                } else {
                    $post_values_correct = FALSE;
                    FERequestAAcrossMLogic::appendMessage(
                        FERequestAAcrossMLogic::TYPE_ERROR,
                        FERequestAAcrossMLogic::MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBHOST,
                        self::PREFIX);
                }

                $database_server_port = NULL;
                if(!empty($_POST["database_server_port"])) {
                    $database_server_port = $_POST["database_server_port"];
                    if($database_server_port !== strval(intval($database_server_port))) {
                        $post_values_correct = FALSE;
                        FERequestAAcrossMLogic::appendMessage(
                            FERequestAAcrossMLogic::TYPE_ERROR,
                            FERequestAAcrossMLogic::MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_INVALID_DBPORT,
                            self::PREFIX);
                    }
                } else {
                    $post_values_correct = FALSE;
                    FERequestAAcrossMLogic::appendMessage(
                        FERequestAAcrossMLogic::TYPE_ERROR,
                        FERequestAAcrossMLogic::MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBPORT,
                        self::PREFIX);
                }

                $database_name = NULL;
                if(!empty($_POST["database_name"])) {
                    $database_name = $_POST["database_name"];
                } else {
                    $post_values_correct = FALSE;
                    FERequestAAcrossMLogic::appendMessage(
                        FERequestAAcrossMLogic::TYPE_ERROR,
                        FERequestAAcrossMLogic::MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBNAME,
                        self::PREFIX);
                }

                $database_username = NULL;
                if(!empty($_POST["database_username"])) {
                    $database_username = $_POST["database_username"];
                } else {
                    $post_values_correct = FALSE;
                    FERequestAAcrossMLogic::appendMessage(
                        FERequestAAcrossMLogic::TYPE_ERROR,
                        FERequestAAcrossMLogic::MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBUSERNAME,
                        self::PREFIX);
                }

                $database_password = "";
                if(isset($_POST["database_password"])) {
                    $database_password = $_POST["database_password"];
                }

                $database_read_only_username = NULL;
                $database_read_only_password = NULL;
                if(!empty($_POST["database_read_only_username"]) || $database_name !== NULL) {
                    if(empty($_POST["database_read_only_username"])) {
                        $database_read_only_username = $database_username;
                        $database_read_only_password = $database_password;
                    } else {
                        $database_read_only_username = $_POST["database_read_only_username"];
                        if(isset($_POST["database_read_only_password"])) {
                            $database_read_only_password = $_POST["database_read_only_password"];
                        } else {
                            $database_read_only_password = "";
                        }
                    }
                } else {
                    $post_values_correct = FALSE;
                    FERequestAAcrossMLogic::appendMessage(
                        FERequestAAcrossMLogic::TYPE_ERROR,
                        FERequestAAcrossMLogic::MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBREADONLYUSERNAME,
                        self::PREFIX);
                }

                // Do reset
                if ($post_values_correct) {
                    if(\integration\DatabaseIntegration::setCredentials(
                        $database_server_host,
                        $database_server_port,
                        $database_name,
                        $database_username,
                        $database_password,
                        $database_read_only_username,
                        $database_read_only_password
                    ) === TRUE) {
                        $result = \integration\DatabaseIntegration::testConnection();
                        if($result === TRUE) {
                            if (\logics\ResetLogic::doReset()) {
                                LOG::INFO("Reset has been successful!");
                                FERequestAAcrossMLogic::appendMessage(
                                    FERequestAAcrossMLogic::TYPE_SUCCESS,
                                    FERequestAAcrossMLogic::MESSAGE_RESETCONTROLLER_RESET_SUCCESSFUL,
                                    self::PREFIX);
                                return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix());
                            } else {
                                LOG::ERROR("Reset has failed!");
                                FERequestAAcrossMLogic::appendMessage(
                                    FERequestAAcrossMLogic::TYPE_ERROR,
                                    FERequestAAcrossMLogic::MESSAGE_RESETCONTROLLER_RESET_ERROR_GENERIC,
                                    self::PREFIX);
                                return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix() . "reset");
                            }
                        } else {
                            \integration\DatabaseIntegration::removeCredentials();
                            LOG::ERROR("Connection test failed!");
                            FERequestAAcrossMLogic::appendMessage(
                                FERequestAAcrossMLogic::TYPE_ERROR,
                                FERequestAAcrossMLogic::MESSAGE_RESETCONTROLLER_RESET_ERROR_GENERIC,
                                self::PREFIX, $result);
                            return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix() . "reset");
                        }
                    } else {
                        LOG::ERROR("Credentials couldnot be stored!");
                        FERequestAAcrossMLogic::appendMessage(
                            FERequestAAcrossMLogic::TYPE_ERROR,
                            FERequestAAcrossMLogic::MESSAGE_RESETCONTROLLER_RESET_ERROR_GENERIC,
                            self::PREFIX);
                        return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix() . "reset");
                    }
                } else {
                    LOG::ERROR("Reset has failed because the input values are invalid!");
                    return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix() . "reset");
                }
            }
            \logics\CSRFLogic::reset(self::PREFIX);
        }

        $_RESPONSE[self::PREFIX . self::SUFFIX_CSRF_VALUE] = \logics\CSRFLogic::initialize(self::PREFIX, 180);

        return new \actions\ViewAction("ResetView");
    }

}