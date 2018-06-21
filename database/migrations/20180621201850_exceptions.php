<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class Exceptions extends Migrator
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
        $table = $this->table('exceptions', array('engine' => 'InnoDB', 'comment' => '异常处理表'));
        $table->addColumn('site_id', 'integer')
            ->addColumn('site_code', 'string')
            ->addColumn('site_name', 'string')
            ->addColumn('factory_id', 'integer')
            ->addColumn('factory_code', 'string')
            ->addColumn('factory_name', 'string')
            ->addColumn('shipment_number', 'string')
            ->addColumn('shipment_date', 'integer')
            ->addColumn('deliveryman', 'string')
            ->addColumn('delivery_date', 'integer')
            ->addColumn('exception_type', 'integer')
            ->addColumn('desc', 'string', ['null' => true])
            ->addColumn('rejection_number', 'integer', ['null' => true, 'default' => 0])
            ->addColumn('remark', 'string', ['null' => true])
            ->addColumn('status', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '处理状态:1.处理完毕'])
            ->addColumn('solution', 'string', ['null' => true])
            ->addColumn('createtime', 'integer', ['null' => true, ])
            ->addColumn('updatetime', 'integer', ['null' => true, ])
            ->addColumn('is_enabled', 'integer', array('default' => 1, 'limit' => MysqlAdapter::INT_TINY, 'comment' => '0.删除,1.可用'))
            ->addIndex(['site_code'])
            ->addIndex(['factory_code'])
            ->addIndex(['is_enabled'])
            ->create();

    }
}
