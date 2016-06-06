<?php
defined('BASEPATH') or exit ('No direct script access allowed');

/**
 * @name        申请管理 -发送offer
 * @package       apply
 * @author        Laravel
 * @copyright
 **/
class change_offer_status extends Master_Basic
{
	protected $scholorshipapply = array();

	/**
	 * 全部申请
	 **/
	function __construct()
	{
		parent::__construct();
		$this->load->library ( 'sdyinc_email' );
		$this->load->model('master/enrollment/change_offer_status_model');
		$this->scholorshipapply = $this->get_scholorshipapply();
		$this->load->vars('scholorshipapply', $this->scholorshipapply);

	}

	//初始化
	function index()
	{
		$app_id = intval($this->input->get('id'));

		if ($app_id) {
			//根据唯一ID查询对应记录
			$result = $this->change_offer_status_model->get_one($app_id);
			if (!empty ($result)) {
				$this->load->vars('app_id', $app_id);
				$this->load->vars('action', 'update');
				$this->load->vars('result', $result);
				$html = $this->load->view('master/enrollment/appmanager/change_offer_status', '', true);
				ajaxReturn($html, '', 1);
			}
			ajaxReturn('', '所查数据不存在', 0);
		}
		ajaxReturn('', '缺少必要参数', 0);
	}

	/**
	 * 发送e-offter
	 */
	function send_e_offer()
	{
		$app_id = intval($this->input->post('id'));
		$m = intval($this->input->post('m'));
		$xy=5.91;
		if($m==89){
			$xy=4.5;
		}
		if ($app_id) {
			$result = $this->change_offer_status_model->get_one($app_id);
			//生成一个 e-offer的 pdf 通知书
			$this->load->library('sdyinc_print');
			// 开学时间
			$opening_date = $this->input->post('opening_date');
			//报到截止时间
			$report_end_time = $this->input->post('report_end_time');
			//学历名称
			$degree=$this->db->get_where('degree_info','id = '.$result->degree)->row_array();
			//编号     年份 学历编号 专业编号 学期 录取好
			$number='';
			//年份
			$number_time=date('Y',time());
			$number.=substr($number_time,2);
			//学历编号
			$number.=!empty($degree['degree_number'])?$degree['degree_number']:'00';
			//专业编号
			$number.=!empty($result->major_number)?$result->major_number:'00';
			//学期
			if($result->stage==1){
				$number.='01';
			}elseif($result->stage==2){
				$number.='02';
			}else{
				$number.='00';
			}
			//录取号
			$num=$this->db->select('count(*) as num')->get_where('apply_info','courseid = '.$result->courseid.' AND ( state = 7 OR state = 8)')->row_array();
			$num['num']=$num['num']+1;
			$num['num']=sprintf("%03d", $num['num']);
			$number.=!empty($num['num'])?$num['num']:'001';

			//获取专业
			if($m==102){
				$data = array(
					'name' => !empty($result->firstname) &&!empty($result->lastname) ? $result->firstname.' '.$result->lastname : '',
					'major' => !empty($result->englishname) ? $result->englishname : '',
					//'opentime' => !empty($result->opentime) ? date('Y-m-d', $result->opentime) : '',
					//'sendtime' => date('Y-m-d', time())
					'opening_date' => $opening_date,
					'report_end_time' => $report_end_time,
					'now_time'=>date('Y-m-d',time()),
					'degree'=>$degree['entitle'],
					'number'=>$number,
					'address_in' => 'No.318, Liuhe Road, Hangzhou, Zhejiang Province'
				
				);
			}

			$content = $this->sdyinc_print->do_pdf_print($m, $data, 'S',$xy);

			$path = JJ_ROOT.'uploads/admin/'.date('Ym').'/'.date('d').'/';
			mk_dir($path);
			$file_name = build_order_no().'.pdf';
			$save_file = $path . $file_name;
			$web_path='/uploads/admin/'.date('Ym').'/'.date('d').'/'.$file_name;
			@file_put_contents($save_file,$content);


			//把这个pdf附件 用邮件发出去
			$val_arr = array(
				'name' => $result->englishname,
				'email' => $result->email
			);
			//$send_ok = $this->_send_mail($result->email, $save_file, 'E_offer', $val_arr);
			
			$MAIL = new sdyinc_email ();
			$MAIL->dot_send_mail ( 14,$result->email,$val_arr,$save_file);
			//修改申请表的信息
			$data = array(
				'e_offer_status' => 1,
				//保存学生的eoffer 方便中介查看
				'e_offer_path'=>'/uploads/admin/'.date('Ym').'/'.date('d').'/'.$file_name,
				'notification_number'=>$number
			);
			$this->_update_apply($app_id, $data);
			
			
			$datalog = array (
					'adminid' => $_SESSION ['master_user_info']->id,
					'adminname' => $_SESSION ['master_user_info']->username,
					'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '给申请用户为' .$result->email.'发送e-offer',
					'ip' => get_client_ip (),
					'lasttime' => time (),
					'type' => 2,
					'appid' => $app_id
			);
			if (! empty ( $datalog )) {
				$this->adminlog->savelog ( $datalog );
			}
		
			ajaxReturn('', '', 1);

		}
		ajaxReturn('', '缺少必要参数', 0);

	}

