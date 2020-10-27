<?php

use think\migration\Migrator;
use think\migration\db\Column;

class UpdateDepotTable extends Migrator
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
        $this->table('depot')
            ->removeColumn('support')
            ->addColumn('cn', 'integer', ['comment' => '支持菜鸟', 'default' => 1])
            ->addColumn('pdd', 'integer', ['comment' => '支持拼多多', 'default' => 1])->update();
    }

    public function down()
    {
        $this->table('depot')->addColumn('support', 'integer', ['comment' => '支持单号类型'])
            ->removeColumn('cn')
            ->removeColumn('pdd')->update();
    }
}
