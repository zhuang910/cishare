<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 前台 学生 控制器
 *
 * @author zyj
 *        
 */
class Student extends Student_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		is_studentlogin ();
		
		$this->load->model ( 'student/student_model' );
        $this->load->model ( 'master/charge/pay_model' );
        $this->load->model ( 'student/fee_model' );
	}
	
	/**
	 * 主页
	 */
	function index() {

		$html = $this->load->view('master/enrollment/appmanager/print_offer_select',array(
            ),true);
        ajaxReturn($html,'',1);
	}
	
	/**
	 * 修改密码
	 */
	function editinfo() {
		$userinfo = $this->student_model->get_info_one ( 'id = ' . $_SESSION ['student'] ['userinfo'] ['id'] );
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		$this->load->view ( 'student/student_editinfo', array (
				'nationality' => $nationality,
				'userinfo' => ! empty ( $userinfo [0] ) ? $userinfo [0] : array () 
		) );
	}
	
		/**
	 * 邮箱验证
	 */
	function checkemail() {
		$email = $this->input->get ( 'email' );
		if (! empty ( $email )) {
			if (! preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email )) {
				die ( json_encode ( lang ( 'email_error' ) ) );
			} else {
				$where = array (
						'email' => $email 
				);
				$email_true = $this->student_model->get_info_one ( $where );
				
				if ($email_true) {
					if($_SESSION ['student'] ['userinfo'] ['id'] == $email_true[0]['id']){
						
						die ( json_encode ( true ) );
					}else{
						
						die ( json_encode ( lang ( 'email_exist' ) ) );
					}
					
				} else {
					die ( json_encode ( true ) );
				}
			}
		}
	}
	
	/**
	 * 修改个人信息
	 * 保存
	 */
	function do_editinfo() {
		$data = $this->input->post ();
		if (! empty ( $data )) {
			if (! empty ( $data ['birthday'] )) {
				$data ['birthday'] = strtotime ( $data ['birthday'] );
			}
			if(!empty($data['chfirstname'])||!empty($data['lastname'])){
				$data['chname']=$data['chfirstname'].$data['chlastname'];
			}
			if(!empty($data['firstname'])||!empty($data['chlastname'])){
				$data['enname']=$data['firstname'].$data['lastname'];
			}
			$flag = $this->student_model->basic_update ( 'id = ' . $_SESSION ['student'] ['userinfo'] ['id'], $data );
			if ($flag) {
				ajaxReturn ( '', lang ( 'update_success' ), 1 );
			} else {
				ajaxReturn ( '', lang ( 'update_error' ), 0 );
			}
		} else {
			ajaxReturn ( '', lang ( 'update_error' ), 0 );
		}
	}
	
	/**
	 * 修改密码
	 */
	function editphoto() {
		$id = $_SESSION ['student'] ['userinfo'] ['id'];
		$pic = $this->student_model->get_pic ( $id );
		$this->load->view ( 'student/student_editphoto', array (
				'pic' => $pic 
		) );
	}
	
	/**
	 * 修改密码
	 */
	function editpassword() {
		$this->load->view ( 'student/student_editpassword' );
	}
	
	/**
	 * 验证原始密码
	 * 是否正确
	 */
	function checkpassword() {
		$password = $this->input->get ( 'oldpassword' );
		$where = "id = {$_SESSION['student']['userinfo']['id']}";
		$userinfo = $this->student_model->get_info_one ( $where );
		if (! empty ( $password )) {
			if (substr ( md5 ( $password ), 0, 27 ) != $userinfo [0] ['password']) {
				die ( json_encode ( lang ( 'password_error' ) ) );
			} else {
				die ( json_encode ( true ) );
			}
		} else {
			die ( json_encode ( lang ( 'password_error' ) ) );
		}
	}
	
	/**
	 * 执行 修改密码
	 */
	function do_editpassword() {
		$data = $this->input->post ();
		$where = "id = {$_SESSION['student']['userinfo']['id']}";
		$userinfo = $this->student_model->get_info_one ( $where );
		if (! empty ( $data )) {
			if (substr ( md5 ( $data ['oldpassword'] ), 0, 27 ) != $userinfo [0] ['password']) {
				ajaxReturn ( '', lang ( 'password_error' ), 0 );
			}
			
			if ($data ['password'] != $data ['repassword']) {
				ajaxReturn ( '', lang ( 'password_equal' ), 0 );
			}
			$flag = $this->student_model->basic_update ( array (
					'id' => $_SESSION ['student'] ['userinfo'] ['id'] 
			), array (
					'password' => substr ( md5 ( $data ['password'] ), 0, 27 ) 
			) );
			
			if ($flag) {
				
				ajaxReturn ( '', lang ( 'update_success' ), 1 );
			} else {
				
				ajaxReturn ( '', lang ( 'update_error' ), 0 );
			}
		}
	}
	
	/**
	 * 住宿列表
	 */
	function accommodation() {
		$this->grf_update_room();
		$publics = CF ( 'publics', '', CONFIG_PATH );
		$flag_isshoufei = 0;
		$flag_isshoufeiyajin = 0;
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		$info = $this->db->select ( '*' )->order_by ( 'subtime DESC' )->limit ( 1 )->get_where ( 'accommodation_info', 'userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] )->result_array ();

        if (! empty ( $info )) {
			$camp = $this->db->select ( '*' )->get_where ( 'school_accommodation_campus', 'id = ' . $info [0] ['campid'] )->row ();
			$campname = ! empty ( $camp->name ) ? $camp->name : '';
			$build = $this->db->select ( '*' )->get_where ( 'school_accommodation_buliding', 'id = ' . $info [0] ['buildingid'] )->row ();
			$buildname = ! empty ( $build->name ) ? $build->name : '';
			$data = array ();
			// var_dump($info);exit;
			$builds = $this->db->select ( '*' )->get_where ( 'school_accommodation_prices', 'id > 0 AND bulidingid  = ' . $info [0] ['buildingid'] . ' AND id = ' . $info [0] ['roomid'] )->result_array ();
			
			// var_dump($builds);exit;
			$data = $publics ['room'] [$builds [0] ['campusid']] . '<br /> Price: RMB ' . $builds [0] ['prices'] . ' ' . lang ( 'room_price' );
			
			// 如果 有订单信息 走支付 否则 状态 通过
			if (! empty ( $info )) {
				
				$orderinfo = $this->db->select ( '*' )->get_where ( 'apply_order_info', 'userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] . ' AND id = ' . $info [0] ['order_id'] . ' AND ordertype = 4' )->result_array ();
			}
			$stay = CF ( 'stay', '', CONFIG_PATH );
			if (! empty ( $stay ) && in_array ( $stay ['stay'], array (
					'yes',
			) )) {
				// 住宿费和押金
				$flag_isshoufei = 1;
			}
			$stay_yajin = CF ( 'acc_pledge', '', CONFIG_PATH );
			if (! empty ( $stay_yajin ) && in_array ( $stay_yajin ['acc_pledge'], array (
					'yes',
			) )) {
				// 住宿费和押金
				$flag_isshoufei = 1;
			}
		}
		$flag_accommodation = 0;
		// 查看是否有申请 并且是 被录取了 才能显示提交表单
		$apply_all = $this->db->select ( '*' )->get_where ( 'apply_info', 'state >= 7 AND userid = ' .  $_SESSION ['student'] ['userinfo'] ['id'] )->result_array ();
		if (! empty ( $apply_all )) {
			$flag_accommodation = 1;
		}
		$shibai=1;
		//判断是否申请失败了
		if(!empty($info)){
			foreach ($info as $k => $v) {
				if($v['acc_state']!=4&&$v['acc_state']!=7){
					$shibai=0;
				}
			}
		}
		$userinfo=$this->db->where('id',$_SESSION ['student'] ['userinfo'] ['id'])->get('student_info')->row_array();
		//查看凭据失败原因
		$is_cause=0;
		if(!empty($info)){
			if($info[0]['paytype']==3){
				$c_info=$this->db->get_where('credentials','orderid ='.$info[0]['order_id'])->row_array();
				if($c_info['state']==2){
					$is_cause=$c_info['id'];
				}
			}
		}

		$this->load->view ( 'student/student_accommodation', array (
				'nationality' => $nationality,
				'info' => ! empty ( $info ) ? $info [0] : array (),
				'campname' => ! empty ( $campname ) ? $campname : '',
				'buildname' => ! empty ( $buildname ) ? $buildname : '',
				'data' => ! empty ( $data ) ? $data : '',
				'orderinfo' => ! empty ( $orderinfo ) ? $orderinfo [0] : array (),
				'flag_isshoufei' => $flag_isshoufei ,
				'flag_accommodation' => $flag_accommodation,
				'builds'=>!empty($builds)?$builds:array(),
				'flag_isshoufeiyajin'=>$flag_isshoufeiyajin,
				'shibai'=>$shibai,
				'userinfo'=>$userinfo,
				'is_cause'=>$is_cause
		) );
	}
	
	/**
	 * 接机列表
	 */
	function pickuplist() {
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		$flag_isshoufei = 0;
		$info = $this->db->select ( '*' )->order_by ( 'subtime DESC' )->limit ( 1 )->get_where ( 'pickup_info', 'userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] )->result_array ();
		if(!empty($info[0]['registeration_fee'])){
			$flag_isshoufei = 1;
		}
		// 如果 有订单信息 走支付 否则 状态 通过
		if (! empty ( $info ) && !empty($info[0]['order_id'])) {
			$orderinfo = $this->db->select ( '*' )->get_where ( 'apply_order_info', 'id = '.$info[0]['order_id'])->result_array ();
		}
		$pickup = CF ( 'pickup', '', CONFIG_PATH );
	//	if($pickup == 1){
			
		//	$pickup = '';
		//}
		$flag_accommodation = 0;
		// 查看是否有申请 并且是 被录取了 才能显示提交表单
		$apply_all = $this->db->select ( '*' )->limit ( 1 )->order_by ( 'applytime DESC' )->get_where ( 'apply_info', 'paystate = 1 AND state >= 7 AND userid = ' .  $_SESSION ['student'] ['userinfo'] ['id'] )->result_array ();
		if (! empty ( $apply_all )) {
			$flag_accommodation = 1;
		}
		$this->load->view ( 'student/student_pickuplist', array (
				'nationality' => $nationality,
				'info' => ! empty ( $info ) ? $info [0] : array (),
				'orderinfo' => ! empty ( $orderinfo ) ? $orderinfo [0] : array (),
				'flag_isshoufei' => $flag_isshoufei ,
				'flag_accommodation'=>$flag_accommodation,
				'pickup' => !empty($pickup)?$pickup:array()
		) ); 
	}
	
	/**
	 * 接机
	 */
	function pickup() {
		$flag = $this->pickup_power ();
		$pickup = CF ( 'pickup', '', CONFIG_PATH );
		
		$citys = CF ( 'city', '', CONFIG_PATH );
		if(!empty($citys) && !empty($pickup)){
			foreach($pickup as $key => $val){
				$cityid [] = $val['cityid'];
			}
			
			foreach($citys as $k => $v){
				if(in_array($k,$cityid)){
					if($k == 4 || $k == 5){
						$city[4] = 'Shanghai Pudong International Airport';
					}else{
						$city[$k] = $v;
					}
				}
			}
		}
		if($pickup == 1){
			
			$pickup = '';
		}
		if (! $flag) {
			
			echo '<script>window.location.href="/' . $this->puri . '/student/student/pickuplist"</script>';
		}
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		
		// 获取 专业 信息
		$majorid = $this->db->select('courseid')->limit(1)->order_by('id DESC')->get_where('apply_info','userid = '.$_SESSION['student']['userinfo']['id'])->row();
		if(!empty($majorid)){
			$major_info = $this->db->select('name,englishname')->get_where('major','id = '.$majorid->courseid)->row();
			
		}
		
		$this->load->view ( 'student/student_pickup', array (
				'nationality' => $nationality,
				'pickup' => !empty($pickup)?$pickup:array(),
				'city' => !empty($city)?$city:array(),
				'major_info' => !empty($major_info)?$major_info:array(),
		) );
	}
	
	/**
	*
	*获取接机 费用
	*/
	function pickup_fees(){
		$type = intval(trim($this->input->get('type')));
		$arrivetime = strtotime(trim($this->input->get('arrivetime')));
		if(!$arrivetime){
			//请先选择 预计到达时间
			ajaxReturn('', lang ( 'arrivetime' ),2);
		}
		if($type && $arrivetime){
			$pickup = CF ( 'pickup', '', CONFIG_PATH );
			//查时间 和 查费用
			if($type == 1 || $type == 2 || $type == 3){
				//首先获取 起止时间
				$stime = !empty($pickup) && !empty($pickup[$type]) && !empty($pickup[$type]['stime']) ? $pickup[$type]['stime'] : 0;
				$etime = !empty($pickup) && !empty($pickup[$type]) && !empty($pickup[$type]['etime']) ? $pickup[$type]['etime'] : 0;
				if($arrivetime > $stime && $arrivetime < $etime){
					//直接 接机费 用就是 0
					ajaxReturn(0,'',1);
				}
				
				$fees = !empty($pickup) && !empty($pickup[$type]) && !empty($pickup[$type]['carfees']) ? $pickup[$type]['carfees'] : 0;
				ajaxReturn($fees,'',1);
			}else{
				$fees = !empty($pickup) && !empty($pickup[$type]) && !empty($pickup[$type]['carfees']) ? $pickup[$type]['carfees'] : 0;
				ajaxReturn($fees,'',1);
			}
		}
	}
	
	/**
	 * 插入数据
	 * 接机
	 */
	 
	 function do_pickup() {
		$flag = $this->pickup_power ();
		if (! $flag) {
			echo '<script>window.location.href="/' . $this->puri . '/student/student/pickuplist"</script>';
		}
		$data = $this->input->post ();
		//var_dump($data);die;
		if ($data) {
			if (empty ( $data ['numbers'] )) {
				ajaxReturn ( '', lang ( 'update_error' ), 0 );
			}
			
			
			$pickup = CF ( 'pickup', '', CONFIG_PATH );
			
			
			$id = ! empty ( $data ['id'] ) ? $data ['id'] : '';
			unset ( $data ['id'] );
			if (! empty ( $data ['arrivetime'] )) {
				$data ['arrivetime'] = strtotime ( $data ['arrivetime'] );
			}
			$data ['state'] = 0;
			$data ['subtime'] = time ();
			$data ['userid'] = $_SESSION ['student'] ['userinfo'] ['id'];
			$flag = false;
			
			//判断 费用 类似 费用的开关
			$money = 0;
			if($data['cityid'] == 1 || $data['cityid'] == 2 || $data['cityid'] == 3 ){
				// 需要判断时间‘
				//首先获取 起止时间
				$stime = !empty($pickup) && !empty($pickup[$data['cityid']]) && !empty($pickup[$data['cityid']]['stime']) ? $pickup[$data['cityid']]['stime'] : 0;
				$etime = !empty($pickup) && !empty($pickup[$data['cityid']]) && !empty($pickup[$data['cityid']]['etime']) ? $pickup[$data['cityid']]['etime'] : 0;
				if($data ['arrivetime'] > $stime && $data ['arrivetime'] < $etime){
					//直接 接机费 用就是 0
					$money = 0;
				}else{
					//$money = !empty($pickup) && !empty($pickup[$data['cityid']]) && !empty($pickup[$data['cityid']]['carfees']) ? $pickup[$data['cityid']]['carfees'] : 0;
					$money = $data['fees'];
				
				}
				
				
			}else{
				//直接算钱
				
				//$money = !empty($pickup) && !empty($pickup[$data['cityid']]) && !empty($pickup[$data['cityid']]['carfees']) ? $pickup[$data['cityid']]['carfees'] : 0;
				$money = $data['fees'];
			}
			if ($money == 0) {
				// 直接表单提交
				$this->db->insert ( 'pickup_info', $data );
				$flag = $this->db->insert_id ();
				if ($flag) {
					$urls = '/' . $this->puri . '/student/student/pickuplist';
					ajaxReturn ( $urls, '', 1 );
				} else {
					ajaxReturn ( '', '', 0 );
				}
			} else {
				//插入收支表
				$budget_arr=array(
						'userid'=>$_SESSION ['student'] ['userinfo'] ['id'],
						'budget_type'=>1,
						'type'=>3,
						'payable'=>$money,
						'createtime'=>time(),
					);
				$this->db->insert('budget',$budget_arr);
				$budgetid=$this->db->insert_id();
				//再插入订单表
				$max_cucasid = build_order_no ();
				$order_arr=array(
					'budget_id'=>$budgetid,
					'ordernumber' => 'ZUST' . $max_cucasid,
					'ordertype' => 3,
					'userid' => $_SESSION ['student'] ['userinfo'] ['id'],
					'ordermondey' => ! empty ( $money ) ? $money : 0,
					'paytype' => 0,
					'paytime' => 0,
					'paystate' => 0,
					'createtime' => time (),
					'lasttime' => time () 
					);
				$this->db->insert('apply_order_info',$order_arr);
				$order_id=$this->db->insert_id();
				// 提交表单 跳到支付去
				$data ['registeration_fee'] = $money;
				$data['order_id']=$order_id;
				$data ['danwei'] = 'RMB';
				$data ['applytime'] = time ();
				$data ['paystate'] = 0;
				$data ['paytime'] = 0;
				$data ['isproof'] = 0;
				
				$this->db->insert ( 'pickup_info', $data );
				$acc_id = $this->db->insert_id ();
				if ($acc_id) {
					//auth:zyj 2015 8 30 更新订单表 把 applyID
					$this->db->update('apply_order_info',array('applyid' => $acc_id),'id = '.$order_id);
					// $applyid = cucas_base64_encode ( $acc_id . '-3' );
					// $url = '/' . $this->puri . '/pay_pa/index?applyid=' . $applyid;
					// ajaxReturn ( $url, '', 1 );
					$urls = '/' . $this->puri . '/student/student/pickuplist';
					ajaxReturn ( $urls, '', 1 );
				} else {
					ajaxReturn ( '', '', 0 );
				}
			}
		} else {
			ajaxReturn ( '', lang ( 'update_error' ), 0 );
		}
	}
	/*
	function do_pickup() {
		$flag = $this->pickup_power ();
		if (! $flag) {
			echo '<script>window.location.href="/' . $this->puri . '/student/student/pickuplist"</script>';
		}
		$data = $this->input->post ();
		var_dump($data);die;
		if ($data) {
			if (empty ( $data ['numbers'] )) {
				ajaxReturn ( '', lang ( 'update_error' ), 0 );
			}
			
			// 是否收费
			$flag_isshoufei = 0;
			
			// 钱
			$money = 0;
			// 单位
			$danwei = '';
			$pickup = CF ( 'pickup', '', CONFIG_PATH );
			if (! empty ( $pickup['pickup'] ) && $pickup ['pickup'] == 'yes') {
				$flag_isshoufei = 1;
				// 单价 每个人 收多少钱
				$money_dj = $pickup ['pickupmoney'];
				if ($pickup ['pickupway'] == 'pickuprmb') {
					$danwei = 'RMB';
				}
				if ($pickup ['pickupway'] == 'pickupusd') {
					$danwei = 'USD';
				}
				
				$money = $money_dj * $data ['numbers'];
			}
			
			$id = ! empty ( $data ['id'] ) ? $data ['id'] : '';
			unset ( $data ['id'] );
			if (! empty ( $data ['arrivetime'] )) {
				$data ['arrivetime'] = strtotime ( $data ['arrivetime'] );
			}
			$data ['state'] = 0;
			$data ['subtime'] = time ();
			$data ['userid'] = $_SESSION ['student'] ['userinfo'] ['id'];
			$flag = false;
			
			//
			if ($flag_isshoufei == 0) {
				// 直接表单提交
				$this->db->insert ( 'pickup_info', $data );
				$flag = $this->db->insert_id ();
				if ($flag) {
					$urls = '/' . $this->puri . '/student/student/pickuplist';
					ajaxReturn ( $urls, '', 1 );
				} else {
					ajaxReturn ( '', '', 0 );
				}
			} else {
				//插入收支表
				$budget_arr=array(
						'userid'=>$_SESSION ['student'] ['userinfo'] ['id'],
						'budget_type'=>1,
						'type'=>3,
						'payable'=>$money,
						'createtime'=>time(),
					);
				$this->db->insert('budget',$budget_arr);
				$budgetid=$this->db->insert_id();
				//再插入订单表
				$max_cucasid = build_order_no ();
				$order_arr=array(
					'budget_id'=>$budgetid,
					'ordernumber' => 'ZUST' . $max_cucasid,
					'ordertype' => 3,
					'userid' => $_SESSION ['student'] ['userinfo'] ['id'],
					'ordermondey' => ! empty ( $money ) ? $money : 0,
					'paytype' => 0,
					'paytime' => 0,
					'paystate' => 0,
					'createtime' => time (),
					'lasttime' => time () 
					);
				$this->db->insert('apply_order_info',$order_arr);
				$order_id=$this->db->insert_id();
				// 提交表单 跳到支付去
				$data ['registeration_fee'] = $money;
				$data['order_id']=$order_id;
				$data ['danwei'] = $danwei;
				$data ['applytime'] = time ();
				$data ['paystate'] = 0;
				$data ['paytime'] = 0;
				$data ['isproof'] = 0;
				
				$this->db->insert ( 'pickup_info', $data );
				$acc_id = $this->db->insert_id ();
				if ($acc_id) {
					
					// $applyid = cucas_base64_encode ( $acc_id . '-3' );
					// $url = '/' . $this->puri . '/pay_pa/index?applyid=' . $applyid;
					// ajaxReturn ( $url, '', 1 );
					$urls = '/' . $this->puri . '/student/student/pickuplist';
					ajaxReturn ( $urls, '', 1 );
				} else {
					ajaxReturn ( '', '', 0 );
				}
			}
		} else {
			ajaxReturn ( '', lang ( 'update_error' ), 0 );
		}
	}*/
	/**
	 * 判断接机权限
	 */
	function pickup_power() {
		$data = $this->db->select ( '*' )->get_where ( 'pickup_info', 'userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] )->result_array ();
		if ($data) {
			return false;
		}
		return true;
	}
	
	/**
	 * 站内消1
	 */
	function stu_message($state = 'all') {
		switch ($state) {
			case 'all' :
				$state = 0;
				break;
			case 'read' :
				$state = 1;
				break;
			case 'unread' :
				$state = 2;
				break;
		}
		$studentid = ( int ) $_SESSION ['student'] ['userinfo'] ['id'];
		$this->load->library ( 'sdyinc_message' );
		if (! empty ( $studentid )) {
			$data = $this->sdyinc_message->get_student_message ( $studentid, $state );
		}
		$count = count ( $data );
		$this->load->view ( 'student/student_message', array (
				'data' => $data,
				'count' => $count 
		) );
	}
	/**
	 * 读取消息
	 */
	function read_message() {
		$id = $this->input->get ( 'id' );
		$this->load->library ( 'sdyinc_message' );
		if (! empty ( $id )) {
			$r = $this->sdyinc_message->read_student_message ( $id );
			if ($r == 1) {
				ajaxReturn ( '', '更新成功', 1 );
			} else {
				ajaxReturn ( '', '已经更新过', 1 );
			}
		}
	}
	
	/**
	 * 虚拟删除消息
	 */
	function del_stu_message() {
		$id = $this->input->get ( 'id' );
		$this->load->library ( 'sdyinc_message' );
		if (! empty ( $id )) {
			$r = $this->sdyinc_message->dummy_del_stu_message ( $id );
			if ($r == 1) {
				ajaxReturn ( '', '删除成功', 1 );
			} else {
				ajaxReturn ( '', '删除失败', 0 );
			}
		}
	}
	
	/**
	 * 虚拟多删除消息
	 */
	function del_more_stu_message() {
		$this->load->library ( 'sdyinc_message' );
		$data = $this->input->post ();
		
		if (! empty ( $data )) {
			foreach ( $data ['checkbox'] as $k => $v ) {
				$this->sdyinc_message->dummy_del_stu_message ( $v );
			}
			
			ajaxReturn ( '', '删除成功', 1 );
		}
	}
	
	/**
	 * 学生更多消息
	 */
	function message_more($state = null) {
		if ($state == null) {
			$state = 'all';
		}
		$studentid = ( int ) $_SESSION ['student'] ['userinfo'] ['id'];
		$this->load->library ( 'sdyinc_message' );
		if (! empty ( $studentid )) {
			$data = $this->sdyinc_message->get_student_message_all ( $studentid, $state );
		}
		
		ajaxReturn ( $data, '', 1 );
	}
	/**
	 * 学生考勤
	 */
	function checking() {
        // 判断一下是否是 在学学生
        $is_student = $this->db->select ( '*' )->get_where ( 'student', 'state = 1 AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] )->result_array();

        if(empty($is_student)){
            echo '<script>window.location.href="/'.$this->puri.'/student/index"</script>';
        }
		// $nowterm=!empty($this->input->get('term'))?$this->input->get('term'):1;
		$nowterm = $this->input->get ( 'term' );
		if (empty ( $nowterm )) {
			$nowterm = 1;
		}
		$userid = ( int ) $_SESSION ['student'] ['userinfo'] ['id'];
		$term = $this->student_model->get_term ( $userid );
		
		$attendance = $this->student_model->get_attendance ( $userid, $nowterm, 'all' );
		$attendances = $this->student_model->get_attendance ( $userid, $nowterm, 'all' );
		$count = count ( $attendances );
		$this->load->view ( 'student/student_attendance', array (
				'attendance' => $attendance,
				'term' => $term,
				'nowterm' => $nowterm,
				'count' => $count 
		) );
	}
	/**
	 * 学生考勤导出
	 */
	function checking_export() {
		// $nowterm=!empty($this->input->get('term'))?$this->input->get('term'):1;
		$nowterm = $this->input->get ( 'term' );
		$type = $this->input->get ( 'type' );
		if (empty ( $nowterm )) {
			$nowterm = 1;
		}
		$userid = ( int ) $_SESSION ['student'] ['userinfo'] ['id'];
		$attendance = $this->student_model->get_attendance ( $userid, $nowterm, 'all' );
		$this->load->library ( 'sdyinc_export' );
		if ($attendance) {
			$d = $this->sdyinc_export->student_attendance_export ( $attendance, $type );
		}
		if (! empty ( $d )) {
			$this->load->helper ( 'download' );
			force_download ( 'kaoqin' . time () . '.xlsx', $d );
			return 1;
		}
	}
	/**
	 * 学生考勤更多信息
	 */
	function checking_more() {
		// $nowterm=!empty($this->input->get('term'))?$this->input->get('term'):1;
		$nowterm = $this->input->get ( 'term' );
		if (empty ( $nowterm )) {
			$nowterm = 1;
		}
		$userid = ( int ) $_SESSION ['student'] ['userinfo'] ['id'];
		$attendance = $this->student_model->get_attendance ( $userid, $nowterm, 'all' );
		for($i = 0; $i <= 5; $i ++) {
			unset ( $attendance [$i] );
		}
		if (! empty ( $attendance )) {
			ajaxReturn ( $attendance, '', 1 );
		}
		ajaxReturn ( '', '该学生的考勤为空', 0 );
	}
	
	/**
	 * 学生成绩
	 */
	function score() {
        // 判断一下是否是 在学学生
        $is_student = $this->db->select ( '*' )->get_where ( 'student', 'state = 1 AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] )->result_array();

        if(empty($is_student)){
            echo '<script>window.location.href="/'.$this->puri.'/student/index"</script>';
        }
		// 获取的学期及默认的学期
		// $nowterm=!empty($this->input->get('term'))?$this->input->get('term'):1;
		$nowterm = $this->input->get ( 'term' );
		if (empty ( $nowterm )) {
			$nowterm = 1;
		}
		$userid = ( int ) $_SESSION ['student'] ['userinfo'] ['id'];
		// 总学期数
		$term = $this->student_model->get_term ( $userid );
		
		// 考试类型
		$scoretype = $this->db->get('set_score')->result_array();
		// 获取的考试类型及默认的考试类型
		foreach ( $scoretype as $k => $v ) {
			$morenscoretype = $v['id'];
			break;
		}
		// 获取考试类型
		// $scoretypes = ! empty ( $this->input->get ( 'scoretype' ) ) ? $this->input->get ( 'scoretype' ) : $morenscoretype;
		$scoretypes = $this->input->get ( 'scoretype' );
		if (empty ( $scoretypes )) {
			$scoretypes = $morenscoretype;
		}
		$achievement = $this->student_model->get_achievement ( $userid, $nowterm, $scoretypes, 'all' );
		$achievements = $this->student_model->get_achievement ( $userid, $nowterm, $scoretypes, 'all' );
		$count = count ( $achievements );
		//获取专业
		$majorname=$this->student_model->get_major_name($userid);
		$squadname=$this->student_model->get_squad_name($userid);
		// 计算平均分
		$avgscore = $this->student_model->avg_score ( $achievements );
		
		$this->load->view ( 'student/student_achievement', array (
				'achievement' => $achievement,
				'term' => $term,
				'nowterm' => $nowterm,
				'count' => $count,
				'scoretype' => $scoretype,
				'scoretypes' => $scoretypes,
				'avgscore' => $avgscore ,
				'majorname'=>$majorname,
				'squadname'=>$squadname
		) );
	}
	
	/**
	 * 学生成绩导出
	 */
	function score_export() {
		// $nowterm = ! empty ( $this->input->get ( 'term' ) ) ? $this->input->get ( 'term' ) : 1;
		// $nowterm=!empty($this->input->get('term'))?$this->input->get('term'):1;
		$nowterm = $this->input->get ( 'term' );
		$type = $this->input->get ( 'type' );
		if (empty ( $nowterm )) {
			$nowterm = 1;
		}
		$scoretype = $this->input->get ( 'scoretype' );
		$userid = ( int ) $_SESSION ['student'] ['userinfo'] ['id'];
		$achievement = $this->student_model->get_achievement ( $userid, $nowterm, $scoretype, 'all' );
		$this->load->library ( 'sdyinc_export' );
		if ($achievement) {
			$d = $this->sdyinc_export->student_achievement_export ( $achievement, $type );
		}
		if (! empty ( $d )) {
			$this->load->helper ( 'download' );
			force_download ( 'chengji' . time () . '.xlsx', $d );
			return 1;
		}
	}
	
	/**
	 */
	function schedules() {
        // 判断一下是否是 在学学生
        $is_student = $this->db->select ( '*' )->get_where ( 'student', 'state = 1 AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] )->result_array();

        if(empty($is_student)){
            echo '<script>window.location.href="/'.$this->puri.'/student/index"</script>';
        }
		$hour = CF ( 'hour', '', CONFIG_PATH );
		$userid = ( int ) $_SESSION ['student'] ['userinfo'] ['id'];
		$userinfo=$this->student_model->get_user_info($userid);
		$now_term=$this->student_model->get_now_term($userinfo['squadid']);
		$schedules = $this->student_model->get_schedules ( $userid ,$now_term);
		//选修课那些信息
		
		$ele_info=$this->student_model->get_ele_info($userid,$now_term);
		$time=CF('hour_time','',CONFIG_PATH);
		// var_dump($ele_info);exit;
		$this->load->view ( 'student/student_schedules', array (
				'hour' => $hour,
				'schedules' => $schedules ,
				'ele_info'=>$ele_info,
				'time'=>$time
		) );
	}
	/**
	 * 获取用户消息数
	 */
	function get_message_num() {
		$data = $this->student_model->get_messagenum ( $_SESSION ['student'] ['userinfo'] ['id'] );
		ajaxReturn ( $data, '', 1 );
	}
	/**
	 * 用户图片上传
	 */
	function stu_pic() {
		$c = $_FILES ['doc'] ['tmp_name'];
		if (empty ( $c )) {
			echo "<script>window.location.href = '" . $this->zjjp . "student/student/editphoto'</script>";
			exit ();
		}
		$a = file_get_contents ( $c );
		
		$id = $_SESSION ['student'] ['userinfo'] ['id'];
		$filepath = date ( 'Ym', time () );
		
		if (! is_dir ( './uploads/student_pic' )) {
			mkdir ( './uploads/student_pic' );
		}
		$Yimgpath = './uploads/student_pic/' . $filepath;
		if (! is_dir ( $Yimgpath )) {
			mkdir ( $Yimgpath );
		}
		$mimgpath = date ( 'd', time () );
		if (! is_dir ( $Yimgpath . '/' . $mimgpath )) {
			mkdir ( $Yimgpath . '/' . $mimgpath );
		}
		
		$img = $Yimgpath . '/' . $mimgpath . '/' . $id . time () . '.jpg';
		$abc = file_put_contents ( $img, $a );
		if ($abc) {
			unlink ( $_FILES ['doc'] ['tmp_name'] );
		}
		$this->student_model->insert_pic ( $img, $id );
		echo "<script>window.location.href = '/" . $this->puri . "/student/student/editphoto'</script>";
	}
	
	/**
	 * 获取用户头像
	 */
	function get_stu_pic() {
		$id = $_SESSION ['student'] ['userinfo'] ['id'];
		$pic = $this->student_model->get_pic ( $id );
		if (! empty ( $pic )) {
			ajaxReturn ( $pic, '', 1 );
		} else {
			ajaxReturn ( '/resource/home/images/user/uers_pic.png', '', 1);
		}
	}
	
	/**
	 * 交学费
	 */
	function tuition() {
		// 历史记录
		$history = $this->db->select ( '*' )->get_where ( 'tuition_info', "userid = '{$_SESSION ['student'] ['userinfo'] ['id']}'" )->result_array ();
		if(!empty($history)){
			foreach ($history as $k => $v) {
				//查看凭据失败原因
				$is_cause=0;
				if($v['paytype']==3){
					$c_info=$this->db->get_where('credentials','orderid ='.$v['order_id'])->row_array();
					if($c_info['state']==2){
						$is_cause=$c_info['id'];
					}
				}
				$history[$k]['is_cause']=$is_cause;
			}
		}
		$this->load->view ( 'student/student_tuition', array (
				'history' => ! empty ( $history ) ? $history : array () 
		) );
	}
	/**
	 * [repairs 查看学费明细]
	 * @return [type] [description]
	 */
	function look_tuition_detail(){
		$userid=$_SESSION ['student'] ['userinfo'] ['id'];
		var_dump('查看学费明细');
		//用户信息
		// $user_info=$this->accommodation_model->get_user_info($userid);
		// $email='';
		// if(!empty($user_info['email'])){
		// 	$email=$user_info['email'];
		// }
		// //获取该学生的房间信息
		// $acc_info=$this->accommodation_model->get_acc_info($userid);
		//  $this->load->view ( '/student/repairs_page', array(
		// 	'acc_info'=>$acc_info,
		// 	'userid'=>$userid,
		// 	'email'=>$email
		// 		) );
		//ajaxReturn ( $html, '', 1 );
	}
    /**
     * 学生删除接机
     */
    function delete_picup(){
        $id=intval($this->input->get('id'));
        //缺少frebug攻击
        if(!empty($id)){
            $this->db->delete('pickup_info','id = '.$id);
            ajaxReturn('','',1);
        }
    }
    /**
     * 检查房间是否已经满了
     */
    function check_room(){
        $id=intval($this->input->get('id'));
        if(!empty($id)){
            //获取该房间的人数是否已经满了
            $acc_info=$this->db->where('id',$id)->get('accommodation_info')->row_array();
            if(!empty($acc_info['roomid'])){
                //获取该房间的信息
                $room_info=$this->db->where('id',$acc_info['roomid'])->get('school_accommodation_prices')->row_array();
                if(!empty($room_info)){
                    if($room_info['is_reserve']==0){
                        //此房间已经关闭预定
                        $this->db->update('accommodation_info',array('acc_state'=>4),'id ='.$id);
                        ajaxReturn('','',3);
                    }
                    if($room_info['in_user_num']>=$room_info['maxuser']||$room_info['is_reserve']==2){

                        //此房间已经满了
                        //修改状态预定失败
                        $this->db->update('accommodation_info',array('acc_state'=>4),'id ='.$id);
                        ajaxReturn('','',2);
                    }
                    //可以去预定
                  ajaxReturn('','',1);
                }
            }

        }
        ajaxReturn('','',0);
    }
    /**
     * [fee 费用类型页面]
     * @return [type] [description]
     */
    function fee(){
    	//缴费列表
    	$info=$this->db->order_by('createtime desc')->get_where('budget','userid = '.$_SESSION ['student'] ['userinfo'] ['id'].' AND budget_type = 1')->result_array();
    	$this->load->view ( 'student/student_fee', array (
				'info' => ! empty ( $info ) ? $info : array () 
		) );
    }
    /**
     * [book_fee 交书费页面]
     * @return [type] [description]
     */
    function book_fee(){
    	$term=intval($this->input->get('term'));
    	$re=intval($this->input->get('re'));
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
							$bookids.=$vv['booksid'].',';
						}
					}
				}
			}
    	}
    	//查询有没有支付这个学期的
    	$is_jiao=$this->db->get_where('books_fee','userid = '.$_SESSION ['student'] ['userinfo'] ['id'].' AND term = '.$term.' AND paystate = 1')->row_array();
    	//查看已经选过的
    	$before_info=$this->db->get_where('books_fee','userid = '.$_SESSION ['student'] ['userinfo'] ['id'].' AND term = '.$term)->result_array();
    	$ids_str='';
    	$money=0;
    	if(!empty($before_info)){
    		foreach ($before_info as $key => $value) {
    			if($value['paystate']!=1){
    				continue;
    			}
    			$ids_str.=$value['book_ids'].',';
    			
    			$money+=$value['last_money'];

    		}
    	}
    	if(!empty($ids_str)){
    		$select_id=explode(',', $ids_str);
    	}
    	$wei_ids='';
    	//筛选未交的书费
    	if(!empty($book)&&!empty($select_id)){
    		foreach ($book as $k => $v) {
	    		if(in_array($v['id'], $select_id)){
	    			continue;
	    		}else{
	    			$wei_ids.=$v['id'].',';
	    		}
	    	}
    	}
    	
    	$this->load->view ( 'student/student_book_fee', array (
				'major_info'=>$major_info,
				'book'=>!empty($book)?$book:array(),
				'book_ids'=>!empty($book_ids)?$book_ids:'',
				'book_money'=>!empty($money)?$money:0,
				'term'=>!empty($term)?$term:'',
				'is_jiao'=>!empty($is_jiao)?$is_jiao:array(),
				'select_id'=>!empty($select_id)?$select_id:array(),
				'wei_ids'=>!empty($wei_ids)?"'".trim($wei_ids,',')."'":'',
				're'=>!empty($re)?$re:0

		) );
    }
      /**
         * [resubmissions_book_fee 补交书费入口]
         * @return [type] [description]
         */
        function resubmissions_book_fee(){
        	$ids=$this->input->get('ids');
        	$term=$this->input->get('term');
        	$where='';
        	if(!empty($ids)){
        		$ids_arr=explode(',', $ids);
        	}
        	$data=$this->db->where_in('id',$ids_arr)->get('books')->result_array();
    		//查询还未交的书费
    		$this->load->view ( 'student/resubmissions_book_page',array(
    			'term'=>$term,
    			'data'=>$data,

    			));
        }
      /**
     * [book_fee 交书费页面]
     * @return [type] [description]
     */
    function electric_pledge()
    {
        $electric = CF('electric', '', CONFIG_PATH);
        if (empty($electric) || $electric['electric'] == 'no') {
            ajaxReturn('', '', 2);
        }
        $info = $this->db->get_where('electric_pledge', 'userid = ' . $_SESSION ['student'] ['userinfo'] ['id'].' AND is_retreat=0')->row_array();
        if (empty($info)) {
            //插入收支表
            $userid = $_SESSION ['student'] ['userinfo'] ['id'];
                //获取该学生班级学期
                $student_info = $this->db->get_where('student', 'userid = ' . $userid)->row_array();
                if (!empty($student_info['squadid'])) {
                    //查看班级的属性
                    $squad_info = $this->db->get_where('squad', 'id = ' . $student_info['squadid'])->row_array();
                    $term = $squad_info['nowterm'];
                } else {
                    $term = 1;
                }
                //创建收支表
                $budget_arr = array(
                    'userid' => $_SESSION ['student'] ['userinfo'] ['id'],
                    'budget_type' => 1,
                    'type' => 14,
                    'term' => $term,
                    'payable' => $electric['electricmoney'],
                    'createtime' => time(),
                );
                $budgetid = $this->fee_model->insert_budget($budget_arr);
                //插入订单表
                $max_cucasid = build_order_no();
                $order_arr = array(
                    'budget_id' => $budgetid,
                    'createtime' => time(),
                    'lasttime' => time(),
                    'ordernumber' => 'ZUST' . $max_cucasid,
                    'ordertype' => 14,
                    'userid' => $_SESSION ['student'] ['userinfo'] ['id'],
                    'ordermondey' => $electric['electricmoney']
                );
                $orderid = $this->fee_model->insert_order($order_arr);
                //插入电费押金表
                $arr = array(
                    'order_id' => $orderid,
                    'budgetid' => $budgetid,
                    'userid' => $_SESSION ['student'] ['userinfo'] ['id'],
                    'last_money' => $electric['electricmoney'],
                    'paystate' => 0,
                    'createtime' => time()
                );
                $this->db->insert('electric_pledge', $arr);
            }
        $info = $this->db->get_where('electric_pledge', 'userid = ' . $_SESSION ['student'] ['userinfo'] ['id'].' AND is_retreat=0')->row_array();
            $this->load->view('student/student_electric_pledge', array(
                'info' => $info
            ));
        }

        /**
         * [save_books_fee 保存用户所选的书籍]
         * @return [type] [description]
         */
        function save_books_fee(){
        	$data=$this->input->post();
        	if(!empty($data)){
        		$ids='';
        		$money=0;
        		foreach ($data['ids'] as $k => $v) {
        			$book_info=$this->db->get_where('books','id = '.$v)->row_array();
        			$money+=$book_info['price'];
        			$ids.=$v.',';
        		}
        		//查看之前有没有预订书  有的话就删除
        		$before_info=$this->db->get_where('budget','term = '.$data['term'].' AND userid = '.$_SESSION ['student'] ['userinfo'] ['id'].' AND type = 8 AND paystate =0')->row_array();
        		if(!empty($before_info)){
        			$this->db->delete('apply_order_info','budget_id = '.$before_info['id']);
        			$this->db->delete('books_fee','budgetid = '.$before_info['id']);
        			$this->db->delete('budget','id = '.$before_info['id']);
        		}
        		//创建收支表
				$budget_arr=array(
					'userid'=>$_SESSION ['student'] ['userinfo'] ['id'],
					'budget_type'=>1,
					'type'=>8,
					'term'=>$data['term'],
					'payable'=>$money,
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
					'ordertype'=>8,
					'userid'=>$_SESSION ['student'] ['userinfo'] ['id'],
					'ordermondey'=>$money,
					);
				$orderid=$this->fee_model->insert_order($order_arr);

				$book=array(
					'userid'=>$_SESSION ['student'] ['userinfo'] ['id'],
					'orderid'=>$orderid,
					'budgetid'=>$budgetid,
					'term'=>$data['term'],
					'book_ids'=>trim($ids,','),
					'last_money'=>$money,
					'createtime'=>time(),
					'paystate'=>0
					);
				$this->db->insert('books_fee',$book);

				ajaxReturn(cucas_base64_encode($data['term'].'-8'),'',1);
        	}
        	ajaxReturn('','',0);
        }
}

