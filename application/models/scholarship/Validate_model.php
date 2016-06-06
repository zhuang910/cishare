<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 验证模型
 *
 * @author junjiezhang
 *        
 */
class Validate_Model extends CI_Model {
	const T_COURSE = 'scholarship_info';
	
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
		$is = $this->db->select ( 'id' )->where ( 'id = ' . $courseid . ' AND apply_state = 2 AND state = 1' )->limit ( 1 )->get ( self::T_COURSE )->row ();
		
		if (! empty ( $is )) {
			return true;
		} else {
			return false;
		}
	}
}