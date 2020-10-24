<?php

use think\migration\Migrator;
use think\migration\db\Column;

class ChangeUserProfileTable extends Migrator
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
        $this->table('user')
            ->addColumn('fren', 'string', ['comment' => '发件人姓名', 'null' => true])
            ->addColumn('fhao', 'string', ['comment' => '发件人号码', 'null' => true])->update();
    }

    public function down()
    {
        $this->table('user')->removeColumn('fren')->removeColumn('fhao');
    }
}
