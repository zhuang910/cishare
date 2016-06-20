<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 该helper 为基础方法
 * ============================================================================
 * 版权所有 2012 MBAChina.com，并保留所有权利。
 * 网站地址: http://www.MBAChina.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您不能对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: zhang junjie $
 * $Id: commen_helper 2012-7-16 $
 */

/**
 *
 * @name 去除代码中的空白和注释
 * @param string $content        	
 * @return string $stripStr
 *        
 * @example 例子
 */
function strip_whitespace($content) {
	$stripStr = '';
	// 分析php源码
	$tokens = token_get_all ( $content );
	$last_space = false;
	for($i = 0, $j = count ( $tokens ); $i < $j; $i ++) {
		if (is_string ( $tokens [$i] )) {
			$last_space = false;
			$stripStr .= $tokens [$i];
		} else {
			switch ($tokens [$i] [0]) {
				// 过滤各种PHP注释
				case T_COMMENT :
				case T_DOC_COMMENT :
					break;
				// 过滤空格
				case T_WHITESPACE :
					if (! $last_space) {
						$stripStr .= ' ';
						$last_space = true;
					}
					break;
				case T_START_HEREDOC :
					$stripStr .= "<<<THINK\n";
					break;
				case T_END_HEREDOC :
					$stripStr .= "THINK;\n";
					for($k = $i + 1; $k < $j; $k ++) {
						if (is_string ( $tokens [$k] ) && $tokens [$k] == ';') {
							$i = $k;
							break;
						} else if ($tokens [$k] [0] == T_CLOSE_TAG) {
							break;
						}
					}
					break;
				default :
					$last_space = false;
					$stripStr .= $tokens [$i] [1];
			}
		}
	}
	return $stripStr;
}

/**
 * 缓存文件读取 写入
 * 计算申请费，传入人民币，返回美元，需要保证个位数逢<5，取+5，逢小于10，取+10,
 */
function calculate_appfee($rbm) {
	$rate = 6; // 基本美元汇率
	$charge = 1.1; // 支付银行扣费
	$usd = intval ( $rbm / $rate * $charge ); // 得到换算后的美元数
	
	$single_digit = $usd % 10; // 得到个位数
	$base_val = $usd - $single_digit; // 得到个位数为0的基准数
	$usd = ($single_digit > 5) ? ($base_val + 10) : ($base_val + 5); // 个位数小于5时，为基准数加5，个位数大于5时，为基准数加10；
	return $usd;
}

/**
 * 缓存文件读取 写入
 */
/**
 * 缓存文件读取 写入
 */
function CF($name, $value = '', $path = 'cache/') {
	static $_cache = array ();
	$filename = $path . $name . '.php';
	if ('' !== $value) {
		if (is_null ( $value )) {
			// 删除缓存
			return unlink ( $filename );
		} else {
			// 缓存数据
			$dir = dirname ( $filename );
			// 目录不存在则创建
			if (! is_dir ( $dir ))
				mk_dir ( $dir );
			$_cache [$name] = $value;
			file_put_contents ( $filename, strip_whitespace ( "<?php\nreturn " . var_export ( $value, true ) . ";\n?>" ) );
		}
	}
	if (isset ( $_cache [$name] ))
		return $_cache [$name];
		
		// 获取缓存数据
	if (is_file ( $filename )) {
		$value = include $filename;
		$_cache [$name] = $value;
	} else {
		$value = false;
	}
	return $value;
}

/**
 * ajax返回
 */
function ajaxReturn($data, $info = '', $status = 1, $type = 'JSON') {
	$result = array ();
	$result ['state'] = $status;
	$result ['info'] = $info;
	$result ['data'] = $data;
	
	if (empty ( $type )) {
		$type = 'JSON';
	}
	if (strtoupper ( $type ) == 'JSON') {
		// 返回JSON数据格式到客户端 包含状态信息
		header ( 'Content-Type:text/html; charset=utf-8' );
		exit ( json_encode ( $result ) );
	} else {
		// TODO 增加其它格式
	}
}

/**
 * 获取客户端IP地址
 */
