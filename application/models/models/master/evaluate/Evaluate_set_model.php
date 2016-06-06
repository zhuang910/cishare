<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Evaluate_set_Model extends CI_Model {
	const T_E_CLASS = 'evaluate_class';

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
			
			return $this->db->from ( self::T_E_CLASS )->count_all_results ();
		}
		return 0;
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get($field, $condition, $programaids = null) {
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
			return $this->db->get ( self::T_E_CLASS )->result ();
		}
		return array ();
	}
		function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_E_CLASS, $where);
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
			$base = array();
				$base = $this->db->where ($where)->limit(1)->get(self::T_E_CLASS)->row ();
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
				$this->db->insert ( self::T_E_CLASS, $data );
				return $this->db->insert_id ();
			} else {
				$this->db->update ( self::T_E_CLASS, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 * [set_evaluate_time 设置时间]
	 */
	function set_evaluate_time($data){
		if(!empty($data)){
			$arr['starttime']=strtotime($data['starttime']);
			$arr['endtime']=strtotime($data['endtime']);
			$json_ids=$data['ids'];
			unset($data['ids']);
			$ids= json_decode($json_ids);
			if(!empty($ids)){
				foreach ($ids as $k => $v) {
					$this->db->update(self::T_E_CLASS,$arr,'id='.$v);
				}
			}
		}
	}
}