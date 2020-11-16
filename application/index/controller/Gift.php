<?php

namespace app\index\controller;

use app\admin\model\Depot;
use app\admin\model\Order;
use app\common\controller\Frontend;
use app\index\controller\traits\OrderTrait;
use think\Config;
use think\Cookie;
use think\Hook;
use think\Request;
use think\Response;
use think\response\Json;
use function EasyWeChat\Kernel\data_get;

/**
 * 礼品
 */
class Gift extends Frontend
{
    protected $layout = 'default';
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];

    use OrderTrait;

    public function _initialize()
    {
        parent::_initialize();
        $auth = $this->auth;
        $this->assign('auth', $auth);

        if (! Config::get('fastadmin.usercenter')) {
            $this->error(__('User center already closed'));
        }

        //监听注册登录退出的事件
        Hook::add('user_login_successed', function ($user) use ($auth) {
            $expire = input('post.keeplogin') ? 30 * 86400 : 0;
            Cookie::set('uid', $user->id, $expire);
            Cookie::set('token', $auth->getToken(), $expire);
        });
        Hook::add('user_register_successed', function ($user) use ($auth) {
            Cookie::set('uid', $user->id);
            Cookie::set('token', $auth->getToken());
        });
        Hook::add('user_delete_successed', function ($user) use ($auth) {
            Cookie::delete('uid');
            Cookie::delete('token');
        });
        Hook::add('user_logout_successed', function ($user) use ($auth) {
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
            $orders =
                Order::where('user_id', $this->auth->getUser()['id'])
                    ->order('id', 'desc')
                    ->paginate($request->get('limit'), false, [
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
        $type = $request->param('type', $request->get('type'));
        $this->assign('depot_id', $depot_id);
        $this->assign('gift_id', $gift_id);
        $this->assign('type', $type);
        $gift = \app\admin\model\Gift::get($gift_id);
        $depot = Depot::get($depot_id);
        $this->assign('gift', $gift);
        $this->assign('depot', $depot);
        return [$gift, $depot, $type];
    }

    protected function validMoney($total)
    {

    }

    protected function lackMoney()
    {
        return Json::create([
            'msg'  => '余额不足',
            'url'  => '',
            'code' => 400
        ]);
    }

    public function orderSuccess()
    {
        return Json::create([
            'msg'  => '下单完成',
            'url'  => url('index/gift/orders'),
            'code' => 302
        ]);
    }

    public function order(Request $request)
    {
        list($gift, $depot) = $this->tabs($request);
        if (! $request->isPost()) {
            return $this->fetch();
        }
        $addresses = $this->addresses($request->post('addstext'));
        if (count($addresses) * $gift->price > $this->auth->getUser()->money) {
            return $this->lackMoney();
        }
        $this->generateOrder($depot, $gift, $addresses, $request->post());
        return $this->orderSuccess();
    }

    public function upload(Request $request)
    {
        list($gift, $depot) = $this->tabs($request);
        if (! $request->isAjax()) {
            return $this->fetch();
        }
        $addresses = $this->getAddresses($request->post('excel'));
        if (count($addresses) * $gift->price > $this->auth->getUser()->money) {
            return $this->lackMoney();
        }
        $this->generateOrder($depot, $gift, $addresses, $request->post());
        return $this->orderSuccess();
    }

    public function getAddresses($file)
    {
        $file = ROOT_PATH . 'public' . $file;
        $xlsx = \SimpleXLSX::parse($file);
        $header_values = $rows = [];
        foreach ($xlsx->rows() as $k => $r) {
            if ($k === 0) {
                $header_values = $r;
                continue;
            }
            $rows[] = array_combine($header_values, $r);
        }
        $data = [];
        if (isset($rows[0]['省'])) {
            foreach ($rows as $key => $row) {
                $data[$key]['address'] = implode(',', [
                    trim($row['收货人']),
                    trim($row['手机']),
                    implode(' ', [
                        trim($row['省']),
                        trim($row['市']),
                        trim($row['区']),
                        trim($row['街道'])
                    ])
                ]);
                $data[$key]['sn'] = $row['订单号'];
            }
        } else {
            foreach ($rows as $key => $row) {
                $data[$key]['address'] = implode(',', [
                    trim($row['收货人姓名']),
                    trim($row['收货人手机']),
                    trim($row['收货地址']),
                ]);
                $data[$key]['sn'] = $row['订单号'];
            }
        }
        return $data;
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

    protected function addresses($addresses)
    {
        $data = [];
        foreach (explode("\r\n", $addresses) as $address) {
            $data[]['address'] = $address;
        }
        return $data;
    }
}
