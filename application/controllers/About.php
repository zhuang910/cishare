<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 前台课程
 *
 * @author JJ
 *        
 */
class About extends Home_Basic {
	protected $bread_line = null;
	protected $zyj_nav = null;
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'home/about_model' );
		$this->zyj_nav = menu_tree ( 0, $this->nav, 'programaid' );
		foreach ( $this->zyj_nav as $k => $v ) {
			if ($v ['programaid'] ==73) {
				$l_n = $v ['childs'];
			}
		}
		$this->load->vars ( 'l_n', $l_n );
	}
	
	/**
	 */
	function index() {
		$programaid = intval ( trim ( $this->input->get ( 'programaid' ) ) );
		if ($programaid == 73) {
			// 查询课程的广告位
			$where_advance = 'id >0 AND state = 1 AND columnid = 7';
			$advance_about = $this->db->select ( '*' )->order_by ( 'orderby DESC' )->limit ( 1 )->get_where ( 'advance_info', $where_advance )->result_array ();
			$html = $this->load->view ( 'home/about_list', array (
					'advance_about' => $advance_about
			), true );
			echo $html;
			die ();
		}
		
		$result = $this->about_model->get_one ( 'programaid = ' . $programaid . ' AND site_language = ' . $this->where_lang );
		
		$this->load->view ( 'home/about_index', array (
				'result' => ! empty ( $result ) ? $result : array (),
				'programaid' => $programaid 
		) );
	}
}