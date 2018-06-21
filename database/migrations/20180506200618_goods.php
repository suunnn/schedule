<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class Goods extends Migrator
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
        $table = $this->table('goods', array('engine' => 'InnoDB', 'comment' => '产品表'));
        $table->addColumn('factory_id', 'integer', array('comment' => '厂商id'))
            ->addColumn('goods_code', 'string', array('comment' => '产品代码'))
            ->addColumn('barcode', 'string', array('comment' => '产品条码'))
            ->addColumn('safe_num', 'integer', array('comment' => '安全存量', 'null' => true))
            ->addColumn('goods_name', 'string', array('comment' => '产品名称'))
            ->addColumn('specification', 'string', array('comment' => '产品规格', 'null' => true))
            ->addColumn('volume', 'decimal', array('precision' => 8, 'scale' => 4, 'comment' => '产品体积', 'null' => true))
            ->addColumn('weight', 'decimal', array('precision' => 8, 'scale' => 4, 'comment' => '产品重量', 'null' => true))
            ->addColumn('small_unit', 'string', array('comment' => '小单位', 'null' => true))
            ->addColumn('small_unit_transition', 'integer', array('comment' => '小单位转换量', 'null' => true))
            ->addColumn('medium_unit', 'string', array('comment' => '中单位', 'null' => true))
            ->addColumn('medium_unit_transition', 'integer', array('comment' => '中单位转换量', 'null' => true))
            ->addColumn('big_unit', 'string', array('comment' => '大单位', 'null' => true))
            ->addColumn('remarks', 'string', array('comment' => '备注', 'null' => true))
            ->addColumn('createtime', 'integer', ['null' => true, ])
            ->addColumn('updatetime', 'integer', ['null' => true, ])
            ->addColumn('is_enabled', 'integer', array('default' => 1, 'limit' => MysqlAdapter::INT_TINY, 'comment' => '0.删除,1.可用'))
            ->addIndex(['factory_id'])
            ->addIndex(['goods_code'])
            ->addIndex(['is_enabled'])
            ->create();



    }
}
