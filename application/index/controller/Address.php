<?php

namespace app\index\controller;

use think\Db;

class Address extends Base
{
    public function index()
    {
        return view();
    }

    public function getList()
    {
        $where = ['is_enabled' => 1];
//        $list = Db::name('address')->where($where)->paginate(10);
        $list = Db::name('address')->where($where)->select();

//        $this->assign('list', $list);
        return $list;
    }

    public function add()
    {
        if (request()->isPost()) {

            $post = input('post.');

            $check = Db::name('address')->where(['number' => $post['number'], 'is_enabled' => 1])->find();
            if ($check) {
                $this->error('地址號碼已存在!');
            }

            $post['createtime'] = $post['updatetime'] = time();
            $result = Db::name('address')->insertGetId($post);


            if ($result) {
                $this->success('更新成功');
            } else {
                $this->error('更新失败');
            }
        }

        return view();
    }

    public function edit()
    {
        $id = input('param.id');

        if (request()->isPost()) {
            $post = input('post.');

            if (isset($id) && $id) {
                $check = Db::name('address')->where(['number' => $post['number'], 'is_enabled' => 1, 'id' => ['neq', $post['id']]])->find();
                if ($check) {
                    $this->error('地址號碼已存在!');
                }

                $post['updatetime'] = time();
                unset($post['id']);
                $result = Db::name('address')->where(['id' => $id])->update($post);
            } else {
                $check = Db::name('address')->where(['number' => $post['number'], 'is_enabled' => 1])->find();
                if ($check) {
                    $this->error('地址號碼已存在!');
                }

                $post['createtime'] = $post['updatetime'] = time();
                $result = Db::name('address')->insertGetId($post);
            }

            if ($result) {
                $this->success('更新成功');
            } else {
                $this->error('更新失败');
            }

        } else {

            if (isset($id) && $id) {
                $data = Db::name('address')->where(['id' => $id, 'is_enabled' => 1])->find();

                $this->assign('data', $data);
            }

            return view();
        }
    }

    public function delete()
    {
        $ids = input('post.id');
        $result = Db::name('address')->where(['id' => ['in', $ids]])->update(['is_enabled' => 0]);
        if ($result) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }

    }

    public function generateNumber()
    {
        $data = Db::name('address')->where(['is_enabled' => 1])->max('number');

        return json(resultArray(['data' => generate_address_number(intval($data) + 1)]));
    }

    public function getAddress()
    {
        $param = input('param.');
        $where['is_enabled'] = 1;
        $where['number'] = $param['number'];

        $data = Db::name('address')->where($where)->find();
        if (!$data) {
            return json(resultArray(['error' => '地址不存在']));
        }

        return json(resultArray(['data' => $data]));
    }
}