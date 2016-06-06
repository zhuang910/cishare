<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 申请支付
 *
 * @author zyj
 *        
 */
class Index extends Student_Basic {
	protected $item_type = null; // 类型 1 申请 2 代付费 3 接机 4 住宿 5 押金
	protected $item_name = null; // Apply Trans 。。。。
	protected $ordertype = array (); // 缓存中的数据
	protected $ordernumber = null; // 订单号
	protected $order_info = array (); // 订单详情
	protected $m_usd = null; // 美元
	protected $m_rmb = null; // 人民币
	protected $table = array ();
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		is_studentlogin ();
		$this->load->model ( 'student/student_model' );
		$this->load->model ( 'home/apply_pa_model' );
		$this->load->model ( 'home/pay_model' );
		$this->load->model ( 'student/fee_model' );

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
		$this->table = array (
				'3' => 'pickup_info',
				'4' => 'accommodation_info',
				'5' => 'deposit_info',
				'6' => 'tuition_info' ,
				'8' => 'books_fee',
				'14'=> 'electric_pledge'
		);
	}
	
	/**
	 * 主页
	 */
	function index() {
		$applyid_flag = trim ( $this->input->get ( 'applyid' ) );
		// 先对传过来的字符串 进行数组排序 然后 再分割成字符串 存入数据库 这样就可以比较两个字符串是否相等了 这个是多个课程生成一个订单 现在不需要
		// $applyidOrderTemp = explode ( ',', $applyid );
		// asort ( $applyidOrderTemp );
		// $applyidOrder = implode ( ',', $applyidOrderTemp );
		
		if ($applyid_flag) {
			// $applyid_flag 是 组合数据 申请id 和 类型的id 3 接机 4 住宿
			
			$applyid_flag = cucas_base64_decode ( $applyid_flag );
			
			$applyid_flag_z = explode ( '-', $applyid_flag );
			$applyid = $applyid_flag_z [0]; // 申请id
			$flag_type = $applyid_flag_z [1]; // 类型 3 接机 4 住宿 5 押金 6 学费
			                                  // 首先查一下支付情况
			if ($flag_type == 5) {
				$apply_info_state = $this->apply_pa_model->get_apply_info ( 'applyid = ' . $applyid . ' AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'], $this->table [$flag_type] );
				
			}elseif($flag_type == 3){
				$apply_info_state = $this->apply_pa_model->get_apply_info ( 'id = ' . $applyid . ' AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'], $this->table [$flag_type] );
				
			}elseif($flag_type == 8){
				//书费思密达
				$apply_info_state=$this->apply_pa_model->get_apply_info ( 'term = ' . $applyid . ' AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'], $this->table [$flag_type] );
			}else {
				$apply_info_state = $this->apply_pa_model->get_apply_info ( 'order_id = ' . $applyid . ' AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'], $this->table [$flag_type] );
			}
			if(empty($apply_info_state)&&$flag_type==8){
				if($flag_type==8){
					//获取该学期的书费缴纳信息
					$book_fee_info=$this->book_fee($applyid);
				}
				//创建收支表
				$budget_arr=array(
					'userid'=>$_SESSION ['student'] ['userinfo'] ['id'],
					'budget_type'=>1,
					'type'=>$flag_type,
					'term'=>$applyid,
					'payable'=>$book_fee_info['book_money'],
					'createtime'=>time(),
					);
				$budgetid=$this->fee_model->insert_budget($budget_arr);
				//插入订单表
				$max_cucasid = build_order_no ();
				$order_arr=array(
					'budget_id'=>$budgetid,
					'createtime'=>time(),
					'lasttime'=>time(),
					'ordernumber'=>'ZUST'.$max_cucasid,
					'ordertype'=>$flag_type,
					'userid'=>$_SESSION ['student'] ['userinfo'] ['id'],
					'ordermondey'=>$book_fee_info['book_money'],
					);
				$orderid=$this->fee_model->insert_order($order_arr);

				$book=array(
					'userid'=>$_SESSION ['student'] ['userinfo'] ['id'],
					'orderid'=>$orderid,
					'budgetid'=>$budgetid,
					'term'=>$applyid,
					'book_ids'=>$book_fee_info['book_ids'],
					'last_money'=>$book_fee_info['book_money'],
					'createtime'=>time(),
					'paystate'=>0
					);
				$this->db->insert('books_fee',$book);
				$apply_info_state=$this->apply_pa_model->get_apply_info ( 'term = ' . $applyid . ' AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'], $this->table [$flag_type] );
			}
			// 已经支付成功的选手 直接跳到个人中心去 显示 支付成功
			if ($apply_info_state ['paystate'] == 1) {
				switch ($flag_type) {
					case 3 :
						$pay_success = '/' . $this->puri . '/student/student/pickuplist';
						break;
					case 4 :
						$pay_success = '/' . $this->puri . '/student/student/accommodation';
						break;
					case 5 :
						$pay_success = '/' . $this->puri . '/student/index';
						break;
					case 6 :
						$pay_success = '/' . $this->puri . '/student/student/tuition';
						break;
					case 8 :
						$pay_success = '/' . $this->puri . 'student/student/book_fee?term='.$term;
					break;
				}
				echo '<script>window.location.href="' . $pay_success . ' "</script>';
				die ();
			}
			if ($flag_type == 5) {
				$where_apply = 'applyid = ' . $applyid . ' AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] . ' AND paystate != 1';
			}elseif($flag_type==8){
				$where_apply = 'term = ' . $applyid . ' AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] . ' AND paystate != 1';
			}elseif($flag_type==14){
				$where_apply = 'order_id = ' . $applyid . ' AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'];
			} else {
				$where_apply = 'id = ' . $applyid . ' AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] . ' AND paystate != 1';
			}
			
			$apply_info = $this->apply_pa_model->get_apply_info ( $where_apply, $this->table [$flag_type] );

			if($flag_type == 6){
				// 费用
				$total = $apply_info ['tuition'];
			}elseif($flag_type == 8){
				$total = $apply_info ['last_money'];
			}elseif($flag_type == 14){
				$total = $apply_info ['last_money'];
			}else{
				// 费用
				$total = $apply_info ['registeration_fee'];
			}
			
			// 查询订单表 看是否有这条数据 支付的种类很多 因此 需要 添加支付类型 这个限制
			// 押金：applyid 指的是 申请专业表的id 也就是 押金表的applyid 因此用 $apply_info['id'] 可以找到 押金表的id 作为订单表的 applyid
			if ($flag_type == 5) {
				$where_order = "userid = {$_SESSION ['student']['userinfo'] ['id']} AND id = {$apply_info['order_id']} AND ordertype = " . $flag_type;
			}elseif($flag_type==8){
				$where_order = "id = {$apply_info['orderid']} AND userid = {$_SESSION ['student']['userinfo'] ['id']} AND ordertype = " . $flag_type;
			} else {
				$where_order = "userid = {$_SESSION ['student']['userinfo'] ['id']} AND ordertype = " . $flag_type;
			}
			// 获取订单的信息
			$result = $this->pay_model->get_apply_order_info ( $where_order );
			
			// 如果为空 就插入数据
			if ($flag_type == 5) {
				$applyid = $apply_info ['id'];
			}
			if (empty ( $result )) {

				$max_cucasid = build_order_no ();
				$data = array (
						'ordernumber' => 'ZUST' . $max_cucasid,
						'ordertype' => $flag_type,
						'userid' => $_SESSION ['student'] ['userinfo'] ['id'],
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
				$this->apply_pa_model->save_apply_info ( 'id = ' . $applyid, array (
						'ordernumber' => 'ZUST' . $max_cucasid 
				), $this->table [$flag_type] );
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
			// $this->m_rmb = $this->item_type == 1 ? ceil ( calculate_appfee ( $money ) * get_rate_local ( 'USD', 'CNY' ) ) : ceil ( $money * get_rate_local ( 'USD', 'CNY' ) );
			// 货币的转换
			if ($flag_type == 6) {
				$this->m_usd = ceil ( $money * get_rate_local ( 'CNY', 'USD' ) );
				$this->m_rmb = $money;
			}elseif($flag_type == 8){
				$this->m_usd = ceil ( $money * get_rate_local ( 'CNY', 'USD' ) );
				$this->m_rmb = $money;
			}else {
				//如果是住宿订单的时候
				if($flag_type==4){
					//每天的费用乘计算住的天数来计算总共的价钱
					$apply_info['accendtime']=intval($apply_info['accendtime']);
					$money=$apply_info['accendtime']*$money;
				
						$this->m_usd = ceil ( $money * get_rate_local ( 'CNY', 'USD' ) );
						$this->m_rmb = $money;
					//判断用不用交住宿押金   如果用就把住宿押金交上
					$is_yajin=CF('acc_pledge','',CONFIG_PATH);
					// var_dump($is_yajin);exit;
					if($is_yajin['acc_pledge']=='yes'){
						if ($is_yajin ['acc_pledgeway'] == 'acc_pledgermb') {
							$pledge_money=ceil ($is_yajin['acc_pledgemoney']  * get_rate_local ( 'CNY', 'USD' ) );
							$this->m_usd +=$pledge_money;
							$this->m_rmb +=$is_yajin['acc_pledgemoney'] ;
						}
						if ($is_yajin ['acc_pledgeway']  == 'acc_pledgeusd') {
							$pledge_money= ceil ( $is_yajin['acc_pledgemoney'] * get_rate_local ( 'USD', 'CNY' ) );
							$this->m_usd +=$is_yajin['acc_pledgemoney'];
							$this->m_rmb +=$pledge_money ;
						}
					}
				}else{
					// if (strtoupper($apply_info ['danwei'])  == 'USD') {
					// 	$this->m_usd = $money;
					// 	$this->m_rmb = ceil ( $money * get_rate_local ( 'USD', 'CNY' ) );
					// }
					// if (strtoupper($apply_info ['danwei']) == 'RMB') {
					// //现在默认都是人民币
						$this->m_usd = ceil ( $money * get_rate_local ( 'CNY', 'USD' ) );
						$this->m_rmb = $money;
					// }
				}
			}
			
			// 两种支付的信息
			$this->returnpayeaseInter ();
			$this->returnpayeaseChina ();
			// 凭据用户 去查凭据信息
			if (! empty ( $this->order_info ['paytype'] ) && $this->order_info ['paytype'] == 3) {
				$where_credentials = "orderid = '{$this->order_info['id']}' AND ordertype = 6";
				$credentials = $this->pay_model->get_credentials ( $where_credentials );
				
				
			}
		}
		// 当前url
		$nationality=CF('public','',CACHE_PATH);
		// $nowurl = 'http://' . $_SERVER ['SERVER_NAME'] . '/' . $this->puri;
		$this->load->view ( 'pay_pa/index_index_test', array (
				'apply_info' => ! empty ( $apply_info ) ? $apply_info : array (),
				'order_info' => ! empty ( $this->order_info ) ? $this->order_info : array (),
				'item_type' => $this->item_type,
				'item_name' => $this->item_name,
				'm_usd' => $this->m_usd,
				'm_rmb' => $this->m_rmb,
				'credentials' => ! empty ( $credentials ) ? $credentials [0] : array () ,
				'nationality'=>!empty($nationality['global_country'])?$nationality['global_country']:array()
		// 'nowurl' => $nowurl
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

	 /**
     * [book_fee 交书费页面]
     * @return [type] [description]
     */
    function book_fee($term){
    	$student_info=$this->db->get_where('student','userid = '.$_SESSION ['student'] ['userinfo'] ['id'])->row_array();
    	if(!empty($student_info['majorid'])){
    		$majorid=$student_info['majorid'];
    	}else{
    		$majorid=$student_info['major'];
    	}
    	//获取专业信息
    	$major_info=$this->db->get_where('major','id = '.$majorid)->row_array();
    	if(!empty($term)){
    		//查询所有的书籍
    		//获取该专业的所有书籍
			$course_info=$this->pay_model->get_major_course($major_info['id']);
				//筛选本学期课程
			if(!empty($course_info)){
				foreach ($course_info as $k => $v) {
					$shanchu=0;
					$term_start=json_decode($v['term_start'],true);
					if(!empty($term_start)){
						foreach ($term_start as $key => $value) {
						
							if($value==$term){
								$shanchu=1;
							}
						}
					}
					if($shanchu==0){
						unset($course_info[$k]);
					}
				}
			}
			$book=array();
			$bookids='';
			$money=0;
			if(!empty($course_info)){
				foreach ($course_info as $k => $v) {
					//获取该课程的书籍
					$book_info=$this->pay_model->get_course_book($v['courseid']);
					if(!empty($book_info)){
						foreach ($book_info as $kk => $vv) {
							//获取书籍信息
							$book[$vv['booksid']]=$this->pay_model->get_book_info($vv['booksid']);
							$money+=$book[$vv['booksid']]['price'];
							$bookids.=$vv['booksid'].',';
						}
					}
				}
			}
    	}
    
    		return array (
				'book_ids'=>!empty($bookids)?$bookids:'',
				'book_money'=>!empty($money)?$money:0,
				'term'=>!empty($term)?$term:''
			) ;
    }
}