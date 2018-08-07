<?php

namespace actions;

require_once("AbstractAction.php");

/**
 * An action to tell the frontcontroller to show the respective ErrorDocument
 * which corresponds to the given HTTP status code.
 *
 * @package actions
 */
class StatusCodeAction extends AbstractAction
{
    /**
     * The stored HTTP status code given in the constructor.
     *
     * @var int|null
     */
    private $statusCode = NULL;

    /**
     * StatusCodeAction constructor.
     *
     * @param int $statusCode The wished HTTP status code.
     */
    public function __construct(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Returns the stored private `$statusCode`.
     *
     * @return int|null The stored status code
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Translates the HTTP status code to an ErrorDocument*View which matches.
     * Has to be defined manually.
     *
     * @return string The View to which the status code translates to
     */
    public function getStatusCodeView()
    {
        switch ($this->getStatusCode()) {
            case 303:
                return "ErrorDocument303View";
            case 404:
                return "ErrorDocument404View";
            case 500:
                return "ErrorDocument500View";
            default:
                return "ErrorDocumentGenericView";
        }
    }
}