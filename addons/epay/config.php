<?php

return [
    [
        'name' => 'wechat',
        'title' => '微信',
        'type' => 'array',
        'content' => [],
        'value' => [
            'appid' => '',
            'app_id' => '',
            'app_secret' => '',
            'miniapp_id' => '',
            'mch_id' => '',
            'key' => '',
            'mode' => 'normal',
            'sub_mch_id' => '',
            'sub_appid' => '',
            'sub_app_id' => '',
            'sub_miniapp_id' => '',
            'notify_url' => '/addons/epay/api/notifyx/type/wechat',
            'cert_client' => '/addons/epay/certs/apiclient_cert.pem',
            'cert_key' => '/addons/epay/certs/apiclient_key.pem',
            'log' => '1',
        ],
        'rule' => '',
        'msg' => '',
        'tip' => '微信参数配置',
        'ok' => '',
        'extend' => '',
    ],
    [
        'name' => 'alipay',
        'title' => '支付宝',
        'type' => 'array',
        'content' => [],
        'value' => [
            'app_id' => '2021002108614220',
            'mode' => 'normal',
            'notify_url' => '/addons/epay/api/notifyx/type/alipay',
            'return_url' => '/addons/epay/api/returnx/type/alipay',
            'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAicxfJ1rxFQlezENFZGJIcuvGSZeQIlr7Sme236W3BkpRTpP7+XfF243LpT7VB63IA1MXH0A3HTdP0tIb8QshbwXvbEfVBAAK8qWeXGwiWsXtCU6D7BGyXXBrr574Jf7qOOb93pNHZq+2apIwR/um6pCbyhbLNtOT/SLOLvqEsrdamnDUoPk6481OhzSqtX64/38ZsurmJCiQ37H0mIPH9m42cwHDctYE4h8YAZH+12ak0JaQzWNBCzPto+A0pHi2JXnM+3KlnnMvrgyOJBdlJDJRZUl/hJJvtFL08wyFcAkTNrPst2JBnQjioZbm65nYFsIBaeCk76ptVOlM8q48VwIDAQAB',
            'private_key' => 'MIIEowIBAAKCAQEAn7iaq5wrCU1ezK5T84/1Ewg+jQVgWAzDbP62htjAPZaHp2Vy5/C2vBaWkuMWiLaX79O+21Yrkyvt5phhrypBOxzksW1E6GO0wy4Z5FTD/sKT/Fa3QMr24d5BsbVHOBvkKE341/VNDqtMns/1w9LH0rYwtgASpGMz97WZhsNVhNoq1M0hpsyWjkvfOL+WtGJAa83a7DYXAWB2RDyxaFmXY6C1c/jj1NsDRI596quFQKdIj1yZ5wWJHFd0HVflgSOaPLhgkg/FScuqHluN1oZCJa0b2Htl/5g8+Jx8a0rAG5ufh3zPdfkSSmrBm8zVHcQlDPKGUl3Y6mFI074a5l2C9QIDAQABAoIBACXfbC2NFldaVURLgfSbCg29QrfFspauUBikPTu0YcE41GnJEHoXBf2LjaC+4DUCtvxTRUpVIHgBTqQDNgaHCnit3TyFIHXKTq6JW/Jcdy0NjnGjTki619evD/zHc1/GnU9BTeRHckIsNDrkLO6GKIO6V8qQMdtw+n2ePg1KfBjIErI+JNHib2m8afzpOCZ4XzxOauSbNjITpxWVt7B84oagdR5L+Hz9UAoJkDVd3X0oinWvDzC+QtAZ+lrw4zb+HzAzULgHA4B280OtCPzwVUgV6d4JlKTMIx0ejh5DivZPw/gvsRdYF56PUIjA5p6IizfyNVnh6kDz9JX35/JRloECgYEA0bC5W9E92BLjO9QAnrZlncgFFEAKt49GjAS+mc1CkmImhQjXnD2LEFzvwFszo9wEBHTEKKbAzzI90e/HPb0df5y/d95oG9ShvOGLBO1amrQ7YGkIFIl04ovTBzOWag83aW/yNnX5alCY8EsQJf3nKITpMhXyEAzSp25nKLSZZ7kCgYEAwv7EYqmeRhcN5/fFA3Z/zZeALG3/C6wwkEgcjCMFT2ieb28hse3NVD3ZezjqKQlXKgn3DWyXblxIPw69KhtgvSX13NoCicViAeKZoj9nlNqgBhg3HMXKqSXsQ0GPrbkwDRs8sHlsM5ACuev19OqAo7KUrKo2mwWEyW2RfqUOWx0CgYBli3hn729MiPED0wmt0lRpUSisgsrt03NtcQrAPndjniZbPEn1fpQui2MLOt9KFczYP5eSvBYZAJKRbNWGn2N+nfDW4px2BcWBS5PgAfVjf65VZOel0l8JKn86OJA5sj66T5zzJLRw+LDnhOJAE7HynFK1j40Wmq8Up9FLFBJJ+QKBgQCO9ZmlfBW97UkUuGKIl2g7oscly78hH6y7GyNS2poaDemaaS+a822GRZIc9S03yNFO0N9/yA19q8qL8JJfPJNAGYSX1n3l5ABmwWtBuJqIV4Da9wXw4lIikNsWtApSo2LHOSDVgTAC3aIiDQzV8tc9LMAzltdR8EnaI6p33ysXQQKBgCU0dJoWakEYtZhvUW/LZS32zaGhyh3gcFRanWMZLr36tWH9GkRtBm3oWHgteoOx+8fBDjQGpTnOQtRrmzYlcp0ISeQcbxlOOeGDu0yj2vzscHZ3fOSBciEEQjDvPIRQrZncEE/f29FL/bq/blXbyET+n+MTCW2IDuNn9Xf8qn0R',
            'log' => '1',
            'scanpay' => '1',
        ],
        'rule' => 'required',
        'msg' => '',
        'tip' => '支付宝参数配置',
        'ok' => '',
        'extend' => '',
    ],
    [
        'name' => '__tips__',
        'title' => '温馨提示',
        'type' => 'array',
        'content' => [],
        'value' => '请注意微信支付证书路径位于/addons/epay/certs目录下，请替换成你自己的证书<br>appid：APP的appid<br>app_id：公众号的appid<br>app_secret：公众号的secret<br>miniapp_id：小程序ID<br>mch_id：微信商户ID<br>key：微信商户支付的密钥',
        'rule' => '',
        'msg' => '',
        'tip' => '微信参数配置',
        'ok' => '',
        'extend' => '',
    ],
];