	/**
	 * 发送e-offter
	 */
	function send_e_offer_preview()
	{
		$app_id = intval($this->input->get('id'));
		$m = intval($this->input->get('m'));
		$xy=5.91;
		if($m==89){
			$xy=4.5;
		}
		if ($app_id) {
			$result = $this->change_offer_status_model->get_one($app_id);
			//生成一个 e-offer的 pdf 通知书
			$this->load->library('sdyinc_print');
			// 开学时间
			$opening_date = $this->input->get('opening_date');
			//报到截止时间
			$report_end_time = $this->input->get('report_end_time');
			//学历名称
			$degree=$this->db->get_where('degree_info','id = '.$result->degree)->row_array();
			//编号     年份 学历编号 专业编号 学期 录取好
			$number='';
			//年份
			$number_time=date('Y',time());
			$number.=substr($number_time,2);
			//学历编号
			$number.=!empty($degree['degree_number'])?$degree['degree_number']:'00';
			//专业编号
			$number.=!empty($result->major_number)?$result->major_number:'00';
			//学期
			if($result->stage==1){
				$number.='01';
			}elseif($result->stage==2){
				$number.='02';
			}else{
				$number.='00';
			}
			//录取号
			$num=$this->db->select('count(*) as num')->get_where('apply_info','courseid = '.$result->courseid.' AND ( state = 7 OR state = 8)')->row_array();
			$num['num']=$num['num']+1;
			$num['num']=sprintf("%03d", $num['num']);
			$number.=!empty($num['num'])?$num['num']:'001';

			//获取专业
			if($m==102){
				$data = array(
					'name' => !empty($result->firstname) &&!empty($result->lastname) ? $result->firstname.' '.$result->lastname : '',
					'major' => !empty($result->englishname) ? $result->englishname : '',
					//'opentime' => !empty($result->opentime) ? date('Y-m-d', $result->opentime) : '',
					//'sendtime' => date('Y-m-d', time())
					'opening_date' => $opening_date,
					'report_end_time' => $report_end_time,
					'now_time'=>date('Y-m-d',time()),
					'degree'=>$degree['entitle'],
					'number'=>$number,
					'address_in' => 'No.318, Liuhe Road, Hangzhou, Zhejiang Province'
				
				);
			}
			

			$html = $this->sdyinc_print->do_pdf_print($m, $data, 'D',$xy);


		}


	}

	/**
	 * 发送纸质offter
	 */
	function send_z_offer()
	{
		$app_id = intval($this->input->get('id'));
		if ($app_id) {
			$result = $this->change_offer_status_model->get_one($app_id);
			//生成一个 e-offer的 pdf 通知书


			//把这个pdf附件 用邮件发出去
			$val_arr = array(
				'email' => $result->email,  //学生名
				'name' => $result->englishname, //课程名
				  
				//'delivery_method'=>$post['sendtype'],             //发送方式
				//'delivery_time'=>date('Y-m-d',time()),            //发送时间
				//'delivery_proof'=>$post['sendproofid'],           //邮寄单号
				//'tip_content' =>(!empty($post['insertetips']))?nl2br($post['sendrmark']):''  //备注信息
// 				'delivery_method' => 1,             //发送方式
// 				'delivery_time' => date('Y-m-d', time()),            //发送时间
// 				'delivery_proof' => '1111111',           //邮寄单号
// 				'tip_content' => '2222222'  //备注信息
			);
			//$send_ok = $this->_send_mail($result->email, '', 'E_offer', $val_arr);
			$MAIL = new sdyinc_email ();
			$MAIL->dot_send_mail ( 16,$result->email,$val_arr);
			//修改申请表的信息
			$data = array(
				'pagesend_status' => 1,
				'pagesend_time' => time(),
			);
			$this->_update_apply($app_id, $data);
			
			//写入日志
			$datalog = array (
					'adminid' => $_SESSION ['master_user_info']->id,
					'adminname' => $_SESSION ['master_user_info']->username,
					'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '给申请用户为' .$result->email.'发送纸质offer',
					'ip' => get_client_ip (),
					'lasttime' => time (),
					'type' => 2,
					'appid' => $app_id
			);
			if (! empty ( $datalog )) {
				$this->adminlog->savelog ( $datalog );
			}
			
			
			ajaxReturn('', '', 1);

		}
		ajaxReturn('', '缺少必要参数', 0);

	}

