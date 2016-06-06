<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @name 课程管理 MODEL
 * @package Course_Model
 * @author cucas Team [xuejiao]
 * @copyright Copyright (c) 2014-1-06, cucas
 */
class Attachment_Model extends CI_Model {
	const T_T = 'attachments'; // 附件模版
	const T_F = 'attachmentstopic'; // 附件项目
	const T_C = 'school_account';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	/**
	 * 获取所有的附件模版 1
	 */
	function get_attachment_info($where = null) {
		if ($where != null) {
			return $lists = $this->db->where ( $where )->order_by ( 'atta_id DESC' )->get ( self::T_T )->result_array ();
		} else {
			return array ();
		}
	}
	
	/**
	 * 保存模版信息 1
	 */
	function save_attachment($where = null, $data) {
		if ($where != null) {
			return $this->db->update ( self::T_T, $data, $where );
		} else {
			$this->db->insert ( self::T_T, $data );
			return $this->db->insert_id ();
		}
	}
	
	/**
	 * 获取列表数据 1
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get_attachmentitem_count($where = null) {
		$count = $this->db->get_where ( self::T_F, $where )->num_rows ();
		return $count;
	}
	
	/**
	 * 删除模版 1
	 */
	function del_attachment($where = null) {
		if ($where != null) {
			return $this->db->delete ( self::T_T, $where );
		}
		return false;
	}
	
	/**
	 * 获取所有的附件模版 项的数据 1
	 */
	function get_attachmentstopic_info($where = null) {
		if ($where != null) {
			return $lists = $this->db->where ( $where )->order_by ( 'line DESC' )->get ( self::T_F )->result_array ();
		} else {
			return array ();
		}
	}
	
	/**
	 * 保存附件项 1
	 */
	function save_attachmentstopic($where = null, $data) {
		if ($where != null) {
			return $this->db->update ( self::T_F, $data, $where );
		} else {
			$this->db->insert ( self::T_F, $data );
			return $this->db->insert_id ();
		}
	}
	
	/**
	 * 删除附件 项 1
	 */
	function del_attachmentstopic($where = null) {
		if ($where != null) {
			return $this->db->delete ( self::T_F, $where );
		}
		return false;
	}
	
	/**
	 * 获取某个群组下的项
	 */
	function get_formtopic_info($where = null) {
		if ($where != null) {
			return $lists = $this->db->where ( $where )->order_by ( 'line DESC' )->get ( self::T_F )->result_array ();
		} else {
			return array ();
		}
	}
	
	/**
	 * 获取项下面的值
	 */
	function get_formitem_info($where = null) {
		if ($where != null) {
			return $lists = $this->db->where ( $where )->order_by ( 'line DESC' )->get ( self::T_F_I )->result_array ();
		} else {
			return array ();
		}
	}
	
	/**
	 * 获取帐号
	 */
	function get_all_account() {
		$list = $this->db->get ( self::T_C )->result ();
		$data = array ();
		foreach ( $list as $key => $val ) {
			$data [$val->id] = ( array ) $val;
		}
		return $data;
	}
	
	/**
	 * 查询项的数量
	 */
	function get_items_count($where = null) {
		$count = $this->db->get_where ( self::T_F, $where )->num_rows ();
		return $count;
	}
	
	/**
	 * 保存项的信息
	 */
	function save_formtopic($where = null, $data) {
		if ($where != null) {
			return $this->db->update ( self::T_F, $data, $where );
		} else {
			$this->db->insert ( self::T_F, $data );
			return $this->db->insert_id ();
		}
	}
	
	/**
	 * 保存项的信息
	 */
	function save_formitem($where = null, $data) {
		if ($where != null) {
			return $this->db->update ( self::T_F_I, $data, $where );
		} else {
			$this->db->insert ( self::T_F_I, $data );
			return $this->db->insert_id ();
		}
	}
	
	/**
	 * 删除项
	 */
	function del_items($where = null) {
		if ($where != null) {
			return $this->db->delete ( self::T_F, $where );
		}
		return false;
	}
	
	/**
	 * 保存项的值的信息
	 */
	function del_get_formitem_info($where = null) {
		if ($where != null) {
			return $this->db->delete ( self::T_F_I, $where );
		}
		return false;
	}
	
	/**
	 * 上面的是用的
	 */
	
	/* 查询课程的类别是非学历 还是学历 还是MBBS */
	function get_course_type($id = null) {
		if ($id != null) {
			$info = $this->db->select ( 'iseducation' )->where ( 'id', $id )->get ( self::T_COURSE )->row_array ();
			return $info ['iseducation'];
		}
		return false;
	}
	
	/**
	 * 获取非学历的所有的分类
	 */
	function get_cat_list($where = null) {
		if ($where != null) {
			$infos = $info = array ();
			$infos = $this->db->where ( $where )->get ( self::T_COURSE_CAT )->result_array ();
			if ($infos) {
				foreach ( $infos as $k => $v ) {
					$info [$v ['id']] = trim ( $v ['title'] );
				}
			}
			return $info;
		}
	}
	
