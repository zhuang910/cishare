<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * @name 		申请管理-全部申请
 * @package 	apply
 * @author 		Laravel
 * @copyright   
 **/
class change_app_status extends Master_Basic {
	protected $pledge_on = 0;
	protected $pledge_fees = 0;
	protected $yjdw = '';
	protected $scholorshipapply = array();
	/**
	 * 全部申请
	 **/
	function __construct() {
		parent::__construct ();
		$this->load->library ( 'sdyinc_email' );
		$this->load->model (  'master/enrollment/change_app_status_model' );
			$this->load->model ( 'student/fee_model' );
		//查询是否  交押金
		
		$pledge = CF('pledge','',CONFIG_PATH);
		if(!empty($pledge) && $pledge['pledge'] == 'yes'){
			$this->pledge_on = 1;
			$this->pledge_fees = $pledge['pledgemoney'];
			if($pledge['pledgeway'] == 'pledgeusd'){
				$this->yjdw = 'USD';
			}else if($pledge['pledgeway'] == 'pledgeusd'){
				$this->yjdw = 'RMB';
			}
		}
		
		$this->scholorshipapply = $this->get_scholorshipapply();
		$this->load->vars('scholorshipapply',$this->scholorshipapply);
		
	}
	
	//初始化
	function index() {
		$id = intval ( $this->input->get ( 'id' ) );
		$label_id = intval ( $this->input->get ( 'label_id' ) );
		
		if ($id && $label_id||!empty($id)&&$label_id==0 ) {
			//根据唯一ID查询对应记录
			$result = $this->change_app_status_model->get_ones ( $id );
			
			if (! empty ( $result )) {
				//此处是为了处理未提交的特殊情况
				if ($label_id==0) {
					$this->load->vars ( 'label_id', '0');
				}else{
					$this->load->vars ( 'label_id', $label_id);
				} 
				$this->load->vars ( 'action', 'submit_app_status' );
				$this->load->vars ( 'result', $result );
				
				//给模板传递操作权限下拉列表框变量
				//但状态label_id==6 及预录取状态的时候且未交押金的情况下，
				//老师要进行下一步操操作的时候之提示老师当前状态无修改状态按钮
				
				if($result->status<9){//审核材料阶段操作条件
					//资料未移交
				    if ($result->status==0) {
					   $scount=$this->operate_authority($label_id,-1,-1);
				     }elseif ($result->status==6 && $result->deposit_state==-1){
				     	$scount=$this->operate_authority($label_id,1,-1);
				     }else{
				     	$scount=$this->operate_authority($label_id,$result->isdeposit,$result->ispageoffer);
				     }
				
					if(count($scount)>1){
						$this->load->vars ( 'obj_select', $this->obj_select($scount,'state'));
					}else{
						$this->load->vars ( 'obj_select', '');
					}
				}else{//跟进阶段阶段操作条件											 
					$scount=$this->operate_authority_1($label_id);
					if(count($scount)){
						$this->load->vars ( 'obj_select', $this->obj_select($scount,'state'));
					}else{
						$this->load->vars ( 'obj_select', 0);
					}
				}
				$html = $this->load->view ( 'master/enrollment/appmanager/change_app_status', '', true );
				ajaxReturn ( $html, '', 1 );
			}
			ajaxReturn ( '', '所查数据不存在', 0 );
		}
		ajaxReturn ( '', '缺少必要参数', 0 );
	}

	
	//将传入数组封装为下拉列表框返回
	//参数：$select_array=传入一维数组；$select_id=下拉列表框ID值；$select_class=下拉列表框类名称
	function obj_select($select_array='',$select_id='',$select_class=''){
		$string = '<select id="'.$select_id.'" name="'.$select_id.'" class="'.$select_class.'">';
		foreach ( $select_array as $key => $val ) {
            if($key==5){
                continue;
            }
			$string.='<option value="'.$key.'">'.$val.'</option>';
		}
		$string.='</select>';
		return $string;
		
	}
	
