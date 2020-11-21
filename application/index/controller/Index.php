<?php

namespace app\index\controller;

use app\admin\model\Depot;
use app\common\controller\Frontend;
use think\Request;

class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = 'default';

    public function index(Request $request)
    {
        $depots = Depot::all();
        $gifts = \app\admin\model\Gift::all();
        $this->assign('depots', $depots);
        $this->assign('gifts', $gifts);
        return $this->fetch();
    }

}
