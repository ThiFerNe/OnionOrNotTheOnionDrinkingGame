<?php

namespace actions;

require_once("AbstractAction.php");

/**
 * An action to tell the frontcontroller which View to send to the client.
 *
 * @package actions
 */
class ViewAction extends AbstractAction
{
    /**
     * The private variable containing the wished viewpage given to the constructor.
     *
     * @var null|string
     */
    private $viewpage = NULL;

    /**
     * ViewAction constructor.
     *
     * @param string $viewpage The wished View to be shown by the frontcontroller.
     */
    public function __construct(string $viewpage)
    {
        $this->viewpage = $viewpage;
    }

    /**
     * Returns the value of the private `$viewpage` variable.
     *
     * @return null|string The value of the `$viewpage` variable
     */
    public function getViewpage()
    {
        return $this->viewpage;
    }
}