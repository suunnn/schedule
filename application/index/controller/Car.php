<?php

namespace app\index\controller;

use think\Db;

class Car extends Base
{
    public function index()
    {
        return view();
    }

    public function getList()
    {
        $where = ['is_enabled' => 1];
//        $list = Db::name('car')->where($where)->paginate(10);
        $list = Db::name('car')->where($where)->select();
        foreach ($list as $key => $value) {
            if ($value['status'] == 1) {
                $list[$key]['status'] = '正常';
            } else {
                $list[$key]['status'] = '維修中';
            }
        }

//        $this->assign('list', $list);
        return $list;
    }

    public function edit()
    {
        $id = input('param.id');

        if (request()->isPost()) {
            $post = input('post.');
            if (!empty($post['insurance_type'])) {
                $post['insurance_type'] = json_encode($post['insurance_type']);
            }

            $default_date = '0000-00-00';
            if (empty($post['buy_date'])) {
                $post['buy_date'] = $default_date;
            }
            if (empty($post['check_date'])) {
                $post['check_date'] = $default_date;
            }
            if (empty($post['repair_date'])) {
                $post['repair_date'] = $default_date;
            }
            if (empty($post['insurance_expire'])) {
                $post['insurance_expire'] = $default_date;
            }
            if (isset($id) && $id) {
                $check = Db::name('car')->where(['number' => $post['number'], 'is_enabled' => 1, 'id' => ['neq', $post['id']]])->find();
                if ($check) {
                    $this->error('車牌號碼已存在!');
                }

                $post['updatetime'] = time();
                unset($post['id']);
                $result = Db::name('car')->where(['id' => $id])->update($post);
            } else {
                $check = Db::name('car')->where(['number' => $post['number'], 'is_enabled' => 1])->find();
                if ($check) {
                    $this->error('車牌號碼已存在!');
                }

                $post['createtime'] = $post['updatetime'] = time();
                $result = Db::name('car')->insertGetId($post);
            }

            if ($result) {
                $this->success('更新成功');
            } else {
                $this->error('更新失败');
            }

        } else {

            if (isset($id) && $id) {
                $data = Db::name('car')->where(['id' => $id, 'is_enabled' => 1])->find();

                $this->assign('data', $data);
            }

            return view();
        }
    }

    public function delete()
    {
        $ids = input('post.id');
        $result = Db::name('car')->where(['id' => ['in', $ids]])->update(['is_enabled' => 0]);
        if ($result) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }

    }
}