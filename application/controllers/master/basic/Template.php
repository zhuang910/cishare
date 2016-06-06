<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Template extends Master_Basic {
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
		$template=CF('template_set','',CONFIG_PATH);
		$this->_view ('template_set',array(
			'template'=>$template,
			));
	}
	
	function templatesave(){
		$hour=$this->input->post('hour');
		//var_dump($time);exit;
		if(CF('template_set',array('hour'=>$hour),CONFIG_PATH)){
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);
	}
	/**
	 *
	 *预览
	 **/	
	function yulan() {
		$s = intval ( $this->input->get ( 's' ) );
		if (! empty ( $s )) {
			$html = $this->_view ( 'preview', '', true );
			ajaxReturn ( $html, '', 1 );
		}
	}	
	
}