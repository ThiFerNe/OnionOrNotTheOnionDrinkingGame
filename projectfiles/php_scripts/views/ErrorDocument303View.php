<?php

namespace views;

require_once("AbstractView.php");

/**
 * The view for the ErrorDocument 303 page
 *
 * @package views
 */
class ErrorDocument303View extends AbstractView
{
    /**
     * A prefix used to set values inside the REQUEST array.
     */
    public const PREFIX = "ErrorDocument303View_";

    /**
     * A suffix used to set values inside the REQUEST array.
     */
    public const REFER_TO = "ReferTo";

    /**
     * Override of parent function. More to read at that.
     *
     * @see \controllers\AbstractView::getCssIncludeFiles()
     * @return array (More to read at parent function)
     */
    public function getCssIncludeFiles()
    {
        return array("errordocument.css");
    }

    /**
     * Override of parent function. More to read at that.
     *
     * @see \controllers\AbstractView::isRestrictedView()
     * @return bool (More to read at parent function)
     */
    protected function isRestrictedView() {
        return TRUE;
    }

    /**
     * Override of parent function. More to read at that.
     *
     * @see \controllers\AbstractView::printMain()
     */
    public function printMain()
    {
        global $_RESPONSE;
        ?>
        <main>
            <article>
                <h1>
                    303 - See Other
                </h1>
                <p>
                    Wir haben versucht Sie weiterzuleiten, aber das war uns nicht möglich.<br/>
                    Bitte klicken Sie eigenständig auf folgenden Link: <a
                            href="<?php echo $_RESPONSE[ErrorDocument303View::PREFIX . ErrorDocument303View::REFER_TO];
                            ?>"><?php echo htmlentities($_RESPONSE[ErrorDocument303View::PREFIX . ErrorDocument303View::REFER_TO]); ?></a>.
                </p>
            </article>
        </main>
        <?php
    }
}