	/* 写入课程基本表 */
	function insert_course($data) {
		if (! empty ( $data )) {
			return $this->db->insert ( self::T_COURSE, $data );
		}
		return false;
	}
	
	/**
	 * 写入分类表
	 */
	function insert_course_cat($data) {
		if (! empty ( $data )) {
			$id = $this->db->insert ( self::T_COURSE_CAT, $data );
			return $this->db->insert_id ();
		}
		return false;
	}
	
	/**
	 * 更新课程基本表信息
	 */
	function update_course($id = null, $data = array()) {
		if ($id != null && ! empty ( $data ) && is_array ( $data )) {
			return $this->db->update ( self::T_COURSE, $data, 'id = ' . $id );
		}
		return false;
	}
	
	/**
	 * 更新课程基本表信息ssss
	 */
	function update_course_d($id = null, $data = array()) {
		if ($id != null && ! empty ( $data ) && is_array ( $data )) {
			return $this->db->update ( self::T_COURSE_DEGREE, $data, 'courseid = ' . $id );
		}
		return false;
	}
	/**
	 * 获取程基本信息id 数组集
	 */
	function get_infos_ids($where) {
		if ($where != null) {
			$info = $this->db->select ( 'id' )->where ( $where )->get ( self::T_COURSE_INFOS )->result_array ();
			if ($info) {
				$ck_rule_id = array ();
				foreach ( $info as $value ) {
					$ck_rule_id [] = $value ['id'];
				}
				return $ck_rule_id;
			}
		}
	}
	/**
	 * 删除课程INFOS中的某块
	 */
	function delete_infos_by_id($id = null) {
		if ($id != null) {
			return $this->db->delete ( self::T_COURSE_INFOS, array (
					'id' => $id 
			) );
		}
		return false;
	}
	// 获取课程INFO信息
	function get_infos_($id = null) {
		if ($id != null) {
			$info = $this->db->where ( 'id', $id )->get ( self::T_COURSE_INFOS )->result ();
			if ($info) {
				return $info [0];
			}
		}
	}
	/**
	 * 获取课程基本信息
	 * $id 课程id
	 */
	function get_course_infos($id = null) {
		if ($id != null) {
			$infos = $this->db->where ( 'courseid', $id )->order_by ( 'order', 'DESC' )->get ( self::T_COURSE_INFOS )->result_array ();
			return $infos;
		}
	}
	/* 写入课程信息表 */
	function insert_course_info($data) {
		if (! empty ( $data )) {
			return $this->db->insert ( self::T_COURSE_INFOS, $data );
		}
		return false;
	}
	/**
	 * 更新课程info信息信息
	 */
	function update_course_info($id = null, $data = array()) {
		if ($id != null && ! empty ( $data ) && is_array ( $data )) {
			return $this->db->update ( self::T_COURSE_INFOS, $data, 'id = ' . $id );
		}
		return false;
	}
	
	/**
	 * 更新课程info信息信息
	 * 批量修改信息
	 */
	function update_course_infos($id = array(), $data = array()) {
		if (! empty ( $id ) && is_array ( $id ) && ! empty ( $data ) && is_array ( $data )) {
			return $this->db->update ( self::T_COURSE_INFOS, $data, $id );
		}
		return false;
	}
	
