<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * @name 		申请管理-全部申请
 * @package 	apply
 * @author 		Laravel
 * @copyright   
 **/
class Change_studentscholarship_status extends Master_Basic {
	
	/**
	 * 全部申请
	 **/
	function __construct() {
		parent::__construct ();
		$this->load->library ( 'sdyinc_email' );
		
		$this->load->model (  'master/scholarship/change_scholarship_status_model' );
		
		
	}
	
	//初始化
	function index() {
		$id = intval ( $this->input->get ( 'id' ) );
		$label_id = intval ( $this->input->get ( 'label_id' ) );
		
		if ($id) {
			//根据唯一ID查询对应记录
			$result = $this->change_scholarship_status_model->get_ones ( $id );
			
			if (! empty ( $result )) {
				//0 未完成 1 审核中 2 打回 3 打回提交 4 拒绝 5 通过 6 结束
				//定义操作数组
				$opeate = array(
						0 => array(
								4 => '拒绝',
								5 => '通过',
						),
						
						4 => array(
								5 => '通过',
						),
						5 => array(
								4 => '拒绝',
						),
						
				);
				
				
				$html = $this->load->view ( 'master/scholarship/change_app_status', array('result' => !empty($result)?$result:array(),'opeate' => $opeate,'label_id' => $label_id), true );
				ajaxReturn ( $html, '', 1 );
			}
			ajaxReturn ( '', '所查数据不存在', 0 );
		}
		ajaxReturn ( '', '缺少必要参数', 0 );
	}


	
	/*修改申请数据表状态*/
	function submit_app_status(){
	
		//获取申请ID
		$id = intval ( $this->input->post ( 'id' ) );
		$status = intval ( $this->input->post ( 'state' ) );		//准备前端更新数据 - 状态
		
		if($status && $id){
			
			$insertetips = trim ( $this->input->post ( 'insertetips' ) );		//准备前端更新数据 - 是否将文本域内容一起发送给前端用户
			$tips = !empty($insertetips)?trim ( $this->input->post ( 'tips' ) ):"";		//准备前端更新数据 - 提醒
		
			//得到数据 修改状态 发邮件
			//修改状态
			if($status == 2){
				$data_status = array(
						'state' => $status,
						'isinformation' => 0,
						'isatt' => 0,
						'issubmit' => 0
				);
			}else{
				$data_status = array(
						'state' => $status,
				);
			}
			
			
			$flag = $this->db->update('applyscholarship_info',$data_status,'id = '.$id);
			if($flag){
				//发邮件
				//根据唯一ID查询对应申请记录
				$email_array = array(
						1 => 42,
						2 => 38,
						4 => 39,
						5 => 40,
						6 => 41
						
				);
				$app_info = $this->change_scholarship_status_model->get_relation_one ( $id );
			
				$MAIL = new sdyinc_email ();
				
				$val_arr  = array(
						'email' =>$app_info->email,
						'name' =>$app_info->title,
						'school_name' => 'Zhejiang University of Science and Technology',
						'tip_content' => $tips,
						'student_name' => $app_info->enname,
				);
				$MAIL->dot_send_mail ( $email_array[$status],$app_info->email,$val_arr);
				
			
				ajaxReturn ( '', '邮件发送失败', 1 );
				
				
				}else{
					ajaxReturn ( '', '后端数据更新失败，请重试', 0 );
				}
			}else{
				ajaxReturn ( '', '前端数据更新失败，请重试', 0 );
			}
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
		$result = $this->change_scholarship_status_model->send_message_info ( $mail_title , $mail_content , $send_name );  //发送消息
	} */
	/**
	 * 返回保存数据
	 *
	 * @return multitype:array |boolean
	 */
	private function _save_data() {
		$post = $this->input->post ();
		$fields = $this->change_scholarship_status_model->field_info ();
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
		$result = $this->db->select('id,remark')->get_where('applyscholarship_info','id = '.$id)->row();
		$html = $this->load->view ( 'master/scholarship/add_remark', array(
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
		$scholarship = $this->db->select('*')->get_where('scholarship_info','ischinascholorship = 1 AND state = 1')->result_array();
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