function get_client_ip() {
	static $ip = NULL;
	if ($ip !== NULL)
		return $ip;
	if (isset ( $_SERVER ['HTTP_X_FORWARDED_FOR'] )) {
		$arr = explode ( ',', $_SERVER ['HTTP_X_FORWARDED_FOR'] );
		$pos = array_search ( 'unknown', $arr );
		if (false !== $pos) {
			unset ( $arr [$pos] );
		}
		$ip = trim ( $arr [0] );
	} elseif (isset ( $_SERVER ['HTTP_CLIENT_IP'] )) {
		$ip = $_SERVER ['HTTP_CLIENT_IP'];
	} elseif (isset ( $_SERVER ['REMOTE_ADDR'] )) {
		$ip = $_SERVER ['REMOTE_ADDR'];
	}
	// IP地址合法验证
	$ip = (false !== ip2long ( $ip )) ? $ip : '0.0.0.0';
	return $ip;
}

/**
 *
 * @param string $string
 *        	原文或者密文
 * @param string $operation
 *        	操作(ENCODE | DECODE), 默认为 DECODE
 * @param string $key
 *        	密钥
 * @param int $expiry
 *        	密文有效期, 加密时候有效， 单位 秒，0 为永久有效
 * @return string 处理后的 原文或者 经过 base64_encode 处理后的密文
 *        
 * @example $a = authcode('abc', 'ENCODE', 'key');
 *          $b = authcode($a, 'DECODE', 'key'); // $b(abc)
 *         
 *          $a = authcode('abc', 'ENCODE', 'key', 3600);
 *          $b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;
	// 随机密钥长度 取值 0-32;
	// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
	// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
	// 当此值为 0 时，则不产生随机密钥
	
	$keya = md5 ( substr ( $key, 0, 16 ) );
	$keyb = md5 ( substr ( $key, 16, 16 ) );
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr ( $string, 0, $ckey_length ) : substr ( md5 ( microtime () ), - $ckey_length )) : '';
	
	$cryptkey = $keya . md5 ( $keya . $keyc );
	$key_length = strlen ( $cryptkey );
	
	$string = $operation == 'DECODE' ? base64_decode ( substr ( $string, $ckey_length ) ) : sprintf ( '%010d', $expiry ? $expiry + time () : 0 ) . substr ( md5 ( $string . $keyb ), 0, 16 ) . $string;
	$string_length = strlen ( $string );
	
	$result = '';
	$box = range ( 0, 255 );
	
	$rndkey = array ();
	for($i = 0; $i <= 255; $i ++) {
		$rndkey [$i] = ord ( $cryptkey [$i % $key_length] );
	}
	
	for($j = $i = 0; $i < 256; $i ++) {
		$j = ($j + $box [$i] + $rndkey [$i]) % 256;
		$tmp = $box [$i];
		$box [$i] = $box [$j];
		$box [$j] = $tmp;
	}
	
	for($a = $j = $i = 0; $i < $string_length; $i ++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box [$a]) % 256;
		$tmp = $box [$a];
		$box [$a] = $box [$j];
		$box [$j] = $tmp;
		$result .= chr ( ord ( $string [$i] ) ^ ($box [($box [$a] + $box [$j]) % 256]) );
	}
	
	if ($operation == 'DECODE') {
		if ((substr ( $result, 0, 10 ) == 0 || substr ( $result, 0, 10 ) - time () > 0) && substr ( $result, 10, 16 ) == substr ( md5 ( substr ( $result, 26 ) . $keyb ), 0, 16 )) {
			return substr ( $result, 26 );
		} else {
			return '';
		}
	} else {
		return $keyc . str_replace ( '=', '', base64_encode ( $result ) );
	}
}
/**
 * url 生成
 *
 * @param string $url        	
 * @param string $vars        	
 * @return string
 */
function URL($url = '', $vars = '') {
	$info = parse_url ( $url );
	// 解析参数
	if (is_string ( $vars )) { // aaa=1&bbb=2 转换成数组
		parse_str ( $vars, $vars );
	} elseif (! is_array ( $vars )) {
		$vars = array ();
	}
	if (isset ( $info ['query'] )) { // 解析地址里面参数 合并到vars
		parse_str ( $info ['query'], $params );
		$vars = array_merge ( $params, $vars );
	}
	
	if (! empty ( $vars )) {
		$vars = http_build_query ( $vars );
		return $url .= '?' . $vars;
	}
}
/**
 * 金额转换INT
 *
 * @param string $str        	
 * @return string
 */
