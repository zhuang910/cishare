<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 前台课程
 *
 * @author JJ
 *        
 */
class Scholarship extends Home_Basic {
	
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'home/scholarship_model' );
	}
	
	/**
	 */
	function index() {
		$programaid = $this->input->get ( 'programaid' );
		$list = $this->scholarship_model->getall ( 'id > 0  AND state = 1 AND site_language = ' . $this->where_lang );
		if (empty ( $programaid )) {
			$programaid = $list [0] ['id'];
		}
		
		$result = $this->scholarship_model->get_one ( 'id = ' . $programaid . '  AND state = 1 AND site_language = ' . $this->where_lang );
		$count_apply = 0;
		// 数量
		$count = $this->db->select ( 'id' )->get_where ( 'apply_info', 'scholorshipid = ' . $result ['id'] . ' AND state = 8' )->result_array ();
		if (! empty ( $count )) {
			$count_apply = count ( $count );
		}
		
		$this->load->view ( 'home/scholarship_index', array (
				'result' => ! empty ( $result ) ? $result : array (),
				'list' => ! empty ( $list ) ? $list : array (),
				'programaid' => ! empty ( $programaid ) ? $programaid : '' ,
				'count_apply' => $count_apply
				 
		) );
	}
}