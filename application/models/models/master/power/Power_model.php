<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 权限管理 管理员管理
 *
 * @author zyj
 *        
 */
class Power_Model extends CI_Model {
	const T_ARTICLE = 'system_group_menu';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 获取权限
	 */
	function get_power($where = null) {
		if ($where != null) {
			$base = $data = array ();
			$base = $this->db->where ( $where )->get ( self::T_ARTICLE )->result_array ();
			if ($base) {
				foreach ( $base as $k => $v ) {
					$data [] = $v ['power'];
				}
				return $data;
			}
			return array ();
		}
	}
}