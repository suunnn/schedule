<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

function generate_site_code($id)
{
    $base = 10100000;
    if ($id < $base) {
        return $base + $id;
    } else {
        return $id;
    }
}

function generate_factory_code($id) {
    $base = 10100000;
    if ($id < $base) {
        return $base + $id;
    } else {
        return $id;
    }
}

function generate_deliveryman_number($id)
{
    $base = 10000;
    if ($id < $base) {
        return $base + $id;
    } else {
        return $id;
    }
}

function generate_address_number($id)
{
    $base = 10000;
    if ($id < $base) {
        return $base + $id;
    } else {
        return $id;
    }
}

function upload_file($file_type)
{
    $file_type_arr = [
        'image' => 'jpg,png,gif',
        'excel' => 'xls,xlsx'
    ];
    $result['status'] = true;
    if (!array_key_exists($file_type, $file_type_arr)) {
        $result['status'] = false;
        $result['error'] = '文件类型不支持';
        return $result;
    }
    $file = request()->file('file');
    if (!$file) {
        $result['status'] = false;
        $result['error'] = '文件不存在';
        return $result;
    }

    $info = $file->validate(['size' => 2 * 1024 * 1024, 'ext' => $file_type_arr[$file_type]])->move(ROOT_PATH . 'public' . DS . 'uploads');

    if ($info) {
        // 成功上传后 获取上传信息
        $result['data']['save_name'] = $info->getSaveName();
        $result['data']['file_name'] = $info->getInfo('name');
    } else {
        // 上传失败获取错误信息
        $result['status'] = false;
        $result['error'] = $file->getError();
    }

    return $result;

}

/**
 * 检查数组中是否有非true的值
 *
 * @param $rs
 * @return bool
 */
function check_arr($rs)
{
    foreach ($rs as $v) {
        if (!$v) {
            return false;
        }
    }

    return true;
}

function resultArray($array)
{
    if(isset($array['data'])) {
        $array['error'] = '';
        $array['status'] = 0;
    } elseif (isset($array['error'])) {
        $array['status'] = 1;
        $array['data'] = '';
    }

    return ['status' => $array['status'], 'data' => $array['data'], 'error' => $array['error']];
}