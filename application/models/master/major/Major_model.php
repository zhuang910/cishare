<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Major_Model extends CI_Model {
	const T_MAJOR = 'major';
	const T_FACULTY = 'faculty';
	const T_COURSE = 'course';
	const T_MAJOR_COURSE = 'major_course';
	const T_DEGREE_INFO = 'degree_info';
	const T_C = 'major_content';
	const T_S = 'scholarship_info';
	const T_T = 'templateclass'; // 模版
	const T_A = 'attachments'; // 附件
	const T_SQUAD = 'squad';
	const T_MAJOR_TUITION = 'major_tuition'; // 专业学费表
	const T_PAIKE = 'scheduling';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count($condition) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			$this->db->join ( self::T_FACULTY, self::T_MAJOR . '.facultyid=' . self::T_FACULTY . '.id' );
			// $this->db->join(self::T_COURSE ,self::T_MAJOR.'.id='.self::T_COURSE.'.majorid','left');
			// $this->db->group_by('course.majorid');
			return $this->db->from ( self::T_MAJOR )->count_all_results ();
		}
		return 0;
	}
	
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get($field, $condition) {
		if (is_array ( $field ) && ! empty ( $field )) {
			
			$this->db->select ( 'major.id,major.name,faculty.name as fname,major.englishname,alias,degree,termnum,termdays,coursenum,squadnum,major.state' );
			
			if (is_array ( $condition ) && ! empty ( $condition )) {
				if (! empty ( $condition ['where'] )) {
					$this->db->where ( $condition ['where'] );
				}
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			$this->db->join ( self::T_FACULTY, self::T_MAJOR . '.facultyid=' . self::T_FACULTY . '.id' );
			// $this->db->join(self::T_COURSE ,self::T_MAJOR.'.id='.self::T_COURSE.'.majorid','left');
			// $this->db->group_by('course.majorid');
			
			return $this->db->get ( self::T_MAJOR )->result ();
		}
		return array ();
	}
	function get_faculty() {
		return $this->db->where ( 'state <> 0' )->get ( self::T_FACULTY )->result ();
	}
	/**
	 * 删除
	 */
	function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_MAJOR, $where );
			return true;
		}
		return false;
	}
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_MAJOR )->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	/**
	 * 获取专业总学期学费
	 *
	 * @param number $majorid        	
	 */
	function get_major_tuition($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->get ( self::T_MAJOR_TUITION )->result_array ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	/**
	 * 保存基本信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save($id = null, $data = array()) {
		if (! empty ( $data )) {
			if ($id == null) {
				$this->db->insert ( self::T_MAJOR, $data );
				return $this->db->insert_id ();
			} else {
				return $this->db->update ( self::T_MAJOR, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 * [insert_major_tuition 保存专业每学期的学费]
	 *
	 * @param [type] $id
	 *        	[description]
	 * @param array $data
	 *        	[description]
	 * @return [type] [description]
	 */
	function insert_major_tuition($id = null, $data = array()) {
		if ($id !== null && ! empty ( $data )) {
			$insert_arr ['majorid'] = $id;
			foreach ( $data as $k => $v ) {
				$insert_arr ['term'] = $k;
				if (! empty ( $v )) {
					$insert_arr ['tuition'] = $v;
				} else {
					continue;
				}
				$this->db->insert ( self::T_MAJOR_TUITION, $insert_arr );
			}
		}
	}
	/**
	 * [update_major_tuition 更新专业学期学费]
	 *
	 * @param [type] $id
	 *        	[description]
	 * @param array $data
	 *        	[description]
	 * @return [type] [description]
	 */
	function update_major_tuition($id = null, $data = array()) {
		if ($id !== null && ! empty ( $data )) {
			$this->db->delete ( self::T_MAJOR_TUITION, 'majorid=' . $id );
			$this->insert_major_tuition ( $id, $data );
		}
	}
	/**
	 * 获取所有的班级id
	 */
	function get_squadid_all() {
		$this->db->select ( 'id,majorid' );
		return $this->db->get ( self::T_SQUAD )->result_array ();
	}
	/**
	 * 获取该班级的学期跨度
	 */
	function get_major_span($id) {
		$this->db->select ( 'termdays' );
		$this->db->where ( 'id', $id );
		$data = $this->db->get ( self::T_MAJOR )->row_array ();
		return $data ['termdays'];
	}
	// 获取该班级的跨度
	function get_squad_span($id) {
		$this->db->select ( 'spacing' );
		$this->db->where ( 'id', $id );
		$data = $this->db->get ( self::T_SQUAD )->row_array ();
		return $data ['spacing'];
	}
	// 获取班级的开班时间
	function get_squad_time($id) {
		$this->db->select ( 'classtime' );
		$this->db->where ( 'id', $id );
		$data = $this->db->get ( self::T_SQUAD )->row_array ();
		return $data ['classtime'];
	}
	// 获取该班级所在专业的学期数
	function get_major_num($id) {
		$this->db->select ( 'termnum' );
		$this->db->where ( 'id', $id );
		$data = $this->db->get ( self::T_MAJOR )->row_array ();
		return $data ['termnum'];
	}
	// 结束该班级
	function end_squad($id) {
		$data ['state'] = 0;
		return $this->db->update ( self::T_SQUAD, $data, 'id = ' . $id );
	}
	// 更新班级
	function update_squad_term($id, $s) {
		$data ['nowterm'] = $s;
		return $this->db->update ( self::T_SQUAD, $data, 'id = ' . $id );
	}
	/**
	 * 获取所有的课程
	 */
	function get_course() {
		return $this->db->get ( self::T_COURSE )->result_array ();
	}
	/**
	 * 获取所有的课程
	 */
	function get_course_limit() {
		$this->db->limit ( 10 );
		return $this->db->get ( self::T_COURSE )->result_array ();
	}
	/**
	 * 获取所有的课程
	 */
	function get_search_courseinfo($text) {
		$this->db->like ( 'name', $text );
		return $this->db->get ( self::T_COURSE )->result_array ();
	}
	/**
	 * 获取所有的课程
	 */
	function get_search_courseinfo_limit($text) {
		$this->db->limit ( 10 );
		$this->db->like ( 'name', $text );
		return $this->db->get ( self::T_COURSE )->result_array ();
	}
	/**
	 * 保存专业课程
	 */
	function save_course($data = array()) {
		// var_dump($data);exit;
		if ($data ['id'] != null) {
			if ($this->db->delete ( self::T_MAJOR_COURSE, 'id=' . $data ['id'] )) {
				$num = $this->course_num ( $data ['majorid'] );
				if ($this->db->update ( self::T_MAJOR, $num, 'id = ' . $data ['majorid'] )) {
					return 'del';
				}
			}
		}
		if (! empty ( $data )) {
			
			$this->db->insert ( self::T_MAJOR_COURSE, $data );
			$id = $this->db->insert_id ();
			if ($id) {
				$num = $this->course_num ( $data ['majorid'] );
				
				if ($this->db->update ( self::T_MAJOR, $num, 'id = ' . $data ['majorid'] )) {
					return $id;
				}
			}
			return 0;
		}
		return 0;
	}
	/**
	 * 计算课程数
	 */
	function course_num($majorid) {
		$this->db->select ( 'count(*) as coursenum' );
		$this->db->where ( 'majorid', $majorid );
		
		return $this->db->get ( self::T_MAJOR_COURSE )->row_array ();
	}
	/**
	 * 获取该专业课程
	 */
	function get_m_c($majorid) {
		$this->db->where ( 'majorid', $majorid );
		return $this->db->get ( self::T_MAJOR_COURSE )->result_array ();
	}
	/**
	 * 判断是否删除
	 */
	function check_m_c($data) {
		$this->db->where ( 'majorid', $data ['majorid'] );
		$this->db->where ( 'courseid', $data ['courseid'] );
		return $this->db->get ( self::T_MAJOR_COURSE )->row_array ();
	}
	/**
	 * 获取学位类型
	 */
	function get_degree($id) {
		if (! empty ( $id )) {
			$this->db->where ( 'id', $id );
			$data = $this->db->get ( self::T_DEGREE_INFO )->row_array ();
			return $data ['title'];
		} else {
			return $this->db->get ( self::T_DEGREE_INFO )->result_array ();
		}
	}
	
	/**
	 * 审核文章
	 *
	 * @param number $id        	
	 * @param number $state        	
	 */
	function save_audit($id = null, $state = 1) {
		if ($id !== null) {
			return $this->db->update ( self::T_MAJOR, array (
					'state' => $state 
			), 'id = ' . $id );
		}
	}
	
	/**
	 * 获得奖学金信息
	 */
	function get_scholarshi() {
		$data = array ();
		$data = $this->db->where ( "state = 1" )->get ( self::T_S )->result_array ();
		if ($data) {
			foreach ( $data as $k => $v ) {
				if ($v ['apply_state'] == 1) {
					$apply_state = '在学';
				} else {
					$apply_state = '新生';
				}
				$basic [$v ['id']] = $apply_state . '--' . $v ['title'];
			}
			return $basic;
		}
		
		return array ();
	}
	
	/**
	 * 模版信息
	 */
	function get_templates() {
		$data = array ();
		$where = "classType = 1";
		$where .= ' AND admin_id = 0';
		$data = $this->db->where ( $where )->get ( self::T_T )->result_array ();
		if ($data) {
			foreach ( $data as $k => $v ) {
				$basic [$v ['tClass_id']] = $v ['ClassName'];
			}
			return $basic;
		}
		return array ();
	}
	
	/**
	 * 附件信息
	 */
	function get_attachments() {
		$data = array ();
		$data = $this->db->where ( 'atta_id > 1' )->get ( self::T_A )->result_array ();
		if ($data) {
			foreach ( $data as $k => $v ) {
				$basic [$v ['atta_id']] = $v ['AttaName'];
			}
			return $basic;
		}
		return array ();
	}
	
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_course_content($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_C )->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	
	/**
	 * 删除内容
	 */
	function del_major($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_C, $where );
			return true;
		}
		return false;
	}
	
	/**
	 * 保存基本信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_major($id = null, $data = array()) {
		if (! empty ( $data )) {
			if ($id == null) {
				$this->db->insert ( self::T_C, $data );
				return $this->db->insert_id ();
			} else {
				return $this->db->update ( self::T_C, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 * 获取关联的专业的所有课程
	 */
	function get_major_course() {
		$this->db->select ( 'course.id,course.name' );
		$this->db->join ( self::T_COURSE, self::T_MAJOR_COURSE . '.courseid=' . self::T_COURSE . '.id' );
		$this->db->group_by ( 'major_course.courseid' );
		return $this->db->get ( self::T_MAJOR_COURSE )->result_array ();
	}
	/**
	 * 获取院系信息
	 */
	function get_facultys() {
		return $this->db->where ( 'state <> 0' )->get ( self::T_FACULTY )->result_array ();
	}
	/**
	 * 获取专业字段
	 */
	function get_major_fields() {
		return array (
				'name' => '名称',
				'facultyid' => '学院id',
				'englishname' => '英文名字',
				'alias' => '别名',
				'degree' => '学历id',
				'termnum' => '学期数',
				'termdays' => '学期天数',
				'coursenum' => '课程总数',
				'squadnum' => '班级总数',
				'state' => '是否停用1是0否',
				'opentime' => '专业指定开学时间',
				'endtime' => '请指定申请截止日期',
				'regtime' => '指定注册时间',
				'schooling' => '指定学制',
				'xzunit' => '学制的单位 ',
				'tuition' => '请指定学费',
				'applytuition' => '请指定申请费',
				'language' => '授课语言 ',
				'hsk' => 'hsk要求',
				'minieducation' => '最低学历要求',
				'isapply' => '是否可申请',
				'attatemplate' => '请指定附件模板',
				'applytemplate' => '请指定申请表模板',
				'scholarship' => '关联奖学金',
				'createtime' => '创建时间',
				'difficult' => '录取难度',
				'cashpledge' => '押金',
				'isdeposit' => '押金 1 需要 -1 不需要',
				'ispageoffer' => '纸质offer 1 需要 -1 不需要',
				'video' => '视频地址',
				'orderby' => '排序' 
		);
	}
	/**
	 * 插入字段
	 */
	function insert_fields($insert, $value) {
		$sql = 'insert into zust_major (' . $insert . ') values(' . $value . ')';
		$this->db->query ( $sql );
	}
	/**
	 * 获取院系id
	 */
	function get_facultyid($name) {
		$this->db->select ( 'id' );
		$this->db->where ( 'name', $name );
		$data = $this->db->get ( self::T_FACULTY )->row_array ();
		return $data ['id'];
	}
	/**
	 * 获取学历id
	 */
	function get_degrees($name) {
		$this->db->where ( 'title', $name );
		$data = $this->db->get ( self::T_DEGREE_INFO )->row_array ();
		return $data ['id'];
	}
	/**
	 * 获取学制单位
	 */
	function get_program_unit($data, $name) {
		foreach ( $data as $key => $value ) {
			if ($value == $name) {
				return $key;
			} else {
				return 'null';
			}
		}
	}
	/**
	 * 获取授课语言
	 */
	function get_language($data, $name) {
		foreach ( $data as $key => $value ) {
			if ($value == $name) {
				return $key;
			} else {
				return 'null';
			}
		}
	}
	/**
	 * 获取hsk
	 */
	function get_hsk($data, $name) {
		foreach ( $data as $key => $value ) {
			if ($value == $name) {
				return $key;
			} else {
				return 'null';
			}
		}
	}
	/**
	 * 获取最低学历要求
	 */
	function get_degree_type($data, $name) {
		foreach ( $data as $key => $value ) {
			if ($value == $name) {
				return $key;
			} else {
				return 'null';
			}
		}
	}
	/**
	 * 获取是否申请
	 */
	function get_isapply($data, $name) {
		foreach ( $data as $key => $value ) {
			if ($value == $name) {
				return $key;
			} else {
				return 'null';
			}
		}
	}
	/**
	 * 获取录取难度
	 */
	function get_difficult($data, $name) {
		foreach ( $data as $key => $value ) {
			if ($value == $name) {
				return $key;
			} else {
				return 'null';
			}
		}
	}
	/**
	 * 获取申请附件模板id
	 */
	function get_attatemplatename($name) {
		$this->db->select ( 'atta_id' );
		$this->db->where ( 'AttaName', $name );
		$data = $this->db->get ( self::T_A )->row_array ();
		return $data ['atta_id'];
	}
	/**
	 * 获取申请附件模板id
	 */
	function get_applytemplatename($name) {
		$this->db->select ( 'tClass_id' );
		$this->db->where ( 'ClassName', $name );
		$this->db->where ( 'parent_id', 0 );
		$this->db->where ( 'classType', 1 );
		$data = $this->db->get ( self::T_T )->row_array ();
		return $data ['tClass_id'];
	}
	/**
	 * 检查是否有重复记录
	 * @$insert:字段
	 * @$value:字段值
	 */
	function check_major($insert, $value) {
		$insert = explode ( ',', $insert );
		$value = explode ( ',', $value );
		$this->db->select ( 'count(*) as count' );
		$this->db->where ( $insert [0], trim ( $value [0], '""' ) );
		$data = $this->db->get ( self::T_MAJOR )->row_array ();
		return $data ['count'];
	}
	/**
	 * 删除关联的课程关系表
	 */
	function delete_guanlian($id) {
		if ($id != null) {
			
			$this->db->delete ( self::T_MAJOR_COURSE, 'majorid = ' . $id );
			$this->db->delete ( self::T_SQUAD, 'majorid = ' . $id );
			$this->db->delete ( self::T_PAIKE, 'majorid = ' . $id );
		}
		return false;
	}
}