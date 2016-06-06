<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Look_evaluate_model extends CI_Model {
	const T_STUDENT = 'student';
	const T_MAJOR = 'major';
	const T_SQUAD ='squad';
	const T_COURSE='course';
	const T_TEACHER='teacher';
	const T_E_STUDENT = 'evaluate_student';
	const T_E_STU_FINISH='evaluate_student_finish';

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}


	/**
	 * [get_all_teacher 获取所有的老师]
	 * @return [type] [description]
	 */
	function get_all_teacher(){
		return $this->db->get(self::T_TEACHER)->result_array();
	}
	/**
	 * [get_all_major 获取所有的专业]
	 * @return [type] [description]
	 */
	function get_all_major(){
		$this->db->where('state',1);
		return $this->db->get(self::T_MAJOR)->result_array();
	}
	/**
	 * [get_all_major 获取所有的专业]
	 * @return [type] [description]
	 */
	function get_all_course(){
		$this->db->where('state',1);
		return $this->db->get(self::T_COURSE)->result_array();
	}
	/**
	 * [get_all_major 获取所有的专业]
	 * @return [type] [description]
	 */
	function get_all_squad(){
		$this->db->where('state',1);
		return $this->db->get(self::T_SQUAD)->result_array();
	}
}