	/**
	 * 确认入学
	 */
	function confirm_enrollment()
	{
		$id = intval($this->input->get('id'));
		if ($id) {
			$result = $this->change_offer_status_model->get_one($id);
			//申请表的信息
			$dataapply['state'] = 8;
			$dataapply['confirm_admission'] = 1;
			//在学信息
			$time = time();
			//是否申请了 奖学金 且 通过了
			$scholorship = '';
			if(!empty($result->scholorshipid)){
				$scholorshiptemp = $this->get_scholorshipapply_title();
				if(!empty($scholorshiptemp[$result->scholorshipid])){
					$scholorship = $scholorshiptemp[$result->scholorshipid];
				}
			}
			$degree = '';
			if(!empty($result->degree) && !in_array($result->degree,array(3,4,5))){
				$degree = '非学历生';
				
			}else{
				$degree = '学历生';
			}
			
			//院系
			$faculty = $this->get_faculty();
			
			//长短期生
			$isshort = 0;
			if($result->xzunit == 3 || $result->xzunit == 4 ){
				$isshort = 1;
			}
			$sex = 0;
			if(!empty($result->sex) && $result->sex == 'Female'){
				$sex = 2;
			}
			if(!empty($result->sex) && $result->sex == 'Male'){
				$sex = 1;
			}
			//查找学生申请表的信息
			$student_form=$this->db->get_where('apply_template_info','applyid = '.$id)->result_array();
			if(!empty($student_form)){
				foreach ($student_form as $k => $v) {
					if($v['key']=='FatherName'){
						$FatherName=!empty($v['value'])?$v['value']:'';
					}
					if($v['key']=='FatherTel'){
						$FatherTel=!empty($v['value'])?$v['value']:'';
					}
					if($v['key']=='FatherEmail'){
						$FatherEmail=!empty($v['value'])?$v['value']:'';
					}
					if($v['key']=='MotherName'){
						$MotherName=!empty($v['value'])?$v['value']:'';
					}
					if($v['key']=='MotherTel'){
						$MotherTel=!empty($v['value'])?$v['value']:'';
					}
					if($v['key']=='MotherEmail'){
						$MotherEmail=!empty($v['value'])?$v['value']:'';
					}
				}
			}
			
			$d = $this->db->select('id,title')->get_where('degree_info','id > 0')->result_array();
			foreach($d as $kkkkk => $vvvvv){
				$d_d[$vvvvv['id']] = $vvvvv['title']; 

			}
			$datazx = array(
				'studentid' => $result->studentid,
				'name' => !empty($result->chname)?$result->chname:'',
				'enname' => !empty($result->enname)?$result->enname:'',
				'firstname' => !empty($result->firstname)?$result->firstname:'',
				'lastname' => !empty($result->lastname)?$result->lastname:'',
				'sex' => $sex,
				'marital' => !empty($result->marital)?$result->marital:'',
				'birthday' => !empty($result->birthday)?date('Y',$result->birthday).date('m',$result->birthday).date('d',$result->birthday):'',
				'email' => !empty($result->email)?$result->email:'',
				'religion' => !empty($result->religion)?$result->religion:'',
				'scholarshipid' => $scholorship,
				'enroltime' => date('Y',$result->opening).date('m',$result->opening).date('d',$result->opening),
				'applytime' => date('Y',$result->applytime).date('m',$result->applytime).date('d',$result->applytime),
				'majorid' => $result->courseid,
				'major' => $result->courseid,
				'userid' => $result->userid,
				'passport' => !empty($result->passport)?$result->passport:'',
				'nationality' => !empty($result->nationality)?$result->nationality:'',
				'mobile' =>!empty($result->mobile)?$result->mobile:'',
				'tel' => !empty($result->tel)?$result->tel:'',
				'state' => 1,
				'studenttype' =>!empty($d_d[$result->degree])?$d_d[$result->degree]:'',
				'degreeid' =>  !empty($result->degree)?$result->degree:'',
				'faculty' =>  !empty($result->facultyid) && $faculty[$result->facultyid]?$faculty[$result->facultyid]:'',
				'isshort' => $isshort,
				'passporttime' => date('Y',$result->validuntil).date('m',$result->validuntil).date('d',$result->validuntil),
				'paperstype' => '护照',
				'speciality'=>$result->speciality,
				'createtime'=>time(),
				'language_level'=>$result->language_level,
				'applyid'=>$id,
				'patriarch_name'=>@$FatherName,
				'patriarch_tel'=>@$FatherTel,
				'patriarch_email'=>@$FatherEmail,
				'mother_tel'=>@$MotherTel,
				'mother_email'=>@$MotherEmail,
				'mother_name'=>@$MotherName,
				'btime' => time()
			);
			
			$flag1 = $this->db->update('apply_info', $dataapply, 'id = ' . $id);
			$flag2 = $this->db->insert('student', $datazx);
			$s_t_id = $this->db->insert_id();
			$data_visa = array(
				'studentid' => $s_t_id,
				'visatype' => '护照',
				'visanumber' =>  !empty($result->passport)?$result->passport:'',
				'birth_place' => !empty($result->birthplace)?$result->birthplace:'',
				'marital' => !empty($result->marital)?$result->marital:'',
			);
			$this->db->insert('student_visa', $data_visa);
			//写入日志
			$datalog = array (
					'adminid' => $_SESSION ['master_user_info']->id,
					'adminname' => $_SESSION ['master_user_info']->username,
					'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改申请用户为' .$result->email.'的状态为确认入学',
					'ip' => get_client_ip (),
					'lasttime' => time (),
					'type' => 2,
					'appid' => $id
			);
			if (! empty ( $datalog )) {
				$this->adminlog->savelog ( $datalog );
			}
			if ($flag1 && $flag2) {
				ajaxReturn('', '', 1);
			} else {
				ajaxReturn('', '', 0);
			}

		} else {
			ajaxReturn('', '', 0);
		}
	}

