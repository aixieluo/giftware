<?php

namespace app\index\controller;

use app\admin\model\Depot;
use app\admin\model\Order;
use app\common\controller\Frontend;
use think\Config;
use think\Cookie;
use think\Hook;
use think\Request;
use think\Response;

/**
 * 礼品
 */
class Gift extends Frontend
{
    protected $layout = 'default';
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();
        $auth = $this->auth;
        $this->assign('auth', $auth);

        if (! Config::get('fastadmin.usercenter')) {
            $this->error(__('User center already closed'));
        }

        //监听注册登录退出的事件
        Hook::add('user_login_successed',
            function ($user) use ($auth) {
                $expire = input('post.keeplogin') ? 30 * 86400 : 0;
                Cookie::set('uid', $user->id, $expire);
                Cookie::set('token', $auth->getToken(), $expire);
            });
        Hook::add('user_register_successed',
            function ($user) use ($auth) {
                Cookie::set('uid', $user->id);
                Cookie::set('token', $auth->getToken());
            });
        Hook::add('user_delete_successed',
            function ($user) use ($auth) {
                Cookie::delete('uid');
                Cookie::delete('token');
            });
        Hook::add('user_logout_successed',
            function ($user) use ($auth) {
                Cookie::delete('uid');
                Cookie::delete('token');
            });
    }

    public function index(Request $request)
    {
        $id = $request->param('id');
        $depots = Depot::all();
        $gifts = \app\admin\model\Gift::all();
        $this->assign('depots', $depots);
        $this->assign('gifts', $gifts);
        return $this->fetch();
    }

    public function show(Request $request)
    {
        $id = $request->param('id');
        $gift = \app\admin\model\Gift::get($id);
        $this->assign('gift', $gift);
        return $this->fetch();
    }

    public function orders(Request $request)
    {
        $user = $this->auth->getUser();
        if ($request->isAjax()) {
            $orders = Order::where('user_id', $this->auth->getUser()['id'])->paginate($request->get('limit'), false, [
                'page' => $request->get('offset') / $request->get('limit') + 1,
            ]);
            $data['rows'] = $orders->getCollection();
            $data['total'] = $orders->total();
            return Response::create($data, 'json');
        }
        $this->assign('orders', $user->orders);
        $this->assign('auth', $this->auth);
        return $this->fetch();
    }

    public function buy(Request $request)
    {
        $d = Depot::get($request->param('depot_id'));
        $this->assign('depot_id', $request->param('depot_id'));
        $this->assign('depots', Depot::all());
        $this->assign('gifts', $d ? $d->gifts : []);
        return $this->fetch();
    }

    public function items(Request $request)
    {
        if ($request->isAjax()) {
            $list = Depot::get($request->get('depot_id'))->gifts()->paginate($request->get('limit'), false, [
                'page' => $request->get('offset') / $request->get('limit') + 1,
            ]);
            $data['rows'] = $list->getCollection();
            $data['total'] = $list->total();
            return Response::create($data, 'json');
        }

        return $this->fetch();
    }

    protected function tabs(Request $request)
    {
        $depot_id = $request->param('depot_id', $request->get('depot_id'));
        $gift_id = $request->param('gift_id', $request->get('gift_id'));
        $this->assign('depot_id', $depot_id);
        $this->assign('gift_id', $gift_id);
        $gift = \app\admin\model\Gift::get($gift_id);
        $depot = Depot::get($depot_id);
        $this->assign('gift', $gift);
        $this->assign('depot', $depot);
        return [$gift, $depot];
    }

    public function order(Request $request)
    {
        list($gift, $depot) = $this->tabs($request);
        if (!$request->isPost()) {
            return $this->fetch();
        }
        $addresses = $this->addresses($request->post('addstext'));
        foreach ($addresses as $address) {
            $this->storeOrder($this->auth->getUser(), $depot, $gift, $address, $request->post());
        }
        $this->redirect('index/gift/orders');
    }

    public function upload(Request $request)
    {
        list($gift, $depot) = $this->tabs($request);
        return $this->fetch();
    }

    public function typeAuto(Request $request)
    {
        list($gift, $depot) = $this->tabs($request);
        return $this->fetch();
    }

    public function typeOrder(Request $request)
    {
        list($gift, $depot) = $this->tabs($request);
        return $this->fetch();
    }

    protected function storeOrder(\app\common\model\User $user, Depot $depot, \app\admin\model\Gift $gift, $address, array $attr)
    {
        $o['sn'] = date("Ymdhis") . sprintf("%03d", $this->auth->getUser()['id']) . mt_rand(1000, 9999);
        $o['total'] = $gift->price + $depot->price;
        list($o['recipient'], $o['receipt_number'], $o['receipt_address']) = explode(',', $address);
        $o['courier'] = '申通';
        $o['plattype'] = $attr['type'];
        $o['item'] = $gift->name;
        $order = new Order($o);
        $order->user_id = $user->id;
        $order->gift_id = $gift->id;
        $order->depot_id = $depot->id;
        $order->save();
    }

    protected function addresses($addresses)
    {
        return explode("\r\n", $addresses);
    }
}
