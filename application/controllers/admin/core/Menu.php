<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 登陆
 *
 * @author zhuangqianlin
 *        
 */
class Menu extends CUCAS_Ext {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'admin/core/';
	}
	
	/**
	 * 菜单
	 */
	function index() {
		$this->_view ( 'menu_index' );
	}
}