<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 权限管理 中介管理
 *
 * @author zyj
 *        
 */
class Agency_Model extends CI_Model {
	const T_ARTICLE = 'agency_info';
	const T_ADMIN='admin_info';
	const T_ACC='apply_info';
	
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
				
				if ($programaids !== null) {
					$this->db->where ( 'columnid in(' . $programaids . ')' );
				}
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			return $this->db->get ( self::T_ARTICLE )->result ();
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
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_ARTICLE )->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	
	/**
	 * 获取群组的名称
	 */
	function get_group($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->get ( self::T_G )->result_array ();
			
			if ($base) {
				foreach ( $base as $k => $v ) {
					$data [$v ['id']] = $v ['title'];
				}
				
				return $data;
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
				return $this->db->update ( self::T_ARTICLE, $data, 'id = ' . $id );
			}
		}
	}
	
	/**
	 * 保存基本信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_content($id = null, $data = array()) {
		if (! empty ( $data )) {
			// 验证内容表是否存在$id
			$is = $this->db->get_where ( self::T_ARTICLE_CONTENT, 'articleid = ' . $id, 1, 0 )->row ();
			if (! empty ( $is )) {
				return $this->db->update ( self::T_ARTICLE_CONTENT, $data, 'articleid = ' . $id );
			} else {
				$data ['articleid'] = $id;
				return $this->db->insert ( self::T_ARTICLE_CONTENT, $data );
			}
		}
	}
	
	/**
	 * 审核文章
	 *
	 * @param number $id        	
	 * @param number $state        	
	 */
	function save_audit($id = null, $state = 1) {
		if ($id !== null) {
			return $this->db->update ( self::T_ARTICLE, array (
					'state' => $state 
			), 'id = ' . $id );
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

	/**
	 *报存管理员表
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_admin($id = null, $data = array()) {
		if (! empty ( $data )) {
			unset($data['nationality']);
			unset($data['fax']);
			unset($data['postcode']);
			unset($data['checkstate']);
			unset($data['company']);
			if ($id == null) {
				$data['groupid']=8;
				$data['createip']=get_client_ip ();
				$this->db->insert ( self::T_ADMIN, $data );
				return $this->db->insert_id ();
			} else {
				return $this->db->update ( self::T_ADMIN, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 * [get_userid 获取userid]
	 * @return [type] [description]
	 */
	function get_userid($id){
		if(!empty($id)){
			$this->db->select('userid');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_ARTICLE)->row_array();
			if(!empty($data['userid'])){
				return $data['userid'];
			}
		}
		return 0;
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
	/**
	 * [get_student_num 获取中介的人数]
	 * @return [type] [description]
	 */
	function get_student_num($agency_id){
		if(!empty($agency_id)){
			$this->db->select('count(*) as num');
			$this->db->where('is_agency',1);
			$this->db->where('agency_id',$agency_id);
			$data=$this->db->get(self::T_ACC)->row_array();
			return $data['num'];
		}
		return 0;
	}
}