<?php

namespace logics;

require_once(__DIR__ . "/../integration/DatabaseIntegration.php");


class GameDataLocalizationLogic
{
    public static function add(int $gid, string $localeShort, string $headline, string $furtherExplanation) {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "INSERT INTO `gamedata_localization` (`gid`, `locale`, `headline`, `further_explanation`) VALUES (?, ?, ?, ?);"
        );
        return $preparedStatement->execute(array($gid, $localeShort, $headline, $furtherExplanation));
    }

    public static function containsByGameDataIdAndLocaleShort(int $gid, string $localeShort) {
        return self::getIdByGameDataIdAndLocaleShort($gid, $localeShort) !== NULL;
    }

    public static function getIdByGameDataIdAndLocaleShort(int $gid, string $localeShort) {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `id` FROM `gamedata_localization` WHERE `gid` = ? AND `locale` = ?;"
        );
        if($preparedStatement->execute(array($gid, $localeShort))) {
            if($preparedStatement->rowCount() > 0) {
                return $preparedStatement->fetch(\PDO::FETCH_ASSOC)["id"];
            }
        }
        return NULL;
    }

    public static function getHeadlineByGameDataIdAndLocaleShort(int $gid, string $localeShort) {
        return self::getHeadlineByGameDataLocalizationId(self::getIdByGameDataIdAndLocaleShort($gid, $localeShort));
    }

    public static function getHeadlineByGameDataLocalizationId(int $gdlid) {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `headline` FROM `gamedata_localization` WHERE `id` = ?;"
        );
        if($preparedStatement->execute(array($gdlid))) {
            if($preparedStatement->rowCount() > 0) {
                return $preparedStatement->fetch(\PDO::FETCH_ASSOC)["headline"];
            }
        }
        return NULL;
    }

    public static function getFurtherExplanationByGameDataIdAndLocaleShort(int $gid, string $localeShort) {
        return self::getFurtherExplanationByGameDataLocalizationId(self::getIdByGameDataIdAndLocaleShort($gid, $localeShort));
    }

    public static function getFurtherExplanationByGameDataLocalizationId(int $gdlid) {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `further_explanation` FROM `gamedata_localization` WHERE `id` = ?;"
        );
        if($preparedStatement->execute(array($gdlid))) {
            if($preparedStatement->rowCount() > 0) {
                return $preparedStatement->fetch(\PDO::FETCH_ASSOC)["further_explanation"];
            }
        }
        return NULL;
    }
}