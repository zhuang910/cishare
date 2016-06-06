<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 模型管理
 *
 * @author zyj
 *        
 */
class Module_model extends CI_Model {
	const T_MODULE = 'module_info';

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
			return $this->db->from ( self::T_MODULE )->count_all_results ();
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
			$this->db->select ( str_replace ( " , ", " ", implode ( "`, `", $field ) ) );
			if (is_array ( $condition ) && ! empty ( $condition )) {
				if (! empty ( $condition ['where'] )) {
					$this->db->where ( $condition ['where'] );
				}
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			return $this->db->get ( self::T_MODULE )->result ();
		}
		return array ();
	}
	
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($where = null) {
		if ($where != null) {
			$base = array();
				$base = $this->db->where ($where)->limit(1)->get(self::T_MODULE)->row ();
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
				$data['createtime']=time();
				$data['lasttime']=time();
				$this->db->insert ( self::T_MODULE, $data );
				return $this->db->insert_id ();
			} else {
				$data['lasttime']=time();
				return $this->db->update ( self::T_MODULE, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 * 删除
	 */
	function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_MODULE, $where);
			return true;
		}
		return false;
	}
}