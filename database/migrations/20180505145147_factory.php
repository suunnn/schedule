<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class Factory extends Migrator
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
        // create the table
        $table = $this->table('factory', array('engine' => 'InnoDB', 'comment' => '厂商表'));
        $table->addColumn('code', 'string', array('comment' => '厂商代码'))
            ->addColumn('reg_date', 'integer', array('null' => true, 'comment' => '注册日期'))
            ->addColumn('full_name', 'string', array('comment' => '全称'))
            ->addColumn('short_name', 'string', array('null' => true, 'comment' => '简称'))
            ->addColumn('director', 'string', array('comment' => '负责人'))
            ->addColumn('linkman', 'string', array('comment' => '联系人'))
            ->addColumn('address', 'string', array('null' => true, 'comment' => '地址'))
            ->addColumn('goods_address', 'string', array('null' => true, 'comment' => '提货地址'))
            ->addColumn('invoice_address', 'string', array('null' => true, 'comment' => '发票地址'))
            ->addColumn('number', 'string', array('comment' => '统一编号'))
            ->addColumn('tax_number', 'string', array('null' => true, 'comment' => '税籍编号'))
            ->addColumn('phone_1', 'string', array('comment' => '电话'))
            ->addColumn('phone_2', 'string', array('null' => true, 'comment' => '电话'))
            ->addColumn('fax', 'string', array('null' => true, 'comment' => '传真'))
            ->addColumn('remarks', 'string', array('null' => true, 'comment' => '备注'))
            ->addColumn('createtime', 'integer', ['null' => true, ])
            ->addColumn('updatetime', 'integer', ['null' => true, ])
            ->addColumn('is_enabled', 'integer', array('default' => 1, 'limit' => MysqlAdapter::INT_TINY, 'comment' => '0.删除,1.可用'))
            ->addIndex(['code'])
            ->addIndex(['full_name'])
            ->addIndex(['is_enabled'])
            ->create();
    }
}
