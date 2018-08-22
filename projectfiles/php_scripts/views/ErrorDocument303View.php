<?php

namespace views;

require_once("AbstractView.php");

require_once(__DIR__ . "/../helper/LocalizationHelper.php");
require_once(__DIR__ . "/../helper/further/LocalizationStore.php");

use \helper\LocalizationHelper as LocalizationHlp;
use \helper\further\LocalizationStore as LocalizationStore;

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
        return array("style.css", "errordocument.css");
    }

    /**
     * Override of parent function. More to read at that.
     *
     * @see \controllers\AbstractView::isRestrictedView()
     * @return bool (More to read at parent function)
     */
    protected function isRestrictedView()
    {
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
                    <?php
                    echo LocalizationHlp::get(LocalizationStore::ID_ERRORDOCUMENT303VIEW_BODY_MAIN_HEADLINE);
                    ?>
                </h1>
                <p>
                    <?php
                    echo LocalizationHlp::get(LocalizationStore::ID_ERRORDOCUMENT303VIEW_BODY_MAIN_PARAGRAPH);
                    ?> <a href="<?php echo $_RESPONSE[ErrorDocument303View::PREFIX . ErrorDocument303View::REFER_TO];
                    ?>"><?php echo htmlentities($_RESPONSE[ErrorDocument303View::PREFIX . ErrorDocument303View::REFER_TO]); ?></a>.
                </p>
            </article>
        </main>
        <?php
    }
}