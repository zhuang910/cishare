<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Index extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/core/';
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		// 获取权限组
		$authorityAll = $this->db->select ( '*' )->get_where ( 'ci_system_group', 'id > 0' )->result_array ();
		if ($authorityAll) {
			foreach ( $authorityAll as $k => $v ) {
				$authority [$v ['id']] = $v ['title'];
			}
		}
		

		$dataadmin = $this->db->select ( '*' )->get_where ( 'ci_admin_info', 'id = ' . $_SESSION ['master_user_info']->id )->row ();
		$this->_view ( 'index_index', array (
				'authority' => ! empty ( $authority ) ? $authority : array (),
				'dataadmin' => ! empty ( $dataadmin ) ? $dataadmin : array ()
		) );
	}
	
	/**
	 * 查申请 个数
	 */
	function get_apply_count() {
		$countapplywhere = 'state = 1';
		$countapply = 0;
		$countapply_All = $this->db->select ( '*' )->get_where ( 'apply_info', $countapplywhere )->result_array ();
		if ($countapply_All) {
			$countapply = count ( $countapply_All );
		}
		ajaxReturn ( $countapply, '', 1 );
	}
	//获取所有有班级的学生
	function get_stuid(){
		$this->db->select('id');
		$this->db->where('squadid <>','');
		return $this->db->get('student')->result_array();
	}
	//计算该学生的考勤数
	function get_stu_num($id){
		$this->db->select('count(*) as num');
		$this->db->where('studentid',$id);
		$data=$this->db->get('checking')->row_array();
		return $data['num'];
	}
	//获取所有有签证到期时间的学生
	function qian_student_id(){
		$this->db->select('id');
		$this->db->where('visaendtime <>',0);
		return $this->db->get('student')->result_array();
	}
	//获取学生的签证到期时间
	function get_stu_due_time($id){
		$this->db->select('visaendtime');
		$this->db->where('id',$id);
		$data=$this->db->get('student')->row_array();
		return $data['visaendtime'];
	}
}