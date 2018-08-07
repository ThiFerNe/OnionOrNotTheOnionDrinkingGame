<?php

namespace helper;

require_once("LogHelper.php");
require_once("VariousHelper.php");

use \helper\LogHelper as LOG;
use \helper\VariousHelper as VariousHlp;

/*
 *
 * @package helper
 */

class PaginationHelper
{
    public static function getCssFiles()
    {
        return array("pagination.css");
    }

    public static function getPaginationHtml(int $currentPage, int $maxPageNr, string $linkPrefix,
                                             array $getValues, bool $appendPageAsDir = FALSE,
                                             string $getParamKey = "page", int $minPageNr = 1)
    {
        $preprepreviousPageNr = $currentPage - 3;
        $prepreviousPageNr = $currentPage - 2;
        $previousPageNr = $currentPage - 1;
        $nextPageNr = $currentPage + 1;
        $nenextPageNr = $currentPage + 2;
        $nenenextPageNr = $currentPage + 3;

        $returnHtml = "";

        if ($appendPageAsDir) {
            $myGetValues = array();
            if ($getValues !== NULL && $getValues !== FALSE) {
                $myGetValues = array_merge($myGetValues, $getValues);
            }
            $minPageLink = VariousHelper::joinURL(array($linkPrefix, $minPageNr)) . VariousHlp::assembleGetParam($myGetValues);
            $preprepreviousPageLink = VariousHelper::joinURL(array($linkPrefix, $preprepreviousPageNr)) . VariousHlp::assembleGetParam($myGetValues);
            $prepreviousPageNrLink = VariousHelper::joinURL(array($linkPrefix, $prepreviousPageNr)) . VariousHlp::assembleGetParam($myGetValues);
            $previousPageLink = VariousHelper::joinURL(array($linkPrefix, $previousPageNr)) . VariousHlp::assembleGetParam($myGetValues);
            $currentPageLink = VariousHelper::joinURL(array($linkPrefix, $currentPage)) . VariousHlp::assembleGetParam($myGetValues);
            $nextPageLink = VariousHelper::joinURL(array($linkPrefix, $nextPageNr)) . VariousHlp::assembleGetParam($myGetValues);
            $nenextPageLink = VariousHelper::joinURL(array($linkPrefix, $nenextPageNr)) . VariousHlp::assembleGetParam($myGetValues);
            $maxPageLink = VariousHelper::joinURL(array($linkPrefix, $nenenextPageNr)) . VariousHlp::assembleGetParam($myGetValues);
            $nenenextPageLink = VariousHelper::joinURL(array($linkPrefix, $maxPageNr)) . VariousHlp::assembleGetParam($myGetValues);
        } else {
            $myGetValues = array();
            if ($getValues !== NULL && $getValues !== FALSE) {
                $myGetValues = array_merge($myGetValues, $getValues);
            }
            if ($getParamKey !== NULL && $getParamKey !== FALSE) {
                $minPageLink = $linkPrefix . VariousHlp::assembleGetParam(array_merge($myGetValues, array($getParamKey => $minPageNr)));
                $preprepreviousPageLink = $linkPrefix . VariousHlp::assembleGetParam(array_merge($myGetValues, array($getParamKey => $preprepreviousPageNr)));
                $prepreviousPageNrLink = $linkPrefix . VariousHlp::assembleGetParam(array_merge($myGetValues, array($getParamKey => $prepreviousPageNr)));
                $previousPageLink = $linkPrefix . VariousHlp::assembleGetParam(array_merge($myGetValues, array($getParamKey => $previousPageNr)));
                $currentPageLink = $linkPrefix . VariousHlp::assembleGetParam(array_merge($myGetValues, array($getParamKey => $currentPage)));
                $nextPageLink = $linkPrefix . VariousHlp::assembleGetParam(array_merge($myGetValues, array($getParamKey => $nextPageNr)));
                $nenextPageLink = $linkPrefix . VariousHlp::assembleGetParam(array_merge($myGetValues, array($getParamKey => $nenextPageNr)));
                $nenenextPageLink = $linkPrefix . VariousHlp::assembleGetParam(array_merge($myGetValues, array($getParamKey => $nenenextPageNr)));
                $maxPageLink = $linkPrefix . VariousHlp::assembleGetParam(array_merge($myGetValues, array($getParamKey => $maxPageNr)));
            } else {
                $minPageLink = $linkPrefix . VariousHlp::assembleGetParam($myGetValues);
                $preprepreviousPageLink = $linkPrefix . VariousHlp::assembleGetParam($myGetValues);
                $prepreviousPageNrLink = $linkPrefix . VariousHlp::assembleGetParam($myGetValues);
                $previousPageLink = $linkPrefix . VariousHlp::assembleGetParam($myGetValues);
                $currentPageLink = $linkPrefix . VariousHlp::assembleGetParam($myGetValues);
                $nextPageLink = $linkPrefix . VariousHlp::assembleGetParam($myGetValues);
                $nenextPageLink = $linkPrefix . VariousHlp::assembleGetParam($myGetValues);
                $nenenextPageLink = $linkPrefix . VariousHlp::assembleGetParam($myGetValues);
                $maxPageLink = $linkPrefix . VariousHlp::assembleGetParam($myGetValues);
            }
        }

        $navStartHtml = <<<EOT
<nav class="pagination_container">
EOT;
        $minPageNrHtml = <<<EOT
<a href="{$minPageLink}" class="flat_pretty_button pagination_item pagination_item_min">{$minPageNr}</a>
EOT;
        $asideHtml = <<<EOT
<aside class="pagination_item pagination_item_aside">...</aside>
EOT;
        $preprepreviousPageNrHtml = <<<EOT
<a href="{$preprepreviousPageLink}" class="flat_pretty_button pagination_item pagination_item_prepreprevious">{$preprepreviousPageNr}</a>
EOT;
        $prepreviousPageNrHtml = <<<EOT
<a href="{$prepreviousPageNrLink}" class="flat_pretty_button pagination_item pagination_item_preprevious">{$prepreviousPageNr}</a>
EOT;
        $previousPageNrHtml = <<<EOT
<a href="{$previousPageLink}" class="flat_pretty_button pagination_item pagination_item_previous">{$previousPageNr}</a>
EOT;
        $currentPageHtml = <<<EOT
<a href="{$currentPageLink}" class="flat_pretty_button pagination_item pagination_item_current">{$currentPage}</a>
EOT;
        $nextPageNrHtml = <<<EOT
<a href="{$nextPageLink}" class="flat_pretty_button pagination_item pagination_item_next">{$nextPageNr}</a>
EOT;
        $nenextPageNrHtml = <<<EOT
<a href="{$nenextPageLink}" class="flat_pretty_button pagination_item pagination_item_nenext">{$nenextPageNr}</a>
EOT;
        $nenenextPageNrHtml = <<<EOT
<a href="{$nenenextPageLink}" class="flat_pretty_button pagination_item pagination_item_nenenext">{$nenenextPageNr}</a>
EOT;
        $maxPageNrHtml = <<<EOT
<a href="{$maxPageLink}" class="flat_pretty_button pagination_item pagination_item_max">{$maxPageNr}</a>
EOT;
        $navEndHtml = <<<EOT
</nav>
EOT;

        $returnHtml .= $navStartHtml;
        $returnHtml .= $minPageNrHtml;
        $returnHtml .= $asideHtml;
        if ($preprepreviousPageNr >= $minPageNr) {
            $returnHtml .= $preprepreviousPageNrHtml;
        }
        if ($prepreviousPageNr >= $minPageNr) {
            $returnHtml .= $prepreviousPageNrHtml;
        }
        if ($previousPageNr >= $minPageNr) {
            $returnHtml .= $previousPageNrHtml;
        }
        $returnHtml .= $currentPageHtml;
        if ($nextPageNr <= $maxPageNr) {
            $returnHtml .= $nextPageNrHtml;
        }
        if ($nenextPageNr <= $maxPageNr) {
            $returnHtml .= $nenextPageNrHtml;
        }
        if ($nenenextPageNr <= $maxPageNr) {
            $returnHtml .= $nenenextPageNrHtml;
        }
        $returnHtml .= $asideHtml;
        $returnHtml .= $maxPageNrHtml;
        $returnHtml .= $navEndHtml;

        return $returnHtml;
    }

    public static function printPaginationHtml(int $currentPage, int $maxPageNr, string $linkPrefix,
                                               array $getValues, bool $appendPageAsDir = FALSE,
                                               string $getParamKey = "page", int $minPageNr = 1)
    {
        echo self::getPaginationHtml($currentPage, $maxPageNr, $linkPrefix, $getValues, $appendPageAsDir, $getParamKey, $minPageNr);
    }
}