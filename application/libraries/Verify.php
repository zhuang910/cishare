<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 验证码
 * @author zhuangqianlin
 *
 */
class Verify {
	
	public static $seKey = 'verify';
	
	public static $codeSet = '0123456789ABCDEFGHJKLMNPQRTUVWXY';
	public static $fontSize = 26;
	public static $useCurve = true;
	public static $useNoise = true;
	public static $imageH = 0;
	public static $imageL = 0;
	public static $length = 4;
	public static $bg = array (243, 251, 254 );
	
	protected static $_image = null;
	protected static $_color = null;
	function __construct() {
	}
	
	public static function buildImageVerify() {
		
		self::$imageL || self::$imageL = self::$length * self::$fontSize * 1.5 + self::$fontSize * 1.5;
		self::$imageH || self::$imageH = self::$fontSize * 2;
		self::$_image = imagecreate ( self::$imageL, self::$imageH );
		imagecolorallocate ( self::$_image, self::$bg [0], self::$bg [1], self::$bg [2] );
		
		self::$_color = imagecolorallocate ( self::$_image, mt_rand ( 1, 120 ), mt_rand ( 1, 120 ), mt_rand ( 1, 120 ) );
		
		$ttf = dirname ( __FILE__ ) . '/ttfs/t' . mt_rand ( 1, 9 ) . '.ttf';
		if (self::$useNoise) {
			self::_writeNoise ();
		}
		if (self::$useCurve) {
			self::_writeCurve ();
		}
		
		$code = array ();
		$codeNX = 0;
		for($i = 0; $i < self::$length; $i ++) {
			$code [$i] = self::$codeSet [mt_rand ( 0, 9 )];
			$codeNX += mt_rand ( self::$fontSize * 1.2, self::$fontSize * 1.6 );
			imagettftext ( self::$_image, self::$fontSize, mt_rand ( - 40, 70 ), $codeNX, self::$fontSize * 1.5, self::$_color, $ttf, $code [$i] );
		}
		
		$_SESSION [self::$seKey] = md5 ( strtolower ( join ( '', $code ) ) );
		
		header ( 'Pragma: no-cache' );
		header ( "content-type: image/JPEG" );
		
		imageJPEG ( self::$_image );
		imagedestroy ( self::$_image );
	}
	
	protected static function _writeCurve() {
		$A = mt_rand ( 1, self::$imageH / 2 );
		$b = mt_rand ( - self::$imageH / 4, self::$imageH / 4 );
		$f = mt_rand ( - self::$imageH / 4, self::$imageH / 4 );
		$T = mt_rand ( self::$imageH * 1.5, self::$imageL * 2 );
		$w = (2 * M_PI) / $T;
		
		$px1 = 0;
		$px2 = mt_rand ( self::$imageL / 2, self::$imageL * 0.667 );
		for($px = $px1; $px <= $px2; $px = $px + 0.9) {
			if ($w != 0) {
				$py = $A * sin ( $w * $px + $f ) + $b + self::$imageH / 2;
				$i = ( int ) ((self::$fontSize - 6) / 4);
				while ( $i > 0 ) {
					imagesetpixel ( self::$_image, $px + $i, $py + $i, self::$_color );
					$i --;
				}
			}
		}
		
		$A = mt_rand ( 1, self::$imageH / 2 );
		$f = mt_rand ( - self::$imageH / 4, self::$imageH / 4 );
		$T = mt_rand ( self::$imageH * 1.5, self::$imageL * 2 );
		$w = (2 * M_PI) / $T;
		$b = $py - $A * sin ( $w * $px + $f ) - self::$imageH / 2;
		$px1 = $px2;
		$px2 = self::$imageL;
		for($px = $px1; $px <= $px2; $px = $px + 0.9) {
			if ($w != 0) {
				$py = $A * sin ( $w * $px + $f ) + $b + self::$imageH / 2;
				$i = ( int ) ((self::$fontSize - 8) / 4);
				while ( $i > 0 ) {
					imagesetpixel ( self::$_image, $px + $i, $py + $i, self::$_color );
					$i --;
				}
			}
		}
	}
	
	protected static function _writeNoise() {
		for($i = 0; $i < 10; $i ++) {
			$noiseColor = imagecolorallocate ( self::$_image, mt_rand ( 150, 225 ), mt_rand ( 150, 225 ), mt_rand ( 150, 225 ) );
			for($j = 0; $j < 5; $j ++) {
				imagestring ( self::$_image, 5, mt_rand ( - 10, self::$imageL ), mt_rand ( - 10, self::$imageH ), self::$codeSet [mt_rand ( 0, 28 )], $noiseColor );
			}
		}
	}
}