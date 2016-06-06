<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/*
*现金收费管理
*
*/
class Acc_fee_Model extends CI_Model{
	const ACCOMMODATION_INFO = 'accommodation_info';
	const T_STUDENT = 'student_info';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	//查询数据总数
	function count($where = null){
    	$this->db->select('acc_fee.*,student_info.chname,student_info.enname,student_info.passport');
		if($where !== null){
			$this->db->where($where);
    	}
    	$this->db->join('student_info','student_info.id=accommodation_info.userid');
    	$this->db->join('acc_fee','acc_fee.acc_id=accommodation_info.id');
    	return $this->db->count_all_results(self::ACCOMMODATION_INFO);
	}


//获得数据
    function get($where = null,$limit = 25,$offset = 0,$orderby = null){
    	$this->db->select('acc_fee.*,student_info.chname,student_info.enname,student_info.passport');
    	if($where != null){
			$this->db->where($where);
    	}
    	if($limit){
			$this->db->limit($limit,$offset);
    	}

    	if($orderby !== null){
    		$this->db->order_by($orderby);
    	}
    	$this->db->join('student_info','student_info.id=accommodation_info.userid');
    	$this->db->join('acc_fee','acc_fee.acc_id=accommodation_info.id');
    	return $this->db->get(self::ACCOMMODATION_INFO)->result();
    }
	
}