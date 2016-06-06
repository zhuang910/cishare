<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 前台课程
 *
 * @author JJ
 *        
 */
class Cost extends Home_Basic {
	protected $bread_line = null;
	protected $zyj_nav = null;
	protected $l_n = null;
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'home/pages_model' );
	}
	
	/**
	 */
	function index() {
		$programaid = 69;
		
		$result = $this->pages_model->get_one ( 'programaid = ' . $programaid . ' AND site_language = ' . $this->where_lang );
	
		$this->load->view ( 'home/cost_index', array (
				'result' => ! empty ( $result ) ? $result : array () 
		) );
	}
}
