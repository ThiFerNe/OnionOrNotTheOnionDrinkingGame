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
        switch ($current_state) {
            case \logics\LobbyLogic::STATE_START:
                ?>
                <main>
                    <h2><?php echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_USER_NAME];?></h2>
                    <h3><?php
                        if ($is_watcher) {
                            ?>Watcher<?php
                        } else {
                            ?>Player<?php
                        }
                        ?></h3>
                    <a href="<?php \helper\VariousHelper::printUrlPrefix() ?>exit">Exit</a>
                    <h1>Welcome!</h1>
                    <?php FERequestAcrossMLogic::insertHTML(); ?>
                    <p>Waiting for others to join...</p>
                    <ul>
                        <li>username</li>
                        <li>username</li>
                        <li>username</li>
                        <li>username</li>
                    </ul>
                    <?php
                    if (!$is_watcher) {
                        ?>
                        <form action="<?php \helper\VariousHelper::printUrlPrefix(); ?>game" method="POST">
                            <input type="submit" name="start_game" value="START"/>
                        </form><?php
                    }
                    ?>

                </main>
            <aside>
                Session last active: <?php echo date("Y.m.d H:i:s", $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_LOBBY_LAST_ACTIVE]); ?><br/>
                Lobby last active: <?php echo date("Y.m.d H:i:s", $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_LOBBY_LAST_ACTIVE]); ?><br/>
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
                        ?></strong> and Code <strong><?php echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_LOBBY_CODE]; ?></strong>!
                </aside>
                <?php
                break;
            case \logics\LobbyLogic::STATE_QUESTION:
                ?>
                <main>
                    <h2>USERNAME</h2>
                    <a href="<?php \helper\VariousHelper::printUrlPrefix() ?>exit">Exit</a>
                    <h1>HEADLINE</h1>
                    <section>
                        <button>ONION</button>
                        <button>NOT THE ONION</button>
                    </section>
                    <section>
                        36 seconds to go!
                    </section>
                </main>
                <aside>
                    Session last active: <?php echo date("Y.m.d H:i:s", $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_LOBBY_LAST_ACTIVE]); ?><br/>
                    Lobby last active: <?php echo date("Y.m.d H:i:s", $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_LOBBY_LAST_ACTIVE]); ?><br/>
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
                        ?></strong> and Code <strong><?php echo $_RESPONSE[\controllers\GameController::PREFIX . \controllers\GameController::SUFFIX_LOBBY_CODE]; ?></strong>!
                </aside>
                <?php
                break;
            case \logics\LobbyLogic::STATE_AFTERMATH:
                ?>AFTERMATH <?php FERequestAcrossMLogic::insertHTML(); ?><?php
                break;
            case \logics\LobbyLogic::STATE_END:
                ?>END <?php FERequestAcrossMLogic::insertHTML(); ?><?php
                break;
            default:
                ?>DEFAULT <?php FERequestAcrossMLogic::insertHTML(); ?><?php
        }
    }

}