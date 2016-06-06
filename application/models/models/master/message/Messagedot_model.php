<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Messagedot_Model extends CI_Model {
	const T_FACULTY = 'faculty';
	const T_MESSAGE_DOT='message_dot';

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
			
			return $this->db->from ( self::T_MESSAGE_DOT )->count_all_results ();
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
			return $this->db->get ( self::T_MESSAGE_DOT )->result ();
		}
		return array ();
	}
		function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_MESSAGE_DOT, $where);
			return true;
		}
		return false;
	}

	/**
	 * 获取配置一条 根据ID
	 *
	 * @param int $id
	 */
	function get_one($id = 0)
	{
		if ($id !== 0) {
			return $this->db->get_where(self::T_MESSAGE_DOT, 'id = ' . $id, 1, 0)->row();
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
				$this->db->insert ( self::T_MESSAGE_DOT, $data );
				return $this->db->insert_id ();
			} else {
				$this->db->update ( self::T_MESSAGE_DOT, $data, 'id = ' . $id );
			}
		}
	}
	
}