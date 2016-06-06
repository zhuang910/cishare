<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 前台课程
 *
 * @author JJ
 *        
 */
class Pages extends Home_Basic {
	protected $bread_line = null;
	protected $zyj_nav = null;
	protected $l_n = null;
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'home/pages_model' );
		$this->zyj_nav = menu_tree ( 0, $this->nav, 'programaid' );
		foreach ( $this->zyj_nav as $k => $v ) {
			if ($v ['programaid'] == 44) {
				$this->l_n = $v ['childs'];
			}
		}
		$this->load->vars ( 'l_n', $this->l_n );
	}
	
	/**
	 */
	function index() {
		$programaid = intval ( trim ( $this->input->get ( 'programaid' ) ) );
		if ($programaid == 44) {
			// 查询课程的广告位
			$where_advance = 'id >0 AND state = 1 AND columnid = 8';
			$advance_pages = $this->db->select ( '*' )->order_by ( 'orderby DESC' )->limit ( 1 )->get_where ( 'advance_info', $where_advance )->result_array ();
			$html = $this->load->view ( 'home/pages_list', array (
					'advance_pages' => $advance_pages 
			), true );
			echo $html;
			die ();
		}
		// 单页下面还有单页
		$flag = 0;
		
		foreach ( $this->l_n as $k => $v ) {
			if ($v ['programaid'] == $programaid && ! empty ( $v ['childs'] )) {
				$flag = 1;
				// 获取所有的子项值
				$next_nav = $v ['childs'];
			}
		}
		
		if ($flag == 1 && ! empty ( $next_nav )) {
			$view = 'pages_index1';
			$data = $this->_content ( $next_nav );
			if (! empty ( $data )) {
				foreach ( $data as $zk => $zc ) {
					if (empty ( $zc['content'] ) || $zc['content'] == '<br>') {
						unset ( $data[$zk] );
					}
				}
			}
		} else {
			$view = 'pages_index';
		}
		
		$result = $this->pages_model->get_one ( 'programaid = ' . $programaid . ' AND site_language = ' . $this->where_lang );
		
		$this->load->view ( 'home/' . $view, array (
				'result' => ! empty ( $result ) ? $result : array (),
				'programaid' => $programaid,
				'data' => ! empty ( $data ) ? $data : array () 
		) );
	}
	
	// 获取 内容
	function _content($condition = null) {
		if ($condition != null) {
			foreach ( $condition as $k => $v ) {
				$data [] = $this->pages_model->get_one ( 'programaid = ' . $v ['programaid'] . ' AND site_language = ' . $this->where_lang );
			}
			return $data;
		}
		return array ();
	}
}
