<?php

namespace views;

require_once("AbstractView.php");

require_once(__DIR__ . "/../logics/LocalizationLogic.php");
require_once(__DIR__ . "/../logics/further/LocalizationStore.php");

/**
 * The view for the ErrorDocument 404 page
 *
 * @package views
 */
class ErrorDocument404View extends AbstractView
{
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
        ?>
        <main>
            <article>
                <h1>
                    <?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_ERRORDOCUMENT404VIEW_BODY_MAIN_HEADLINE);
                    ?>
                </h1>
                <p>
                    <?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_ERRORDOCUMENT404VIEW_BODY_MAIN_PARAGRAPH);
                    ?>
                </p>
            </article>
        </main>
        <?php
    }
}