<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 验证模型
 *
 * @author junjiezhang
 *        
 */
class Validate_Model extends CI_Model {
	const T_COURSE = 'major';
	
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
	
	}
	
	/**
	 * 验证是否可申请
	 */
	function isapply($courseid = 0) {
//        var_dump($courseid);exit;
		$is = $this->db->select ( 'id' )->where ( 'id = ' . $courseid . ' AND endtime > unix_timestamp() AND state = 1' )->limit ( 1 )->get ( self::T_COURSE )->row ();
		if (! empty ( $is )) {
			return true;
		} else {
			return false;
		}
	}
}