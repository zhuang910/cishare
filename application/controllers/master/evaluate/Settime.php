<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Settime extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/evaluate/';
		//$this->load->model($this->view.'sysconf_model');
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		$evaluate_time=CF('evaluate_time','',CONFIG_PATH);
		
		$this->_view ('settime_index',array(
				'evaluate_time'=>$evaluate_time,
			));
	}
	
	function save(){
		$data=$this->input->post();
		$data['starttime']=strtotime($data['starttime']);
		$data['endtime']=strtotime($data['endtime']);
		
		//var_dump($time);exit;
		if(CF('evaluate_time',$data,CONFIG_PATH)){
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);
	}		
	
}