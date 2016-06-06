<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 招生 系统 注册用户
 *
 * @author zyj
 *        
 */
class Register extends Master_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/enrollment/';
		
		$this->load->model ( $this->view . 'register_model' );
		   $this->load->model ( 'master/enrollment/acc_in_model' );
	}
	
	/**
	 * 主页
	 */
	function index() {
		// 如果是ajax请求则返回json数据列表
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			
			$nationality = CF ( 'nationality', '', 'application/cache/' );
			
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->register_model->count ( $condition );
			$output ['aaData'] = $this->register_model->get ( $fields, $condition );
		//	echo $this->db->last_query();
			foreach ( $output ['aaData'] as $item ) {
				$state = $item->state;
				$item->nationality = isset($nationality [$item->nationality])?$nationality [$item->nationality]:'--';
				$item->state = $this->_get_lists_state ( $item->state );
				$item->registertime = ! empty ( $item->registertime ) ? date ( 'Y-m-d H:i:s', $item->registertime ) : '';
				$item->sex = $this->_get_lists_sex ( $item->sex );
				$item->major = '';
				$item->state_s = "<font color='red'>注册用户</font>";
				$s_f = $this->db->get_where('student','userid = '.$item->id)->row();
				if($s_f){
					$item->state_s = '在学学生';
				}
				//获取 专业
				$majorid = $this->db->select('courseid')->limit(1)->order_by('id DESC')->get_where('apply_info','userid = '.$item->id)->row();
				if(!empty($majorid->courseid)){
					$majorname = $this->db->select('name,englishname')->get_where('major','id = '.$majorid->courseid)->row();
					if(!empty($majorname)){
						$item->major .= !empty($majorname->name)?$majorname->name:'';
						$item->major .= !empty($majorname->englishname)?'<br />'.$majorname->englishname:'';
					}
				}
				$birthday = $item->birthday;
				if(!empty($birthday)){
					$item->birthday = date('Y-m-d',$item->birthday);
				}
				$studystarttime = $item->studystarttime;
				$item->studystarttime = '';
				if(!empty($studystarttime)){
					$item->studystarttime .= '开始时间：'.date('Y-m',$studystarttime);
				}
				if(!empty($item->studyendtime)){
					$item->studystarttime .= '<br />结束时间：'.date('Y-m',$item->studyendtime);
				}
				$item->operation = '<div class="btn-group">';
				$item->operation .= '<a class="btn btn-xs btn-info" href="javascript:;" onclick="uppassword(' . $item->id . ')"  id="uppassword">重置密码为123456</a><button data-toggle="dropdown" class="btn btn-xs btn-info btn-white dropdown-toggle">
					    更多
                        <span class="ace-icon fa fa-caret-down icon-only"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-info dropdown-menu-right">';
				
				$item->operation .= '<li><a href="/master/enrollment/register/edit?id=' . $item->id . '">编辑</a></li>';
				$item->operation .= '<li><a href="javascript:;" onclick="tozaixue(' . $item->id . ')">转为在学</a></li>';
				$item->operation .= ' <li><a data-toggle="modal" role="button" href="/master/student/student/room?userid=' . $item->id . '">分配房间</a></li>';
				if ($state == 1) {
					$item->operation .= '<li><a href="javascript:;" onclick="upstate(' . $item->id . ',0)"  id="upstate">点击禁用</a></li>';
				} else {
					$item->operation .= '<li><a href="javascript:;" onclick="upstate(' . $item->id . ',1)" id="upstate">点击启用</a></li>';
				}
				
				$item->operation.= '<li class="divider"></li>
					<li><a href="javascript:;" onclick="del(' . $item->id . ')"  id="del">删除</a></li>
					<ul></div>';
				}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'register_index' );
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
	*转为 在学
	*/
	function tozaixue(){
		$id = intval(trim($this->input->get('id')));
		if ($id) {
			//先判断一下 是否 已经 转成
			$f_l = $this->db->get_where('student','userid = '.$id)->row();
			if($f_l){
				ajaxReturn('','已经是 在学',0);
			}
			$this->load->model('master/enrollment/change_offer_status_model');
			$result = $this->change_offer_status_model->get_one_a($id);
			
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
			
			//院系
			$faculty = $this->get_faculty();
			
			//长短期生
			$isshort = 0;
		
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
				'sex' => !empty($result->sex)?$result->sex:0,
				'birthday' => !empty($result->birthday)?date('Y',$result->birthday).date('m',$result->birthday).date('d',$result->birthday):'',
				'marital' => !empty($result->marital)?$result->marital:'',
				'email' => !empty($result->email)?$result->email:'',
				'religion' => !empty($result->religion)?$result->religion:'',
				'scholarshipid' => !empty($scholorship)?$scholorship:'自费',
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
				'isshort' => 0,
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
			//
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
			//还要 插入签证表
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
	*单个添加
	*/
	function add_one(){
		 $this->load->model ( 'master/student/student_model' );
		  $major_info = $this->student_model->get_major_info ('id>0',0,0,'language desc');
		
        // 获取学历
        $major_info = $this->_get_major_by_degree($major_info);
		  // 国籍
        $nationality = CF ( 'public', '', CACHE_PATH );
		
		//学生类别
		$d = $this->db->get_where('degree_info','id > 0')->result_array();
		
		$this->_view ( 'register_add', array (
            
            'major_info' => $major_info,
            'd' => $d,
            
            'nationality' => $nationality['global_country']
        ) );
	}
	
	function check_passport(){
		$passport = trim($this->input->get('passport'));
		if($passport){
			$result = $this->db->select()->get_where('student_info',"passport = '{$passport}'")->row();
			if ($result) {
				die ( json_encode ( '护照号已存在' ) );
					
				} else {
					die ( json_encode ( true ) );
				}
		}
	}
	
	function check_email(){
		$email = trim($this->input->get('email'));
		if($email){
			$result = $this->db->select()->get_where('student_info',"email = '{$email}'")->row();
			if ($result) {
				die ( json_encode ( '邮箱已存在' ) );
					
				} else {
					die ( json_encode ( true ) );
				}
		}
	}
	
	
	function saveone(){
		$data = $this->input->post();
		if(!empty($data) && !empty($data['passport']) && !empty($data['courseid'])){
			if(!empty($data['birthday'])){
				$data['birthday'] = strtotime($data['birthday']);
			}
			if(!empty($data['studystarttime'])){
				$data['studystarttime'] = strtotime($data['studystarttime']);
			}
			if(!empty($data['studyendtime'])){
				$data['studyendtime'] = strtotime($data['studyendtime']);
			}
			$courseid = $data['courseid'];
			unset($data['courseid']);
			$time = time();
			$data['password'] = "e10adc3949ba59abbe56e057f20";
			$data['interest'] = 0;
            $data['inquiries'] = 0;
			$data['registertime'] = $time;
			$data['registerip'] = "127.0.0.1";
			$data['lasttime'] = $time;
			$data['state'] = 1;
			$data['isactive'] = 1;
			$data['address'] = "";
			$data['isenrol'] = 1;
			
			 // 验证重复
                $is = $this->db->where("passport = '".$data['passport']."'")->get('student_info')->row();
				
                if(empty($is)){
                    $this->db->insert('student_info',$data);
                    $user_id = $this->db->insert_id();
					$majorid = $courseid;
					$major_info = $this->db->get_where('major','id = '.$majorid)->row();
					
                    $data_s = array();
                    $data_s['number'] = build_order_no();
                    $data_s['userid'] = $user_id;
                    $data_s['courseid'] = $majorid;
                    $data_s['tuition'] = !empty($major_info->tuition)?$major_info->tuition:0;
                    $data_s['applytime'] = $time;
                    $data_s['registration_fee'] = !empty($major_info->registration_fee)?$major_info->registration_fee:0;
                    $data_s['danwei'] = 2;
                    $data_s['paystate'] = 1;
                    $data_s['paytime'] = $time;
                    $data_s['paytype'] = 4;
                    $data_s['isstart'] = 1;
                    $data_s['isinformation'] = 1;
                    $data_s['isatt'] = 1;
                    $data_s['issubmit'] = 1;
                    $data_s['issubmittime'] = $time;
                    $data_s['lasttime'] = $time;
                    $data_s['state'] = 8;
                    $data_s['addressconfirm'] = 1;
                    $data_s['address_ctime'] = $time;
                    $data_s['opening'] = $time;
                    $data_s['remark'] = "单个添加";
                    $data_s['pagesend_status'] = 1;
                    $data_s['e_offer_status'] = 1;
                    $data_s['confirm_admission'] = "1";
					
                    $this->db->insert('apply_info',$data_s);
					
                    $apply_id = $this->db->insert_id();
					
					if($apply_id){
						ajaxReturn('','',1);
					}
                }else{
					ajaxReturn('','',0);
				}
		}else{
			
			ajaxReturn('','',0);
		}
		
	}
	
 private function _get_major_by_degree($major_lists = array()){
	 $this->load->model ( 'master/student/student_model' );
        $temp = array();
        if(!empty($major_lists)){
            $degree = $this->student_model->get_degree_name('id > 0',0,0,'orderby desc');
			
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
	 * 添加
	 */
	function add() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		
		if ($id) {
			$result = $this->register_model->get_one ( 'id =' . $id );
		}
		$this->_view ( 'register_edit', array (
				'info' => ! empty ( $result ) ? $result : array () 
		) );
	}
	/**
	 * 编辑注册学生
	 */
	function edit() {
		$id = intval ( $this->input->get ( 'id' ) );
	
       // $this->load->model ( 'master/student/student_model' );
		if ($id) {
			$where = "id={$id}";
			$info = $this->register_model->get_one ( $where );
			if (empty ( $info )) {
				ajaxReturn ( '', '该学生不存在', 0 );
			}
		}
		$nationality=CF('public','',CACHE_PATH);
		//状态
		$info->nationality=$this->register_model->get_nationality($info->nationality);
		//获取专业
		$major_info = $this->db->order_by('language desc')->get_where ('major','id > 0')->result_array();
		foreach($major_info as $k => $v){
			$major[$v['id']] = $v['name'].'---'.$v['englishname'];
		}
		
        // 获取学历
      //  $major_info = $this->_get_major_by_degree($major_info);
	//获取 专业
	$major_1 = '';
				$majorid = $this->db->select('courseid')->limit(1)->order_by('id DESC')->get_where('apply_info','userid = '.$id)->row();
				if(!empty($majorid->courseid)){
					$majorname = $this->db->select('name,englishname')->get_where('major','id = '.$majorid->courseid)->row();
					if(!empty($majorname)){
						$major_1 .= !empty($majorname->name)?$majorname->name:'';
						$major_1 .= !empty($majorname->englishname)?'---'.$majorname->englishname:'';
					}
				}
		$this->_view ( 'register_edit', array (
				'info' => $info ,
				'id'=>$id,
				'nationality'=>$nationality['global_country_cn'],
				'select_nationlity'=>$info->nationality,
				'major'=>!empty($major)?$major:array(),
				's_m' => !empty($major_1)?$major_1:''
		) );
	}
	
	   /* private function _get_major_by_degree($major_lists = array()){
        $temp = array();
        if(!empty($major_lists)){
            $degree = $this->student_model->get_degree_name('id > 0',0,0,'orderby desc');
			
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
    }*/
	/**
	 *
	 *编辑字段
	 **/
	function edit_fields(){
		$data=$this->input->post();
		$arr=explode('-', $data['name']);
		
		if(!empty($data)){
			$this->register_model->update_fields($data);
			ajaxReturn('','更新成功',1);
		}
		ajaxReturn('','更新失败',0);
	}
	/**
	 * 保存数据
	 */
	function save() {
		$data = $this->input->post ();
		$id = null;
		$oldpass = null;
		if (! empty ( $data ['id'] )) {
			$id = $data ['id'];
		}
		unset ( $data ['id'] );
		if (! empty ( $data ['oldpass'] )) {
			$oldpass = trim ( $data ['oldpass'] );
		}
		unset ( $data ['oldpass'] );
		$this->load->helper ( 'string' );
		if (! empty ( $data )) {
			if ($id != null) {
				// 编辑管理员
				// 如果没有修改密码 则还原老的密码
				if (empty ( $data ['password'] )) {
					$data ['password'] = $oldpass;
				} else {
					$rand = random_string ( 'alnum', 6 );
					$data ['password'] = md5 ( $data ['password'] ) . md5 ( $rand );
					$data ['salt'] = $rand;
				}
				$flag = $this->register_model->save ( $id, $data );
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了教师' . $data ['name'] . '的信息',
						'ip' => get_client_ip (),
						'lasttime' => time () 
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
			} else {
				$rand = random_string ( 'alnum', 6 );
				$data ['password'] = md5 ( $data ['password'] ) . md5 ( $rand );
				$data ['salt'] = $rand;
				$data ['createtime'] = time ();
				$flag = $this->register_model->save ( null, $data );
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '添加了教师名为' . $data ['name'] . '的教师',
						'ip' => get_client_ip (),
						'lasttime' => time () 
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
			}
			if ($flag) {
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 修改管理员的状态
	 */
	function upstate() {
		$id = intval ( $this->input->get_post ( 'id' ) );
		$state = intval ( $this->input->get_post ( 'state' ) );
		if (! empty ( $id )) {
			$result = $this->register_model->save_audit ( $id, $state );
			if ($result === true) {
				$teacherlog = $this->register_model->get_one ( 'id = ' . $id );
				$statelog = array (
						'禁用',
						'启用' 
				);
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了注册用户' . $teacherlog->email . '的状态信息为' . $statelog [$state],
						'ip' => get_client_ip (),
						'lasttime' => time () 
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
				ajaxReturn ( '', '更改成功', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 重置管理员的
	 * 密码
	 */
	function uppassword() {
		$id = intval ( $this->input->get_post ( 'id' ) );
		if ($id) {
			
			$data ['password'] = substr ( md5 ( '123456' ), 0, 27 );
			$flag = $this->register_model->save ( $id, $data );
			if ($flag) {
				$teacherlog = $this->register_model->get_one ( 'id = ' . $id );
				
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了注册用户' . $teacherlog->email . '的密码',
						'ip' => get_client_ip (),
						'lasttime' => time () 
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
	 * 检查邮箱 是否 重复
	 */
	function checkemail() {
		$email = trim ( $this->input->get ( 'email' ) );
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if (! empty ( $email )) {
			if (! preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email )) {
				// die ( json_encode ( 'Email address format is not correct ' ) );
				die ( json_encode ( '邮箱格式不正确！' ) );
			} else {
				$email_true = $this->register_model->get_one ( array (
						'email' => $email 
				) );
				if ($email_true) {
					// die ( json_encode ( true ) );
					if ($email_true->id == $id) {
						die ( json_encode ( true ) );
					} else {
						die ( json_encode ( '邮箱已被占用' ) );
					}
				} else {
					// die ( json_encode ( 'Email does not exist ' ) );
					die ( json_encode ( true ) );
				}
			}
		} else {
			die ( json_encode ( '邮箱不能为空！' ) );
		}
	}
	
	/**
	 * 删除 关联表中数据也会删除
	 */
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$info = ( object ) $this->register_model->get_one ( $where );
			$is = $this->register_model->delete ( $where );
			if ($is === true) {
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '删除了注册用户' . $info->email . '的信息',
						'ip' => get_client_ip (),
						'lasttime' => time () 
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'enname',
				'nationality',
				'passport',
				'sex',
				'tel',
				'mobile',
				'registertime',
				'state',
				'birthday',
				'passport',
				'studystarttime',
				'studyendtime',
				'studenttype'
		);
	}
	
	/**
	 * 获取管理员状态
	 *
	 * @param string $statecode        	
	 * @param string $stateindexcode        	
	 * @return string
	 */
	private function _get_lists_state($statecode = null) {
		if ($statecode != null) {
			$statemsg = array (
					'<span class="label label-important">禁用</span>',
					'<span class="label label-success">正常</span>' 
			);
			return $statemsg [$statecode];
		}
		return;
	}
	
	/**
	 * 获取管理员状态
	 *
	 * @param string $statecode        	
	 * @param string $stateindexcode        	
	 * @return string
	 */
	private function _get_lists_sex($statecode = null) {
		if ($statecode != null && $statecode != 0) {
			$statemsg = array (
					'-1' => '未填写',
					'1' => '男',
					'2' => '女' 
			);
			return $statemsg [$statecode];
		}
		return;
	}
	
	 /**
     * 分配房间页面
     */
    function room(){
        $userid = $this->input->get('userid');
        $campus_info=$this->acc_in_model->get_campus_info();
        $this->_view ( 'acc_in_adjust_index' ,array(
            'campus_info'=>$campus_info,
            'userid'=>$userid
        ));
    }

    /**
     * 分配房间页面
     */
    function fenpei(){
        $bulidingid=$this->input->get('bulidingid');
        $campusid=$this->input->get('campusid');
        $floor=$this->input->get('floor');
        $roomid=$this->input->get('roomid');
        $userid=$this->input->get('userid');
        $html = $this->_view ('master/student/user_room',array(
            'bulidingid'=>$bulidingid,
            'campusid'=>$campusid,
            'floor'=>$floor,
            'roomid'=>$roomid,
            'userid'=>$userid
        ),true);
        ajaxReturn($html,'',1);
    }

    /**
     * 提交的房间
     */
    function sub_room(){
        $data=$this->input->post();
        if(!empty($data['accstarttime'])&&!empty($data['endtime'])){
            $accstarttime=strtotime($data['accstarttime']);
            $endtime=strtotime($data['endtime']);
            if($accstarttime>$endtime){
                ajaxReturn('','',0);
            }
            $xx=$endtime-$accstarttime;

            $xx=$xx/3600/24;
            $data['accstarttime']=$accstarttime;
            $data['accendtime']=$xx;
        }else{
            ajaxReturn('','',0);
        }
        //获取房间的价格
        $room_info=$this->db->get_where('school_accommodation_prices','id = '.$data['roomid'])->row_array();
        if(!empty($room_info['prices'])){
            $data['registeration_fee']=$room_info['prices'];
        }else{
            $data['registeration_fee']=0;
        }
        //计算剩余金额
        $nowxx=time()-$accstarttime;
        if($nowxx>0){
            $nowxx=$nowxx/3600/24;
            $data['residual_amount']=$nowxx;
        }else{
            $data['residual_amount']=0;
        }
        //插入所有收支表
        $budget_data['userid']=$data['userid'];
        $budget_data['budget_type']=1;
        $budget_data['type']=4;
        $budget_data['term']=1;
        $budget_data['payable']=$data['accendtime']*$data['registeration_fee'];
        $budget_data['paid_in']=$data['accendtime']*$data['registeration_fee'];
        $budget_data['paystate']=1;
        $budget_data['createtime']=time();
        $budget_data['adminid']=$_SESSION ['master_user_info']->id;
        $this->db->insert('budget',$budget_data);
        $budgetid=$this->db->insert_id();
        $data['budgetid']=$budgetid;
        $data['applytime']=time();
        $data['paystate']=1;
        $data['paytime']=time();
        $data['acc_state']=6;
        $data['adminid']=$_SESSION ['master_user_info']->id;
        unset($data['endtime']);
        $this->db->insert('accommodation_info',$data);
        $this->db->update('student',array('acc_state'=>1),'userid = '.$data['userid']);
        ajaxReturn('','',1);
    }
}

