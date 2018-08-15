<?php

namespace views;

require_once("AbstractView.php");

require_once(__DIR__ . "/../helper/VariousHelper.php");

require_once(__DIR__ . "/../controllers/GameController.php");

require_once(__DIR__ . "/../logics/FrontEndRequestAcrossMessagesLogic.php");
require_once(__DIR__ . "/../logics/LocalizationLogic.php");
require_once(__DIR__ . "/../logics/further/LocalizationStore.php");
require_once(__DIR__ . "/../logics/GameViewContentLogic.php");

class GameView extends AbstractView
{
    public function getCssIncludeFiles()
    {
        return array("style.css", "game.css");
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
            <aside id="join_panel">
                <?php
                echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_GAMEVIEW_BODY_ASIDE_JOIN_GAME_PART_1);
                ?> <strong><?php
                    $urlprefix = \helper\VariousHelper::getUrlPrefix();
                    if (strlen($urlprefix) >= strlen(strlen("https://")) && substr($urlprefix, 0, strlen("https://")) === "https://") {
                        $urlprefix = substr($urlprefix, strlen("https://"));
                    } else if (strlen($urlprefix) >= strlen(strlen("http://")) && substr($urlprefix, 0, strlen("http://")) === "http://") {
                        $urlprefix = substr($urlprefix, strlen("http://"));
                    }
                    while (strlen($urlprefix) > 0 && substr($urlprefix, strlen($urlprefix) - 1) == "/") {
                        $urlprefix = substr($urlprefix, 0, strlen($urlprefix) - 1);
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
            <section id="main_wrapper">
                <?php \logics\GameViewContentLogic::printContent(); ?>
            </section>
        </main>
        <script>
            function updateMainWrapper() {
                fetch('<?php \helper\VariousHelper::printUrlPrefix(); ?>api/v1/game/mainwrapper')
                    .then(function (response) {
                            if (response.ok) {
                                response.text().then(function (result) {
                                    document.getElementById("main_wrapper").innerHTML = result;
                                });
                            }
                        }
                    )
                ;
            }
            setInterval(function () {
                updateMainWrapper();
            }, 1000);
        </script>
        <?php
    }

}