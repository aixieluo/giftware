<?php

namespace app\api\controller;

use app\admin\model\Depot;
use app\admin\model\Order;
use app\common\controller\Api;
use app\index\controller\traits\KuaiBaoTrait;
use app\index\controller\traits\OrderTrait;
use think\Request;

/**
 * 礼品接口
 *
 * @package app\api\controller
 */
class Gift extends Api
{
    use OrderTrait, KuaiBaoTrait;

    protected $noNeedRight = ['*'];

    /**
     * 礼品列表
     * @ApiMethod(POST)
     * @ApiHeaders(name=token, type=string, required=true, description="请求的Token，注：token值请放在headers")
     */
    public function gifts()
    {
        $this->success('', \app\admin\model\Gift::all());
    }

    /**
     * 仓库列表
     * @ApiMethod(POST)
     * @ApiHeaders(name=token, type=string, required=true, description="请求的Token，注：token值请放在headers")
     */
    public function depots()
    {
        $depots = \app\admin\model\Depot::all();
        $this->success('', $depots);
    }

    /**
     * 礼品下单
     * @ApiMethod(POST)
     * @ApiHeaders(name=token, type=string, required=true, description="请求的Token，注：token值请放在headers")
     * @ApiParams(name=sn, type=string, required=true, description="订单号，不可重复。")
     * @ApiParams(name=gift_id, type=int, required=true, description="礼品编号，请在礼品列表接口获得具体编号")
     * @ApiParams(name=depot_id, type=int, required=true, description="仓库编号，请在仓库列表接口获得具体编号")
     * @ApiParams(name=type, type=int, required=true, description="单号类型 默认 1，1是菜鸟，2是拼多多（请注意识别仓库支持单号类型）")
     * @ApiParams(name=recipient, type=int, required=true, description="收货人")
     * @ApiParams(name=receipt_number, type=string, required=true, description="收货人号码")
     * @ApiParams(name=receipt_province, type=string, required=true, description="收货地址所在省")
     * @ApiParams(name=receipt_city, type=string, required=true, description="收货地址所在市")
     * @ApiParams(name=receipt_district, type=string, required=true, description="收货地址所在区")
     * @ApiParams(name=receipt_address, type=string, required=true, description="收货地址")
     * @ApiParams(name=sendname, type=string, required=true, description="发件人")
     * @ApiParams(name=sendphone, type=string, required=true, description="发件号码")
     */
    public function order(Request $request)
    {
        $msg = $this->validate($request->post(), [
            'sn'               => 'require',
            'gift_id'          => 'require',
            'depot_id'         => 'require',
            'type'             => 'require',
            'recipient'        => 'require',
            'receipt_number'   => 'require',
            'receipt_province' => 'require',
            'receipt_city'     => 'require',
            'receipt_district' => 'require',
            'receipt_address'  => 'require',
            'sendname'         => 'require',
            'sendphone'        => 'require',
        ]);
        if ($msg !== true) {
            $this->error($msg);
        }
        foreach (Order::EXCEPT as $addr) {
            if (mb_strpos($request->post('receipt_province'), $addr) !== false) {
                $this->error('很抱歉！由于海南，新疆，西藏运费偏高，暂不能发货！');
            }
        }
        $gift = \app\admin\model\Gift::find($request->post('gift_id', 0));
        if (! $gift) {
            $this->error('错误的礼品id');
        }
        $plattype = intval($request->post('type', 1)) === 1 ? 'cn' : 'pdd';
        $depot = Depot::where($plattype, 1)->find($request->post('depot_id', 0));
        if (! $depot) {
            $this->error('错误的仓库id或者不支持单号类型');
        }
        if ($o = Order::where('sn', $request->post('sn'))->find()) {
            $this->error('订单号已存在', $o);
        }
        $this->auth->getUser()->fren = $request->post('sendname');
        $this->auth->getUser()->fhao = $request->post('sendphone');
        $recipient = trim($request->post('recipient'));
        $receipt_number = trim($request->post('receipt_number'));
        $receipt_province = trim($request->post('receipt_province'));
        $receipt_city = trim($request->post('receipt_city'));
        $receipt_district = trim($request->post('receipt_district'));
        $receipt_address = trim($request->post('receipt_address'));
        $orders = $this->generateOrder2($depot, $gift, [
            [
                'sn'      => $request->post('sn'),
                'address' => "{$recipient},{$receipt_number},{$receipt_province} {$receipt_city} {$receipt_district} {$receipt_address}"
            ]
        ], $request->post());
        $order = reset($orders);

        if ($order instanceof Order) {
            $order->hidden(['depot', 'user']);
        }

        $this->success('', $order);
    }

    /**
     * 获取快递单号
     * @ApiMethod(POST)
     * @ApiHeaders(name=token, type=string, required=true, description="请求的Token，注：token值请放在headers")
     * @ApiParams(name=sn,type=string,required=true,description="订单号")
     */
    public function courier(Request $request)
    {
        $order = Order::get(['sn' => $request->post('sn')]);
        if (! $order) {
            $this->error('无此订单');
        }
        $order = array_intersect_key($order->getData(), array_flip([
            'id',
            'sn',
            'courier',
            'recipient',
            'receipt_number',
            'receipt_address',
            'create_time',
            'courier_sn',
            'reason',
        ]));
        $this->success('', $order);
    }

    /**
     * 查询余额
     * @ApiMethod(POST)
     * @ApiHeaders(name=token, type=string, required=true, description="请求的Token，注：token值请放在headers")
     */
    public function getMoney()
    {
        $this->success('', ['money' => $this->auth->getUser()->money]);
    }
}
