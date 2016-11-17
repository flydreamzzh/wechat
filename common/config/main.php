<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'wechat' => [
            'class' => 'callmez\wechat\sdk\Wechat',
            'appId' => 'wx83115b29d7db9967',
            'appSecret' => '73a262bf6fab3a2d5e7ff7cbb2445ae0',
            'token' => 'df4sd56f4sd53f4f56g4s3df4sd3f4sd53f453'
        ]
    ],
];
