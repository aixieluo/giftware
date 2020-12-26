<?php

namespace app\admin\job;

use app\admin\model\Order;
use app\index\controller\traits\KuaiBaoTrait;
use think\queue\Job;

class KuaiBaoJob{
    use KuaiBaoTrait;

    public function fire(Job $job, $data){
        $order = new Order();
        $order->isUpdate(true);
        $order->data($data);
        if ($order->courier_sn) {
            return $job->delete();
        }

        $this->kuaibao($order);
        //....这里执行具体的任务

        if ($job->attempts() > 3) {
            //通过这个方法可以检查这个任务已经重试了几次了
        }


        //如果任务执行成功后 记得删除任务，不然这个任务会重复执行，直到达到最大重试次数后失败后，执行failed方法
        $job->delete();

        // 也可以重新发布这个任务
//        $job->release($delay); //$delay为延迟时间

    }

    public function failed($data){

        // ...任务达到最大重试次数后，失败了
    }

}