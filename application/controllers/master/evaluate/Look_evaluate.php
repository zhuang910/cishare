<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Look_evaluate extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/evaluate/';
		$this->load->model ( $this->view . 'look_evaluate_model' );
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		$this->_view ( 'look_evaluate_index',array(
			
			));
	}
	/**
	 * [get_where_info 获取该下的值]
	 * @return [type] [description]
	 */
	function get_where_info(){
		$val=$this->input->get('where');
		if($val=='teacherid'){
			//查询所有的老师
			$teacher_info=$this->look_evaluate_model->get_all_teacher();
			if(!empty($teacher_info)){
				ajaxreturn($teacher_info,'',1);
			}
		}
		if($val=='majorid'){
			//查询所有的老师
			$major_info=$this->look_evaluate_model->get_all_major();
			if(!empty($major_info)){
				ajaxreturn($major_info,'',1);
			}
		}
		if($val=='courseid'){
			$course_info=$this->look_evaluate_model->get_all_course();
			if(!empty($course_info)){
				ajaxreturn($course_info,'',1);
			}
		}
		if($val=='squadid'){
			$squad_info=$this->look_evaluate_model->get_all_squad();
			if(!empty($squad_info)){
				ajaxreturn($squad_info,'',1);
			}
		}
	}
}