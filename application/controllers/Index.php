<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 网站首页
 *
 * @author zyj
 *        
 */
class Index extends Home_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 菜单
	 */
	function index() {
		echo '<script>window.parent.location.href="/master/core/login";</script>';
		$this->load->model ( 'home/news_model' );
		
		$this->load->library ( 'pager' ); // 调用分页类
		$where = 'id > 0 AND state = 1 AND' . " site_language = '{$this->puri}'";
		$page = ! empty ( $_GET ['page'] ) ? $_GET ['page'] : 1;
		$getcount = $this->news_model->counts ( $where, 1 );
		$pagecount = ! empty ( $getcount ['pagecount'] ) ? $getcount ['pagecount'] : 1;
		list ( $offset, $size, $pagestring ) = $this->pager->pagestring ( $getcount ['allcount'], 1 );
		$notice = $this->news_model->getall ( $where, '*', $offset, $size, 'orderby DESC,createtime DESC' );
		
		$this->load->view ( 'index_index', array (
				'notice' => ! empty ( $notice ) ? $notice : array (),
				'page' => $page,
				'pagecount' => $pagecount 
		) );
	}
	
	/**
	 * 得到数据
	 */
	function get_data() {
		$page = intval ( trim ( $this->input->get ( 'page' ) ) );
		$flag = intval ( trim ( $this->input->get ( 'flag' ) ) );
		if ($page && $flag) {
			$this->load->model ( 'home/news_model' );
			$this->load->library ( 'pager' ); // 调用分页类
			$where = 'id > 0 AND state = 1 AND' . " site_language = '{$this->puri}'";
			$getcount = $this->news_model->counts ( $where, 1 );
			$pagecount = ! empty ( $getcount ['pagecount'] ) ? $getcount ['pagecount'] : 1;
			if ($page >= 1 && $page <= $pagecount) {
				$size = 1;
				$offset = ($page - 1) * $size;
				;
				$news_active = $this->news_model->getall ( $where, '*', $offset, $size, 'orderby DESC,createtime DESC' );
				
				$html = $this->load->view ( 'home/index_get_data', array (
						'notice' => ! empty ( $news_active ) ? $news_active : array (),
						'page' => $page 
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
	 * 顶部搜索
	 */
	function noticedetail() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if ($id) {
			$this->load->model ( 'home/news_model' );
			
			$result = $this->news_model->get_one ( 'state = 1 AND id = ' . $id );
		}
		$this->load->view ( 'home/index_noticedetail', array (
				'result' => ! empty ( $result ) ? $result : '' 
		) );
	}
}