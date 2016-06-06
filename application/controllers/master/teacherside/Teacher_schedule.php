<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Teacher_schedule extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/teacherside/';
		$this->load->model($this->view.'teacher_schedule_model');
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		$mdata=$this->teacher_schedule_model->get_majorinfo();
		$tdata=$this->teacher_schedule_model->get_teacher();
		$hour=CF('hour','',CONFIG_PATH);
		if(!empty($_SESSION ['master_user_info']->id)){
			$userid=$_SESSION ['master_user_info']->id;
		}
		$teacherid=$this->teacher_schedule_model->get_teacherids($userid);
		$tdata=$this->teacher_schedule_model->get_scheduling_t($teacherid);
		$this->_view ('teacher_schedule_index',array(
			'mdata'=>$mdata,
			'tdata'=>$tdata,
			'hour'=>$hour,
			'tdata'=>$tdata,
			));
	}
	/**
	 * 获取该专业学期
	 */
	public function get_nowterm($mid){
		$nowterm=$this->teacher_schedule_model->get_major_nowterm($mid);
		$course=$this->teacher_schedule_model->get_course($mid);
		$data['nowterm']=$nowterm;
		$data['course']=$course;
		if(!empty($data['nowterm'])&&!empty($data['course'])){
			ajaxReturn ( $data, '', 1 );
		}
		
	}
	/**
	 * 获取该学期的专业
	 */
	function get_squad(){
		$mid=$this->input->get('mid');
		$term=$this->input->get('term');
		$squad=$this->teacher_schedule_model->get_squadinfo($mid,$term);

		//var_dump($squad);exit;
		if(!empty($squad)){
			ajaxReturn ( $squad, '', 1 );
		}
	}
	/**
	 * 获取排课的条件
	 */
	function get_condition(){
		$d=$this->input->post();
		$hour=CF('hour','',CONFIG_PATH);
		$data['hour']=$hour['hour'];
		if($d['squadid']=='0'){
			$data['scheduling']=$this->teacher_schedule_model->get_scheduling_term($d['majorid'],$d['nowterm']);
			$sinfo=array();
			foreach ($data['scheduling'] as $k => $v) {
				$sinfo[$k]=$this->teacher_schedule_model->get_sinfo($k);
			}
			$data['sinfo']=$sinfo;
			ajaxReturn($data,'',2);
		}
		if($d['majorid']!='0'&&$d['nowterm']!='0'){
			$data['scheduling']=$this->teacher_schedule_model->get_scheduling_info($d['majorid'],$d['nowterm'],$d['squadid']);
			
			if(!empty($data)){
				ajaxReturn($data,'',1);
			}
		}
		
			ajaxReturn('','学期班级课程不能为空',0);
	}
	
	/**
	 * 按老师查询
	 * 
	 **/
	function select_teacher(){
		if(!empty($_SESSION ['master_user_info']->id)){
			$userid=$_SESSION ['master_user_info']->id;
		}
		$teacherid=$this->teacher_checking_model->get_teacherids($userid);
		$tdata['scheduling']=$this->teacher_schedule_model->get_scheduling_t($teacherid,$term);
		$hour=CF('hour','',CONFIG_PATH);
		$tdata['hour']=$hour['hour'];
		
		ajaxReturn($tdata,'',1);

	}
	function get_s_nowterm(){
		$tid=$this->input->get('tid');
		$data=$this->teacher_schedule_model->get_s_term($tid);
		ajaxReturn($data,'',1);
	}

}