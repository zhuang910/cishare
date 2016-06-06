<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Faculty_Model extends CI_Model {
	const T_FACULTY = 'faculty';
	const T_ADMIN='admin_info';
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
			
			return $this->db->from ( self::T_FACULTY )->count_all_results ();
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
			return $this->db->get ( self::T_FACULTY )->result ();
		}
		return array ();
	}
		function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_FACULTY, $where);
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
				$base = $this->db->where ($where)->limit(1)->get(self::T_FACULTY)->row ();
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
				$this->db->insert ( self::T_FACULTY, $data );
				return $this->db->insert_id ();
			} else {
				$this->db->update ( self::T_FACULTY, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 *报存管理员表
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_admin($id = null, $data = array()) {
		if (! empty ( $data )) {
				$inser_arr['groupid']=9;
				$inser_arr['email']=$data['email'];
				$inser_arr['mobile']=$data['phone'];
				$inser_arr['tel']=$data['telephone'];
				$inser_arr['password']=$data['password'];
				$inser_arr['salt']=$data['salt'];
				$inser_arr['username']=$data['username'];
				$inser_arr['nikename']=$data['name'];
			if ($id == null) {
				$inser_arr['createip']=get_client_ip ();
				$inser_arr['createtime']=time();
				$this->db->insert ( self::T_ADMIN, $inser_arr );
				return $this->db->insert_id ();
			} else {
				return $this->db->update ( self::T_ADMIN, $inser_arr, 'id = ' . $id );
			}
		}
	}
	/**
	 * [check_admin_username 检查admin表里用户名是否重复]
	 * @return [type] [description]
	 */
	function check_admin_username($username){
		if(!empty($username)){
			$this->db->select('count(*) as num');
			$this->db->where('username',$username);
			$data=$this->db->get(self::T_ADMIN)->row_array();
			return $data['num'];
		}
		return 1;
	}
	/**
	 * [check_admin_username 检查admin表里用户名是否重复]
	 * @return [type] [description]
	 */
	function check_admin_email($email){
		if(!empty($email)){
			$this->db->select('count(*) as num');
			$this->db->where('email',$email);
			$data=$this->db->get(self::T_ADMIN)->row_array();
			return $data['num'];
		}
		return 1;
	}
}