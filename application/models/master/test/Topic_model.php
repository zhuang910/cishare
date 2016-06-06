<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Topic_Model extends CI_Model {
	const T_PAPER_ITEM='paper_item';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}

	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get_paper_item($field, $condition) {
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
			return $this->db->get ( self::T_PAPER_ITEM )->result ();
		}
		return array ();
	}
	
	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count_paper_item($condition) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			return $this->db->from ( self::T_PAPER_ITEM )->count_all_results ();
		}
		return 0;
	}
	/**
	 * 保存试题项基本信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_paper_item($id = null, $data = array()) {
		if (! empty ( $data )) {
			if ($id == null) {
				if ($this->db->insert ( self::T_PAPER_ITEM, $data )) {
					
					return $this->db->insert_id ();
				}
			} else {
				$this->db->update ( self::T_PAPER_ITEM, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one_paper_item($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_PAPER_ITEM )->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	/**
	 * 删除一条
	 *
	 * @param
	 *        	$id
	 */
	function delete($where) {
		if (!empty($where)) {
			if ($this->db->delete ( self::T_PAPER_ITEM, $where )) {
				return true;
			}
		}
		return false;
	}
}