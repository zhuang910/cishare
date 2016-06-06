<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Rebuild_Model extends CI_Model {
	const T_STUDENT_REBUILD='student_rebuild';//重修费用表
	const T_MAJOR='major';
	const T_SQUAD='squad';
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
			
			return $this->db->get ( self::T_STUDENT_REBUILD )->result ();
		}
		return array ();
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
			
			return $this->db->from ( self::T_STUDENT_REBUILD )->count_all_results ();
		}
		return 0;
	}
	
	/**
	 * 删除一条
	 *
	 * @param
	 *        	$id
	 */
	function delete($where) {
		if ($where != null) {
			if ($this->db->delete ( self::T_STUDENT_REBUILD, $where)) {
				return true;
			}
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
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_STUDENT_REBUILD )->row ();
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
				if ($this->db->insert ( self::T_STUDENT_REBUILD, $data )) {
					
					return $this->db->insert_id ();
				}
			} else {
				$this->db->update ( self::T_STUDENT_REBUILD, $data, 'id = ' . $id );
			}
		}
	}
	
	/**
	 * 获得专业名字       	
	 */
	function get_majorname($id){
		if(!empty($id)){
			$data= $this->db->where('id',$id)->get ( self::T_MAJOR)->row_array ();
			return $data['name'];
		}
		return '';
	}
	/**
	 * 获得班级名字       	
	 */
	function get_squadname($id){
		if(empty($id)){
			return '';
		}else{
			$data= $this->db->where('id',$id)->get ( self::T_SQUAD)->row_array ();
			return $data['name'];
		}
		
	}
	/**
	 * [get_remark 获取备注]
	 * @return [type] [description]
	 */
	function get_remark($id){
		if(!empty($id)){
			$this->db->select('remark');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_STUDENT_REBUILD)->row_array();
			return $data['remark'];
		}
	}
	/**
	 * [get_major_info_one 获取一条学校信息]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function get_major_info_one($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			return $this->db->get ( self::T_MAJOR)->row_array ();
		}
		return array();
	}
}