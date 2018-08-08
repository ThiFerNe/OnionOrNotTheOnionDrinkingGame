<?php

namespace views;

require_once("AbstractView.php");

require_once(__DIR__ . "/../helper/VariousHelper.php");

require_once(__DIR__ . "/../controllers/GameController.php");

require_once(__DIR__ . "/../logics/FrontEndRequestAcrossMessagesLogic.php");

use \logics\FrontEndRequestAcrossMessagesLogic as FERequestAcrossMLogic;

class GameView extends AbstractView
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
        $current_state = $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_CURRENT_STATE];
        $is_watcher = $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_IS_WATCHER];
        ?>
        <main>
            <h2>
                <?php echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_USER_NAME]; ?>
            </h2>
            <h3>
                <?php
                if ($is_watcher) {
                    ?>Watcher<?php
                } else {
                    ?>Player<?php
                }
                ?>
            </h3>
            <a href="<?php \helper\VariousHelper::printUrlPrefix() ?>exit">Exit</a>
            <?php FERequestAcrossMLogic::insertHTML(); ?>
            <?php
            switch ($current_state) {
                case \logics\LobbyLogic::STATE_START:
                    ?>
                    <h1>Welcome!</h1>
                    <?php
                    if (!$is_watcher) {
                        ?>
                        <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST">
                            <input type="submit" name="start_game" value="START"/>
                        </form>
                        <?php
                    }
                    break;
                case \logics\LobbyLogic::STATE_QUESTION:
                    ?>
                    <h1>
                        <?php
                        echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_HEADLINE];
                        ?>
                    </h1>
                    <?php
                    if (!$is_watcher) {
                        ?>
                        <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST">
                            <input type="hidden" name="question_id" value="<?php
                            echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID];
                            ?>"/>
                            <input type="submit" name="set_onion" value="ONION"/>
                        </form>
                        <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST">
                            <input type="hidden" name="question_id" value="<?php
                            echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID];
                            ?>"/>
                            <input type="submit" name="set_not_the_onion" value="NOT THE ONION"/>
                        </form>
                    <?php }
                    break;
                case \logics\LobbyLogic::STATE_AFTERMATH:
                    ?>
                    <h1>
                        <?php echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_HEADLINE]; ?>
                    </h1>
                    <h2>
                        It's
                        <?php
                        if ($_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_IS_ONION]) {
                            echo "THE ONION";
                        } else {
                            echo "NOT THE ONION";
                        }
                        if (!$is_watcher) {
                            if ($_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_USER_ANSWER_WAS_CORRECT]) {
                                echo " - Your Answer was correct!";
                            } else {
                                echo " - Your Answer was wrong!";
                            }
                        }
                        ?>
                    </h2>
                    <img src="<?php
                    echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_PICTURE];
                    ?>"/>
                    <p>
                        <?php echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_FURTHER_EXPLANATION]; ?>
                    </p>
                    <a href="<?php
                    echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_HYPERLINK];
                    ?>" target="_blank">Link to Post</a>
                    <?php
                    if (!$is_watcher) {
                        ?>
                        <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST">
                            <input type="hidden" name="question_id" value="<?php
                            echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID];
                            ?>"/>
                            <input type="submit" name="upvote" value="UPVOTE"/>
                        </form>
                        <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST">
                            <input type="hidden" name="question_id" value="<?php
                            echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID];
                            ?>"/>
                            <input type="submit" name="downvote" value="DOWNVOTE"/>
                        </form>
                    <?php }
                    break;
                case \logics\LobbyLogic::STATE_END:
                    // HEADLINE END
                    ?>
                    <h1>The Game Has Ended</h1>
                    <h2>Ranking</h2>
                    <?php
                    // RANKING
                    $sorted_playing_user_ranking = $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_END_RANKING];
                    if (count($sorted_playing_user_ranking) > 0) {
                        for ($i = 1; $i <= count($sorted_playing_user_ranking); $i++) {
                            $rank = $sorted_playing_user_ranking[$i]["rank"];
                            $ranked_user = $sorted_playing_user_ranking[$i]["name"];
                            $points = $sorted_playing_user_ranking[$i]["points"];
                            ?>
                            <ol>
                                <?php echo $rank; ?>. <?php echo $ranked_user; ?> - <?php echo $points; ?>P
                            </ol>
                            <?php
                        }
                    } else {
                        ?><p>No one can be ranked!</p><?php
                    }
                    // NEXT ROUND BUTTON
                    if (!$is_watcher) {
                        ?>
                        <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST">
                            <input type="submit" name="next_round" value="PLAY AGAIN!"/>
                        </form>
                        <?php
                    }
                    break;
                default:
            }
            ?>
            <h3>Players:</h3>
            <?php
            if (count($_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_USERS_LIST]) > 0) {
                ?>
                <ul>
                    <?php
                    foreach ($_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_USERS_LIST] as $user) {
                        ?>
                        <li><?php
                        if ($user[\controllers\GameController::USERS_LIST_USERID] == \logics\SessionLogic::getUserSessionIdByPhpSessionId(session_id())) {
                            echo "<strong>";
                        }
                        echo $user[\controllers\GameController::USERS_LIST_USERNAME];
                        if ($user[\controllers\GameController::USERS_LIST_USERID] == \logics\SessionLogic::getUserSessionIdByPhpSessionId(session_id())) {
                            echo "</strong>";
                        }
                        if ($user[\controllers\GameController::USERS_LIST_HAS_ANSWERED]) {
                            echo " *";
                            if ($current_state == \logics\LobbyLogic::STATE_AFTERMATH) {
                                if ($user[\controllers\GameController::USERS_LIST_HAS_ANSWERED_CORRECT]) {
                                    echo " T";
                                } else {
                                    echo " F";
                                }
                            }
                        }
                        if (!$user[\controllers\GameController::USERS_LIST_IS_WATCHING]) {
                            echo " - " . $user[\controllers\GameController::USERS_LIST_POINTS] . " Points";
                        } else {
                            echo " (Watching)";
                        }
                        ?></li><?php
                    }
                    ?>
                </ul>
                <?php
            } else {
                ?><p>No one in!</p><?php
            }
            ?>
            <?php
            switch ($current_state) {
                case \logics\LobbyLogic::STATE_START:
                    break;
                case \logics\LobbyLogic::STATE_QUESTION:
                    if (isset($_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_REMAINING_TIME])) {
                        ?>
                        <section><?php
                            echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_REMAINING_TIME];
                            ?> seconds to go!
                        </section> <?php
                    }
                    break;
                case \logics\LobbyLogic::STATE_AFTERMATH:
                    if (isset($_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_REMAINING_TIME])) {
                        ?>
                        <section><?php
                            echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_REMAINING_TIME];
                            ?> seconds in aftermath!
                        </section> <?php
                    }
                    break;
                case \logics\LobbyLogic::STATE_END:
                    break;
                default:
            }
            ?>
        </main>
        <aside>
            Session last active: <?php
            echo date("Y.m.d H:i:s", $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_SESSION_LAST_ACTIVE]);
            ?><br/>
            Lobby last active: <?php
            echo date("Y.m.d H:i:s", $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_LOBBY_LAST_ACTIVE]);
            ?><br/>
            Current time: <?php echo date("Y.m.d H:i:s"); ?>
        </aside>
        <aside>
            Join Game with <strong><?php
                $urlprefix = \helper\VariousHelper::getUrlPrefix();
                if (strlen($urlprefix) >= strlen(strlen("https://")) && substr($urlprefix, 0, strlen("https://")) === "https://") {
                    $urlprefix = substr($urlprefix, strlen("https://"));
                } else if (strlen($urlprefix) >= strlen(strlen("http://")) && substr($urlprefix, 0, strlen("http://")) === "http://") {
                    $urlprefix = substr($urlprefix, strlen("http://"));
                }
                echo $urlprefix;
                ?></strong> and Code <strong><?php
                echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_LOBBY_CODE];
                ?></strong>!
        </aside>
        <?php
    }

}