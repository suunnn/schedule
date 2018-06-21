<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Factory20180612 extends Migrator
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
        $table = $this->table('factory');
        $table->removeColumn('tax_number')
            ->addColumn('email', 'string', array('null' => true, 'comment' => '联系人邮箱'))
            ->addColumn('address_zipcode', 'string', array('null' => true, 'comment' => '地址邮编'))
            ->addColumn('goods_address_zipcode', 'string', array('null' => true, 'comment' => '提货地址邮编'))
            ->addColumn('invoice_address_zipcode', 'string', array('null' => true, 'comment' => '发票地址邮编'))
            ->save();
    }

    public function down()
    {
        $table = $this->table('factory');
        $table->addColumn('tax_number', 'string', array('null' => true, 'comment' => '税籍编号'))
            ->removeColumn('email')
            ->removeColumn('address_zipcode')
            ->removeColumn('goods_address_zipcode')
            ->removeColumn('invoice_address_zipcode')
            ->save();
    }
}
