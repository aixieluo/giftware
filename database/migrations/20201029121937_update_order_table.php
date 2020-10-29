<?php

use think\migration\Migrator;

class UpdateOrderTable extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->table('order')->addColumn('plattype', 'string', ['comment' => '类型'])->removeColumn('type')
            ->addColumn('depot_id', 'integer')
            ->changeColumn('item', 'string', ['comment' => '物品', 'null' => true])
            ->changeColumn('pdd_sn', 'string', ['comment' => '拼多多订单号', 'null' => true])
            ->changeColumn('tb_sn', 'string', ['comment' => '淘宝订单号', 'null' => true])
            ->addColumn('courier_sn', 'string', ['comment' => '快递单号', 'null' => true])
            ->changeColumn('courier', 'string', ['comment' => '快递', 'null' => true])
            ->addColumn('gift_id', 'integer')->update();
    }

    public function down()
    {
        $this->table('order')->removeColumn('plattype')->addColumn('type', 'string', ['comment' => '类型'])
            ->removeColumn('depot_id')
            ->removeColumn('courier_sn')
            ->removeColumn('gift_id')->update();
    }
}
