<?php
    
    
    require('header.php'); 
    require_once "connect.php";

    

    $id_kh = intval($_SESSION['ID_KH']); 
    $sql = "
        SELECT d.*, h.*, k.* 
        FROM dia_chi_giao_hang d
        JOIN don_hang h ON d.ID_DH = h.ID_DH
        JOIN khach_hang k ON h.ID_KH = k.ID_KH
        WHERE h.ID_KH = $id_kh
        ORDER BY h.NgayTao DESC 
        LIMIT 1;
    ";
    $result1 = mysqli_query($conn, $sql);

    if ($result1 && mysqli_num_rows($result1) > 0) {
        $order_info = mysqli_fetch_assoc($result1); 
       
        $customer_id = $order_info['ID_DH'];
        $customer_total = $order_info['TongTien'];
    } else {
        // echo "Không tìm thấy thông tin đơn hàng.";
        exit;
    }
?>
<?php
function execPostRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Yêu cầu cURL trả về dữ liệu thay vì in ra màn hình.
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

$endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

$partnerCode = 'MOMOBKUN20180529';
$accessKey = 'klm05TvNBzhg7h7j';
$secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
$orderInfo = "Thanh toán qua MoMo";


$amount = isset($order_info['TongTien']) ? $order_info['TongTien'] : 10000;
$orderId = isset($order_info['ID_DH']) ? $order_info['ID_DH'] : time();

$redirectUrl = "http://localhost/LV/order.php";
$ipnUrl = "http://localhost/LV/order.php";
$extraData = "";

$requestId = time() . "";
$requestType = "payWithATM";

// Tạo chữ ký HMAC SHA256 để đảm bảo dữ liệu không bị chỉnh sửa.
$rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType; //Chuỗi dữ liệu cần mã hóa
$signature = hash_hmac("sha256", $rawHash, $secretKey); 

$data = array(
    'partnerCode' => $partnerCode,
    'partnerName' => "Test",
    "storeId" => "MomoTestStore",
    'requestId' => $requestId,
    'amount' => $amount,
    'orderId' => $orderId,
    'orderInfo' => $orderInfo,
    'redirectUrl' => $redirectUrl,
    'ipnUrl' => $ipnUrl,
    'lang' => 'vi',
    'extraData' => $extraData,
    'requestType' => $requestType,
    'signature' => $signature
);

//Gửi request đến MoMo API và giải mã phản hồi JSON.
$result = execPostRequest($endpoint, json_encode($data));
$jsonResult = json_decode($result, true); 

// Kiểm tra lỗi
if (isset($jsonResult['payUrl'])) {
    header('Location: ' . $jsonResult['payUrl']);
} else {
    echo "Lỗi khi tạo thanh toán: " . json_encode($jsonResult);
}
?>
