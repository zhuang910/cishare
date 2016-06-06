<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Attendance_notice_set extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/basic/';
		//$this->load->model($this->view.'sysconf_model');
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		$arr=CF('attendance_notice','',CONFIG_PATH);
		$this->_view ('attendance_notice_set_index',array(
			'arr'=>$arr,
			));
	}
	
	function save(){
		$data=$this->input->post();
		CF('attendance_notice',$data,CONFIG_PATH);
		ajaxReturn('','',1);
	}

}