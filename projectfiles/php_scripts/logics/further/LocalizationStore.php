<?php

namespace logics\further;

class LocalizationStore
{
    public const LOCALE_ENGLISH = "Locale_English";
    public const LOCALE_ENGLISH_SHORT = "en";
    public const LOCALE_GERMAN = "Locale_German";
    public const LOCALE_GERMAN_SHORT = "de";

    public static function getShortForLocale(string $longLocale)
    {
        switch ($longLocale) {
            case self::LOCALE_GERMAN:
                return self::LOCALE_GERMAN_SHORT;
            case self::LOCALE_ENGLISH:
            default:
                return self::LOCALE_ENGLISH_SHORT;
        }
    }

    public static function loadLocale(string $locale)
    {
        $output = array();
        switch ($locale) {
            case self::LOCALE_GERMAN:
                $output[self::ID_GENERAL_GAME_NAME] = "The Onion Oder Nicht The Onion Trinkspiel";

                $output[self::ID_VIEWFRAMEHELPER_HTML_HEAD_TITLE_TEXT] = $output[self::ID_GENERAL_GAME_NAME];
                $output[self::ID_VIEWFRAMEHELPER_HTML_BODY_TITLE_TEXT] = "The Onion Oder Nicht The Onion";
                $output[self::ID_VIEWFRAMEHELPER_HTML_BODY_SUBTITLE_TEXT] = "Trinkspiel";

                $output[self::ID_INDEXVIEW_BODY_MAIN_GAME_DESCRIPTION] = " ist ein Online Spiel, in welchem dein Handy " .
                    "dein Controller ist. Erstell einfach eine Lobby und spiele. Zusätzlich kannst du einen großen " .
                    "Bildschirm nutzen, auf welchem jeder, der auf deiner ganze Party nicht mitspielen will, " .
                    "das Geschehen mitverfolgen kann.";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_USERNAME_NEW_GAME_PLACEHOLDER] = "Spielername";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_LABEL] = "Ich will es einfach nur anschauen!";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_VALUE] = $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_LABEL];
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_INVITE_CODE_PLACEHOLDER] = "Einladungscode";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_MAX_QUESTIONS_PLACEHOLDER] = "Anzahl an Fragen";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_MAX_QUESTIONS_DESCRIPTION_PARAGRAPH] = "Leer Lassen, wenn alle Fragen gewollt sind";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_MINIMUM_SCORE_PLACEHOLDER] = "Minimaler Score der Fragen";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_MINIMUM_SCORE_DESCRIPTION_PARAGRAPH] = "Leer Lassen, wenn es egal ist, wie gut eine Frage sein muss";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_TIMER_WANTED_PLACEHOLDER] = "Timer";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_TIMER_WANTED_DESCRIPTION_PARAGRAPH] = "Leer Lassen, wenn kein Timer beim Beantworten erwünscht ist.";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_PARAGRAPH_FURTHER_GAME_START_EXPLANATION] =
                    "Mit keinem Einladungscode wird ein neues Spiel gestartet. Andernfalls wird dem Spiel mit dem Einladungscode beigetreten.";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE] = "STARTEN";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE_JOIN] = "BEITRETEN";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE_CREATE] = "ERSTELLEN";

                $output[self::ID_RESETVIEW_BODY_MAIN_HEADLINE] = "Diese Seite zurücksetzen";
                $output[self::ID_RESETVIEW_BODY_MAIN_PARAGRAPH_EXPLANATION] = "Du kannst diese Seite zurücksetzen. Willst du das?";
                $output[self::ID_RESETVIEW_BODY_MAIN_FORM_SUBMIT_VALUE] = "TUE ES! SETZE ALLES ZURÜCK!";

                $output[self::ID_GAMEVIEW_BODY_MAIN_TYPE_OF_PLAYER_WATCHER] = "Zuschauer";
                $output[self::ID_GAMEVIEW_BODY_MAIN_TYPE_OF_PLAYER_PLAYER] = "Spieler";
                $output[self::ID_GAMEVIEW_BODY_MAIN_EXIT_THE_GAME] = "Das Spiel verlassen";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_START_HEADLINE_WELCOME] = "Willkommen!";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_START_FORM_START_GAME_SUBMIT_VALUE] = "STARTEN";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_QUESTION_FORM_SET_ONION_SUBMIT_VALUE] = "THE ONION";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_QUESTION_FORM_SET_NOT_ONION_SUBMIT_VALUE] = "NOT THE ONION";
                $output[self::ID_GAMEVIEW_BODY_MAIN_PLAYERS_HEADLINE] = "Spieler:";
                $output[self::ID_GAMEVIEW_BODY_MAIN_PLAYERS_TERM_POINTS] = "Punkte";
                $output[self::ID_GAMEVIEW_BODY_MAIN_PLAYERS_TERM_WATCHING] = "Zuschauer";
                $output[self::ID_GAMEVIEW_BODY_MAIN_PLAYERS_NO_ONE_HERE] = "Keiner da!";
                $output[self::ID_GAMEVIEW_BODY_ASIDE_JOIN_GAME_PART_1] = "Tritt dem Spiel auf ";
                $output[self::ID_GAMEVIEW_BODY_ASIDE_JOIN_GAME_PART_2] = "mit dem Code";
                $output[self::ID_GAMEVIEW_BODY_ASIDE_JOIN_GAME_PART_3] = "bei!";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_QUESTION_SECTION_TIME_REMAINING_PART_1] = "Es verbleiben";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_QUESTION_SECTION_TIME_REMAINING_PART_2] = "Sekunden!";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_PART1] = "Es ist ";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_PART2] = "";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_TERM_THE_ONION] = "THE ONION";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_TERM_NOT_THE_ONION] = "NOT THE ONION";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_ANSWER_CORRECT] = "Deine Antwort war richtig!";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_ANSWER_WRONG] = "Deine Antwort war falsch!";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_LINK_TO_POST] = "Link zum Artikel";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_FORM_UPVOTE_SUBMIT_VALUE] = "HOCHWÄHL";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_FORM_DOWNVOTE_SUBMIT_VALUE] = "RUNTERWÄHL";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SECTION_TIME_REMAINING_PART_1] = "Es verbleiben";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SECTION_TIME_REMAINING_PART_2] = "Sekunden!";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_END_FORM_NEXT_ROUND_SUBMIT_VALUE] = "NOCHMAL SPIELEN!";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_END_HEADLINE] = "Das Spiel ist vorbei";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_END_RANKING_HEADLINE] = "Rangliste";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_END_RANKING_TERM_POINTS] = $output[self::ID_GAMEVIEW_BODY_MAIN_PLAYERS_TERM_POINTS];
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_END_RANKING_NO_ONE_HERE] = "Niemand ist in der Rangliste!";

                $output[self::ID_ERRORDOCUMENT303VIEW_BODY_MAIN_HEADLINE] = "303 - See Other";
                $output[self::ID_ERRORDOCUMENT303VIEW_BODY_MAIN_PARAGRAPH] = "Wir haben versucht Sie weiterzuleiten, aber das war uns nicht möglich.<br/>" .
                    "Bitte klicken Sie eigenständig auf folgenden Link:";

                $output[self::ID_ERRORDOCUMENT404VIEW_BODY_MAIN_HEADLINE] = "404 - Nicht gefunden";
                $output[self::ID_ERRORDOCUMENT404VIEW_BODY_MAIN_PARAGRAPH] = "Sie oder Ihr Browser hat eine Datei gefordert, die uns nicht bekannt ist.<br/>" .
                    "Kehren Sie bitte zur Hauptseite zurück.";

                $output[self::ID_ERRORDOCUMENT500VIEW_BODY_MAIN_HEADLINE] = "500 - Interner Server Fehler";
                $output[self::ID_ERRORDOCUMENT500VIEW_BODY_MAIN_PARAGRAPH] = "Es entstand ein interner Fehler im Server, der sehr wahrscheinlich aufgrund einer fehlerhaften Programmierung auftrat.";

                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_RESETCONTROLLER_RESET_SUCCESSFUL] = "Das Zurücksetzen war erfolgreich!";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_RESETCONTROLLER_RESET_ERROR_GENERIC] = "Ein Fehler trat während dem Zurücksetzen auf!";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_USERNAME_MISSING] = "Dein Name hat gefehlt!";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_USERNAME_TOO_LONG] = "Dein Name war zu lang! Bitte wähle einen mit weniger als 60 Buchstaben.";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_CODE_TOO_LONG] = "Dein Einladungscode war zu lang! Er sollte kleiner als 17 Buchstaben sein.";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_TIMER_INVALID] = "Der Wert für den Timer war ungültig! Bitte nutze eine Nummer.";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_TIMER_TOO_MUCH_TIME] = "Dein Wert für den Timer überschritt 10 Minuten! Das ist zu lange!";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_CREATING_LOBBY_FAILED] = "Das Erstellen der Lobby schlug fehl!";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_JOINING_LOBBY_FAILED] = "Das Beitreten der Lobby schlug fehl!";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_WRONG_STATE] = "Ein interner Serverfehler trat auf.";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_LOBBYID_NULL] = "Die Lobby existiert nicht!";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_BOTH_OPTIONS_WERE_WANTED] = "Ein interner Serverfehler trat auf.";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_INVALID_QUESTION_ID_NOT_EXISTS] = "Ein interner Serverfehler trat auf.";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_INVALID_QUESTION_ID_NO_NUMBER] = "Ein interner Serverfehler trat auf.";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_MISSING_QUESTION_ID] = "Ein interner Serverfehler trat auf.";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_QUESTIONS_INVALID] = "Die Anzahl der Fragen war ungültig! Bitte nutze eine Nummer.";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_MINIMUM_SCORE_INVALID] = "Die minimale Bewertung war ungültig! Bitte nutze eine Nummer.";

                break;
            case self::LOCALE_ENGLISH:
            default:
                $output[self::ID_GENERAL_GAME_NAME] = "The Onion Or Not The Onion Drinking Game";

                $output[self::ID_VIEWFRAMEHELPER_HTML_HEAD_TITLE_TEXT] = $output[self::ID_GENERAL_GAME_NAME];
                $output[self::ID_VIEWFRAMEHELPER_HTML_BODY_TITLE_TEXT] = "The Onion Or Not The Onion";
                $output[self::ID_VIEWFRAMEHELPER_HTML_BODY_SUBTITLE_TEXT] = "Drinking Game";

                $output[self::ID_INDEXVIEW_BODY_MAIN_GAME_DESCRIPTION] = " is a web game in which your mobile devices " .
                    "are your controllers. Just create a lobby and play. Furthermore you can host a screen which " .
                    "provides a view for rest of your party which doesn't want to play.";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_USERNAME_NEW_GAME_PLACEHOLDER] = "Playername";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_LABEL] = "I just want to watch it!";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_VALUE] = $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_LABEL];
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_INVITE_CODE_PLACEHOLDER] = "Invite Code";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_MAX_QUESTIONS_PLACEHOLDER] = "Count of Questions";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_MAX_QUESTIONS_DESCRIPTION_PARAGRAPH] = "Leave Blank if you want to get all available questions.";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_MINIMUM_SCORE_PLACEHOLDER] = "Minimum Score Of Questions";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_MINIMUM_SCORE_DESCRIPTION_PARAGRAPH] = "Leave Blank if you don't care how much score a question has";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_TIMER_WANTED_PLACEHOLDER] = "Timer";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_TIMER_WANTED_DESCRIPTION_PARAGRAPH] = "Leave Blank if no timer while answering is wished";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_PARAGRAPH_FURTHER_GAME_START_EXPLANATION] =
                    "With no invite code a new game will be started. Otherwise the game with the code will be joined.";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE] = "START";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE_JOIN] = "JOIN";
                $output[self::ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE_CREATE] = "CREATE";

                $output[self::ID_RESETVIEW_BODY_MAIN_HEADLINE] = "Reset This Page";
                $output[self::ID_RESETVIEW_BODY_MAIN_PARAGRAPH_EXPLANATION] = "You can reset this page to default. Do you want to?";
                $output[self::ID_RESETVIEW_BODY_MAIN_FORM_SUBMIT_VALUE] = "DO IT! RESET EVERYTHING!";

                $output[self::ID_GAMEVIEW_BODY_MAIN_TYPE_OF_PLAYER_WATCHER] = "Watcher";
                $output[self::ID_GAMEVIEW_BODY_MAIN_TYPE_OF_PLAYER_PLAYER] = "Player";
                $output[self::ID_GAMEVIEW_BODY_MAIN_EXIT_THE_GAME] = "Exit The Game";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_START_HEADLINE_WELCOME] = "Welcome!";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_START_FORM_START_GAME_SUBMIT_VALUE] = "START";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_QUESTION_FORM_SET_ONION_SUBMIT_VALUE] = "THE ONION";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_QUESTION_FORM_SET_NOT_ONION_SUBMIT_VALUE] = "NOT THE ONION";
                $output[self::ID_GAMEVIEW_BODY_MAIN_PLAYERS_HEADLINE] = "Players:";
                $output[self::ID_GAMEVIEW_BODY_MAIN_PLAYERS_TERM_POINTS] = "Points";
                $output[self::ID_GAMEVIEW_BODY_MAIN_PLAYERS_TERM_WATCHING] = "Watching";
                $output[self::ID_GAMEVIEW_BODY_MAIN_PLAYERS_NO_ONE_HERE] = "No one here!";
                $output[self::ID_GAMEVIEW_BODY_ASIDE_JOIN_GAME_PART_1] = "Join Game with";
                $output[self::ID_GAMEVIEW_BODY_ASIDE_JOIN_GAME_PART_2] = "and Code";
                $output[self::ID_GAMEVIEW_BODY_ASIDE_JOIN_GAME_PART_3] = "";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_QUESTION_SECTION_TIME_REMAINING_PART_1] = "";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_QUESTION_SECTION_TIME_REMAINING_PART_2] = "seconds to go!";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_PART1] = "It's";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_PART2] = "";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_TERM_THE_ONION] = "THE ONION";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_TERM_NOT_THE_ONION] = "NOT THE ONION";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_ANSWER_CORRECT] = "Your Answer was correct!";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_ANSWER_WRONG] = "Your Answer was wrong!";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_LINK_TO_POST] = "Link to Post";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_FORM_UPVOTE_SUBMIT_VALUE] = "UPVOTE";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_FORM_DOWNVOTE_SUBMIT_VALUE] = "DOWNVOTE";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SECTION_TIME_REMAINING_PART_1] = "";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SECTION_TIME_REMAINING_PART_2] = "seconds to go!";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_END_FORM_NEXT_ROUND_SUBMIT_VALUE] = "PLAY AGAIN!";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_END_HEADLINE] = "The Game Has Ended";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_END_RANKING_HEADLINE] = "Ranking";
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_END_RANKING_TERM_POINTS] = $output[self::ID_GAMEVIEW_BODY_MAIN_PLAYERS_TERM_POINTS];
                $output[self::ID_GAMEVIEW_BODY_MAIN_STATE_END_RANKING_NO_ONE_HERE] = "No one can be ranked!";

                $output[self::ID_ERRORDOCUMENT303VIEW_BODY_MAIN_HEADLINE] = "303 - See Other";
                $output[self::ID_ERRORDOCUMENT303VIEW_BODY_MAIN_PARAGRAPH] = "We have tried to forward you, but it was not possible for us.<br/>" .
                    "Please click on the following link to get to the destination:";

                $output[self::ID_ERRORDOCUMENT404VIEW_BODY_MAIN_HEADLINE] = "404 - Not Found";
                $output[self::ID_ERRORDOCUMENT404VIEW_BODY_MAIN_PARAGRAPH] = "Your or your browser have requested a file, which we do not know.<br/>" .
                    "Please return to the main site.";

                $output[self::ID_ERRORDOCUMENT500VIEW_BODY_MAIN_HEADLINE] = "500 - Internal Server Error";
                $output[self::ID_ERRORDOCUMENT500VIEW_BODY_MAIN_PARAGRAPH] = "An internal error occurred in the server, which most probably has been achieved through erroneous programming.";

                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_RESETCONTROLLER_RESET_SUCCESSFUL] = "The reset has been successful!";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_RESETCONTROLLER_RESET_ERROR_GENERIC] = "An error occurred during the reset!";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_USERNAME_MISSING] = "Your name has been missing!";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_USERNAME_TOO_LONG] = "Your name has been too long! Please choose one smaller than 60 characters.";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_CODE_TOO_LONG] = "Your invite code has been too long! It should be smaller than 17 characters.";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_TIMER_INVALID] = "Your value for the timer has been invalid! Please use a number.";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_TIMER_TOO_MUCH_TIME] = "Your value for the timer exceeded 10 minutes! That's too long!";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_CREATING_LOBBY_FAILED] = "Creating the lobby has failed!";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_JOINING_LOBBY_FAILED] = "Joining the lobby has failed!";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_WRONG_STATE] = "An internal server error happened.";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_LOBBYID_NULL] = "The lobby does not exist!";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_BOTH_OPTIONS_WERE_WANTED] = "An internal server error happened.";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_INVALID_QUESTION_ID_NOT_EXISTS] = "An internal server error happened.";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_INVALID_QUESTION_ID_NO_NUMBER] = "An internal server error happened.";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_MISSING_QUESTION_ID] = "An internal server error happened.";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_QUESTIONS_INVALID] = "The number of questions has been invalid! Please use a number!";
                $output[self::ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_MINIMUM_SCORE_INVALID] = "The minimum score has been invalid! Please use a number!";
        }
        return $output;
    }

    public const ID_GENERAL_GAME_NAME = "General_Game_Name";

    public const ID_VIEWFRAMEHELPER_HTML_HEAD_TITLE_TEXT = "ViewFrameHelper_Html_Head_Title_Text";
    public const ID_VIEWFRAMEHELPER_HTML_BODY_TITLE_TEXT = "ViewFrameHelper_Html_Body_Title_Text";
    public const ID_VIEWFRAMEHELPER_HTML_BODY_SUBTITLE_TEXT = "ViewFrameHelper_Html_Body_Subtitle_Text";

    public const ID_INDEXVIEW_BODY_MAIN_GAME_DESCRIPTION = "IndexView_Body_Main_Game_Description";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_USERNAME_NEW_GAME_PLACEHOLDER = "IndexView_Body_Main_Form_Username_New_Game_Placeholder";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_LABEL = "IndexView_Body_Main_Form_Just_Watch_New_Game_Label";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_JUST_WATCH_NEW_GAME_VALUE = "IndexView_Body_Main_Form_Just_Watch_New_Game_Value";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_INVITE_CODE_PLACEHOLDER = "IndexView_Body_Main_Form_Invite_Code_Placeholder";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_MAX_QUESTIONS_PLACEHOLDER = "IndexView_Body_Main_Form_Max_Questions_Placeholder";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_MAX_QUESTIONS_DESCRIPTION_PARAGRAPH = "IndexView_Body_Main_Form_Max_Questions_Description_Paragraph";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_MINIMUM_SCORE_PLACEHOLDER = "IndexView_Body_Main_Form_Minimum_Score_Placeholder";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_MINIMUM_SCORE_DESCRIPTION_PARAGRAPH = "IndexView_Body_Main_Form_Minimum_Score_Paragraph";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_TIMER_WANTED_PLACEHOLDER = "IndexView_Body_Main_Form_Timer_Wanted_Placeholder";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_TIMER_WANTED_DESCRIPTION_PARAGRAPH = "IndexView_Body_Main_Form_Timer_Wanted_Paragraph";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_PARAGRAPH_FURTHER_GAME_START_EXPLANATION = "IndexView_Body_Main_Form_Paragraph_Further_Game_Start_Explanation";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE = "IndexView_Body_Main_Form_Submit_Value";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE_JOIN = "IndexView_Body_Main_Form_Submit_Value_Join";
    public const ID_INDEXVIEW_BODY_MAIN_FORM_SUBMIT_VALUE_CREATE = "IndexView_Body_Main_Form_Submit_Value_Create";

    public const ID_RESETVIEW_BODY_MAIN_HEADLINE = "ResetView_Body_Main_Headline";
    public const ID_RESETVIEW_BODY_MAIN_PARAGRAPH_EXPLANATION = "ResetView_Body_Main_Paragraph_Explanation";
    public const ID_RESETVIEW_BODY_MAIN_FORM_SUBMIT_VALUE = "ResetView_Body_Main_Form_Submit_Value";

    public const ID_GAMEVIEW_BODY_MAIN_TYPE_OF_PLAYER_WATCHER = "GameView_Body_Main_Type_Of_Player_Watcher";
    public const ID_GAMEVIEW_BODY_MAIN_TYPE_OF_PLAYER_PLAYER = "GameView_Body_Main_Type_Of_Player_Player";
    public const ID_GAMEVIEW_BODY_MAIN_EXIT_THE_GAME = "GameView_Body_Main_Exit_The_Game";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_START_HEADLINE_WELCOME = "GameView_Body_Main_State_Start_Headline_Welcome";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_START_FORM_START_GAME_SUBMIT_VALUE = "GameView_Body_Main_State_Start_Form_Start_Game_Submit_Value";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_QUESTION_FORM_SET_ONION_SUBMIT_VALUE = "GameView_Body_Main_State_Question_Form_Set_Onion_Submit_Value";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_QUESTION_FORM_SET_NOT_ONION_SUBMIT_VALUE = "GameView_Body_Main_State_Question_Form_Set_Not_Onion_Submit_Value";
    public const ID_GAMEVIEW_BODY_MAIN_PLAYERS_HEADLINE = "GameView_Body_Main_Players_Headline";
    public const ID_GAMEVIEW_BODY_MAIN_PLAYERS_TERM_POINTS = "GameView_Body_Main_Players_Term_Points";
    public const ID_GAMEVIEW_BODY_MAIN_PLAYERS_TERM_WATCHING = "GameView_Body_Main_Players_Term_Watching";
    public const ID_GAMEVIEW_BODY_MAIN_PLAYERS_NO_ONE_HERE = "GameView_Body_Main_Players_No_One_Here";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_QUESTION_SECTION_TIME_REMAINING_PART_1 = "GameView_Body_Main_State_Question_Section_Time_Remaining_Part_1";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_QUESTION_SECTION_TIME_REMAINING_PART_2 = "GameView_Body_Main_State_Question_Section_Time_Remaining_Part_2";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_PART1 = "GameView_Body_Main_State_Aftermath_SubHeadline_Part1";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_PART2 = "GameView_Body_Main_State_Aftermath_SubHeadline_Part2";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_TERM_THE_ONION = "GameView_Body_Main_State_Aftermath_SubHeadline_Term_The_Onion";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_TERM_NOT_THE_ONION = "GameView_Body_Main_State_Aftermath_SubHeadline_Term_Not_The_Onion";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_ANSWER_CORRECT = "GameView_Body_Main_State_Aftermath_SubHeadline_Answer_Correct";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SUBHEADLINE_ANSWER_WRONG = "GameView_Body_Main_State_Aftermath_SubHeadline_Answer_Wrong";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SECTION_TIME_REMAINING_PART_1 = "GameView_Body_Main_State_Aftermath_Section_Time_Remaining_Part_1";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_SECTION_TIME_REMAINING_PART_2 = "GameView_Body_Main_State_Aftermath_Section_Time_Remaining_Part_2";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_LINK_TO_POST = "GameView_Body_Main_State_Aftermath_Link_To_Post";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_FORM_UPVOTE_SUBMIT_VALUE = "GameView_Body_Main_State_Aftermath_Form_Upvote_Submit_Value";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_AFTERMATH_FORM_DOWNVOTE_SUBMIT_VALUE = "GameView_Body_Main_State_Aftermath_Form_Downvote_Submit_Value";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_END_FORM_NEXT_ROUND_SUBMIT_VALUE = "GameView_Body_Main_State_End_Form_Next_Round_Submit_Value";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_END_HEADLINE = "GameView_Body_Main_State_End_Headline";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_END_RANKING_HEADLINE = "GameView_Body_Main_State_End_Ranking_Headline";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_END_RANKING_TERM_POINTS = "GameView_Body_Main_State_End_Ranking_Term_Points";
    public const ID_GAMEVIEW_BODY_MAIN_STATE_END_RANKING_NO_ONE_HERE = "GameView_Body_Main_State_End_Ranking_No_One_Here";
    public const ID_GAMEVIEW_BODY_ASIDE_JOIN_GAME_PART_1 = "GameView_Body_Aside_Join_Game_Part_1";
    public const ID_GAMEVIEW_BODY_ASIDE_JOIN_GAME_PART_2 = "GameView_Body_Aside_Join_Game_Part_2";
    public const ID_GAMEVIEW_BODY_ASIDE_JOIN_GAME_PART_3 = "GameView_Body_Aside_Join_Game_Part_3";

    public const ID_ERRORDOCUMENT303VIEW_BODY_MAIN_HEADLINE = "ErrorDocument303View_Body_Main_Headline";
    public const ID_ERRORDOCUMENT303VIEW_BODY_MAIN_PARAGRAPH = "ErrorDocument303View_Body_Main_Paragraph";

    public const ID_ERRORDOCUMENT404VIEW_BODY_MAIN_HEADLINE = "ErrorDocument404View_Body_Main_Headline";
    public const ID_ERRORDOCUMENT404VIEW_BODY_MAIN_PARAGRAPH = "ErrorDocument404View_Body_Main_Paragraph";

    public const ID_ERRORDOCUMENT500VIEW_BODY_MAIN_HEADLINE = "ErrorDocument500View_Body_Main_Headline";
    public const ID_ERRORDOCUMENT500VIEW_BODY_MAIN_PARAGRAPH = "ErrorDocument500View_Body_Main_Paragraph";

    public const ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_RESETCONTROLLER_RESET_SUCCESSFUL = "FrontendRequestAcrossMessagesLogic_Message_ResetController_Reset_Successful";
    public const ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_RESETCONTROLLER_RESET_ERROR_GENERIC = "FrontendRequestAcrossMessagesLogic_Message_ResetController_Reset_Error_Generic";
    public const ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_USERNAME_MISSING = "FrontendRequestAcrossMessagesLogic_Message_IndexController_Error_Username_Missing";
    public const ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_USERNAME_TOO_LONG = "FrontendRequestAcrossMessagesLogic_Message_IndexController_Error_Username_Too_Long";
    public const ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_CODE_TOO_LONG = "FrontendRequestAcrossMessagesLogic_Message_IndexController_Error_Code_Too_Long";
    public const ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_TIMER_INVALID = "FrontendRequestAcrossMessagesLogic_Message_IndexController_Error_Timer_Invalid";
    public const ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_TIMER_TOO_MUCH_TIME = "FrontendRequestAcrossMessagesLogic_Message_IndexController_Error_Timer_Too_Much_Time";
    public const ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_CREATING_LOBBY_FAILED = "FrontendRequestAcrossMessagesLogic_Message_IndexController_Error_Creating_Lobby_Failed";
    public const ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_JOINING_LOBBY_FAILED = "FrontendRequestAcrossMessagesLogic_Message_IndexController_Error_Joining_Lobby_Failed";
    public const ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_WRONG_STATE = "FrontendRequestAcrossMessagesLogic_Message_GameController_Error_Wrong_State";
    public const ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_LOBBYID_NULL = "FrontendRequestAcrossMessagesLogic_Message_GameController_Error_LobbyId_Null";
    public const ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_BOTH_OPTIONS_WERE_WANTED = "FrontendRequestAcrossMessagesLogic_Message_GameController_Error_Both_Options_Were_Wanted";
    public const ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_INVALID_QUESTION_ID_NOT_EXISTS = "FrontendRequestAcrossMessagesLogic_Message_GameController_Error_Invalid_Question_Id_Not_Exists";
    public const ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_INVALID_QUESTION_ID_NO_NUMBER = "FrontendRequestAcrossMessagesLogic_Message_GameController_Error_Invalid_Question_Id_No_Number";
    public const ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_GAMECONTROLLER_ERROR_MISSING_QUESTION_ID = "FrontendRequestAcrossMessagesLogic_Message_GameController_Error_Missing_Question_Id";
    public const ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_QUESTIONS_INVALID = "FrontendRequestAcrossMessagesLogic_Message_IndexController_Error_Questions_Invalid";
    public const ID_FRONTENDREQUESTACROSSMESSAGESLOGIC_MESSAGE_INDEXCONTROLLER_ERROR_MINIMUM_SCORE_INVALID = "FrontendRequestAcrossMessagesLogic_Message_IndexController_Error_Minimum_Score_Invalid";
}