	/**
	 * 修改申请表的信息
	 */
	function _update_apply($id = null, $data = null)
	{
		if ($id != null && $data != null) {
			$flag = $this->db->update('apply_info', $data, 'id = ' . $id);
			return $flag;
		}
	}

	//执行更新OFFER发送状态，首先更新WWW端申请状态及地址确认表中OFFER发送方式和订单号状态，成后更新OA端申请管理表状态和OFFER管理表状态
	function update()
	{

		//获取POST数据
		$post = $this->input->post();

		//从OA端申请管理表读取对应申请信息
		$app_id = $post['app_id'];

		$result = $this->change_offer_status_model->get_one($app_id);
		//var_dump($result);

		//执行更新前端地址确认表状态
		$data_upadta_www_offer = array(
			'sendtype' => $post['sendtype'],
			'sendtime' => time(),
			'mail_order' => $post['sendproofid'],
			'sendrmark' => $post['sendrmark']
		);
		$result_www_offer = $this->change_offer_status_model->update_www_offer($app_id, $data_upadta_www_offer);

		if (!$result_www_offer) {
			ajaxReturn('', '更新前端地址确认信息失败，请重试', 0);
		}

		/* //执行OA端申请管理主表状态更新
		$data_upadta_oa_app = array(
			'status'=>17,
			'appfinish'=>1,
			'appfinishtime'=>time()
		);
		$result_oa_app = $this->change_offer_status_model->update_oa_app($app_id ,$data_upadta_oa_app);
		if ( !$result_oa_app ) {
			ajaxReturn ( '', '更新OA端申请状态失败，请重试', 0 );
		}
		
		//执行OA端OFFER收发表状态
		$data_upadta_oa_offer = array(
			'sendtype'=>$post['sendtype'],
			'sendtime'=>time(),
			'sendproofid'=>$post['sendproofid'],
			'sendrmark'=>$post['sendrmark']
		);
		$result_oa_offer = $this->change_offer_status_model->update_oa_offer($app_id ,$data_upadta_oa_offer);
		if ( !$result_oa_offer ) {
			ajaxReturn ( '', '更新OA端OFFER收发状态失败，请重试', 0 );
		} */

		//执行插入前端操作日志
		/* $data_upadta_www_log = array(
			'userid'=>$result_oa_app_info->userid,
			'app_id'=>$result_oa_app_info->id,
			'action'=>'纸质OFFER发送',
			'adminid'=>1,
			'createtime'=>time()
		);
		$result_www_log = $this->change_offer_status_model->insert_www_log($data_upadta_www_log);	
		if ( !$result_www_log ) {
			ajaxReturn ( '', '前端申请操作记录插入失败，请重试', 0 );
		} */

		//执行插入OA前端操作日志
		$data_upadta_oa_log = array(
			'lasttime' => time(),
			'appid' => $result->appid,
			'events' => '发送纸质OFFER',
			'operater' => 1,
			'remark' => $post['sendrmark']
		);
		$result_oa_log = $this->change_offer_status_model->insert_oa_log($data_upadta_oa_log);
		if (!$result_oa_log) {
			ajaxReturn('', 'OA端申请操作记录插入失败，请重试', 0);
		}

		//执行发送邮件

		//准备基础数据-邮件模板中需要的通用变量

		$val_arr = array(
			'student_name' => $result->enname,  //学生名
			'program_name' => $result->name, //课程名
			'school_name' => 'Beiing Foreign Studies University',   //学校名
			'delivery_method' => $post['sendtype'],             //发送方式
			'delivery_time' => date('Y-m-d', time()),            //发送时间
			'delivery_proof' => $post['sendproofid'],           //邮寄单号
			'tip_content' => (!empty($post['insertetips'])) ? nl2br($post['sendrmark']) : ''  //备注信息
		);
		//执行邮件发送  - 16-纸质Offer发送完成
		$send_ok = $this->_send_mail($result->email, '', 'Offer_Delivered', $val_arr);
		if ($send_ok) {
			//更新申请表纸质offer发送状态
			$data_update_app_status = array(
				'pagesend_status' => '1',
				'pagesend_time' => time()
			);
			$this->change_offer_status_model->update_app_sendoffer_status($app_id, $data_update_app_status);
			ajaxReturn('', '更新成功', 1);
		} else {
			ajaxReturn('', '更新失败', 0);
		}


	}

