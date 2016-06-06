<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Tuition extends Master_Basic{


	//状态
	public $config_state =array(
		0 => '未支付',
		1 =>  '成功' , 
		2 => '失败', 
		3 =>  '待确认'
		);

	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/finance/';
		$this->load->model($this->view.'tuition_model');
	}
/**
	 * 查看历史记录
	 */
	function history_pay(){
		$userid = intval(trim($this->input->get('userid')));
		if($userid){
			$history = $this->db->select('*')->get_where('tuition_info','userid = '.$userid)->result_array();
			
			$html = $this->_view('history_pay',array(
					'history' => !empty($history)?$history:array()
			),true);
			ajaxReturn($html,'',1);
		}else{
			ajaxReturn('','',0);
		}
	}
		/**
	 * 后台主页
	 */
	function index() {
		$major_id = $this->input->get('major_id');
		$term = $this->input->get('term');
		$squad_id = $this->input->get('squad_id');

		$major_id = !empty($major_id) ? $major_id : 0;
		$squad_id = !empty($squad_id) ? $squad_id : 0;
		$term = !empty($term) ? $term : 0;
		if ($this->input->is_ajax_request () === true) {
			$field = $this->_set_lists_field ();
			
			// 翻页
			$limit = "";
			$offset = "";
			if (isset ( $_GET ['iDisplayStart'] ) && $_GET ['iDisplayLength'] != '-1') {
				$offset = intval ( $_GET ['iDisplayStart'] );
				$limit = intval ( $_GET ['iDisplayLength'] );
			}

			// 排序
			$orderby = null;
			if (isset ( $_GET ['iSortCol_0'] )) {
				for($i = 0; $i < intval ( $_GET ['iSortingCols'] ); $i ++) {
					// bSortable_x:false
					if ($_GET ['bSortable_' . intval ( $_GET ['iSortCol_' . $i] )] == "true") {
						$orderby = $field [intval ( $_GET ['iSortCol_' . $i] )] . ' ' . mysql_real_escape_string ( $_GET ['sSortDir_' . $i] );
					}
				}
			}
			$major_id = $this->input->get('major_id');
			$major_id = !empty($major_id) ? $major_id : 0;
			$squad_id = $this->input->get('squad_id');
			$squad_id = !empty($squad_id) ? $squad_id : 0;
			$term = $this->input->get('term');
			$term = !empty($term) ? $term : 0;

			$where='zust_tuition_info.id <> 0';
            $sSearch =  $_GET['sSearch' ];
            if (! empty ( $sSearch )) {
                $where .= " AND ( zust_tuition_info.id LIKE '%{$sSearch}%' OR zust_student.email LIKE '{$sSearch}'  OR zust_student.passport LIKE '{$sSearch}' OR zust_student.name LIKE '{$sSearch}')";
            }
			$sSearch_0 =  $_GET['sSearch_0' ];
			if (! empty ( $sSearch_0 )) {
				$where .= " AND ( zust_tuition_info.id LIKE '%{$sSearch_0}%')";
			}
			$sSearch_1 =  $_GET['sSearch_1' ];
			if (! empty ( $sSearch_1 )) {
				$where .= " AND ( zust_student.name LIKE '%{$sSearch_1}%'
								OR zust_student.email LIKE '%{$sSearch_1}%'
								OR zust_student.passport LIKE '%{$sSearch_1}%'
					)";
			}
			$sSearch_2 =  $_GET['sSearch_2' ];
			if (isset($sSearch_2) && $sSearch_2 != '') {
				$where .= " AND ( zust_tuition_info.paystate = {$sSearch_2})";
			}

			//专业名
			$majorname = $this->db->get('zust_major')->result_array();			
			foreach ($majorname as $m => $n) {
				$config_major[$n['id']] = $n['name'];
			}
			//班级名
			$squadname = $this->db->get('zust_squad')->result_array();			
			foreach ($squadname as $w => $d) {
				$config_squad[$d['id']] = $d['name'];
			}
			// 输出
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->tuition_model->count ( $where,$major_id,$term,$squad_id);
			$output ['aaData'] = $this->tuition_model->get ( $where, $limit, $offset, $orderby ,$major_id,$term,$squad_id);
			foreach ( $output ['aaData'] as $val ) {
				$val->field_0 = $val->bookid;
				$val->field_1 = '
							用户名:&nbsp;&nbsp;<td>'.$val->name.'</td><br />邮&nbsp;&nbsp;&nbsp;&nbsp;箱:&nbsp;&nbsp;<td>'.$val->email.'</td><br />护照号:&nbsp;&nbsp;<td>'.$val->passport.'</td>
				';
				$val->field_2 = isset($config_major[$val->majorid]) ? $config_major[$val->majorid] : '--';//专业
				$val->field_3 = '第'.$val->nowterm.'学期';//学期
				$val->field_4 = isset($config_squad[$val->squadid]) ? $config_squad[$val->squadid] : '--';//班级
				$val->field_5 = isset($this->config_state[$val->paystate]) ? $this->config_state[$val->paystate] : '--';
				$val->field_6 = date('Y-m-d',$val->paytime);
				$val->field_7 = '<a href="javascript:pub_alert_html(\'' . $this->zjjp . 'tuition/history_pay?userid=' . $val->userid . '&s=1\');" class="btn btn-xs btn-info" rel="tooltip" title="查看历史记录">查看历史记录</a>';
			}
			exit ( json_encode ( $output ) );
		}

		$major_info=$this->db->order_by('language DESC')->get_where('major','id > 0')->result();
		$major_info = $this->_get_major_by_degree($major_info);
		
		$major_info_one=$this->db->get_where('major','id = '.$major_id.' AND state = 1')->row_array();
		$squad_info=$this->db->get_where('squad','majorid='.$major_id.' AND nowterm = '.$term)->result_array();
        $this->_view('tuition_index',array(
        	'major_id' => $major_id,
        	'squad_id' => $squad_id,
        	'major_info'=>$major_info,
        	'squad_info' => $squad_info,
        	'major_info_one'=>!empty($major_info_one)?$major_info_one:array(),
        	'term'=>$term
        	));
    }

	private function _get_major_by_degree($major_lists = array()){
        $temp = array();
        if(!empty($major_lists)){
           
			$degree = $this->db->order_by('orderby DESC')->get('degree_info','id > 0')->result_array();
            foreach($degree as $key => $item){
                foreach($major_lists as $info){
                    if($info->degree == $item['id']){
                        $temp[$key]['degree_title'] = $item['title'];
                        $temp[$key]['degree_major'][] = $info;
                    }
                }
            }
        }
        return $temp;
    }

	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'zust_tuition_info.id',
				'zust_tuition_info.userid',
                'zust_student.majorid',
                'zust_student.squadid',
                'zust_tuition_info.nowterm',
				'zust_tuition_info.paystate',
				'zust_tuition_info.paytime'
		);
	}




}










// if (! defined ( 'BASEPATH' )) {
// 	exit ( 'No direct script access allowed' );
// }

// /**
//  * 申请管理
//  *
//  * @author Laravel
//  *        
//  */
// class Tuition extends Master_Basic {
	
// 	/**
// 	 * 构造函数
// 	 */
// 	function __construct() {
// 		parent::__construct ();
// 		$this->view = 'master/finance/';
// 		$this->load->model ( $this->view . 'tuition_model' );
// 	}
// 	function index() {
// 		$label_id = $this->input->get ( 'label_id' );
// 		$label_id = ! empty ( $label_id ) ? $label_id : '1';
// 		if ($label_id == 1) {
// 			$where = 'paystate = 1 AND id > 0';
// 		} else {
// 			$where = 'paystate != 1 AND id > 0';
// 		}
// 		// 根据状态获得申请列表信息
// 		$lists = $this->tuition_model->get_app_1 ( $where );

// 		$publics = CF ( 'publics', '', CONFIG_PATH );
		
