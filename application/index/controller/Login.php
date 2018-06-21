<?php

namespace app\index\controller;

use think\Controller;
use think\Db;

class Login extends Controller
{
    public function index()
    {
        if (request()->isPost()) {

            $post = input('post.');
            if (!captcha_check($post['captcha'])) {
                $this->error('验证码错误');
            }
            $admin = Db::name('admin')->where(['username' => $post['username'], 'password' => sha1(md5($post['password'])), 'is_enabled' => 1])->find();
            if ($admin) {
                session('admin_id', $admin['id']);
                session('admin_name', $admin['username']);
                cookie('think_var', 'zh-tw');
                $this->redirect('index/factory/index');
            } else {
                $this->error('登录失败');
            }
        } else {

            return view();
        }
    }

    public function logout()
    {
        session('admin_id', null);
        $this->redirect('/login');
    }
}