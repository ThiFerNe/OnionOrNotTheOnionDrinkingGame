<?php

namespace views;

require_once("AbstractView.php");

require_once(__DIR__ . "/../helper/VariousHelper.php");
require_once(__DIR__ . "/../helper/FrontEndRequestAcrossMessagesHelper.php");
require_once(__DIR__ . "/../helper/LocalizationHelper.php");
require_once(__DIR__ . "/../helper/further/LocalizationStore.php");

require_once(__DIR__ . "/../controllers/ResetController.php");

use \helper\FrontEndRequestAcrossMessagesHelper as FERequestAMHelper;
use \helper\LocalizationHelper as LocalizationHlp;
use \helper\further\LocalizationStore as LocalizationStore;

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
                    echo LocalizationHlp::get(LocalizationStore::ID_RESETVIEW_BODY_MAIN_HEADLINE);
                    ?></h2>
                <?php
                if (!FERequestAMHelper::isEmpty()) {
                    ?>
                    <section id="main_section_frontend_messages"><?php
                    FERequestAMHelper::insertHTML(
                        "", "msg-box-success",
                        "msg-box-error", "msg-box-warn", "msg-box-info");
                    ?></section><?php
                }
                ?>
                <p id="main_paragraph_description"><?php
                    echo LocalizationHlp::get(LocalizationStore::ID_RESETVIEW_BODY_MAIN_PARAGRAPH_EXPLANATION);
                    ?></p>
                <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>reset" method="POST" id="main_form">
                    <input type="hidden" name="csrf_value"
                           value="<?php echo $_RESPONSE[\controllers\ResetController::PREFIX . \controllers\ResetController::SUFFIX_CSRF_VALUE]; ?>"/>
                    <label for="database_server_host" class="reset_form_description_label"><?php
                        echo LocalizationHlp::get(LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBHOST_LABEL);
                        ?>:</label>
                    <input type="text" name="database_server_host" id="database_server_host" value="localhost"
                           placeholder="<?php
                           echo LocalizationHlp::get(LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBHOST_PLACEHOLDER);
                           ?>"/>
                    <label for="database_server_port" class="reset_form_description_label"><?php
                        echo LocalizationHlp::get(LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBPORT_LABEL);
                        ?>:</label>
                    <input type="text" name="database_server_port" id="database_server_port" value="3306"
                           placeholder="<?php
                           echo LocalizationHlp::get(LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBPORT_PLACEHOLDER);
                           ?>"/>
                    <label for="database_name" class="reset_form_description_label"><?php
                        echo LocalizationHlp::get(LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBNAME_LABEL);
                        ?></label>
                    <input type="text" name="database_name" id="database_name" value="theonionornottheonion"
                           placeholder="<?php
                           echo LocalizationHlp::get(LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBNAME_PLACEHOLDER);
                           ?>"/>
                    <label for="database_username" class="reset_form_description_label"><?php
                        echo LocalizationHlp::get(LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBUSERNAME_LABEL);
                        ?>:</label>
                    <input type="text" name="database_username" id="database_username" value=""
                           placeholder="<?php
                           echo LocalizationHlp::get(LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBUSERNAME_PLACEHOLDER);
                           ?>"/>
                    <label for="database_password" class="reset_form_description_label"><?php
                        echo LocalizationHlp::get(LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBPASSWORD_LABEL);
                        ?>:</label>
                    <input type="text" name="database_password" id="database_password" value=""
                           placeholder="<?php
                           echo LocalizationHlp::get(LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBPASSWORD_PLACEHOLDER);
                           ?>"/>
                    <label for="database_read_only_username" class="reset_form_description_label"><?php
                        echo LocalizationHlp::get(LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBREADONLYUSERNAME_LABEL);
                        ?>:</label>
                    <input type="text" name="database_read_only_username" id="database_read_only_username" value=""
                           placeholder="<?php
                           echo LocalizationHlp::get(LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBREADONLYUSERNAME_PLACEHOLDER);
                           ?>"/>
                    <label for="database_read_only_password" class="reset_form_description_label"><?php
                        echo LocalizationHlp::get(LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBREADONLYPASSWORD_LABEL);
                        ?>:</label>
                    <input type="text" name="database_read_only_password" id="database_read_only_password" value=""
                           placeholder="<?php
                           echo LocalizationHlp::get(LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_DBREADONLYPASSWORD_PLACEHOLDER);
                           ?>"/>
                    <input type="submit" name="reset" id="reset" value="<?php
                    echo LocalizationHlp::get(LocalizationStore::ID_RESETVIEW_BODY_MAIN_FORM_SUBMIT_VALUE);
                    ?>"/>
                </form>
            </section>
        </main>
        <?php
    }

}