// 		// 获取状态
		
// 		// 读取国籍缓存
// 		$nationality = CF ( 'nationality', '', 'application/cache/' );

// 		$country = '';
		
// 		foreach ( $nationality as $key => $value ) {
// 			$country [$key] = $value;
// 		}
		

		
// 		$data = array (
// 				'label_id' => $label_id,
// 				'lists' => $lists,
				
// 				'country' => $country,
			
// 		);
		
// 		$this->_view ( 'tuition_index', $data );
// 	}
	
	
	
	
	
	
	
// 	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 	/**
// 	 * 申请详情页
// 	 */
// 	function app_detail() {
// 		$appid = $this->input->get ( 'id' ); // 获取标签识别查询条件
// 		$lists = $this->app->get_one_app_detail ( $appid ); // 获取符合标签识别变量的申请数据
		
// 		$lists_log = $this->app->get_log ( $appid ); // 获取符合标签识别变量的日志数据
		
// 		$publics = CF ( 'publics', '', CONFIG_PATH );
		
// 		// 获取状态
// 		$app_state = '';
// 		foreach ( $publics ['app_state'] as $key => $value ) {
// 			$app_state [$key] = $value;
// 		}
		
// 		// 获取课程周期
// 		$programa_unit = '';
		
// 		foreach ( $publics ['programa_unit'] as $key => $value ) {
// 			$programa_unit [$key] = $value;
// 		}
		
// 		// 读取国籍缓存
// 		$nationality = CF ( 'nationality', '', 'application/cache/' );
// 		$country = '';
		
// 		foreach ( $nationality as $key => $value ) {
// 			$country [$key] = $value;
// 		}
		
// 		$this->load->view ( 'master/enrollment/appmanager/app_detail', 		// 指定模板
// 		array (
// 				'lists' => $lists, // 查询申请结果对象数组
// 				'lists_log' => $lists_log, // 查询日志结果对象数组
// 				'country' => $country, // 国籍数组
// 				'apply_state' => $app_state, // 申请状态数组
// 				'apply_operator' => 1, // 申请操作人数组
// 				'programa_unit' => $programa_unit 
// 		) );
// 	}
// 	// 录取处理-----发送offer的处理
// 	function app_offer() {
// 		$label_id = $this->input->get ( 'label_id' );
// 		$label_id = ! empty ( $label_id ) ? $label_id : '7';
// 		$ispageoffer = $this->input->get ( 'ispageoffer' ); // 是否发送e-offer
// 		                                                    // $ispageoffer = ! empty ( $ispageoffer ) ? $ispageoffer : '-1';
// 		$wait = $this->input->get ( 'sendtype' ); // 纸质offer 发送状态
// 		                                          // $wait = ! empty ( $wait ) ? $wait : '';
// 		$cstatus = $this->input->get ( 'cstatus' ); // 地址确认状态
// 		if ($label_id == 7) {
// 			$where = 'apply_info.state = 7';
// 			if ($ispageoffer) {
// 				$where .= ' AND apply_info.e_offer_status = ' . $ispageoffer;
// 			}
			
// 			if ($wait) {
// 				$where .= ' AND apply_info.pagesend_status=' . $wait;
// 			}
			
// 			if ($cstatus) {
// 				$where .= ' AND apply_info.addressconfirm=' . $cstatus;
// 			}
// 		}
		
// 		// if ($label_id == 7 && $ispageoffer == 1) {
// 		// $where = ' AND apply_info.e_offer_status = 1 AND apply_info.pagesend_status=' . $wait . '';
// 		// AND apply_info.addressconfirm=' . $cstatus . '
// 		// } else {
// 		// //$where = 'apply_info.state = 7 AND major.ispageoffer=' . $ispageoffer . '';
// 		// $where = 'apply_info.state = 7 AND apply_info.e_offer_status= -1';
// 		// }
		
// 		// 根据状态获得申请列表信息
// 		$lists = $this->app->get_app ( $where );

// 		/*
// 		 * 通过申请id去查看offer中的发生状态
// 		 */
		
// 		$publics = CF ( 'publics', '', CONFIG_PATH );
		
// 		// 获取状态
// 		$app_state = '';
// 		foreach ( $publics ['app_state'] as $key => $value ) {
// 			$app_state [$key] = $value;
// 		}
		
// 		// 获取课程周期
// 		$programa_unit = '';
		
// 		foreach ( $publics ['programa_unit'] as $key => $value ) {
// 			$programa_unit [$key] = $value;
// 		}
		
// 		// 读取国籍缓存
// 		$nationality = CF ( 'nationality', '', 'application/cache/' );
// 		$country = '';
		
// 		foreach ( $nationality as $key => $value ) {
// 			$country [$key] = $value;
// 		}
		
// 		$data = array (
// 				'label_id' => $label_id,
// 				'lists' => $lists,
// 				'app_state' => $app_state,
// 				'programa_unit' => $programa_unit,
// 				'country' => $country,
// 				'ispageoffer' => $ispageoffer,
// 				'sendtype' => $wait,
// 				'cstatus' => $cstatus 
// 		);
		
// 		if ($wait == - 1) {
// 			$this->load->view ( 'master/enrollment/appmanager/app_offer_1', $data );
// 		} else {
// 			$this->load->view ( 'master/enrollment/appmanager/app_offer', $data );
// 		}
// 	}
	
// 	/**
// 	 * 入学确认
// 	 */
// 	function app_finish() {
// 		$label_id = $this->input->get ( 'label_id' ); // 获取标签识别查询条件
// 		$label_id = ! empty ( $label_id ) ? $label_id : 7; // 此时由于前面的流程已经执行，入学确认则从录取流程进行
		                                                   
// 		// 根据条件进行查询
// 		if ($label_id == 7) {
// 			$where = 'apply_info.state = ' . $label_id . ' AND  apply_info.e_offer_status=1';
// 		} else {
// 			$where = 'apply_info.state = ' . $label_id . '';
// 		}
		
// 		/*
// 		 * 为两种显示形式准备数据结果集 $label_id=3；为发送E-OFFER
// 		 */
// 		$lists = $this->app->get_app ( $where );
		
// 		$publics = CF ( 'publics', '', CONFIG_PATH );
		
// 		// 获取状态
// 		$app_state = '';
// 		foreach ( $publics ['app_state'] as $key => $value ) {
// 			$app_state [$key] = $value;
// 		}
		
// 		// 获取课程周期
// 		$programa_unit = '';
		
// 		foreach ( $publics ['programa_unit'] as $key => $value ) {
// 			$programa_unit [$key] = $value;
// 		}
		
// 		// 读取国籍缓存
// 		$nationality = CF ( 'nationality', '', 'application/cache/' );
// 		$country = '';
		
// 		foreach ( $nationality as $key => $value ) {
// 			$country [$key] = $value;
// 		}
// 		var_dump($country);exit;
// 		$data = array (
// 				'lists' => $lists,
// 				'label_id' => $label_id,
// 				'programa_unit' => $programa_unit,
// 				'country' => $country 
// 		);
// 		/*
// 		 * $this->_view ( //根据标签识别变量调用不同模板 array( 'lists' 		=> $lists,								//查询结果对象数组 'country' 		=> $global_country,						//国籍数组 'apply_state' 	=> $data,				//申请状态数组 'delivery' 		=> $global_delivery,					//邮寄方式数据 'send' 		=> $global_send,							//发送方式数组 'label_id' 		=> $label_id							//显示当前标签识别变量 ) );
// 		 */
// 		$this->load->view ( 'master/enrollment/appmanager/confirm_app.php', $data );
// 	}
	
