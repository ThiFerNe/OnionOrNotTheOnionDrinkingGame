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

    protected function isRestrictedView() {
        return FALSE;
    }

    public function printMain()
    {
        ?>
        <main>
            <section>
                <strong>Onion Or Not The Onion Drinking Game</strong> is a web game in which your mobile devices are your
                controllers. Just create a lobby and play. Furthermore you can host a screen which provides a view for rest of
                your party which doesn't want to play.
            </section>
            <?php
            \logics\FrontEndRequestAcrossMessagesLogic::insertHTML();
            ?>
            <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>index" method="POST">
                <input type="text" name="username_new_game" value="" placeholder="Name"/>
                <label for="just_watch_new_game"><input type="checkbox" name="just_watch_new_game" id="just_watch_new_game"
                                                        value="Just Watch It!"> Just
                    Watch It!</label>
                <input type="text" name="invite_code" value="<?php
                    if(!empty($_GET["invitecode"])) {
                        echo htmlentities($_GET["invitecode"]);
                    }
                ?>" placeholder="Invite Code"/>
                <input type="text" name="max_questions" value="" placeholder="Questions - Leave Blank if all are wished"/>
                <input type="text" name="timer_wanted" value="" placeholder="Timer - Leave Blank if none is wished"/>
                <p>With no invite code a new game will be started. Otherwise the game with the code will be joined.</p>
                <input type="submit" name="start_or_join_game" value="START"/>
            </form>
        </main>
        <?php
    }

}