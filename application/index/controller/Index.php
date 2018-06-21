<?php
namespace app\index\controller;

class Index extends Base
{
    public function index()
    {
        return view();
    }

    public function test()
    {
        //        $spreadsheet = new Spreadsheet();
//        $sheet = $spreadsheet->getActiveSheet();
//        $sheet->setCellValue('A1', 'Hello World');
//
//        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header('Content-Disposition: attachment;filename="myfile.xlsx"');
//        header('Cache-Control: max-age=0');
//
//        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
//        $writer->save('php://output');
//        exit;
    }
}
