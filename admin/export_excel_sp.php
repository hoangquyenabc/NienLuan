<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once "connect.php";

// Tạo file Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Tiêu đề cột
$sheet->setCellValue('A1', 'Mã sản phẩm')
      ->setCellValue('B1', 'Tên sản phẩm')
      ->setCellValue('C1', 'Giá bán')
      ->setCellValue('D1', 'Số lượng kho')
      ->setCellValue('E1', 'Thương hiệu')
      ->setCellValue('F1', 'Loại sản phẩm');

$sql = "SELECT sp.*, bg.*, l.*, t.*
                        FROM san_pham sp
                        JOIN (
                            SELECT ID_SP, Gia, NgayGio
                            FROM co_gia bg1
                            WHERE NgayGio = (
                                SELECT MAX(NgayGio)
                                FROM co_gia bg2
                                WHERE bg1.ID_SP = bg2.ID_SP
                            )
                        ) bg ON sp.ID_SP = bg.ID_SP
                        JOIN loai_sp l ON l.ID_L = sp.ID_L
                        JOIN thuong_hieu t ON t.ID_TH = sp.ID_TH
                        ORDER BY sp.ID_SP";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $rowNum = 2; // Bat dau tu dong 2
    while ($row = mysqli_fetch_assoc($result)) {
        $sheet->setCellValue('A' . $rowNum, $row['ID_SP']);
        $sheet->setCellValue('B' . $rowNum, $row['Ten_SP']);
        $sheet->setCellValue('C' . $rowNum, $row['Gia']);
        $sheet->setCellValue('D' . $rowNum, $row['SoLuongKho']);
        $sheet->setCellValue('E' . $rowNum, $row['Ten_TH']);
        $sheet->setCellValue('F' . $rowNum, $row['Ten_L']);
        $rowNum++;
    }
}

// Xuất file Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="danh_sach_san_pham.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
