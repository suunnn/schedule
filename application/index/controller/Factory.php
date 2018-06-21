<?php

namespace app\index\controller;

use think\Db;

class Factory extends Base
{
    public function index()
    {
        return view();
    }

    public function getList()
    {
        $where = ['is_enabled' => 1];
//        $list = Db::name('factory')->where($where)->paginate(10);
        $list = Db::name('factory')->where($where)->select();

//        $this->assign('list', $list);
        return $list;
    }

    public function edit()
    {
        $id = input('param.id');

        if (request()->isPost()) {
            $post = input('post.');
            if (isset($post['reg_date']) && $post['reg_date']) {
                $post['reg_date'] = strtotime($post['reg_date']);
            }

            if (isset($id) && $id) {

                $check = Db::name('factory')->where(['code' => $post['code'], 'is_enabled' => 1, 'id' => ['neq', $post['id']]])->find();
                if ($check) {
                    $this->error('厂商代码已存在!');
                }
                $post['updatetime'] = time();
                unset($post['id']);
                $result = Db::name('factory')->where(['id' => $id])->update($post);
            } else {
                $check = Db::name('factory')->where(['code' => $post['code'], 'is_enabled' => 1])->find();
                if ($check) {
                    $this->error('厂商代码已存在!');
                }
                $post['createtime'] = $post['updatetime'] = time();
                $result = Db::name('factory')->insertGetId($post);
            }

            if ($result) {
                $this->success('更新成功');
            } else {
                $this->error('更新失败');
            }

        } else {

            if (isset($id) && $id) {
                $data = Db::name('factory')->where(['id' => $id, 'is_enabled' => 1])->find();
                if ($data) {
                    $data['reg_date'] = date('Y-m-d', $data['reg_date']);
                }
                $this->assign('data', $data);
            }

            return view();
        }
    }

    public function delete()
    {
        $ids = input('post.id');
        $result = Db::name('factory')->where(['id' => ['in', $ids]])->update(['is_enabled' => 0]);
        if ($result) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }

    }

    public function getFactory()
    {
        $param = input('get.');
        $where['is_enabled'] = 1;
        $where['code'] = $param['code'];
        $data = Db::name('factory')->where($where)->find();
        if (!$data) {
            return json(resultArray(['error' => lang('FACTORY_NOT_EXIST')]));
        }

        return json(resultArray(['data' => $data]));
    }

    public function generateCode()
    {
        $data = Db::name('factory')->where(['is_enabled' => 1])->max('code');

        return json(resultArray(['data' => generate_factory_code(intval($data) + 1)]));
    }
}