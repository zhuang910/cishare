<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}

/**
 * 申请管理
 *
 * @author Laravel
 *        
 */
class Otherscholarship extends Master_Basic {
	protected $scholorshipapply = array();
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/scholarship/';
		$this->load->model ( $this->view . 'appmanager_model', 'app' );
		$this->load->model (  'master/charge/pay_model' );
		$this->load->model (  'master/scholarship/change_scholarship_status_model' );
		
		
	}
	function index() {
		$label_id = $this->input->get ( 'label_id' );
		$label_id = ! empty ( $label_id ) ? $label_id : '0';
// 		if ($label_id == 1) {
// 			$where = '(applyscholarship_info.state = 1 OR applyscholarship_info.state=3) AND applyscholarship_info.type = 2';
// 		} else {
// 			$where = 'applyscholarship_info.type = 2 AND applyscholarship_info.state = ' . $label_id . '';
// 		}
		$where = 'applyscholarship_info.type = 2 AND applyscholarship_info.state = ' . $label_id . '';
		// 根据状态获得申请列表信息
		$lists = $this->app->get_app ( $where );
		
		$publics = CF ( 'publics', '', CONFIG_PATH );
		// 获取状态
		$app_state = '';
		foreach ( $publics ['app_state'] as $key => $value ) {
			$app_state [$key] = $value;
		}
		
		
		// 读取国籍缓存
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		$country = '';
		
		foreach ( $nationality as $key => $value ) {
			$country [$key] = $value;
		}
		
		
		$data = array (
				'label_id' => $label_id,
				'lists' => $lists,
				'app_state' => $app_state,
				'country' => $country,
		);
		$this->load->view ( 'master/scholarship/app_index', $data );
	}
	
	//编辑备注信息
	function edit_app_remark_info() {
	
		$id = trim ( $this->input->post ( 'pk' ) );         //获取记录ID值
		$key = trim ( $this->input->post ( 'name' ) );		//获取记录字段值
		$value = trim ( $this->input->post ( 'value' ) ); 	//获取记录更新值
		$value_format = explode('^',$id);					//分割字符串，获得^号后值，用来判断是否是日期格式
		
		if($value_format[1]=='date'){
			$value=strtotime($value);
		}
		$id=$value_format[0];
		
	
		
	
		//执行更新
		$result = $this->db->update('applyscholarship_info',array('remark' => !empty($value)?$value:''),'id = '.$id);
	
		if ($result) {
		
			ajaxReturn ( '', '更新成功', 1 );
		}else{
			ajaxReturn ( '', '更新失败，请重试', 0 );
		}
	}
	


	//结束申请
	function over_app() {
		$id = intval ( $this->input->get ( 'id' ) );
		if (! empty ( $id )) {
			$result = $this->db->update('applyscholarship_info',array('state' => 6),'id = '.$id);
			if($result){
				ajaxReturn('','',1);
			}else{
				ajaxReturn('','',0);
			}
		}
		ajaxReturn ( '', '缺少必要参数', 0 );
	}
	
	/**
	 * 申请详情页
	 */
	function app_detail() {
		$appid = $this->input->get ( 'id' ); // 获取标签识别查询条件
		$lists = $this->app->get_one_app_detail ( $appid ); // 获取符合标签识别变量的申请数据
		
		$lists_log = $this->app->get_logs ( 'type = 2 AND appid = '.$appid ); // 获取符合标签识别变量的日志数据
		
		$publics = CF ( 'publics', '', CONFIG_PATH );
		
		// 获取状态
		$app_state = '';
		foreach ( $publics ['app_state'] as $key => $value ) {
			$app_state [$key] = $value;
		}
		
		// 获取课程周期
		$programa_unit = '';
		
		foreach ( $publics ['programa_unit'] as $key => $value ) {
			$programa_unit [$key] = $value;
		}
		
		// 读取国籍缓存
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		$country = '';
		
		foreach ( $nationality as $key => $value ) {
			$country [$key] = $value;
		}
		
		//获取管理员
		
		$this->load->view ( 'master/enrollment/appmanager/app_detail', 		// 指定模板
		array (
				'lists' => $lists, // 查询申请结果对象数组
				'lists_log' => $lists_log, // 查询日志结果对象数组
				'country' => $country, // 国籍数组
				'apply_state' => $app_state, // 申请状态数组
				'apply_operator' => 1, // 申请操作人数组
				'programa_unit' => $programa_unit 
		) );
	}
	// 录取处理-----发送offer的处理
	function app_offer() {
		$label_id = $this->input->get ( 'label_id' );
		$label_id = ! empty ( $label_id ) ? $label_id : '7';
		$ispageoffer = $this->input->get ( 'ispageoffer' );//是否发送e-offer
		$ispageoffer = ! empty ( $ispageoffer ) ? $ispageoffer : '-1';
		$wait = $this->input->get ( 'sendtype' );//纸质offer 发送状态
		//$wait = ! empty ( $wait ) ? $wait : '';
		$cstatus = $this->input->get ( 'cstatus' );//地址确认状态
		//$cstatus = !empty($cstatus)?$cstatus:'-1';
		if($label_id == 7){
			$where = 'apply_info.state = 7';
			if($ispageoffer){
				$where.=' AND apply_info.e_offer_status = '.$ispageoffer;
			}
			
			if($wait){
				$where.=' AND apply_info.pagesend_status=' . $wait;
			}
			
			if($cstatus){
				$where.=' AND apply_info.addressconfirm=' . $cstatus;
			}
			
		}
		
// 		if ($label_id == 7 && $ispageoffer == 1) {
// 			$where = ' AND apply_info.e_offer_status = 1 AND apply_info.pagesend_status=' . $wait . '';
// 			AND apply_info.addressconfirm=' . $cstatus . '
// 		} else {
// 			//$where = 'apply_info.state = 7 AND major.ispageoffer=' . $ispageoffer . '';
// 			$where = 'apply_info.state = 7 AND apply_info.e_offer_status= -1';
// 		}
		
		// 根据状态获得申请列表信息
		$lists = $this->app->get_app ( $where );
		/*
		 * 通过申请id去查看offer中的发生状态
		 */
		
		$publics = CF ( 'publics', '', CONFIG_PATH );
		
		// 获取状态
		$app_state = '';
		foreach ( $publics ['app_state'] as $key => $value ) {
			$app_state [$key] = $value;
		}
		
		// 获取课程周期
		$programa_unit = '';
		
		foreach ( $publics ['programa_unit'] as $key => $value ) {
			$programa_unit [$key] = $value;
		}
		
		// 读取国籍缓存
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		$country = '';
		
		foreach ( $nationality as $key => $value ) {
			$country [$key] = $value;
		}
		
		$data = array (
				'label_id' => $label_id,
				'lists' => $lists,
				'app_state' => $app_state,
				'programa_unit' => $programa_unit,
				'country' => $country,
				'ispageoffer' => $ispageoffer,
				'sendtype' => $wait,
				'cstatus' => $cstatus 
		);
		
		if ($wait == - 1) {
			$this->load->view ( 'master/enrollment/appmanager/app_offer_1', $data );
		} else {
			$this->load->view ( 'master/enrollment/appmanager/app_offer', $data );
		}
	}
	
	/**
	 * 入学确认
	 */
	function app_finish() {
		$label_id = $this->input->get ( 'label_id' ); // 获取标签识别查询条件
		$label_id = ! empty ( $label_id ) ? $label_id : 7; // 此时由于前面的流程已经执行，入学确认则从录取流程进行
		                                                   
		// 根据条件进行查询
		if ($label_id == 7) {
			$where = 'apply_info.state = ' . $label_id . ' AND  apply_info.e_offer_status=1';
		} else {
			$where = 'apply_info.state = ' . $label_id . '';
		}
		
		/*
		 * 为两种显示形式准备数据结果集 $label_id=3；为发送E-OFFER
		 */
		$lists = $this->app->get_app ( $where );
		
		$publics = CF ( 'publics', '', CONFIG_PATH );
		
		// 获取状态
		$app_state = '';
		foreach ( $publics ['app_state'] as $key => $value ) {
			$app_state [$key] = $value;
		}
		
		// 获取课程周期
		$programa_unit = '';
		
		foreach ( $publics ['programa_unit'] as $key => $value ) {
			$programa_unit [$key] = $value;
		}
		
		// 读取国籍缓存
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		$country = '';
		
		foreach ( $nationality as $key => $value ) {
			$country [$key] = $value;
		}
		
		$data = array (
				'lists' => $lists,
				'label_id' => $label_id,
				'programa_unit' => $programa_unit,
				'country' => $country 
		);
		/*
		 * $this->_view ( //根据标签识别变量调用不同模板 array( 'lists' 		=> $lists,								//查询结果对象数组 'country' 		=> $global_country,						//国籍数组 'apply_state' 	=> $data,				//申请状态数组 'delivery' 		=> $global_delivery,					//邮寄方式数据 'send' 		=> $global_send,							//发送方式数组 'label_id' 		=> $label_id							//显示当前标签识别变量 ) );
		 */
		$this->load->view ( 'master/enrollment/appmanager/confirm_app.php', $data );
	}
	
	/**
	 * 审核资料
	 */
	function check_info() {
		$appid = $this->input->get ( 'id' );
		$result = $this->app->get_app_infos ( $appid );
		$arr2 = array ();
		$arr3 = array ();
		if (! empty ( $result )) {
			foreach ( $result as $k1 => $v1 ) {
				$arr1 = explode ( '_', $v1->keyid );
				// 数值关系
				$arr2 [$arr1 [2]] = $v1->value;
				$arr3 [$arr1 [1]] [] = $arr1 [2];
			}
		}
		// apply_block
		$info = $this->app->get_apply_block ();
		foreach ( $info as $key => $val ) {
			$info1 [$val->id] = $val->title;
		}
		
		// apply_form
		$infos = $this->app->get_apply_form ();
		
		foreach ( $infos as $key => $val ) {
			$info2 [$val->id] ['title'] = $val->title;
			$info2 [$val->id] ['type'] = $val->type;
			$info2 [$val->id] ['name'] = $val->name;
		}
		
		$infom = $this->app->get_apply_form_item ();
		foreach ( $infom as $key => $val ) {
			$info3 [$val->formid] [$val->value] = $val->formtitle;
		}
		// var_dump($info2);
		
		// 读取国籍缓存
		$nationality = CF ( 'nationality' );
		
		$country = '';
		
		foreach ( $nationality as $key => $value ) {
			$country [$key] = $value;
		}
		
		$data = array (
				'arr2' => $arr2,
				'arr3' => $arr3,
				'info1' => $info1,
				'info2' => $info2,
				'info3' => $info3,
				'country' => $country,
				'appid' => $appid 
		);
		$this->load->view ( '/appmanager/check_info.php', $data );
	}
	
	/**
	 * 个人审核状态下进行申请流程的操作
	 */
	function check_apply_flow() {
		$appid = $this->input->get ( 'id' );
		$label = $this->input->get ( 'label_id' );
		$label = ! empty ( $label ) ? $label : '';
		// 获得申请id和当前所处的状态
		$data = array (
				'state' => $label 
		);
		if ($label == 2) {
			$action = '资料被打回';
			$tips = '资料被打回';
		} elseif ($label == 4) {
			$action = '拒绝';
			$tips = '拒绝';
		} elseif ($label == 5) {
			$action = '调剂';
			$tips = '调剂';
		} elseif ($label == 6) {
			$action = '预录取';
			$tips = '预录取';
		} elseif ($label == 7) {
			$action = '预录取';
			$tips = '预录取';
		}
		$lists = $this->app->update_app_flow_status ( $appid, $data, $action, $tips );
	}
	
	/**
	 * 附件下载
	 */
	function attach_download() {
		$id = $this->input->get ( 'id' );
		$this->load->library ( 'zip' );
		// $lists = $this->app->get_attach ( $id );
		$lists = $this->db->select ( '*' )->get_where ( 'apply_attachment_info', 'applyid = ' . $id )->result_array ();
		if (! empty ( $lists )) {
			foreach ( $lists as $k => $v ) {
				$data = file_get_contents ( $_SERVER ['DOCUMENT_ROOT'] . $v ['url'] );
				$name = mb_convert_encoding ( $k . $v ['truename'], 'GBK', 'UTF-8' );
				$this->zip->add_data ( $name, $data );
			}
			
			$filezip = $this->zip->get_zip ( 'my_backup.zip' );
			$this->load->helper ( 'download' );
			
			//获取 信息 
			$file_name_all = $this->db->select('*')->get_where('apply_info','id = '.$id)->row();
			if(!empty($file_name_all)){
				$file_name_user = $this->db->select('*')->get_where('student_info','id = '.$file_name_all->userid)->row();
				$file_name = '';
				$file_name .=!empty($file_name_user->enname)?$file_name_user->enname:'';
				$file_name .=!empty($file_name_user->passport)?'-'.$file_name_user->passport:'-';
				$file_name .=!empty($file_name_user->email)?'-'.$file_name_user->email:'';
			}
			
			if(empty($file_name)){
				$file_name = 'ZUST';
			}
			force_download ( $file_name.'.zip', $filezip );
		}else{
			echo '<script>alert("无数据");window.history.go(-1)</script>';
			die();
		}
	}
	
	/**
	 * 申请表下载
	 */
	function apply_form_download() {
		$appid = $this->input->get ( 'id' );
		$result = $this->app->get_app_infos ( $appid );
		$arr2 = array ();
		if (! empty ( $result )) {
			foreach ( $result as $k1 => $v1 ) {
				$arr1 = explode ( '_', $v1->keyid );
				// 数值关系
				$arr2 [$arr1 [2]] = $v1->value;
			}
		}
		
		// apply_form
		$infos = $this->app->get_apply_form ();
		
		foreach ( $infos as $key => $val ) {
			$info2 [$val->id] ['title'] = $val->title;
			$info2 [$val->id] ['type'] = $val->type;
			$info2 [$val->id] ['name'] = $val->name;
		}
		
		// 读取国籍缓存
		$nationality = CF ( 'nationality' );
		
		$country = '';
		
		foreach ( $nationality as $key => $value ) {
			$country [$key] = $value;
		}
		
		$data = array (
				'arr2' => $arr2,
				'info2' => $info2,
				'country' => $country,
				'appid' => $appid 
		);
		
		$html = $this->load->view ( '/appmanager/apply_form.php', $data, true );
		
		header ( "Content-Type:application/msword" );
		header ( "Content-Disposition:attachment;filename=文档.doc" );
		header ( "Pragma:no-cache" );
		header ( "Expires:0" );
		echo $html;
	}
	
	/**
	 * 凭据用户
	 */
	function proof() {
		$publics = CF ( 'publics', '', CONFIG_PATH );
		foreach ( $publics ['programa_unit'] as $key => $value ) {
			$programa_unit [$key] = $value;
		}
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		// 凭据表 找到 usersid orderid applyid
		// 先获取 凭据表的信息
		$proof_all = $this->app->get_proof ();
		// 用户信息
		if (! empty ( $proof_all )) {
			foreach ( $proof_all as $k => $v ) {
				$userid [] = $v ['userid'];
				// 订单id
				$orderid [] = $v ['orderid'];
			}
            if(!empty($userid)){
                $where_user = 'id IN (' . implode ( ',', $userid ) . ')';
            }else{
                $where_user = 'id =0';
            }
			$userinfo_all = $this->db->get_where ( 'student_info', $where_user )->result_array ();
			foreach ( $userinfo_all as $key => $val ) {
				$userinfo [$val ['id']] = $val;
			}
			
			// 订单信息
			$where_order = 'id IN (' . implode ( ',', $orderid ) . ')';
			$order_all = $this->db->get_where ( 'apply_order_info', $where_order )->result_array ();
			foreach ( $order_all as $ok => $ov ) {
				$orderinfo [$ov ['id']] = $ov;
				$applyid [] = $ov ['applyid'];
			}
			
			// 获取课程信息
			$course_all = $this->db->get_where ( 'major', 'id > 0 AND state = 1' )->result_array ();
			foreach ( $course_all as $ck => $cv ) {
				$course [$cv ['id']] = $cv;
			}
			
			// 申请表的信息
			$where_apply = 'id IN (' . implode ( ',', $applyid ) . ')';
			$apply_all = $this->db->get_where ( 'apply_info', $where_apply )->result_array ();
			foreach ( $apply_all as $ak => $av ) {
				$applyinfo [$av ['id']] = $av;
				$applyinfo [$av ['id']] ['course'] = $course [$av ['courseid']];
			}
			
			// 订单信息 和 申请表信息的组合
			foreach ( $orderinfo as $okey => $oval ) {
				$orderinfo [$okey] ['apply'] = $applyinfo [$oval ['applyid']];
			}
		}
		
		$this->load->view ( 'master/enrollment/appmanager/proof.php', array (
				'userinfo' => ! empty ( $userinfo ) ? $userinfo : array (),
				'proof_all' => ! empty ( $proof_all ) ? $proof_all : array (),
				'orderinfo' => ! empty ( $orderinfo ) ? $orderinfo : array (),
				'applyinfo' => ! empty ( $applyinfo ) ? $applyinfo : array (),
				'course' => ! empty ( $course ) ? $course : array (),
				'programa_unit' => ! empty ( $programa_unit ) ? $programa_unit : array (),
				'nationality' => $nationality 
		) );
	}
	
	/**
	 * 查看凭据
	 */
	function editproof() {
		$id = $this->input->get ( 'id' );
		$file = $this->db->get_where ( 'credentials', 'id = ' . $id )->result_array ();
		$html = $this->load->view ( 'master/enrollment/appmanager/editproof.php', array (
				'img' => ! empty ( $file [0] ['file'] ) ? $file [0] ['file'] : '' 
		), true );
		
		ajaxReturn ( $html, '', 1 );
	}
	
	/**
	 * 凭据通过
	 */
	function goproof() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		$orderid = intval ( trim ( $this->input->get ( 'orderid' ) ) );
		$applyid = intval ( trim ( $this->input->get ( 'applyid' ) ) );
		$state = intval ( trim ( $this->input->get ( 'state' ) ) );
		$userid = intval ( trim ( $this->input->get ( 'userid' ) ) );
		$this->load->library ( 'sdyinc_email' );
		if (! empty ( $id ) && ! empty ( $orderid ) && ! empty ( $applyid ) && ! empty ( $state ) && ! empty ( $userid )) {
			$flag1 = $this->db->update ( 'credentials', array (
					'state' => $state 
			), 'id = ' . $id );
			
			$flag2 = $this->db->update ( 'apply_info', array (
					'paystate' => $state 
			), 'id = ' . $applyid );
			
			$flag3 = $this->db->update ( 'apply_order_info', array (
					'paystate' => $state 
			), 'id = ' . $orderid );
			
			// 查用户
			$user = $this->db->get_where ( 'student_info', 'id = ' . $userid )->result_array ();
			// 查 订单
			$order = $this->db->get_where ( 'apply_order_info', 'id = ' . $orderid )->result_array ();
			// 产申请
			$apply = $this->db->get_where ( 'apply_info', 'id = ' . $applyid )->result_array ();
			// 查课程
			$course = $this->db->get_where ( 'major', 'id = ' . $apply [0] ['courseid'] )->result_array ();
			$email = $user [0] ['email'];
			
			$usd = $order [0] ['ordermondey'];
			$name = $course [0] ['name'];
			if ($state == 1) {
				$a = 27;
				$operation = '通过';
			} else {
				$a = 28;
				$operation = '不通过';
			}
		
			$val_arr = array (
					'email' => ! empty ( $email ) ? $email : '',
					'usd' => ! empty ( $usd ) ? $usd : '',
					'name' => ! empty ( $name ) ? $name : '' 
			);
			$MAIL = new sdyinc_email ();
			$MAIL->dot_send_mail ( $a,$email,$val_arr);
			if ($flag1 && $flag2 && $flag3) {
				// 写入日志
				
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了(申请)凭据用户' .$email . '的信息为'.$operation,
						'ip' => get_client_ip (),
						'lasttime' => time (),
						'type' => 6
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
				
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
		
	}
	/**
	 * 不通过
	 */
	function unproof() {
		$id = $this->input->get ( 'id' );
		$orderid = $this->input->get ( 'orderid' );
		$applyid = $this->input->get ( 'applyid' );
		$userid = $this->input->get ( 'userid' );
		$courseid = $this->input->get ( 'courseid' );
		$flag1 = $this->db->update ( 'credentials', array (
				'state' => 2 
		), 

		'id = ' . $id );
		$flag2 = $this->db->update ( 'apply_order_info', array (
				'paytype' => 3,
				'paystate' => 2 
		), 

		'id = ' . $orderid );
		$flag3 = $this->db->update ( 'apply_info', array (
				'paytype' => 3,
				'paystate' => 2,
				'isproof' => 1 
		), 

		'id = ' . $applyid );
		
		// 查用户
		$user = $this->db->get_where ( 'student_info', 'id = ' . $userid )->result_array ();
		// 查 订单
		$order = $this->db->get_where ( 'apply_order_info', 'id = ' . $orderid )->result_array ();
		// 查课程
		$course = $this->db->get_where ( 'major', 'id = ' . $courseid )->result_array ();
		$email = $user [0] ['email'];
		$title = 'Payment failed';
		$usd = $order [0] ['ordermondey'];
		$name = $course [0] ['title'];
		$html = $this->load->view ( 'master/enrollment/mail/pay_success_email.php', array (
				'email' => ! empty ( $email ) ? $email : '',
				'name' => ! empty ( $name ) ? $name : '' 
		), true );
		$this->_send_email ( $email, $title, $html );
		
		if ($flag1 && $flag2 && $flag3) {
			
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 申请结束
	 */
	function app_over() {
		$where = $where = 'apply_info.state = 9';
		// 根据状态获得申请列表信息
		$lists = $this->app->get_app ( $where );
		
		$publics = CF ( 'publics', '', CONFIG_PATH );
		
		// 获取状态
		$app_state = '';
		foreach ( $publics ['app_state'] as $key => $value ) {
			$app_state [$key] = $value;
		}
		
		// 获取课程周期
		$programa_unit = '';
		
		foreach ( $publics ['programa_unit'] as $key => $value ) {
			$programa_unit [$key] = $value;
		}
		
		// 读取国籍缓存
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		$country = '';
		
		foreach ( $nationality as $key => $value ) {
			$country [$key] = $value;
		}
		
		$data = array (
				'lists' => $lists,
				'programa_unit' => $programa_unit,
				'country' => $country 
		);
		$this->load->view ( 'master/enrollment/appmanager/app_over', $data );
	}
	
	/**
	 * 所有申请
	 */
	function app_allof() {
		$where = 'apply_info.id > 0';
		// 根据状态获得申请列表信息
		$lists = $this->app->get_app ( $where );
		
		$publics = CF ( 'publics', '', CONFIG_PATH );
		
		// 获取状态
		$app_state = '';
		foreach ( $publics ['app_state'] as $key => $value ) {
			$app_state [$key] = $value;
		}
		
		// 获取课程周期
		$programa_unit = '';
		
		foreach ( $publics ['programa_unit'] as $key => $value ) {
			$programa_unit [$key] = $value;
		}
		
		// 读取国籍缓存
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		$country = '';
		
		foreach ( $nationality as $key => $value ) {
			$country [$key] = $value;
		}
		
		$data = array (
				'lists' => $lists,
				'app_state' => $app_state,
				'programa_unit' => $programa_unit,
				'country' => $country 
		);
		$this->load->view ( 'master/enrollment/appmanager/app_allof', $data );
	}
	
	/**
	 * 打印通知书确定信息
	 */
	function print_offter() {
		$nationality = CF ( 'nationality' );
		$applyid = intval ( trim ( $this->input->get ( 'id' ) ) );
		if ($applyid) {
			$where = 'apply_info.id = ' . $applyid;
			$result = $this->app->get_app ( $where );
		}
		
		$html = $this->load->view ( '/appmanager/print_offter', array (
				'result' => ! empty ( $result ) ? $result [0] : array (),
				'nationality' => ! empty ( $nationality ) ? $nationality : array () 
		), true );
		ajaxReturn ( $html, '', 1 );
	}
	
	/**
	 * 保存通知书
	 */
	function save_print_offter() {
		// Include the main TCPDF library (search for installation path).
		require_once $_SERVER ['DOCUMENT_ROOT'] . '/public/tcpdf/tcpdf.php';
		
		// create new PDF document
		$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
		
		// set document information
		$pdf->SetCreator ( PDF_CREATOR );
		$pdf->SetAuthor ( '' );
		$pdf->SetTitle ( '' );
		$pdf->SetSubject ( '' );
		$pdf->SetKeywords ( '' );
		
		// set default header data
		$pdf->SetHeaderData ( '', '', '', '', array (
				0,
				64,
				255 
		), array (
				0,
				64,
				128 
		) );
		$pdf->setFooterData ( array (
				0,
				64,
				0 
		), array (
				0,
				64,
				128 
		) );
		
		// set header and footer fonts
		$pdf->setHeaderFont ( Array (
				PDF_FONT_NAME_MAIN,
				'',
				PDF_FONT_SIZE_MAIN 
		) );
		$pdf->setFooterFont ( Array (
				PDF_FONT_NAME_DATA,
				'',
				PDF_FONT_SIZE_DATA 
		) );
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont ( PDF_FONT_MONOSPACED );
		
		// set margins
		$pdf->SetMargins ( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );
		$pdf->SetHeaderMargin ( PDF_MARGIN_HEADER );
		$pdf->SetFooterMargin ( PDF_MARGIN_FOOTER );
		
		// set auto page breaks
		$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
		
		// set image scale factor
		$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
		
		// set some language-dependent strings (optional)
		if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
			require_once (dirname ( __FILE__ ) . '/lang/eng.php');
			$pdf->setLanguageArray ( $l );
		}
		
		// ---------------------------------------------------------
		
		// set default font subsetting mode
		$pdf->setFontSubsetting ( true );
		
		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		$pdf->SetFont ( 'cid0cs', '', 14, '', true );
		
		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage ();
		
		// set text shadow effect
		$pdf->setTextShadow ( array (
				'enabled' => true,
				'depth_w' => 0.2,
				'depth_h' => 0.2,
				'color' => array (
						196,
						196,
						196 
				),
				'opacity' => 1,
				'blend_mode' => 'Normal' 
		) );
		
		// Set some content to print
		
		$nationality = CF ( 'nationality' );
		$data = $this->input->post ();
		$this->lang->load ( 'public', 'english' );
		$this->load->helper ( 'language' );
		if (! empty ( $data ['type'] ) && $data ['type'] == 1) {
			
			$html = $this->load->view ( '/appmanager/offter_short', array (
					'data' => ! empty ( $data ) ? $data : array (),
					'nationality' => ! empty ( $nationality ) ? $nationality : array () 
			), true );
		} else {
			$html = $this->load->view ( '/appmanager/offter_bfsu', array (
					'data' => ! empty ( $data ) ? $data : array (),
					'nationality' => ! empty ( $nationality ) ? $nationality : array () 
			), true );
		}
		
		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell ( 0, 0, '', '', $html, 0, 1, 0, true, '', true );
		
		// ---------------------------------------------------------
		
		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output ( 'example_001.pdf', 'I' );
		
		// ============================================================+
		// END OF FILE
		// ============================================================+
	}
	
	/**
	 * 生成二维码
	 */
	function made_qr() {
		$id = trim ( $this->input->get ( 'id' ) );
		if (! empty ( $id )) {
			$lists = $this->app->get_app ( 'apply_info.id = ' . $id );
			// var_dump($id);
			require_once $_SERVER ['DOCUMENT_ROOT'] . '/public/phpqrcode/phpqrcode.php';
			$value = 'http://chinese.bfsu.edu.cn/made_qr/fill_form?applyid=' . authcode ( $id, 'ENCODE' );
			// $value = 'http://bfsu_xs.com/made_qr/fill_form?applyid='.authcode($id,'ENCODE');
			// $name = ! empty ( $lists [0]->enname ) ? $lists [0]->enname : '';
			// $idnum = ! empty ( $lists [0]->idnum ) ? $lists [0]->idnum : '';
			// $coursename = ! empty ( $lists [0]->name ) ? $lists [0]->name : '';
			
			// $value .= '姓名:' . $name;
			// $value .= '学号:' . $idnum;
			// $value .= '课程名:' . $coursename;
			// var_dump($value);
			// 引入
			$dir = $_SERVER ['DOCUMENT_ROOT'] . '/uploads/work/' . date ( 'Y' ) . '/' . date ( 'md' ) . '/';
			if (! file_exists ( $dir )) {
				mk_dir ( $dir );
			}
			// 生成二维码的图片路径
			$filename = $dir . $lists [0]->userid . '.jpg';
			$savepath = '/uploads/qr/' . date ( 'Y' ) . '/' . date ( 'md' ) . '/' . $lists [0]->userid . '.jpg';
			$this->db->update ( 'apply_info', array (
					'qrcode' => $savepath 
			), 'id = ' . $id );
			/*
			 * $errorCorrectionLevel表示纠错级别， 纠错级别越高，生成图片会越大。 L水平 7%的字码可被修正 M水平 15%的字码可被修正 Q水平 25%的字码可被修正 H水平 30%的字码可被修正Size表示图片每个黑点的像素。
			 */
			$errorCorrectionLevel = "L";
			/*
			 * 点的大小：1到10 参数$matrixPointSize表示生成图片大小，默认是3； 参数$margin表示二维码周围边框空白区域间距值； 参数$saveandprint表示是否保存二维码并 显示
			 */
			$matrixPointSize = "4";
			QRcode::png ( $value, $filename, $errorCorrectionLevel, $matrixPointSize );
			ajaxReturn ( '', '', 1 );
		}
	}
	
	/**
	 * 打印PDF版的通知书
	 */
	function print_offters() {
		
		// Include the main TCPDF library (search for installation path).
		require_once $_SERVER ['DOCUMENT_ROOT'] . '/public/tcpdf/tcpdf.php';
		
		// create new PDF document
		$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
		
		// set document information
		$pdf->SetCreator ( PDF_CREATOR );
		$pdf->SetAuthor ( 'Nicola Asuni' );
		$pdf->SetTitle ( 'TCPDF Example 001' );
		$pdf->SetSubject ( 'TCPDF Tutorial' );
		$pdf->SetKeywords ( 'TCPDF, PDF, example, test, guide' );
		
		// set default header data
		$pdf->SetHeaderData ( PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array (
				0,
				64,
				255 
		), array (
				0,
				64,
				128 
		) );
		$pdf->setFooterData ( array (
				0,
				64,
				0 
		), array (
				0,
				64,
				128 
		) );
		
		// set header and footer fonts
		$pdf->setHeaderFont ( Array (
				PDF_FONT_NAME_MAIN,
				'',
				PDF_FONT_SIZE_MAIN 
		) );
		$pdf->setFooterFont ( Array (
				PDF_FONT_NAME_DATA,
				'',
				PDF_FONT_SIZE_DATA 
		) );
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont ( PDF_FONT_MONOSPACED );
		
		// set margins
		$pdf->SetMargins ( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );
		$pdf->SetHeaderMargin ( PDF_MARGIN_HEADER );
		$pdf->SetFooterMargin ( PDF_MARGIN_FOOTER );
		
		// set auto page breaks
		$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
		
		// set image scale factor
		$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
		
		// set some language-dependent strings (optional)
		if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
			require_once (dirname ( __FILE__ ) . '/lang/eng.php');
			$pdf->setLanguageArray ( $l );
		}
		
		// ---------------------------------------------------------
		
		// set default font subsetting mode
		$pdf->setFontSubsetting ( true );
		
		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		$pdf->SetFont ( 'dejavusans', '', 14, '', true );
		
		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage ();
		
		// set text shadow effect
		$pdf->setTextShadow ( array (
				'enabled' => true,
				'depth_w' => 0.2,
				'depth_h' => 0.2,
				'color' => array (
						196,
						196,
						196 
				),
				'opacity' => 1,
				'blend_mode' => 'Normal' 
		) );
		
		// Set some content to print
		$html = 1111111;
		
		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell ( 0, 0, '', '', $html, 0, 1, 0, true, '', true );
		
		// ---------------------------------------------------------
		
		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output ( 'example_001.pdf', 'I' );
		
		// ============================================================+
		// END OF FILE
		// ============================================================+
	}
	
	/**
	 * 下载
	 */
	function xz() {
		$type = $this->input->get ( 'type' );
		// 申请id
		$id = ( int ) $this->input->get ( 'id' );
		$courseid = ( int ) $this->input->get ( 'courseid' );
		$userid = ( int ) $this->input->get ( 'userid' );
		if (! empty ( $id ) && ! empty ( $courseid )) {
			/*
			 * $OAADMIN = $this->load->database ( 'cucas', true ); $appid = $OAADMIN->select ( 'applytemplate' )->where ( 'id = ' . $courseid )->get ( 'course' )->result_array (); $applyid = $appid [0] ['applytemplate']; if (empty ( $applyid )) { $applyid = $this->app_detail_model->get_default_form_id ( ( int ) $schoolid ); } $view = 'word_' . $schoolid . '_' . $applyid; $str = ''; $encrpt_str = cucas_base64_encode ( authcode ( $id, 'ENCODE', 'mongo_userinfo', 0 ) ); $str = json_decode ( file_get_contents ( 'http://apply.' . DOHEAD . 'cucas.edu.cn/sync_userinfo/get_by_mongo/' . $encrpt_str ), true );
			 */
			$data = array();
			$str = $this->db->select ( 'key,value' )->get_where ( 'apply_template_info', 'applyid = ' . $id.' AND userid = '.$userid )->result_array ();
			var_dump($str);
			foreach ( $str as $key => $v ) {
				$data [$v ['key']] = $v ['value'];
			}
			$userpage = $edu = $family = $work = array ();
			$userpage = $data;
			
			// 教育情况
			if (! empty ( $data ['group_edu'] )) {
				$edu = unserialize ( $data ['group_edu'] );
			}
			// 家庭情况
			if (! empty ( $data ['group_family'] )) {
				$family = unserialize ( $data ['group_family'] );
			}
			// 工作情况
			if (! empty ( $data ['group_work'] )) {
				$work = unserialize ( $data ['group_work'] );
			}
			
			$public = CF ( 'public', '', CACHE_PATH );
			$PreviousEducation = $public ['global_PreviousEducation'];
			$country = $public ['global_country'];
			
			// echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"
			
			// xmlns:w="urn:schemas-microsoft-com:office:word"
			
			// xmlns="http://www.w3.org/TR/REC-html40">';
			$html = $this->load->view ( 'master/scholarship/applyform', array (
					'userpage' => $userpage,
					'edu' => $edu,
					'family' => $family,
					'PreviousEducation' => $PreviousEducation,
					'country' => $country,
					'work' => $work 
			), true );
			
			//获取邮箱
			$file_name_user = $this->db->select('*')->get_where('student_info','id = '.$userid)->row();
		
			$file_name = '';
// 			$file_name .= !empty($userpage['chinesename'])?$userpage['chinesename']:'_';
// 			$file_name .= !empty($userpage['passportno'])?'_'.$userpage['passportno']:'_';
// 			$file_name .= !empty($email_w->email)?'_'.$email_w->email:'';
			$file_name .=!empty($file_name_user->enname)?$file_name_user->enname:'';
			$file_name .=!empty($file_name_user->passport)?'-'.$file_name_user->passport:'-';
			$file_name .=!empty($file_name_user->email)?'-'.$file_name_user->email:'';
			
			header ( "Content-Type:application/msword" );
			header ( "Content-Disposition:attachment;filename=$file_name.doc" );
			header ( "Pragma:no-cache" );
			header ( "Expires:0" );
			echo $html;
		}
	}
	
	/**
	 * 预览
	 */
	function browse() {
		$type = $this->input->get ( 'type' );
		// 申请id
		$id = ( int ) $this->input->get ( 'id' );
		$courseid = ( int ) $this->input->get ( 'courseid' );
		$userid = ( int ) $this->input->get ( 'userid' );
		if (! empty ( $id ) && ! empty ( $courseid )) {
			/*
			 * $OAADMIN = $this->load->database ( 'cucas', true ); $appid = $OAADMIN->select ( 'applytemplate' )->where ( 'id = ' . $courseid )->get ( 'course' )->result_array (); $applyid = $appid [0] ['applytemplate']; if (empty ( $applyid )) { $applyid = $this->app_detail_model->get_default_form_id ( ( int ) $schoolid ); } $view = 'word_' . $schoolid . '_' . $applyid; $str = ''; $encrpt_str = cucas_base64_encode ( authcode ( $id, 'ENCODE', 'mongo_userinfo', 0 ) ); $str = json_decode ( file_get_contents ( 'http://apply.' . DOHEAD . 'cucas.edu.cn/sync_userinfo/get_by_mongo/' . $encrpt_str ), true );
			 */
			$data = array();
			$str = $this->db->select ( 'key,value' )->get_where ( 'apply_template_info', 'applyid = ' . $id.' AND userid = '.$userid )->result_array ();
			
			foreach ( $str as $key => $v ) {
				$data [$v ['key']] = $v ['value'];
			}

			$userpage = $edu = $family = $work = array ();
			$userpage = $data;
			
			// 教育情况
			if (! empty ( $data ['group_edu'] )) {
				$edu = unserialize ( $data ['group_edu'] );
			}
			// 家庭情况
			if (! empty ( $data ['group_family'] )) {
				$family = unserialize ( $data ['group_family'] );
			}
			// 工作情况
			if (! empty ( $data ['group_work'] )) {
				$work = unserialize ( $data ['group_work'] );
			}
			
			$public = CF ( 'public', '', CACHE_PATH );
			$PreviousEducation = $public ['global_PreviousEducation'];
			$country = $public ['global_country'];
			
			// echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"
			
			// xmlns:w="urn:schemas-microsoft-com:office:word"
			
			// xmlns="http://www.w3.org/TR/REC-html40">';
			
			$this->load->view ( 'master/scholarship/applyform', array (
					'userpage' => $userpage,
					'edu' => $edu,
					'family' => $family,
					'PreviousEducation' => $PreviousEducation,
					'country' => $country,
					'work' => $work 
			) );
		}
	}
	
	/**
	 * 下载附件
	 */
	function check_upload() {
		// 申请id
		$id = trim ( $this->input->get ( 'id' ) );
		$courseid = trim ( $this->input->get ( 'courseid' ) );
		if (! empty ( $id ) && ! empty ( $courseid )) {
			$data2 = $this->db->select ( 'attatemplate' )->get_where ( 'major', 'id = ' . $courseid )->row ();
			
			if(empty($data2->attatemplate)){
				$data2 = $this->db->select ( 'atta_id' )->get_where ( 'attachments' ,'aKind = \'Y\'')->row ();
				
				$where = "atta_id = {$data2->atta_id}";
			}else{
					$where = "atta_id = {$data2->attatemplate}";
			}
			
		
			$data1 = $this->db->select ( '*' )->get_where ( 'attachmentstopic', $where )->result_array ();
			
			foreach ( $data1 as $k => $v ) {
				$data3 [$v ['aTopic_id']] = $v ['TopicName'];
			}
			$data = $this->db->select ( '*' )->get_where ( 'apply_attachment_info', 'applyid = ' . $id )->result_array ();
			
			$this->load->vars ( 'dataF', $data3 );
			$this->load->vars ( 'data', $data );
			$html = $this->load->view ( 'master/enrollment/appmanager/check_upload', '', true );
			ajaxReturn ( $html, '', 1 );
		} else {
			
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 邮件发送函数
	 */
	function _send_email($email, $title, $content) {
		// 初始化
		$this->load->library ( 'mymail' );
		$MAIL = new Mymail ();
		$MAIL->domail ( $email, $title, $content );
	}
	/**
	 *
	 *导入页面
	 **/
	function tochanel(){
		$s=intval($this->input->get('s'));
			if(!empty($s)){
			$html = $this->_view ( 'tochanel', array(
				), true );
				ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 *
	 *导出模板
	 **/
	function tochaneltenplate(){
		$data=$this->app->get_app_fields();
		$this->load->library('sdyinc_export');
		$d=$this->sdyinc_export->shenqing_tochaneltenplate($data);
		if(!empty($d)){
			$this->load->helper('download');
			force_download('shenqing'. time().'.xlsx', $d);
			return 1;
		}
	}
	/**
	 *
	 *导出页面
	 **/
	function export_where(){
		$major=$this->app->get_major();
		$stu_nationality=$this->app->get_nationality();
		$s=intval($this->input->get('s'));
			if(!empty($s)){
			$html = $this->_view ( 'export_where', array(
					'major'=>$major,
					'stu_nationality'=>$stu_nationality,
				), true );
				ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 *
	 *导出
	 **/
	function export(){
		$where=$this->input->post();
		foreach ($where as $k => $v) {
			if($v==0){
				unset($where[$k]);
			}
		}
		$this->load->library('sdyinc_export');
		
			$d=$this->sdyinc_export->do_export_shenqing($where);
		
		if(!empty($d)){
			$this->load->helper('download');
			force_download('shenqing'. time().'.xlsx', $d);
			return 1;
		}
	}
	/**
	 *
	 *上传major
	 **/
	function upload_excel(){
		    //判断文件类型，如果不是"xls"或者"xlsx"，则退出
        if ( $_FILES["file"]["type"] == "application/vnd.ms-excel" ){
                $inputFileType = 'Excel5';
        }
        elseif ( $_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ){
                $inputFileType = 'Excel2007';
        }
        else {
                echo "Type: " . $_FILES["file"]["type"] . "<br />";
                echo "您选择的文件格式不正确";
                exit();
        }
        
        if ($_FILES["file"]["error"] > 0)
        {
                echo "Error: " . $_FILES["file"]["error"] . "<br />";
                exit();
        }
        $str=$_SERVER ['DOCUMENT_ROOT'] . '/uploads/work/' . date ( 'Ym' ) . '/' . date ( 'd' );
        if(!is_dir($str)){
			mk_dir($str);
		}
		 $inputFileName =$str.'/'.$_FILES["file"]["name"];
        if (file_exists($inputFileName))
        {
                //echo $_FILES["file"]["name"] . " already exists. <br />";
                unlink($inputFileName);    //如果服务器上存在同名文件，则删除
        }
        else
        {
        }
        move_uploaded_file($_FILES["file"]["tmp_name"],$inputFileName);
        echo "Stored in: " . $inputFileName.'<br />';
        $this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');
		$this->load->library('PHPExcel/Writer/Excel2007');
        $objReader = IOFactory::createReader($inputFileType);
        $WorksheetInfo = $objReader->listWorksheetInfo($inputFileName);

        //读取文件最大行数、列数，偶尔会用到。
        $maxRows = $WorksheetInfo[0]['totalRows'];
        $maxColumn = $WorksheetInfo[0]['totalColumns']; 

         //设置只读，可取消类似"3.08E-05"之类自动转换的数据格式，避免写库失败
        $objReader->setReadDataOnly(true);
      
        $objPHPExcel = $objReader->load($inputFileName);
        $sheetData = $objPHPExcel->getSheet(0)->toArray(null,true,true,true);

        $zjj = $objPHPExcel->getActiveSheet();
        $zjj->getStyle('D1:D'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
		$zjj->getStyle('G1:G'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
		$zjj->getStyle('M1:M'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
		$zjj->getStyle('O1:O'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
		$zjj->getStyle('R1:R'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
		$zjj->getStyle('U1:U'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
		$zjj->getStyle('Z1:Z'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
		$zjj->getStyle('AD1:AD'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
		$zjj->getStyle('AE1:AE'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');

        //excel2003文件，可使用'$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);'
        //excel2007文件使用"getActiveSheet()"方法时会提示出错：对non-object使用了"toArray"方法。    
         $keywords = $sheetData[1];
   	     $num=count($sheetData[1]);
         $warning = '上传文件字段格式不正确，请修改后重新上传！<br />';
        $columns = array ( 'A', 'B', 'C', 'D', 'E', 'F', 'G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE' );
        $mfields=$this->app->get_app_fields();
        unset($mfields['number']);
		unset($mfields['ordernumber']);
         if($num!=count($mfields)+1){
        	echo '字段个数不匹配';
        	exit();
        }
        $keysInFile = array ( );
        foreach ($mfields as $key => $value) {
        	$keysInFile[]=$value;
        }
        foreach( $columns as $keyIndex => $columnIndex ){
        	if($columnIndex=='A'){
        		continue;
        	}
                if ( $keywords[$columnIndex] != $keysInFile[$keyIndex] ){
                        echo $warning . $columnIndex . '列应为' . $keysInFile[$keyIndex] . '，而非' . $keywords[$columnIndex];
                         unlink($inputFileName);   
                        exit();
                }
        }
        $insert='number,';
        foreach ($mfields as $k => $v) {
        	if($k=='courseid'){
        		$insert.=$k.',';
        		$insert.='ordernumber,';
        	}else{
        	$insert.=$k.',';

        	}
        }
        $insert=trim($insert,',');
        unset($sheetData[1]);
			$i=65;
			$str='';
			$ss=2;
			foreach ($sheetData as $k => $v) {
				$value='';
				$email=$v['AF'];
				$majorid='';
				$app_time='';
				$studentid='';
				$sss=0;
				if(empty($email)){
						$str.=$ss.'行没有输入邮箱,该行没有插入<br />';
						continue;
					}
				$checkemail=$this->checkemail($email);
				if($checkemail==0){
					$str.=$ss.'行填入的邮箱格式有误,该行没有插入<br />';
					continue;
				}
				foreach ($v as $kk => $vv) {
					if($kk=='A'){
						$number = build_order_no ();
						$value.='"'.$number.'",';
					$studentid=$this->app->get_student_id($vv,$email);
					if(empty($studentid)){
						$studentid=$this->app->insert_student_info($vv,$email);
					}
					
						$value.='"'.$studentid.'",';
					}
					elseif($kk=='B'){
						$majorid=$this->app->get_majorid($vv);
						$value.='"'.$majorid.'",';
						$ordernumber = build_order_no ();
						$value.='"SDYI'.$ordernumber .'",';
					}elseif($kk=='H'){
						$vv=$this->get_paytype($vv);
						$value.='"'.$vv.'",';
					}elseif($kk=='D'){
						$app_time=strtotime($zjj->getCell('D'.$k)->getFormattedValue());
						$value.='"'.$app_time.'",';
					}elseif($kk=='G'||$kk=='M'||$kk=='O'||$kk=='R'||$kk=='U'||$kk=='Z'||$kk=='AD'||$kk=='AE'){
					
						$vv=strtotime($zjj->getCell($kk.$k)->getFormattedValue());
						$value.='"'.$vv.'",';
					}elseif($kk=='I'||$kk=='J'||$kk=='K'||$kk=='L'||$kk=='N'||$kk=='V'){
						$vv=$vv=='是'?1:0;
						$value.='"'.$vv.'",';
					}elseif($kk=='X'||$kk=='Y'){
						$vv=$vv=='未发送'?-1:1;
						$value.='"'.$vv.'",';
					}elseif($kk=='Q'||$kk=='AA'){
						$vv=$vv=='未确认'?-1:1;
						$value.='"'.$vv.'",';
					}elseif($kk=='P'){
						$vv=$this->get_app_state($vv);
						$value.='"'.$vv.'",';
					}elseif($kk=='F'){
						$vv=$this->get_paystate($vv);
						$value.='"'.$vv.'",';
					}elseif($kk=='S'){
						$vv=$vv=='未交'?-1:1;
						$value.='"'.$vv.'",';
					}elseif($kk=='AC'){
						$vv=$this->get_scholorstate($vv);
						$value.='"'.$vv.'",';
					}elseif($kk=='AB'){
						$email=$vv;
					}else{
						$value.='"'.$vv.'",';
					}
				}
				$value=trim($value,',');
				$count=$this->app->check_app($studentid,$majorid,$app_time);
				if($count>0){
					$str.='<br />excel中的'.$ss."行记录与数据库重复,改行没有插入";
					$ss++;
					continue;
				}
				if($sss==1){
					continue;
				}
				
				// $insert=explode(',', $insert);
				// $value=explode(',', $insert);
				// var_dump($insert);
				// var_dump($value);exit;
				$this->app->insert_fields($insert,$value);
				$ss++;
			$i++;
			}
			if($str!=''){
				echo $str;
			}
	}
	/**
	 * 邮箱验证
	 */
	function checkemail($email) {
		if (! empty ( $email )) {
			if (! preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email )) {
				return 0;
			}
		}
		return 1;
	}
	function get_app_state($str){
		$publics=CF('publics','',CONFIG_PATH);
		foreach ($publics['app_state'] as $key => $value) {
			if($value==$str){
				return $key;
			}
		}
	}

	function get_paystate($str){
		if(!empty($str)){
			switch ($str) {
				case '未支付':
					$s=0;
					break;
				case '成功':
					$s= 1;
					break;
				case '失败':
					$s= 2;
					break;
				case '待确认':
					$s= 3;
					break;
			
				
			}
			return $s;
		}
		return 0;
	}
	
	function get_paytype($str){
		if(!empty($str)){
			switch ($str) {
				case 'paypal':
					$s= 1;
					break;
				case 'payease':
					$s= 2;
					break;
				case '凭据':
					$s= 3;
					break;
			
				
			}
			return $s;
		}
		return 0;
	}
	function get_scholorstate($str){
		if(!empty($str)){
			$s=null;
			switch ($str) {
				case '待审核':
					$s= 0;
					break;
				case '通过':
					$s= 1;
					break;
				case '不通过':
					$s= 2;
					break;
			
				
			}
			return $s;
		}
		return 0;
	}
	/**
	 * 获取 奖学金的 全部
	 */
	function get_scholorshipapply(){
		$data = array();
		// 奖学金开关
		$scholarship_on = CF ( 'scholarship', '', CONFIG_PATH );
		if (! empty ( $scholarship_on ) && $scholarship_on ['scholarship'] == 'yes') {
			$scholarship = $this->db->select('*')->get_where('scholarship_info','id > 0')->result_array();
			if(!empty($scholarship)){
				foreach ($scholarship as $k => $v){
					$data[$v['id']] = $v['title'];
 				}
			}
			
		}
		return $data;
	}
	/**
	 *打印202表
	 */
	function print_two() {
		$this->load->library ( 'sdyinc_print' );
		$print_data=$this->input->post();
		if(!empty($print_data)){
			$this->sdyinc_print->do_pdf_prints ( 80, $print_data );
			return 1;
		}
		$id = $this->input->get ( 'id' );
		$appid=$this->input->get('appid');
		$appdata=$this->app->get_one_app_info($appid);
		$data=$this->app->get_student_one_info($id);
		$data['nationality']=$this->app->get_nationality_name($data['nationality']);
		$data['sex']=!empty($data['sex'])&&$data['sex']==1?'男':'女';
		$data['marital']=!empty($data['marital'])&&$data['marital']==1?'是':'否';

		if(!empty($data['birthday'])){
			$dayin_birthday=$data['birthday'];
			$data['birthday_year']=date('Y',$dayin_birthday);
			$data['birthday_month']=date('m',$dayin_birthday);
			$data['birthday_day']=date('d',$dayin_birthday);
		}
		$data['houseaddress']=$data['address'];
		if(!empty($appdata['courseid'])){
			$major_info=$this->app->get_major_info_one($appdata['courseid']);

			$dayin_opentime=strtotime($major_info['opentime']);
			$data['opentime_year']=date('Y',$dayin_opentime);
			$data['opentime_month']=date('m',$dayin_opentime);
			$data['opentime_day']=date('d',$dayin_opentime);
		}
		if(!empty($appdata['courseid'])){
			$major_info=$this->app->get_major_info_one($appdata['courseid']);
			$dayin_endtime=strtotime($major_info['endtime']);
			$data['endtime_year']=date('Y',$dayin_endtime);
			$data['endtime_month']=date('m',$dayin_endtime);
			$data['endtime_day']=date('d',$dayin_endtime);
		}
		if(!empty($appdata['courseid'])){
			$data['majorid']=$this->app->get_majorname($appdata['courseid']);
		}
		// var_dump($dayin_birthday);exit;
		$data ['school'] = "zust";
		$url='/master/student/student/dayin';
		if ($id) {
			$this->sdyinc_print->do_print ( 80, $data ,false,$url);
		}
		return 1;
	}
	/**
	 *打印邮递单
	 */
	function print_post() {
		$this->load->library ( 'sdyinc_print' );
		$print_data=$this->input->post();
		if(!empty($print_data)){
			$this->sdyinc_print->do_pdf_prints ( 87, $print_data );
			return 1;
		}
		$id = $this->input->get ( 'id' );
		$appid=$this->input->get('appid');
		$appdata=$this->app->get_one_app_info($appid);
		$data=$this->app->get_student_one_info($id);
		$data['sendname']='老师';
		$data['receiptname']=$data['lastname'];
		$data['receiptaddress']=$data['address'];
		$data['receipttel']=$data['mobile'];
		$url='/master/student/student/dayin';
		if ($id) {
			$this->sdyinc_print->do_print ( 87, $data ,false,$url);
		}
		return 1;
	}
	/**
	 * [onsite 现场缴费]
	 * @return [type] [html]
	 */
	function onsite(){
		$s = intval ( $this->input->get ( 's' ) );
		$userid=$this->input->get('userid');
		$id=intval ( $this->input->get ( 'id' ) );
		$type=1;
		$url='/master/enrollment/appmanager/do_onsite';
		$jump_url='/master/enrollment/appmanager';
		if (! empty ( $s )) {
			$html = $this->_view ( 'onsite', array (
				'userid'=>$userid,
				'id'=>$id,
				'type'=>$type,
				'url'=>$url,
				'jump_url'=>$jump_url
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 * [do_onsite 现场缴费]
	 * @return [type] [ajax]
	 */
	function do_onsite(){
		$data=$this->input->post();
		if(!empty($data)){
			$result=$this->app->pay_change_state($data);
			if($result){
				$results=$this->app->insert_pay_record($data);
				if($results){
					ajaxReturn('','提交成功',1);
				}
			}
		}
		ajaxReturn('','未知错误',0);
	}
	/**
	 * [add_otherscholarship 人工添加奖学金]
	 */
	function add_otherscholarship(){
		$type=$this->input->get('type');
	
		$this->_view ( 'add_otherscholarship',array(
				'type'=>$type
			) );
	}
	/**
	 * 
	 * [shouyu 授予奖学金弹框]
	 * @return [type] [description]
	 */
	function shouyu(){
		$userid=$this->input->get('userid');
		$type=$this->input->get('type');
		$student_info=$this->db->get_where('student','userid = '.$userid)->row_array();
		if(empty($student_info)){
			ajaxReturn('','该学生没有入学',2);
		}
		//查找新生奖学金
		$info=array();
		if($type==2){
			$info=array();
			//把申请列出来
			$apply_info=$this->pay_model->get_user_apply_infos($userid);
			if(empty($apply_info)){
				ajaxReturn('','该学生的申请还没有录取',0);
			}
		}elseif($type==1){
			$info=$this->db->where('apply_state = 1 AND state = 1')->get('scholarship_info')->result_array();
		}
	
		$html = $this->_view ( 'add_otherscholarship_box', array (
			'info'=>$info,
			'userid'=>$userid,
			'type'=>$type,
			'apply_info'=>!empty($apply_info)?$apply_info:array(),
		), true );
		ajaxReturn ( $html, '', 1 );
	}
	/**
	 * [get_jiangxuejin 获取这个专业下的奖学金
	 * @return [type] [description]
	 */
	function get_jiangxuejin(){
		$id=$this->input->get('id');
		if(!empty($id)){
			$major_info=$this->db->get_where('major','id = '.$id)->row_array();
			if(!empty($major_info['scholarship'])){
				$where=explode(',',$major_info['scholarship']);
				$scholarship_info=$this->db->where_in('id',$where)->where('apply_state',2)->get('scholarship_info')->result_array();
				if(!empty($scholarship_info)){
					ajaxReturn($scholarship_info,'',1);
				}
			}
		}
		ajaxReturn('','',1);
	}
	/**
	 * [save_otherscholarship 保存奖学金]
	 * @return [type] [description]
	 */
	function save_otherscholarship(){
		$data=$this->input->post();
		if(!empty($data)){
			$student_info=$this->db->get_where('student_info','id = '.$data['userid'])->row_array();
			$student=$this->db->get_where('student','userid = '.$data['userid'])->row_array();
			$term=1;
			if(!empty($student['squadid'])){
				$squad_info=$this->db->get_where('squad','id = '.$student['squadid'])->row_array();
				if(!empty($squad_info['nowterm'])){
					$term=$squad_info['nowterm'];
				}
			}
			$max_cucasid = build_order_no ();
			$arr=array(
				'scholarshipid'=>$data['scholarshipid'],
				'remark'=>$data['remark'],
				'userid'=>$data['userid'],
				'number'=>$max_cucasid,
				'term'=>$term,
				'type'=>$data['type'],
				'name'=>$student_info['chname'],
				'passport'=>$student_info['passport'],
				'email'=>$student_info['email'],
				'nationality'=>$student_info['nationality'],
				'applytime'=>time(),
				'isstart'=>1,
				'isinformation'=>1,
				'isatt'=>1,
				'issubmit'=>1,
				'issubmittime'=>time(),
				'is_artificial_grant'=>1,
				'state'=>1
				);
			if($data['type']==2){
				$arr['applyid']=$data['applyid'];
			}
			$this->db->insert('applyscholarship_info',$arr);
			$id=$this->db->insert_id();
			ajaxReturn($id,'',1);
		}
		ajaxReturn('','添加失败',0);
	}
	/**
	 * [get_major_book 获取该学期的书费]
	 * @return [type] [description]
	 */
	function _get_major_term_book($majorid,$term){
		//获取该专业的所有书籍
		$course_info=$this->pay_model->get_major_course($majorid);
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
		$last_money=0;
		if(!empty($course_info)){
			foreach ($course_info as $k => $v) {
				//获取该课程的书籍
				$book_info=$this->pay_model->get_course_book($v['courseid']);
				if(!empty($book_info)){
					foreach ($book_info as $kk => $vv) {
						//获取书籍信息
						$book[$vv['booksid']]=$this->pay_model->get_book_info($vv['booksid']);
						$last_money+=$book[$vv['booksid']]['price'];
						$bookids.=$vv['booksid'].',';
					}
				}
			}
		}
		$returndata['book']=$book;
		$returndata['bookids']=trim($bookids,',');
		$returndata['last_money']=$last_money;
		return $returndata;
	}

}