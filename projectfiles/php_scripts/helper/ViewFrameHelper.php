<?php

namespace helper;

require_once(__DIR__ . "/../views/AbstractView.php");
require_once(__DIR__ . "/../helper/LogHelper.php");
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php
    }

    /**
     * A function to print the part of the header after the css includes and before the main to STDOUT.
     */
    public static function printPostHeader(bool $isRestrictedView = FALSE)
    {
    ?>
    <title>Onion Or Not The Onion Drinking Game</title>
</head>
<body>
<header>
    <h1>Onion Or Not The Onion Drinking Game</h1>
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
    <p id="footer_copyright">
        &copy; 2018 Tiquthon
    </p>
</footer>
</body>
</html>
<?php
}
}