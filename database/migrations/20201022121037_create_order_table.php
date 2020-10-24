<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateOrderTable extends Migrator
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
        $table = $this->table('order')->setComment('订单表');
        $table->setId('id');
        $table->addColumn('user_id', 'integer', ['comment' => '创建人']);
        $table->addColumn('sn', 'string', ['comment' => '订单号'])->addIndex('sn');
        $table->addColumn('tb_sn', 'string', ['comment' => '淘宝订单号']);
        $table->addColumn('pdd_sn', 'string', ['comment' => '拼多多订单号']);
        $table->addColumn('type', 'string', ['comment' => '类型']);
        $table->addColumn('courier', 'string', ['comment' => '快递']);
        $table->addColumn('total', 'float', ['comment' => '总价']);
        $table->addColumn('item', 'string', ['comment' => '物品']);
        $table->addColumn('recipient', 'string', ['comment' => '收件人']);
        $table->addColumn('receipt_number', 'string', ['comment' => '收件号码']);
        $table->addColumn('receipt_address', 'string', ['comment' => '收件地址']);
        $table->addTimestamps();
        $table->create();
    }

    public function down()
    {
        $this->dropTable('order');
    }
}
