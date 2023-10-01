<?php

/** @var yii\web\View $this */

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\HtmlPurifier;
use app\components\helpers\IpFormatter;
use app\components\helpers\FormatUnixTime;


$this->title = 'История';

?>

<div class="row">
    <div class="col-md-6">
        <?php foreach ($messages as $message) { ?>
            <div class="card card-default">
                <div class="card-body">
                    <h5 class="card-title"><?= Html::encode($message->name) ?></h5>
                    <p><?= HtmlPurifier::process($message->content) ?></p>
                    <p>
                        <small class="text-muted">
                            <?= FormatUnixTime::format($message->created_at) ?> |
                            <?= IpFormatter::hideOctets($message->ip) ?>
                        </small>
                    </p>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="col-md-6">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['placeholder' => 'Имя']) ?>

        <?= $form->field($model, 'content')->textarea(['rows' => 3, 'placeholder' => 'Ваши гениальные мысли, которые запомнит история']) ?>

        <?= $form->field($model, 'captcha')->widget(Captcha::class) ?>

        <div class="form-group">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>