// 	/**
// 	 * 审核资料
// 	 */
// 	function check_info() {
// 		$appid = $this->input->get ( 'id' );
// 		$result = $this->app->get_app_infos ( $appid );
// 		$arr2 = array ();
// 		$arr3 = array ();
// 		if (! empty ( $result )) {
// 			foreach ( $result as $k1 => $v1 ) {
// 				$arr1 = explode ( '_', $v1->keyid );
// 				// 数值关系
// 				$arr2 [$arr1 [2]] = $v1->value;
// 				$arr3 [$arr1 [1]] [] = $arr1 [2];
// 			}
// 		}
// 		// apply_block
// 		$info = $this->app->get_apply_block ();
// 		foreach ( $info as $key => $val ) {
// 			$info1 [$val->id] = $val->title;
// 		}
		
// 		// apply_form
// 		$infos = $this->app->get_apply_form ();
		
// 		foreach ( $infos as $key => $val ) {
// 			$info2 [$val->id] ['title'] = $val->title;
// 			$info2 [$val->id] ['type'] = $val->type;
// 			$info2 [$val->id] ['name'] = $val->name;
// 		}
		
// 		$infom = $this->app->get_apply_form_item ();
// 		foreach ( $infom as $key => $val ) {
// 			$info3 [$val->formid] [$val->value] = $val->formtitle;
// 		}
// 		// var_dump($info2);
		
// 		// 读取国籍缓存
// 		$nationality = CF ( 'nationality' );
		
// 		$country = '';
		
// 		foreach ( $nationality as $key => $value ) {
// 			$country [$key] = $value;
// 		}
		
// 		$data = array (
// 				'arr2' => $arr2,
// 				'arr3' => $arr3,
// 				'info1' => $info1,
// 				'info2' => $info2,
// 				'info3' => $info3,
// 				'country' => $country,
// 				'appid' => $appid 
// 		);
// 		$this->load->view ( '/appmanager/check_info.php', $data );
// 	}
	
// 	/**
// 	 * 个人审核状态下进行申请流程的操作
// 	 */
// 	function check_apply_flow() {
// 		$appid = $this->input->get ( 'id' );
// 		$label = $this->input->get ( 'label_id' );
// 		$label = ! empty ( $label ) ? $label : '';
// 		// 获得申请id和当前所处的状态
// 		$data = array (
// 				'state' => $label 
// 		);
// 		if ($label == 2) {
// 			$action = '资料被打回';
// 			$tips = '资料被打回';
// 		} elseif ($label == 4) {
// 			$action = '拒绝';
// 			$tips = '拒绝';
// 		} elseif ($label == 5) {
// 			$action = '调剂';
// 			$tips = '调剂';
// 		} elseif ($label == 6) {
// 			$action = '预录取';
// 			$tips = '预录取';
// 		} elseif ($label == 7) {
// 			$action = '预录取';
// 			$tips = '预录取';
// 		}
// 		$lists = $this->app->update_app_flow_status ( $appid, $data, $action, $tips );
// 	}
	
// 	/**
// 	 * 附件下载
// 	 */
// 	function attach_download() {
// 		$id = $this->input->get ( 'id' );
// 		$this->load->library ( 'zip' );
// 		// $lists = $this->app->get_attach ( $id );
// 		$lists = $this->db->select ( '*' )->get_where ( 'apply_attachment_info', 'applyid = ' . $id )->result_array ();
// 		if (! empty ( $lists )) {
// 			foreach ( $lists as $k => $v ) {
// 				$data = file_get_contents ( $_SERVER ['DOCUMENT_ROOT'] . $v ['url'] );
// 				$name = mb_convert_encoding ( $k . $v ['truename'], 'GBK', 'UTF-8' );
// 				$this->zip->add_data ( $name, $data );
// 			}
			
// 			$filezip = $this->zip->get_zip ( 'my_backup.zip' );
// 			$this->load->helper ( 'download' );
			
// 			force_download ( 'cucas.zip', $filezip );
// 		} else {
// 			echo '<script>alert("无数据");window.history.go(-1)</script>';
// 			die ();
// 		}
// 	}
	
// 	/**
// 	 * 申请表下载
// 	 */
// 	function apply_form_download() {
// 		$appid = $this->input->get ( 'id' );
// 		$result = $this->app->get_app_infos ( $appid );
// 		$arr2 = array ();
// 		if (! empty ( $result )) {
// 			foreach ( $result as $k1 => $v1 ) {
// 				$arr1 = explode ( '_', $v1->keyid );
// 				// 数值关系
// 				$arr2 [$arr1 [2]] = $v1->value;
// 			}
// 		}
		
// 		// apply_form
// 		$infos = $this->app->get_apply_form ();
		
// 		foreach ( $infos as $key => $val ) {
// 			$info2 [$val->id] ['title'] = $val->title;
// 			$info2 [$val->id] ['type'] = $val->type;
// 			$info2 [$val->id] ['name'] = $val->name;
// 		}
		
// 		// 读取国籍缓存
// 		$nationality = CF ( 'nationality' );
		
// 		$country = '';
		
// 		foreach ( $nationality as $key => $value ) {
// 			$country [$key] = $value;
// 		}
		
// 		$data = array (
// 				'arr2' => $arr2,
// 				'info2' => $info2,
// 				'country' => $country,
// 				'appid' => $appid 
// 		);
		
// 		$html = $this->load->view ( '/appmanager/apply_form.php', $data, true );
		
// 		header ( "Content-Type:application/msword" );
// 		header ( "Content-Disposition:attachment;filename=文档.doc" );
// 		header ( "Pragma:no-cache" );
// 		header ( "Expires:0" );
// 		echo $html;
// 	}
	
// 	/**
// 	 * 凭据用户
// 	 */
// 	function proof() {
// 		$publics = CF ( 'publics', '', CONFIG_PATH );
// 		foreach ( $publics ['programa_unit'] as $key => $value ) {
// 			$programa_unit [$key] = $value;
// 		}
// 		$nationality = CF ( 'nationality', '', 'application/cache/' );
// 		// 凭据表 找到 usersid orderid applyid
// 		// 先获取 凭据表的信息
// 		$proof_all = $this->app->get_proof ();
// 		// 用户信息
// 		if (! empty ( $proof_all )) {
// 			foreach ( $proof_all as $k => $v ) {
// 				$userid [] = $v ['userid'];
// 				// 订单id
// 				$orderid [] = $v ['orderid'];
// 			}
// 			$where_user = 'id IN (' . implode ( ',', $userid ) . ')';
// 			$userinfo_all = $this->db->get_where ( 'student_info', $where_user )->result_array ();
// 			foreach ( $userinfo_all as $key => $val ) {
// 				$userinfo [$val ['id']] = $val;
// 			}
			
// 			// 订单信息
// 			$where_order = 'id IN (' . implode ( ',', $orderid ) . ')';
// 			$order_all = $this->db->get_where ( 'apply_order_info', $where_order )->result_array ();
// 			foreach ( $order_all as $ok => $ov ) {
// 				$orderinfo [$ov ['id']] = $ov;
// 				$applyid [] = $ov ['applyid'];
// 			}
			
// 			// 获取课程信息
// 			$course_all = $this->db->get_where ( 'major', 'id > 0 AND state = 1' )->result_array ();
// 			foreach ( $course_all as $ck => $cv ) {
// 				$course [$cv ['id']] = $cv;
// 			}
			
// 			// 申请表的信息
// 			$where_apply = 'id IN (' . implode ( ',', $applyid ) . ')';
// 			$apply_all = $this->db->get_where ( 'apply_info', $where_apply )->result_array ();
// 			foreach ( $apply_all as $ak => $av ) {
// 				$applyinfo [$av ['id']] = $av;
// 				$applyinfo [$av ['id']] ['course'] = $course [$av ['courseid']];
// 			}
			
// 			// 订单信息 和 申请表信息的组合
// 			foreach ( $orderinfo as $okey => $oval ) {
// 				$orderinfo [$okey] ['apply'] = $applyinfo [$oval ['applyid']];
// 			}
// 		}
		
