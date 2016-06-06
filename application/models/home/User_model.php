<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @author zyj
 *        
 */
class User_Model extends CI_Model {
	const T_ARTICLE = 'user_info';
	const T_ARTICLE_EXTEND = 'user_extend';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 修改基本信息
	 */
	function basic_update($where = null, $data = null) {
		if ($where !== null && $data != null) {
			return $this->db->update ( self::T_ARTICLE, $data, $where );
		}
	}
	
	/**
	 * 修改扩展信息
	 */
	function extend_update($where = null, $data = null) {
		if ($where !== null && $data != null) {
			return $this->db->update ( self::T_ARTICLE_EXTEND, $data, $where );
		}
	}
	
	/**
	 * 查询数据是否存在
	 */
	function get_info_one($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->get ( self::T_ARTICLE )->result_array ();
		}
	}
	
	/**
	 *获取扩展信息
	 */
	function get_info_extend($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->get ( self::T_ARTICLE_EXTEND )->result_array ();
		}
	}
	
	/**
	 * 注册添加
	 * 数据
	 */
	function add($data = array()) {
		if (! empty ( $data )) {
			$this->db->insert ( self::T_ARTICLE, $data );
			$userid = $this->db->insert_id ();
			$where = array (
					'id' => $userid 
			);
			return $this->db->where ( $where )->get ( self::T_ARTICLE )->result_array ();
		} else {
			return false;
		}
	}
	
	/**
	 * 写入扩展信息表
	 */
	function save_extend($data = array()) {
		if (! empty ( $data )) {
			$this->db->insert ( self::T_ARTICLE_EXTEND, $data );
		}
	}
	
	/**
	 * 首页获取最新的3条
	 */
	function index_get($where = null) {
		if ($where != null) {
			return $this->db->select ( '*' )->where ( $where )->order_by ( 'orderby DESC,createtime DESC' )->group_by ( 'name' )->limit ( 50 )->get ( self::T_ARTICLE )->result_array ();
		}
	}
	
	/**
	 * 前台获取数据
	 */
	function get_lists($where = null, $limit = 0, $offset = 0) {
		if ($where != null) {
			if ($limit != 0) {
				$this->db->limit ( $limit, $offset );
			}
			return $this->db->select ( '*' )->where ( $where )->order_by ( 'orderby DESC,createtime DESC' )->get ( self::T_ARTICLE )->result_array ();
		}
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
				$this->db->update ( self::T_ARTICLE, $data, 'id = ' . $id );
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
			), 'articleid = ' . $id );
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
}