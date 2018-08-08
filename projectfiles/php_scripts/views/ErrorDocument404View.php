<?php

namespace views;

require_once("AbstractView.php");

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
    public function getCssIncludeFiles() {
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
    public function printMain() {
        ?>
        <main>
            <article>
                <h1>
                    404 - Nicht gefunden
                </h1>
                <p>
                    Sie oder Ihr Browser hat eine Datei gefordert, die uns nicht bekannt ist.<br/>
                    Kehren Sie bitte zur Hauptseite zurück.
                </p>
            </article>
        </main>
        <?php
    }
}