function priceToInt($str) {
	$length = strpos ( $str, '.' );
	if ($length) {
		list ( $yuan, $fen ) = explode ( '.', $str );
		return $yuan . mb_strcut ( $str, $length + 1, 2 );
	} else {
		return $str . '00';
	}
}
/**
 * INT 转换金额
 *
 * @param string $str        	
 * @return string
 */
function intToPrice($str) {
	$length = strlen ( $str );
	if ($length) {
		$fen = mb_strcut ( $str, $length - 2, 2 );
		$yuan = mb_strcut ( $str, 0, $length - 2 );
		if (! $yuan)
			$yuan = '0';
	}
	return $yuan . '.' . $fen;
}

/**
 * 循环创建目录
 *
 * @param string $dir        	
 * @param number $mode        	
 * @return boolean
 */
function mk_dir($dir, $mode = 0777) {
	if (is_dir ( $dir ) || @mkdir ( $dir, $mode ))
		return true;
	if (! mk_dir ( dirname ( $dir ), $mode ))
		return false;
	return @mkdir ( $dir, $mode );
}
/**
 * 获取菜单树
 *
 * @param unknown $menu        	
 */
function get_menu_tree($menu = array(), $selected = array(), $field = 'menuid') {
	foreach ( $menu as $key => $value ) {
		echo '<li id="' . $value [$field] . '" ' . (! empty ( $selected ) && (in_array ( $value ['pin'], $selected ) || in_array ( $value [$field], $selected )) ? 'class="selected"' : "") . '>' . $value ['title'];
		if (isset ( $value ['childs'] ) && is_array ( $value ['childs'] )) {
			echo '<ul>';
			get_menu_tree ( $value ['childs'], $selected, $field );
			echo '</ul></li>';
		} else {
			echo '</li>';
		}
	}
}

/**
 * 获取上传配置
 */
function get_upload_config($configid) {
	$config = array ();
	if (! empty ( $configid )) {
		$sysconfig = CF ( 'sysconfig' );
		if (! empty ( $sysconfig [3] ['maindir'] ) && isset ( $sysconfig [$configid] ) && is_array ( $sysconfig [$configid] )) {
			$config = array (
					'save_path' => $sysconfig [3] ['maindir'] . '/' . $sysconfig [$configid] ['dir'] . '/' . date ( 'Ym' ) . '/' . date ( 'd' ),
					'upload_path' => '.' . $sysconfig [3] ['maindir'] . '/' . $sysconfig [$configid] ['dir'] . '/' . date ( 'Ym' ) . '/' . date ( 'd' ),
					'allowed_types' => $sysconfig [$configid] ['allowext'],
					'max_size' => $sysconfig [$configid] ['maxsize'] * 1024,
					'file_name' => time () . rand ( 100000, 999999 ) 
			);
		}
	}
	return $config;
}

/**
 * 查询子类
 *
 * @param array $arr        	
 * @param number $id        	
 * @return multitype:array
 */
function find_child($arr, $id, $parentid = 'parentid') {
	$childs = array ();
	if (! empty ( $arr )) {
		foreach ( $arr as $k => $v ) {
			if ($v [$parentid] == $id) {
				$childs [] = $v;
			}
		}
	}
	return $childs;
}

/**
 * 递归
 *
 * @param number $root_id
 *        	开始ID
 * @param array $lists
 *        	需要递归的数据
 * @param string $loop_id
 *        	ID
 * @return NULL Ambigous multitype:array, multitype:array >
 */
function menu_tree($root_id, $lists = array(), $field = 'menuid', $parentid = 'parentid') {
	$childs = find_child ( $lists, $root_id, $parentid );
	if (empty ( $childs )) {
		return null;
	}
	foreach ( $childs as $k => $v ) {
		$rescurTree = menu_tree ( $v [$field], $lists, $field, $parentid );
		if (null != $rescurTree) {
			$childs [$k] ['childs'] = $rescurTree;
		}
	}
	return $childs;
}

