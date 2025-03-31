<?php
require 'vendor/autoload.php'; // Nạp thư viện

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once "connect.php";

// Tạo file Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Tiêu đề cột
$sheet->setCellValue('A1', 'Tên khách hàng')
      ->setCellValue('B1', 'Tên sản phẩm')
      ->setCellValue('C1', 'Nội dung')
      ->setCellValue('E1', 'rating')
      ->setCellValue('F1', 'timestamp');

// Lấy dữ liệu từ database
$sql = "SELECT d.*, k.*, s.* 
        FROM danh_gia d 
        JOIN khach_hang k ON d.ID_KH = k.ID_KH
        JOIN san_pham s ON s.ID_SP = d.ID_SP;";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $rowNum = 2;
    while ($row = mysqli_fetch_assoc($result)) {
        $sheet->setCellValue('A' . $rowNum, $row['Ten_KH']);
        $sheet->setCellValue('B' . $rowNum, $row['Ten_SP']);
        $sheet->setCellValue('C' . $rowNum, $row['NoiDung']);
        $sheet->setCellValue('E' . $rowNum, $row['Rating']);
        $sheet->setCellValue('F' . $rowNum, $row['Created_at']);
        $rowNum++;
    }
}

// Xuất file Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="danh_sach_danh_gia.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
