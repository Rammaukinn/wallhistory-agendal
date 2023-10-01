<?php

namespace app\components\helpers;

use Yii;

class FormatUnixTime {
    public static function format($timestamp) {
        $currentTime = time();
        $timeDiff = $currentTime - $timestamp;

        if ($timeDiff < 3600) {
            // Менее часа назад
            $minutesAgo = floor($timeDiff / 60);
            return Yii::t('app', '{n, plural, =1{1 минуту назад} one{# минуту назад} few{# минуты назад} many{# минут назад} other{# минут назад}}', ['n' => $minutesAgo]);
        } elseif ($timeDiff < 86400) {
            // Более часа назад, но менее 24 часов
            $hoursAgo = floor($timeDiff / 3600);
            return Yii::t('app', '{n, plural, =1{1 час назад} one{# час назад} few{# часа назад} many{# часов назад} other{# часов назад}}', ['n' => $hoursAgo]);
        } else {
            // Более 24 часов назад
            return Yii::$app->formatter->asDatetime($timestamp, 'long');
        }
    }
}