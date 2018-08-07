<?php

namespace views;

require_once(__DIR__ . "/../helper/ViewFrameHelper.php");
require_once(__DIR__ . "/../helper/LogHelper.php");
require_once(__DIR__ . "/../helper/VariousHelper.php");

use \helper\LogHelper as LOG;
use \helper\ViewFrameHelper as ViewFrameHelper;

/**
 * This abstract class serves as a parent class for the different available views inside this application.
 * Each child of this class has a different output for the main part and the css files part of the returned HTML document.
 *
 * @package views
 */
abstract class AbstractView
{
    /**
     * This function just returns an array of CSS files which should be included when this HTML document is being created.
     * Only the filenames are needed. The full folderpath is added at creation.
     *
     * @return array
     */
    protected abstract function getCssIncludeFiles();

    /**
     * This function returns nothing. It prints its contents to the STDOUT.
     */
    protected abstract function printMain();

    /**
     * Returns if this View has to have a restricted view.
     * For example a 500 error document.
     * This is mainly used to stop calling functions inside the header and footer functions.
     *
     * @return bool If this site is restricted.
     */
    protected abstract function isRestrictedView();

    /**
     * This function combines multiple functions.
     *
     * Primarily it concatenates HEADER, MAIN and FOOTER parts of the final page.
     */
    public function print()
    {
        ViewFrameHelper::printPreHeader($this->isRestrictedView());
        foreach ($this->getCssIncludeFiles() as $wanted_css_include_file) {
            ?>
            <link rel="stylesheet" type="text/css"
                  href="<?php \helper\VariousHelper::printUrlPrefix(); ?>css/<?php echo $wanted_css_include_file; ?>">
            <?php
        }
        ViewFrameHelper::printPostHeader($this->isRestrictedView());
        $this->printMain();
        ViewFrameHelper::printFooter($this->isRestrictedView());
    }
}