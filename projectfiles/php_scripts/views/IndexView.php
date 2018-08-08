<?php

namespace views;

require_once("AbstractView.php");

require_once(__DIR__ . "/../helper/VariousHelper.php");

require_once(__DIR__ . "/../logics/FrontEndRequestAcrossMessagesLogic.php");


class IndexView extends AbstractView
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
        ?>
        <main>
            <section>
                <strong><?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GENERAL_GAME_NAME);
                    ?></strong> <?php
                echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_GAME_DESCRIPTION);
                ?>
            </section>
            <?php
            \logics\FrontEndRequestAcrossMessagesLogic::insertHTML();
            ?>
            <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>index" method="POST">
                <input type="text" name="username_new_game" value="" placeholder="<?php
                echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_USERNAME_NEW_GAME_PLACEHOLDER);
                ?>"/>
                <label for="just_watch_new_game"><input type="checkbox" name="just_watch_new_game"
                                                        id="just_watch_new_game"
                                                        value="<?php
                                                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_VALUE);
                                                        ?>"> <?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_LABEL);
                    ?></label>
                <input type="text" name="invite_code" value="<?php
                if (!empty($_GET["invitecode"])) {
                    echo htmlentities($_GET["invitecode"]);
                }
                ?>" placeholder="<?php
                echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_INVITE_CODE_PLACEHOLDER);
                ?>"/>
                <input type="text" name="max_questions" value=""
                       placeholder="<?php
                       echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_MAX_QUESTIONS_PLACEHOLDER);
                       ?>"/>
                <input type="text" name="minimum_score" value=""
                       placeholder="<?php
                       echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_MINIMUM_SCORE_PLACEHOLDER);
                       ?>"/>
                <input type="text" name="timer_wanted" value="" placeholder="<?php
                echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_TIMER_WANTED_PLACEHOLDER);
                ?>"/>
                <p><?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_PARAGRAPH_FURTHER_GAME_START_EXPLANATION);
                    ?></p>
                <input type="submit" name="start_or_join_game" value="<?php
                echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE);
                ?>"/>
            </form>
        </main>
        <?php
    }

}