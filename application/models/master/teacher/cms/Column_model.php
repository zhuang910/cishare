<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 栏目管理
 *
 * @author zyj
 *        
 */
class Column_Model extends CI_Model {
	const T_PROGRAMA = 'programa_info';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 执行SQL语句返回结果
	 */
	function getquery($sql) {
		$query = $this->db->query ( $sql );
		$res = array ();
		if ($query->num_rows () > 0) {
			$res = $query->result_array ();
		}
		return $res;
	}
	/**
	 * 获取关联菜单
	 *
	 * @return multitype:array
	 */
	function get() {
		$list = $this->db->order_by ( 'orderby ASC' )->get ( self::T_PROGRAMA )->result ();
		$data = array ();
		foreach ( $list as $key => $val ) {
			$data [$val->programaid] = ( array ) $val;
		}
		return $data;
	}
	
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($id = null) {
		if ($id != null) {
			return $this->db->get_where ( self::T_PROGRAMA, 'programaid = ' . $id, 1, 0 )->row ();
		}
	}
	/**
	 * 删除
	 *
	 * @param number $menuid        	
	 */
	function delete($id = 0) {
		if ($id) {
			return $this->db->delete ( self::T_PROGRAMA, 'programaid = ' . $id );
		}
	}
	
	/**
	 * 排序
	 *
	 * @param array $orderby        	
	 */
	function orderby($orderby = array()) {
		if (! empty ( $orderby ) && is_array ( $orderby )) {
			foreach ( $orderby as $order => $menuid ) {
				$this->db->update ( self::T_PROGRAMA, array (
						'orderby' => $order 
				), 'programaid = ' . $menuid );
			}
			return true;
		}
		return false;
	}
	
	/**
	 * 保存
	 */
	function save($id = null, $data) {
		if (! empty ( $id )) {
			return $this->db->update ( self::T_PROGRAMA, $data, 'programaid = ' . $id );
		} else {
			return $this->db->insert ( self::T_PROGRAMA, $data );
		}
	}
	
	/**
	 * 获取字段
	 */
	function field() {
		return $this->db->list_fields ( self::T_PROGRAMA );
	}
	
	/**
	 * 验证唯一标识是否重复
	 *
	 * @param string $identify        	
	 */
	function check_identify($identify = null) {
		if (! empty ( $identify )) {
			$result = $this->db->from ( self::T_PROGRAMA )->where ( 'identify = \'' . $identify . '\'' )->count_all_results ();
			if ($result > 0) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * 获取最高父栏目ID
	 *
	 * @param number $id        	
	 */
	private function _get_parent($id) {
		$programa = CF ( 'programa' );
		if (isset ( $programa [$id] ) && $programa [$id] ['parentid'] != 0) {
			return $this->_get_parent ( $programa [$id] ['parentid'] );
		}
		if (isset ( $programa [$id] ) && $programa [$id] ['parentid'] == 0) {
			return $programa [$id] ['programaid'];
		}
	}
}