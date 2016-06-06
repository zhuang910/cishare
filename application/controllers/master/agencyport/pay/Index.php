<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 申请支付
 *
 * @author zyj
 *        
 */
class Index extends Student_Basic {
	protected $item_type = null; // 类型 1 申请 2 代付费 3 接机 4 住宿
	protected $item_name = null; // Apply Trans 。。。。
	protected $ordertype = array (); // 缓存中的数据
	protected $ordernumber = null; // 订单号
	protected $order_info = array (); // 订单详情
	protected $m_usd = null; // 美元
	protected $m_rmb = null; // 人民币
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		// is_studentlogin ();
		$this->load->model ( 'student/student_model' );
		$this->load->model ( 'home/apply_model' );
		$this->load->model ( 'home/course_model' );
		$this->load->model ( 'home/pay_model' );
		$this->load->vars ( 'way', array (
				1 => 'Western Union',
				2 => 'Bank Transfer Overseas',
				3 => 'Bank Transfer In China' 
		) );
		$this->load->vars ( 'flag', array (
				1 => 'Successful Payment',
				2 => 'Paid Failed',
				3 => 'Verifying...' 
		) );
		
		$publics = CF ( 'publics', '', CONFIG_PATH );
		// 支付方式
		$this->ordertype = $publics ['ordertype'];
	}
	
	/**
	 * 主页
	 */
	function index() {
		$applyid = trim ( $this->input->get ( 'applyid' ) );
		$userid=intval(trim($this->input->get('userid')));
		// 先对传过来的字符串 进行数组排序 然后 再分割成字符串 存入数据库 这样就可以比较两个字符串是否相等了 这个是多个课程生成一个订单 现在不需要
		// $applyidOrderTemp = explode ( ',', $applyid );
		// asort ( $applyidOrderTemp );
		// $applyidOrder = implode ( ',', $applyidOrderTemp );
		
		if ($applyid) {
			// 获取总的申请费用 和 所有申请的信息 而且是 没有支付的
			$applyid = intval ( cucas_base64_decode ( $applyid ) );
			// 首先查一下支付情况
			$apply_info_state = $this->apply_model->get_apply_info ( 'id = ' . $applyid . ' AND userid = ' . $userid );
			// 已经支付成功的选手 直接跳到支付成功也去
			if ($apply_info_state ['paystate'] == 1) {
				
				$pay_success = '/master/agencyport/apply/make_paymeznt?userid='.$userid.'&applyid=' . cucas_base64_encode ( $applyid );
				echo '<script>window.location.href="' . $pay_success . ' "</script>';
				die ();
			}
			$where_apply = 'id = ' . $applyid . ' AND userid = ' . $userid . ' AND paystate != 1';
			$apply_info = $this->apply_model->get_apply_info ( $where_apply );
			
			// 先更新申请表中的申请费 查询课程的申请费
			
// 			$course = $this->course_model->get_one ( 'id = ' . $apply_info ['courseid'] );
			
// 			// 更新申请表的 申请费
// 			$this->apply_model->save_apply_info ( 'id = ' . $applyid, array (
// 					'registration_fee' => $course ['applytuition'] 
// 			) );
			
			$total = !empty($apply_info ['registration_fee'])?$apply_info ['registration_fee']:0;
			$danwei = !empty($apply_info['danwei'])?$apply_info['danwei']:1;
			
			// 查询订单表 看是否有这条数据 支付的种类很多 因此 需要 添加支付类型 这个限制
			$where_order = "userid = {$userid} AND applyid = {$applyid} AND ordertype = 1";
			// 获取订单的信息
			$result = $this->pay_model->get_apply_order_info ( $where_order );
			
			// 如果为空 就插入数据 如果不为空 但是 已经支付了 也要插入数据
			
			
			if (empty ( $result ) || (! empty ( $result ) && $result [0] ['paystate'] == 1)) {
				$max_cucasid = build_order_no ();

				$data = array (
						'ordernumber' => 'SDYI' . $max_cucasid,
						'ordertype' => 1,
						'userid' => $userid,
						'applyid' => $applyid,
						'ordermondey' => ! empty ( $total ) ? $total : 0,
						'paytype' => 0,
						'paytime' => 0,
						'paystate' => 0,
						'createtime' => time (),
						'lasttime' => time () 
				);
				
				$flags = $this->pay_model->save_apply_order_info ( null, $data );
				// 同时 更新 订单号到 申请表
				$this->apply_model->save_apply_info ( 'id = ' . $applyid, array (
						'ordernumber' => 'SDYI' . $max_cucasid 
				) );
			}
			// 获取订单数据
			$order_info_temp = $this->pay_model->get_apply_order_info ( $where_order );
			if (! empty ( $order_info_temp )) {
				$this->order_info = $order_info_temp [0];
			}
			
			$this->item_type = ! empty ( $this->order_info ['ordertype'] ) ? $this->order_info ['ordertype'] : 0;
			$this->item_name = ! empty ( $this->ordertype [$this->order_info ['ordertype']] ['name'] ) ? $this->ordertype [$this->order_info ['ordertype']] ['name'] : '';
			$this->ordernumber = ! empty ( $this->order_info ['ordernumber'] ) ? $this->order_info ['ordernumber'] : 0;
			// $money = sprintf ( "%.2f", $this->order_info ['ordermondey'] );
			$money = $total;
			// $this->m_usd = $this->item_type == 1 ? calculate_appfee ( $money ) : $money;
			// $this->m_rmb = $this->item_type == 1 ? ceil ( calculate_appfee ( $money ) * get_rate ( 'USD', 'CNY' ) ) : ceil ( $money * get_rate ( 'USD', 'CNY' ) );
			// 货币的转换
			if ($danwei == 1) {
				$this->m_usd = $money;
				$this->m_rmb = ceil ( $money * get_rate ( 'USD', 'CNY' ) );
			}
				
			if ($danwei == 2) {
				$this->m_usd = ceil ( $money * get_rate ( 'CNY', 'USD' ) );
				$this->m_rmb = $money;
			}
			
			// 两种支付的信息
			$this->returnpayeaseInter ();
			$this->returnpayeaseChina ();
			// 凭据用户 去查凭据信息
			if (! empty ( $this->order_info ['paytype'] ) && $this->order_info ['paytype'] == 3) {
				$where_credentials = "orderid = '{$this->order_info['id']}'";
				$credentials = $this->pay_model->get_credentials ( $where_credentials );
			}
		}
		// 当前url
		//$nowurl = 'http://' . $_SERVER ['SERVER_NAME'] . '/' . $this->puri;
		$this->load->view ( '/master/agencyport/pay/index_index_test', array (
				'apply_info' => ! empty ( $apply_info ) ? $apply_info : array (),
				'order_info' => ! empty ( $this->order_info ) ? $this->order_info : array (),
				'item_type' => $this->item_type,
				'item_name' => $this->item_name,
				'm_usd' => $this->m_usd,
				'm_rmb' => $this->m_rmb,
				'credentials' => ! empty ( $credentials ) ? $credentials [0] : array (),
                'userid'=>$userid
				//'nowurl' => $nowurl 
		) );
	}
	
	/**
	 * 支付方式的配置
	 */
	function returnpayeaseInter() {
		$v_ymd = date ( 'Ymd', time () ); // 订单产生日期，要求订单日期格式yyyymmdd.
		$v_mid = "5181"; // 商户编号，和首信签约后获得。测试的商户编号是250
		$v_appID = $this->ordernumber;
		$v_oid = $v_ymd . '-' . $v_mid . '-' . $v_appID; // 订单编号。订单编号的格式是yyyymmdd-商户编号-流水号，流水号可以取系统当前时间，也可以取随机数，也可以商户自己定义的订单号，自己定义的订单号必须保证每一次提交，订单号是唯一的值。
		$v_rcvname = '5181'; // 收货人姓名,建议用商户编号代替。或者是英文数字。因为首信平台的编号是gb2312的。
		$v_rcvaddr = 'cucas'; // 收货人地址，可以用常量。
		$v_rcvtel = "010-82652626"; // 收货人电话
		$v_rcvpost = "100083"; // 收货人邮编
		$v_amount = $this->m_usd; // calculate_appfee($this->money); //($uAppNum > 0) ? $pageApp["appfee"] : $pageApp["appfee"]+30; //订单金额
		$v_orderstatus = "1"; // 配货状态。0-未配齐，1-已配齐
		$v_ordername = "cucas"; // 订货人姓名
		$v_moneytype = "1"; // 币种，0-人民币，1-美元
		$v_url = "http://pay.cucas.edu.cn/payease"; // 支付完成后的实时返回地址。支付完成后实时先向这个地址做返回。在此地址下做接收银行返回的支付确认消息。详细的返回参数格式见(接口文档的第二部分或者代码示例的received1.php)
		                                            // v_md5info的算法
		$source = $v_moneytype . $v_ymd . $v_amount . $v_rcvname . $v_oid . $v_mid . $v_url; // 七个参数的拼串
		$MD5_Key = "cucaschiwestucas"; // 签约后，自定义一个16位的数字字母组合做MD5Key.发到huangyi@payeasenet.com.邮件说明您的商户编号，公司名称和密钥。testtest是测试商户编号250的密钥
		$v_md5info = bin2hex ( mhash ( MHASH_MD5, $source, $MD5_Key ) );
		
		$this->load->vars ( "v_ymd", $v_ymd );
		$this->load->vars ( "v_mid", $v_mid );
		$this->load->vars ( "v_oid", $v_oid );
		$this->load->vars ( "v_rcvname", $v_rcvname );
		$this->load->vars ( "v_rcvaddr", $v_rcvaddr );
		$this->load->vars ( "v_rcvtel", $v_rcvtel );
		$this->load->vars ( "v_rcvpost", $v_rcvpost );
		$this->load->vars ( "v_amount", $v_amount );
		$this->load->vars ( "v_orderstatus", $v_orderstatus );
		$this->load->vars ( "v_ordername", $v_ordername );
		$this->load->vars ( "v_moneytype", $v_moneytype );
		$this->load->vars ( "v_url", $v_url );
		$this->load->vars ( "v_md5info", $v_md5info );
	}
	
	/**
	 * 支付信息 国内的
	 */
	function returnpayeaseChina() {
		// 首信易支付,国内支付，相关信息---开始
		$v_ymd_1 = date ( 'Ymd', time () ); // 订单产生日期，要求订单日期格式yyyymmdd.
		$v_mid_1 = "6970"; // 商户编号，和首信签约后获得。测试的商户编号是250
		$v_appID_1 = $this->ordernumber;
		$v_oid_1 = $v_ymd_1 . '-' . $v_mid_1 . '-' . $v_appID_1; // 订单编号。订单编号的格式是yyyymmdd-商户编号-流水号，流水号可以取系统当前时间，也可以取随机数，也可以商户自己定义的订单号，自己定义的订单号必须保证每一次提交，订单号是唯一的值。
		$v_rcvname_1 = '6970'; // 收货人姓名,建议用商户编号代替。或者是英文数字。因为首信平台的编号是gb2312的。
		$v_rcvaddr_1 = 'cucas'; // 收货人地址，可以用常量。
		$v_rcvtel_1 = "010-82652626"; // 收货人电话
		$v_rcvpost_1 = "100083"; // 收货人邮编
		$v_amount_1 = $this->m_rmb; // ($uAppNum > 0) ? $pageApp["appfee"] : $pageApp["appfee"]+30; //订单金额
		$v_orderstatus_1 = "1"; // 配货状态。0-未配齐，1-已配齐
		$v_ordername_1 = "cucas"; // 订货人姓名
		$v_moneytype_1 = "0"; // 币种，0-人民币，1-美元
		$v_url_1 = "http://pay.cucas.edu.cn/payease"; // 支付完成后的实时返回地址。支付完成后实时先向这个地址做返回。在此地址下做接收银行返回的支付确认消息。详细的返回参数格式见(接口文档的第二部分或者代码示例的received1.php)
		                                              // v_md5info的算法
		$source_1 = $v_moneytype_1 . $v_ymd_1 . $v_amount_1 . $v_rcvname_1 . $v_oid_1 . $v_mid_1 . $v_url_1; // 七个参数的拼串
		$MD5_Key = "cucaschiwestucas"; // 签约后，自定义一个16位的数字字母组合做MD5Key.发到huangyi@payeasenet.com.邮件说明您的商户编号，公司名称和密钥。testtest是测试商户编号250的密钥
		$v_md5info_1 = bin2hex ( mhash ( MHASH_MD5, $source_1, $MD5_Key ) );
		
		$this->load->vars ( "v_ymd_1", $v_ymd_1 );
		$this->load->vars ( "v_mid_1", $v_mid_1 );
		$this->load->vars ( "v_oid_1", $v_oid_1 );
		$this->load->vars ( "v_rcvname_1", $v_rcvname_1 );
		$this->load->vars ( "v_rcvaddr_1", $v_rcvaddr_1 );
		$this->load->vars ( "v_rcvtel_1", $v_rcvtel_1 );
		$this->load->vars ( "v_rcvpost_1", $v_rcvpost_1 );
		$this->load->vars ( "v_amount_1", $v_amount_1 );
		$this->load->vars ( "v_orderstatus_1", $v_orderstatus_1 );
		$this->load->vars ( "v_ordername_1", $v_ordername_1 );
		$this->load->vars ( "v_moneytype_1", $v_moneytype_1 );
		$this->load->vars ( "v_url_1", $v_url_1 );
		$this->load->vars ( "v_md5info_1", $v_md5info_1 );
	}
}