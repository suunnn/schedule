<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Car extends Migrator
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
        $table = $this->table('car', array('engine' => 'InnoDB', 'comment' => '车辆表'));
        $table->addColumn('number', 'string', ['comment' => '车牌号码'])
            ->addColumn('brand', 'string', ['null' => true, 'comment' => '厂牌'])
            ->addColumn('buy_date', 'date', ['null' => true, 'comment' => '购买日期'])
            ->addColumn('check_date', 'date', ['null' => true, 'comment' => '验车日期'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'comment' => '车况,1.正常,2.维修中'])
            ->addColumn('repair_date', 'date', ['null' => true, 'comment' => '维修完成日期'])
            ->addColumn('insurance_type', 'string', ['null' => true, 'comment' => '保险种类,多选,1.甲,2.乙,3.丙,4.意外,5.强制'])
            ->addColumn('insurance_expire', 'date', ['null' => true, 'comment' => '保险到期日'])
            ->addColumn('displacement', 'string', ['null' => true, 'comment' => '排气量'])
            ->addColumn('load_capacity', 'string', ['null' => true, 'comment' => '载重量'])
            ->addColumn('total_weight', 'string', ['null' => true, 'comment' => '总重量'])
            ->addColumn('createtime', 'integer', ['null' => true])
            ->addColumn('updatetime', 'integer', ['null' => true])
            ->addColumn('is_enabled', 'integer', array('default' => 1, 'limit' => 1, 'comment' => '0.删除,1.可用'))
            ->addIndex(['is_enabled'])
            ->create();
    }
}
