<?php

namespace app\index\controller\traits;

use app\admin\model\Order;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

trait TianNiuTrait
{
    protected function tn_post($uri, $data = [])
    {
        $url = 'http://testh1.valueinfos.com/api/'.$uri;
        $client = new Client();
        $res = $client->post($url, [
            RequestOptions::JSON => tianniu_sign($data),
            'headers'            => [
                'Content-Type' => 'application/json',
            ]
        ]);
        return json_decode($res->getBody(), true);
    }

    protected function tn_gifts()
    {
        return $this->tn_post('product/list');
    }

    protected function tn_create($user, Order $order)
    {
        $res = $this->tn_post('order/create', [
            // 礼品id
            'goods_id'         => $order->gift_id,
            // 平台来源 taobao pinduoduo
            'platform'         => $order->plattype == 1 ? 'taobao': 'pinduoduo',
            'recive_user_info' => [
                [
                    // 收货人姓名
                    'uname'    => $order->recipient,
                    // 收货人手机号
                    'mobile'   => $order->receipt_number,
                    // 收件人地址，省、市、区、详细地址用空格隔开
                    'address'  => $order->receipt_address,
                    // 第三方订单号
                    'order_sn' => $order->real_sn,
                    // 不传默认是1，传的话不能大于3
                    'num'      => 1,
                ],
            ],
            'cw_remark'        => '备注',
            // 发货人姓名
            'send_name'        => $user->fren ?? $user->nickname,
            // 发货人手机号
            'send_mobile'      => $user->fhao ?? $user->mobile,
        ]);
        if ($res['code'] == -1) {
            $this->error($res['msg']);
        }
        $order->save([
            'courier'    => $res['data'][$order->real_sn]['kd_company'],
            'courier_sn' => $res['data'][$order->real_sn]['package_sn'],
        ]);
    }
}
