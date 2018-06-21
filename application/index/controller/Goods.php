<?php

namespace app\index\controller;

use think\Db;

class Goods extends Base
{
    public function index()
    {
        return view();
    }

    public function getList()
    {
        $where = ['g.is_enabled' => 1];
//        $list = Db::name('goods')->where($where)->paginate(10);
        $list = Db::name('goods')->alias('g')->field("g.*, f.code, f.full_name")->join("factory f", "g.factory_id = f.id")->where($where)->select();

//        $this->assign('list', $list);
        return $list;
    }

    public function edit()
    {
        $id = input('param.id');

        if (request()->isPost()) {
            $post = input('post.');

            if (isset($id) && $id) {
                $where['is_enabled'] = 1;
                $where['factory_id'] = $post['factory_id'];
                $where['goods_code'] = $post['goods_code'];
                $where['id'] = ['neq', $post['id']];
                $check = Db::name('goods')->where($where)->find();
                if ($check) {
                    $this->error('商品代码已存在!');
                }
                $post['updatetime'] = time();
                unset($post['id']);
                $result = Db::name('goods')->where(['id' => $id])->update($post);
            } else {
                $where['is_enabled'] = 1;
                $where['factory_id'] = $post['factory_id'];
                $where['goods_code'] = $post['goods_code'];
                $check = Db::name('goods')->where($where)->find();
                if ($check) {
                    $this->error('商品代码已存在!');
                }
                $post['createtime'] = $post['updatetime'] = time();
                $result = Db::name('goods')->insertGetId($post);
            }

            if ($result) {
                $this->success('更新成功');
            } else {
                $this->error('更新失败');
            }

        } else {

            if (isset($id) && $id) {
                $data = Db::name('goods')->where(['id' => $id, 'is_enabled' => 1])->find();

                $this->assign('data', $data);
            }

            return view();
        }
    }

    public function delete()
    {
        $ids = input('post.id');
        $result = Db::name('goods')->where(['id' => ['in', $ids]])->update(['is_enabled' => 0]);
        if ($result) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }

    }
}