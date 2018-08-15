<?php

namespace helper;

require_once(__DIR__ . "/../views/AbstractView.php");
require_once(__DIR__ . "/../helper/LogHelper.php");
require_once(__DIR__ . "/../logics/LocalizationLogic.php");
require_once(__DIR__ . "/../logics/further/LocalizationStore.php");
require_once("VariousHelper.php");

use \helper\VariousHelper as VariousHlp;
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
?><!DOCTYPE html>
<html lang="<?php echo \logics\further\LocalizationStore::getShortForLocale(\logics\LocalizationLogic::getCurrentLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Playfair+Display:400,700,900,400italic,700italic,900italic|Droid+Serif:400,700,400italic,700italic'
          rel='stylesheet' type='text/css'>
    <link rel="icon" href="<?php VariousHlp::printUrlPrefix(); ?>favicon.png">
    <?php
    }

    /**
     * A function to print the part of the header after the css includes and before the main to STDOUT.
     */
    public static function printPostHeader(bool $isRestrictedView = FALSE)
    {
    ?>
    <title><?php echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_VIEWFRAMEHELPER_HTML_HEAD_TITLE_TEXT); ?></title>
</head>
<body>
<header>
    <div id="header_headline">
        <?php echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_VIEWFRAMEHELPER_HTML_BODY_TITLE_TEXT); ?>
    </div>
    <div id="header_subheadline">
        <?php echo \logics\LocalizationLogic::get(\logics\further\LocalizationStore::ID_VIEWFRAMEHELPER_HTML_BODY_SUBTITLE_TEXT); ?>
    </div>
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
        <a href="<?php \helper\VariousHelper::printUrlPrefix(); ?>?lang=en">English / English</a>
        -
        <a href="<?php \helper\VariousHelper::printUrlPrefix(); ?>?lang=de">Deutsch / German</a>
    </nav>
    <p id="footer_copyright">
        &copy; 2018 Tiquthon
    </p>
</footer>
</body>
</html>
<?php
}
}