	/* 审核材料阶段不同标签下操作权限*/
	/* 参数：$label_id=标签识别变量;$paperdocumentyes=是否需要纸质材料,$onlineappyes=是否需要网申*/
	function operate_authority($label_id,$paperdocumentyes,$onlineappyes){
		
		if($this->pledge_on == 1){
			//定义操作权限数组
			$authority = array(
					0  =>   array(
							'0' => '请选择',
							'1' => '资料提交'
					),
					1   =>  array(							//审核中-模板
							'' => '请选择',
							'2'=> '打回',
							'4'=> '拒绝',
							'5'=> '调剂',
							'6'=> '预录取',
							'7'=> '录取'
					),
					2	=>  array(   	 			//网申中-模板
								
					),
					3	=>  array(   	 //等待纸质材料中-模板
							'' => '请选择',
							'2'=> '打回',
							'4'=> '拒绝',
							'5'=> '调剂',
							'6'=> '预录取',
							'7'=> '录取'
					),
					4	=>  array(   	 //打回中-模板
			
					),
					5	=>  array(   	 //调剂
							
					) ,
					6   =>  array(
							''=> '请选择',
							
							'7'=> '录取'
					),
					7 => array()
						
			);
		}else{
			//定义操作权限数组
			$authority = array(
					0  =>   array(
							'0' => '请选择',
							'1' => '资料提交'
					),
					1   =>  array(							//审核中-模板
							'' => '请选择',
							'2'=> '打回',
							'4'=> '拒绝',
							'5'=> '调剂',
							'7'=> '录取'
					),
					2	=>  array(   	 			//网申中-模板
								
					),
					3	=>  array(   	 //等待纸质材料中-模板
							'' => '请选择',
							'2'=> '打回',
							'4'=> '拒绝',
							'5'=> '调剂',
							'7'=> '录取'
					),
					4	=>  array(   	 //打回中-模板
			
					),
					5	=>  array(   	 //调剂
							
					) 
				
						
			);
		}
			
		
		//将操作权限数组根据条件转化为一维数组，并返回
		
		if(isset($authority[$label_id][$paperdocumentyes][$onlineappyes])){
			return $authority[$label_id][$paperdocumentyes][$onlineappyes];
		}else if(isset($authority[$label_id][$paperdocumentyes])){
			return $authority[$label_id][$paperdocumentyes];
		}else if(isset($authority[$label_id])){
			return $authority[$label_id];
		}else{
			return array('error'=>'error');
		}
	}
	
	
	/* 跟进结果阶段不同标签下操作权限*/
	/* 参数：$label_id=标签识别变量;$paperdocumentyes=是否需要纸质材料,$onlineappyes=是否需要网申*/
	function operate_authority_1($label_id){
		
		//定义操作权限数组
		$authority = array(
					1   =>  array(		 //等待结果
								  ''=>'请选择',
								  '15'=>'通过',
								  '11'=>'打回',
								  '13'=>'拒绝',
								  '14'=>'调剂'
								  ), 
					2	=>  array(   	 //通过
								 ''=>'请选择',
								 '16'=>'发送E-OFFER'
								  ),
					3	=>  array(   	 //E-offer已发送
								  ''=>'请选择'
								  ),
					4	=>  array(   	 //打回
								 ''=>'请选择',
								 '13'=>'拒绝',
								 '14'=>'调剂',
								 '15'=>'通过'
							
								  ),
					5	=>  array(   	 //拒绝
								 ''=>'请选择'
								  ),
					6	=>  array(   	 //调剂
								 ''=>'请选择'
								  ), 
					7	=>  array(   	 //即时跟进
								 ''=>'请选择',
								  '15'=>'通过',
								  '11'=>'打回',
								  '13'=>'拒绝',
								  '14'=>'调剂'
								  ) 
				
				);	
		
		//将操作权限数组根据条件转化为一维数组，并返回
		if(isset($authority[$label_id])){
			return $authority[$label_id];
		}else{
			return array('error'=>'error');
		}
	}
	
