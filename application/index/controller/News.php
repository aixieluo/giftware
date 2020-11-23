<?php

namespace app\index\controller;

use app\admin\model\News as NewsModel;
use app\common\controller\Frontend;
use think\Request;

class News extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = 'default';

    public function index(Request $request)
    {
        $type = $request->get('type', 1);
        $news = NewsModel::where('type', $type)->order('createtime', 'desc')->paginate(10);
        $this->assign('news', $news);
        $this->assign('title', $type == 1 ? '网站公告' : '帮助说明');
        return $this->fetch();
    }

    public function show(Request $request)
    {
        $news = NewsModel::get($request->get('id'));
        $type = $news->type;
        $this->assign('news', $news);
        $this->assign('title', $type == 1 ? '网站公告' : '帮助说明');
        $this->assign('type', $type);
        return $this->fetch();
    }
}
