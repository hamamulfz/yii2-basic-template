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
Hai <?= $name ?>

OTP Anda adalah : <?= $model->otp ?>.
Jangan bagikan kode OTP kepada siapapun. Untuk alasan apapun, jangan pernah membagikan one-time password ke siapapun. Pihak KCIC resmi sendiri juga tidak akan meminta kode tersebut.

<?= $site['website'] ?>

<?= $site['appName'] ?>

<?php $this->endBody() ?>
<?php $this->endPage() ?>