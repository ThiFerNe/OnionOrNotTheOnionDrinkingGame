<?php

namespace views;

require_once("AbstractView.php");

require_once(__DIR__ . "/../helper/VariousHelper.php");

require_once(__DIR__ . "/../controllers/GameController.php");

require_once(__DIR__ . "/../logics/FrontEndRequestAcrossMessagesLogic.php");
require_once(__DIR__ . "/../logics/LocalizationLogic.php");
require_once(__DIR__ . "/../logics/further/LocalizationStore.php");

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
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_TYPE_OF_PLAYER_WATCHER);
                } else {
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_TYPE_OF_PLAYER_PLAYER);
                }
                ?>
            </h3>
            <a href="<?php \helper\VariousHelper::printUrlPrefix() ?>exit"><?php
                echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_EXIT_THE_GAME);
                ?></a>
            <?php FERequestAcrossMLogic::insertHTML(); ?>
            <?php
            switch ($current_state) {
                case \logics\LobbyLogic::STATE_START:
                    ?>
                    <h1><?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_START_HEADLINE_WELCOME);
                        ?></h1>
                    <?php
                    if (!$is_watcher) {
                        ?>
                        <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST">
                            <input type="submit" name="start_game" value="<?php
                            echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_START_FORM_START_GAME_SUBMIT_VALUE);
                            ?>"/>
                        </form>
                        <?php
                    }
                    break;
                case \logics\LobbyLogic::STATE_QUESTION:
                    ?>
                    <h1>
                        <?php
                        if (\logics\GameDataLocalizationLogic::containsByGameDataIdAndLocaleShort(
                            $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID],
                            \logics\further\LocalizationStore::getShortForLocale(\logics\LocalizationLogic::getCurrentLocale()))) {
                            echo \logics\GameDataLocalizationLogic::getHeadlineByGameDataIdAndLocaleShort(
                                $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID],
                                \logics\further\LocalizationStore::getShortForLocale(\logics\LocalizationLogic::getCurrentLocale()));
                        } else {
                            echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_HEADLINE];
                        }
                        ?>
                    </h1>
                    <?php
                    if (!$is_watcher) {
                        ?>
                        <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST">
                            <input type="hidden" name="question_id" value="<?php
                            echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID];
                            ?>"/>
                            <input type="submit" name="set_onion" value="<?php
                            echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_QUESTION_FORM_SET_ONION_SUBMIT_VALUE);
                            ?>"/>
                        </form>
                        <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST">
                            <input type="hidden" name="question_id" value="<?php
                            echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID];
                            ?>"/>
                            <input type="submit" name="set_not_the_onion" value="<?php
                            echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_QUESTION_FORM_SET_NOT_ONION_SUBMIT_VALUE);
                            ?>"/>
                        </form>
                    <?php }
                    break;
                case \logics\LobbyLogic::STATE_AFTERMATH:
                    ?>
                    <h1>
                        <?php
                        if (\logics\GameDataLocalizationLogic::containsByGameDataIdAndLocaleShort(
                            $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID],
                            \logics\further\LocalizationStore::getShortForLocale(\logics\LocalizationLogic::getCurrentLocale()))) {
                            echo \logics\GameDataLocalizationLogic::getHeadlineByGameDataIdAndLocaleShort(
                                $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID],
                                \logics\further\LocalizationStore::getShortForLocale(\logics\LocalizationLogic::getCurrentLocale()));
                        } else {
                            echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_HEADLINE];
                        }
                        ?>
                    </h1>
                    <h2>
                        <?php
                        $val1 = \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_PART1);
                        if (strlen($val1) > 0) {
                            echo $val1 . " ";
                        }
                        if ($_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_IS_ONION]) {
                            echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_TERM_THE_ONION);
                        } else {
                            echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_TERM_NOT_THE_ONION);
                        }
                        $val2 = \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_PART2);
                        if (strlen($val2) > 0) {
                            echo " " . $val2;
                        }
                        if (!$is_watcher) {
                            if ($_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_USER_ANSWER_WAS_CORRECT]) {
                                echo " - " . \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_ANSWER_CORRECT);
                            } else {
                                echo " - " . \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_ANSWER_WRONG);
                            }
                        }
                        ?>
                    </h2>
                    <img src="<?php
                    echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_PICTURE];
                    ?>"/>
                    <p>
                        <?php
                        if (\logics\GameDataLocalizationLogic::containsByGameDataIdAndLocaleShort(
                            $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID],
                            \logics\further\LocalizationStore::getShortForLocale(\logics\LocalizationLogic::getCurrentLocale()))) {
                            echo \logics\GameDataLocalizationLogic::getFurtherExplanationByGameDataIdAndLocaleShort(
                                $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID],
                                \logics\further\LocalizationStore::getShortForLocale(\logics\LocalizationLogic::getCurrentLocale()));
                        } else {
                            echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_FURTHER_EXPLANATION];
                        }
                        ?>
                    </p>
                    <a href="<?php
                    echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_HYPERLINK];
                    ?>" target="_blank"><?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_LINK_TO_POST);
                        ?></a>
                    <?php
                    if (!$is_watcher) {
                        ?>
                        <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST">
                            <input type="hidden" name="question_id" value="<?php
                            echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID];
                            ?>"/>
                            <input type="submit" name="upvote" value="<?php
                            echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_FORM_UPVOTE_SUBMIT_VALUE);
                            ?>"/>
                        </form>
                        <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST">
                            <input type="hidden" name="question_id" value="<?php
                            echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID];
                            ?>"/>
                            <input type="submit" name="downvote" value="<?php
                            echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_FORM_DOWNVOTE_SUBMIT_VALUE);
                            ?>"/>
                        </form>
                    <?php }
                    break;
                case \logics\LobbyLogic::STATE_END:
                    // HEADLINE END
                    ?>
                    <h1><?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_END_HEADLINE);
                        ?></h1>
                    <h2><?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_END_RANKING_HEADLINE);
                        ?></h2>
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
                                <?php echo $rank; ?>. <?php echo $ranked_user; ?> - <?php echo $points; ?> <?php
                                echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_END_RANKING_TERM_POINTS);
                                ?>
                            </ol>
                            <?php
                        }
                    } else {
                        ?><p><?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_END_RANKING_NO_ONE_HERE);
                        ?></p><?php
                    }
                    // NEXT ROUND BUTTON
                    if (!$is_watcher) {
                        ?>
                        <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST">
                            <input type="submit" name="next_round" value="<?php
                            echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_END_FORM_NEXT_ROUND_SUBMIT_VALUE);
                            ?>"/>
                        </form>
                        <?php
                    }
                    break;
                default:
            }
            ?>
            <h3><?php
                echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_PLAYERS_HEADLINE);
                ?></h3>
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
                            echo " - " . $user[\controllers\GameController::USERS_LIST_POINTS] . " ";
                            echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_PLAYERS_TERM_POINTS);
                        } else {
                            echo " (";
                            echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_PLAYERS_TERM_WATCHING);
                            echo ")";
                        }
                        ?></li><?php
                    }
                    ?>
                </ul>
                <?php
            } else {
                ?><p><?php
                echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_PLAYERS_NO_ONE_HERE);
                ?></p><?php
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
                            $val1 = \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_QUESTION_SECTION_TIME_REMAINING_PART_1);
                            if (strlen($val1) > 0) {
                                echo $val1 . " ";
                            }
                            echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_REMAINING_TIME];
                            $val2 = \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_QUESTION_SECTION_TIME_REMAINING_PART_2);
                            if (strlen($val2) > 0) {
                                echo " " . $val2;
                            }
                            ?>
                        </section> <?php
                    }
                    break;
                case \logics\LobbyLogic::STATE_AFTERMATH:
                    if (isset($_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_REMAINING_TIME])) {
                        ?>
                        <section><?php
                            $val1 = \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SECTION_TIME_REMAINING_PART_1);
                            if (strlen($val1) > 0) {
                                echo $val1 . " ";
                            }
                            echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_REMAINING_TIME];
                            $val2 = \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SECTION_TIME_REMAINING_PART_2);
                            if (strlen($val2) > 0) {
                                echo " " . $val2;
                            }
                            ?>
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
            <?php
            echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_ASIDE_JOIN_GAME_PART_1);
            ?> <strong><?php
                $urlprefix = \helper\VariousHelper::getUrlPrefix();
                if (strlen($urlprefix) >= strlen(strlen("https://")) && substr($urlprefix, 0, strlen("https://")) === "https://") {
                    $urlprefix = substr($urlprefix, strlen("https://"));
                } else if (strlen($urlprefix) >= strlen(strlen("http://")) && substr($urlprefix, 0, strlen("http://")) === "http://") {
                    $urlprefix = substr($urlprefix, strlen("http://"));
                }
                echo $urlprefix;
                ?></strong> <?php
            echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_ASIDE_JOIN_GAME_PART_2);
            ?> <strong><?php
                echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_LOBBY_CODE];
                ?></strong><?php
            $val = \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_ASIDE_JOIN_GAME_PART_3);
            if (strlen($val) > 0) {
                echo " " . $val;
            }
            ?>
        </aside>
        <?php
    }

}