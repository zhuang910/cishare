<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 权限管理 管理员管理
 *
 * @author zhuangqianlin
 *        
 */
class Admin_Model extends CI_Model {
	const T_ARTICLE = 'ci_admin_info';
	const T_G = 'ci_system_group';
	const T_M = 'ci_system_group_menu';
	const T_TEACHER='teacher';
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

            $this->db->where('groupid != 8');

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

                $this->db->where('groupid != 8');
				
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
	function save_teacher($id = null, $data = array()) {
		$data['name']=$data['nikename'];
		$data['phone']=$data['mobile'];
		unset($data['nikename']);
		unset($data['groupid']);
		unset($data['createip']);
		unset($data['mobile']);
		$is=$this->get_is_userid($id);
		if (! empty ( $data )) {
			if (empty($is)) {
				$data['userid']=$id;
				$this->db->insert ( self::T_TEACHER, $data );
				return $this->db->insert_id ();
			} else {
				return $this->db->update ( self::T_TEACHER, $data, 'userid = ' . $id );
			}
		}
	}
	/** 
	 *老师表里是否有userid
	 **/
	function get_is_userid($id){
		$this->db->where('userid',$id);
		return $this->db->get(self::T_TEACHER)->result_array();
	}
	/**
	 *删除关联老师的账号
	 **/
	function admin_del_teacher($userid){
		if ($userid != null) {
			$this->db->delete ( self::T_TEACHER, 'userid='.$userid );
			return true;
		}
		return false;
	}
	/**
	 *查看数据是否是老师权限组的
	 **/
	function admin_is_teacher($id){
		$this->db->select('groupid');
		$this->db->where('id',$id);
		$data=$this->db->get(self::T_ARTICLE)->row_array();
		if($data['groupid']==4){
			return 1;
		}else{
			return 0;
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
	 * 删除
	 */
	function delete_teacher($userid) {
		if ($userid != null) {
			$this->db->delete ( self::T_TEACHER, 'userid='.$userid );
			return true;
		}
		return false;
	}
}