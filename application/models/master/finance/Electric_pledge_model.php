<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/*
*现金收费管理
*
*/
class Electric_pledge_Model extends CI_Model{
	const CASH = 'electric_pledge';
	const T_STUDENT = 'student_info';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	//查询数据总数
	function count($where = null){
    	$this->db->select('zust_electric_pledge.*,zust_student_info.*');
		if($where !== null){
			$this->db->where($where);
    	}
    	$this->db->join('zust_student_info','zust_student_info.id=zust_electric_pledge.userid');
    	return $this->db->count_all_results(self::CASH);
	}


//获得数据
    function get($where = null,$limit = 25,$offset = 0,$orderby = null){
    	$this->db->select('zust_electric_pledge.*,zust_electric_pledge.id as bookid,zust_student_info.*');
    	if($where != null){
			$this->db->where($where);
    	}
    	if($limit){
			$this->db->limit($limit,$offset);
    	}

    	if($orderby !== null){
    		$this->db->order_by($orderby);
    	}
    
    	$this->db->join('zust_student_info','zust_student_info.id=zust_electric_pledge.userid');
    	return $this->db->get(self::CASH)->result();
    }

}