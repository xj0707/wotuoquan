<?php
namespace app\admin\controller;

use app\common\controller\Common;
use Endroid\QrCode\QrCode;
use think\Controller;
use think\Loader;
use think\Request;
use think\Response;
use think\Url;
use think\Session;
use think\Config;

/**
* 登录
* @author aierui github  https://github.com/Aierui
* @version 1.0 
*/
class Index extends AdminCommon
{
	/**
	 * 后台登录首页
	 */
	public function index()
	{
		//var_dump($_SESSION);
		return $this->fetch();
	}
	public function view()
    {
        $qrCode = new QrCode();
        $qrCode
            ->setText('stufffdgdfgdfgfdgfd')//内容
            ->setSize(300)//二维码的长宽度正方形
            ->setPadding(10)
            ->setErrorCorrection('high')
            ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0])
            ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0])
            ->setImageType(QrCode::IMAGE_TYPE_PNG);

//        $response = new Response($qrCode->get(), 200);
//        $response->contentType($qrCode->getContentType());
//        $res= $qrCode->getImage();
//        var_dump($res);
    }
    public function view1(){
	    vendor('phpqrcode.phpqrcode');
	    $errorCorrectionLevel=3;
	    $matrixPointSize=4;
	    $object=new \QRcode();
        $object->png('aa','aa.png',$errorCorrectionLevel,$matrixPointSize,2);
    }

}