/**
 * 截取指定长度的字符串(UTF-8专用 汉字和大写字母长度算1，其它字符长度算0.5)
 *
 * @param string $string:
 *        	原字符串
 * @param int $length:
 *        	截取长度
 * @param string $etc:
 *        	省略字符（...）
 * @return string: 截取后的字符串
 *        
 */
function cut_str($sourcestr, $cutlength = 80, $etc = '...') {
	$returnstr = '';
	$i = 0;
	$n = 0.0;
	$str_length = strlen ( $sourcestr ); // 字符串的字节数
	while ( ($n < $cutlength) and ($i < $str_length) ) {
		$temp_str = substr ( $sourcestr, $i, 1 );
		$ascnum = ord ( $temp_str ); // 得到字符串中第$i位字符的ASCII码
		if ($ascnum >= 252) { // 如果ASCII位高与252
			$returnstr = $returnstr . substr ( $sourcestr, $i, 6 ); // 根据UTF-8编码规范，将6个连续的字符计为单个字符
			$i = $i + 6; // 实际Byte计为6
			$n ++; // 字串长度计1
		} elseif ($ascnum >= 248) { // 如果ASCII位高与248
			$returnstr = $returnstr . substr ( $sourcestr, $i, 5 ); // 根据UTF-8编码规范，将5个连续的字符计为单个字符
			$i = $i + 5; // 实际Byte计为5
			$n ++; // 字串长度计1
		} elseif ($ascnum >= 240) { // 如果ASCII位高与240
			$returnstr = $returnstr . substr ( $sourcestr, $i, 4 ); // 根据UTF-8编码规范，将4个连续的字符计为单个字符
			$i = $i + 4; // 实际Byte计为4
			$n ++; // 字串长度计1
		} elseif ($ascnum >= 224) { // 如果ASCII位高与224
			$returnstr = $returnstr . substr ( $sourcestr, $i, 3 ); // 根据UTF-8编码规范，将3个连续的字符计为单个字符
			$i = $i + 3; // 实际Byte计为3
			$n ++; // 字串长度计1
		} elseif ($ascnum >= 192) { // 如果ASCII位高与192
			$returnstr = $returnstr . substr ( $sourcestr, $i, 2 ); // 根据UTF-8编码规范，将2个连续的字符计为单个字符
			$i = $i + 2; // 实际Byte计为2
			$n ++; // 字串长度计1
		} elseif ($ascnum >= 65 and $ascnum <= 90 and $ascnum != 73) { // 如果是大写字母 I除外
			$returnstr = $returnstr . substr ( $sourcestr, $i, 1 );
			$i = $i + 1; // 实际的Byte数仍计1个
			$n ++; // 但考虑整体美观，大写字母计成一个高位字符
		} elseif (! (array_search ( $ascnum, array (
				37,
				38,
				64,
				109,
				119 
		) ) === FALSE)) { // %,&,@,m,w 字符按１个字符宽
			$returnstr = $returnstr . substr ( $sourcestr, $i, 1 );
			$i = $i + 1; // 实际的Byte数仍计1个
			$n ++; // 但考虑整体美观，这些字条计成一个高位字符
		} else { // 其他情况下，包括小写字母和半角标点符号
			$returnstr = $returnstr . substr ( $sourcestr, $i, 1 );
			$i = $i + 1; // 实际的Byte数计1个
			$n = $n + 0.5; // 其余的小写字母和半角标点等与半个高位字符宽...
		}
	}
	if ($i < $str_length) {
		$returnstr = $returnstr . $etc; // 超过长度时在尾处加上省略号
	}
	return $returnstr;
}

/**
 * dataTable js插件 sql条件组合辅助函数
 *
 * @param array $field
 *        	字段
 * @return multitype:array
 */
