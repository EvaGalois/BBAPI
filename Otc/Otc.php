<?php
/**
 * 4.3 Otc（数字货币交易订单变动通知）
 */
header("Content-Type: text/html;charset=utf-8");
require "../Config/config.php";
include "../Common/Helps.php";
$data = [
    'UserName' => $_POST['UserName'],
    'OrderId' => $_POST['OrderId'],
    'OrderNum' => $_POST['OrderNum'],
    'Type' => $_POST['Type'],
    'Coin' => $_POST['Coin'],
    'CoinAmount' => $_POST['CoinAmount'],
    'LegalAmount' => $_POST['LegalAmount'],
    'State1' => $_POST['State1'],
    'State2' => $_POST['State2'],
    'CreateTime' => $_POST['CreateTime'],
    'Remark' => $_POST['Remark'],
    'Price' => $_POST['Price'],
    'Token' => $_POST['Token']
];
ksort($data);

$helps = new \Common\Helps();
$formdata = $helps->buildFormData($data);
$wesign=sprintf("%s%s", $formData, $config['KeyB']);
$sign = md5($wesign);
if (strcmp($sign, $_POST['Sign']) == 0) {
    $res = [
        'Message' => '成功',
        'Code' => 1,
        'Success' => true
    ];
} else {
    $res = [
        'Message' => '签名错误',
        'Code' => 0,
        'Success' => false
    ];
}
/*日志记录*/
$filename = "Otc" . $data['OrderNum'];
$filedata = "wesign" . $wesign . "\r\nres:" . json_encode($res);
$helps->myfwrite($filename, $filedata);

echo json_encode($res);
