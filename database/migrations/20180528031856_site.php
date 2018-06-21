<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Site extends Migrator
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
        $table = $this->table('site', array('engine' => 'InnoDB', 'comment' => '配送站点表'));
        $table->addColumn('site_code', 'string', ['null' => true, 'comment' => '配送点代码'])
            ->addColumn('zipcode', 'string', ['null' => true, 'comment' => '邮递区码'])
            ->addColumn('site_fullname', 'string', ['comment' => '全称'])
            ->addColumn('site_shortname', 'string', ['comment' => '简称'])
            ->addColumn('linkman', 'string', ['null' => true, 'comment' => '联络人'])
            ->addColumn('address', 'string', ['null' => true, 'comment' => '配送地址'])
            ->addColumn('address1', 'string', ['null' => true, 'comment' => '地址1'])
            ->addColumn('address2', 'string', ['null' => true, 'comment' => '地址2'])
            ->addColumn('telephone', 'string', ['null' => true, 'comment' => '电话'])
            ->addColumn('fax', 'string', ['null' => true, 'comment' => '传真'])
            ->addColumn('area', 'string', ['null' => true, 'comment' => '区域码'])
            ->addColumn('center', 'string', ['null' => true, 'comment' => '物流中心'])
            ->addColumn('remark', 'string', ['null' => true, 'comment' => '备注'])
            ->addColumn('createtime', 'integer', ['null' => true])
            ->addColumn('updatetime', 'integer', ['null' => true])
            ->addColumn('is_enabled', 'integer', array('default' => 1, 'limit' => 1, 'comment' => '0.删除,1.可用'))
            ->addIndex(['is_enabled'])
            ->create();
    }
}
