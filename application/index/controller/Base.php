<?php

namespace app\index\controller;

use think\Controller;
use think\Lang;

class Base extends Controller
{
    protected function _initialize()
    {
        parent::_initialize();

        $now_lang = $this->getSetLang();
        $this->assign('set_lang', $now_lang);

        $user_id = session('admin_id');
        if (empty($user_id)) {
            $this->redirect('/login');
        }

    }

    //暂时只做中英文切换
    public function getSetLang()
    {
        $lang = Lang::detect();
        if($lang == 'zh-tw') {
            return 'zh-cn';
        }
        return 'zh-tw';
    }
}