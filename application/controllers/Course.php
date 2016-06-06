<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 前台课程
 *
 * @author JJ
 *        
 */
class Course extends Student_Basic {
	var $bread_line = null;
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		is_studentlogin ();
		$this->load->model ( 'home/course_model' );
		$publics = CF ( 'publics', '', CONFIG_PATH );
		$this->load->vars ( 'publics', $publics );
		$this->view = 'home/';
		$this->bread_line = '<div class="crumbs-nav mg_t_b_5030"><a href="/' . $this->puri . '/">' . lang ( 'nav_1' ) . '</a><i> / </i>';
	}
	
	/**
	 */
	function index() {
		//获取学历的信息
		$degree_info=$this->db->where('state = 1')->order_by('orderby ASC')->get('degree_info')->result_array();
		$degree = intval ( trim ( $this->input->get ( 'degree' ) ) );
		if (empty ( $degree )) {
			if(!empty($degree_info)){
			$degree=$degree_info[0]['id'];
			$_GET ['degree'] = $degree_info[0]['id'];
			}
			
		}
		
		// 查询是否有精品课程
		$ispriority = $this->db->select('*')->get_where('major','state = 1 AND ispriority = 1')->result_array();
		$searchname = intval ( trim ( $this->input->get ( 'searchname' ) ) );
		
		$where = 'id > 0 AND state = 1 AND isapply = 1';
		
		if (! empty ( $degree )) {
			/*if ($degree == 1) {
				$where .= ' AND degree NOT IN (2,3,4,5)';
			} else if($degree == 99999) {
				$where .= ' AND ispriority = 1';
			}else{
				$where .= ' AND degree = '.$degree;
				
			}*/
			$where .= ' AND degree = '.$degree;
		}
		
		if (! empty ( $searchname )) {
			$where .= ' AND id = ' . $searchname;
		}
		
		$course = $this->course_model->get_course_base ( $where ,0,0,'orderby desc');
		$course_name_all = $this->course_model->get_course_base ( 'state = 1  AND degree = ' . $degree.' AND isapply = 1' );
		
		if (! empty ( $course_name_all )) {
			foreach ( $course_name_all as $k => $v ) {
				if ($this->puri == 'en') {
					$course_name [$v ['id']] = $v ['englishname'];
				} else {
					$course_name [$v ['id']] = $v ['name'];
				}
			}
		}
		// 筛选出拒绝后的专业
		$not_course = $this->course_model->get_ont_course ( $_SESSION ['student'] ['userinfo'] ['id'] );
		if (! empty ( $not_course )) {
			foreach ( $course as $k => $v ) {
				$is = 1;
				foreach ( $not_course as $kk => $vv ) {
					if ($v ['id'] == $vv ['courseid']) {
						$is = 0;
					}
				}
				if ($is == 0) {
					unset ( $course [$k] );
				}
			}
		}
		//已经报名过的专业
		$old_course=$this->db->get_where('apply_info','userid = '.$_SESSION ['student'] ['userinfo'] ['id'])->result_array();
		if(!empty($old_course)){
			foreach ($course as $key => $value) {
				$is = 1;
				foreach ( $old_course as $kk => $vv ) {
					if ($value ['id'] == $vv ['courseid']) {
						$is = 0;
					}
				}
				if ($is == 0) {
					unset ( $course [$key] );
				}
			}
		}
		$this->_view ( 'course_index', array (
				'course' => ! empty ( $course ) ? $course : array (),
				'course_name' => ! empty ( $course_name ) ? $course_name : array () ,
				'degree_info'=>!empty($degree_info)?$degree_info:array(),
				'ispriority'=>!empty($ispriority)?$ispriority:array()
		) );
	}
	
	/**
	 * 特色课程
	 */
	function feature() {
	}
	
	/**
	 * 课程详情页
	 */
	function detail() {
		$degree = CF ( 'degree', '', CONFIG_PATH );
		$publics = CF ( 'publics', '', CONFIG_PATH );
		$lang = $publics ['language'];
		$education = $publics ['education'];
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		$site_language = intval ( trim ( $this->input->get ( 'site_language' ) ) );
		if (! empty ( $id )) {
			// 基本信息
			$course_basic = $this->course_model->get_one ( 'state = 1 AND id = ' . $id );
			// 课程内荣
			$course_content = $this->course_model->get_one_content ( 'majorid = ' . $id . ' AND site_language = ' . $this->where_lang );
			
			// 图片
			// $course_images = $this->course_model->get_images ( 'majorid = ' . $id . ' AND site_language = ' . $this->where_lang );
		}
		if (! empty ( $course_content )) {
			$this->bread_line .= '<a href="/' . $this->puri . '/course/feature">' . lang ( 'degree_' . $course_basic ['degree'] ) . '</a><i> / </i><span>' . $course_content->langname . '</span></div>';
		} else {
			$this->bread_line .= '<a href="/' . $this->puri . '/course/feature">' . lang ( 'degree_' . $course_basic ['degree'] ) . '</a></div>';
		}
		// 数组
		$c_s = array (
				
				'1' => 'requirement',
				'2' => 'introduce',
				'3' => 'applymaterial',
				'4' => 'kctd',
				'5' => 'cyfx',
				'6' => 'rhsq' 
		);
		
		// 数组
		$course_css = array (
				
				'1' => 'Program_Highlights',
				'2' => 'Admission_Requirements',
				'3' => 'Main_Courses',
				'4' => 'Training_Objectives',
				'5' => 'Practice_Link',
				'6' => 'Employment_Direction' 
		);
		
		$course_css_zyj = array (
				
				'1' => 'Program Highlights',
				'2' => 'Admission Requirements',
				'3' => 'Main Courses',
				'4' => 'Training Objectives',
				'5' => 'Practice Link',
				'6' => 'Employment Direction' 
		);
		
		// 评论
		$pl = $this->db->select ( '*' )->limit ( 2 )->get_where ( 'major_pl', 'majorid = ' . $id . ' AND site_language = ' . $this->where_lang )->result_array ();
		
		// 图集的第一张图
		$img = $this->db->where ( 'majorid = ' . $id . ' AND site_language = ' . $this->where_lang )->order_by ( 'orderby DESC' )->limit ( 1 )->get ( 'major_images' )->result_array ();
		$c_s = array_flip ( $c_s );
		
		if (! empty ( $c_s ) && ! empty ( $course_content )) {
			foreach ( $c_s as $zk => $zv ) {
				if (empty ( $course_content->$zk ) || $course_content->$zk == '<br>') {
					unset ( $c_s [$zk] );
				}
			}
		}
		$c_s = array_flip ( $c_s );
		$schlorship_flag = 0;
		if (! empty ( $course_basic ['scholarship'] )) {
			$scholorship = $this->db->select ( 'id,title,count' )->get_where ( 'scholarship_info', 'id = ' . $course_basic ['scholarship'] . ' AND state = 1' )->row ();
			// 已经申请的数量 且通过的
			$count_apply = 0;
			// 数量
			$count = $this->db->select ( 'id' )->get_where ( 'apply_info', 'scholorshipid = ' . $scholorship->id . ' AND state = 8' )->result_array ();
			
			if (! empty ( $count )) {
				$count_apply = count ( $count );
			}
			
			if (empty ( $scholorship->count )) {
				$schlorship_flag = 1;
			} else {
				
				if (($scholorship->count - $count_apply) > 0) {
					$schlorship_flag = 1;
				} else {
					$schlorship_flag = 0;
				}
			}
		}
		
		$this->_view ( 'course_detail', array (
				'course_basic' => ! empty ( $course_basic ) ? $course_basic : array (),
				'course_content' => ! empty ( $course_content ) ? $course_content : array (),
				// 'course_images' => ! empty ( $course_images ) ? $course_images : array (),
				'bread_line' => ! empty ( $this->bread_line ) ? $this->bread_line : '',
				'degree' => $degree,
				'lang' => $lang,
				'education' => $education,
				'c_s' => ! empty ( $c_s ) ? $c_s : array (),
				'pl' => ! empty ( $pl ) ? $pl : array (),
				'img' => ! empty ( $img ) ? $img : array (),
				'course_css' => ! empty ( $course_css ) ? $course_css : array (),
				'course_css_zyj' => ! empty ( $course_css_zyj ) ? $course_css_zyj : array (),
				'scholorship' => ! empty ( $scholorship ) ? $scholorship : '',
				'schlorship_flag' => $schlorship_flag 
		) );
	}
	
	/**
	 * 获取 图片集
	 */
	function get_images() {
		$majorid = intval ( trim ( $this->input->get ( 'majorid' ) ) );
		if ($majorid) {
			$images = $this->db->select ( '*' )->get_where ( 'major_images', 'majorid = ' . $majorid . ' AND site_language = ' . $this->where_lang )->result_array ();
			$html = $this->load->view ( 'home/major_images', array (
					'images' => ! empty ( $images ) ? $images : array () 
			), true );
			ajaxReturn ( $html, '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 验证是否登录
	 * 先判断 是否是 登录的状态
	 * 如果是 登录的状态
	 * 再去验证课程
	 * 这里 只验证 课程是否是 过期课程
	 * 如果是 显示 同类型的课程
	 * 如果没有过期
	 * 跳到申请去
	 * 在申请那里 再做 操作
	 */
	function is_course_login() {
		$courseid = intval ( trim ( $this->input->get ( 'courseid' ) ) );
		if (isset ( $_SESSION ['student'] ['userinfo'] )) {
			// 登录了
			$flag = $this->is_deadline ( $courseid );
			if ($flag === true) {
				// 跳转到 申请页面
				ajaxReturn ( '/' . $this->puri . '/student/apply?courseid=' . cucas_base64_encode ( $courseid ), '', 1 );
			} else {
				// 过期了 弹出 推荐页面
				// $html = $this->load->view ( 'course/is_course_login', array (
				// 'result' => ! empty ( $flag ) ? $flag : ''
				// ), true );
				ajaxReturn ( '', '', 2 );
			}
		} else {
			// 未登录 直接弹出登录
			$html = $this->load->view ( 'home/login_ajax', array (
					'courseid' => $courseid 
			), true );
			ajaxReturn ( $html, '', 0 );
		}
	}
	
	/**
	 * 验证课程是否过期
	 */
	function is_deadline($courseid) {
		if ($courseid) {
			$where = "id = {$courseid} AND state = 1";
			$result = $this->course_model->get_one ( $where );
			if ($result ['endtime'] > time ()) {
				return true;
			} else {
				/*
				 * // if (in_array ( $result->columnid, array ( // 28, // 29 // ) )) { // return true; // } // 过期了 查询一下 相同栏目下的课程 $time = time (); $whereL = "columnid = {$result['columnid']} AND state = 1 AND id != {$courseid} AND deadline > {$time}"; $clist = $this->course_model->getall ( $whereL, '*', $offset = '0', $size = '5', $orderby = 'id DESC' ); if (! empty ( $clist )) { $course_names = $this->course_model->get_course_content ( 'id >0' ); foreach ( $course_names as $k => $v ) { $c [$v ['courseid']] [$v ['site_language']] = $v ['name']; } foreach ( $clist as $k => $v ) { $clist [$k] ['name'] = $c [$v ['id']] [$_SESSION ['lang_default']]; $clist [$k] ['site_language'] = $_SESSION ['lang_default']; } } return $clist;
				 */
				return false;
			}
		}
	}
	
	/**
	 * 播放视频
	 */
	function video() {
		$url = $this->input->get_post ( 'url' );
		if (! empty ( $url )) {
			$url_array = explode ( '.', $url );
			
			if (in_array ( 'youku', $url_array )) {
				$html = $this->_view ( 'video_youku', array (
						'url' => $url 
				), true );
				exit ( $html );
			} else {
				$html = $this->_view ( 'video_haoke', array (
						'url' => $url 
				), true );
				exit ( $html );
			}
			
			// if (in_array ( 'youku', $url_array )) {
			// // 优酷
			
			// } else {
			// // 好课
			// $data = '<object width="850" height="550" id="cc_081D346120FF4D919C33DC5901307461" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"><param value="' . $url . '" name="movie"><param name="wmode" value="transparent"><param value="true" name="allowFullScreen"><param value="always" name="allowScriptAccess"><embed width="850" height="550" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" allowscriptaccess="always" allowfullscreen="true" wmode="transparent" name="cc_081D346120FF4D919C33DC5901307461" src="' . $url . '" style="width: 850px; height: 550px;"></object>';
			
			// ajaxReturn ( $data, '', 2 );
			// }
			// } else {
			// ajaxReturn ( '', '', 0 );
		}
	}
}