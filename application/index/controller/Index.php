<?php

namespace app\index\controller;

use app\admin\model\Depot;
use app\admin\model\Fmenu;
use app\admin\model\News;
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
        $news1 = News::where('type', 1)->order('createtime', 'desc')->limit(5)->select();
        $news2 = News::where('type', 2)->order('createtime', 'desc')->limit(5)->select();
        $Adszone = new \addons\adszone\library\Adszone();
        $result = $Adszone->getAdsByMark('banner');
        $this->assign('w', $result['width']);
        $this->assign('h', $result['height']);
        $this->assign('ads', $result['data']);
        $this->assign('depots', $depots);
        $this->assign('gifts', $gifts);
        $this->assign('news1', $news1);
        $this->assign('news2', $news2);
        return $this->fetch();
    }

}
