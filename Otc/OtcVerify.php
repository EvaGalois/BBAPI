<?php
/**
 * 4.4 OtcVerify（数字货币交易订单校验）
 */
header("Content-Type: text/html;charset=utf-8");
require "../Config/config.php";
include "../Common/Helps.php";

$data = [
    'UserName' => $_POST['UserName'],
    'OrderId' => $_POST['OrderId'],
    'OrderNum' => $_POST['OrderNum'],
    'Coin' => $_POST['Coin'],
    'CoinAmount' => $_POST['CoinAmount']
];
$sqldata = [
    'Account' => '',
    'Bank' => '',
    'RealName' => '',
    'SubBranch' => '',
    'Status' => '1',//0/1/2默认1:审核通过
];
ksort($data);//对关联数组按照键名进行升序排序


$helps = new \Common\Helps();
$formdata = $helps->buildFormData($data);
$wesign = sprintf("%s%s", $formData, $config['KeyB']);
$sign = md5($wesign);

$Account = $sqldata['Account'];
$Status = $sqldata['Status'];
$Bank = urlencode($sqldata['Bank']);
$RealName = urlencode($sqldata['RealName']);
$SubBranch = urlencode($sqldata['SubBranch']);
$backsign = md5($account . $Bank . $RealName . $SubBranch . $config['KeyB']);
if (strcmp($sign, $_POST['Sign']) == 0) {
    $res = [
        'Message' => '成功',
        'Code' => 1,
        'Success' => true,
        'Account' => $Account,
        'Bank' => $Bank,
        'RealName' => $RealName,
        'SubBranch' => $SubBranch,
        'Status' => $Status,
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
$filename = "OtcVerify" . $data['OrderNum'];
$filedata = "wesign" . $wesign . "\r\nres:" . json_encode($res);
$helps->myfwrite($filename, $filedata);

echo json_encode($res);
