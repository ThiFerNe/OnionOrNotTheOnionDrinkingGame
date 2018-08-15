<?php

namespace helper;

require_once(__DIR__ . "/../helper/LogHelper.php");

use \helper\LogHelper as LOG;


class VariousHelper
{
    public static function reduceStringIfTooLong(string $value, int $maximumLength, string $appendIfTooLong) {
        if(strlen($value) > $maximumLength) {
            if(strlen($appendIfTooLong) >= $maximumLength) {
                return $appendIfTooLong;
            }
            $value = substr($value, 0, $maximumLength - strlen($appendIfTooLong));
            $value .= $appendIfTooLong;
        }
        return $value;
    }

    public static function realPathPrefixAndExistenceCheck(string $path, string $pathPrefix)
    {
        $pathRealpath = realpath($path);
        $pathprefixRealpath = realpath($pathPrefix);
        $pathRealpathSubstr = substr($pathRealpath, 0, strlen($pathprefixRealpath));
        if ($pathRealpathSubstr === $pathprefixRealpath) {
            if (file_exists($pathprefixRealpath)) {
                return $pathRealpath;
            }
        }
        return FALSE;
    }

    public static function getRequestTopUri()
    {
        return self::getRequestTopUriByHtaccess();
    }

    private static function getRequestTopUriByHtaccess()
    {
        return self::getRequestUriByHtaccess()[0];
    }

    private static function getRequestTopUriByServerRequestUri()
    {
        return self::getRequestUriByServerRequestUri()[0];
    }

    public static function getRequestSubUri()
    {
        return self::getRequestSubUriByHtaccess();
    }

    private static function getRequestSubUriByHtaccess()
    {
        return self::getRequestUriByHtaccess()[1];
    }

    private static function getRequestSubUriByServerRequestUri()
    {
        return self::getRequestUriByServerRequestUri()[1];
    }

    private static function getRequestUriByHtaccess()
    {
        LOG::DEBUG("GETTING REQUEST TOP AND SUB BY HTACCESS:");
        $requestFrontcontrollerUri = $_GET["fcr"];
        if (strlen($requestFrontcontrollerUri) === 0) {
            return array("/", "");
        }
        LOG::DEBUG("REQUEST FC URI           : {$requestFrontcontrollerUri}");
        $indexFirstSlash = strpos($requestFrontcontrollerUri, "/");
        LOG::DEBUG("INDEX FIRST SLASH        : {$indexFirstSlash}");
        if ($indexFirstSlash === FALSE || $indexFirstSlash < 0) {
            $requestTopUri = "/" . $requestFrontcontrollerUri;
            $requestSubUri = "";
        } else {
            $requestTopUri = "/" . substr($requestFrontcontrollerUri, 0, $indexFirstSlash);
            $requestSubUri = substr($requestFrontcontrollerUri, $indexFirstSlash);
        }
        LOG::DEBUG("REQUEST TOP URI          : {$requestTopUri}");
        LOG::DEBUG("REQUEST SUB URI          : {$requestSubUri}");
        return array($requestTopUri, $requestSubUri);
    }

    private static function getRequestUriByServerRequestUri()
    {
        LOG::DEBUG("GETTING REQUEST TOP AND SUB BY CONTEXT PREFIX:");
        $requestUri = $_SERVER["REQUEST_URI"];
        LOG::DEBUG("REQUEST URI              : {$requestUri}");
        $contextPrefix = $_SERVER["CONTEXT_PREFIX"];
        LOG::DEBUG("CONTEXT PREFIX           : {$contextPrefix}");
        $indexContextPrefixEnd = strlen($contextPrefix);
        LOG::DEBUG("INDEX CONTEXT PREFIX     : {$indexContextPrefixEnd}");
        $indexSlashAfterPrefix = strpos($requestUri, "/", $indexContextPrefixEnd + 1);
        LOG::DEBUG("INDEX / AFTER PREFIX     : {$indexSlashAfterPrefix}");
        $indexQuestionMarkAfterPrefix = strpos($requestUri, "?", $indexContextPrefixEnd);
        LOG::DEBUG("INDEX ? AFTER PREFIX     : {$indexQuestionMarkAfterPrefix}");
        $requestTopUri = $indexSlashAfterPrefix === FALSE ?
            (
            $indexQuestionMarkAfterPrefix === FALSE ?
                substr($requestUri, $indexContextPrefixEnd) :
                substr($requestUri, $indexContextPrefixEnd, $indexQuestionMarkAfterPrefix - $indexContextPrefixEnd)
            ) :
            substr($requestUri, $indexContextPrefixEnd, $indexSlashAfterPrefix - $indexContextPrefixEnd);
        LOG::DEBUG("REQUEST TOP URI          : {$requestTopUri}");
        $requestSubUri = $indexQuestionMarkAfterPrefix === FALSE ? substr($requestUri, $indexContextPrefixEnd + strlen($requestTopUri)) : substr($requestUri, $indexContextPrefixEnd + strlen($requestTopUri), $indexQuestionMarkAfterPrefix - ($indexContextPrefixEnd + strlen($requestTopUri)));
        LOG::DEBUG("REQUEST SUB URI          : {$requestSubUri}");

        if (strlen($requestTopUri) === 0) {
            $requestTopUri = "/";
        }

        return array($requestTopUri, $requestSubUri);
    }

