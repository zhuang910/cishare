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
class Apply_form_Model extends CI_Model {
	const T_T = 'templateclass'; // 申请表模版分类
	const T_F = 'formtopic'; // 表单项
	const T_F_I = 'formitem'; // 内容项
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	/**
	 * 获取所有的申请表模版
	 */
	function get_template_info($where = null) {
		if ($where != null) {
			return $lists = $this->db->where ( $where )->order_by ( 'line DESC' )->get ( self::T_T )->result_array ();
		} else {
			return array ();
		}
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
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get_page_count($where = null) {
		$count = $this->db->get_where ( self::T_T, $where )->num_rows ();
		return $count;
	}
	
	/**
	 * 查询项的数量
	 */
	function get_items_count($where = null) {
		$count = $this->db->get_where ( self::T_F, $where )->num_rows ();
		return $count;
	}
	
	/**
	 * 保存模版信息
	 */
	function save_templates($where = null, $data) {
		if ($where != null) {
			return $this->db->update ( self::T_T, $data, $where );
		} else {
			$this->db->insert ( self::T_T, $data );
			return $this->db->insert_id ();
		}
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
	 * 获取一条数据
	 */
	function get_one_formtopic($where = null) {
		$data = array ();
		if ($where != null) {
			$lists = $this->db->where ( $where )->get ( self::T_F )->result_array ();
			if (! empty ( $lists )) {
				return $lists [0];
			}
		}
		return $data;
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
	 * 删除 模版表
	 */
	function del_templateclass($where = null) {
		if ($where != null) {
			return $this->db->delete ( self::T_T, $where );
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
}