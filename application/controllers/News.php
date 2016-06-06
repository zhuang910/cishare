<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 前台课程
 *
 * @author JJ
 *        
 */
class News extends Home_Basic {
	protected $bread_line = null;
	protected $zyj_nav = null;
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'home/news_model' );
		$this->zyj_nav = menu_tree ( 0, $this->nav, 'programaid' );
		foreach ( $this->zyj_nav as $k => $v ) {
			if ($v ['programaid'] == 85) {
				$l_n = $v ['childs'];
			}
		}
		$this->load->vars ( 'l_n', $l_n );
		$this->bread_line = '<div class="crumbs-nav mg_t_b_5030"><a href="/' . $this->puri . '/">' . lang ( 'nav_1' ) . '</a><i> / </i>';
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
			
			$this->load->view ( 'home/news_index', array (
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
			$this->load->view ( 'home/news_detail', array (
					'result' => ! empty ( $result ) ? $result : array (),
					'programaid' => ! empty ( $result ['columnid'] ) ? $result ['columnid'] : '',
					'jpkc' => ! empty ( $jpkc ) ? $jpkc : array (),
					'imgs' => ! empty ( $imgs ) ? $imgs : array () 
			) );
		}
	}
	
	/**
	 * 得到数据
	 */
	function get_data() {
		$teacherid = intval ( trim ( $this->input->get ( 'teacherid' ) ) );
		$page = intval ( trim ( $this->input->get ( 'page' ) ) );
		$flag = intval ( trim ( $this->input->get ( 'flag' ) ) );
		if ($teacherid && $page && $flag) {
			$nationality = CF ( 'nationality', '', 'application/cache/' );
			$this->load->library ( 'pager' ); // 调用分页类
			$where = 'state = 1 AND teacherid = ' . $teacherid;
			
			$getcount = $this->teacher_team_Model->counts ( $where );
			$pagecount = ! empty ( $getcount ['pagecount'] ) ? $getcount ['pagecount'] : 1;
			if ($page >= 1 && $page <= $pagecount) {
				$size = 2;
				$offset = ($page - 1) * $size;
				$evaluate = $this->teacher_team_Model->getall ( $where, '*', $offset, $size, 'createtime DESC,id DESC' );
				$html = $this->load->view ( 'home/teacher_get_data', array (
						'evaluate' => ! empty ( $evaluate ) ? $evaluate : array (),
						'nationality' => $nationality,
						'page' => $page,
						'teacherid' => $teacherid 
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
	 * 判断是否登录
	 */
	function is_ts_login() {
		$backurl = trim ( $this->input->get ( 'backurl' ) );
		if (isset ( $_SESSION ['student'] ['userinfo'] )) {
			// 登录了
			
			ajaxReturn ( '', '', 1 );
		} else {
			// 未登录 直接弹出登录
			$html = $this->load->view ( 'home/login_ajax', array (
					'backurl' => $backurl 
			), 

			true );
			ajaxReturn ( $html, '', 0 );
		}
	}
	
	/**
	 * 评价
	 */
	function doevaluate() {
		$data = $this->input->post ();
		if (! empty ( $data )) {
			if (empty ( $data ['score'] ) || count ( $data ['score'] ) != 4) {
				ajaxReturn ( '', lang ( 'evaluate_wz' ), 0 );
			}
			$scoreall = 0;
			foreach ( $data ['score'] as $k => $v ) {
				$key = 'score' . ($k + 1);
				$data [$key] = $v;
				$scoreall += $v;
			}
			
			unset ( $data ['score'] );
			$data ['createtime'] = time ();
			$data ['studentid'] = $_SESSION ['student'] ['userinfo'] ['id'];
			$data ['name'] = ! empty ( $_SESSION ['student'] ['userinfo'] ['enname'] ) ? $_SESSION ['student'] ['userinfo'] ['enname'] : 'Anonymous';
			$data ['nationality'] = ! empty ( $_SESSION ['student'] ['userinfo'] ['nationality'] ) ? $_SESSION ['student'] ['userinfo'] ['nationality'] : '0';
			$data ['state'] = 1;
			$data ['scoreall'] = $scoreall / 5;
			$data ['photo'] = ! empty ( $_SESSION ['student'] ['userinfo'] ['photo'] ) ? $_SESSION ['student'] ['userinfo'] ['photo'] : '';
			$flag = $this->teacher_team_Model->save_evaluate ( $data );
			if ($flag) {
				ajaxReturn ( '', lang ( 'evaluate_success' ), 1 );
			} else {
				ajaxReturn ( '', lang ( 'evaluate_error' ), 0 );
			}
		} else {
			ajaxReturn ( '', lang ( 'evaluate_error' ), 0 );
		}
	}
}