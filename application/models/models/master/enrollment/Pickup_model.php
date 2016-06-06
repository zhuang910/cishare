<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 权限管理 教师管理
 *
 * @author zyj
 *        
 */
class Pickup_Model extends CI_Model {
	const T_ARTICLE = 'pickup_info';
	const T_C = 'credentials';
	
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
	 * [pay_change_state 现场缴费修改状态]
	 * @param  [array] $data [修改数据]
	 * @return [type]       [Boolean]
	 */
	function pay_change_state($data){
		$arr=array();
		if(!empty($data)){
			$arr['paytime']=time();
			$arr['danwei']=$data['currency'];
			$arr['registeration_fee']=$data['amount'];
			$arr['paystate']=1;
			$this->db->update ( self::T_ARTICLE, $arr, 'id = ' . $data['id']);
			return true;
		}
		return flase;
	}
	/**
	 * [insert_pay_record 插入缴费记录]
	 * @param  [array] $data [插入数据]
	 * @return [type]       [Boolean]
	 */
	function insert_pay_record($data){
		if(!empty($data)){
			unset($data['id']);
			$data['state']=1;
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert ( self::T_C, $data);
			return true;
		}
		return flase;
	}	
}