<?php

namespace helper;

require_once(__DIR__ . "/../views/AbstractView.php");
require_once(__DIR__ . "/../logics/SessionLogic.php");
require_once(__DIR__ . "/../logics/UserLogic.php");
require_once(__DIR__ . "/../logics/ProductLogic.php");
require_once(__DIR__ . "/../logics/ShoppingCartLogic.php");
require_once(__DIR__ . "/../logics/CommodityGroupLogic.php");
require_once(__DIR__ . "/../helper/LogHelper.php");
require_once("VariousHelper.php");

use \helper\VariousHelper as VariousHlp;
use \logics\SessionLogic as SessionLgc;
use \logics\ProductLogic as ProductLgc;
use \logics\ShoppingCartLogic as ShoppingCartLgc;
use \logics\UserLogic as UserLgc;
use \logics\CommodityGroupLogic as CommodityGroupLgc;
use \helper\LogHelper as LOG;

/**
 * A helper to print header and footer for the remaining views.
 *
 * @package views
 */
class ViewFrameHelper
{
    /**
     * A function to print the part of the header before the css includes to STDOUT.
     */
    public static function printPreHeader(bool $isRestrictedView = FALSE)
    {
        ?>
        <!DOCTYPE html>
        <html lang="de">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="<?php VariousHlp::printUrlPrefix(); ?>favicon.png">
        <link rel="import" href="<?php VariousHlp::printUrlPrefix(); ?>cmps/simple-list.html"/>
        <link rel="stylesheet" type="text/css" href="<?php VariousHlp::printUrlPrefix(); ?>css/flat_pretty_button.css">
        <link rel="stylesheet" type="text/css" href="<?php VariousHlp::printUrlPrefix(); ?>css/std_layout.css">
        <script type="application/javascript"
                src="<?php VariousHlp::printUrlPrefix(); ?>js/searchboxpreview.js"></script>
        <script type="application/javascript"
                src="<?php VariousHlp::printUrlPrefix(); ?>js/shoppingcartpopup.js"></script>
        <?php
    }

    private static function printShoppingCartPreview(string $idForThisComponent)
    {
        ?>
        <simple-list id="<?php echo $idForThisComponent; ?>"
                     class="closed_popup"
                     style="background-color: white;">
            <simple-item>
                <p style="font-style: italic; margin: 0; color: #999999;">Loading...</p>
            </simple-item>
        </simple-list>
        <?php
    }

