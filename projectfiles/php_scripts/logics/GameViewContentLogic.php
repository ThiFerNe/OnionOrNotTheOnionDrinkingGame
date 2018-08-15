<?php

namespace logics;

require_once(__DIR__ . "/../helper/VariousHelper.php");

require_once(__DIR__ . "/../controllers/GameController.php");

require_once(__DIR__ . "/../helper/LogHelper.php");

require_once(__DIR__ . "/../logics/FrontEndRequestAcrossMessagesLogic.php");
require_once(__DIR__ . "/../logics/LocalizationLogic.php");
require_once(__DIR__ . "/../logics/further/LocalizationStore.php");
require_once(__DIR__ . "/../logics/GameViewContentLogic.php");

use \helper\LogHelper as LOG;

class GameViewContentLogic
{
    public static function betterPrintContent()
    {
        (new \controllers\GameController())->action("");
        self::printContent();
    }

    public static function printContent()
    {
        global $_RESPONSE;
        $current_state = $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_CURRENT_STATE];
        $is_watcher = $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_IS_WATCHER];
        ?>
        <h2 id="player_name_headline">
            <?php echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_USER_NAME]; ?>
        </h2>
        <p id="player_type_and_exit">
                    <span id="player_type">
                    <?php
                    if ($is_watcher) {
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_TYPE_OF_PLAYER_WATCHER);
                    } else {
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_TYPE_OF_PLAYER_PLAYER);
                    }
                    ?></span>
            |
            <a href="<?php \helper\VariousHelper::printUrlPrefix() ?>exit" class="btn" id="exit_game_link"><?php
                echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_EXIT_THE_GAME);
                ?></a>
        </p>
        <?php
        switch ($current_state) {
            case \logics\LobbyLogic::STATE_START:
                break;
            case \logics\LobbyLogic::STATE_QUESTION:
                LOG::TRACE("STATE QUESTION");
                if (isset($_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_REMAINING_TIME])) {
                    LOG::TRACE("RESPONSE");
                    ?>
                    <section id="remaining_time_question_state"><?php
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
                } else {
                    LOG::TRACE("NOPE");
                }
                break;
            case \logics\LobbyLogic::STATE_AFTERMATH:
                LOG::TRACE("STATE AFTERMATH");
                if (isset($_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_REMAINING_TIME])) {
                    ?>
                    <section id="remaining_time_aftermath_state"><?php
                        $val1 = \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SECTION_TIME_REMAINING_PART_1);
                        if (strlen($val1) > 0) {
                            echo $val1 . " ";
                        }
                        echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_REMAINING_TIME];
                        $val2 = \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SECTION_TIME_REMAINING_PART_2);
                        if (strlen($val2) > 0) {
                            echo " " . $val2;
                        }
                        ?><?php
                        if (!$is_watcher) {
                            if (\logics\SessionLogic::isWantsToSkipAftermathByUserSessionId(
                                \logics\SessionLogic::getUserSessionIdByPhpSessionId(session_id()))
                            ) {
                                ?> - Skipping...<?php
                            } else {
                                ?> - <a href="<?php \helper\VariousHelper::printUrlPrefix(); ?>game?skip=true">
                                    Skip</a><?php
                            }
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
        <?php
        switch ($current_state) {
            case \logics\LobbyLogic::STATE_START:
                ?>
                <h1 id="welcome_headline"><?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_START_HEADLINE_WELCOME);
                    ?></h1>
                <?php
                if (!$is_watcher) {
                    ?>
                    <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST"
                          id="start_form">
                        <input type="submit" name="start_game" id="start_form_submit_button" value="<?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_START_FORM_START_GAME_SUBMIT_VALUE);
                        ?>"/>
                    </form>
                    <?php
                }
                break;
            case \logics\LobbyLogic::STATE_QUESTION:
                ?>
                <h1 id="question_headline_at_question_state" class="question_headline">
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
                    <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST"
                          id="question_the_onion_form">
                        <input type="hidden" name="question_id" value="<?php
                        echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID];
                        ?>"/>
                        <input type="submit" name="set_onion" class="<?php
                        if (\logics\SessionLogic::getActualAnswerIsOnionByUserSessionId(\logics\SessionLogic::getUserSessionIdByPhpSessionId(session_id())) == 1) {
                            echo "button_chosen_outline";
                        }
                        ?>" id="question_the_onion_form_submit_button"
                               value="<?php
                               echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_QUESTION_FORM_SET_ONION_SUBMIT_VALUE);
                               ?>"/>
                    </form>
                    <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST"
                          id="question_not_the_onion_form">
                        <input type="hidden" name="question_id" value="<?php
                        echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID];
                        ?>"/>
                        <input type="submit" name="set_not_the_onion" class="<?php
                        if (\logics\SessionLogic::getActualAnswerIsOnionByUserSessionId(\logics\SessionLogic::getUserSessionIdByPhpSessionId(session_id())) == -1) {
                            echo "button_chosen_outline";
                        }
                        ?>" id="question_not_the_onion_form_submit_button" value="<?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_QUESTION_FORM_SET_NOT_ONION_SUBMIT_VALUE);
                        ?>"/>
                    </form>
                <?php }
                break;
            case \logics\LobbyLogic::STATE_AFTERMATH:
                ?>
                <?php
                $is_onion = $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_IS_ONION];
                $userAnswerCorrect = $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_USER_ANSWER_WAS_CORRECT];
                $additionalQuestionResultClass = "";
                if ($is_watcher) {
                    if ($is_onion) {
                        $additionalQuestionResultClass = "question_result_correct";
                    } else {
                        $additionalQuestionResultClass = "question_result_wrong";
                    }
                } else {
                    if ($userAnswerCorrect) {
                        $additionalQuestionResultClass = "question_result_correct";
                    } else {
                        $additionalQuestionResultClass = "question_result_wrong";
                    }
                }
                ?>
                <p id="question_result" class="<?php echo $additionalQuestionResultClass; ?>">
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
                            echo "<br/>" . \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_ANSWER_CORRECT);
                        } else {
                            echo "<br/>" . \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_ANSWER_WRONG);
                        }
                    }
                    ?>
                </p>
                <h1 id="question_headline_at_aftermath_state" class="question_headline">
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
                <section id="question_subline">
                    <a id="question_subline_link_to_post" href="<?php
                    echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_HYPERLINK];
                    ?>" target="_blank"><?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_LINK_TO_POST);
                        ?></a>
                    <?php
                    if (!$is_watcher) {
                        ?>
                        <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST"
                              id="question_section_downvote_form">
                            <input type="hidden" name="question_id" value="<?php
                            echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID];
                            ?>"/>
                            <input type="submit" name="downvote" class="<?php
                            if (\logics\SessionHasVotedForGameDataLogic::contains($_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID], \logics\SessionLogic::getUserSessionIdByPhpSessionId(session_id())) &&
                                \logics\SessionHasVotedForGameDataLogic::isUpvote($_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID], \logics\SessionLogic::getUserSessionIdByPhpSessionId(session_id())) == FALSE) {
                                echo "button_chosen_outline";
                            }
                            ?>" id="question_section_downvote_submit_button"
                                   value="<?php
                                   echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_FORM_DOWNVOTE_SUBMIT_VALUE);
                                   ?>"/>
                        </form>
                        <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST"
                              id="question_section_upvote_form">
                            <input type="hidden" name="question_id" value="<?php
                            echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID];
                            ?>"/>
                            <input type="submit" name="upvote" class="<?php
                            if (\logics\SessionHasVotedForGameDataLogic::contains($_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID], \logics\SessionLogic::getUserSessionIdByPhpSessionId(session_id())) &&
                                \logics\SessionHasVotedForGameDataLogic::isUpvote($_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_ID], \logics\SessionLogic::getUserSessionIdByPhpSessionId(session_id())) == TRUE) {
                                echo "button_chosen_outline";
                            }
                            ?>" id="question_section_upvote_submit_button"
                                   value="<?php
                                   echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_FORM_UPVOTE_SUBMIT_VALUE);
                                   ?>"/>
                        </form>
                    <?php }
                    ?>
                </section>
                <img id="question_picture" src="<?php
                echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_QUESTION_PICTURE];
                ?>"/>
                <?php
                break;
            case \logics\LobbyLogic::STATE_END:
                // HEADLINE END
                ?>
                <h1 id="question_headline_at_end_state"><?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_END_HEADLINE);
                    ?></h1>
                <?php
                // NEXT ROUND BUTTON
                if (!$is_watcher) {
                    ?>
                    <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST"
                          id="play_again_form">
                        <input type="submit" name="next_round" id="play_again_submit_button" value="<?php
                        echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_END_FORM_NEXT_ROUND_SUBMIT_VALUE);
                        ?>"/>
                    </form>
                    <?php
                }
                ?>
                <h2 id="ranking_headline_at_end_state"><?php
                echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_END_RANKING_HEADLINE);
                ?></h2><?php
                // RANKING
                $sorted_playing_user_ranking = $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_END_RANKING];
                if (count($sorted_playing_user_ranking) > 0) {
                    ?>
                    <ol id="list_or_paragraph_ranking"><?php
                    for ($i = 1; $i <= count($sorted_playing_user_ranking); $i++) {
                        $rank = $sorted_playing_user_ranking[$i]["rank"];
                        $ranked_user = $sorted_playing_user_ranking[$i]["name"];
                        $points = $sorted_playing_user_ranking[$i]["points"];
                        ?>
                        <li class="<?php
                        if ($rank == "1") {
                            echo "list_or_paragraph_ranking_place_one";
                        } else if ($rank == "2") {
                            echo "list_or_paragraph_ranking_place_two";
                        } else if ($rank == "3") {
                            echo "list_or_paragraph_ranking_place_three";
                        } else {
                            echo "list_or_paragraph_ranking_place_others";
                        }
                        ?>">
                            <?php echo $rank; ?>. <?php echo $ranked_user; ?> - <?php echo $points; ?> <?php
                            echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_END_RANKING_TERM_POINTS);
                            ?>
                        </li>
                        <?php
                    }
                    ?></ol><?php
                } else {
                    ?><p id="list_or_paragraph_ranking"><?php
                    echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_STATE_END_RANKING_NO_ONE_HERE);
                    ?></p><?php
                }
                break;
            default:
        }
        ?>
        <h3 id="playerlist_headline"><?php
            echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_PLAYERS_HEADLINE);
            ?></h3>
        <?php
        if (count($_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_USERS_LIST]) > 0) {
            ?>
            <ul id="playerlist_listing_or_paragraph">
                <?php
                foreach ($_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_USERS_LIST] as $user) {
                    ?>
                    <li>
                    <span class="span_username<?php
                    if ($user[\controllers\GameController::USERS_LIST_USERID] == \logics\SessionLogic::getUserSessionIdByPhpSessionId(session_id())) {
                        echo " span_actual_user_is_username";
                    }
                    ?>"><?php
                        echo $user[\controllers\GameController::USERS_LIST_USERNAME];
                        ?></span><?php
                    if (\logics\SessionLogic::isWantsToSkipAftermathByUserSessionId($user[\controllers\GameController::USERS_LIST_USERID])) {
                        ?><span class="span_user_wants_to_skip"><img
                                src="<?php \helper\VariousHelper::printUrlPrefix(); ?>images/fastforward.png"/>
                        </span><?php
                    }
                    if ($user[\controllers\GameController::USERS_LIST_HAS_ANSWERED]) {
                        ?><span class="span_user_has_answered"><img
                                src="<?php \helper\VariousHelper::printUrlPrefix(); ?>images/pencil_icon.png"/>
                        </span><?php
                        if ($current_state == \logics\LobbyLogic::STATE_AFTERMATH) {
                            ?><span class="span_users_answer<?php
                            if ($user[\controllers\GameController::USERS_LIST_HAS_ANSWERED_CORRECT]) {
                                echo " span_users_answer_is_correct";
                            } else {
                                echo " span_users_answer_is_wrong";
                            }
                            ?>"><?php
                            if ($user[\controllers\GameController::USERS_LIST_HAS_ANSWERED_CORRECT]) {
                                ?><img src="<?php \helper\VariousHelper::printUrlPrefix(); ?>images/correct.png"/><?php
                            } else {
                                ?><img
                                src="<?php \helper\VariousHelper::printUrlPrefix(); ?>images/incorrect.png"/><?php
                            }
                            ?></span><?php
                        }
                    }
                    ?><span class="span_points_or_watching"><?php
                        if (!$user[\controllers\GameController::USERS_LIST_IS_WATCHING]) {
                            echo $user[\controllers\GameController::USERS_LIST_POINTS] . " ";
                            echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_PLAYERS_TERM_POINTS);
                        } else {
                            echo " (";
                            echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_PLAYERS_TERM_WATCHING);
                            echo ")";
                        }
                        ?></span>
                    </li><?php
                }
                ?>
            </ul>
            <?php
        } else {
            ?><p id="playerlist_listing_or_paragraph"><?php
            echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_MAIN_PLAYERS_NO_ONE_HERE);
            ?></p><?php
        }
        ?><?php
    }
}