// 		$this->load->view ( 'master/enrollment/appmanager/proof.php', array (
// 				'userinfo' => ! empty ( $userinfo ) ? $userinfo : array (),
// 				'proof_all' => ! empty ( $proof_all ) ? $proof_all : array (),
// 				'orderinfo' => ! empty ( $orderinfo ) ? $orderinfo : array (),
// 				'applyinfo' => ! empty ( $applyinfo ) ? $applyinfo : array (),
// 				'course' => ! empty ( $course ) ? $course : array (),
// 				'programa_unit' => ! empty ( $programa_unit ) ? $programa_unit : array (),
// 				'nationality' => $nationality 
// 		) );
// 	}
	
// 	/**
// 	 * 查看凭据
// 	 */
// 	function editproof() {
// 		$id = $this->input->get ( 'id' );
// 		$file = $this->db->get_where ( 'credentials', 'id = ' . $id )->result_array ();
// 		$html = $this->load->view ( 'master/enrollment/appmanager/editproof.php', array (
// 				'img' => ! empty ( $file [0] ['file'] ) ? $file [0] ['file'] : '' 
// 		), true );
		
// 		ajaxReturn ( $html, '', 1 );
// 	}
	
// 	/**
// 	 * 凭据通过
// 	 */
// 	function goproof() {
// 		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
// 		$orderid = intval ( trim ( $this->input->get ( 'orderid' ) ) );
// 		$applyid = intval ( trim ( $this->input->get ( 'applyid' ) ) );
// 		$state = intval ( trim ( $this->input->get ( 'state' ) ) );
// 		$userid = intval ( trim ( $this->input->get ( 'userid' ) ) );
// 		if (! empty ( $id ) && ! empty ( $orderid ) && ! empty ( $applyid ) && ! empty ( $state ) && ! empty ( $userid )) {
// 			$flag1 = $this->db->update ( 'credentials', array (
// 					'state' => $state 
// 			), 'id = ' . $id );
			
// 			$flag2 = $this->db->update ( 'apply_info', array (
// 					'paystate' => $state 
// 			), 'id = ' . $applyid );
			
// 			$flag3 = $this->db->update ( 'apply_order_info', array (
// 					'paystate' => $state 
// 			), 'id = ' . $orderid );
			
// 			// 查用户
// 			$user = $this->db->get_where ( 'student_info', 'id = ' . $userid )->result_array ();
// 			// 查 订单
// 			$order = $this->db->get_where ( 'apply_order_info', 'id = ' . $orderid )->result_array ();
// 			// 产申请
// 			$apply = $this->db->get_where ( 'apply_info', 'id = ' . $applyid )->result_array ();
// 			// 查课程
// 			$course = $this->db->get_where ( 'major', 'id = ' . $apply [0] ['courseid'] )->result_array ();
// 			$email = $user [0] ['email'];
			
// 			$usd = $order [0] ['ordermondey'];
// 			$name = $course [0] ['name'];
// 			if ($state == 1) {
// 				$view_html = 'mail/pay_success_email';
// 				$title = 'Payment received';
// 			} else {
// 				$view_html = 'mail/pay_fail_email';
// 				$title = 'Payment failed';
// 			}
// 			$html = $this->load->view ( 'master/enrollment/' . $view_html, array (
// 					'email' => ! empty ( $email ) ? $email : '',
// 					'title' => ! empty ( $title ) ? $title : '',
// 					'usd' => ! empty ( $usd ) ? $usd : '',
// 					'name' => ! empty ( $name ) ? $name : '' 
// 			), true );
// 			$this->_send_email ( $email, $title, $html );
// 			if ($flag1 && $flag2 && $flag3) {
// 				ajaxReturn ( '', '', 1 );
// 			} else {
// 				ajaxReturn ( '', '', 0 );
// 			}
// 		} else {
// 			ajaxReturn ( '', '', 0 );
// 		}
// 	}
// 	/**
// 	 * 不通过
// 	 */
// 	function unproof() {
// 		$id = $this->input->get ( 'id' );
// 		$orderid = $this->input->get ( 'orderid' );
// 		$applyid = $this->input->get ( 'applyid' );
// 		$userid = $this->input->get ( 'userid' );
// 		$courseid = $this->input->get ( 'courseid' );
// 		$flag1 = $this->db->update ( 'credentials', array (
// 				'state' => 2 
// 		), 

// 		'id = ' . $id );
// 		$flag2 = $this->db->update ( 'apply_order_info', array (
// 				'paytype' => 3,
// 				'paystate' => 2 
// 		), 

// 		'id = ' . $orderid );
// 		$flag3 = $this->db->update ( 'apply_info', array (
// 				'paytype' => 3,
// 				'paystate' => 2,
// 				'isproof' => 1 
// 		), 

// 		'id = ' . $applyid );
		
// 		// 查用户
// 		$user = $this->db->get_where ( 'student_info', 'id = ' . $userid )->result_array ();
// 		// 查 订单
// 		$order = $this->db->get_where ( 'apply_order_info', 'id = ' . $orderid )->result_array ();
// 		// 查课程
// 		$course = $this->db->get_where ( 'major', 'id = ' . $courseid )->result_array ();
// 		$email = $user [0] ['email'];
// 		$title = 'Payment failed';
// 		$usd = $order [0] ['ordermondey'];
// 		$name = $course [0] ['title'];
// 		$html = $this->load->view ( 'master/enrollment/mail/pay_success_email.php', array (
// 				'email' => ! empty ( $email ) ? $email : '',
// 				'title' => ! empty ( $title ) ? $title : '',
// 				'usd' => ! empty ( $usd ) ? $usd : '',
// 				'name' => ! empty ( $name ) ? $name : '' 
// 		), true );
// 		$this->_send_email ( $email, $title, $html );
		
// 		if ($flag1 && $flag2 && $flag3) {
// 			ajaxReturn ( '', '', 1 );
// 		} else {
// 			ajaxReturn ( '', '', 0 );
// 		}
// 	}
	
// 	/**
// 	 * 申请结束
// 	 */
// 	function app_over() {
// 		$where = $where = 'apply_info.state = 9';
// 		// 根据状态获得申请列表信息
// 		$lists = $this->app->get_app ( $where );
		
// 		$publics = CF ( 'publics', '', CONFIG_PATH );
		
// 		// 获取状态
// 		$app_state = '';
// 		foreach ( $publics ['app_state'] as $key => $value ) {
// 			$app_state [$key] = $value;
// 		}
		
// 		// 获取课程周期
// 		$programa_unit = '';
		
// 		foreach ( $publics ['programa_unit'] as $key => $value ) {
// 			$programa_unit [$key] = $value;
// 		}
		
// 		// 读取国籍缓存
// 		$nationality = CF ( 'nationality', '', 'application/cache/' );
// 		$country = '';
		
// 		foreach ( $nationality as $key => $value ) {
// 			$country [$key] = $value;
// 		}
		
// 		$data = array (
// 				'lists' => $lists,
// 				'programa_unit' => $programa_unit,
// 				'country' => $country 
// 		);
// 		$this->load->view ( 'master/enrollment/appmanager/app_over', $data );
// 	}
	
// 	/**
// 	 * 所有申请
// 	 */
// 	function app_allof() {
// 		$where = 'apply_info.id > 0';
// 		// 根据状态获得申请列表信息
// 		$lists = $this->app->get_app ( $where );
		
// 		$publics = CF ( 'publics', '', CONFIG_PATH );
		
// 		// 获取状态
// 		$app_state = '';
// 		foreach ( $publics ['app_state'] as $key => $value ) {
// 			$app_state [$key] = $value;
// 		}
		
// 		// 获取课程周期
// 		$programa_unit = '';
		
// 		foreach ( $publics ['programa_unit'] as $key => $value ) {
// 			$programa_unit [$key] = $value;
// 		}
		
// 		// 读取国籍缓存
// 		$nationality = CF ( 'nationality', '', 'application/cache/' );
// 		$country = '';
		
