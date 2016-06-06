<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Printsetting_Model extends CI_Model {
	const T_STUDENT='student';
	const T_PRINT_TEMPLATE='print_template';
	const T_PRINT_FIELDS='print_fields';
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
	function count_template_manage($condition,$parentid) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			$this->db->where('parentid',$parentid);
			return $this->db->from ( self::T_PRINT_TEMPLATE )->count_all_results ();
		}
		return 0;
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get_template_manage($field, $condition,$parentid) {
		if (is_array ( $field ) && ! empty ( $field )) {
			$this->db->select ( str_replace ( " , ", " ", implode ( "`, `", $field ) ) );
			if (is_array ( $condition ) && ! empty ( $condition )) {
				if (! empty ( $condition ['where'] )) {
					$this->db->where ( $condition ['where'] );
				}
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				$this->db->where('parentid',$parentid);
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			return $this->db->get ( self::T_PRINT_TEMPLATE )->result ();
		}
		return array ();
	}
	/**
	 *
	 *获取该模板的字段
	 **/
	function get_template_fields($parentid){
		$this->db->where('print_templateid',$parentid);
		return $this->db->get(self::T_PRINT_FIELDS)->result_array();
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
			$this->db->where('parentid',1);
			return $this->db->from ( self::T_PRINT_TEMPLATE )->count_all_results ();
		}
		return 0;
	}
	/**
	 *
	 *删除模板
	 **/
	function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_PRINT_TEMPLATE, $where );
			return true;
		}
		return false;
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
				$this->db->where('parentid',1);
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			return $this->db->get ( self::T_PRINT_TEMPLATE )->result ();
		}
		return array ();
	}
	/**
	 *
	 *获取模板类型
	 **/
	function get_parent_info(){
		$this->db->where('parentid',1);
		return $this->db->get ( self::T_PRINT_TEMPLATE )->result_array ();
	}
	//获取模板名称
	function get_print_template($mid){
		if(!empty($mid)){
			$this->db->select('name');
			$this->db->where('id',$mid);
			$data=$this->db->get(self::T_PRINT_TEMPLATE)->row_array();
			return $data['name'];
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
			$base = array();
				$base = $this->db->where ($where)->limit(1)->get(self::T_PRINT_TEMPLATE)->row ();
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
				$this->db->insert ( self::T_PRINT_TEMPLATE, $data );
				return $this->db->insert_id ();
			} else {
				$this->db->update ( self::T_PRINT_TEMPLATE, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 * 保存基本模板信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_config_lable($id,$data){
		$arr=array(
			'config_lable'=>$data,
			);
		return $this->db->update ( self::T_PRINT_TEMPLATE, $arr, 'id = ' . $id );
	}
	/**
	 * 获取一条模板信息
	 *
	 */
	function get_template_info($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		return $this->db->get(self::T_PRINT_TEMPLATE)->row_array();
	}
	/**
	 * 保存基本模板图片
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_config_lableimg($id,$data){
		$arr=array(
			'img'=>$data,
			);
		return $this->db->update ( self::T_PRINT_TEMPLATE, $arr, 'id = ' . $id );
	}

	function update_img($id) {
		
		$arr=array(
			'img'=>'',
			);
		return $this->db->update ( self::T_PRINT_TEMPLATE, $arr, 'id = ' . $id );
	}


	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count_fields($condition) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			
			return $this->db->from ( self::T_PRINT_FIELDS )->count_all_results ();
		}
		return 0;
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get_fields($field, $condition) {
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
			return $this->db->get ( self::T_PRINT_FIELDS)->result ();
		}
		return array ();
	}
	/**
	 * 保存字段基本信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_fields($id = null, $data = array()) {
		if (! empty ( $data )) {
			if ($id == null) {
				$data['createtime']=time();
				$this->db->insert ( self::T_PRINT_FIELDS, $data );
				return $this->db->insert_id ();
			} else {
				$this->db->update ( self::T_PRINT_FIELDS, $data, 'id = ' . $id );
			}
		}
	}

	/**
	 *
	 *删除字段
	 **/
	function delete_fields($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_PRINT_FIELDS, $where );
			return true;
		}
		return false;
	}
	/**
	 *
	 *获取一条字段数据
	 **/
	function get_fields_info($id){
		if ($id != null) {
			$base = array();
				$base = $this->db->where ('id',$id)->limit(1)->get(self::T_PRINT_FIELDS)->row ();
			if ($base) {
				return $base;
			}
		return array ();
		}
	}
}