	/*修改申请数据表状态*/
	function submit_app_status(){
		
		//获取申请ID
		$id = intval ( $this->input->post ( 'id' ) );
		
		$action_www = array(     //操作日志数组
		        '1'=> '资料提交',
				'2'=>'材料审核不合格',
				'4'=>'电子材料审核通过',
				'5'=>'材料审核通过 - 待发送',
				'6'=>'预录取 - 发送交押金通知',
				'7'=> '录取',
				'10'=>'申请发送学校 - 等待结果中',
				'11'=>'打回补充材料',
				'13'=>'拒绝申请',
				'14'=>'调剂申请',
				'15'=>'申请被接受',
				'16'=>'发送电子录取通知',
				'17'=>'发送纸质通知书'
		); 
		//操作日志数组
		$action_oa = array( 
				'0' => '打回提交',   
		        '1'=> '提交资料资料审核',
				'2'=>'打回',
				'4'=>'电子材料合格',
				'5'=>'材料审核通过 - 待发送',
				'6'=>'预录取 - 发送交押金通知',
				'7'=> '录取操作',
				'10'=>'申请发送学校 - 等待结果中',
				'11'=>'打回 -补充材料',
				'13'=>'拒绝申请',
				'14'=>'调剂申请',
				'15'=>'申请被接受',
				'16'=>'发送电子录取通知',
				'17'=>'发送纸质通知书'
		);
		if(!empty($id)){
			$status = intval ( $this->input->post ( 'state' ) );		//准备前端更新数据 - 状态
// 			if(empty($status)){
// 				ajaxReturn('','',0);
// 			}
			$insertetips = trim ( $this->input->post ( 'insertetips' ) );		//准备前端更新数据 - 是否将文本域内容一起发送给前端用户
			$cucaseoffer = trim ( $this->input->post ( 'cucaseoffer' ) );		//准备前端更新数据 - 是否使用CUCAS E-OFFER
			$tips = !empty($insertetips)?trim ( $this->input->post ( 'tips' ) ):"";		//准备前端更新数据 - 提醒
			$update_wwwarray=array(
							'state' => $status,
							'tips' => $tips
							); 	
			if($status==16){
				//上传E-OFFER
				$data_eoffer = $this ->upload_eoffer();
				foreach ($data_eoffer as $val){
						$update_wwwarray['eoffer'] = $val;
				}
			}
			//执行前端状态，提醒和日志更新			
			$result_www = $this->change_app_status_model->update_www_app_info ( $id, $update_wwwarray, $action_www[$status]);  
			
			if ($result_www) {
				
				//准备后端更新数据
				$update_oaarray = $this->_save_data ();   
				
				        
				if($status==16){
					foreach ($data_eoffer as $val){
						$update_oaarray['eoffer'] = $val;
					}
				}
				
				//执行后端状态和日志更新
				$result_oa = $this->change_app_status_model->update_oa_app_info ( $id, $update_oaarray, $action_oa[$status], $tips); 
				 
				if($result_oa){
										
					//执行发送邮件
					
					//根据唯一ID查询对应申请记录
					$app_info = $this->change_app_status_model->get_relation_one ( $id );
					
					//准备基础数据-邮件模板中需要的通用变量
					$val_arr = array(
							'email' =>$app_info->email,
							'name' =>$app_info->englishname,
							'school_name' => 'BEIJING INFORMATION TECHNOLOGY COLLEGE',
							'tip_content' => $tips,
							'student_name' => $app_info->enname,
							'program_name' => $app_info->name,
							
					);
					//01  资料有未提交进入到审核资料审核状态
					//if($status==1 && empty($update_oaarray['edocument'])){
					if($status==1){
						//$send_ok = $this->_send_mail($app_info->email,'','Processed_Later_1',$val_arr);
						$MAIL = new sdyinc_email ();
						$MAIL->dot_send_mail ( 24,$app_info->email,$val_arr);
					}
					//1-电子材料合格-不发送材料准备或邮寄通知
					/* if($status==4 && empty($update_oaarray['edocument'])){
						
						$send_ok = $this->_send_mail($app_info->email,'','Processed_Later_1',$val_arr);
					} */
					
					//2-处于拒绝状态
					if($status==4){
					
						//$send_ok = $this->_send_mail($app_info->email,'','Rejected',$val_arr);
						$MAIL = new sdyinc_email ();
						$MAIL->dot_send_mail ( 13,$app_info->email,$val_arr);
					}
					
					//2-预录取-交押金通知
					if($status==6){
						
						// 查询是否 交押金
							
						$pledge = CF ( 'pledge', '', CONFIG_PATH );
						if (! empty ( $pledge ) && $pledge ['pledge'] == 'yes') {
							$pledge_ons = 1;
							$pledge_feess = $pledge ['pledgemoney'];
							if ($pledge ['pledgeway'] == 'pledgeusd') {
								$yjdws = 'USD';
							} else if ($pledge ['pledgeway'] == 'pledgermb') {
								$yjdws = 'RMB';
							}
						$grf_userid=$this->db->select('userid')->get_where('apply_info','id = '.$id)->row_array();
						//先插入所有收支表
						$budget=array(
							'userid'=>$grf_userid['userid'],
							'budget_type'=>1,
							'type'=>5,
							'payable'=>$pledge_feess,
							'paystate'=>0,
							'createtime'=>time(),
							'applyid'=>$id
							);
						$budgetid=$this->fee_model->insert_budget($budget);
						$max_cucasid = build_order_no ();
						//再生成所有订单表
						$order_info=array(
								'budget_id'=>$budgetid,
								'createtime'=>time(),
								'ordernumber'=>'ZUST'.$max_cucasid,
								'ordertype'=>5,
								'userid'=>$grf_userid['userid'],
								'ordermondey'=>$pledge_feess,
								'paystate'=>0,
								'applyid'=>$id

							);
						$orderid=$this->fee_model->insert_order($order_info);
							$datadep = array (
									'userid' =>$grf_userid['userid'],
									'applyid' => $id,
									'registeration_fee' => $pledge_feess,
									'danwei' => $yjdws,
									'applytime' => time (),
									'paystate' => 0,
									'lasttime' => time (),
									'order_id'=>$orderid
							);
							$this->db->insert ( 'deposit_info', $datadep );
							$this->db->update ( 'apply_info', array (
									'deposit_fee' => $pledge_feess
							), 'id = ' . $id );
						}
						
						
						
						$deposit_url = 'http://'.$_SERVER['HTTP_HOST'].'/en/pay_pa/index?applyid='.cucas_base64_encode($id.'-5');
						$val_arr = array(
								'depositmoney' => $this->pledge_fees,
								'name' => $app_info->englishname,
								'student_name' =>$app_info->enname,
								'school_name' => 'BEIJING INFORMATION TECHNOLOGY COLLEGE',
								'dw' => $yjdws,
								'deposit_url' => $deposit_url,
								'tip_content' => $tips,
								'email' =>$app_info->email,
						);
						
							
						//$send_ok = $this->_send_mail($app_info->email,'','Accepted',$val_arr);
						$MAIL = new sdyinc_email ();
						$MAIL->dot_send_mail ( 11,$app_info->email,$val_arr);
					}
					
					
					/**
					 * 发送offer
					
					if ($status==7 && $app_info->ispageoffer==-1) {
						
						$send_ok = $this->_send_mail($app_info->email,$attach,'Tourist_Visa',$val_arr);
						
					}else{
						$send_ok = $this->_send_mail($app_info->email,'','Tourist_Visa',$val_arr);
						
					}
					 */
					if ($status==7) {
					
						//$send_ok = $this->_send_mail($app_info->email,'','Tourist_Visa',$val_arr);
						$MAIL = new sdyinc_email ();
						$MAIL->dot_send_mail ( 10,$app_info->email,$val_arr);
					
					}
					//3-发送邮寄材料通知
					/* if($status==4 && $update_oaarray['edocument']==2){
							
						$send_ok = $this->_send_mail($app_info->email,'','Mail_Paper',$val_arr);
					} */
					
					//4-材料合格-待发送
					//缺邮件
					if($status==5){
							
					//$send_ok = $this->_send_mail($app_info->email,'','Processed_Later',$val_arr);
						$MAIL = new sdyinc_email ();
 						$MAIL->dot_send_mail ( 25,$app_info->email,$val_arr);
					}
		
					//6-打回
					if($status==2){
						//修改 申请状态的信息
						$this->db->update('apply_info',array(
								'isinformation' => 0,
								'isatt' => 0
						),'id = '.$id);
						$val_arr['link'] = cucas_base64_encode($app_info->courseid);
						
						//$send_ok = $this->_send_mail($app_info->email,'','Pending',$val_arr);
						$MAIL = new sdyinc_email ();
						$MAIL->dot_send_mail ( 12,$app_info->email,$val_arr);
					}
					
					//发送邮件结束
// 					if($send_ok){
// 						ajaxReturn ( 'back', '更新成功', 1 );
// 					}else{
// 						ajaxReturn ( '', '邮件发送失败', 1 );
// 					}

					//操作日志数组
					$operation = array(
							'0' => '打回提交',
							'1'=> '提交资料资料审核',
							'2'=>'打回',
							'4'=>'拒绝',
							'5'=>'调剂',
							'6'=>'预录取 - 发送交押金通知',
							'7'=> '录取操作',
					);

					$datalog = array (
							'adminid' => $_SESSION ['master_user_info']->id,
							'adminname' => $_SESSION ['master_user_info']->username,
							'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改申请用户为' .$app_info->email.$operation[$status],
							'ip' => get_client_ip (),
							'lasttime' => time (),
							'type' => 2,
							'appid' => $id
					);
					if (! empty ( $datalog )) {
						$this->adminlog->savelog ( $datalog );
					}
					ajaxReturn ( '', '邮件发送失败', 1 );
				}else{
					ajaxReturn ( '', '后端数据更新失败，请重试', 0 );
				}
			}else{
				ajaxReturn ( '', '前端数据更新失败，请重试', 0 );
			}
		}
		ajaxReturn ( '', '参数缺失', 0 );
	}
	