// 		foreach ( $nationality as $key => $value ) {
// 			$country [$key] = $value;
// 		}
		
// 		$data = array (
// 				'lists' => $lists,
// 				'app_state' => $app_state,
// 				'programa_unit' => $programa_unit,
// 				'country' => $country 
// 		);
// 		$this->load->view ( 'master/enrollment/appmanager/app_allof', $data );
// 	}
	
// 	/**
// 	 * 打印通知书确定信息
// 	 */
// 	function print_offter() {
// 		$nationality = CF ( 'nationality' );
// 		$applyid = intval ( trim ( $this->input->get ( 'id' ) ) );
// 		if ($applyid) {
// 			$where = 'apply_info.id = ' . $applyid;
// 			$result = $this->app->get_app ( $where );
// 		}
		
// 		$html = $this->load->view ( '/appmanager/print_offter', array (
// 				'result' => ! empty ( $result ) ? $result [0] : array (),
// 				'nationality' => ! empty ( $nationality ) ? $nationality : array () 
// 		), true );
// 		ajaxReturn ( $html, '', 1 );
// 	}
	
// 	/**
// 	 * 保存通知书
// 	 */
// 	function save_print_offter() {
// 		// Include the main TCPDF library (search for installation path).
// 		require_once $_SERVER ['DOCUMENT_ROOT'] . '/public/tcpdf/tcpdf.php';
		
// 		// create new PDF document
// 		$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
		
// 		// set document information
// 		$pdf->SetCreator ( PDF_CREATOR );
// 		$pdf->SetAuthor ( '' );
// 		$pdf->SetTitle ( '' );
// 		$pdf->SetSubject ( '' );
// 		$pdf->SetKeywords ( '' );
		
// 		// set default header data
// 		$pdf->SetHeaderData ( '', '', '', '', array (
// 				0,
// 				64,
// 				255 
// 		), array (
// 				0,
// 				64,
// 				128 
// 		) );
// 		$pdf->setFooterData ( array (
// 				0,
// 				64,
// 				0 
// 		), array (
// 				0,
// 				64,
// 				128 
// 		) );
		
// 		// set header and footer fonts
// 		$pdf->setHeaderFont ( Array (
// 				PDF_FONT_NAME_MAIN,
// 				'',
// 				PDF_FONT_SIZE_MAIN 
// 		) );
// 		$pdf->setFooterFont ( Array (
// 				PDF_FONT_NAME_DATA,
// 				'',
// 				PDF_FONT_SIZE_DATA 
// 		) );
		
// 		// set default monospaced font
// 		$pdf->SetDefaultMonospacedFont ( PDF_FONT_MONOSPACED );
		
// 		// set margins
// 		$pdf->SetMargins ( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );
// 		$pdf->SetHeaderMargin ( PDF_MARGIN_HEADER );
// 		$pdf->SetFooterMargin ( PDF_MARGIN_FOOTER );
		
// 		// set auto page breaks
// 		$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
		
// 		// set image scale factor
// 		$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
		
// 		// set some language-dependent strings (optional)
// 		if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
// 			require_once (dirname ( __FILE__ ) . '/lang/eng.php');
// 			$pdf->setLanguageArray ( $l );
// 		}
		
// 		// ---------------------------------------------------------
		
// 		// set default font subsetting mode
// 		$pdf->setFontSubsetting ( true );
		
// 		// Set font
// 		// dejavusans is a UTF-8 Unicode font, if you only need to
// 		// print standard ASCII chars, you can use core fonts like
// 		// helvetica or times to reduce file size.
// 		$pdf->SetFont ( 'cid0cs', '', 14, '', true );
		
// 		// Add a page
// 		// This method has several options, check the source code documentation for more information.
// 		$pdf->AddPage ();
		
// 		// set text shadow effect
// 		$pdf->setTextShadow ( array (
// 				'enabled' => true,
// 				'depth_w' => 0.2,
// 				'depth_h' => 0.2,
// 				'color' => array (
// 						196,
// 						196,
// 						196 
// 				),
// 				'opacity' => 1,
// 				'blend_mode' => 'Normal' 
// 		) );
		
// 		// Set some content to print
		
// 		$nationality = CF ( 'nationality' );
// 		$data = $this->input->post ();
// 		$this->lang->load ( 'public', 'english' );
// 		$this->load->helper ( 'language' );
// 		if (! empty ( $data ['type'] ) && $data ['type'] == 1) {
			
// 			$html = $this->load->view ( '/appmanager/offter_short', array (
// 					'data' => ! empty ( $data ) ? $data : array (),
// 					'nationality' => ! empty ( $nationality ) ? $nationality : array () 
// 			), true );
// 		} else {
// 			$html = $this->load->view ( '/appmanager/offter_bfsu', array (
// 					'data' => ! empty ( $data ) ? $data : array (),
// 					'nationality' => ! empty ( $nationality ) ? $nationality : array () 
// 			), true );
// 		}
		
// 		// Print text using writeHTMLCell()
// 		$pdf->writeHTMLCell ( 0, 0, '', '', $html, 0, 1, 0, true, '', true );
		
// 		// ---------------------------------------------------------
		
// 		// Close and output PDF document
// 		// This method has several options, check the source code documentation for more information.
// 		$pdf->Output ( 'example_001.pdf', 'I' );
		
// 		// ============================================================+
// 		// END OF FILE
// 		// ============================================================+
// 	}
	
// 	/**
// 	 * 生成二维码
// 	 */
// 	function made_qr() {
// 		$id = trim ( $this->input->get ( 'id' ) );
// 		if (! empty ( $id )) {
// 			$lists = $this->app->get_app ( 'apply_info.id = ' . $id );
// 			// var_dump($id);
// 			require_once $_SERVER ['DOCUMENT_ROOT'] . '/public/phpqrcode/phpqrcode.php';
// 			$value = 'http://chinese.bfsu.edu.cn/made_qr/fill_form?applyid=' . authcode ( $id, 'ENCODE' );
// 			// $value = 'http://bfsu_xs.com/made_qr/fill_form?applyid='.authcode($id,'ENCODE');
// 			// $name = ! empty ( $lists [0]->enname ) ? $lists [0]->enname : '';
// 			// $idnum = ! empty ( $lists [0]->idnum ) ? $lists [0]->idnum : '';
// 			// $coursename = ! empty ( $lists [0]->name ) ? $lists [0]->name : '';
			
// 			// $value .= '姓名:' . $name;
// 			// $value .= '学号:' . $idnum;
// 			// $value .= '课程名:' . $coursename;
// 			// var_dump($value);
// 			// 引入
// 			$dir = $_SERVER ['DOCUMENT_ROOT'] . '/uploads/qr/' . date ( 'Y' ) . '/' . date ( 'md' ) . '/';
// 			if (! file_exists ( $dir )) {
// 				mk_dir ( $dir );
// 			}
// 			// 生成二维码的图片路径
// 			$filename = $dir . $lists [0]->userid . '.jpg';
// 			$savepath = '/uploads/qr/' . date ( 'Y' ) . '/' . date ( 'md' ) . '/' . $lists [0]->userid . '.jpg';
// 			$this->db->update ( 'apply_info', array (
// 					'qrcode' => $savepath 
// 			), 'id = ' . $id );
// 			/*
// 			 * $errorCorrectionLevel表示纠错级别， 纠错级别越高，生成图片会越大。 L水平 7%的字码可被修正 M水平 15%的字码可被修正 Q水平 25%的字码可被修正 H水平 30%的字码可被修正Size表示图片每个黑点的像素。
// 			 */
// 			$errorCorrectionLevel = "L";
// 			/*
// 			 * 点的大小：1到10 参数$matrixPointSize表示生成图片大小，默认是3； 参数$margin表示二维码周围边框空白区域间距值； 参数$saveandprint表示是否保存二维码并 显示
// 			 */
// 			$matrixPointSize = "4";
// 			QRcode::png ( $value, $filename, $errorCorrectionLevel, $matrixPointSize );
// 			ajaxReturn ( '', '', 1 );
// 		}
// 	}
	
