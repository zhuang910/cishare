<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 网站首页
 *
 * @author zhuangqianlin
 *        
 */
class Index extends Home_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 菜单
	 */
	function index() {
		echo '<script>window.parent.location.href="/admin/core/login";</script>';
		
		$this->load->view ( 'index_index' );
	}

}