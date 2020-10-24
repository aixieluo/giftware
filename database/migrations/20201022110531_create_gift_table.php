<?php

use think\migration\Migrator;

class CreateGiftTable extends Migrator
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
        $table = $this->table('gift')->setComment('礼品表');
        $table->setId('id');
        $table->addColumn('name', 'string', ['comment' => '礼品名']);
        $table->addColumn('price', 'float', ['comment' => '金额']);
        $table->addColumn('weight', 'float', ['comment' => '重量']);
        $table->addColumn('image', 'string', ['comment' => '图片']);
        $table->addTimestamps();
        $table->create();
    }

    public function down()
    {
        $this->dropTable('gift');
    }
}