// 	/**
// 	 * 打印PDF版的通知书
// 	 */
// 	function print_offters() {
		
// 		// Include the main TCPDF library (search for installation path).
// 		require_once $_SERVER ['DOCUMENT_ROOT'] . '/public/tcpdf/tcpdf.php';
		
// 		// create new PDF document
// 		$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
		
// 		// set document information
// 		$pdf->SetCreator ( PDF_CREATOR );
// 		$pdf->SetAuthor ( 'Nicola Asuni' );
// 		$pdf->SetTitle ( 'TCPDF Example 001' );
// 		$pdf->SetSubject ( 'TCPDF Tutorial' );
// 		$pdf->SetKeywords ( 'TCPDF, PDF, example, test, guide' );
		
// 		// set default header data
// 		$pdf->SetHeaderData ( PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array (
// 				0,
// 				64,
// 				255 
// 		), array (
// 				0,
// 				64,
// 				128 
// 		) );
// 		$pdf->setFooterData ( array (
// 				0,
// 				64,
// 				0 
// 		), array (
// 				0,
// 				64,
// 				128 
// 		) );
		
// 		// set header and footer fonts
// 		$pdf->setHeaderFont ( Array (
// 				PDF_FONT_NAME_MAIN,
// 				'',
// 				PDF_FONT_SIZE_MAIN 
// 		) );
// 		$pdf->setFooterFont ( Array (
// 				PDF_FONT_NAME_DATA,
// 				'',
// 				PDF_FONT_SIZE_DATA 
// 		) );
		
// 		// set default monospaced font
// 		$pdf->SetDefaultMonospacedFont ( PDF_FONT_MONOSPACED );
		
// 		// set margins
// 		$pdf->SetMargins ( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );
// 		$pdf->SetHeaderMargin ( PDF_MARGIN_HEADER );
// 		$pdf->SetFooterMargin ( PDF_MARGIN_FOOTER );
		
// 		// set auto page breaks
// 		$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
		
// 		// set image scale factor
// 		$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
		
// 		// set some language-dependent strings (optional)
// 		if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
// 			require_once (dirname ( __FILE__ ) . '/lang/eng.php');
// 			$pdf->setLanguageArray ( $l );
// 		}
		
// 		// ---------------------------------------------------------
		
// 		// set default font subsetting mode
// 		$pdf->setFontSubsetting ( true );
		
// 		// Set font
// 		// dejavusans is a UTF-8 Unicode font, if you only need to
// 		// print standard ASCII chars, you can use core fonts like
// 		// helvetica or times to reduce file size.
// 		$pdf->SetFont ( 'dejavusans', '', 14, '', true );
		
// 		// Add a page
// 		// This method has several options, check the source code documentation for more information.
// 		$pdf->AddPage ();
		
// 		// set text shadow effect
// 		$pdf->setTextShadow ( array (
// 				'enabled' => true,
// 				'depth_w' => 0.2,
// 				'depth_h' => 0.2,
// 				'color' => array (
// 						196,
// 						196,
// 						196 
// 				),
// 				'opacity' => 1,
// 				'blend_mode' => 'Normal' 
// 		) );
		
// 		// Set some content to print
// 		$html = 1111111;
		
// 		// Print text using writeHTMLCell()
// 		$pdf->writeHTMLCell ( 0, 0, '', '', $html, 0, 1, 0, true, '', true );
		
// 		// ---------------------------------------------------------
		
// 		// Close and output PDF document
// 		// This method has several options, check the source code documentation for more information.
// 		$pdf->Output ( 'example_001.pdf', 'I' );
		
// 		// ============================================================+
// 		// END OF FILE
// 		// ============================================================+
// 	}
	
// 	/**
// 	 * 下载
// 	 */
// 	function xz() {
// 		$type = $this->input->get ( 'type' );
// 		// 申请id
// 		$id = ( int ) $this->input->get ( 'id' );
// 		$courseid = ( int ) $this->input->get ( 'courseid' );
// 		$userid = ( int ) $this->input->get ( 'userid' );
// 		if (! empty ( $id ) && ! empty ( $courseid )) {
// 			/*
// 			 * $OAADMIN = $this->load->database ( 'cucas', true ); $appid = $OAADMIN->select ( 'applytemplate' )->where ( 'id = ' . $courseid )->get ( 'course' )->result_array (); $applyid = $appid [0] ['applytemplate']; if (empty ( $applyid )) { $applyid = $this->app_detail_model->get_default_form_id ( ( int ) $schoolid ); } $view = 'word_' . $schoolid . '_' . $applyid; $str = ''; $encrpt_str = cucas_base64_encode ( authcode ( $id, 'ENCODE', 'mongo_userinfo', 0 ) ); $str = json_decode ( file_get_contents ( 'http://apply.' . DOHEAD . 'cucas.edu.cn/sync_userinfo/get_by_mongo/' . $encrpt_str ), true );
// 			 */
// 			$data = array ();
// 			$str = $this->db->select ( 'key,value' )->get_where ( 'apply_template_info', 'applyid = ' . $id )->result_array ();
			
// 			foreach ( $str as $key => $v ) {
// 				$data [$v ['key']] = $v ['value'];
// 			}
// 			$userpage = $edu = $family = $work = array ();
// 			$userpage = $data;
			
// 			// 教育情况
// 			if (! empty ( $data ['group_edu'] )) {
// 				$edu = unserialize ( $data ['group_edu'] );
// 			}
// 			// 家庭情况
// 			if (! empty ( $data ['group_family'] )) {
// 				$family = unserialize ( $data ['group_family'] );
// 			}
// 			// 工作情况
// 			if (! empty ( $data ['group_work'] )) {
// 				$work = unserialize ( $data ['group_work'] );
// 			}
			
// 			$public = CF ( 'public', '', CACHE_PATH );
// 			$PreviousEducation = $public ['global_PreviousEducation'];
// 			$country = $public ['global_country'];
			
// 			// echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"
			
// 			// xmlns:w="urn:schemas-microsoft-com:office:word"
			
// 			// xmlns="http://www.w3.org/TR/REC-html40">';
// 			$html = $this->load->view ( 'master/enrollment/appmanager/applyform', array (
// 					'userpage' => $userpage,
// 					'edu' => $edu,
// 					'family' => $family,
// 					'PreviousEducation' => $PreviousEducation,
// 					'country' => $country,
// 					'work' => $work 
// 			), true );
			
// 			header ( "Content-Type:application/msword" );
// 			header ( "Content-Disposition:attachment;filename=文档.doc" );
// 			header ( "Pragma:no-cache" );
// 			header ( "Expires:0" );
// 			echo $html;
// 		}
// 	}
	
// 	/**
// 	 * 预览
// 	 */
// 	function browse() {
// 		$type = $this->input->get ( 'type' );
// 		// 申请id
// 		$id = ( int ) $this->input->get ( 'id' );
// 		$courseid = ( int ) $this->input->get ( 'courseid' );
// 		$userid = ( int ) $this->input->get ( 'userid' );
// 		if (! empty ( $id ) && ! empty ( $courseid )) {
// 			/*
// 			 * $OAADMIN = $this->load->database ( 'cucas', true ); $appid = $OAADMIN->select ( 'applytemplate' )->where ( 'id = ' . $courseid )->get ( 'course' )->result_array (); $applyid = $appid [0] ['applytemplate']; if (empty ( $applyid )) { $applyid = $this->app_detail_model->get_default_form_id ( ( int ) $schoolid ); } $view = 'word_' . $schoolid . '_' . $applyid; $str = ''; $encrpt_str = cucas_base64_encode ( authcode ( $id, 'ENCODE', 'mongo_userinfo', 0 ) ); $str = json_decode ( file_get_contents ( 'http://apply.' . DOHEAD . 'cucas.edu.cn/sync_userinfo/get_by_mongo/' . $encrpt_str ), true );
// 			 */
// 			$data = array ();
// 			$str = $this->db->select ( 'key,value' )->get_where ( 'apply_template_info', 'applyid = ' . $id )->result_array ();
// 			foreach ( $str as $key => $v ) {
// 				$data [$v ['key']] = $v ['value'];
// 			}
// 			/**
// 			 * *取数据
// 			 * var_dump($str);
// 			 */
// 			$userpage = $edu = $family = $work = array ();
// 			$userpage = $data;
			
