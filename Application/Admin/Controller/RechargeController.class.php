<?php
namespace Admin\Controller;
use Think\Controller;
class RechargeController extends Controller {
    public function index(){
$money=$_POST['money'];


// error_reporting(0);

		$appkey = 'c86eb87db2fd7713f25d5c5858d1e42b';

		$a = array (
			'appid'=>'wx45fae74da5a3cb9d',
			'mch_id'=>'1349659401',
			'nonce_str'=>getNonceStr(),
			// 'sign'=>'C380BEC2BFD727A4B6845133519F3AD6',
			'body'=>'腾讯充值中心-QQ会员充值',
			'out_trade_no'=>$out_trade_no,
			'total_fee'=>'0.1' * 100,
			'spbill_create_ip'=>'182.201.217.218',
			'notify_url'=>'http://www.weixin.qq.com/wxpay/pay.php',
			'trade_type'=>'NATIVE'
		);

		$a['sign'] = sign($a);
		//$a = http_build_query($a);
		$a = ToXml($a);
		$opts = array(
			'http'=>array(
				'method'=>'POST',
				'header' => 'Content-type:application/xml;encoding=utf-8',
				// 'Content-Length: ' . strlen($a) . 'rn',
				'content' => $a	
			));


		$context = stream_context_create($opts);

		$result = file_get_contents('https://api.mch.weixin.qq.com/pay/unifiedorder',false,$context);

		$result = simplexml_load_string($result,'SimpleXMLElement',LIBXML_NOCDATA);

		$result = json_encode($result);

		$result = json_decode($result,true);


		$code_url = $result['code_url'];

echo $return_code;
		//$this->redirect('index/qrcode.html?url='.11);
		$this->qrcode($code_url);

    }
	
	public function qrcode($url,$level=3,$size=4)
    {
			
              Vendor('phpqrcode.phpqrcode');
              $errorCorrectionLevel =intval($level) ;//容错级别 
              $matrixPointSize = intval($size);//生成图片大小 
         //   生成二维码图片 
              $object = new \QRcode();
              $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);   
	 echo $return_code;
    }

	
}