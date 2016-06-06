<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 通知邮件配置
 * 
 * @author JJ
 *        
 */
class Messageset extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/message/';
		//$this->load->model($this->view.'message_model');
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		
		$this->_view ('messageset_index');
	}




}