<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Activity_Model extends CI_Model {
	const T_A = 'activity_base';
	const T_A_E = 'activity_content';
	const T_A_U = 'activity_user';
	
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
	function count($where = null) {
		if (! empty ( $where )) {
			$this->db->where ( $where );
		}
		return $this->db->from ( self::T_A )->count_all_results ();
	}
	
	/**
	 * 统计条数
	 *
	 * @param array $field
	 * @param array $condition
	 */
	function countuser($where = null) {
		if (! empty ( $where )) {
			$this->db->where ( $where );
		}
		return $this->db->from ( self::T_A_U )->count_all_results ();
	}
	
	/**
	 * 修改基本信息
	 */
	function update_base($where = null, $data = null) {
		if ($where !== null && $data != null) {
			return $this->db->update ( self::T_A, $data, $where );
		}
	}
	
	function get_one_base($where = null) {
		$data = array ();
		if ($where != null) {
			$datas = $this->db->where ( $where )->get ( self::T_A )->result_array ();
			if (! empty ( $datas )) {
				$data = $datas [0];
			}
		}
		return $data;
	}
	
	/**
	 * 查询数据是否存在
	 */
	function get_one_content($where = null) {
		$data = array ();
		if ($where != null) {
			$datas = $this->db->where ( $where )->get ( self::T_A_E )->result_array ();
			if (! empty ( $datas )) {
				$data = $datas [0];
			}
		}
		return $data;
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get($where = null, $limit = 0, $offset = 0, $orderby = 'id desc') {
		if (! empty ( $where )) {
			$this->db->where ( $where, NULL, false );
		}
		if ($limit) {
			$this->db->limit ( $limit, $offset );
		}
		
		$data = $this->db->order_by ( $orderby )->get ( self::T_A )->result ();
		if (! empty ( $data )) {
			return $data;
		} else {
			return array ();
		}
	}
	
	/**
	 *
	 * @param string $where
	 * @param string $data
	 * @return boolean
	 */
	function update_activity_user($where = null, $data = null) {
		if ($where !== null && $data != null) {
			return $this->db->update ( self::T_A_U, $data, $where );
		} else {
			return false;
		}
	}
	
	/**
	 * 获取列表数据
	 *
	 * @param array $field
	 * @param array $condition
	 */
	function getuser($where = null, $limit = 0, $offset = 0, $orderby = 'id desc') {
		if (! empty ( $where )) {
			$this->db->where ( $where, NULL, false );
		}
		if ($limit) {
			$this->db->limit ( $limit, $offset );
		}
	
		$data = $this->db->order_by ( $orderby )->get ( self::T_A_U )->result ();
		if (! empty ( $data )) {
			return $data;
		} else {
			return array ();
		}
	}
	




	
}