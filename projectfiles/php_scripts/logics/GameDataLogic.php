<?php

namespace logics;

require_once(__DIR__ . "/../integration/DatabaseIntegration.php");

class GameDataLogic
{
    public static function getHeadlineByGameDataId(int $gamedataid) {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `headline` FROM `gamedata` WHERE `id` = ?;"
        );
        if($preparedStatement->execute(array($gamedataid))) {
            if($preparedStatement->rowCount() > 0) {
                return $preparedStatement->fetch(\PDO::FETCH_ASSOC)["headline"];
            }
        }
        return NULL;
    }

    public static function isOnionByGameDataId(int $gamedataid) {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `is_onion` FROM `gamedata` WHERE `id` = ?;"
        );
        if($preparedStatement->execute(array($gamedataid))) {
            if($preparedStatement->rowCount() > 0) {
                return $preparedStatement->fetch(\PDO::FETCH_ASSOC)["is_onion"] == 1;
            }
        }
        return NULL;
    }

    public static function getHyperlinkByGameDataId(int $gamedataid) {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `hyperlink` FROM `gamedata` WHERE `id` = ?;"
        );
        if($preparedStatement->execute(array($gamedataid))) {
            if($preparedStatement->rowCount() > 0) {
                return $preparedStatement->fetch(\PDO::FETCH_ASSOC)["hyperlink"];
            }
        }
        return NULL;
    }

    public static function getFurtherExplanationByGameDataId(int $gamedataid) {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `further_explanation` FROM `gamedata` WHERE `id` = ?;"
        );
        if($preparedStatement->execute(array($gamedataid))) {
            if($preparedStatement->rowCount() > 0) {
                return $preparedStatement->fetch(\PDO::FETCH_ASSOC)["further_explanation"];
            }
        }
        return NULL;
    }

    public static function getPictureByGameDataId(int $gamedataid) {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `picture` FROM `gamedata` WHERE `id` = ?;"
        );
        if($preparedStatement->execute(array($gamedataid))) {
            if($preparedStatement->rowCount() > 0) {
                return $preparedStatement->fetch(\PDO::FETCH_ASSOC)["picture"];
            }
        }
        return NULL;
    }

    public static function getUpvotesByGameDataId(int $gamedataid) {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `upvotes` FROM `gamedata` WHERE `id` = ?;"
        );
        if($preparedStatement->execute(array($gamedataid))) {
            if($preparedStatement->rowCount() > 0) {
                return $preparedStatement->fetch(\PDO::FETCH_ASSOC)["upvotes"];
            }
        }
        return NULL;
    }

    public static function getDownvotesByGameDataId(int $gamedataid) {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT `downvotes` FROM `gamedata` WHERE `id` = ?;"
        );
        if($preparedStatement->execute(array($gamedataid))) {
            if($preparedStatement->rowCount() > 0) {
                return $preparedStatement->fetch(\PDO::FETCH_ASSOC)["downvotes"];
            }
        }
        return NULL;
    }

    public static function increaseUpvotesByGameDataId(int $gamedataid) {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "UPDATE `gamedata` SET `upvotes` = `upvotes` + 1 WHERE `id` = ?;"
        );
        return $preparedStatement->execute(array($gamedataid));
    }

    public static function decreaseUpvotesByGameDataId(int $gamedataid) {
        if(self::getUpvotesByGameDataId($gamedataid) > 0) {
            $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
                "UPDATE `gamedata` SET `upvotes` = `upvotes` - 1 WHERE `id` = ?;"
            );
            return $preparedStatement->execute(array($gamedataid));
        }
        return FALSE;
    }

    public static function increaseDownvotesByGameDataId(int $gamedataid) {
        $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
            "UPDATE `gamedata` SET `downvotes` = `downvotes` + 1 WHERE `id` = ?;"
        );
        return $preparedStatement->execute(array($gamedataid));
    }

    public static function decreaseDownvotesByGameDataId(int $gamedataid) {
        if(self::getUpvotesByGameDataId($gamedataid) > 0) {
            $preparedStatement = \integration\DatabaseIntegration::getWriteInstance()->getConnection()->prepare(
                "UPDATE `gamedata` SET `downvotes` = `downvotes` - 1 WHERE `id` = ?;"
            );
            return $preparedStatement->execute(array($gamedataid));
        }
        return FALSE;
    }

    public static function count() {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT COUNT(*) AS anzahl FROM `gamedata`;"
        );
        if($preparedStatement->execute(array())) {
            if($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["anzahl"];
            }
        }
        return NULL;
    }

    public static function countWithMinimumScore(int $minimumScore) {
        $preparedStatement = \integration\DatabaseIntegration::getReadInstance()->getConnection()->prepare(
            "SELECT COUNT(*) AS anzahl FROM `gamedata` WHERE (`upvotes` - `downvotes`) >= ?;"
        );
        if($preparedStatement->execute(array($minimumScore))) {
            if($preparedStatement->rowCount() > 0) {
                $fetched_row = $preparedStatement->fetch(\PDO::FETCH_ASSOC);
                return $fetched_row["anzahl"];
            }
        }
        return NULL;
    }

    public static function getScoreByGameDataId(int $gamedataid) {
        $downvotes = self::getDownvotesByGameDataId($gamedataid);
        $upvotes = self::getUpvotesByGameDataId($gamedataid);
        return $upvotes - $downvotes;
    }
}