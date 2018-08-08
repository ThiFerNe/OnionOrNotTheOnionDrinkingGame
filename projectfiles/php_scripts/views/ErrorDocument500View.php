<?php

namespace views;

require_once("AbstractView.php");

/**
 * The view for the ErrorDocument 500 page
 *
 * @package views
 */
class ErrorDocument500View extends AbstractView
{
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
        ?>
        <main>
            <article>
                <h1>
                    500 - Interner Server Fehler
                </h1>
                <p>
                    Es entstand ein interner Fehler im Server, der sehr wahrscheinlich aufgrund einer fehlerhaften
                    Programmierung auftrat.
                </p>
                <p class="italic">
                    Sofern Sie ein Entwickler sind schauen Sie bitte in das Logfile im 'logs/' Ordner.<br/>
                    Logdateien werden nur erzeugt, wenn dieser 'logs/' Ordner existiert.
                </p>
            </article>
        </main>
        <?php
    }
}