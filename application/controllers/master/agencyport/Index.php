<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 前台 学生 登录
 *
 * @author zyj
 *        
 */
class Index extends Student_Basic {
	protected $pledge_on = 0; // 开关
	protected $pledge_fees = 0; // 费用
	protected $yjdw = ''; // 单位

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'home/apply_model' );
		$this->load->model ( 'home/course_model' );
		$this->load->model ( 'student/evaluate_model' );
		// 查询是否 交押金
		
		$pledge = CF ( 'pledge', '', CONFIG_PATH );
		if (! empty ( $pledge ) && $pledge ['pledge'] == 'yes') {
			$this->pledge_on = 1;
			$this->pledge_fees = $pledge ['pledgemoney'];
			if ($pledge ['pledgeway'] == 'pledgeusd') {
				$this->yjdw = 'USD';
			} else if ($pledge ['pledgeway'] == 'pledgeusd') {
				$this->yjdw = 'RMB';
			}
		}
		$this->load->vars ( 'pledge_on', $this->pledge_on );
		$this->load->vars ( 'pledge_fees', $this->pledge_fees );
		$this->load->vars ( 'yjdw', $this->yjdw );
	}
	
	/**
	 * 主页
	 */
	function index() {
		$userid=intval(trim($this->input->get('userid')));
		$s=intval(trim($this->input->get('s')));
		if(!empty($s)){
			$apply_unsub = array ();
			$where_apply = "userid = {$userid}";
			$apply_unsub = $this->apply_model->get_apply_more_info ( $where_apply );
//            var_dump($apply_unsub);exit;
			$data = $this->returnData ( $where_apply );
			$apply_unsub = ! empty ( $data ['apply_info'] ) ? $data ['apply_info'] : array ();
			$course = ! empty ( $data ['courseid'] ) ? $data ['course'] : array ();
            //是否可以申请
            $is_apply_course=1;
            if(!empty($apply_unsub)){
                foreach($apply_unsub as $k=>$v){
                    if($v['state']!=4){
                        $is_apply_course=0;
                    }
                }
            }
			$this->load->view ( '/master/agencyport/index_index', array (
					'course' => $course,
					'apply_unsub' => ! empty ( $apply_unsub ) ? $apply_unsub : array (),
					'flag' => 1 ,
					'userid'=>$userid,
					'prui'=>'cn',
                    'is_apply_course'=>$is_apply_course
			) );
		}else{
			$this->load->view ( '/master/agencyport/age_index_inxex',array(
					'userid'=>$userid
				));
		}
	}
	
	/**
	 * 未提交的
	 */
	function apply_all() {
        $userid=intval(trim($this->input->get('userid')));
		// 查询未提交的申请
		$apply_unsub = array ();
		$where_apply = "userid = {$userid} AND state < 1";
		$apply_unsub = $this->apply_model->get_apply_more_info ( $where_apply );
		$data = $this->returnData ( $where_apply );
		$apply_unsub = ! empty ( $data ['apply_info'] ) ? $data ['apply_info'] : array ();
		$course = ! empty ( $data ['course'] ) ? $data ['course'] : array ();
		
		$this->load->view ( '/master/agencyport/index_index', array (
				'apply_unsub' => ! empty ( $apply_unsub ) ? $apply_unsub : array (),
				'course' => ! empty ( $course ) ? $course : array (),
				'flag' => 1 ,
                'userid'=>$userid
		) );
	}
	
	/**
	 * 接受的申请
	 */
	function accepted() {
		$where = "userid = {$_SESSION['student']['userinfo']['id']} AND state = 8";
		$data = $this->returnData ( $where );
		$apply_info = ! empty ( $data ['apply_info'] ) ? $data ['apply_info'] : array ();
		$course = ! empty ( $data ['course'] ) ? $data ['course'] : array ();
		$this->load->view ( 'student/index_index', array (
				'apply_info' => ! empty ( $apply_info ) ? $apply_info : array (),
				'course' => ! empty ( $course ) ? $course : array (),
				'flag' => 2 
		) );
	}
	
	/**
	 * 获得录取通知书
	 */
	function admission() {
		$where = "userid = {$_SESSION['student']['userinfo']['id']} AND state >= 6";
		$data = $this->returnData ( $where );
		$apply_info = ! empty ( $data ['apply_info'] ) ? $data ['apply_info'] : array ();
		$course = ! empty ( $data ['course'] ) ? $data ['course'] : array ();
		$this->load->view ( 'student/index_index', array (
				'apply_info' => ! empty ( $apply_info ) ? $apply_info : array (),
				'course' => ! empty ( $course ) ? $course : array (),
				'flag' => 3 
		) );
	}
	
	/**
	 * 打回
	 */
	function callback() {
		// 查询打回
		$apply_unsub = array ();
		$where_apply = "userid = {$_SESSION['student']['userinfo']['id']} AND state = 2";
		$apply_unsub = $this->apply_model->get_apply_more_info ( $where_apply );
		$data = $this->returnData ( $where_apply );
		$apply_unsub = ! empty ( $data ['apply_info'] ) ? $data ['apply_info'] : array ();
		$course = ! empty ( $data ['course'] ) ? $data ['course'] : array ();
		
		$this->load->view ( 'student/index_index', array (
				'apply_unsub' => ! empty ( $apply_unsub ) ? $apply_unsub : array (),
				'course' => ! empty ( $course ) ? $course : array (),
				'flag' => 1 
		) );
	}
	
	/**
	 * 返回数据
	 */
	private function returnData($where = null) {
		if ($where != null) {
			$data = array ();
			$apply_info = $this->apply_model->get_apply_more_info ( $where );
			if (! empty ( $apply_info )) {
				// 找出所有的课程的id
				foreach ( $apply_info as $k => $v ) {
					$courseid [] = $v ['courseid'];
				}
				$where_course = "id > 0 AND majorid IN (" . implode ( ',', $courseid ) . ") AND site_language = ".$this->where_lang;
				$course_all = $this->course_model->get_courses ( $where_course );

				// $course_names = $this->course_model->get_course_content ( 'id >0' );
				// foreach ( $course_names as $k => $v ) {
				// $course_name [$v ['courseid']] [$v ['site_language']] = $v ['name'];
				// }
				
				// foreach ( $course_all as $item ) {
				// $course [$item ['id']] ['name'] = $course_name [$item ['id']] [$_SESSION ['lang_default']];
				// $course [$item ['id']] ['site_language'] = $_SESSION ['lang_default'];
				// }
				foreach ( $course_all as $k => $v ) {
					$course [$v ['majorid']] = $v ['langname'];
				}
				
				$data ['apply_info'] = ! empty ( $apply_info ) ? $apply_info : array ();
				$data ['course'] = ! empty ( $course ) ? $course : array ();
				return $data;
			}
		}
		return false;
	}
	
	/**
	 * 地址确认信息
	 */
	function confirm_address() {
		
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		$key = trim ( $this->input->get ( 'key' ) );
		if (! empty ( $key )) {
			// http://bfsu.com/user/personal/confirm_address?id=25&userid=25
			// 解密
			$key_info = authcode ( base64_decode ( $key ), 'DECODE', 'cucas-confirm-address', 0 );
			
			$ver_info = explode ( '-', $key_info );
			$userid = $ver_info [0];
			$applyid = $ver_info [1];
			$result = $this->db->select('addressconfirm')->get_where('apply_info','id = '.$applyid)->row();
			
			if(!empty($result->addressconfirm) && $result->addressconfirm == 1){
				redirect('/'.$this->puri.'/student/index');
			}
			$applyinfo = $this->db->select ( '*' )->get_where ( 'apply_info', array (
					'userid' => $userid,
					'id' => $applyid 
			) )->result_array ();
			
			if (empty ( $applyinfo )) {
				$flag = 0;
			}
			if ($userid != $_SESSION ['student'] ['userinfo'] ['id']) {
				$flag = 0;
			}
			
			$address = $this->db->where ( array (
					'appid' => $applyid,
					'userid' => $userid 
			) )->limit ( 1 )->get ( 'app_getoffer' )->row ();
			$this->load->view ( 'student/confirm_address', array (
					'applyid' => $applyid,
					'userinfo' => $_SESSION ['student'] ['userinfo'],
					'address' => ! empty ( $address ) ? $address : array (),
					'nationality' => $nationality 
			) );
		}
	}
	
	/**
	 * 地址确认
	 */
	function confirmaddress_do() {
		$data = $this->input->post ();
		if (! empty ( $data ) && ! empty ( $data ['appid'] )) {
			$appid = $data ['appid'];
			
			unset ( $data ['submit'] );
			$data['userid'] = $_SESSION ['student'] ['userinfo'] ['id'];
			$f1 = $this->db->insert ( 'app_getoffer', $data);
			$f2 = $this->apply_model->save_apply_info ( array (
					'id' => $appid,
					'userid' => $_SESSION ['student'] ['userinfo'] ['id'] 
			), array (
					'addressconfirm' => 1,
					'address_ctime' => time () 
			) );
			
			if ($f1 && $f2) {
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 删除
	 */
	function del_apply() {
		$id = intval ( trim ( $this->input->post ( 'id' ) ) );
		$userid=intval(trim($this->input->post('userid')));
		if ($id) {
			$where_apply = "userid = {$userid} AND id = {$id} AND paystate != 1";
			$result = $this->apply_model->get_apply_info ( $where_apply );
			
			if (! empty ( $result )) {
				$flag = $this->apply_model->del_apply ( $where_apply );
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	/**
	 * [evaluate 判断是否完成了评教]
	 * @return [type] [description]
	 */
	function evaluate(){
		//查看当前有没有评教的
		$evaluate_time=CF('evaluate_time','',CONFIG_PATH);
		$now_time=time();
		if(!empty($evaluate_time['starttime'])&&!empty($evaluate_time['endtime'])){
			if($now_time>$evaluate_time['starttime']&&$now_time<$evaluate_time['endtime']){
				//查看有没有完成评教
				$year=date('Y',time());
				$is_eav=$this->evaluate_model->check_eva_finish($_SESSION['student']['userinfo']['id'],$year);
				if($is_eav>0){
					return 1;
				}
				//查看该学是否分班
				$is_squad=$this->evaluate_model->check_student_squad($_SESSION['student']['userinfo']['id']);
				if($is_squad==1){
					return 1;
				}
				return 2;
			}else{
				return 1;
			}
		}
		return 1;
		// $evaluate_info=$this->evaluate_model->get_evaluate_info();
		// var_dump($_SESSION['student']['userinfo']['id']);
	}
	/**
	 * 评教页面
	 */
	function evaluate_page(){
		$this->load->vars ( 'is_floot', 'true' );
		$courseid=intval(trim($this->input->get('courseid')));
		$teacherid=intval(trim($this->input->get('teacherid')));
		$userid=$_SESSION['student']['userinfo']['id'];
		$evaluate_info=$this->evaluate_model->get_course_teacher($userid);
		if(empty($courseid)){
			if(!empty($evaluate_info[0]['courseid'])){
				$courseid=$evaluate_info[0]['courseid'];
			}
		}
		if(empty($teacherid)){
			if(!empty($evaluate_info[0]['teacherid'])){
				$teacherid=$evaluate_info[0]['teacherid'];
			}
		}
		$majorid=$evaluate_info[0]['majorid'];
		$squadid=$evaluate_info[0]['squadid'];
		$term=$evaluate_info[0]['nowterm'];
		$coursename=$this->evaluate_model->get_course_name($courseid,$this->puri);
		$teachername=$this->evaluate_model->get_teacher_name($teacherid,$this->puri);
		//评教的类
		$class_info=$this->evaluate_model->get_class_info();
		$item_info=array();
		foreach ($class_info as $k => $v) {
			if($v['type']==1){
				$item_info[$v['id']]=$this->evaluate_model->get_item_info($v['id'],$this->puri);
			}
		}
		$year=date('Y',time());
		//获取该学生所保存的评教数据
		 $stu_eva_info=$this->evaluate_model->get_eva_student_info($userid,$courseid,$teacherid,$year);
		 //当语言切换时强制更改提交都的项id
		 $stu_answer=$this->evaluate_model->qiangzhigaixiang_id($this->puri,$stu_eva_info);
		// var_dump($stu_eva_info);exit;
		$this->load->view ( 'student/evaluate_page_index',array(
			'evaluate_info'=>$evaluate_info,
			'courseid'=>$courseid,
			'teacherid'=>$teacherid,
			'coursename'=>$coursename,
			'teachername'=>$teachername,
			'class_info'=>$class_info,
			'item_info'=>$item_info,
			'majorid'=>$majorid,
			'squadid'=>$squadid,
			'term'=>$term,
			'stu_eva_info'=>$stu_eva_info,
			'stu_answer'=>$stu_answer
				) );
	}
	/**
	 * [sava_eva_item 保存评教的答案]
	 * @return [type] [description]
	 */
	function sava_eva_item(){
		//判断个数没有贴完表单不能提交表单
		//评教的类
		$class_info=$this->evaluate_model->get_class_info();
		$item_info=array();
		$num=0;
		foreach ($class_info as $k => $v) {
			if($v['type']==1){
				$item_info[$v['id']]=$this->evaluate_model->get_item_info($v['id'],$this->puri);
				$num+=count($item_info[$v['id']]);
			}
			if($v['type']==2){
				$num+=1;
			}
		}
		$data=$this->input->post();
		$data_num=count($data['answer']);
		if($num>$data_num){
			ajaxReturn('','请填写完表单',2);
		}
		if(!empty($data)){
			$data['year']=date('Y',time());
			$data['createtime']=time();
			$data['item']=json_encode($data['answer']);
			unset($data['answer']);
			$id=$this->evaluate_model->save_evaluate_student($data);
			if(!empty($id)){
				ajaxReturn('','保存成功',1);

			}
		}
		// var_dump($data);exit;
		ajaxReturn('','',0);
		
		// 
	}
	/**
	 * [accomplish_eva 完成评教]
	 * @return [type] [description]
	 */
	function accomplish_eva(){
		$userid=intval($this->input->get('userid'));
		$evaluate_info=$this->evaluate_model->get_course_teacher($userid);
		//判断完成情况
		foreach ($evaluate_info as $k => $v) {
			$state=$this->evaluate_model->check_student_evaluate($userid,$v);
			if($state<=0){
				$data['teacherid']=$v['teacherid'];
				$data['courseid']=$v['courseid'];
				ajaxReturn($data,'',2);
			}
		}
		$data['year']=date('Y',time());
		$data['userid']=$userid;
		$data['createtime']=time();
		$id=$this->evaluate_model->save_student_finish($data);
		if(!empty($id)){
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);
	}
}
