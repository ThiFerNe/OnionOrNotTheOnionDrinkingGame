<?php

namespace controllers;

/**
 * The abstract parent class of every controller.
 * Those controller classes are used within the frontcontroller
 * to route incoming requests to the right actions.
 *
 * Each controller has to be a child of this AbstractController class.
 *
 * @package controllers
 */
abstract class AbstractController
{
    /**
     * This function returns a prefix only used by an implementation of a Controller.
     * It can be used to prefix entries within the $_REQUEST global variable
     * to better distinguish its entries when used in the view classes.
     *
     * @return string The prefix of this controller
     */
    public abstract function getUniqueControllerPrefix();

    /**
     * Within this function the action do process is being defined.
     * Inside this function an implementation of a controller can process
     * normal GET requests and also POST requests sent to this controller
     * and act respectively.
     *
     * @param string $request_sub_uri The part of the given URI after the controller routing prefix.
     * @return \actions\AbstractAction The Action to process after this controller has done its work. Used in frontcontroller.
     */
    public abstract function action(string $request_sub_uri);
}
