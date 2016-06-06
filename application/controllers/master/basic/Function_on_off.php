<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Function_on_off extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/basic/';
		// $this->load->model($this->view.'sysconf_model');
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		$function_on_off = CF ( 'function_on_off', '', CONFIG_PATH );
		
		$this->_view ( 'function_on_off', array (
				'function_on_off' => $function_on_off 
		) );
	}
	function function_on_offsave() {
		$function_on_off = $this->input->post ();
		
		if (CF ( 'function_on_off', $function_on_off, CONFIG_PATH )) {
			ajaxReturn ( '', '', 1 );
		}
		ajaxReturn ( '', '', 0 );
	}
}