	/**
	 * 删除mbbs课程
	 * (int)$id 课程id
	 */
	function delete_course_mbbs($id = null) {
		if ($id != null) {
			$info = $this->db->where ( 'id', $id )->get ( self::T_COURSE )->row_array ();
			if (! empty ( $info )) {
				$this->db->delete ( self::T_COURSE_MBBS, array (
						'courseid' => $info ['id'] 
				) );
				$this->db->delete ( self::T_COURSE_INFOS, array (
						'courseid' => $info ['id'] 
				) );
			}
			return $this->db->delete ( self::T_COURSE, array (
					'id' => $id 
			) );
		}
		return false;
	}
	/**
	 * 获取MBBS课程信息
	 * $id 课程id
	 */
	function get_course_mbbs($id = null) {
		if ($id != null) {
			$course = $this->db->where ( 'id', $id )->get ( self::T_COURSE )->row_array ();
			$mbbs = $this->db->where ( 'courseid', $id )->get ( self::T_COURSE_MBBS )->row_array ();
			// $info = $this->db->where('courseid',$id)->get(self::T_COURSE_INFO)->row_array() ;
			if (! empty ( $mbbs )) {
				return $resutl = array_merge ( $course, $mbbs );
			} else {
				return $course;
			}
		}
	}
	/**
	 * 查询MBBS表中相应字段是否存在
	 * $id 课程id
	 */
	function get_course_mbbs_yes($id = null) {
		if ($id != null) {
			return $mbbs = $this->db->where ( 'courseid', $id )->get ( self::T_COURSE_MBBS )->row_array ();
		}
	}
	/* 写入MBBS课程表 */
	function insert_course_mbbs($data) {
		if (! empty ( $data )) {
			return $this->db->insert ( self::T_COURSE_MBBS, $data );
		}
		return false;
	}
	/**
	 * 更新MBBS课程信息
	 */
	function update_course_mbbs($id = null, $data = array()) {
		if ($id != null && ! empty ( $data ) && is_array ( $data )) {
			return $this->db->update ( self::T_COURSE_MBBS, $data, 'courseid = ' . $id );
		}
		return false;
	}
	/**
	 * 删除学历课程
	 * (int)$id 课程id
	 */
	function delete_course_degree($id = null) {
		if ($id != null) {
			$info = $this->db->where ( 'id', $id )->get ( self::T_COURSE )->row_array ();
			if (! empty ( $info )) {
				$this->db->delete ( self::T_COURSE_DEGREE, array (
						'courseid' => $info ['id'] 
				) );
				$this->db->delete ( self::T_COURSE_INFOS, array (
						'courseid' => $info ['id'] 
				) );
			}
			return $this->db->delete ( self::T_COURSE, array (
					'id' => $id 
			) );
		}
		return false;
	}
	/**
	 * 获取学历课程信息
	 * $id 课程id
	 */
	function get_course_degree($id = null) {
		if ($id != null) {
			$course = $this->db->where ( 'id', $id )->get ( self::T_COURSE )->row_array ();
			$degree = $this->db->where ( 'courseid', $id )->get ( self::T_COURSE_DEGREE )->row_array ();
			// $info = $this->db->where('courseid',$id)->get(self::T_COURSE_INFO)->row_array() ;
			if (! empty ( $course ) && ! empty ( $degree )) {
				return $resutl = array_merge ( $course, $degree );
			} else if (! empty ( $course )) {
				return $course;
			}
		}
	}
	/* 写入学历课程表 */
	function insert_course_degree($data) {
		if (! empty ( $data )) {
			return $this->db->insert ( self::T_COURSE_DEGREE, $data );
		}
		return false;
	}
	/**
	 * 更新学历课程信息
	 */
	function update_course_degree($id = null, $data = array()) {
		if ($id != null && ! empty ( $data ) && is_array ( $data )) {
			return $this->db->update ( self::T_COURSE_DEGREE, $data, 'courseid = ' . $id );
		}
		return false;
	}
	/**
	 * 删除非学历课程
	 * (int)$id 课程id
	 */
	function delete_course_nondegree($id = null) {
		if ($id != null) {
			$info = $this->db->where ( 'id', $id )->get ( self::T_COURSE )->row_array ();
			if (! empty ( $info )) {
				$this->db->delete ( self::T_COURSE_NONDEGREE, array (
						'courseid' => $info ['id'] 
				) );
				$this->db->delete ( self::T_COURSE_INFOS, array (
						'courseid' => $info ['id'] 
				) );
			}
			return $this->db->delete ( self::T_COURSE, array (
					'id' => $id 
			) );
		}
		return false;
	}
	/**
	 * 获取非学历课程信息
	 * $id 课程id
	 */
	function get_course_nondegree($id = null) {
		if ($id != null) {
			$course = $this->db->where ( 'id', $id )->get ( self::T_COURSE )->row_array ();
			$nondegree = $this->db->where ( 'courseid', $id )->get ( self::T_COURSE_NONDEGREE )->row_array ();
			// $info = $this->db->where('courseid',$id)->get(self::T_COURSE_INFO)->row_array() ;
			if (! empty ( $course ) && ! empty ( $nondegree )) {
				return $resutl = array_merge ( $course, $nondegree );
			}
		}
	}
	/* 写入非学历课程表 */
	function insert_course_nondegree($data) {
		if (! empty ( $data )) {
			return $this->db->insert ( self::T_COURSE_NONDEGREE, $data );
		}
		return false;
	}
	/**
	 * 更新课非学历课程信息
	 */
	function update_course_nondegree($id = null, $data = array()) {
		if ($id != null && ! empty ( $data ) && is_array ( $data )) {
			return $this->db->update ( self::T_COURSE_NONDEGREE, $data, 'courseid = ' . $id );
		}
		return false;
	}
	/**
	 * 根据学校id 学历类别
	 */
	function get_course_list($where = null, $type = 1) {
		if ($where != null) {
			if ($type == 1) {
				$info = $this->db->from ( self::T_COURSE . ' a' )->join ( self::T_COURSE_NONDEGREE . ' b', 'a.id = b.courseid', 'left' )->where ( $where )->get ()->result_array ();
			} else {
				$info = $this->db->where ( $where )->get ( self::T_COURSE )->result_array ();
			}
			
			if ($info) {
				return $info;
			}
		}
	}
	/**
	 * 获取数据表字段
	 *
	 * @param string $act
	 *        	标识 course(课程) degree(非学历课程表)等
	 */
	function field_info($act = null) {
		if ($act != null) {
			if ($act == 'course') {
				return $this->db->list_fields ( self::T_COURSE );
			} elseif ($act == 'degree') {
				return $this->db->list_fields ( self::T_COURSE_DEGREE );
			} elseif ($act == 'info') {
				return $this->db->list_fields ( self::T_COURSE_INFOS );
			} elseif ($act == 'mbbs') {
				return $this->db->list_fields ( self::T_COURSE_MBBS );
			} elseif ($act == 'nondegree') {
				return $this->db->list_fields ( self::T_COURSE_NONDEGREE );
			}
		}
	}
}