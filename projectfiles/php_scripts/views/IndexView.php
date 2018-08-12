<?php

namespace views;

require_once("AbstractView.php");

require_once(__DIR__ . "/../helper/VariousHelper.php");

require_once(__DIR__ . "/../logics/FrontEndRequestAcrossMessagesLogic.php");


class IndexView extends AbstractView
{
    public function getCssIncludeFiles()
    {
        return array("style.css","index.css");
    }

    protected function isRestrictedView()
    {
        return FALSE;
    }

    public function printMain()
    {
        ?>
        <main>
            <div>
                <section>
                    <strong><?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GENERAL_GAME_NAME);
                        ?></strong> <?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_GAME_DESCRIPTION);
                    ?>
                </section>
            </div>
            <?php
            \logics\FrontEndRequestAcrossMessagesLogic::insertHTML(
                    "","","","",""
            );
            ?>
            <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>index" method="POST">
                <input type="text" name="username_new_game" id="username_new_game" value=""
                       placeholder="<?php
                       echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_USERNAME_NEW_GAME_PLACEHOLDER);
                       ?>"/>
                <label for="just_watch_new_game" ><input type="checkbox" name="just_watch_new_game"
                                                                                         id="just_watch_new_game"
                                                                                         value="<?php
                                                                                         echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_VALUE);
                                                                                         ?>" autocomplete="off"> <?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_LABEL);
                    ?></label>
                <input type="text" name="invite_code" id="invite_code" onkeyup="onInviteCode()" value="<?php
                if (!empty($_GET["invitecode"])) {
                    echo htmlentities($_GET["invitecode"]);
                }
                ?>" placeholder="<?php
                echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_INVITE_CODE_PLACEHOLDER);
                ?>"/>
                <label for="invite_code" style="font-style: italic; text-align: center; margin-bottom: 30px;"><?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_PARAGRAPH_FURTHER_GAME_START_EXPLANATION);
                    ?></label>
                <input type="text" name="max_questions" id="max_questions" value=""
                       placeholder="<?php
                       echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_MAX_QUESTIONS_PLACEHOLDER);
                       ?>"/>
                <label for="max_questions" style="font-style: italic; text-align: center; margin-bottom: 30px;" id="max_questions_desc"><?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_MAX_QUESTIONS_DESCRIPTION_PARAGRAPH);
                    ?></label>
                <input type="text" name="minimum_score" id="minimum_score" value=""
                       placeholder="<?php
                       echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_MINIMUM_SCORE_PLACEHOLDER);
                       ?>"/>
                <label for="minimum_score" style="font-style: italic; text-align: center; margin-bottom: 30px;" id="minimum_score_desc"><?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_MINIMUM_SCORE_DESCRIPTION_PARAGRAPH);
                    ?></label>
                <input type="text" name="timer_wanted" id="timer_wanted" value="" placeholder="<?php
                echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_TIMER_WANTED_PLACEHOLDER);
                ?>"/>
                <label for="timer_wanted" style="font-style: italic; text-align: center; margin-bottom: 30px;" id="timer_wanted_desc"><?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_TIMER_WANTED_DESCRIPTION_PARAGRAPH);
                    ?></label>
                <div class="w-100"></div>
                <input type="submit" name="start_or_join_game" id="start_or_join_game" value="<?php
                echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE);
                ?>"/>
            </form>
        </main>
        <script>
            function setVisibilityOfLobbyElements(lobbyElementsVisible) {
                if(lobbyElementsVisible === false) {
                    document.getElementById("max_questions").style.display = 'none';
                    document.getElementById("max_questions_desc").style.display = 'none';
                    document.getElementById("minimum_score").style.display = 'none';
                    document.getElementById("minimum_score_desc").style.display = 'none';
                    document.getElementById("timer_wanted").style.display = 'none';
                    document.getElementById("timer_wanted_desc").style.display = 'none';
                } else {
                    document.getElementById("max_questions").style.display = 'block';
                    document.getElementById("max_questions_desc").style.display = 'block';
                    document.getElementById("minimum_score").style.display = 'block';
                    document.getElementById("minimum_score_desc").style.display = 'block';
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
                if(invite_code.length > 0) {
                    hideLobbyElements();
                    document.getElementById("start_or_join_game").value = "<?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE_JOIN);
                        ?>";
                } else {
                    showLobbyElements();
                    document.getElementById("start_or_join_game").value = "<?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE_CREATE);
                        ?>";
                }
            }

            onInviteCode();
        </script>
        <?php
    }

}