	/**
	 * 发送地址确认信
	 */

	function send_confirm_address_mail()
	{

		$app_id = $this->input->get('id');

		//根据ID，从CUCAS OA端申请主表查找对应记录
		$this->load->model('master/enrollment/change_offer_status_model');
		$result = $this->change_offer_status_model->get_one($app_id);

		$address = $this->change_offer_status_model->get_address($app_id);
		
		$link = 'http://'.$_SERVER['HTTP_HOST'].'/en/student/index/confirm_address?key=' .base64_encode(authcode($result->userid . '-' . $app_id . '-cucas', 'ENCODE', 'cucas-confirm-address', 0));

		//准备基础数据-邮件模板中需要的通用变量

		$val_arr = array(
			'email' => $result->email,
			
			'name' => $result->englishname,
			
			//'link' => base64_encode(authcode($result->userid . '-' . $app_id . '-cucas', 'ENCODE', 'cucas-confirm-address', 0)),
				'link' => $link
			
		);


		//执行邮件发送 13-跟进阶段标注纸质offer已收到（发送地址确认信）
		//$send_ok = $this->_send_mail($result->email, '', 'Mailing_Address_Confirmation_Needed', $val_arr);
		$MAIL = new sdyinc_email ();
		$MAIL->dot_send_mail ( 15,$result->email,$val_arr);
		//写入日志
		$datalog = array (
				'adminid' => $_SESSION ['master_user_info']->id,
				'adminname' => $_SESSION ['master_user_info']->username,
				'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '给申请用户为' .$result->email.'发送地址确认信',
				'ip' => get_client_ip (),
				'lasttime' => time (),
				'type' => 2,
				'appid' => $app_id
		);
		if (! empty ( $datalog )) {
			$this->adminlog->savelog ( $datalog );
		}
		$data = array(
				'appid' => $result->appid,
				'userid' => $result->userid,
				'name' => $result->enname,
				'email' => $result->email,
				'sendtime' => time(),
				'address' => $address->address,
				'status' => -1
			);
			$info = $this->change_offer_status_model->update_offer($data);

			ajaxReturn('', '发送成功', 1);
	}