    public static function getUrlPrefix()
    {
        $urlPrefix = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] .
            substr($_SERVER["PHP_SELF"], 0, strrpos($_SERVER["PHP_SELF"], "/")) . '/';
        return $urlPrefix;
    }

    public static function printUrlPrefix()
    {
        echo self::getUrlPrefix();
    }

    public static function shortenTextAtNthCharAfterWord(string $text, int $charPos)
    {
        if (strlen($text) <= $charPos) {
            return $text;
        }
        for ($i = $charPos; $i >= 0; $i--) {
            if ($text[$i] == ' ') {
                return substr($text, 0, $i);
            }
        }
        return "";
    }

    /**
     * @param $urlToCheck
     * @return bool
     */
    public static function checkURLInsidePrefix($urlToCheck)
    {
        return TRUE;
    }

    public static function formatDate(string $givenTimestamp, string $format = "d.m.Y")
    {
        return date($format, strtotime($givenTimestamp));
    }

    public static function formatMoney(float $value, string $currencySymbol = "€",
                                       bool $currencySymbolInFront = FALSE,
                                       string $currencyDecimalDivider = ",",
                                       string $currencyThousandDivider = ".")
    {

        $bigPart = floor($value);
        $splittedBigParts = array();
        for ($i = strlen($bigPart); $i > 0; $i -= 3) {
            if (($i - 3) >= 0) {
                array_push($splittedBigParts, substr($bigPart, $i - 3, 3));
            } else {
                array_push($splittedBigParts, substr($bigPart, 0, $i));
            }
        }
        $bigPartAssembled = "";
        foreach ($splittedBigParts as $currentSplittedPart) {
            if (strlen($bigPartAssembled) > 0) {
                $bigPartAssembled = $currentSplittedPart . $currencyThousandDivider . $bigPartAssembled;
            } else {
                $bigPartAssembled = $currentSplittedPart;
            }
        }
        $smallPart = substr(intval(($value - $bigPart) * 100.0), 0, 2);
        if (strlen($smallPart) == 1) {
            $smallPart = "0" . $smallPart;
        }
        $returningValue = $bigPartAssembled . $currencyDecimalDivider . $smallPart;
        if ($currencySymbolInFront) {
            return $currencySymbol . " " . $returningValue;
        } else {
            return $returningValue . " " . $currencySymbol;
        }
    }

    public static function joinURL(array $urlParts)
    {
        if ($urlParts === FALSE || $urlParts === NULL || sizeof($urlParts) <= 0) {
            return NULL;
        }
        if (sizeof($urlParts) == 1) {
            return $urlParts[0];
        }
        if (sizeof($urlParts) > 2) {
            $newArray = array();
            $newArray[0] = self::joinURL(array($urlParts[0], $urlParts[1]));
            for ($i = 2; $i < $urlParts; $i++) {
                $newArray[$i - 2] = $urlParts[$i];
            }
            return self::joinURL($newArray);
        }
        $partOne = $urlParts[0];
        while (strlen($partOne) > 0 && $partOne[strlen($partOne) - 1] == '/') {
            $partOne = substr($partOne, 0, strlen($partOne) - 1);
        }
        $partTwo = $urlParts[1];
        while (strlen($partTwo) > 0 && $partTwo[0] == '/') {
            $partTwo = substr($partTwo, 1);
        }
        return $partOne . "/" . $partTwo;
    }

    public static function assembleGetParam(array $getParams)
    {
        $output = "";
        foreach ($getParams as $key => $value) {
            if (strlen($output) > 0) {
                $output .= "&";
            } else {
                $output .= "?";
            }
            $output .= rawurlencode($key);
            $output .= "=";
            $output .= rawurlencode($value);
        }
        return $output;
    }
}