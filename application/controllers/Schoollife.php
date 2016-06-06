<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 前台课程
 *
 * @author JJ
 *        
 */
class Schoollife extends Home_Basic {
	protected $zyj_nav = null;
	protected $f_n = null;
	protected $column_title = null;
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'home/schoollife_Model' );
		$this->load->model ( 'home/share_model' ); // 精彩分享
		$this->load->model ( 'home/fact_model' ); // 设施
		$this->zyj_nav = menu_tree ( 0, $this->nav, 'programaid' );
		foreach ( $this->zyj_nav as $k => $v ) {
			if ($v ['programaid'] == 30) {
				$l_n = $v ['childs'];
			}
		}
		
		if (! empty ( $l_n )) {
			foreach ( $l_n as $kk => $vv ) {
				if ($vv ['programaid'] == 31) {
					$this->f_n = $vv ['childs'];
				}
			}
		}
		$this->load->vars ( 'l_n', $this->f_n );
	}
	
	/**
	 * function index() {
	 * // 查询 第一个设施的图片
	 * $programaid = 32;
	 * if (! empty ( $this->f_n )) {
	 * foreach ( $this->f_n as $key => $val ) {
	 * // $ids [] = $val ['programaid'];
	 * if ($key == 0) {
	 * $programaid = $val ['programaid'];
	 * }
	 * $this->column_title [$val ['programaid']] = $val ['title'];
	 * }
	 * // $where_fac = 'state = 1 AND columnid IN (' . implode ( ',', $ids ) . ') AND site_language = ' . $this->where_lang;
	 * $where_fac = 'state = 1 AND columnid = ' . $programaid . ' AND site_language = ' . $this->where_lang;
	 * $data = $this->db->select ( '*' )->order_by ( 'orderby DESC' )->limit ( 100 )->get_where ( 'school_facilities', $where_fac )->result_array ();
	 *
	 * }
	 * // 获取事件活动
	 * $this->load->library ( 'pager' ); // 调用分页类
	 * $where = 'state = 1 AND columnid = 39 AND site_language = ' . $this->where_lang;
	 * $page = ! empty ( $_GET ['page'] ) ? $_GET ['page'] : 1;
	 * $getcount = $this->schoollife_Model->counts ( $where, 9 );
	 * $pagecount = ! empty ( $getcount ['pagecount'] ) ? $getcount ['pagecount'] : 1;
	 * list ( $offset, $size, $pagestring ) = $this->pager->pagestring ( $getcount ['allcount'], 9 );
	 *
	 * $events = $this->schoollife_Model->getall ( $where, '*', $offset, $size, 'orderby DESC,createtime DESC' );
	 * $this->load->view ( 'home/schoollife_index', array (
	 * 'events' => ! empty ( $events ) ? $events : array (),
	 * 'pagecount' => $pagecount,
	 * 'page' => $page,
	 * 'data' => ! empty ( $data ) ? $data : array (),
	 * 'conumn_title' => ! empty ( $this->column_title ) ? $this->column_title : array ()
	 * ) );
	 * }
	 */
	function index() {
		$programaid = intval ( trim ( $this->input->get ( 'programaid' ) ) );
		$where_advance = 'id >0 AND state = 1 AND columnid = 9';
		$advance_schoollife = $this->db->select ( '*' )->order_by ( 'orderby DESC' )->limit ( 1 )->get_where ( 'advance_info', $where_advance )->result_array ();
		$this->load->view ( 'home/schoollife_list', array (
				'advance_schoollife' => $advance_schoollife 
		) );
	}
	/**
	 * 首页获取数据
	 */
	function get_fac_images() {
		$columnid = intval ( trim ( $this->input->get ( 'columnid' ) ) );
		if ($columnid) {
			foreach ( $this->f_n as $key => $val ) {
				$this->column_title [$val ['programaid']] = $val ['title'];
			}
			$where_fac = 'state = 1 AND columnid = ' . $columnid . ' AND site_language = ' . $this->where_lang;
			$data = $this->db->select ( '*' )->order_by ( 'orderby DESC' )->limit ( 100 )->get_where ( 'school_facilities', $where_fac )->result_array ();
			if (! empty ( $data )) {
				$html = $this->load->view ( 'home/schoollife_get_fac_images', array (
						'data' => ! empty ( $data ) ? $data : array (),
						'conumn_title' => ! empty ( $this->column_title ) ? $this->column_title : array () 
				), true );
				ajaxReturn ( $html, '', 1 );
			} else {
				ajaxReturn ( '', lang ( 'download_fail' ), 0 );
			}
		} else {
			ajaxReturn ( '', lang ( 'download_fail' ), 0 );
		}
	}
	
	/**
	 * 得到数据
	 */
	function get_data() {
		$page = intval ( trim ( $this->input->get ( 'page' ) ) );
		
		if ($page) {
			
			$this->load->library ( 'pager' ); // 调用分页类
			$where = 'state = 1 AND columnid = 39 AND site_language = ' . $this->where_lang;
			
			$getcount = $this->schoollife_Model->counts ( $where, 9 );
			$pagecount = ! empty ( $getcount ['pagecount'] ) ? $getcount ['pagecount'] : 1;
			if ($page <= $pagecount) {
				$size = 9;
				$offset = ($page - 1) * $size;
				$events = $this->schoollife_Model->getall ( $where, '*', $offset, $size, 'createtime DESC,id DESC' );
				$html = $this->load->view ( 'home/schoollife_get_data', array (
						'events' => ! empty ( $events ) ? $events : array (),
						'page' => $page,
						'pagecount' => $pagecount 
				), true );
				ajaxReturn ( $html, '', 1 );
			} else {
				ajaxReturn ( '', lang ( 'no_data' ), 0 );
			}
		} else {
			ajaxReturn ( '', lang ( 'no_data' ), 0 );
		}
	}
	
	/**
	 * 精彩分享 列表页
	 */
	function share() {
		// 获取事件活动
		$this->load->library ( 'pager' ); // 调用分页类
		$where = 'state = 1 AND site_language = ' . $this->where_lang;
		$page = ! empty ( $_GET ['page'] ) ? $_GET ['page'] : 1;
		$getcount = $this->share_model->counts ( $where, 9 );
		$pagecount = ! empty ( $getcount ['pagecount'] ) ? $getcount ['pagecount'] : 1;
		list ( $offset, $size, $pagestring ) = $this->pager->pagestring ( $getcount ['allcount'], 9 );
		
		$events = $this->share_model->getall ( $where, '*', $offset, $size, 'orderby DESC,createtime DESC' );
		$this->load->view ( 'home/schoollife_share', array (
				'events' => ! empty ( $events ) ? $events : array (),
				'pagecount' => $pagecount,
				'page' => $page 
		) );
	}
	
	/**
	 * 精彩分享
	 * 加载更多
	 */
	function share_get_data() {
		$page = intval ( trim ( $this->input->get ( 'page' ) ) );
		
		if ($page) {
			
			$this->load->library ( 'pager' ); // 调用分页类
			$where = 'state = 1 AND site_language = ' . $this->where_lang;
			$page = ! empty ( $_GET ['page'] ) ? $_GET ['page'] : 1;
			$getcount = $this->share_model->counts ( $where, 9 );
			$pagecount = ! empty ( $getcount ['pagecount'] ) ? $getcount ['pagecount'] : 1;
			
			if ($page <= $pagecount) {
				$size = 9;
				$offset = ($page - 1) * $size;
				$events = $this->share_model->getall ( $where, '*', $offset, $size, 'createtime DESC,id DESC' );
				$html = $this->load->view ( 'home/schoollife_get_data_share', array (
						'events' => ! empty ( $events ) ? $events : array (),
						'page' => $page,
						'pagecount' => $pagecount 
				), true );
				ajaxReturn ( $html, '', 1 );
			} else {
				ajaxReturn ( '', lang ( 'no_data' ), 0 );
			}
		} else {
			ajaxReturn ( '', lang ( 'no_data' ), 0 );
		}
	}
	
	/**
	 * 精彩 分享
	 * 详情
	 */
	function share_detail() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if ($id) {
			$result = $this->share_model->get_one ( 'state = 1 AND id = ' . $id );
			$share = $this->db->select ( '*' )->order_by ( 'createtime DESC' )->get_where ( 'stu_share', 'studentid = ' . $id )->result_array ();
			$this->load->view ( 'home/schoollife_share_detail', array (
					'result' => ! empty ( $result ) ? $result : array (),
					'share' => ! empty ( $share ) ? $share : array () 
			) );
		}
	}
	
	/**
	 * 教学设施
	 */
	function facilities() {
		$programaid = intval ( trim ( $this->input->get ( 'programaid' ) ) );
		if ($programaid == 31) {
			$programaid = 32;
		}
		if ($programaid) {
			$data = $this->fact_model->get_more ( 'id > 0 AND state = 1 AND columnid = ' . $programaid . ' AND site_language = ' . $this->where_lang );
			
			$this->load->view ( 'home/schoollife_facilities', array (
					'data' => ! empty ( $data ) ? $data : array (),
					'programaid' => $programaid 
			) );
		}
	}
}