<?php
/**
 * 4.6 GetBankCard(获取银行卡信息)
 */
header("Content-Type: text/html;charset=utf-8");
require "../Config/config.php";
include "../Common/Helps.php";
$data = [
    'UserName' => $_POST['UserName'],
    'OrderNum' => $_POST['OrderNum']
];
$sqldata = [
    'Account' => '',//银行卡号
    'Bank' => '',//银行，招商银行等等
    'RealName' => '',//开户名
    'SubBranch' => '',//支行名称
];
ksort($data);//对关联数组按照键名进行升序排序

$helps = new \Common\Helps();
$formdata = $helps->buildFormData($data);
$wesign = sprintf("%s%s", $formdata, $config['KeyB']);
$sign = md5($wesign);
//此处应当从数据库查
$account = $sqldata['Account'];
$Bank = urlencode($sqldata['Bank']);
$RealName = urlencode($sqldata['RealName']);
$SubBranch = urlencode($sqldata['SubBranch']);
$backsign = md5($account . $Bank . $RealName . $SubBranch . $config['KeyB']);
if (strcmp($sign, $_POST['Sign']) == 0) {
    $res = [
        'Message' => '成功',
        'Code' => 1,
        'Success' => true,
        
        'Account' => $account,
        'Bank' => $Bank,
        'RealName' => $RealName,
        'SubBranch' => $SubBranch,
        'Sign' => $backsign,
    ];
} else {
    $res = [
        'Message' => '签名错误',
        'Code' => 0,
        'Success' => false,
    ];
}
/*日志记录*/
$filename = "GetBankCard" . $data['OrderNum'];
$filedata = "wesign" . $wesign . "\r\nres:" . json_encode($res);
$helps->myfwrite($filename, $filedata);

echo json_encode($res);
