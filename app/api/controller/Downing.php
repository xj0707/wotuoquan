<?php
namespace app\api\controller;

class Downing extends CheckSession{
    public function apkdown(){
        $file_name="na.txt";
        $file_path=WTQ."/".$file_name;
        if(!file_exists($file_path)){
            return json(['code'=>-1,'message'=>'文件不存在！']);
        }
        $fp=fopen($file_path,"r");

        //获取文件大小
        $file_size=filesize($file_path);

        //http 下载需要的响应头
        header("Content-type: application/octet-stream"); //返回的文件
        header("Accept-Ranges: bytes");   //按照字节大小返回
        header("Accept-Length: $file_size"); //返回文件大小
        header("Content-Disposition: attachment; filename=".$file_name);//这里客户端的弹出对话框，对应的文件名

        //向客户端返回数据
        //设置大小输出
        $buffer=1024;

        //为了下载安全，我们最好做一个文件字节读取计数器
        $file_count=0;
        //判断文件指针是否到了文件结束的位置(读取文件是否结束)
        while(!feof($fp) && ($file_size-$file_count)>0){

            $file_data=fread($fp,$buffer);
            //统计读取多少个字节数
            $file_count+=$buffer;
            //把部分数据返回给浏览器
            echo $file_data;
        }
        //关闭文件

        fclose($fp);
    }

}