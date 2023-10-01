<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Message extends ActiveRecord {
    public static function tableName(): string
    {
        return 'messages';
    }

    public function getFormattedCreatedAt()
    {
        return Yii::$app->formatter->asRelativeTime($this->created_at);
    }
}