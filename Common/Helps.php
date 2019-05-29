<?php

namespace Common;

class Helps
{
    var $url;
    var $data;
    var $length;
    var $filename;
    var $res;

    /*curl请求方法*/
    public function getcurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        #尽量要返回json，当然也可以不，这只是一个规范
        return json_decode($output);
    }

    /*将数组转化为字符串*/
    public function buildFormData($data)
    {
        if (!is_array($data)) return "";
        $result = "";
        foreach ($data as $k => $v) {
            $result .= sprintf("%s=%s&", $k, $v);
        }
        $result = rtrim($result, "&");
        return $result;
    }

    /*获取一定数量的随机数*/
    public function setrand($length)
    {
        $hashs = '';
        $chars = '1234567890abcdefghijk';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hashs .= $chars[mt_rand(0, $max)];
        }
        return $hashs;
    }

    /*打印日志*/
    public function myfwrite($filename, $res)
    {
        $filename = "rizhi/" . $filename . ".txt";
        $dsy = "";
        $dsy .= $res;
        file_put_contents($filename, $dsy);
    }
}