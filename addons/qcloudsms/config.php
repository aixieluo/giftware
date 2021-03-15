<?php

return [
    [
        'name' => 'appid',
        'title' => '应用AppID',
        'type' => 'string',
        'content' => [],
        'value' => '1400489508',
        'rule' => 'required',
        'msg' => '',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ],
    [
        'name' => 'appkey',
        'title' => '应用AppKEY',
        'type' => 'string',
        'content' => [],
        'value' => '87d5055a14eafec3051d285b207e27bc',
        'rule' => 'required',
        'msg' => '',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ],
    [
        'name' => 'voiceAppid',
        'title' => '语音短信AppID',
        'type' => 'string',
        'content' => [],
        'value' => '1400489508',
        'rule' => 'required',
        'msg' => '使用语音短信必须设置',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ],
    [
        'name' => 'voiceAppkey',
        'title' => '语音短信AppKEY',
        'type' => 'string',
        'content' => [],
        'value' => '87d5055a14eafec3051d285b207e27bc',
        'rule' => 'required',
        'msg' => '使用语音短信必须设置',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ],
    [
        'name' => 'sign',
        'title' => '签名',
        'type' => 'string',
        'content' => [],
        'value' => '杭州柠柠网络科技有限公司',
        'rule' => 'required',
        'msg' => '',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ],
    [
        'name' => 'isVoice',
        'title' => '是否使用语音短信',
        'type' => 'radio',
        'content' => [
            '否',
            '是',
        ],
        'value' => '0',
        'rule' => 'required',
        'msg' => '',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ],
    [
        'name' => 'isTemplateSender',
        'title' => '是否使用短信模板发送',
        'type' => 'radio',
        'content' => [
            '否',
            '是',
        ],
        'value' => '1',
        'rule' => 'required',
        'msg' => '',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ],
    [
        'name' => 'template',
        'title' => '短信模板',
        'type' => 'array',
        'content' => [],
        'value' => [
            'register' => '880838',
            'resetpwd' => '880838',
            'changepwd' => '880838',
            'profile' => '880838',
        ],
        'rule' => 'required',
        'msg' => '',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ],
    [
        'name' => 'voiceTemplate',
        'title' => '语音短信模板',
        'type' => 'array',
        'content' => [],
        'value' => [
            'register' => '',
            'resetpwd' => '',
            'changepwd' => '',
            'profile' => '',
        ],
        'rule' => 'required',
        'msg' => '',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ],
];