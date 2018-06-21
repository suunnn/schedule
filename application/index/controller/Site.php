<?php

namespace app\index\controller;

use PhpOffice\PhpSpreadsheet\IOFactory;
use think\Db;

class Site extends Base
{
    public function index()
    {
        return view();
    }

    public function getList()
    {
        $where = ['is_enabled' => 1];
//        $list = Db::name('site')->where($where)->paginate(10);
        $list = Db::name('site')->where($where)->select();

//        $this->assign('list', $list);
        return $list;
    }

    public function edit()
    {
        $id = input('param.id');

        if (request()->isPost()) {
            $post = input('post.');

            Db::startTrans();

            if (isset($id) && $id) {
                $check = Db::name('site')->where(['site_code' => $post['site_code'], 'is_enabled' => 1, 'id' => ['neq', $id]])->find();
                if ($check) {
                    $this->error(lang('SITE_CODE_EXIST'));
                }

                $post['updatetime'] = time();
                unset($post['id']);
                $result = Db::name('site')->where(['id' => $id])->update($post);
            } else {
                $check = Db::name('site')->where(['site_code' => $post['site_code'], 'is_enabled' => 1])->find();
                if ($check) {
                    $this->error(lang('SITE_CODE_EXIST'));
                }
                $post['createtime'] = $post['updatetime'] = time();
                $result = Db::name('site')->insertGetId($post);
//                $result = Db::name('site')->where(['id' => $id])->update(['site_code' => generate_site_code($id)]);
            }

            if ($result) {
                Db::commit();
                $this->success('更新成功');
            } else {
                Db::rollback();
                $this->error('更新失败');
            }

        } else {

            if (isset($id) && $id) {
                $data = Db::name('site')->where(['id' => $id, 'is_enabled' => 1])->find();

                $this->assign('data', $data);
            }

            return view();
        }
    }

    public function delete()
    {
        $ids = input('post.id');
        $result = Db::name('site')->where(['id' => ['in', $ids]])->update(['is_enabled' => 0]);
        if ($result) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }

    }

    public function generateCode()
    {
        $data = Db::name('site')->where(['is_enabled' => 1])->max('site_code');

        return json(resultArray(['data' => generate_site_code(intval($data) + 1)]));
    }

    /**
     * 导入邮递区号
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function import()
    {
        $result = upload_file('excel');

        if (!$result['status']) {
            $this->error($result['error']);
        }

        $fileName = $result['data']['save_name'];

        $reader = IOFactory::createReaderForFile(UPLOADS_PATH . $fileName);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load(UPLOADS_PATH . $fileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $data = [];
        foreach ($sheetData as $key => $value) {
            $data[$key]['zipcode'] = $value['A'];
            $data[$key]['city'] = $value['B'];
            $data[$key]['town'] = $value['C'];
            $data[$key]['dc'] = $value['D'];
            $data[$key]['area'] = $value['E'];
        }
        unset($data[1]);

        Db::startTrans();

        Db::name('zipcode')->where(['is_enabled' => 1])->update(['is_enabled' => 0]);
        $rs[] = Db::name('zipcode')->insertAll($data);

        if (check_arr($rs)) {
            Db::commit();
            $this->success(lang('IMPORT_SUCCESS'));
        } else {
            $this->error(lang('IMPORT_FAIL'));
            Db::rollback();
        }

    }

    public function zipcode()
    {
        $zipcode = input('get.zipcode');
        $data = Db::name('zipcode')->where(['zipcode' => $zipcode, 'is_enabled' => 1])->find();
        if (!$data) {
            return json(resultArray(['error' => lang('ZIPCODE_NOT_EXIST')]));
        }

        return json(resultArray(['data' => $data]));
    }

    public function getSite()
    {
        $param = input('param.');

        $where['is_enabled'] = 1;
        $where['site_code'] = $param['site_code'];
        $data = Db::name('site')->where($param)->find();
        if ($data) {
            return json(resultArray(['data' => $data]));
        } else {
            return json(resultArray(['error' => '站点不存在']));
        }
    }
}