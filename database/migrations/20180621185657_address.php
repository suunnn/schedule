<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Address extends Migrator
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
    public function change()
    {
        $table = $this->table('address', array('engine' => 'InnoDB', 'comment' => '地址表'));
        $table->addColumn('number', 'string', ['comment' => '地址编号'])
            ->addColumn('address', 'string')
            ->addColumn('zipcode', 'string')
            ->addColumn('createtime', 'integer', ['null' => true])
            ->addColumn('updatetime', 'integer', ['null' => true])
            ->addColumn('is_enabled', 'integer', array('default' => 1, 'limit' => 1, 'comment' => '0.删除,1.可用'))
            ->addIndex(['is_enabled'])
            ->addIndex(['number'])
            ->create();
    }
}
