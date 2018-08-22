<?php

namespace helper;

class FrontEndRequestAcrossMessagesHelper
{
    public const PREFIX = "FrontEndRequestAcrossMessagesHelper_";

    public const SUFFIX_MESSAGES_ARRAY = "Messages_Array";

    public const ARRAY_KEY_TYPE = "Array_Key_Type";
    public const ARRAY_KEY_MESSAGE = "Array_Message";
    public const ARRAY_KEY_MESSAGE_ADDENDUM = "Array_Message_Addendum";
    public const ARRAY_KEY_SOURCE_PREFIX = "Array_Source_Prefix";

    public const TYPE_INFO = "Type_Info";
    public const TYPE_WARN = "Type_Warn";
    public const TYPE_ERROR = "Type_Error";
    public const TYPE_SUCCESS = "Type_Success";

    public const MESSAGE_RESETCONTROLLER_RESET_SUCCESSFUL = "Message_ResetController_Reset_Successful";
    public const MESSAGE_RESETCONTROLLER_RESET_ERROR_GENERIC = "Message_ResetController_Reset_Error_Generic";
    public const MESSAGE_INDEXCONTROLLER_ERROR_USERNAME_MISSING = "Message_IndexController_Error_Username_Missing";
    public const MESSAGE_INDEXCONTROLLER_ERROR_USERNAME_TOO_LONG = "Message_IndexController_Error_Username_Too_Long";
    public const MESSAGE_INDEXCONTROLLER_ERROR_CODE_TOO_LONG = "Message_IndexController_Error_Code_Too_long";
    public const MESSAGE_INDEXCONTROLLER_ERROR_TIMER_INVALID = "Message_IndexController_Timer_Invalid";
    public const MESSAGE_INDEXCONTROLLER_ERROR_TIMER_TOO_MUCH_TIME = "Message_IndexController_Error_Timer_Too_Much_Time";
    public const MESSAGE_INDEXCONTROLLER_ERROR_CREATING_LOBBY_FAILED = "Message_IndexController_Error_Creating_Lobby_Failed";
    public const MESSAGE_INDEXCONTROLLER_ERROR_JOINING_LOBBY_FAILED = "Message_IndexController_Error_Joining_Lobby_Failed";
    public const MESSAGE_GAMECONTROLLER_ERROR_WRONG_STATE = "Message_GameController_Error_Wrong_State";
    public const MESSAGE_GAMECONTROLLER_ERROR_LOBBYID_NULL = "Message_GameController_Error_LobbyId_Null";
    public const MESSAGE_GAMECONTROLLER_ERROR_BOTH_OPTIONS_WERE_WANTED = "Message_GameController_Error_Both_Options_Were_Wanted";
    public const MESSAGE_GAMECONTROLLER_ERROR_INVALID_QUESTION_ID_NOT_EXISTS = "Message_GameController_Error_Invalid_Question_Id_Not_Exists";
    public const MESSAGE_GAMECONTROLLER_ERROR_INVALID_QUESTION_ID_NO_NUMBER = "Message_GameController_Error_Invalid_Question_Id_No_Number";
    public const MESSAGE_GAMECONTROLLER_ERROR_MISSING_QUESTION_ID = "Message_GameController_Error_Missing_Question_Id";
    public const MESSAGE_INDEXCONTROLLER_ERROR_QUESTIONS_INVALID = "Message_IndexController_Error_Questions_Invalid";
    public const MESSAGE_INDEXCONTROLLER_ERROR_MINIMUM_SCORE_INVALID = "Message_IndexController_Error_Minimum_Score_Invalid";
    public const MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBHOST = "Message_ResetController_Reset_Error_Input_Missing_DBHost";
    public const MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_INVALID_DBPORT = "Message_ResetController_Reset_Error_Input_Invalid_DBPort";
    public const MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBPORT = "Message_ResetController_Reset_Error_Input_Missing_DBPort";
    public const MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBNAME = "Message_ResetController_Reset_Error_Input_Missing_DBName";
    public const MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBUSERNAME = "Message_ResetController_Reset_Error_Input_Missing_DBUsername";
    public const MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBREADONLYUSERNAME = "Message_ResetController_Reset_Error_Input_Missing_DBReadOnlyUsername";

    public const MESSAGE_INDEXVIEW_WARNING_DATA_USAGE = "Message_IndexView_Warning_Data_Usage";

    public static function appendMessage(string $type, string $message, string $sourcePrefix, string $messageAddendum = NULL)
    {
        if (!isset($_SESSION[self::PREFIX . self::SUFFIX_MESSAGES_ARRAY])) {
            $_SESSION[self::PREFIX . self::SUFFIX_MESSAGES_ARRAY] = array();
        }
        array_push($_SESSION[self::PREFIX . self::SUFFIX_MESSAGES_ARRAY], array(
            self::ARRAY_KEY_TYPE => $type,
            self::ARRAY_KEY_MESSAGE => $message,
            self::ARRAY_KEY_MESSAGE_ADDENDUM => $messageAddendum,
            self::ARRAY_KEY_SOURCE_PREFIX => $sourcePrefix
        ));
    }

