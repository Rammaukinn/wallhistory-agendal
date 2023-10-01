<?php

namespace app\controllers;

use app\models\Message;
use app\models\MessageForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new MessageForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $ip = Yii::$app->request->userIP;
            $result_save = $model->new_message($ip);

            if ($result_save['success']) {
                Yii::$app->session->setFlash('success', 'Сообщение успешно отправлено!');
            } else {
                switch ($result_save['error']) {
                    case MessageForm::ERROR_CHECK_LIMITER:
                        Yii::$app->session->setFlash('error', 'Вы можете отправлять только одно сообщение каждые три минуты.');
                        break;
                    case MessageForm::ERROR_DB:
                        Yii::$app->session->setFlash('error', 'При отправлении сообщения произошла ошибка, попробуйте позже.');
                        break;
                }
            }
        }

        $messages = Message::find()
            ->orderBy('created_at desc')
            ->all();

        return $this->render('index', ['model' => $model, 'messages' => $messages]);
    }

    public function actionRules()
    {
        return $this->render('rules');
    }
}
