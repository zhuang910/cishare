<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 前台课程
 *
 * @author JJ
 *        
 */
class Teacher extends Home_Basic {
	protected $bread_line = null;
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'home/teacher_team_Model' );
		
		$this->bread_line = '<div class="crumbs-nav mg_t_b_5030"><a href="/' . $this->puri . '/">' . lang ( 'nav_1' ) . '</a><i> / </i>';
	}
	
	/**
	 */
	function index() {
		// 查询所有的师资按分类//汉语名仕
		$teacher_hyms = $this->teacher_team_Model->get_all ( 'columnid = 103 AND state = 1 AND site_language = ' . $this->where_lang );
		// 专业名师
		$teacher_zyms = $this->teacher_team_Model->get_all ( 'columnid =104 AND state = 1 AND site_language = ' . $this->where_lang );
		
		//
		$teacher_name_all = $this->db->select ( 'id,englishname' )->get_where ( 'teacher', 'id > 0' )->result_array ();
		if (! empty ( $teacher_name_all )) {
			foreach ( $teacher_name_all as $k => $v ) {
				$teacher_name [$v ['id']] = $v ['englishname'];
			}
		}
		
		// 查询师资的广告]
		$teacher_advance = $this->db->select ( '*' )->limit ( 1 )->order_by ( 'orderby DESC' )->get_where ( 'advance_info', 'state = 1 AND columnid = 2 AND site_language = ' . $this->where_lang )->row ();
		
		$this->load->view ( 'home/teacher_index', array (
				'teacher_hyms' => ! empty ( $teacher_hyms ) ? $teacher_hyms : array (),
				'teacher_zyms' => ! empty ( $teacher_zyms ) ? $teacher_zyms : array (),
				'teacher_advance' => ! empty ( $teacher_advance ) ? $teacher_advance : array (),
				'teacher_name' => ! empty ( $teacher_name ) ? $teacher_name : array () 
		) );
	}
	
	/**
	 * 课程详情页
	 */
	function detail() {
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		
		if (! empty ( $id )) {
			// 基本信息
			$teacher_basic = $this->teacher_team_Model->get_one ( 'state = 1 AND id = ' . $id );
		}
		
		// 查询精品课程
		$teacher_jpkc = $this->teacher_team_Model->get_jpkc ( $id );
		
		$teacher_jxsb = $this->teacher_team_Model->get_jxsb ( $id );
		$this->bread_line .= '<a href="/' . $this->puri . '/teacher">' . lang ( 'nav_' . $teacher_basic->columnid ) . '</a><i> / </i><span>' . lang ( 'look_detail' ) . '</span></div>';
		
		// 查询评价
		$this->load->library ( 'pager' ); // 调用分页类
		$where = 'state = 1 AND teacherid = ' . $id;
		$page = ! empty ( $_GET ['page'] ) ? $_GET ['page'] : 1;
		$getcount = $this->teacher_team_Model->counts ( $where );
		$pagecount = ! empty ( $getcount ['pagecount'] ) ? $getcount ['pagecount'] : 1;
		list ( $offset, $size, $pagestring ) = $this->pager->pagestring ( $getcount ['allcount'], 2 );
		
		$evaluate = $this->teacher_team_Model->getall ( $where, '*', $offset, $size, 'createtime DESC,id DESC' );
		
		// 查询教师的平均值
		$avg = $this->db->select_avg ( 'scoreall' )->get_where ( 'teacher_evaluate', $where )->row ();
		$avg_t = ! empty ( $avg->scoreall ) ? floor ( $avg->scoreall ) : 0;
		
		$teacher_name_all = $this->db->select ( 'id,englishname' )->get_where ( 'teacher', 'id > 0' )->result_array ();
		if (! empty ( $teacher_name_all )) {
			foreach ( $teacher_name_all as $k => $v ) {
				$teacher_name [$v ['id']] = $v ['englishname'];
			}
		}
		$this->load->view ( 'home/teacher_detail', array (
				
				'teacher_basic' => ! empty ( $teacher_basic ) ? $teacher_basic : array (),
				'bread_line' => ! empty ( $this->bread_line ) ? $this->bread_line : '',
				'evaluate' => ! empty ( $evaluate ) ? $evaluate : array (),
				'nationality' => $nationality,
				'page' => $page,
				'size' => $size,
				'pagecount' => $pagecount,
				'pagestring' => $pagestring,
				'avg_t' => $avg_t,
				'teacher_jpkc' => ! empty ( $teacher_jpkc ) ? $teacher_jpkc : array (),
				'teacher_jxsb' => ! empty ( $teacher_jxsb ) ? $teacher_jxsb : array (),
				'teacher_name' => ! empty ( $teacher_name ) ? $teacher_name : array () 
		) );
	}
	
	/**
	 * 获取 图片集
	 */
	function get_images() {
		$jpkcid = intval ( trim ( $this->input->get ( 'jpkcid' ) ) );
		if ($jpkcid) {
			$images = $this->db->select ( '*' )->get_where ( 'teacher_jpkc_img', 'jpkcid = ' . $jpkcid )->result_array ();
			$html = $this->load->view ( 'home/major_images', array (
					'images' => ! empty ( $images ) ? $images : array () 
			), true );
			ajaxReturn ( $html, '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
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
						'teacherid' => $teacherid ,
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
			if (isset ( $data ['score'] )) {
				foreach ( $data ['score'] as $kk => $vv ) {
					if (empty ( $vv )) {
						unset ( $data ['score'] );
					}
				}
			}
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
			$data ['scoreall'] =   $scoreall / 4 ;
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