function dateTable_where_order_limit($field = array()) {
	$limit = "";
	$offset = "";
	if (isset ( $_GET ['iDisplayStart'] ) && $_GET ['iDisplayLength'] != '-1') {
		$offset = intval ( $_GET ['iDisplayStart'] );
		$limit = intval ( $_GET ['iDisplayLength'] );
	}
	$orderby = "";
	if (isset ( $_GET ['iSortCol_0'] )) {
		$orderby = "";
		for($i = 0; $i < intval ( $_GET ['iSortingCols'] ); $i ++) {
            if(!empty($field [intval ( $_GET ['iSortCol_' . $i] )])) {
                if ($_GET ['bSortable_' . intval($_GET ['iSortCol_' . $i])] == "true") {
                    $orderby .= $field [intval($_GET ['iSortCol_' . $i])] . " " . mysql_real_escape_string($_GET ['sSortDir_' . $i]) . ", ";
                }
            }
		}
		
		$orderby = substr_replace ( $orderby, "", - 2 );
	}
	$where = "";
	if (isset ( $_GET ['sSearch'] ) && $_GET ['sSearch'] != "") {
		$where = "(";
		for($i = 0; $i < count ( $field ); $i ++) {
            if(!empty($field [$i])) {
                if (isset ($_GET ['bSearchable_' . $i]) && $_GET ['bSearchable_' . $i] == "true") {

                    $where .= $field [$i] . " LIKE '%" . mysql_real_escape_string($_GET ['sSearch']) . "%' OR ";
                }
            }
		}
		$where = substr_replace ( $where, "", - 3 ) . ')';
	}
	for($i = 0; $i < count ( $field ); $i ++) {
        if(!empty($field [$i])) {
            if (isset ($_GET ['bSearchable_' . $i]) && $_GET ['bSearchable_' . $i] == "true" && $_GET ['sSearch_' . $i] != '') {
                if ($where != "") {
                    $where .= " AND ";
                }
                $where .= $field [$i] . " LIKE '%" . mysql_real_escape_string($_GET ['sSearch_' . $i]) . "%' ";
            }
        }
	}
	return array (
			'where' => $where,
			'orderby' => $orderby,
			'limit' => $limit,
			'offset' => $offset 
	);
}

/**
 * 获取所有子项
 *
 * @param string $data
 *        	结构数组
 * @param string $key
 *        	id键名
 */
function find_child_str($data = array(), $key = 'id') {
	global $str;
	if (is_array ( $data ) && ! empty ( $data )) {
		foreach ( $data as $item ) {
			if (isset ( $item ['childs'] ) && is_array ( $item ['childs'] )) {
				$str .= empty ( $str ) ? $item ['programaid'] : ',' . $item ['programaid'];
				find_child_str ( $item ['childs'], $key );
			} else {
				$str .= empty ( $str ) ? $item ['programaid'] : ',' . $item ['programaid'];
			}
		}
	}
	return $str;
}

/**
 * Base64解密
 *
 * @param string $string        	
 * @return string
 */
function cucas_base64_decode($string) {
	$data = str_replace ( array (
			'-',
			'_' 
	), array (
			'+',
			'/' 
	), $string );
	$mod4 = strlen ( $data ) % 4;
	if ($mod4) {
		$data .= substr ( '====', $mod4 );
	}
	return base64_decode ( $data );
}

/**
 * Base64加密
 *
 * @param string $string        	
 * @return string
 */
function cucas_base64_encode($string) {
	$data = base64_encode ( $string );
	$data = str_replace ( array (
			'+',
			'/',
			'=' 
	), array (
			'-',
			'_',
			'' 
	), $data );
	return $data;
}



/**
 * 汇率计算
 * get_rate('CNY', 'USD'); // 人名币 => 美元
 *
 * @param string $from_Currency        	
 * @param string $to_Currency        	
 * @return Ambigous <>
 */
function get_rate($from_Currency, $to_Currency) {
	// $amount = urlencode ( $amount );
	$from_Currency = urlencode ( $from_Currency );
	$to_Currency = urlencode ( $to_Currency );
	$url = "download.finance.yahoo.com/d/quotes.html?s=" . $from_Currency . $to_Currency . "=X&f=sl1d1t1ba&e=.html";
	$ch = curl_init ();
	$timeout = 0;
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)" );
	curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
	$rawdata = curl_exec ( $ch );
	curl_close ( $ch );
	$data = explode ( ',', $rawdata );
	if (empty ( $rawdata ) || empty ( $data [1] )) {
		$data [1] = '6.25';
	}
	return $data [1];
}

