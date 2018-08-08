<?php

namespace controllers;

require_once("AbstractController.php");

require_once(__DIR__ . "/../actions/ViewAction.php");

require_once(__DIR__ . "/../helper/VariousHelper.php");

require_once(__DIR__ . "/../logics/SessionLogic.php");

class ExitController extends AbstractController
{
    public const PREFIX = "ExitController_";

    public function getUniqueControllerPrefix()
    {
        return self::PREFIX;
    }

    public function action(string $requestSubUri)
    {
        \logics\SessionLogic::removeSessionByPhpSessionId(session_id());
        return new \actions\RedirectAction(\helper\VariousHelper::getUrlPrefix());
    }

}