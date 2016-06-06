<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 前台课程
 *
 * @author JJ
 *        
 */
class Study_res extends Home_Basic {
	protected $bread_line = null;
	protected $zyj_nav = null;
	protected $l_n = null;
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'home/news_model' );
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
		
		if ($programaid) {
			$this->load->library ( 'pager' ); // 调用分页类
			$where = 'state = 1 AND columnid = ' . $programaid . ' AND site_language = ' . $this->where_lang;
			$page = ! empty ( $_GET ['page'] ) ? $_GET ['page'] : 1;
			$getcount = $this->news_model->counts ( $where );
			$pagecount = ! empty ( $getcount ['pagecount'] ) ? $getcount ['pagecount'] : 1;
			list ( $offset, $size, $pagestring ) = $this->pager->pagestring ( $getcount ['allcount'], 10 );
			
			$news = $this->news_model->getall ( $where, '*', $offset, $size, 'orderby DESC,createtime DESC' );
			
			$this->load->view ( 'home/study_res_index', array (
					'news' => ! empty ( $news ) ? $news : array (),
					'programaid' => $programaid,
					'pagestring' => ! empty ( $pagestring ) ? $pagestring : '' 
			) );
		}
	}
	
	/**
	 * 课程详情页
	 */
	function detail() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if (! empty ( $id )) {
			$result = $this->news_model->get_one ( 'state =1 AND id = ' . $id );
			
			$this->load->view ( 'home/study_res_detail', array (
					'result' => ! empty ( $result ) ? $result : array (),
					'programaid' => ! empty ( $result ['columnid'] ) ? $result ['columnid'] : '' 
			) );
		}
	}
}