<?php

namespace app\index\controller;

use think\Db;

class Admin extends Base
{
    public function index()
    {
        return view();
    }

    public function getList()
    {
        $where = ['is_enabled' => 1];
//        $list = Db::name('admin')->where($where)->paginate(10);
        $list = Db::name('admin')->where($where)->select();
        foreach ($list as $key => $value) {
            $list[$key]['createtime'] = date('Y-m-d H:i:s', $value['createtime']);
            $list[$key]['updatetime'] = date('Y-m-d H:i:s', $value['updatetime']);
        }

//        $this->assign('list', $list);
        return $list;
    }

    public function edit()
    {
        $id = input('param.id');

        if (request()->isPost()) {
            $post = input('post.');

            if (!empty($post['password'])) {
                $post['password'] = sha1(md5($post['password']));
            }

            if (isset($id) && $id) {

                $admin = Db::name('admin')->where(['username' => $post['username'], 'is_enabled' => 1, 'id' => ['neq', $id]])->find();
                if ($admin) {
                    $this->error('管理員名已存在');
                }

                if (empty($post['password'])) {
                    unset($post['password']);
                }

                $post['updatetime'] = time();
                unset($post['id']);
                $result = Db::name('admin')->where(['id' => $id])->update($post);
            } else {

                if (empty($post['password'])) {
                    $this->error('請輸入密碼');
                }

                $admin = Db::name('admin')->where(['username' => $post['username'], 'is_enabled' => 1])->find();
                if ($admin) {
                    $this->error('管理員名已存在');
                }

                $post['createtime'] = $post['updatetime'] = time();
                $result = Db::name('admin')->insertGetId($post);
            }

            if ($result) {
                $this->success('更新成功');
            } else {
                $this->error('更新失败');
            }

        } else {

            if (isset($id) && $id) {
                $data = Db::name('admin')->where(['id' => $id, 'is_enabled' => 1])->find();

                $this->assign('data', $data);
            }

            return view();
        }
    }

    public function delete()
    {
        $ids = input('post.id');
        $id_arr = explode(',', $ids);
        $admin_id = session('admin_id');
        foreach ($id_arr as $key => $value) {
            if ($admin_id == $value) {
                unset($id_arr[$key]);
            }
        }
        $ids = implode(',', $id_arr);
        $result = Db::name('admin')->where(['id' => ['in', $ids]])->update(['is_enabled' => 0]);
        if ($result) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }

    }

    public function password()
    {
        $admin_id = session('admin_id');

        if (request()->isPost()) {

            $post = input('post.');

            $admin = Db::name('admin')->where(['id' => $admin_id, 'password' => sha1(md5($post['old_password']))])->find();
            if (!$admin) {
                $this->error('原密码错误');
            }

            $data['password'] = sha1(md5($post['new_password']));
            $data['updatetime'] = time();

            $result = Db::name('admin')->where(['id' => $admin_id])->update($data);
            if ($result) {
                $this->success('更新成功');
            } else {
                $this->error('更新失败');
            }
        }

        return view();
    }
}