<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 名师团队管理
 *
 * @author zyj
 *        
 */
class Teacher_team_Model extends CI_Model {
	const T_ARTICLE = 'teacher_team';
	const T_J = 'teacher_jpkc';
	const T_JX = 'teacher_jxsb';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
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
	 * 前台获取数据
	 */
	function get_all($where = null, $limit = 0, $offset = 0) {
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
	 * 保存 学生评价信息
	 */
	function save_evaluate($data = null) {
		if ($data != null) {
			$this->db->insert ( 'teacher_evaluate', $data );
			return $this->db->insert_id ();
		}
		return false;
	}
	
	/**
	 * 获取两条数据
	 */
	function get_evaluate($where = null, $offset = 0, $size = 2) {
		if ($where != null) {
			$data = $this->db->select ( '*' )->order_by ( 'createtime DESC' )->get_where ( 'teacher_evaluate', $where, $size, $offset )->result_array ();
			if ($data) {
				return $data;
			} else {
				return array ();
			}
		}
		return array ();
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
	
	/**
	 * 统计数量(总页数、总条数)
	 * @table 表名
	 * @where 条件
	 * @size 查询多少条
	 */
	function counts($where, $size = 2) {
		$count = $this->db->get_where ( 'teacher_evaluate', $where )->num_rows ();
		$data = array (
				'allcount' => $count,
				'pagecount' => ceil ( $count / $size ) 
		);
		return $data;
	}
	
	/**
	 * 得到多条信息 默认降序
	 * @table 表名
	 * @where 条件
	 * @select 查询字段
	 * @offset 从第几条开始查询
	 * @size 查询多少条
	 * @orderby 排序
	 */
	function getall($where = 'id > 0', $select = '*', $offset = '0', $size = '2', $orderby = 'createtime DESC') {
		$res = array ();
		$query = $this->db->select ( $select )->order_by ( $orderby )->get_where ( 'teacher_evaluate', $where, $size, $offset );
		if ($query->num_rows () > 0) {
			$res = $query->result_array ();
		}
		return $res;
	}
	
	/**
	 * 查询 精品课程
	 */
	function get_jpkc($teamid = null) {
		$data = array ();
		if ($teamid != null) {
			$data = $this->db->select ( '*' )->order_by ( 'orderby DESC' )->get_where ( self::T_J, 'teamid =' . $teamid )->result_array ();
		}
		return $data;
	}
	
	/**
	 * 查询 精品课程
	 */
	function get_jxsb($teamid = null) {
		$data = array ();
		if ($teamid != null) {
			$data = $this->db->select ( '*' )->order_by ( 'orderby DESC' )->get_where ( self::T_JX, 'teamid =' . $teamid )->result_array ();
		}
		return $data;
	}
}