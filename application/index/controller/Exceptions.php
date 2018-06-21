<?php

namespace app\index\controller;

use think\Db;

class Exceptions extends Base
{
    public function index()
    {
        return view();
    }

    public function getList()
    {
        $where = ['is_enabled' => 1];
//        $list = Db::name('exceptions')->where($where)->paginate(10);
        $list = Db::name('exceptions')->where($where)->select();
        foreach ($list as $key => $value) {
            $list[$key]['shipment_date'] = date('Y-m-d' , $value['shipment_date']);
            $list[$key]['delivery_date'] = date('Y-m-d' , $value['delivery_date']);
        }

//        $this->assign('list', $list);
        return $list;
    }

    public function edit()
    {
        $id = input('param.id');

        if (request()->isPost()) {
            $post = input('post.');

            $post['shipment_date'] = !empty($post['shipment_date']) ? strtotime($post['shipment_date']) : 0;
            $post['delivery_date'] = !empty($post['delivery_date']) ? strtotime($post['delivery_date']) : 0;
            $post['status'] = isset($post['status']) ? $post['status'] : 0;

            if (isset($id) && $id) {

                $post['updatetime'] = time();
                unset($post['id']);
                $result = Db::name('exceptions')->where(['id' => $id])->update($post);
            } else {
                $post['createtime'] = $post['updatetime'] = time();
                $result = Db::name('exceptions')->insertGetId($post);
            }

            if ($result) {
                $this->success('更新成功');
            } else {
                $this->error('更新失败');
            }

        } else {

            if (isset($id) && $id) {
                $data = Db::name('exceptions')->where(['id' => $id, 'is_enabled' => 1])->find();
                if (!empty($data)) {
                    $data['shipment_date'] = $data['shipment_date'] > 0 ? date('Y-m-d', $data['shipment_date']) : '';
                    $data['delivery_date'] = $data['delivery_date'] > 0 ? date('Y-m-d', $data['delivery_date']) : '';
                }

                $this->assign('data', $data);
            }

            return view();
        }
    }

    public function delete()
    {
        $ids = input('post.id');
        $result = Db::name('exceptions')->where(['id' => ['in', $ids]])->update(['is_enabled' => 0]);
        if ($result) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }

    }

    public function getExceptionType()
    {
        $data = [
            ['id' => 1, 'name' => '貨錯誤拒收'],
            ['id' => 2, 'name' => '保存期限不符拒收'],
            ['id' => 3, 'name' => '現場無訂單'],
            ['id' => 4, 'name' => '金額不符拒收發票'],
            ['id' => 5, 'name' => '搭贈不符'],
            ['id' => 6, 'name' => '已取消訂單'],
            ['id' => 7, 'name' => '超出交貨期限'],
            ['id' => 8, 'name' => '條碼錯誤'],
            ['id' => 9, 'name' => '電腦當機押單'],
            ['id' => 10, 'name' => '客戶不在'],
            ['id' => 11, 'name' => '與訂單不符拒收'],
            ['id' => 12, 'name' => '地址錯誤'],
            ['id' => 13, 'name' => '重複訂單拒收'],
            ['id' => 14, 'name' => '庫存尚有拒收'],
            ['id' => 15, 'name' => '其他原因'],
        ];

        return json(resultArray(['data' => $data]));
    }
}