<?php

namespace app\index\controller\traits;

use function fast\array_get;

trait KuaiBaoTrait
{
    protected function kuaibao(\app\admin\model\Order $order)
    {
        $user = $order->user;
        $host = "https://kop.kuaidihelp.com/api";
        $headers = [];
        //根据API的要求，定义相对应的Content-Type
        array_push($headers, "Content-Type" . ":" . "application/x-www-form-urlencoded; charset=UTF-8");
        $appId = '107541';
        $method = 'cloud.print.waybill';
        $ts = time();
        $appKey = '27a51dcfd28329d858b13df8dffa0ba7e0f7f7c5';
        $address = explode(' ', $order->receipt_address);
        $address2 = explode(' ', $order->depot->address);
        if (intval($order->plattype) === 1) {
            // cn 打印机
            $agent_id = '3123977140924881';
        } elseif (intval($order->plattype) === 2) {
            // pdd 打印机
            $agent_id = '2895952730374647';
        } else {
            $this->error('平台选择错误');
        }

        $bodys = [
            "app_id" => $appId,
            "method" => $method,
            "sign"   => md5($appId . $method . $ts . $appKey),
            "ts"     => $ts,
            "data"   => json_encode([
                'agent_id'    => $agent_id,
                'template_id' => '666691',
                'print_data'  => [
                    [
                        'tid'        => $order->real_sn,
                        'cp_code'    => 'yt',
                        'sender'     => [
                            'address' => [
                                "province" => $address2[0],
                                "city"     => $address2[1],
                                "district" => $address2[2],
                                "detail"   => $address2[3],
                            ],
                            "name"    => $user->fren ?? $user->nickname,
                            "mobile"  => $user->fhao ?? $user->mobile,
                        ],
                        'recipient'  => [
                            'address' => [
                                "province" => $address[0],
                                "city"     => $address[1],
                                "district" => $address[2],
                                "detail"   => $address[3],
                            ],
                            "name"    => $order->recipient,
                            "mobile"  => $order->receipt_number,
                        ],
                        'goods_name' => $order->item
                    ]
                ]
            ])
        ];
        $bodys = http_build_query($bodys);
        $url = $host;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$" . $host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);
        $info = curl_exec($curl);
        $response = json_decode($info);
        $order->data('uid', $response->uid);
        $order->data('info', $info);
        if ($response->code === 0) {
            $order->data('courier_sn', $response->data->{$order->real_sn}->task_info->waybill_code)->save();
            $user->data('money', $user->money - $order->total)->save();
        } else {
            $order->data('reason', $this->arr_get($this->arr_get(json_decode($info, true), 'data'), 'reason'))->save();
        }
    }

    public function arr_get($arr, $key)
    {
        if (is_array($arr) && isset($arr[$key])) {
            return $arr[$key];
        }
        return false;
    }
}
