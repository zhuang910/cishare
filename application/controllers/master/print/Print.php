<?php
defined('BASEPATH') or exit ('No direct script access allowed');

/**
 * 通知邮件配置
 *
 * @author JJ
 *
 */
class Printt extends Master_Basic
{
	
	/**
	 * 基础类构造函数
	 */
	function __construct()
	{
		parent::__construct();
		$this->view = 'master/print/';
		$this->load->model($this->view . 'print_model');
	
	
		
	}

	/**
	 * 配置主页
	 */
	function index()
	{	
		
		$this->_view ('printt_index');		
	}
	

		

}