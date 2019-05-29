<?php
/**
 * 4.5 OtcQuery（用户平台虚拟币余额查询）
 * 此处从获取到的参数去查询数据库的余额（CoinAmount）与币种（Coin）
 */
header("Content-Type: text/html;charset=utf-8");
require "../Config/config.php";
include "../Common/Helps.php";
$data = [
    'UserName' => $_POST['UserName'],
    'OrderNum' => $_POST['OrderNum'],
    'Coin' => $_GET['Coin']
];
/*生產階段根據上方訂單號查詢出下列結果*/
$sqldata = [
    'Coin' => 'DC',
    'CoinAmount' => "1000",
];

ksort($data);//对关联数组按照键名进行升序排序

$helps = new \Common\Helps();
$formdata = $helps->buildFormData($data);
$wesign = sprintf("%s%s", $formData, $config['KeyB']);
$sign = md5($wesign);
$backsign = md5($sqldata['Coin'] . $sqldata['CoinAmount'] . $config['KeyB']);
if (strcmp($sign, $_POST['Sign']) == 0) {
    $res = [
        'Message' => '成功',
        'Code' => 1,
        'Success' => true,
        'Coin' => $config['Coin'],
        'CoinAmount' => $config['CoinAmount'],
        'Sign' => $backsign
    ];
} else {
    $res = [
        'Message' => '签名错误',
        'Code' => 0,
        'Success' => false,
    ];
}
/*日志记录*/
$filename = "OtcQuery" . $data['OrderNum'];
$filedata = "wesign" . $wesign . "\r\nres:" . json_encode($res);
$helps->myfwrite($filename, $filedata);

echo json_encode($res);
