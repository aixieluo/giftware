<?php

namespace app\admin\model;

use app\common\library\Auth;
use think\Model;


class Depot extends Model
{


    // 表名
    protected $name = 'depot';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'gids',
        'support',
        'code',
        'express',
        'except_province'
    ];

    public function getPriceAttr()
    {
        $user = Auth::instance()->getUser();
        if (! $user) {
            return $this->getData('price');
        }
        $group = $user->group;
        if (! $group) {
            return $this->getData('price');
        }
        return $group->yt;
    }

    public function getGidsAttr()
    {
        return $this->gifts()->column('gift_id');
    }

    public function gifts()
    {
        return $this->belongsToMany(Gift::class, 'gift_depot');
    }

    public function getSupportAttr()
    {
        $str = '支持';
        if ($this->cn) {
            $str .= '菜鸟单号';
        }
        if ($this->pdd) {
            if ($this->cn) {
                $str .= '、';
            }
            $str .= '拼多多单号';
        }
        return $str;
    }

    public function getCodeAttr()
    {
        if ($this->cn && $this->pdd) {
            return 3;
        } elseif ($this->cn) {
            return 2;
        } elseif ($this->pdd) {
            return 1;
        }
    }

    public function getExpressAttr()
    {
        return '圆通';
    }

    public function getExceptProvinceAttr()
    {
        return Order::EXCEPT;
    }
}
