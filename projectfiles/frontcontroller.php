<?php
/*
 * Following two lines are just for debugging purposes and should be removed after development has finished.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
 * With this a PHP session is started or continued.
 * It is here at this position, because this feature should be available as early as possible.
 */
session_start();

/*
 * Configuring the LogHelper to use the right log-path and the right log-level.
 */
require_once("php_scripts/helper/LogHelper.php");

use \helper\LogHelper as LOG;

LOG::$LOGFILE_PATH = realpath(join("/", array(__DIR__, "logs/"))) . "/" . "onionornottheonion.log";
LOG::$LOG_LEVEL = LOG::LEVEL_TRACE;

/*
 * Include all necessary PHP scripts. Further scripts won't be needed. Every other script will be dynamically included.
 */
require_once("php_scripts/actions/ViewAction.php");
require_once("php_scripts/actions/RedirectAction.php");
require_once("php_scripts/actions/StatusCodeAction.php");

require_once("php_scripts/controllers/AbstractController.php");

require_once("php_scripts/views/AbstractView.php");
require_once("php_scripts/views/ErrorDocument500View.php");
require_once("php_scripts/views/ErrorDocument404View.php");
require_once("php_scripts/views/ErrorDocument303View.php");

require_once("php_scripts/logics/ResetLogic.php");
require_once("php_scripts/logics/UpdateLogic.php");
require_once("php_scripts/logics/LocalizationLogic.php");
require_once("php_scripts/logics/further/LocalizationStore.php");

require_once("php_scripts/helper/VariousHelper.php");


/*
 * Define a $_RESPONSE "server" variable.
 * It should be used to transfer information from the controllers to the views.
 */

global $_RESPONSE;
$_RESPONSE = array();

/*
 * The controller array contains class names of the provided
 * controllers. The keys are the prefixes to match against the top level of the REQUEST_URI.
 *
 * This is used to route the incoming requests to the right controllers.
 */
$controller = array();
$controller["/"] = "IndexController";
$controller["/index"] = "IndexController";
$controller["/game"] = "GameController";
$controller["/reset"] = "ResetController";
$controller["/exit"] = "ExitController";

/*
 * To route the incoming request, we have to get the top level request uri.
 * This is done with a function defined in VariousHelper class.
 */

$requestTopUri = \helper\VariousHelper::getRequestTopUri();

/*
 * Setup language:
 */
if (isset($_GET["lang"])) {
    $language = strtolower($_GET["lang"]);

    switch ($language) {
        case "de":
            \logics\LocalizationLogic::setCurrentLocale(\logics\further\LocalizationStore::LOCALE_GERMAN);
            break;
        case "en":
        default:
            \logics\LocalizationLogic::setCurrentLocale(\logics\further\LocalizationStore::LOCALE_ENGLISH);
    }

    LOG::TRACE("Language has been changed");
    header('Location: ' . \helper\VariousHelper::getUrlPrefix(), true, 303);
    $_RESPONSE[\views\ErrorDocument303View::PREFIX . \views\ErrorDocument303View::REFER_TO] = \helper\VariousHelper::getUrlPrefix();
    (new \views\ErrorDocument303View())->print();
    exit;
}

/*
 * At first check if this website is set up.
 */

if (!\logics\ResetLogic::isWebsiteBeenSetup() && $requestTopUri !== "/reset") {
    LOG::TRACE("Setup has been triggered");
    header('Location: ' . \helper\VariousHelper::getUrlPrefix() . "reset", true, 303);
    $_RESPONSE[\views\ErrorDocument303View::PREFIX . \views\ErrorDocument303View::REFER_TO] = \helper\VariousHelper::getUrlPrefix() . "reset";
    (new \views\ErrorDocument303View())->print();
    exit;
}

/**
 * Force Updated of System!
 */

\logics\UpdateLogic::updateAll();

/*
 * Logging the requested uri and the transformed top level request uri:
 */

LOG::TRACE("Requesting " . $_SERVER["REQUEST_URI"] . " resp. " . $_GET["fcr"] . " => " . $requestTopUri);

/*
 * Here the requested top level uri has to be found within the routing controller array from above.
 * Otherwise a 404 is send in the else branch!
 */
