<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateCollectTable extends Migrator
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
        $table = $this->table('collect')->setComment('收藏表');
        $table->setId('id');
        $table->addColumn('gift_id', 'integer', ['comment' => '礼品id']);
        $table->addColumn('user_id', 'integer', ['comment' => '用户id']);
        $table->create();
    }

    public function down()
    {
        $this->dropTable('collect');
    }
}
