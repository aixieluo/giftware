<?php

namespace app\admin\job;

use app\admin\model\Order;
use app\index\controller\traits\KuaiBaoTrait;
use think\Log;
use think\queue\Job;

class KuaiBaoJob{
    use KuaiBaoTrait;

    public function fire(Job $job, $data){
        try {
            $id = isset($data['id']) ? $data['id'] : null;
            $order = Order::find($id);
            //        $order = new Order();
            //        $order->isUpdate(true);
            //        $order->data($data);
            if (isset($order->courier_sn) && $order->courier_sn) {
                return $job->delete();
            }

            $this->kuaibao($order);
            //....这里执行具体的任务

            if ($job->attempts() > 10) {
                return $job->delete();
                //通过这个方法可以检查这个任务已经重试了几次了
            }
        } catch (\Throwable $exception) {
            Log::info($exception->getMessage());
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
