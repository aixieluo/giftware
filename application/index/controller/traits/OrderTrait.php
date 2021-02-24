<?php

namespace app\index\controller\traits;
use app\admin\job\KuaiBaoJob;
use app\admin\model\Depot;
use app\admin\model\Order;
use think\Queue;
use function EasyWeChat\Kernel\data_get;

trait OrderTrait
{
    protected function validAddress($list)
    {
        foreach ($list as $item) {
            $address = $item['address'];
            $p = $this->arr_get(explode(',', $address), 2);
            $p = $this->arr_get(explode(' ', trim($p)), 0);
            $except = [
                '海南',
                '新疆',
                '西藏',
            ];
            foreach ($except as $addr) {
                if (mb_strpos($p, $addr) !== false) {
                    $this->error('很抱歉！由于海南，新疆，西藏运费偏高，暂不能发货，请删掉此地址即可发货！');
                }
            }
        }
    }

    protected function generateOrder($depot, $gift, $list, $arr)
    {
        $this->validAddress($list);
        $os = [];
        foreach ($list as $item) {
            $arr['sn'] = data_get($item, 'sn');
            $order = $this->storeOrder($this->auth->getUser(), $depot, $gift, $item['address'], $arr);
            $os[] = $order;
            // 如要只生成订单不打单，注释下面4行代码
            if ($depot->tianniu) {
                $this->tn_create($this->auth->getUser(), $order);
            } else {
                Queue::push(KuaiBaoJob::class, $order);
//                $this->kuaibao($order);
            }
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

    public function arr_get($array, $key, $default = null)
    {
        if (! is_array($array)) {
            return $default;
        }
        if (is_null($key)) {
            return $array;
        }
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }
        return $array;
    }
}
