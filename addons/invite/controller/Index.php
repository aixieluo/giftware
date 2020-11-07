<?php

namespace addons\invite\controller;

use think\addons\Controller;
use think\Cookie;

class Index extends Controller
{

    public function index()
    {
        $id = $this->request->param('id/d');
        if ($id) {
            Cookie::set("inviter", $id);
        }
        return $this->view->fetch();
    }
}
