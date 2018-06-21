<?php

namespace app\index\controller;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use think\Db;

class Schedule extends Base
{
    public function index()
    {
        return view();
    }

    public function getList()
    {
        $param = input('param.');
        $where = ['is_enabled' => 1];
        if (isset($param['field1']) && isset($param['condition1']) && isset($param['value1'])) {
            $where[$param['field1']] = [$param['condition1'], $param['value1']];
        }
        if (isset($param['field2']) && isset($param['condition2']) && isset($param['value2'])) {
            $where[$param['field2']] = [$param['condition2'], $param['value2']];
        }
        if (isset($param['field3']) && isset($param['condition3']) && isset($param['value3'])) {
            $where[$param['field3']] = [$param['condition3'], $param['value3']];
        }

//        $list = Db::name('factory')->where($where)->paginate(10);
        $list = Db::name('schedule')->where($where)->select();
//        $this->assign('list', $list);
        return $list;
    }

    public function edit()
    {
        $id = input('param.id');

        if (request()->isPost()) {
            $post = input('post.');

//            $post['distribution_date'] = isset($post['distribution_date']) && !empty($post['distribution_date']) ? strtotime($post['distribution_date']) : '';
//            $post['original_distribution_date'] = isset($post['original_distribution_date']) && !empty($post['original_distribution_date']) ? strtotime($post['original_distribution_date']) : '';

            if (isset($post['distribution_date']) && $post['distribution_date']) {
                $post['distribution_date'] = strtotime($post['distribution_date']);
            }

            if (isset($post['original_distribution_date']) && $post['original_distribution_date']) {
                $post['original_distribution_date'] = strtotime($post['original_distribution_date']);
            }

            if (isset($id) && $id) {
                $post['updatetime'] = time();
                unset($post['id']);
                $result = Db::name('schedule')->where(['id' => $id])->update($post);
            } else {
                $post['createtime'] = $post['updatetime'] = time();
                $result = Db::name('schedule')->insertGetId($post);
            }

            if (request()->isAjax()) {
                if ($result) {
                    return json(resultArray(['data' => '更新成功']));
                } else {
                    return json(resultArray(['error' => '更新失败']));
                }
            }
            if ($result) {
                $this->success('更新成功');
            } else {
                $this->error('更新失败');
            }

        } else {

            if (isset($id) && $id) {
                $data = Db::name('schedule')->where(['id' => $id, 'is_enabled' => 1])->find();
                $data['distribution_date'] = date('Y-m-d', $data['distribution_date']);
                $data['original_distribution_date'] = date('Y-m-d', $data['original_distribution_date']);

                $this->assign('data', $data);
            } else {
                $data['distribution_date'] = $data['original_distribution_date'] = date('Y-m-d', time() + 3600 * 24);

                $this->assign('data', $data);
            }

            return view();
        }
    }

    public function delete()
    {
        $ids = input('post.id');
        $result = Db::name('schedule')->where(['id' => ['in', $ids]])->update(['is_enabled' => 0]);
        if ($result) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }

    }

    public function dispatch()
    {
        $data = Db::name('schedule')->where(['is_enabled' => 1])->select();
        $this->assign('data', $data);
        return view();
    }

    public function exportByZipcode()
    {
        $param = input('param.');
        $zipcode = $param['zipcode'];

        $data = Db::name('schedule')->where(['zipcode_area' => $zipcode, 'is_enabled' => 1])->select();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', "配送日期" . date('Y-m-d', time() + 3600 * 24));
        $sheet->setCellValue('A2', "区域码" . $zipcode);

        $sheet->setCellValue('A3', "客户");
        $sheet->setCellValue('B3', "厂商");
        $sheet->setCellValue('C3', "件数");
        $sheet->setCellValue('D3', "位码");
        $sheet->setCellValue('E3', "备注");
        $sheet->setCellValue('F3', "客户地址/客户备注");

        foreach ($data as $k => $v) {
            $sheet->setCellValue("A" . ($k + 4), "");
            $sheet->setCellValue("B" . ($k + 4), $v['factory_name']);
            $sheet->setCellValue("C" . ($k + 4), $v['number']);
            $sheet->setCellValue("D" . ($k + 4), '');
            $sheet->setCellValue("E" . ($k + 4), $v['remarks']);
            $sheet->setCellValue("F" . ($k + 4), $v['address']);
        }

        //        print_r($shipment);exit;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header('Content-Disposition: attachment;filename="' . $params['factory_fullname'] . '.xlsx"');
        header('Content-Disposition: attachment;filename="' . $zipcode . '配送报表.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}