<?php

namespace views;

require_once("AbstractView.php");

require_once(__DIR__ . "/../helper/VariousHelper.php");

require_once(__DIR__ . "/../controllers/ResetController.php");

require_once(__DIR__ . "/../logics/FrontEndRequestAcrossMessagesLogic.php");


class ResetView extends AbstractView
{
    public function getCssIncludeFiles()
    {
        return array();
    }

    protected function isRestrictedView()
    {
        return FALSE;
    }

    public function printMain()
    {
        global $_RESPONSE;
        ?>
        <main>
            <section>
                <h2><?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_HEADLINE);
                    ?></h2>
                <?php
                \logics\FrontEndRequestAcrossMessagesLogic::insertHTML();
                ?>
                <p><?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_PARAGRAPH_EXPLANATION);
                    ?></p>
                <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>reset" method="POST">
                    <input type="hidden" name="csrf_value"
                           value="<?php echo $_RESPONSE[\controllers\ResetController::PREFIX . \controllers\ResetController::SUFFIX_CSRF_VALUE]; ?>"/>
                    <input type="submit" name="reset" value="<?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_SUBMIT_VALUE);
                    ?>"/>
                </form>
            </section>
        </main>
        <?php
    }

}