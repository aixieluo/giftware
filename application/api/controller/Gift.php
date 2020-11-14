<?php

namespace app\api\controller;

use app\common\controller\Api;

/**
 * 礼品接口
 *
 * @package app\api\controller
 */
class Gift extends Api
{
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
        $this->success('', \app\admin\model\Depot::all());
    }

    /**
     * 礼品下单
     * @ApiMethod(POST)
     * @ApiHeaders(name=token, type=string, required=true, description="请求的Token，注：token值请放在headers")
     */
    public function order()
    {

    }

    /**
     * 获取快递单号
     * @ApiMethod(POST)
     * @ApiHeaders(name=token, type=string, required=true, description="请求的Token，注：token值请放在headers")
     */
    public function courier()
    {

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
