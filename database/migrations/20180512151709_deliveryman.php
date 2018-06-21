<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class Deliveryman extends Migrator
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
        $table = $this->table('deliveryman', array('engine' => 'InnoDB', 'comment' => '配送员表'));
        $table->addColumn('number', 'string', ['comment' => '编号'])
            ->addColumn('username', 'string', ['comment' => '姓名'])
            ->addColumn('sex', 'integer', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '性别'])
            ->addColumn('birthday', 'date', ['null' => true, 'comment' => '生日'])
            ->addColumn('native_place', 'string', ['null' => true, 'comment' => '籍贯'])
            ->addColumn('area', 'string', ['null' => true, 'comment' => '区域'])
            ->addColumn('id_card', 'string', ['null' => true, 'comment' => '身份证号'])
            ->addColumn('probation', 'integer', ['limit' => MysqlAdapter::INT_SMALL, 'null' => true, 'comment' => '试用期长,单位月'])
            ->addColumn('take_office', 'date', ['null' => true, 'default' => '0000-00-00', 'comment' => '到职日期'])
            ->addColumn('become_regular', 'date', ['null' => true, 'comment' => '转正日期'])
            ->addColumn('dimission', 'date', ['null' => true, 'comment' => '离职日期'])
            ->addColumn('email', 'string', ['null' => true, 'comment' => 'email'])
            ->addColumn('address', 'string', ['null' => true, 'comment' => '地址'])
            ->addColumn('telephone', 'string', ['null' => true, 'comment' => '电话'])
            ->addColumn('mobilephone', 'string', ['null' => true, 'comment' => '手机'])
            ->addColumn('createtime', 'integer', ['null' => true])
            ->addColumn('updatetime', 'integer', ['null' => true])
            ->addColumn('is_enabled', 'integer', array('default' => 1, 'limit' => 1, 'comment' => '0.删除,1.可用'))
            ->addIndex(['is_enabled'])
            ->create();
    }
}
