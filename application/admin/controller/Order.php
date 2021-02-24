<?php

namespace app\admin\controller;

use app\admin\job\KuaiBaoJob;
use app\admin\model\Order as OrderAlias;
use app\common\controller\Backend;
use app\index\controller\traits\KuaiBaoTrait;
use think\Db;
use think\Queue;

/**
 * 订单管理
 *
 * @icon fa fa-circle-o
 */
class Order extends Backend
{

    use KuaiBaoTrait;

    /**
     * Order模型对象
     *
     * @var OrderAlias
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new OrderAlias;

    }

    public function import()
    {
        parent::import();
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function dadan()
    {
        $orders = OrderAlias::whereNull('courier_sn')->select();
        foreach ($orders as $order) {
            Queue::push(KuaiBaoJob::class, $order);
        }
        $this->success('OK');
    }
}
