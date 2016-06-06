<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Warning_line extends Master_Basic {
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
		$info=CF('warning_line','',CONFIG_PATH);
		$this->_view ('warning_line_index',array(
			'info'=>$info
			));
	}
	
	function putup_save(){
		$data=$this->input->post();
		if(!empty($data)){
			$info=CF('warning_line','',CONFIG_PATH);
			if(!empty($info['putup_day'])){
				unset($info['putup_day']);
			}
			$info['putup_day']=$data['putup_day'];
			CF('warning_line',$info,CONFIG_PATH);
			ajaxReturn('','',1);
		}
		ajaxReturn('','不能为空',0);
	}
		function charge_save(){
		$data=$this->input->post();
		if(!empty($data)){
			$info=CF('warning_line','',CONFIG_PATH);
			if(!empty($info['charge'])){
				unset($info['charge']);
			}
			$info['charge']=$data['charge'];
			CF('warning_line',$info,CONFIG_PATH);
			ajaxReturn('','',1);
		}
		ajaxReturn('','不能为空',0);
	}
}