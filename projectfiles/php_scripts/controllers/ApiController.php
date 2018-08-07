<?php

namespace controllers;

require_once("AbstractController.php");
require_once(__DIR__ . "/../actions/NoneAction.php");
require_once(__DIR__ . "/../helper/VariousHelper.php");

use \helper\VariousHelper as VariousHlp;

/**
 * This is a simple controller redirecting every request to the AboutView.
 *
 * @package controllers
 */
class ApiController extends AbstractController
{
    /**
     * The prefix. Only used by an implementation of this Controller.
     * It can be used to prefix entries within the $_REQUEST global variable
     * to better distinguish its entries when used in the view classes.
     */
    public const PREFIX = "ApiController_";

    /**
     * Override of parent function. More to read at that.
     *
     * @see \controllers\AbstractController::getUniqueControllerPrefix()
     * @return string (More to read at parent function)
     */
    public function getUniqueControllerPrefix()
    {
        return self::PREFIX;
    }

    /**
     * Override of parent function. More to read at that.
     *
     * @see \subcontrollers\AbstractController::getUniqueControllerPrefix()
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

            // Get the remaining part of the requested suburi
            $requestSubSubUri = "/" . implode("/", array_slice(explode("/", $requestSubUriEdited), 1));

            // Prepare switching for every requested first part of the suburi
            $subcontrollerToCall = NULL;

            // if elses for the requested suburis
            if ($requestSubcontroller === "/v1") {
                require_once(__DIR__ . "/subcontrollers/ApiV1Subcontroller.php");
                $subcontrollerToCall = new \subcontrollers\ApiV1Subcontroller();
            }

            // If the subcontroller had been found
            if($subcontrollerToCall !== NULL) {
                return $subcontrollerToCall->action($requestSubSubUri);
            }
        }

        // If nothing has been found send 400 BAD REQUEST
        http_response_code(400);
        return new \actions\NoneAction();
    }
}