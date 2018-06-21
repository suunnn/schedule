<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Zipcode extends Migrator
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
        $table = $this->table('zipcode', array('engine' => 'InnoDB', 'comment' => '邮递区号表'));
        $table->addColumn('zipcode', 'string')
            ->addColumn('city', 'string')
            ->addColumn('town', 'string')
            ->addColumn('dc', 'string')
            ->addColumn('area', 'string')
            ->addColumn('is_enabled', 'integer', array('default' => 1, 'limit' => 1, 'comment' => '0.删除,1.可用'))
            ->addIndex(['zipcode', 'is_enabled'])
            ->addIndex(['is_enabled'])
            ->create();
    }
}
