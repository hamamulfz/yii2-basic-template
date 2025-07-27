<?php

use yii\helpers\Html;

$model = $this->params['model'];
$site = $this->params['site'];
$name = $this->params['name'];
/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <?php $this->head() ?>
</head>


<body>
    <?php $this->beginBody() ?>
    <div class="verify-email">
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="font-family: Arial, Helvetica, sans-serif">
            <tr>
                <td align="center">
                    <img src="https://potential-hazard-report-kcic.web-dev.biz.id/assets/logo.png" alt="" style="width: 50%" />
                </td>
            </tr>
            <tr>
                <td bgcolor="#ffffff" style="padding: 0px 30px 40px 30px">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td align="center">
                                <h2 style="color: #134784 !important">
                                <?= $model->title ?>
                                </h3>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 20px 0 30px 0">
                                <p>Hai <?= Html::encode($name) ?>,</p>
                                <p><?= $model->message ?></b>.</p>
                            </td>
                        <tr>
                            <td align="center" style="border-radius: 12px;" bgcolor="#E10600">
                                <a href="<?= $site['website'] ?>" target="_blank" style="border: none;color: white;padding: 20px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;margin: 4px 2px;cursor: pointer;">
                                    Go to <?= $site['appName'] ?>
                                </a>
                            </td>
                        </tr>
            </tr>
        </table>
        <p>-<strong> KCIC </strong>SSHE</p>
    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>