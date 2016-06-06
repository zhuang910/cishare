<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Hour extends Master_Basic {
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
		$hour=CF('hour','',CONFIG_PATH);
		
		$this->_view ('hour_index',array(
				'hour'=>$hour['hour'],
			));
	}
	
	function hoursave(){
		$hour=$this->input->post('hour');
		//var_dump($time);exit;
		if(CF('hour',array('hour'=>$hour),CONFIG_PATH)){
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);
	}		
	
}