// 			// 教育情况
// 			if (! empty ( $data ['group_edu'] )) {
// 				$edu = unserialize ( $data ['group_edu'] );
// 			}
// 			// 家庭情况
// 			if (! empty ( $data ['group_family'] )) {
// 				$family = unserialize ( $data ['group_family'] );
// 			}
// 			// 工作情况
// 			if (! empty ( $data ['group_work'] )) {
// 				$work = unserialize ( $data ['group_work'] );
// 			}
			
// 			$public = CF ( 'public', '', CACHE_PATH );
// 			$PreviousEducation = $public ['global_PreviousEducation'];
// 			$country = $public ['global_country'];
			
// 			// echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"
			
// 			// xmlns:w="urn:schemas-microsoft-com:office:word"
			
// 			// xmlns="http://www.w3.org/TR/REC-html40">';
			
// 			$this->load->view ( 'master/enrollment/appmanager/applyform', array (
// 					'userpage' => $userpage,
// 					'edu' => $edu,
// 					'family' => $family,
// 					'PreviousEducation' => $PreviousEducation,
// 					'country' => $country,
// 					'work' => $work 
// 			) );
// 		}
// 	}
	
// 	/**
// 	 * 下载附件
// 	 */
// 	function check_upload() {
// 		// 申请id
// 		$id = trim ( $this->input->get ( 'id' ) );
// 		$courseid = trim ( $this->input->get ( 'courseid' ) );
// 		if (! empty ( $id ) && ! empty ( $courseid )) {
// 			$data2 = $this->db->select ( 'attatemplate' )->get_where ( 'major', 'id = ' . $courseid )->row ();
			
// 			if (empty ( $data2->attatemplate )) {
// 				$data2 = $this->db->select ( 'atta_id' )->get_where ( 'attachments', 'aKind = \'Y\'' )->row ();
				
// 				$where = "atta_id = {$data2->atta_id}";
// 			} else {
// 				$where = "atta_id = {$data2->attatemplate}";
// 			}
			
// 			$data1 = $this->db->select ( '*' )->get_where ( 'attachmentstopic', $where )->result_array ();
			
// 			foreach ( $data1 as $k => $v ) {
// 				$data3 [$v ['aTopic_id']] = $v ['TopicName'];
// 			}
// 			$data = $this->db->select ( '*' )->get_where ( 'apply_attachment_info', 'applyid = ' . $id )->result_array ();
			
// 			$this->load->vars ( 'dataF', $data3 );
// 			$this->load->vars ( 'data', $data );
// 			$html = $this->load->view ( 'master/enrollment/appmanager/check_upload', '', true );
// 			ajaxReturn ( $html, '', 1 );
// 		} else {
			
// 			ajaxReturn ( '', '', 0 );
// 		}
// 	}
	
// 	/**
// 	 * 邮件发送函数
// 	 */
// 	function _send_email($email, $title, $content) {
// 		// 初始化
// 		$this->load->library ( 'mymail' );
// 		$MAIL = new Mymail ();
// 		$MAIL->domail ( $email, $title, $content );
// 	}
// 	/**
// 	 * 导入页面
// 	 */
// 	function tochanel() {
// 		$s = intval ( $this->input->get ( 's' ) );
// 		if (! empty ( $s )) {
// 			$html = $this->_view ( 'tochanel', array (), true );
// 			ajaxReturn ( $html, '', 1 );
// 		}
// 	}
// 	/**
// 	 * 导出模板
// 	 */
// 	function tochaneltenplate() {
// 		$data = $this->app->get_app_fields ();
// 		$this->load->library ( 'sdyinc_export' );
// 		$d = $this->sdyinc_export->shenqing_tochaneltenplate ( $data );
// 		if (! empty ( $d )) {
// 			$this->load->helper ( 'download' );
// 			force_download ( 'shenqing' . time () . '.xlsx', $d );
// 			return 1;
// 		}
// 	}
// 	/**
// 	 * 导出页面
// 	 */
// 	function export_where() {
// 		$major = $this->app->get_major ();
// 		$stu_nationality = $this->app->get_nationality ();
// 		$s = intval ( $this->input->get ( 's' ) );
// 		if (! empty ( $s )) {
// 			$html = $this->_view ( 'export_where', array (
// 					'major' => $major,
// 					'stu_nationality' => $stu_nationality 
// 			), true );
// 			ajaxReturn ( $html, '', 1 );
// 		}
// 	}
// 	/**
// 	 * 导出
// 	 */
// 	function export() {
// 		$where = $this->input->post ();
// 		foreach ( $where as $k => $v ) {
// 			if ($v == 0) {
// 				unset ( $where [$k] );
// 			}
// 		}
// 		$this->load->library ( 'sdyinc_export' );
		
// 		$d = $this->sdyinc_export->do_export_shenqing ( $where );
		
// 		if (! empty ( $d )) {
// 			$this->load->helper ( 'download' );
// 			force_download ( 'shenqing' . time () . '.xlsx', $d );
// 			return 1;
// 		}
// 	}
// 	/**
// 	 * 上传major
// 	 */
// 	function upload_excel() {
// 		// 判断文件类型，如果不是"xls"或者"xlsx"，则退出
// 		if ($_FILES ["file"] ["type"] == "application/vnd.ms-excel") {
// 			$inputFileType = 'Excel5';
// 		} elseif ($_FILES ["file"] ["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
// 			$inputFileType = 'Excel2007';
// 		} else {
// 			echo "Type: " . $_FILES ["file"] ["type"] . "<br />";
// 			echo "您选择的文件格式不正确";
// 			exit ();
// 		}
		
// 		if ($_FILES ["file"] ["error"] > 0) {
// 			echo "Error: " . $_FILES ["file"] ["error"] . "<br />";
// 			exit ();
// 		}
		
// 		$inputFileName = './uploads/tempexcel/' . $_FILES ["file"] ["name"];
// 		if (file_exists ( $inputFileName )) {
// 			// echo $_FILES["file"]["name"] . " already exists. <br />";
// 			unlink ( $inputFileName ); // 如果服务器上存在同名文件，则删除
// 		} else {
// 		}
// 		move_uploaded_file ( $_FILES ["file"] ["tmp_name"], $inputFileName );
// 		echo "Stored in: " . $inputFileName;
// 		$this->load->library ( 'PHPExcel' );
// 		$this->load->library ( 'PHPExcel/IOFactory' );
// 		$this->load->library ( 'PHPExcel/Writer/Excel2007' );
// 		$objReader = IOFactory::createReader ( $inputFileType );
// 		$WorksheetInfo = $objReader->listWorksheetInfo ( $inputFileName );
// 		// 读取文件最大行数、列数，偶尔会用到。
// 		$maxRows = $WorksheetInfo [0] ['totalRows'];
// 		$maxColumn = $WorksheetInfo [0] ['totalColumns'];
		
// 		// 设置只读，可取消类似"3.08E-05"之类自动转换的数据格式，避免写库失败
// 		$objReader->setReadDataOnly ( true );
		
