<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 前台课程
 *
 * @author JJ
 *        
 */
class Accommodation extends Home_Basic {
	protected $bread_line = null;
	protected $zyj_nav = null;
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'home/accommodation_model' );
	}
	
	/**
	 */
	function index() {

		$this->load->view ( '/home/acc_index');
	}
}