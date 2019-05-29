<?php
/**
 * 4.7 ApproveSell（数字货币交易卖单审核通知接口，商户代理平台手动
 * 审单接口）
 * 数字货币交易卖单审核通知接口（手动审单接口），4.4 接口数字货币交
 * 易订单校验是并回传订单审核状态，如果是 Status 状态为 0 审核中， 商户代
 * 理平台需要积极主动调用此接口，进行人工审单。 数字货币交易 等待审核
 * 时间为 30 分钟，请在订单有效期内，审核订单。逾期将关闭订单，并通过 4.3
 * 接口进行通知。
 */
header("Content-Type: text/html;charset=utf-8");
require "../Config/config.php";
include "../Common/Helps.php";
if (@isset($_GET['submit'])) {
    $config['Url'] = "http://vboutox.gosafepp.com/api/xyft/coin/ApproveSell";
    $order=@$_GET['order'];
    $time = time();
//Timestamp可省略
    $data = [
        'MerCode' => $config['MerCode'],
        'UserName' => 'xiaoming',
        'Coin' => 'DC',
        'Ordernum' => '',//此处首字母文档写的小写，
        'CoinAmount' => '',
        'Code' => 1,//1/2默认1通过审核
    ];
//获得不加密的key
    $helps = new \Common\Helps();
    $keya = $helps->setrand(6);
    $keyc = $helps->setrand(4);
    $keyb = $helps->buildFormData($data);
    $keyB = $keyb . $config['KeyB'] . date("Ymd", $time);;
    $keys = md5($keya . $keyB . $keyc);

//再组成需要加密的数据
    $data['Timestamp'] = $time;
    $desdata = $helps->buildFormData($data);
    $desdata = $desdata . "&key=" . $config['DesKey'];
//密钥
    $iv = $config['DesKey'];
//2.加密获得param的数值
    $param = strtoupper(bin2hex(openssl_encrypt($desdata, "DES-CBC", $iv, OPENSSL_RAW_DATA, $iv)));
//拼接url
    $url = $config['Url'] . "?param=" . $param . "&Key=" . $keys;
//请求
    $result = $helps->getcurl($url);

    if ($result->Success == true) {
        echo "操作成功，返回数据为：" . $result->Message;
    } else {
        echo $result->Message;
    }
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Title</title>
    </head>
    <body>
    <h1>币宝订单审核</h1>
    <form action="" method="get">
        <li>
            <span>订单号</span>
            <span><input type="text" name="order" id="" value=""></span>
        </li>
        <li>
            <input type="submit" name="submit" value="提交">
        </li>
    </form>
    </body>
    </html>

<?php } ?>