    public static function getMessages()
    {
        if (!isset($_SESSION[self::PREFIX . self::SUFFIX_MESSAGES_ARRAY])) {
            $_SESSION[self::PREFIX . self::SUFFIX_MESSAGES_ARRAY] = array();
        }
        return $_SESSION[self::PREFIX . self::SUFFIX_MESSAGES_ARRAY];
    }

    public static function count()
    {
        return count(self::getMessages());
    }

    public static function isEmpty()
    {
        return self::count() <= 0;
    }

    public static function popMessages()
    {
        $messages = self::getMessages();
        self::deleteMessages();
        return $messages;
    }

    public static function deleteMessages()
    {
        $_SESSION[self::PREFIX . self::SUFFIX_MESSAGES_ARRAY] = array();
    }

    public static function insertHTML(string $generalCssClasses, string $successCssClasses, string $errorCssClasses, string $warnCssClasses, string $infoCssClasses)
    {
        if (!self::isEmpty()) {
            $messages = self::popMessages();
            foreach ($messages as $message) {
                ?>
                <section class="<?php
                echo $generalCssClasses . " ";
                switch ($message[self::ARRAY_KEY_TYPE]) {
                    case self::TYPE_SUCCESS:
                        echo $successCssClasses;
                        break;
                    case self::TYPE_ERROR:
                        echo $errorCssClasses;
                        break;
                    case self::TYPE_WARN:
                        echo $warnCssClasses;
                        break;
                    case self::TYPE_INFO:
                    default:
                        echo $infoCssClasses;
                        break;
                }
                ?>"><?php echo self::getMessage($message[self::ARRAY_KEY_MESSAGE]);
                    if ($message[self::ARRAY_KEY_MESSAGE_ADDENDUM] !== NULL) { ?>
                        <br/><?php echo $message[self::ARRAY_KEY_MESSAGE_ADDENDUM];
                    } ?></section>
                <?php
            }
        }
    }

    public static function getMessage(string $messageId)
    {
        switch ($messageId) {
            case self::MESSAGE_INDEXVIEW_WARNING_DATA_USAGE:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXVIEW_WARNING_DATA_USAGE);
            case self::MESSAGE_RESETCONTROLLER_RESET_SUCCESSFUL:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_RESETCONTROLLER_RESET_SUCCESSFUL);
            case self::MESSAGE_RESETCONTROLLER_RESET_ERROR_GENERIC:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_RESETCONTROLLER_RESET_ERROR_GENERIC);
            case self::MESSAGE_INDEXCONTROLLER_ERROR_USERNAME_MISSING:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_USERNAME_MISSING);
            case self::MESSAGE_INDEXCONTROLLER_ERROR_USERNAME_TOO_LONG:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_USERNAME_TOO_LONG);
            case self::MESSAGE_INDEXCONTROLLER_ERROR_CODE_TOO_LONG:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_CODE_TOO_LONG);
            case self::MESSAGE_INDEXCONTROLLER_ERROR_TIMER_INVALID:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_TIMER_INVALID);
            case self::MESSAGE_INDEXCONTROLLER_ERROR_TIMER_TOO_MUCH_TIME:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_TIMER_TOO_MUCH_TIME);
            case self::MESSAGE_INDEXCONTROLLER_ERROR_CREATING_LOBBY_FAILED:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_CREATING_LOBBY_FAILED);
            case self::MESSAGE_INDEXCONTROLLER_ERROR_JOINING_LOBBY_FAILED:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_JOINING_LOBBY_FAILED);
            case self::MESSAGE_GAMECONTROLLER_ERROR_WRONG_STATE:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_WRONG_STATE);
            case self::MESSAGE_GAMECONTROLLER_ERROR_LOBBYID_NULL:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_LOBBYID_NULL);
            case self::MESSAGE_GAMECONTROLLER_ERROR_BOTH_OPTIONS_WERE_WANTED:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_BOTH_OPTIONS_WERE_WANTED);
            case self::MESSAGE_GAMECONTROLLER_ERROR_INVALID_QUESTION_ID_NOT_EXISTS:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_INVALID_QUESTION_ID_NOT_EXISTS);
            case self::MESSAGE_GAMECONTROLLER_ERROR_INVALID_QUESTION_ID_NO_NUMBER:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_INVALID_QUESTION_ID_NO_NUMBER);
            case self::MESSAGE_GAMECONTROLLER_ERROR_MISSING_QUESTION_ID:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_MISSING_QUESTION_ID);
            case self::MESSAGE_INDEXCONTROLLER_ERROR_QUESTIONS_INVALID:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_QUESTIONS_INVALID);
            case self::MESSAGE_INDEXCONTROLLER_ERROR_MINIMUM_SCORE_INVALID:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_MINIMUM_SCORE_INVALID);
            case self::MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBHOST:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBHOST);
            case self::MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_INVALID_DBPORT:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_INVALID_DBPORT);
            case self::MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBPORT:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBPORT);
            case self::MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBNAME:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBNAME);
            case self::MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBUSERNAME:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBUSERNAME);
            case self::MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBREADONLYUSERNAME:
                return LocalizationHelper::get(
                    further\LocalizationStore::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_RESETCONTROLLER_RESET_ERROR_INPUT_MISSING_DBREADONLYUSERNAME);


            default:
                return "#error#";
        }
    }
}