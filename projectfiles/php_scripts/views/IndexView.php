<?php

namespace views;

require_once("AbstractView.php");

require_once(__DIR__ . "/../helper/VariousHelper.php");
require_once(__DIR__ . "/../helper/FrontEndRequestAcrossMessagesHelper.php");
require_once(__DIR__ . "/../helper/LocalizationHelper.php");
require_once(__DIR__ . "/../helper/further/LocalizationStore.php");

use \helper\FrontEndRequestAcrossMessagesHelper as FERequestAMHelper;
use \helper\LocalizationHelper as LocalizationHlp;
use \helper\further\LocalizationStore as LocalizationStore;

class IndexView extends AbstractView
{
    public function getCssIncludeFiles()
    {
        return array("style.css", "index.css");
    }

    protected function isRestrictedView()
    {
        return FALSE;
    }

    public function printMain()
    {
        ?>
        <main>
            <section id="body_main_game_explanation">
                <strong><?php
                    echo LocalizationHlp::get(LocalizationStore::ID_GENERAL_GAME_NAME);
                    ?></strong> <?php
                echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_GAME_DESCRIPTION);
                ?>
            </section>
            <?php
            FERequestAMHelper::appendMessage(
                FERequestAMHelper::TYPE_WARN,
                FERequestAMHelper::MESSAGE_INDEXVIEW_WARNING_DATA_USAGE,
                "IndexView_"
            );
            if (!FERequestAMHelper::isEmpty()) {
                ?>
                <section id="main_section_frontend_messages"><?php
                FERequestAMHelper::insertHTML(
                    "", "msg-box-success",
                    "msg-box-error", "msg-box-warn", "msg-box-info");
                ?></section><?php
            }
            ?>
            <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>index" method="POST" id="body_main_form">
                <label for="username_new_game" class="index_form_description_label"><?php
                    echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_USERNAME_NEW_GAME_LABEL);
                    ?>:</label>
                <input type="text" name="username_new_game" id="username_new_game" value=""
                       placeholder="<?php
                       echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_USERNAME_NEW_GAME_PLACEHOLDER);
                       ?>" required/>
                <label for="invite_code" id="invite_code_label" class="index_form_description_label"><?php
                    echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_INVITE_CODE_LABEL);
                    ?>:</label>
                <input type="text" name="invite_code" id="invite_code" onkeyup="onInviteCode()" value="<?php
                if (!empty($_GET["invitecode"])) {
                    echo htmlentities($_GET["invitecode"]);
                }
                ?>" placeholder="<?php
                echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_INVITE_CODE_PLACEHOLDER);
                ?>"/>
                <p class="index_form_description_paragraph"><?php
                    echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_PARAGRAPH_FURTHER_GAME_START_EXPLANATION);
                    ?></p>
                <label for="just_watch_new_game" id="just_watch_new_game_label"><input type="checkbox" name="just_watch_new_game"
                                                        id="just_watch_new_game"
                                                        value="<?php
                                                        echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_VALUE);
                                                        ?>" autocomplete="off"> <?php
                    echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_LABEL);
                    ?></label>
                <label for="max_questions" id="max_questions_label" class="index_form_description_label"><?php
                    echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_MAX_QUESTIONS_LABEL);
                    ?>:</label>
                <input type="text" name="max_questions" id="max_questions" value="10"
                       placeholder="<?php
                       echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_MAX_QUESTIONS_PLACEHOLDER);
                       ?>" autocomplete="off"/>
                <p class="index_form_description_paragraph" id="max_questions_desc"><?php
                    echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_MAX_QUESTIONS_DESCRIPTION_PARAGRAPH);
                    ?></p>
                <label for="minimum_score" id="minimum_score_label" class="index_form_description_label"><?php
                    echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_MINIMUM_SCORE_LABEL);
                    ?>:</label>
                <input type="text" name="minimum_score" id="minimum_score" value=""
                       placeholder="<?php
                       echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_MINIMUM_SCORE_PLACEHOLDER);
                       ?>" autocomplete="off"/>
                <p class="index_form_description_paragraph" id="minimum_score_desc"><?php
                    echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_MINIMUM_SCORE_DESCRIPTION_PARAGRAPH);
                    ?></p>
                <label for="timer_wanted" id="timer_wanted_label" class="index_form_description_label"><?php
                    echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_TIMER_WANTED_LABEL);
                    ?>:</label>
                <input type="text" name="timer_wanted" id="timer_wanted" value="30" placeholder="<?php
                echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_TIMER_WANTED_PLACEHOLDER);
                ?>" autocomplete="off"/>
                <p class="index_form_description_paragraph" id="timer_wanted_desc"><?php
                    echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_TIMER_WANTED_DESCRIPTION_PARAGRAPH);
                    ?></p>
                <input type="submit" name="start_or_join_game" id="start_or_join_game" value="<?php
                echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE);
                ?>"/>
            </form>
        </main>
        <script>
            function setVisibilityOfLobbyElements(lobbyElementsVisible) {
                if (lobbyElementsVisible === false) {
                    document.getElementById("max_questions_label").style.display = 'none';
                    document.getElementById("max_questions").style.display = 'none';
                    document.getElementById("max_questions_desc").style.display = 'none';
                    document.getElementById("minimum_score_label").style.display = 'none';
                    document.getElementById("minimum_score").style.display = 'none';
                    document.getElementById("minimum_score_desc").style.display = 'none';
                    document.getElementById("timer_wanted_label").style.display = 'none';
                    document.getElementById("timer_wanted").style.display = 'none';
                    document.getElementById("timer_wanted_desc").style.display = 'none';
                } else {
                    document.getElementById("max_questions_label").style.display = 'block';
                    document.getElementById("max_questions").style.display = 'block';
                    document.getElementById("max_questions_desc").style.display = 'block';
                    document.getElementById("minimum_score_label").style.display = 'block';
                    document.getElementById("minimum_score").style.display = 'block';
                    document.getElementById("minimum_score_desc").style.display = 'block';
                    document.getElementById("timer_wanted_label").style.display = 'block';
                    document.getElementById("timer_wanted").style.display = 'block';
                    document.getElementById("timer_wanted_desc").style.display = 'block';
                }
            }

            function hideLobbyElements() {
                setVisibilityOfLobbyElements(false);
            }

            function showLobbyElements() {
                setVisibilityOfLobbyElements(true);
            }

            function onInviteCode() {
                const invite_code = document.getElementById("invite_code").value;
                if (invite_code.length > 0) {
                    hideLobbyElements();
                    document.getElementById("start_or_join_game").value = "<?php
                        echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE_JOIN);
                        ?>";
                } else {
                    showLobbyElements();
                    document.getElementById("start_or_join_game").value = "<?php
                        echo LocalizationHlp::get(LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE_CREATE);
                        ?>";
                }
            }

            onInviteCode();
        </script>
        <?php
    }

}