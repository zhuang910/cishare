<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Pages_Model extends CI_Model {
	const PPT = 'pages_info';
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 保存
	 */
	function save($id = null, $data) {
		if (! empty ( $id )) {
			return $this->db->update ( self::PPT, $data, 'columnid = ' . $id );
		} else {
			$this->db->insert ( self::PPT, $data );
			return $this->db->insert_id ();
		}
	}
	
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($id = null) {
		if ($id != null) {
			return $this->db->get_where ( self::PPT, 'columnid = ' . $id, 1, 0 )->row ();
		}
	}
	
	/**
	 * 删除
	 *
	 * @param number $menuid        	
	 */
	function delete($id = 0) {
		if ($id) {
			return $this->db->delete ( self::PPT, 'columnid = ' . $id );
		}
	}
}