<?php

use yii\helpers\Html;

/** @var \yii\web\View $this view component instance */
/** @var \yii\mail\MessageInterface $message the message being composed */
/** @var string $content main view render result */
$model = $this->params['model'];
$name = $this->params['name'];
$site = $this->params['site'];
?>

<?php $this->beginPage() ?>
<?php $this->beginBody() ?>
<?= $model->title ?>

Hai <?= $name ?>

<?= $model->message ?>

<?= $site['website'] ?>

<?= $site['appName'] ?>

<?php $this->endBody() ?>
<?php $this->endPage() ?>