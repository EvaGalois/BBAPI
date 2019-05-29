<?php
header('content-type:text/html;charset=utf-8');
require "../Config/config.php";
include "../Common/Helps.php";
if (@isset($_GET['submit'])) {
    // 获取变量
    $ordernum = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    $config['UserName'] = @$_GET['username'];
    $config['Url'] = "http://vboutox.gosafepp.com/api/xyft/coin/Login";
    $config['Type'] = '1';
    $config['Coin'] = "DC";
    $config['OrderNum'] = $ordernum;
    $time = time();
    /*deskey:3S9rUV9u
    虚拟币key:keyA=6位,keyB=dVsVx5F9f,kfyC=4位*/
    $helps = new \Common\Helps();
    $keyA = $helps->setrand(6);
    $keyC = $helps->setrand(4);
    $keyB = md5($config['MerCode'] . $config['UserName'] . $config['Type'] . $config['OrderNum'] . $config['KeyB'] . date("Ymd", $time));
    /*1.通过六位随机数keyA+mds的keyB+四位随机数keyC拼接得到url中不加密的key值*/
    $keys = $keyA . $keyB . $keyC;
    $data = 'MerCode=' . $config['MerCode'] . '&Timestamp=' . $time . '&UserName=' . $config['UserName'] . '&Type=' . $config['Type'] . '&Coin=' . $config['Coin'] . '&OrderNum=' . $config['OrderNum'] . '&Key=' . $config['DesKey'];
    //deskey
    $iv = $config['DesKey'];
    //2.加密获得param的数值
    $param = strtoupper(bin2hex(openssl_encrypt($data, "DES-CBC", $iv, OPENSSL_RAW_DATA, $iv)));
    //拼接url
    $url = $config['Url'] . "?param=" . $param . "&Key=" . $keys;
    //3.请求接口
    $output = $helps->getcurl($url);
    if ($output->Success == true) {
        echo "登录成功，请通过此按钮进行下一步操作： <a href='" . $output->Data->Url . "/" . $output->Data->Token . "'>前往交易</a>";
    } else {
        echo $output->Message;
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
    <h1>登录币宝</h1>
    <form action="" method="get">
        <li>
            <span>姓名</span>
            <span><input type="text" name="username" id="" value=""></span>
        </li>
        <li>
            <input type="submit" name="submit" value="提交">
        </li>
    </form>
    </body>
    </html>
<?php } ?>