	/**
	 * 发送邮件
	 *$send_name： 收件人
	 *$send_attach：  附件路径
	 *$param: 邮件标题数组的键名,邮件模板的名称
	 *$val_arr：发送内容模板中所需变量
	 * @return $flag true|false 发送邮件成功或失败
	 * 
	 */
// 	private function _send_mail($send_name ='' ,$send_attach ='' ,$param='', $val_arr = array()) {
		
// 		//载入模板所需变量
// 		$this->load->vars ( 'val_arr', $val_arr );
		
// 		//载入标题数组
// 		$title_array = CF ( 'web_email_oa','',CONFIG_PATH);
		
// 		//邮件标题赋值
// 		$mail_title = $title_array[$param]['title'];
		
// 		//邮件内容赋值
// 		$this->view = 'master/enrollment/mail/';
// 		$mail_content = $this->_view ( $param, '', true );
		
// 		//发送邮件开始
		
// 		//初始化
// // 		$this->load->library ( 'mymail' );
// // 		$MAIL = new Mymail();
		
// 		//开始发送邮件
// 		//邮件发送函数的参数示例：domail($sentTo,$title=null,$content=null,$attach=null,$reply_to=null,$cc=null,$bcc=null)
// 		if(!empty($send_attach)) { 
			
// 			//发送站内信
// 			$send_message = $this->send_message($mail_title,$mail_content,$send_name);
// 			//包含附件
// 			$send_attach = $_SERVER['DOCUMENT_ROOT'].'/../../uploads'.$send_attach;
// 			return $MAIL->domail($send_name,$mail_title,$mail_content,$send_attach);
			
// 		}else{
			
// 			//发送站内信
// 			//$send_message = $this->send_message($mail_title,$mail_content,$send_name);
			
// 			//不包含附件
// 			//return $send_name.$mail_title.$mail_content;
// 			return $MAIL->domail($send_name,$mail_title,$mail_content);
// 		}		
// 	}
	
	
	/**
	 * 发送站内信
	 *$send_name： 收件人
	 *$param: 邮件标题数组的键名,邮件模板的名称
	 *$val_arr：发送内容模板中所需变量
	 * @return $flag true|false 发送邮件成功或失败
	 *
	 */
	/* private function send_message($mail_title ='' ,$mail_content ='' , $send_name='') {
		$result = $this->change_app_status_model->send_message_info ( $mail_title , $mail_content , $send_name );  //发送消息
	} */
	/**
	 * 返回保存数据
	 *
	 * @return multitype:array |boolean
	 */
	private function _save_data() {
		$post = $this->input->post ();
		$fields = $this->change_app_status_model->field_info ();
		$save = array ();
		if ($fields && $post) {
			foreach ( $post as $f => $val ) {
				if (in_array ( $f, $fields )) {
					$save [$f] = $val;
				}
			}
			return $save;
		}
		return false;
	}
	