if (array_key_exists($requestTopUri, $controller) === TRUE) {

    /*
     * In the following part a standard check is being made. If a coder made a mistake the user get's a 500 error view:
     */
    if ($controller[$requestTopUri] === NULL || strlen($controller[$requestTopUri]) <= 0) {
        LOG::FATAL("Controller `{$requestTopUri}` has been requested, but the value is not valid! Sending status 500 to client!");
        http_response_code(500);
        (new \views\ErrorDocument500View())->print();
    } else {

        /*
         * Now the controller which has been associated with the requested top level uri has to be dynamically loaded.
         * This happens with inserting the defined controller name into the relative path.
         * There is no problem in doing that, because no evil client input is used for this.
         */
        $wantedControllerPath = "php_scripts/controllers/" . $controller[$requestTopUri] . ".php";
        $controllerFolderPath = join("/", array(__DIR__, "php_scripts/controllers")) . "/";

        /*
         * With the following function inside the condition the given relative path is transformed into an absolute path.
         * Furthermore a check is being performed on the path to test if it's inside a given directory.
         * In this way the dynamically built path cannot be misused to execute another script within a not supported directory.
         */
        if (($wantedControllerPath = \helper\VariousHelper::realPathPrefixAndExistenceCheck($wantedControllerPath, $controllerFolderPath)) !== FALSE) {

            /*
             * The controller is getting included and instantiated.
             * After that the if condition checks that this loaded class is in fact
             * a valid controller which inherits from AbstractController.
             */
            require_once($wantedControllerPath);
            $controllerString = "\\controllers\\" . $controller[$requestTopUri];
            $instantiatedController = new $controllerString();
            if ($instantiatedController instanceof \controllers\AbstractController) {

                /*
                 * The following line does the MAGIC.
                 * It calls the main function of the loaded controller and awaits its answer.
                 */
                $action = $instantiatedController->action(\helper\VariousHelper::getRequestSubUri());

                /*
                 * Now the answer of the controller is being checked and interpreted:
                 */
                if ($action !== NULL) {

                    /*
                     * It's possible that the returned action is a "RedirectAction" which directs the
                     * frontcontroller to redirect to a given URL.
                     * If that's the case the header is set to redirect with "303 See Other" to the given URL.
                     * Furthermore the 303 ErrorDocument is printed. In case their browser won't redirect.
                     */
                    if ($action instanceof \actions\RedirectAction) {
                        LOG::TRACE("`{$controllerString}` returns \\actions\\RedirectAction => Redirecting to `{$action->getRedirectTo()}`");
                        header('Location: ' . $action->getRedirectTo(), true, 303);
                        $_RESPONSE[\views\ErrorDocument303View::PREFIX . \views\ErrorDocument303View::REFER_TO] = $action->getRedirectTo();
                        (new \views\ErrorDocument303View())->print();
                        exit;
                    } /*
                     * It's also possible that the returned action is a "ViewAction" which dynamically loads
                     * a View class and prints that to send it to the requesting client.
                     */
                    else if ($action instanceof \actions\ViewAction) {
                        LOG::TRACE("`{$controllerString}` returns \\actions\\ViewAction => Trying to show `{$action->getViewpage()}`");

                        /*
                         * In the following lines again the script is loaded the same as the controller previously.
                         * And again the function to transform the relative path to an absolute one is being used.
                         * After that it is checked that the instantiated object is in fact a view by using AbstractView.
                         */
                        $calculatedWantedViewPath = "php_scripts/views/" . $action->getViewpage() . ".php";
                        $wantedViewPath = $calculatedWantedViewPath;
                        $viewFolderPath = join("/", array(__DIR__, "php_scripts/views")) . "/";

                        if (($wantedViewPath = \helper\VariousHelper::realPathPrefixAndExistenceCheck($wantedViewPath, $viewFolderPath)) !== FALSE) {
                            require_once($wantedViewPath);
                            $viewString = "\\views\\" . $action->getViewpage() . "";
                            $instantiatedView = new $viewString();

                            if ($instantiatedView instanceof \views\AbstractView) {

                                /*
                                 * Here the status code "200 OK" is set and the function to print the page is called.
                                 * This function assembles all the parts of the final page. This includes:
                                 * - Head
                                 * - Dynamic CSS files
                                 * - Header
                                 * - Main
                                 * - Footer
                                 *
                                 * After that the script is exited to close the work on this request.
                                 */
                                http_response_code(200);
                                $instantiatedView->print();
                                LOG::TRACE("`{$wantedViewPath}` successfully printed!");
                                exit;
                            } else {
                                LOG::ERROR("`{$viewString}` does NOT inherit from AbstractView!");
                            }
                        } else {
                            LOG::ERROR("Path `{$calculatedWantedViewPath}` for View returned by `{$controllerString}` is not within a valid directory!");
                        }
                    } /*
                     * Furthermore it's possible that the returned action is a "StatusCodeAction" which dynamically loads
                     * the linked ErrorDocument View and prints that to send it to the requesting client.
                     */
                    else if ($action instanceof \actions\StatusCodeAction) {
                        LOG::TRACE("`{$controllerString}` returns \\actions\\StatusCodeAction => Trying to show Error `{$action->getStatusCode()}` with ErrorDocument `{$action->getStatusCodeView()}`");

                        /*
                         * In the following lines again the script is loaded the same as the controller previously.
                         * And again the function to transform the relative path to an absolute one is being used.
                         * After that it is checked that the instantiated object is in fact a view by using AbstractView.
                         */
                        $calculatedWantedViewPath = "php_scripts/views/" . $action->getStatusCodeView() . ".php";
                        $wantedViewPath = $calculatedWantedViewPath;
                        $viewFolderPath = join("/", array(__DIR__, "php_scripts/views")) . "/";

                        if (($wantedViewPath = \helper\VariousHelper::realPathPrefixAndExistenceCheck($wantedViewPath, $viewFolderPath)) !== FALSE) {
                            require_once($wantedViewPath);
                            $viewString = "\\views\\" . $action->getStatusCodeView() . "";
                            $instantiatedView = new $viewString();

                            if ($instantiatedView instanceof \views\AbstractView) {

                                /*
                                 * Here the status code "200 OK" is set and the function to print the page is called.
                                 * This function assembles all the parts of the final page. This includes:
                                 * - Head
                                 * - Dynamic CSS files
                                 * - Header
                                 * - Main
                                 * - Footer
                                 *
                                 * After that the script is exited to close the work on this request.
                                 */
                                http_response_code(200);
                                $instantiatedView->print();
                                LOG::TRACE("`{$wantedViewPath}` successfully printed!");
                                exit;
                            } else {
                                LOG::ERROR("`{$viewString}` does NOT inherit from AbstractView!");
                            }
                        } else {
                            LOG::ERROR("Path `{$calculatedWantedViewPath}` for View returned by `{$controllerString}` is not within a valid directory!");
                        }
                    }  /*
                     * Furthermore it's possible that the returned action is a "StatusCodeAction" which dynamically loads
                     * the linked ErrorDocument View and prints that to send it to the requesting client.
                     */
                    else if ($action instanceof \actions\NoneAction) {
                        LOG::TRACE("`{$controllerString}` returns \\actions\\NoneAction => Showing nothing");
                        LOG::TRACE("None has been successfully printed!");
                        exit;
                    } else {
                        LOG::ERROR("Action returned by `{$controllerString}` is unknown!");
                    }
                } else {
                    LOG::ERROR("Action returned by `{$controllerString}` is NULL!");
                }
            } else {
                LOG::ERROR("`{$controllerString}` does NOT inherit from AbstractController!");
            }
        } else {
            LOG::ERROR("Controllerpath `{$wantedControllerPath}` is not within a valid directory!");
        }
        /*
         * Within the last few lines and the following three a lot of error logs are being logged.
         * Those are to identify errors within the coding structure.
         * Additionally if an error like this is being produced the HTTP Status 500 ErrorDocument page is being sent to the client.
         */
        LOG::FATAL("Controller `{$requestTopUri}` has been requested, but somehow it couldn't be used! Sending status 500 to client!");
        http_response_code(500);
        (new \views\ErrorDocument500View())->print();
    }
} else {
    /*
     * If no valid route for the top level request uri could be found the HTTP Status 404 ErrorDocuemtn page is being sent to the client.
     */
    LOG::FATAL("Controller `{$requestTopUri}` has been requested, but no value is set for this one! Sending status 404 to client!");
    http_response_code(404);
    (new \views\ErrorDocument404View())->print();
}
