<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 前台课程
 *
 * @author JJ
 *        
 */
class Link extends Home_Basic {
	
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 */
	function index() {
		$result = $this->db->select ( '*' )->order_by ( 'orderby DESC' )->get_where ( 'article_info', 'state = 1 AND columnid = 84 AND site_language = '.$this->where_lang )->result_array ();
		$this->load->view ( 'home/link_index', array (
				'result' => ! empty ( $result ) ? $result : array () 
		) );
	}
}