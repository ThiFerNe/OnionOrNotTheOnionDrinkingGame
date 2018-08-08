<?php

namespace logics\further;


class LocalizationStore
{
    public const LOCALE_ENGLISH = "Locale_English";
    public const LOCALE_GERMAN = "Locale_German";

    public static function loadLocale(string $locale)
    {
        $output = array();
        switch ($locale) {
            case self::LOCALE_GERMAN:
                $output[self::ID_GENERAL_GAME_NAME] = "The Onion Oder Nicht The Onion Trinkspiel";

                $output[self::ID_VIEWFRAMEHELPER_HTML_HEAD_TITLE_TEXT] = $output[self::ID_GENERAL_GAME_NAME];
                $output[self::ID_VIEWFRAMEHELPER_HTML_BODY_TITLE_TEXT] = $output[self::ID_GENERAL_GAME_NAME];

                $output[self::ID_INDEXVIEW_BODY_MAIN_GAME_DESCRIPTION] = " ist ein Online Spiel, in welchem dein Handy " .
                    "dein Controller ist. Erstell einfach eine Lobby und spiele. Zusätzlich kannst du einen großen " .
                    "Bildschirm nutzen, auf welchem jeder, der auf deiner ganze Party nicht mitspielen will, " .
                    "das Geschehen mitverfolgen kann.";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_USERNAME_NEW_GAME_PLACEHOLDER] = "Name";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_LABEL] = "Ich will es einfach nur anschauen!";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_VALUE] = $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_LABEL];
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_INVITE_CODE_PLACEHOLDER] = "Einladungscode";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_MAX_QUESTIONS_PLACEHOLDER] = "Fragen - Leer lassen, wenn alle gewollt sind";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_MINIMUM_SCORE_PLACEHOLDER] = "Minimaler Score der Fragen - Leer lassen, wenn alle gewollt sind";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_TIMER_WANTED_PLACEHOLDER] = "Timer - Leer lassen, wenn alle gewollt sind";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_PARAGRAPH_FURTHER_GAME_START_EXPLANATION] =
                    "Mit keinem Einladungscode wird ein neues Spiel gestartet. Andernfalls wird dem Spiel mit dem Einladungscode beigetreten.";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE] = "STARTEN";

                $output[self::ID_RESETVIEW_BODY_MAIN_HEADLINE] = "Setze diese Seite zurück";
                $output[self::ID_RESETVIEW_BODY_MAIN_PARAGRAPH_EXPLANATION] = "Du kannst diese Seite zurücksetzen. Willst du das?";
                $output[self::ID_RESETVIEW_BODY_MAIN_FORM_SUBMIT_VALUE] = "TUE ES! SETZE ALLES ZURÜCK!";

                break;
            case self::LOCALE_ENGLISH:
            default:
                $output[self::ID_GENERAL_GAME_NAME] = "The Onion Or Not The Onion Drinking Game";

                $output[self::ID_VIEWFRAMEHELPER_HTML_HEAD_TITLE_TEXT] = $output[self::ID_GENERAL_GAME_NAME];
                $output[self::ID_VIEWFRAMEHELPER_HTML_BODY_TITLE_TEXT] = $output[self::ID_GENERAL_GAME_NAME];

                $output[self::ID_INDEXVIEW_BODY_MAIN_GAME_DESCRIPTION] = " is a web game in which your mobile devices " .
                    "are your controllers. Just create a lobby and play. Furthermore you can host a screen which " .
                    "provides a view for rest of your party which doesn't want to play.";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_USERNAME_NEW_GAME_PLACEHOLDER] = "Name";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_LABEL] = "I just want to watch it!";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_VALUE] = $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_LABEL];
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_INVITE_CODE_PLACEHOLDER] = "Invite Code";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_MAX_QUESTIONS_PLACEHOLDER] = "Questions - Leave Blank if all are wished";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_MINIMUM_SCORE_PLACEHOLDER] = "Minimum Score Of Questions - Leave Blank if all are wished";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_TIMER_WANTED_PLACEHOLDER] = "Timer - Leave Blank if none is wished";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_PARAGRAPH_FURTHER_GAME_START_EXPLANATION] =
                    "With no invite code a new game will be started. Otherwise the game with the code will be joined.";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE] = "START";

            $output[self::ID_RESETVIEW_BODY_MAIN_HEADLINE] = "Reset This Page To Default";
            $output[self::ID_RESETVIEW_BODY_MAIN_PARAGRAPH_EXPLANATION] = "You can reset this page to default. Do you want to?";
            $output[self::ID_RESETVIEW_BODY_MAIN_FORM_SUBMIT_VALUE] = "DO IT! RESET EVERYTHING!";
        }
        return $output;
    }

    public const ID_GENERAL_GAME_NAME = "General_Game_Name";

    public const ID_VIEWFRAMEHELPER_HTML_HEAD_TITLE_TEXT = "ViewFrameHelper_Html_Head_Title_Text";
    public const ID_VIEWFRAMEHELPER_HTML_BODY_TITLE_TEXT = "ViewFrameHelper_Html_Body_Title_Text";

    public const ID_INDEXVIEW_BODY_MAIN_GAME_DESCRIPTION = "IndexView_Body_Main_Game_Description";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_USERNAME_NEW_GAME_PLACEHOLDER = "IndexView_Body_Main_Form_Username_New_Game_Placeholder";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_LABEL = "IndexView_Body_Main_Form_Just_Watch_New_Game_Label";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_VALUE = "IndexView_Body_Main_Form_Just_Watch_New_Game_Value";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_INVITE_CODE_PLACEHOLDER = "IndexView_Body_Main_Form_Invite_Code_Placeholder";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_MAX_QUESTIONS_PLACEHOLDER = "IndexView_Body_Main_Form_Max_Questions_Placeholder";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_MINIMUM_SCORE_PLACEHOLDER = "IndexView_Body_Main_Form_Minimum_Score_Placeholder";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_TIMER_WANTED_PLACEHOLDER = "IndexView_Body_Main_Form_Timer_Wanted_Placeholder";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_PARAGRAPH_FURTHER_GAME_START_EXPLANATION = "IndexView_Body_Main_Form_Paragraph_Further_Game_Start_Explanation";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE = "IndexView_Body_Main_Form_Submit_Value";

    public const ID_RESETVIEW_BODY_MAIN_HEADLINE = "ResetView_Body_Main_Headline";
    public const ID_RESETVIEW_BODY_MAIN_PARAGRAPH_EXPLANATION = "ResetView_Body_Main_Paragraph_Explanation";
    public const ID_RESETVIEW_BODY_MAIN_FORM_SUBMIT_VALUE = "ResetView_Body_Main_Form_Submit_Value";
}