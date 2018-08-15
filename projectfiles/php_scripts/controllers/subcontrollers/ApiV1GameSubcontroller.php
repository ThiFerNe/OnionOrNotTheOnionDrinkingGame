<?php

namespace subcontrollers;

require_once("AbstractSubcontroller.php");
require_once(__DIR__ . "/../../actions/NoneAction.php");
require_once(__DIR__ . "/../../helper/LogHelper.php");
require_once(__DIR__ . "/../../logics/GameViewContentLogic.php");

use \helper\LogHelper as LOG;

class ApiV1GameSubcontroller extends AbstractSubcontroller
{
    /**
     * The prefix. Only used by an implementation of this Controller.
     * It can be used to prefix entries within the $_REQUEST global variable
     * to better distinguish its entries when used in the view classes.
     */
    public const PREFIX = "ApiV1GameSubcontroller_";

    /**
     * Override of parent function. More to read at that.
     *
     * @see \subcontrollers\AbstractSubcontroller::getUniqueControllerPrefix()
     * @return string (More to read at parent function)
     */
    public function getUniqueControllerPrefix()
    {
        return self::PREFIX;
    }

    /**
     * Override of parent function. More to read at that.
     *
     * @see \subcontrollers\AbstractSubcontroller::action()
     * @return \actions\AbstractAction (More to read at parent function)
     */
    public function action(string $requestSubUri)
    {

        // Only check this if the suburi is filled
        if (!empty($requestSubUri)) {
            // Copy given parameter to modify it safely
            $requestSubUriEdited = $requestSubUri;

            // Remove any starting slashes
            while (substr($requestSubUriEdited, 0, 1) === "/") {
                $requestSubUriEdited = substr($requestSubUriEdited, 1);
            }

            // Get the first part of the requested suburi
            $requestSubcontroller = "/" . explode("/", $requestSubUriEdited)[0];

            // if elses for the requested suburis
            if ($requestSubcontroller === "/mainwrapper") {
                if($_SERVER['REQUEST_METHOD'] === "GET") {
                    \logics\GameViewContentLogic::betterPrintContent();
                    return new \actions\NoneAction();
                }
            }
        }

        // If nothing has been found send 400 BAD REQUEST
        http_response_code(400);
        return new \actions\NoneAction();
    }

}