<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/*
*现金收费管理
*
*/
class Cash_Model extends CI_Model{
	const CASH = 'cash';
	const T_STUDENT = 'student_info';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	//查询数据总数
	function count($where = null){
    	$this->db->select('cash.*,student_info.chname,student_info.enname,student_info.passport');
		if($where !== null){
			$this->db->where($where);
    	}
    	$this->db->join('student_info','student_info.id=cash.userid');
    	return $this->db->count_all_results(self::CASH);
	}


//获得数据
    function get($where = null,$limit = 25,$offset = 0,$orderby = null){
    	$this->db->select('cash.*,student_info.chname,student_info.enname,student_info.passport');
    	if($where != null){
			$this->db->where($where);
    	}
    	if($limit){
			$this->db->limit($limit,$offset);
    	}

    	if($orderby !== null){
    		$this->db->order_by($orderby);
    	}
    	$this->db->join('student_info','student_info.id=cash.userid');
    	return $this->db->get(self::CASH)->result();
    }
	/**
	 * [get_student_remark 获取学生备注]
	 * @return [type] [description]
	 */
	function get_student_remark($id){
		if(!empty($id)){
			$this->db->select('remark');
			$this->db->where('id',$id);
			$data=$this->db->get(self::CASH)->row_array();
			return $data['remark'];
		}
		return '';
	}

}