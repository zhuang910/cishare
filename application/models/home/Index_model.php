<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @author zyj
 *        
 */
class Index_Model extends CI_Model {
	const T_ARTICLE = 'student_info';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 修改基本信息
	 */
	function basic_update($where = null, $data = null) {
		if ($where !== null && $data != null) {
			return $this->db->update ( self::T_ARTICLE, $data, $where );
		}
	}
	
	/**
	 * 查询数据是否存在
	 */
	function get_info_one($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->get ( self::T_ARTICLE )->result_array ();
		}
	}
	
	/**
	 * 注册添加
	 * 数据
	 */
	function add($data = array()) {
		if (! empty ( $data )) {
			$this->db->insert ( self::T_ARTICLE, $data );
			$userid = $this->db->insert_id ();
			$where = array (
					'id' => $userid 
			);
			return $this->db->where ( $where )->get ( self::T_ARTICLE )->result_array ();
		} else {
			return false;
		}
	}
	
	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count($condition, $programaids = null) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			if ($programaids !== null) {
				$this->db->where ( 'columnid in(' . $programaids . ')' );
			}
			return $this->db->from ( self::T_ARTICLE )->count_all_results ();
		}
		return 0;
	}
	
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_ARTICLE )->row ();
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
				$this->db->insert ( self::T_ARTICLE, $data );
				return $this->db->insert_id ();
			} else {
				$this->db->update ( self::T_ARTICLE, $data, 'id = ' . $id );
			}
		}
	}
	
	/**
	 * 删除
	 */
	function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_ARTICLE, $where );
			return true;
		}
		return false;
	}
}