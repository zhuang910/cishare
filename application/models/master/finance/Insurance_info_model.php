<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/*
*现金收费管理
*
*/
class Insurance_info_Model extends CI_Model{
	const CARD = 'insurance_info';
	const T_STUDENT = 'student_info';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	//查询数据总数
	function count($where = null){
    	$this->db->select('zust_insurance_info.*,zust_student_info.chname,zust_student_info.enname,zust_student_info.passport');
		if($where !== null){
			$this->db->where($where);
    	}
    	$this->db->join('zust_insurance_info','zust_student_info.id=zust_insurance_info.userid');
    	return $this->db->count_all_results(self::T_STUDENT);
	}


	//获得数据
    function get($where = null,$limit = 25,$offset = 0,$orderby = null){
    	$this->db->select('zust_insurance_info.*,zust_student_info.chname,zust_student_info.enname,zust_student_info.passport');
    	if($where != null){
			$this->db->where($where);
    	}
    	if($limit){
			$this->db->limit($limit,$offset);
    	}

    	if($orderby !== null){
    		$this->db->order_by($orderby);
    	}
    	$this->db->join('zust_student_info','zust_student_info.id=zust_insurance_info.userid');
    	return $this->db->get(self::CARD)->result();
    }
	/**
	 * [get_student_remark 获取学生备注]
	 * @return [type] [description]
	 */
	function get_student_remark($id){
		if(!empty($id)){
			$this->db->select('remark');
			$this->db->where('id',$id);
			$data=$this->db->get(self::CARD)->row_array();
			return $data['remark'];
		}
		return '';
	}

}