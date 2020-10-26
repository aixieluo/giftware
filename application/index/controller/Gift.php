<?php

namespace app\index\controller;

use app\admin\model\Depot;
use app\common\controller\Frontend;
use think\Config;
use think\Cookie;
use think\Hook;
use think\Request;

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
        $this->assign('orders', $user->orders);
        $this->assign('auth', $this->auth);
        return $this->fetch();
    }

    public function buy()
    {
        return $this->fetch();
    }

    public function order()
    {
        return $this->fetch();
    }

    public function upload()
    {
        return $this->fetch();
    }

    public function typeAuto()
    {
        return $this->fetch();
    }

    public function typeOrder()
    {
        return $this->fetch();
    }
}
