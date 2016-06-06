<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @author zyj
 *        
 */
class About_Model extends CI_Model {
	const T_ARTICLE = 'pages_info';
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
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_ARTICLE )->result_array ();
			if ($base) {
				return $base [0];
			}
			return array ();
		}
	}
	
	/**
	 * 获取多条数据
	 *
	 * @param number $id        	
	 */
	function get_news($where = null, $limit = null) {
		if ($where != null && $limit != null) {
			$base = array ();
			$base = $this->db->where ( $where )->order_by ( 'orderby DESC' )->limit ( $limit )->get ( self::T_ARTICLE )->result_array ();
			if ($base) {
				return $base;
			}
			return array ();
		}
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
	function getall($where = 'id > 0', $select = '*', $offset = '0', $size = '10', $orderby = 'id DESC') {
		$res = array ();
		$query = $this->db->select ( $select )->order_by ( $orderby )->get_where ( self::T_ARTICLE, $where, $size, $offset );
		if ($query->num_rows () > 0) {
			$res = $query->result_array ();
		}
		return $res;
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