    /**
     * A function to print the part of the header after the css includes and before the main to STDOUT.
     */
public static function printPostHeader(bool $isRestrictedView = FALSE)
{
    ?>
    <title>Toy Models GmbH Standard Layout</title>
</head>
<body>
    <header>
        <a href="<?php VariousHlp::printUrlPrefix(); ?>" id="header_logo_link">
            <img src="<?php VariousHlp::printUrlPrefix(); ?>images/headline_logo_long.png"
                 id="header_logo_image" alt="Toy Models GmbH Logo">
        </a>
        <?php
        if (!$isRestrictedView && SessionLgc::isLoggedIn()) {
            ?>
            <section id="header_logged_in_panel">
                <section id="header_logged_in_panel_internal">
                    <a href="<?php VariousHlp::printUrlPrefix(); ?>account" id="header_logged_in_panel_welcometext"
                       class="flat_pretty_button">
                        Willkommen, <?php echo trim(UserLgc::getPrenameForUID(SessionLgc::getLoggedInUsername())); ?>!
                    </a>
                    <a href="<?php VariousHlp::printUrlPrefix(); ?>logout" id="header_logged_in_panel_logout_link"
                       class="flat_pretty_button">Abmelden</a>
                    <a href="<?php VariousHlp::printUrlPrefix(); ?>shoppingcart"
                       id="header_logged_in_panel_shopping_cart" class="flat_pretty_button"
                       onmouseenter="showShoppingCartPopUp()" onmouseleave="hideShoppingCartPopUp()">&#128722;</a>
                    <a href="<?php VariousHlp::printUrlPrefix(); ?>shoppingcart"
                       id="header_logged_in_panel_shopping_cart_text" class="flat_pretty_button"
                       onmouseenter="showShoppingCartPopUp()" onmouseleave="hideShoppingCartPopUp()">&#128722;
                        Warenkorb</a>
                </section>
                <?php self::printShoppingCartPreview("header_logged_in_panel_shoppingcart_preview_popup"); ?>
            </section>
            <?php
        } else {
            ?>
            <form action="<?php VariousHlp::printUrlPrefix(); ?>login" method="POST"
                  id="header_login_form">
                <section id="header_login_form_internal" class="header_login_form_internal_closed_popup">
                    <button type="submit" name="submitlogin" value="Anmelden" id="header_login_submit"
                            class="flat_pretty_button">Anmelden
                    </button>
                    <!--
                        The following input field for the username is NOT a number input and does NOT have a pattern,
                        because this sensible information about the format of a username should not be directly visible.
                    -->
                    <input type="text" name="username" id="header_login_username" placeholder="Kundennummer" required>
                    <input type="password" name="password" id="header_login_password" placeholder="Passwort" required>
                    <input type="hidden" name="referto" value="index"/>
                    <p id="header_login_register" class="header_login_register_closed_popup">oder <a
                                href="<?php VariousHlp::printUrlPrefix(); ?>register">registrieren</a></p>
                    <a href="<?php VariousHlp::printUrlPrefix(); ?>shoppingcart"
                       id="header_login_shopping_cart_text"
                       class="flat_pretty_button header_login_shopping_cart_text_closed_popup"
                       onmouseenter="showShoppingCartPopUp()" onmouseleave="hideShoppingCartPopUp()">&#128722;
                        Warenkorb</a>
                </section>
                <?php self::printShoppingCartPreview("header_login_form_shoppingcart_preview_popup"); ?>
            </form>
            <section id="header_login_mobile">
                <section id="header_login_mobile_internal" class="header_login_mobile_internal_closed_popup">
                    <a href="<?php VariousHlp::printUrlPrefix(); ?>login"
                       id="header_login_mobile_login" class="flat_pretty_button header_login_mobile_login_closed_popup">Anmelden</a>
                    <a href="<?php VariousHlp::printUrlPrefix(); ?>register"
                       id="header_login_mobile_register"
                       class="flat_pretty_button">Registrieren</a>
                    <a href="<?php VariousHlp::printUrlPrefix(); ?>shoppingcart"
                       id="header_login_mobile_shopping_cart"
                       class="flat_pretty_button header_login_mobile_shopping_cart_closed_popup"
                       onmouseenter="showShoppingCartPopUp()" onmouseleave="hideShoppingCartPopUp()">&#128722;</a>
                    <a href="<?php VariousHlp::printUrlPrefix(); ?>shoppingcart"
                       id="header_login_mobile_shopping_cart_text"
                       class="flat_pretty_button header_login_mobile_shopping_cart_text_closed_popup"
                       onmouseenter="showShoppingCartPopUp()" onmouseleave="hideShoppingCartPopUp()">&#128722;
                        Warenkorb</a>
                </section>
                <?php self::printShoppingCartPreview("header_login_mobile_shoppingcart_preview_popup"); ?>
            </section>
            <?php
        }
        ?>
        <section id="header_searchbar">
            <form action="<?php VariousHlp::printUrlPrefix(); ?>search" method="GET"
                  id="header_searchbox_form" class="header_searchbox_form_opened_search_preview">
                <input type="search" name="for" placeholder="Suchbegriff hier eingeben" autofocus
                       autocomplete="off" id="header_searchtextfield"
                       class="header_searchtextfield_opened_search_preview" value="<?php
                if (!empty($_GET["for"])) {
                    echo htmlspecialchars($_GET["for"]);
                }
                ?>" onkeyup="updateSearchboxPreview('<?php require_once("VariousHelper.php");
                \helper\VariousHelper::printUrlPrefix(); ?>api/v1/search/preview')"
                       onblur="hideSearchboxPreview()" onfocus="showSearchboxPreview()">
                <button type="submit" id="header_searchbutton"
                        class="flat_pretty_button header_searchbutton_opened_search_preview">&#128269;
                </button>
            </form>
            <ul id="header_searchbox_preview" style="display: none;">
                <li style='font-style: italic; color: #777777;'>Nichts gefunden</li>
            </ul>
        </section>
        <nav id="header_navigation">
            <?php
            if ($isRestrictedView) {
                ?>
                <a href="<?php VariousHlp::printUrlPrefix(); ?>search?for="
                   class="header_navigation_item flat_pretty_button">n/a</a>
                <a href="<?php VariousHlp::printUrlPrefix(); ?>search?for="
                   class="header_navigation_item flat_pretty_button">n/a</a>
                <a href="<?php VariousHlp::printUrlPrefix(); ?>search?for="
                   class="header_navigation_item flat_pretty_button">n/a</a>
                <a href="<?php VariousHlp::printUrlPrefix(); ?>search?for="
                   class="header_navigation_item flat_pretty_button">n/a</a>
                <?php
            } else {
                try {
                    $commodityGroups = CommodityGroupLgc::getAllCommodityGroupNames();
                    if ($commodityGroups === FALSE || $commodityGroups === NULL || sizeof($commodityGroups) <= 0) {
                        throw new \Exception("Did not get any commodity groups.");
                    }
                    foreach ($commodityGroups as $currentCommodityGroup) {
                        ?>
                        <a href="<?php VariousHlp::printUrlPrefix(); ?>search?for=warengruppe:<?php echo $currentCommodityGroup; ?>"
                           class="header_navigation_item flat_pretty_button"><?php echo $currentCommodityGroup; ?></a>
                        <?php
                    }
                } catch (\Exception $e) {
                    LOG::ERROR("Could not retrieve the commodity groups ({$e->getMessage()})");
                }
            }
            ?>
        </nav>
    </header>
    <?php
}

    /**
     * A function to print the footer and the rest to STDOUT.
     */
    public static function printFooter(bool $isRestrictedView = FALSE)
    {
        ?>
        <footer>
            <nav id="footer_links">
                <a href="<?php VariousHlp::printUrlPrefix(); ?>about">Über</a> / <a
                        href="<?php VariousHlp::printUrlPrefix(); ?>impressum">Impressum</a>
            </nav>
            <p id="footer_copyright">
                &copy; 2018 Toy Models GmbH
            </p>
        </footer>
        <script type="application/javascript">
            updateSearchboxPreview('<?php require_once("VariousHelper.php");
                \helper\VariousHelper::printUrlPrefix(); ?>api/v1/search/preview');
            updateShoppingCartPopup('<?php require_once("VariousHelper.php");
                \helper\VariousHelper::printUrlPrefix(); ?>api/v1/shoppingcart/items?sessionid=<?php echo session_id(); ?>');
            window.setInterval(function () {
                updateShoppingCartPopup('<?php require_once("VariousHelper.php");
                    \helper\VariousHelper::printUrlPrefix(); ?>api/v1/shoppingcart/items?sessionid=<?php echo session_id(); ?>')
            }, 1500);
        </script>
        </body>
        </html>
        <?php
    }
}