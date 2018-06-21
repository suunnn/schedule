<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Schedule extends Migrator
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
        $table = $this->table('schedule', array('engine' => 'InnoDB', 'comment' => '排班'));
        $table->addColumn('site_id', 'integer')
            ->addColumn('site_code', 'string')
            ->addColumn('site_name', 'string')
            ->addColumn('address', 'string')
            ->addColumn('factory_id', 'integer')
            ->addColumn('factory_code', 'string')
            ->addColumn('factory_name', 'string')
            ->addColumn('deliveryman_id', 'integer', ['null' => true])
            ->addColumn('deliveryman', 'string', ['null' => true])
            ->addColumn('car_id', 'integer', ['null' => true])
            ->addColumn('car_number', 'string', ['null' => true])
            ->addColumn('shipment_num', 'integer', ['null' => true])
            ->addColumn('document_total_big', 'integer', ['null' => true])
            ->addColumn('document_total_small', 'integer', ['null' => true])
            ->addColumn('distribution_date', 'integer', ['null' => true])
            ->addColumn('shipment_code', 'string', ['null' => true])
            ->addColumn('original_distribution_date', 'integer', ['null' => true])
            ->addColumn('number', 'integer', ['null' => true])
            ->addColumn('zipcode_area', 'string', ['null' => true])
            ->addColumn('volume', 'string', ['null' => true])
            ->addColumn('weight', 'string', ['null' => true])
            ->addColumn('remarks', 'string', ['null' => true])
            ->addColumn('createtime', 'integer', ['null' => true])
            ->addColumn('updatetime', 'integer', ['null' => true])
            ->addColumn('is_enabled', 'integer', ['default' => 1, 'limit' => 1, 'comment' => '0.删除,1.可用'])
            ->addIndex(['site_code'])
            ->addIndex(['is_enabled'])
            ->create();

    }
}