	/*上传文件调用*
	 * 
	 * 
	 */
	function upload_eoffer(){
		
		$upload_path = UPLOADS .'/oa/eoffer/';
		// 创建文件夹
		$data = array();
		$this->mkdirs ( $upload_path );
		if (sizeof ( $_FILES )) {
			foreach ( $_FILES as $key => $value ) {
				$fileupload = $this->_do_upload ( $key, $upload_path );
				if ($fileupload)
					$data [$key] = '/oa/eoffer/' . $fileupload ['upload_data'] ['file_name'];
			}
		}
		return $data;
	}
	
	/**
	 * 上传方法
	 */
	function _do_upload($key, $path) {
		$config ['upload_path'] = $path;
		$config ['allowed_types'] = 'gif|jpeg|jpg|png';
		$config ['max_size'] = '10000';
		$config ['encrypt_name'] = TRUE;
		
		$this->load->library ( 'upload', $config );
		if (! $this->upload->do_upload ( $key )) {
			ajaxReturn('',$this->upload->display_errors ('',''),0);
			// return false;
			return $config;
		} else {
			$data = array (
					'upload_data' => $this->upload->data () 
			);
			return $data;
		}
	}
	// 创建文件夹
	function mkdirs($dir) {
		if (! is_dir ( $dir )) {
			if (! $this->mkdirs ( dirname ( $dir ) )) {
				return false;
			}
			if (! mkdir ( $dir, 0777 )) {
				return false;
			}
		}
		return true;
	}
	

