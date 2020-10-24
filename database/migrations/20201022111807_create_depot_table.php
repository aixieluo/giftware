<?php

use think\migration\Migrator;

class CreateDepotTable extends Migrator
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
        $table = $this->table('depot')->setComment('仓库');
        $table->setId('id');
        $table->addColumn('name', 'string', ['comment' => '仓库名称']);
        $table->addColumn('address', 'string', ['comment' => '发货地址']);
        $table->addColumn('note', 'string', ['comment' => '备注']);
        $table->addColumn('support', 'integer', ['comment' => '支持单号类型']);
        $table->addTimestamps();
        $table->create();
    }

    public function down()
    {
        $this->dropTable('depot');
    }
}
