<?php

namespace app\index\controller\traits;
use app\admin\model\Depot;
use app\admin\model\Order;
use function EasyWeChat\Kernel\data_get;

trait OrderTrait
{
    protected function generateOrder($depot, $gift, $list, $arr)
    {
        $os = [];
        foreach ($list as $item) {
            $arr['sn'] = data_get($item, 'sn');
            $order = $this->storeOrder($this->auth->getUser(), $depot, $gift, $item['address'], $arr);
            $os[] = $order;
            kuaibao($this->auth->getUser(), $order);
        }
        return $os;
    }

    protected function storeOrder(
        \app\common\model\User $user,
        Depot $depot,
        \app\admin\model\Gift $gift,
        $address,
        array $attr
    ) {
        $o['sn'] =
            data_get($attr, 'sn')
            ??
            date("Ymdhis") . sprintf("%03d", $this->auth->getUser()['id']) . mt_rand(1000, 9999);
        $o['total'] = $gift->price + $depot->price;
        list($o['recipient'], $o['receipt_number'], $o['receipt_address']) = explode(',', $address);
        $o['courier'] = '圆通';
        $o['plattype'] = $attr['type'];
        $o['item'] = $gift->name;
        $order = new Order($o);
        $order->user_id = $user->id;
        $order->gift_id = $gift->id;
        $order->depot_id = $depot->id;
        $order->save();
        return $order;
    }
}
