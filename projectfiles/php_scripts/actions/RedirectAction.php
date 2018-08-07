<?php

namespace actions;

require_once("AbstractAction.php");

/**
 * An action used to transfer the wished action of a redirect to the frontcontroller
 * together with the target. The target can be anything which a browser can
 * read within the Location field inside the HTTP response header.
 *
 * @package actions
 */
class RedirectAction extends AbstractAction
{
    /**
     * The variable to store the given target URL given in the constructor.
     *
     * @var null|string
     */
    private $redirectTo = NULL;

    /**
     * RedirectAction constructor.
     * @param string $redirectTo The wished target URL.
     */
    public function __construct(string $redirectTo)
    {
        $this->redirectTo = $redirectTo;
    }

    /**
     * Returns the value of the private variable `redirectTo`.
     *
     * @return null|string The value of the private variable `redirectTo`
     */
    public function getRedirectTo()
    {
        return $this->redirectTo;
    }
}