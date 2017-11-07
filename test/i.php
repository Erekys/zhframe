<?php
/**
 *
 * @copyright 2007-2012 Xiaoqiang.
 * @author Xiaoqiang.Wu <jamblues@gmail.com>
 * @version 1.01
 */

error_reporting(E_ALL);
ini_set('memory_limit', '4024M');

date_default_timezone_set('Asia/ShangHai');

/** PHPExcel_IOFactory */
require_once 'Class/ExcelClasses/PHPExcel/IOFactory.php';


// Check prerequisites
//xls=>Excel5  xlsx=>Excel2007

$reader = PHPExcel_IOFactory::createReader('Excel2007'); //设置以Excel5格式(Excel97-2003工作簿)
$PHPExcel = $reader->load("/Users/lisx/Downloads/Hidden(1).xlsx"); //载入xlsx文件
$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
$highestRow = $sheet->getHighestRow(); // 取得总行数
$highestColumm = $sheet->getHighestColumn(); // 取得总列数
$end_index     = PHPExcel_Cell::columnIndexFromString($highestColumm);
$end_index = 10;
//var_dump($end_index);die;


/** 循环读取每个单元格的数据 */
for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
    $info = array();
    for ($column = 0; $column < $end_index; $column++) {//列数是以A列开始
        $col_name = PHPExcel_Cell::stringFromColumnIndex($column);
        //$dataset[] = $sheet->getCell($column.$row)->getValue();
        $str = trim($sheet->getCell($col_name.$row)->getValue());
        //var_dump($str);
        $info[] = $str;    
    }
    $data[] = $info;
}
echo json_encode($data);die;

$fp = fopen('20170214.json', 'wb');
fwrite($fp, json_encode($data));
fclose($fp);


?>