// 		$objPHPExcel = $objReader->load ( $inputFileName );
// 		$sheetData = $objPHPExcel->getSheet ( 0 )->toArray ( null, true, true, true );
// 		// excel2003文件，可使用'$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);'
// 		// excel2007文件使用"getActiveSheet()"方法时会提示出错：对non-object使用了"toArray"方法。
// 		$keywords = $sheetData [1];
// 		$num = count ( $sheetData [1] );
// 		$warning = '上传文件字段格式不正确，请修改后重新上传！<br />';
// 		$columns = array (
// 				'A',
// 				'B',
// 				'C',
// 				'D',
// 				'E',
// 				'F',
// 				'G',
// 				'H',
// 				'I',
// 				'J',
// 				'K',
// 				'L',
// 				'M',
// 				'N',
// 				'O',
// 				'P',
// 				'Q',
// 				'R',
// 				'S',
// 				'T',
// 				'U',
// 				'V',
// 				'W',
// 				'X',
// 				'Y',
// 				'Z',
// 				'AA',
// 				'AB',
// 				'AC',
// 				'AD',
// 				'AE' 
// 		);
// 		$mfields = $this->app->get_app_fields ();
// 		unset ( $mfields ['number'] );
// 		unset ( $mfields ['ordernumber'] );
// 		if ($num != count ( $mfields ) + 1) {
// 			echo '字段个数不匹配';
// 			exit ();
// 		}
// 		$keysInFile = array ();
// 		foreach ( $mfields as $key => $value ) {
// 			$keysInFile [] = $value;
// 		}
// 		foreach ( $columns as $keyIndex => $columnIndex ) {
// 			if ($columnIndex == 'A') {
// 				continue;
// 			}
// 			if ($keywords [$columnIndex] != $keysInFile [$keyIndex]) {
// 				echo $warning . $columnIndex . '列应为' . $keysInFile [$keyIndex] . '，而非' . $keywords [$columnIndex];
// 				unlink ( $inputFileName );
// 				exit ();
// 			}
// 		}
// 		$insert = 'number,';
// 		foreach ( $mfields as $k => $v ) {
// 			if ($k == 'courseid') {
// 				$insert .= $k . ',';
// 				$insert .= 'ordernumber,';
// 			} else {
// 				$insert .= $k . ',';
// 			}
// 		}
// 		$insert = trim ( $insert, ',' );
// 		unset ( $sheetData [1] );
// 		$i = 65;
// 		$str = '';
// 		$ss = 2;
// 		foreach ( $sheetData as $k => $v ) {
// 			$value = '';
// 			$email = $v ['AF'];
// 			$majorid = '';
// 			$app_time = '';
// 			$studentid = '';
// 			$sss = 0;
// 			foreach ( $v as $kk => $vv ) {
// 				if ($kk == 'A') {
// 					$number = build_order_no ();
// 					$value .= '"' . $number . '",';
// 					$studentid = $this->app->get_student_id ( $vv, $email );
// 					if (empty ( $studentid )) {
// 						$str .= $ss . '行的用户名和邮箱有误,该行没有插入<br />';
// 						$sss = 1;
// 						continue;
// 					}
// 					$value .= '"' . $studentid . '",';
// 				} elseif ($kk == 'B') {
// 					$majorid = $this->app->get_majorid ( $vv );
// 					$value .= '"' . $majorid . '",';
// 					$ordernumber = build_order_no ();
// 					$value .= '"SDYI' . $ordernumber . '",';
// 				} elseif ($kk == 'H') {
// 					$vv = $this->get_paytype ( $vv );
// 					$value .= '"' . $vv . '",';
// 				} elseif ($kk == 'D') {
// 					$vv = strtotime ( excelTime ( $vv ) );
// 					$app_time = $vv;
// 					$value .= '"' . $vv . '",';
// 				} elseif ($kk == 'G' || $kk == 'M' || $kk == 'O' || $kk == 'R' || $kk == 'U' || $kk == 'Z' || $kk == 'AD' || $kk == 'AE') {
					
// 					$vv = strtotime ( excelTime ( $vv ) );
// 					$value .= '"' . $vv . '",';
// 				} elseif ($kk == 'I' || $kk == 'J' || $kk == 'K' || $kk == 'L' || $kk == 'N' || $kk == 'V') {
// 					$vv = $vv == '是' ? 1 : 0;
// 					$value .= '"' . $vv . '",';
// 				} elseif ($kk == 'X' || $kk == 'Y') {
// 					$vv = $vv == '未发送' ? - 1 : 1;
// 					$value .= '"' . $vv . '",';
// 				} elseif ($kk == 'Q' || $kk == 'AA') {
// 					$vv = $vv == '未确认' ? - 1 : 1;
// 					$value .= '"' . $vv . '",';
// 				} elseif ($kk == 'P') {
// 					$vv = $this->get_app_state ( $vv );
// 					$value .= '"' . $vv . '",';
// 				} elseif ($kk == 'F') {
// 					$vv = $this->get_paystate ( $vv );
// 					$value .= '"' . $vv . '",';
// 				} elseif ($kk == 'S') {
// 					$vv = $vv == '未交' ? - 1 : 1;
// 					$value .= '"' . $vv . '",';
// 				} elseif ($kk == 'AC') {
// 					$vv = $this->get_scholorstate ( $vv );
// 					$value .= '"' . $vv . '",';
// 				} elseif ($kk == 'AB') {
// 					$email = $vv;
// 				} else {
// 					$value .= '"' . $vv . '",';
// 				}
// 			}
// 			$value = trim ( $value, ',' );
// 			$count = $this->app->check_app ( $studentid, $majorid, $app_time );
// 			if ($count > 0) {
// 				$str .= '<br />excel中的' . $ss . "行记录与数据库重复,改行没有插入";
// 				$ss ++;
// 				continue;
// 			}
// 			if ($sss == 1) {
// 				continue;
// 			}
			
// 			// $insert=explode(',', $insert);
// 			// $value=explode(',', $insert);
// 			// var_dump($insert);
// 			// var_dump($value);exit;
// 			$this->app->insert_fields ( $insert, $value );
// 			$ss ++;
// 			$i ++;
// 		}
// 		if ($str != '') {
// 			echo $str;
// 		}
// 	}
// 	function get_app_state($str) {
// 		$publics = CF ( 'publics', '', CONFIG_PATH );
// 		foreach ( $publics ['app_state'] as $key => $value ) {
// 			if ($value == $str) {
// 				return $key;
// 			}
// 		}
// 	}
// 	function get_paystate($str) {
// 		switch ($str) {
// 			case '未支付' :
// 				$s = 0;
// 				break;
// 			case '成功' :
// 				$s = 1;
// 				break;
// 			case '失败' :
// 				$s = 2;
// 				break;
// 			case '待确认' :
// 				$s = 3;
// 				break;
// 		}
// 		return $s;
// 	}
// 	function get_paytype($str) {
// 		switch ($str) {
// 			case 'paypal' :
// 				$s = 1;
// 				break;
// 			case 'payease' :
// 				$s = 2;
// 				break;
// 			case '凭据' :
// 				$s = 3;
// 				break;
// 		}
// 		return $s;
// 	}
// 	function get_scholorstate($str) {
// 		$s = null;
// 		switch ($str) {
// 			case '待审核' :
// 				$s = 0;
// 				break;
// 			case '通过' :
// 				$s = 1;
// 				break;
// 			case '不通过' :
// 				$s = 2;
// 				break;
// 		}
// 		return $s;
// 	}
// 	/**
// 	 * 获取 奖学金的 全部
// 	 */
// 	function get_scholorshipapply() {
// 		$data = array ();
// 		// 奖学金开关
// 		$scholarship_on = CF ( 'scholarship', '', CONFIG_PATH );
// 		if (! empty ( $scholarship_on ) && $scholarship_on ['scholarship'] == 'yes') {
// 			$scholarship = $this->db->select ( '*' )->get_where ( 'scholarship_info', 'id > 0' )->result_array ();
// 			if (! empty ( $scholarship )) {
// 				foreach ( $scholarship as $k => $v ) {
// 					$data [$v ['id']] = $v ['title'];
// 				}
// 			}
// 		}
// 		return $data;
// 	}
// }