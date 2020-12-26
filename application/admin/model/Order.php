<?php

namespace app\admin\model;

use think\Model;
use function EasyWeChat\Kernel\data_get;


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

    protected $field = [
        'id',
        'user_id',
        'sn',
        'tb_sn',
        'pdd_sn',
        'type',
        'courier',
        'total',
        'item',
        'recipient',
        'receipt_number',
        'receipt_address',
        'plattype',
        'depot_id',
        'courier_sn',
        'gift_id',
    ];

    // 追加属性
    protected $append = [
        'plattype_text',
        'real_sn',
    ];

    public function getPlattypeTextAttr()
    {
        return $this->plattype == 1 ? '菜鸟单号' : ' 拼多多电子';
    }

    public function getRealSnAttr()
    {
        $sn = null;
        if ($this->plattype === 1) {
            $sn = data_get($this, 'tb_sn');
        } else {
            $sn = data_get($this, 'pdd_sn');
        }
        return $sn ?? $this->sn;
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