	/**
	 * 发送确认入学通知email
	 *
	 */
	function send_confirm_joinus()
	{
		//申请id
		$app_id = $this->input->get('id');
		if ($app_id) {

			$result = $this->change_offer_status_model->get_one($app_id);

			$val_arr = array(
				'userid' => $result->userid,
				'student_name' => $result->enname,
				'program_name' => $result->name,
				'school_name' => 'Beiing Foreign Studies University',
				'recive_name' => $result->enname,
				'link' => base64_encode(authcode($result->userid . '-' . $app_id . '-cucas', 'ENCODE', 'cucas-confirm-address', 0)),
				'time' => (date('Y-m-d', time()))
			);

			$send_ok = $this->_send_mail($result->email, '', 'Enrollment', $val_arr);

			//发送邮件结束
			if ($send_ok) {
				$data = array(
					'state' => 8,
					'confirm_admission' => 1
				);

				$info = $this->change_offer_status_model->update_oa_app($app_id, $data);

				ajaxReturn('', '更新成功', 1);
			} else {
				ajaxReturn('', '邮件发送失败', 1);
			}

		}
		ajaxReturn('', '缺少必要参数', 0);


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
	private function _send_mail($send_name = '', $send_attach = '', $param = '', $val_arr = array())
	{

		//载入模板所需变量
		$this->load->vars('val_arr', $val_arr);

		//载入标题数组
		$title_array = CF('web_email_oa', '', CONFIG_PATH);

		//邮件标题赋值
		$mail_title = $title_array[$param]['title'];

		//邮件内容赋值
		$this->view = 'master/enrollment/mail/';
		$mail_content = $this->_view($param, '', true);

		//发送邮件开始

		//初始化
		$this->load->library('mymail');
		$MAIL = new Mymail();

		//开始发送邮件
		//邮件发送函数的参数示例：domail($sentTo,$title=null,$content=null,$attach=null,$reply_to=null,$cc=null,$bcc=null)
		if (!empty($send_attach)) {
			//发送站内信
			//$send_message = $this->send_message($mail_title,$mail_content,$send_name);

			return $MAIL->domail($send_name, $mail_title, $mail_content, $send_attach);
		} else {

			//发送站内信
			//$send_message = $this->send_message($mail_title,$mail_content,$send_name);

			//不包含附件
			//return $send_name.$mail_title.$mail_content;
			return $MAIL->domail($send_name, $mail_title, $mail_content);
		}

	}

	/**
	 * 发送站内信
	 *$send_name： 收件人
	 *$param: 邮件标题数组的键名,邮件模板的名称
	 *$val_arr：发送内容模板中所需变量
	 * @return $flag true|false 发送邮件成功或失败
	 *
	 */
	private function send_message($mail_title = '', $mail_content = '', $send_name = '')
	{
		$result = $this->change_offer_status_model->send_message_info($mail_title, $mail_content, $send_name);  //发送消息
	}

	/**
	 * 获取 奖学金的 全部
	 */
	function get_scholorshipapply()
	{
		$data = array();
		// 奖学金开关
		$scholarship_on = CF('scholarship', '', CONFIG_PATH);
		if (!empty ($scholarship_on) && $scholarship_on ['scholarship'] == 'yes') {
			$scholarship = $this->db->select('*')->get_where('scholarship_info', 'id > 0')->result_array();
			if (!empty($scholarship)) {
				foreach ($scholarship as $k => $v) {
					$data[$v['id']] = $v['title'];
				}
			}

		}
		return $data;
	}
	
	/**
	 * 获取 奖学金的 标题
	 */
	function get_scholorshipapply_title()
	{
		$data = array();
			$scholarship = $this->db->select('*')->get_where('scholarship_info', 'id > 0')->result_array();
			if (!empty($scholarship)) {
				foreach ($scholarship as $k => $v) {
					$data[$v['id']] = $v['title'];
				}
			}
	
	
		return $data;
	}
	
	/**
	 * 获取 院系 标题
	 */
	function get_faculty()
	{
		$data = array();
		$scholarship = $this->db->select('*')->get_where('faculty', 'id > 0')->result_array();
		if (!empty($scholarship)) {
			foreach ($scholarship as $k => $v) {
				$data[$v['id']] = $v['name'];
			}
		}
	
	
		return $data;
	}

	/**
	 * 调取打印接口 邮寄单
	 */
	function print_address()
	{

		$app_id = intval($this->input->get('id'));
		$m = intval($this->input->get('m'));
		if ($app_id && $m) {
			//查询信息
			$result = $this->change_offer_status_model->get_one($app_id);
			$this->load->library('sdyinc_print');
			//获取寄件人 信息
			$publics = CF('publics', '', CONFIG_PATH);
			$send = $publics['send'];
			//获取收件人信息
			$nationality = CF('nationality', '', 'application/cache/');
			$receipt = $this->db->select('*')->order_by('id DESC')->limit(1)->get_where('app_getoffer', 'userid = ' . $result->userid, ' AND appid = ' . $result->appid)->row();

			if (empty($receipt)) {
				$data1 = array(
					'receiptname' => $result->enname,
					'receiptto' => !empty($result->nationality) ? $nationality[$result->nationality] : '',
					'receiptaddress' => !empty($result->address) ? $result->address : '',
					'receiptcompany' => '',
					'receipttel' => !empty($result->tel) ? $result->tel : '',
					'receiptcode' => ''
				);
			} else {
				$data1 = array(
					'receiptname' => $receipt->name,
					'receiptto' => !empty($receipt->country) ? $nationality[$receipt->country] : '',
					'receiptaddress' => !empty($receipt->address) ? $receipt->address : '',
					'receiptcompany' => '',
					'receipttel' => !empty($receipt->tel) ? $receipt->tel : '',
					'receiptcode' => !empty($receipt->postcode) ? $receipt->postcode : ''
				);
			}
			$data2 = array(
				'sendname' => $send['sendname'],
				'sendfrom' => $send['sendfrom'],
				'sendaddress' => $send['sendaddress'],
				'sendcompany' => $send['sendcompany'],
				'sendtel' => $send['sendtel'],
				'sendremark' => $send['sendremark'],
				'sendsing' => $send['sendsing'],
				'sendtime' => $send['sendtime'],
				'sendcode' => $send['sendcode'],


			);
			$data = array_merge($data1, $data2);

			$this->sdyinc_print->do_print($m, $data);
		}
	}


	/**
	 * 调取打印接口
	 */
	function print_offer()
	{

		$app_id = intval($this->input->get('id'));
		$m = intval($this->input->get('m'));
		$studystime = trim($this->input->get('studystime')); //学习时间结束
		$studyetime = trim($this->input->get('studyetime')); //学习时间结束
		$rollstime = trim($this->input->get('rollstime'));//报到时间开始
		$rolletime = trim($this->input->get('rolletime'));//报到时间结束
		
		
		if ($app_id && $m) {
						
			$result = $this->change_offer_status_model->get_one($app_id);
			$this->load->library('sdyinc_print');
			//先去查看 地址确认信息
			$app_name = $this->db->select('*')->order_by('id DESC')->limit(1)->get_where('app_getoffer','appid = '.$app_id)->row();
			//获取专业
			if(!empty($app_name->name)){
				$name = $app_name->name;
			}else{
				$name = !empty($result->enname) ? $result->enname : '';
			}
			$data = array(
				'enname' => $name,
				'majorid' => !empty($result->name) ? $result->name : '',
				'opentime' => !empty($result->opentime) ? date('Y-m-d', $result->opentime) : '',
				'sendtime' => date('Y-m-d', time()),
				'studystime' => !empty($studystime)?$studystime:'',
				'studyetime' => !empty($studyetime)?$studyetime:'',
				'rollstime' => !empty($rollstime)?$rollstime:'',
				'rolletime' => !empty($studystime)?$rolletime:'',

			);

			$this->sdyinc_print->do_print($m, $data);

		}
	}


	/**
	 * 打印邮寄单
	 */
	function print_ad()
	{
		$app_id = intval($this->input->post('id'));
		$m = intval($this->input->post('m'));
		if ($app_id && $m) {
			$data = array(
				'id' => $app_id,
				'm' => $m
			);
			ajaxReturn($data, '', 1);
		} else {
			ajaxReturn('', '', 0);
		}

	}

	/**
	 * 发送纸质offter
	 */
	function print_z()
	{
		$app_id = intval($this->input->post('id'));
		$m = intval($this->input->post('m'));
		if ($app_id && $m) {
			$data = array(
				'id' => $app_id,
				'm' => $m
			);
			ajaxReturn($data, '', 1);
		} else {
			ajaxReturn('', '', 0);
		}

	}

	/**
	 * 选择  录取通知书的模版
	 */
	function print_m()
	{
		$id = intval($this->input->get('id'));
		$result = $this->db->select('*')->get_where('print_template', 'parentid = 3')->result_array();
		$html = $this->load->view('master/enrollment/appmanager/print_m', array(
			'result' => $result,
			'id' => $id
		), true);
		ajaxReturn($html, '', 1);

	}

	/**
	 * 选择  录取通知书的模版
	 */
	function send_e()
	{
		$id = intval($this->input->get('id'));
		$result = $this->db->select('*')->get_where('print_template', 'parentid = 3')->result_array();
		$html = $this->load->view('master/enrollment/appmanager/send_e', array(
			'result' => $result,
			'id' => $id
		), true);
		ajaxReturn($html, '', 1);

	}

	/**
	 * 选择  录取通知书的模版
	 */
	function print_d()
	{
		$id = intval($this->input->get('id'));
		$result = $this->db->select('*')->get_where('print_template', 'parentid = 7')->result_array();
		$html = $this->load->view('master/enrollment/appmanager/print_d', array(
			'result' => $result,
			'id' => $id
		), true);
		ajaxReturn($html, '', 1);

	}

	//初始化
	function ckaddress()
	{
		$id = intval($this->input->get('id'));
		$userid = intval($this->input->get('userid'));
		$nationality = CF('nationality', '', 'application/cache/');
		$result = $this->db->select('*')->get_where('app_getoffer', 'appid = ' . $id . ' AND userid = ' . $userid)->row();
		$html = $this->load->view('master/enrollment/appmanager/ckaddress', array(
			'result' => $result,
			'nationality' => $nationality
		), true);
		ajaxReturn($html, '', 1);

	}
	/**
	 * [uplaod_table 上传202表页面]
	 * @return [type] [description]
	 */
	function uplaod_table_page(){
		$appid=$this->input->get('id');
		$html = $this->load->view('master/enrollment/appmanager/upload_table', array(
			'appid'=>$appid
		), true);
		ajaxReturn($html, '', 1);
	}
	/**
	 * [send_table_yes 发送202表]
	 * @return [type] [description]
	 */
	function send_table_yes(){
		$get_data=$this->input->post();
		if(empty($get_data['file_path'])){
			ajaxReturn('','请上传202表',0);
		}
		$save_file=JJ_ROOT.trim($get_data['file_path'],'/');
		// //把这个pdf附件 用邮件发出去
		// 	$val_arr = array(
		// 		'name' => '123123',
		// 		'email' => '935173649@qq.com'
		// 	);
		// $MAIL = new sdyinc_email ();
		// $MAIL->dot_send_mail ( 16,'935173649@qq.com',$val_arr,$save_file);

		// $app_id = intval($this->input->get('id'));
		if ($get_data['appid']) {
			$result = $this->change_offer_status_model->get_one($get_data['appid']);
			//生成一个 e-offer的 pdf 通知书


			//把这个pdf附件 用邮件发出去
			$val_arr = array(
				'email' => $result->email,  //学生名
				'name' => $result->englishname, //课程名
			);
			//$send_ok = $this->_send_mail($result->email, '', 'E_offer', $val_arr);
			$MAIL = new sdyinc_email ();
			$MAIL->dot_send_mail ( 16,$result->email,$val_arr,$save_file);
			//修改申请表的信息
			$data = array(
				'pagesend_status' => 1,
				'pagesend_time' => time(),
			);
			$this->_update_apply($get_data['appid'], $data);
			
			//写入日志
			$datalog = array (
					'adminid' => $_SESSION ['master_user_info']->id,
					'adminname' => $_SESSION ['master_user_info']->username,
					'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '给申请用户为' .$result->email.'发送纸质offer',
					'ip' => get_client_ip (),
					'lasttime' => time (),
					'type' => 2,
					'appid' => $get_data['appid']
			);
			if (! empty ( $datalog )) {
				$this->adminlog->savelog ( $datalog );
			}
			
			
			ajaxReturn('', '', 1);

		}
		ajaxReturn('', '缺少必要参数', 0);
	}
}