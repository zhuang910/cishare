<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 前台课程
 *
 * @author JJ
 *        
 */
class Start_teacher extends Home_Basic {
	
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'home/news_model' );
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
			
			// 查询新闻的广告]
			$news_advance = $this->db->select ( '*' )->limit ( 1 )->order_by ( 'orderby DESC' )->get_where ( 'advance_info', 'state = 1 AND columnid = 3 AND site_language = ' . $this->where_lang )->row ();
			
			$this->load->view ( 'home/start_teacher_index', array (
					'news' => ! empty ( $news ) ? $news : array (),
					'programaid' => $programaid,
					'pagestring' => ! empty ( $pagestring ) ? $pagestring : '',
					'news_advance' => ! empty ( $news_advance ) ? $news_advance : array () 
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
			if (! empty ( $result ['jpkcid'] )) {
				$jpkc = $this->db->select ( '*' )->get_where ( 'teacher_jpkc', 'id = ' . $result ['jpkcid'] )->row ();
				if (empty ( $jpkc->video )) {
					
					$imgs = $this->db->select ( '*' )->get_where ( 'teacher_jpkc_img', 'jpkcid = ' . $jpkc->id )->result_array ();
				}
			}
			$this->load->view ( 'home/start_teacher_detail', array (
					'result' => ! empty ( $result ) ? $result : array (),
					'programaid' => ! empty ( $result ['columnid'] ) ? $result ['columnid'] : '',
					'jpkc' => ! empty ( $jpkc ) ? $jpkc : array (),
					'imgs' => ! empty ( $imgs ) ? $imgs : array () 
			) );
		}
	}
}