<?php

namespace logics;


class FrontEndRequestAcrossMessagesLogic
{
    public const PREFIX = "FrontEndRequestAcrossMessagesLogic_";

    public const SUFFIX_MESSAGES_ARRAY = "Messages_Array";

    public const ARRAY_KEY_TYPE = "Array_Key_Type";
    public const ARRAY_KEY_MESSAGE = "Array_Message";
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

    public const MESSAGEDEFAULT_INTERNAL_SERVER_ERROR = "An internal server error happened.";

    public const MESSAGES = array(
        self::MESSAGE_RESETCONTROLLER_RESET_SUCCESSFUL => "The reset has been successful!",
        self::MESSAGE_RESETCONTROLLER_RESET_ERROR_GENERIC => "An error occurred during the reset!",
        self::MESSAGE_INDEXCONTROLLER_ERROR_USERNAME_MISSING => "Your name has been missing!",
        self::MESSAGE_INDEXCONTROLLER_ERROR_USERNAME_TOO_LONG => "Your name has been too long! Please choose one smaller than 60 characters.",
        self::MESSAGE_INDEXCONTROLLER_ERROR_CODE_TOO_LONG => "Your invite code has been too long! It should be smaller than 17 characters.",
        self::MESSAGE_INDEXCONTROLLER_ERROR_TIMER_INVALID => "Your value for the timer has been invalid! Please use a number.",
        self::MESSAGE_INDEXCONTROLLER_ERROR_TIMER_TOO_MUCH_TIME => "Your value for the timer exceeded 10 minutes! That's too long!",
        self::MESSAGE_INDEXCONTROLLER_ERROR_CREATING_LOBBY_FAILED => "Creating the lobby has failed!",
        self::MESSAGE_INDEXCONTROLLER_ERROR_JOINING_LOBBY_FAILED => "Joining the lobby has failed!",
        self::MESSAGE_GAMECONTROLLER_ERROR_WRONG_STATE => self::MESSAGEDEFAULT_INTERNAL_SERVER_ERROR,
        self::MESSAGE_GAMECONTROLLER_ERROR_LOBBYID_NULL => "The lobby does not exist!"
    );

    public static function appendMessage(string $type, string $message, string $sourcePrefix)
    {
        if (!isset($_SESSION[self::PREFIX . self::SUFFIX_MESSAGES_ARRAY])) {
            $_SESSION[self::PREFIX . self::SUFFIX_MESSAGES_ARRAY] = array();
        }
        array_push($_SESSION[self::PREFIX . self::SUFFIX_MESSAGES_ARRAY], array(
            self::ARRAY_KEY_TYPE => $type,
            self::ARRAY_KEY_MESSAGE => $message,
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

    public static function insertHTML()
    {
        if (!self::isEmpty()) {
            $messages = self::popMessages();
            foreach ($messages as $message) {
                ?>
                <section class="<?php
                switch ($message[self::ARRAY_KEY_TYPE]) {
                    case self::TYPE_SUCCESS:
                        echo "msg-box-success";
                        break;
                    case self::TYPE_ERROR:
                        echo "msg-box-error";
                        break;
                    case self::TYPE_WARN:
                        echo "msg-box-warn";
                        break;
                    case self::TYPE_INFO:
                    default:
                        echo "msg-box-info";
                        break;
                }
                ?>"><?php echo self::MESSAGES[$message[self::ARRAY_KEY_MESSAGE]]; ?></section>
                <?php
            }
        }
    }
}