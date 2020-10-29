<?php

namespace app\admin\model;

use think\Model;


class Order extends Model
{





    // 表名
    protected $name = 'order';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'plattype_text'
    ];

    public function getPlattypeTextAttr()
    {
        return $this->plattype === 1 ? '菜鸟单号' : ' 拼多多电子';
    }

    public function user()
    {
        return $this->belongsTo(\app\common\model\User::class);
    }

    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    public function gift()
    {
        return $this->belongsTo(Gift::class);
    }
}
