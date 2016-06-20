<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 分类管理
 *
 * @author zhuangqianlin
 *        
 */
class Category_Model extends CI_Model {
	const T_CATEGORY = 'category';
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
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
				$this->db->insert ( self::T_CATEGORY, $data );
				return $this->db->insert_id ();
			} else {
				$this->db->update ( self::T_CATEGORY, $data, 'cat_id = ' . $id );
			}
		}
	}

	/**
	 * 获取一条
	 *
	 * @param number $id
	 */
	function get_one($where = null) {
		if ($where != null) {
			$base = array();
			$base = $this->db->where ($where)->limit(1)->get(self::T_CATEGORY)->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}

	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count($where=null) {
		if (! empty ( $where )) {
			$this->db->where ( $where );
		}
		return $this->db->from ( self::T_CATEGORY )->count_all_results ();
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get($where = null, $limit = 0, $offset = 0, $orderby = 'cat_id desc') {
		if (! empty ( $where )) {
			$this->db->where ( $where, NULL, false );
		}
		if ($limit) {
			$this->db->limit ( $limit, $offset );
		}
	
		$data= $this->db->order_by ( $orderby )->get ( self::T_CATEGORY )->result ();
		if(!empty($data)){
			return $data;
		}else{
			return array();
		}
	}

	/**
	 * [delete 删除]
	 * @param  [type] $where [description]
	 * @return [type]        [description]
	 */
	function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_CATEGORY, $where);
			return true;
		}
		return false;
	}

}