	//初始化
	function add_remark() {
		$id = intval ( $this->input->get ( 'id' ) );
		$result = $this->db->select('id,remark')->get_where('apply_info','id = '.$id)->row();
		$html = $this->load->view ( 'master/enrollment/appmanager/add_remark', array(
				'result' => $result
		), true );
		ajaxReturn ( $html, '', 1 );

	}
	
	//添加学号
	function add_number() {
		$id = intval ( $this->input->get ( 'id' ) );
		$label_id = intval ( $this->input->get ( 'label_id' ) );
		
		$html = $this->load->view ( 'master/enrollment/appmanager/add_number', array('id' => $id), true );
		ajaxReturn ( $html, '', 1 );
	
	}
	

	//设置中国政府奖学金
	function scholorship_set() {
		$id = intval ( $this->input->get ( 'id' ) );
		$label_id = intval ( $this->input->get ( 'label_id' ) );
		//中国政府奖学金
		$scholarship = $this->db->select('*')->get_where('scholarship_info','id > 0 AND state = 1')->result_array();
		//申请信息
		$applyInfo = $this->db->select('scholorstate,scholorshipid')->get_where('apply_info','id = '.$id)->row();
		$html = $this->load->view ( 'master/enrollment/appmanager/scholorship_set', array('id' => $id,'applyinfo' => !empty($applyInfo)?$applyInfo:array(),'scholarship'=>!empty($scholarship)?$scholarship:array()), true );
		ajaxReturn ( $html, '', 1 );
	
	}
	
