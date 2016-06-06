<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @name 		申请管理
 * @package 	
 * @author 		Laravel
 * @copyright   
 **/
class Edit_app_info_model extends CI_Model {
	const T_APP		  = 'apply_info';		
	const T_USER	  = 'student_info';		
	const T_COURSE    = 'course_lists';
	const T_USER_HISTORY = 'apply_history';
	const T_OFFER  ='app_getoffer';
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		
	}

	/**
	 * 执行更新申请用户信息
	 * @param int $id 更新的申请id
	 */
	function update_app_info($id = null, $data = array()) {
		if ($id != null && ! empty ( $data ) && is_array ( $data )) {
			
			$this->db->select('userid');
			$userid = $this->db->get_where ( self::T_APP, 'id = ' . $id, 1, 0 )->row ();
		    
			return $this->db->update ( self::T_USER, $data, 'id = ' . $userid->userid );
		}
		return false;
	}
	
	/**
	 * 执行更新申请用户信息
	 * @param int $id 更新的申请id
	 */
	function update_app_remark_info($id = null, $data = array()) {
		if ($id != null && ! empty ( $data ) && is_array ( $data )) {
	
			return $this->db->update ( self::T_APP, $data, 'id = ' . $id );
		}
		return false;
	}
	
	/**
	 * 执行修改课程基本信息
	 * @param int $id
	 */
	
	function update_app_course_info($id = null, $data = array()){
         
         if ($id != null && ! empty ( $data ) && is_array ( $data )) {
			//查询出课程id
			$this->db->select('courseid');
			$courseid = $this->db->get_where ( self::T_APP, 'id = ' . $id, 1, 0 )->row ();
		    
			return $this->db->update ( self::T_COURSE, $data, 'id = ' . $courseid->courseid );
		}
		return false;
	}
	
	/**
	 * 
	 * 执行结束状态修改操作
	 */
	
	function  app_over_update($id = null, $data = array()){
		if ($id != null && ! empty ( $data ) && is_array ( $data )) {
		
			return $this->db->update ( self::T_APP, $data, 'id = ' . $id);
		}
		return false;
	}
	
	/**
	 * 查看用户操作记录
	 */
	
	function  cat_user_operate($id = NULL){
		if ($id !=null) {
			$this->db->select('*');
			$lists = $this->db->get_where ( self::T_USER_HISTORY, 'appid = ' . $id)->result ();
			return  $lists;
		}
		return  false;
	}
	
	
	/**
	 * 查看用户确认地址信息
	 */
	
	function  cat_user_address_info($id = NULL){
		if ($id !=null) {
			$this->db->select('*');
			$lists = $this->db->get_where ( self::T_OFFER, 'appid = ' . $id, 1, 0 )->row ();
			return  $lists;
		}
		return  false;
	}
}