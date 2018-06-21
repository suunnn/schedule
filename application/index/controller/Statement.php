<?php

namespace app\index\controller;

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use think\Db;

class Statement extends Base
{
    public function import()
    {
        $result = upload_file('excel');
        if (!$result['status']) {
            $this->error($result['error']);
        }

        $data['filename'] = $result['data']['file_name'];
        $data['pathname'] = $result['data']['save_name'];
        $data['type'] = 1;
        $data['createtime'] = time();
        $result = Db::name('file_system')->insert($data);
        if ($result) {
            $this->success(lang('IMPORT_SUCCESS'));
        } else {
            $this->error(lang('IMPORT_FAIL'));
        }

    }

    public function shipment()
    {
        $this->assign('date', date('Y-m-d', strtotime(date('Y-m-d', time())) + 3600 * 24));
        return view();
    }

    public function picking()
    {
        $params = input('param.');

        $result = Db::name('file_system')->where(['type' => 1])->order('id desc')->find();

        $reader = IOFactory::createReaderForFile(UPLOADS_PATH . $result['pathname']);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load(UPLOADS_PATH . $result['pathname']);
        $sheetData = $spreadsheet->getSheet(0)->toArray(null, true, true, true);
        $sheetData1 = $spreadsheet->getSheet(1)->toArray(null, true, true, true);

        /************************************************************************/
        foreach ($sheetData as $key => $value) {
            foreach ($sheetData1 as $value1) {
                if (trim($value['A']) == trim($value1['B'])) {
                    $sheetData[$key]['num'] = $value1['E'];
                    $sheetData[$key]['product_no'] = $value1['C'];
                    $sheetData[$key]['product_name'] = $value1['D'];
                }
            }
        }

        $dc_arr = Db::name('zipcode')->group('dc')->column('dc');
        $sheetDataDc = [];
        foreach ($dc_arr as $dc) {
            foreach ($sheetData as $value) {
                $zipcodeDc = Db::name('zipcode')->where(['zipcode' => $value['H']])->value('dc');
                if ($zipcodeDc == $dc) {
                    $sheetDataDc[$dc][] = $value;
                }
            }
        }

        $spreadsheet = new Spreadsheet();

        $dcK = 0;
        foreach ($sheetDataDc as $key => $value) {
            $workSheet = new Worksheet($spreadsheet, "($key) 拣货表");
            $spreadsheet->addSheet($workSheet, $dcK);
//            $spreadsheet->createSheet($dcK);
            $sheet = $spreadsheet->getSheet($dcK);


            $sheet->mergeCells('A1:C1');
            $sheet->setCellValue('A1', "($key) " . $params['factory_fullname'] . " 拣货表\n出货单日期:" . date('Y-m-d', time()));
            $sheet->setCellValue('A2', '產品編號');
            $sheet->setCellValue('B2', '產品名稱');
            $sheet->setCellValue('C2', '產品數量');
            foreach ($value as $k => $v) {
                $sheet->setCellValueExplicit("A" . ($k + 3), $v['product_no'], DataType::TYPE_STRING2);
                $sheet->setCellValue("B" . ($k + 3), $v['product_name']);
                $sheet->setCellValue("C" . ($k + 3), $v['num']);
            }

            $dcK++;
        }
        /************************************************************************/


        /************************************************************************/
        $site_arr = Db::name('site')->where(['is_enabled' => 1])->select();
        $shipment = [];
        foreach ($sheetData as $key => $value) {
            foreach ($site_arr as $site) {
                if ($site['zipcode'] == trim($value['H'])) {
                    $shipment[$key]['shortname'] = $site['site_shortname'];
                    $shipment[$key]['address'] = $site['address'];
                    $shipment[$key]['remark'] = $site['remark'];
                    $shipment[$key]['tracking_no'] = trim($value['A']);
                    $shipment[$key]['zipcode'] = trim($value['H']);

                }
            }
        }

        $shipmentDc = [];
        foreach ($dc_arr as $dc) {
            foreach ($shipment as $value) {
                $zipcodeDc = Db::name('zipcode')->where(['zipcode' => $value['zipcode']])->value('dc');
                if ($zipcodeDc == $dc) {
                    $shipmentDc[$dc][] = $value;
                }
            }
        }


        $dcK = 0;
        foreach ($shipmentDc as $key => $value) {
            $workSheet = new Worksheet($spreadsheet, "($key) 位码出货表");
            $spreadsheet->addSheet($workSheet, $dcK);
            $sheet = $spreadsheet->getSheet($dcK);
            $sheet->mergeCells('A1:C1');
            $sheet->setCellValue('A1', "($key) " . $params['factory_fullname'] . " 位码/出货表\n出货单日期:" . date('Y-m-d', time()));
            $sheet->setCellValue('A2', '位码');
            $sheet->setCellValue('B2', '配送点简称/地址/备注');
            foreach ($value as $k => $v) {
                $sheet->setCellValue("A" . ($k + 3), '');
                $sheet->setCellValue("B" . ($k + 3), $v['shortname'] . ' ' . $v['address'] . ' ' . $v['remark']);
            }
            $dcK++;
        }
        /************************************************************************/

        /************************************************************************/
        $checkData = [];
        foreach ($sheetData as $key => $value) {
            foreach ($site_arr as $site) {
                if ($site['zipcode'] == trim($value['H'])) {
                    $checkData[$key]['fullname'] = $site['site_fullname'];
                    $checkData[$key]['shortname'] = $site['site_shortname'];
                    $checkData[$key]['address'] = $site['address'];
                    $checkData[$key]['remark'] = $site['remark'];
                    $checkData[$key]['tracking_no'] = trim($value['A']);
                    $checkData[$key]['zipcode'] = trim($value['H']);

                }
            }
        }

        $checkDataDc = [];
        foreach ($dc_arr as $dc) {
            foreach ($checkData as $value) {
                $zipcodeDc = Db::name('zipcode')->where(['zipcode' => $value['zipcode']])->value('dc');
                if ($zipcodeDc == $dc) {
                    $checkDataDc[$dc][] = $value;
                }
            }
        }

        $dcK = 0;
        foreach ($checkDataDc as $key => $value) {
            $workSheet = new Worksheet($spreadsheet, "($key) 出货核对单");
            $spreadsheet->addSheet($workSheet, $dcK);
            $sheet = $spreadsheet->getSheet($dcK);
            $sheet->mergeCells('A1:C1');
            $sheet->setCellValue('A1', "($key) " . $params['factory_fullname'] . " 出货核对单\n出货单日期:" . date('Y-m-d', time()));
            $sheet->setCellValue('A2', '配送点名称');
            $sheet->setCellValue('B2', '分店名称/地址/备注');
            $sheet->setCellValue('C2', '出货单编号');
            foreach ($value as $k => $v) {
                $sheet->setCellValue("A" . ($k + 3), $v['fullname']);
                $sheet->setCellValue("B" . ($k + 3), $v['shortname'] . ' ' . $v['address'] . ' ' . $v['remark']);
                $sheet->setCellValue("C" . ($k + 3), $v['tracking_no']);
            }
            $dcK++;
        }

        /************************************************************************/


//        print_r($shipment);exit;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header('Content-Disposition: attachment;filename="' . $params['factory_fullname'] . '.xlsx"');
        header('Content-Disposition: attachment;filename="' . $params['factory_fullname'] . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;

    }
}