	/**
	 * 执行 中国政府奖学金 设置
	 */
	function do_scholorship_set(){
		$scholorstate = intval(trim($this->input->post('scholorstate')));
		$scholorshipid = intval(trim($this->input->post('scholorshipid')));
		$remark = trim($this->input->post('remark'));
		$id = intval(trim($this->input->post('id')));
		if(!empty($scholorstate) && !empty($id) && !empty($scholorshipid)){
			if($scholorstate == -1){
				$scholorstate = 0;
			}
			
			//需要两部操作 1 更新申请表的 第二步 更新 奖学金申请表的 如果奖学金申请表中没有数据 则 先插入 数据
			//先获得 申请表的数据
			$applyInfo = $this->db->select('*')->get_where('apply_info','id = '.$id)->row();
			//判断申请信息 中 是否申请了 中国政府奖学金  没有的话 更新一下
			$this->db->update('apply_info',array('scholorshipid' => $scholorshipid,'scholorstate' => $scholorstate,'isscholar' => 1));
			//查询  奖学金申请表里是否有数据 
			$result = $this->db->select('*')->get_where('applyscholarship_info','userid = '.$applyInfo->userid.' AND scholarshipid = '.$scholorshipid.' AND type = 3')->row();
			if(!empty($result)){
				//更新
				$flag = $this->db->update('applyscholarship_info',array('state' => $scholorstate,'remark' => $remark),'id = '.$result->id);
			}else{
				//根据userid 查询用户信息
				$info = $this->db->select('enname,passport,email,nationality')->get_where('student_info','id = '.$applyInfo->userid)->row();
				//插入
				$max_number = build_order_no ();
				$dataA = array(
						'number' => $max_number,
						'userid' => $applyInfo->userid,
						'scholarshipid' => $scholorshipid,
						'type' => 3,
						'name' => ! empty ( $info->enname ) ? $info->enname : '',
						'passport' => ! empty ( $info->passport ) ? $info->passport : '',
						'email' => ! empty ( $info->email ) ? $info->email : '',
						'nationality' => ! empty ( $info->nationality ) ?$info->nationality : '',
						'applytime' => time (),
						'isstart' => 1,
						'isinformation' => 1,
						'isatt' => 1,
						'issubmit' => 1,
						'state' => $scholorstate,
						'lasttime' => time () 
				);
				$flag = $this->db->insert('applyscholarship_info',$dataA);
			}
			if($flag){
				ajaxReturn('','',1);
			}else{
				ajaxReturn('','',0);
			}
		}else{
			ajaxReturn('','',0);
		}
	}
	
	/**
	 * 添加学号
	 */
	function do_add_number(){
		$this->load->model('master/enrollment/edit_app_info_model');
		$id = intval(trim($this->input->post('id')));
		$studentid = intval(trim($this->input->post('studentid')));
		if($id && $studentid){
			$flag = $this->edit_app_info_model->update_app_info($id,array('studentid'=>$studentid));
			//写入日志
			//组织信息 首先 查用户的id
			$userid = $this->db->select('userid')->get_where('apply_info','id = '.$id)->row();
			//查询邮箱
			$email = $this->db->select('email')->get_where('student_info','id = '.$userid->userid)->row();
				
			$datalog = array (
					'adminid' => $_SESSION ['master_user_info']->id,
					'adminname' => $_SESSION ['master_user_info']->username,
					'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '给申请用户为' .$email->email.'分配学号为:'.$studentid,
					'ip' => get_client_ip (),
					'lasttime' => time (),
					'type' => 2,
					'appid' => $id
			);
			if (! empty ( $datalog )) {
				$this->adminlog->savelog ( $datalog );
			}
			if($flag){
				ajaxReturn('','',1);
			}else{
				ajaxReturn('','',0);
			}
		}else{
			ajaxReturn('','',0);
		}
	}
	
	//初始化
	function ckaddress() {
		$id = intval ( $this->input->get ( 'id' ) );
		$userid = intval ( $this->input->get ( 'userid' ) );
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		$result = $this->db->select('*')->order_by('id DESC')->limit(1)->get_where('app_getoffer','appid = '.$id.' AND userid = '.$userid)->row();
		
		$html = $this->load->view ( 'master/enrollment/appmanager/ckaddress', array(
				'result' => $result,
				'nationality' => $nationality
		), true );
		ajaxReturn ( $html, '', 1 );
	
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
	
}