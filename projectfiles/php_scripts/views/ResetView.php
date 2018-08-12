<?php

namespace views;

use logics\FrontEndRequestAcrossMessagesLogic;

require_once("AbstractView.php");

require_once(__DIR__ . "/../helper/VariousHelper.php");

require_once(__DIR__ . "/../controllers/ResetController.php");

require_once(__DIR__ . "/../logics/FrontEndRequestAcrossMessagesLogic.php");


class ResetView extends AbstractView
{
    public function getCssIncludeFiles()
    {
        return array("style.css", "reset.css");
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
            <section id="main_section">
                <h2 id="main_section_headline"><?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_HEADLINE);
                    ?></h2>
                <?php
                if (!\logics\FrontEndRequestAcrossMessagesLogic::isEmpty()) {
                    ?>
                    <section id="main_section_frontend_messages"><?php
                    \logics\FrontEndRequestAcrossMessagesLogic::insertHTML(
                        "", "msg-box-success",
                        "msg-box-error", "msg-box-warn", "msg-box-info");
                    ?></section><?php
                }
                ?>
                <p id="main_paragraph_description"><?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_PARAGRAPH_EXPLANATION);
                    ?></p>
                <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>reset" method="POST" id="main_form">
                    <input type="hidden" name="csrf_value"
                           value="<?php echo $_RESPONSE[\controllers\ResetController::PREFIX . \controllers\ResetController::SUFFIX_CSRF_VALUE]; ?>"/>
                    <label for="database_server_host" class="reset_form_description_label"><?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBHOST_LABEL);
                        ?>:</label>
                    <input type="text" name="database_server_host" id="database_server_host" value="localhost"
                           placeholder="<?php
                           echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBHOST_PLACEHOLDER);
                           ?>"/>
                    <label for="database_server_port" class="reset_form_description_label"><?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBPORT_LABEL);
                        ?>:</label>
                    <input type="text" name="database_server_port" id="database_server_port" value="3306"
                           placeholder="<?php
                           echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBPORT_PLACEHOLDER);
                           ?>"/>
                    <label for="database_name" class="reset_form_description_label"><?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBNAME_LABEL);
                        ?></label>
                    <input type="text" name="database_name" id="database_name" value="theonionornottheonion"
                           placeholder="<?php
                           echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBNAME_PLACEHOLDER);
                           ?>"/>
                    <label for="database_username" class="reset_form_description_label"><?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBUSERNAME_LABEL);
                        ?>:</label>
                    <input type="text" name="database_username" id="database_username" value=""
                           placeholder="<?php
                           echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBUSERNAME_PLACEHOLDER);
                           ?>"/>
                    <label for="database_password" class="reset_form_description_label"><?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBPASSWORD_LABEL);
                        ?>:</label>
                    <input type="text" name="database_password" id="database_password" value=""
                           placeholder="<?php
                           echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBPASSWORD_PLACEHOLDER);
                           ?>"/>
                    <label for="database_read_only_username" class="reset_form_description_label"><?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBREADONLYUSERNAME_LABEL);
                        ?>:</label>
                    <input type="text" name="database_read_only_username" id="database_read_only_username" value=""
                           placeholder="<?php
                           echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBREADONLYUSERNAME_PLACEHOLDER);
                           ?>"/>
                    <label for="database_read_only_password" class="reset_form_description_label"><?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBREADONLYPASSWORD_LABEL);
                        ?>:</label>
                    <input type="text" name="database_read_only_password" id="database_read_only_password" value=""
                           placeholder="<?php
                           echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBREADONLYPASSWORD_PLACEHOLDER);
                           ?>"/>
                    <input type="submit" name="reset" id="reset" value="<?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_SUBMIT_VALUE);
                    ?>"/>
                </form>
            </section>
        </main>
        <?php
    }

}