<?php

namespace app\index\controller;

use think\Db;

class Deliveryman extends Base
{
    public function index()
    {
        return view();
    }

    public function getList()
    {
        $where = ['is_enabled' => 1];
//        $list = Db::name('deliveryman')->where($where)->paginate(10);
        $list = Db::name('deliveryman')->where($where)->select();
        foreach ($list as $key => $value) {
            if ($value['sex'] == 1) {
                $list[$key]['sex'] = lang('MALE');
            } else {
                $list[$key]['sex'] = lang('FEMALE');
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

            $default_date = '0000-00-00';
            if (empty($post['take_office'])) {
                $post['take_office'] = $default_date;
            }
            if (empty($post['become_regular'])) {
                $post['become_regular'] = $default_date;
            }
            if (empty($post['dimission'])) {
                $post['dimission'] = $default_date;
            }
            if (isset($id) && $id) {
                $check = Db::name('deliveryman')->where(['number' => $post['number'], 'is_enabled' => 1, 'id' => ['neq', $post['id']]])->find();
                if ($check) {
                    $this->error('编号已存在!');
                }
                $post['updatetime'] = time();
                unset($post['id']);
                $result = Db::name('deliveryman')->where(['id' => $id])->update($post);
            } else {
                $check = Db::name('deliveryman')->where(['number' => $post['number'], 'is_enabled' => 1])->find();
                if ($check) {
                    $this->error('编号已存在!');
                }
                $post['createtime'] = $post['updatetime'] = time();
                $result = Db::name('deliveryman')->insertGetId($post);
            }

            if ($result) {
                $this->success('更新成功');
            } else {
                $this->error('更新失败');
            }

        } else {

            if (isset($id) && $id) {
                $data = Db::name('deliveryman')->where(['id' => $id, 'is_enabled' => 1])->find();

                $this->assign('data', $data);
            }

            return view();
        }
    }

    public function delete()
    {
        $ids = input('post.id');
        $result = Db::name('deliveryman')->where(['id' => ['in', $ids]])->update(['is_enabled' => 0]);
        if ($result) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }

    }

    public function generateNumber()
    {
        $data = Db::name('deliveryman')->where(['is_enabled' => 1])->max('number');

        return json(resultArray(['data' => generate_deliveryman_number(intval($data) + 1)]));
    }
}