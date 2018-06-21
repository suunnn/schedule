<?php

use think\migration\Migrator;
use think\migration\db\Column;

class FileSystem extends Migrator
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
        $table = $this->table('file_system', array('engine' => 'InnoDB', 'comment' => '文件系统'));
        $table->addColumn('filename', 'string', ['comment' => '原文件名'])
            ->addColumn('pathname', 'string', ['comment' => '路径名'])
            ->addColumn('type', 'integer', ['limit' => 1, 'comment' => '文件类型:1.拣货excel'])
            ->addColumn('createtime', 'integer', ['null' => true])
            ->addIndex(['type'])
            ->create();
    }
}
