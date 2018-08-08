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

        if (!empty($_POST["reset"])) {
            if (\logics\CSRFLogic::check($_POST["csrf_value"], self::PREFIX)) {
                \logics\CSRFLogic::reset(self::PREFIX);
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
            }
            \logics\CSRFLogic::reset(self::PREFIX);
        }

        $_RESPONSE[self::PREFIX . self::SUFFIX_CSRF_VALUE] = \logics\CSRFLogic::initialize(self::PREFIX, 180);

        return new \actions\ViewAction("ResetView");
    }

}