/**
 * 订单生成
 *
 * @return string
 */
function build_order_no() {
	$year_code = array (
			'A',
			'B',
			'C',
			'D',
			'E',
			'F',
			'G',
			'H',
			'I',
			'J' 
	);
	return $order_sn = $year_code [intval ( date ( 'Y' ) ) - 2010] . strtoupper ( dechex ( date ( 'm' ) ) ) . date ( 'd' ) . substr ( time (), - 5 ) . substr ( microtime (), 2, 5 ) . sprintf ( '%d', rand ( 10, 99 ) );
}

/**
 * 判断时候登陆
 */
function is_login() {
	if (! (isset ( $_SESSION ['master_user_key'] ) && $_SESSION ['master_user_key'] === true)) {
		echo '<script>window.parent.location.href="/admin/core/login";</script>';
		die ();
	}
}

/**
 * 判断学生登陆
 */
function is_studentlogin($url = null) {
	if (! (isset ( $_SESSION ['student'] ['userinfo'] ))) {
		echo '<script>window.parent.location.href="/student/login?backurl='.$url.'";</script>';
		die ();
	}
}


/**
 * 判断学生登陆
 */
function is_societylogin() {
	if (! (isset ( $_SESSION ['society'] ['userinfo'] ))) {
		echo '<script>window.parent.location.href="/student/login";</script>';
		die ();
	}
}

/**
 *
 * @param string $title        	
 * @param string $keywords        	
 * @return Ambigous <string, mixed>
 */
function str_color($title = '', $keywords = '') {
	$str = $title;
	if (! empty ( $title ) && ! empty ( $keywords )) {
		$str = str_ireplace ( trim ( $keywords ), "<font color='red'><b>" . $keywords . "</b></font>", $title );
	}
	return $str;
}
function excelTime($date, $time = false)
{
	if (function_exists('GregorianToJD')) {
		if (is_numeric($date)) {
			$jd = GregorianToJD(1, 1, 1970);
			$gregorian = JDToGregorian($jd + intval($date) - 25569);
			$date = explode('/', $gregorian);
			$date_str = str_pad($date [2], 4, '0', STR_PAD_LEFT) . "-" . str_pad($date [0], 2, '0', STR_PAD_LEFT) . "-" . str_pad($date [1], 2, '0', STR_PAD_LEFT) . ($time ? " 00:00:00" : '');
			return strtotime($date_str);
		}
	} else {
		$date = $date > 25568 ? $date + 1 : 25569; /*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
		$ofs = (70 * 365 + 17 + 2) * 86400;
		$date = date("Y-m-d", ($date * 86400) - $ofs) . ($time ? " 00:00:00" : '');
	}
	return strtotime($date);
}
/**
 * 读本地汇率
 */
function get_rate_local($from_Currency, $to_Currency){
	$rate=CF('rate','',CONFIG_PATH);
	if(empty($rate)){
		//开始读汇率存到配置文件中
		$rmb_to_usd=get_rate ( 'CNY', 'USD' );
		$usd_to_rmb=get_rate ( 'USD', 'CNY' );
		$date=strtotime(date("Y-m-d H:i:s",mktime(0,0,0,date('d',time()),date('m',time()),date('Y',time()))))+3600*24;
		$data['rmb_to_usd']=$rmb_to_usd;
		$data['usd_to_rmb']=$usd_to_rmb;
		$data['date']=$date;
		$rate=CF('rate',$data,CONFIG_PATH);
		if($from_Currency=='CNY'&&$to_Currency=='USD'){
			return $rmb_to_usd;
		}elseif($from_Currency=='USD'&&$to_Currency=='CNY'){
			return $usd_to_rmb;
		}
	}else{
		if($from_Currency=='CNY'&&$to_Currency=='USD'){
			return $rate['rmb_to_usd'];
		}elseif($from_Currency=='USD'&&$to_Currency=='CNY'){
			return $rate['usd_to_rmb'];
		}
	}
}
	
/* End of file commen_helper.php */

/* Location: ./application/helpers/commen_helper.php */