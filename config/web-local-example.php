<?php
return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=127.0.0.1;dbname=db',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'in-v3.mailjet.com',
                'username' => '',
                'password' => '',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],

    ],
];
