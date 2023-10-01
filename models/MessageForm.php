<?php

namespace app\models;

use yii\base\Model;

class MessageForm extends Model {
    public $name;
    public $content;
    public $captcha;

    public const ERROR_CHECK_LIMITER = 'error_check_limiter';
    public const ERROR_DB = 'error_db';

    public function attributeLabels() {
        return [
            'name' => 'Автор',
            'content' => 'Сообщение',
            'captcha' => 'Код с картинки'
        ];
    }

    public function rules()
    {
        return [
            [['name', 'content', 'captcha'], 'required'],
            ['name', 'string', 'min' => 2, 'max' => 15],
            ['content', 'string', 'min' => 30, 'max' => 1000],
            ['content', 'filter', 'filter' => function ($value) {
                return strip_tags($value, '<b><i><s>');
            }],
            ['captcha', 'captcha']
        ];
    }

    public function check_rate_limiter($ip): bool
    {
        $current_time = time();
        $time_ago = $current_time - 180;

        return
            Message::find()
            ->where(['ip' => $ip])
            ->andWhere(['>=', 'created_at', $time_ago])
            ->exists();
    }

    /**
     * Создает новое сообщение и сохраняет его в базе данных.
     *
     * @param string $ip IP-адрес отправителя сообщения.
     *
     * @return array Массив с результатом операции:
     *   - 'success' (bool): Успешно ли выполнено добавление сообщения.
     *   - 'error' (string|null): Тип ошибки, если операция завершилась неуспешно. Может быть null если сохранение успешно.
     *
     * Возможные значения 'error':
     *   - self::ERROR_CHECK_LIMITER: Ошибка проверки лимитера (если $this->check_rate_limiter($ip) возвращает true).
     *   - self::ERROR_DB: Ошибка при сохранении сообщения в базе данных (если $message->save() возвращает false).
     */
    public function new_message(string $ip): array
    {
        if ($this->check_rate_limiter($ip)) {
            return [
                'success' => false,
                'error' => self::ERROR_CHECK_LIMITER
            ];
        }

        $message = new Message();
        $message->name = $this->name;
        $message->content = $this->content;
        $message->ip = $ip;
        $message->created_at = time();
        $success_save = $message->save();

        if (!$success_save) {
            return [
                'success' => false,
                'error' => self::ERROR_DB
            ];
        }

        return [
            'success' => true
        ];
    }
}