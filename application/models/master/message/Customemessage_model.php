<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Customemessage_Model extends CI_Model {

	const T_MESSAGE_LOG='message_log';

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
			
			return $this->db->from ( self::T_MESSAGE_LOG )->count_all_results ();
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
			return $this->db->get ( self::T_MESSAGE_LOG )->result ();
		}
		return array ();
	}
	/**
	 * 保存基本信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save( $data = array()) {
		if (! empty ( $data )) {
			
				$this->db->insert ( self::T_MESSAGE_LOG, $data );
				return $this->db->insert_id ();
		
		}
	}
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($id) {
		return $this->db->where ('id',$id)->limit(1)->get(self::T_MESSAGE_LOG)->row ();
	}
}