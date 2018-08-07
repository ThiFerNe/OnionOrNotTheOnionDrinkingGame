<?php

namespace subcontrollers;

/**
 * The abstract parent class of every subcontroller.
 * Those subcontroller classes are used within controllers
 * to further divide the main controller.
 *
 * Each subcontroller has to be a child of this AbstractSubcontroller class.
 *
 * @package subcontrollers
 */
abstract class AbstractSubcontroller
{
    /**
     * This function returns a prefix only used by an implementation of a Subcontroller.
     * It can be used to prefix entries within the $_RESPONSE global variable
     * to better distinguish its entries when used in the view classes.
     *
     * @return string The prefix of this controller
     */
    public abstract function getUniqueControllerPrefix();

    /**
     * Within this function the action do process is being defined.
     * Inside this function an implementation of a subcontroller can process
     * normal GET requests and also POST requests sent to this controller
     * and act respectively.
     *
     * @param string $request_sub_uri The part of the given URI after the subcontroller routing prefix.
     * @return \actions\AbstractAction The Action to process after this controller has done its work. Used in frontcontroller.
     */
    public abstract function action(string $request_sub_uri);
}
