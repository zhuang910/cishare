<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Finance_all_Model extends CI_Model {
	const T_A_O_I = 'apply_order_info';
	const T_STU_INFO='student_info';

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	//查询数据总数
	function count($where = null){
    	$this->db->select('zust_student_info.*,zust_student_info.chname,zust_student_info.enname,zust_student_info.passport');
		if($where !== null){
			$this->db->where($where);
    	}
    	$this->db->join('zust_apply_order_info','zust_student_info.id=zust_apply_order_info.userid');
    	return $this->db->count_all_results(self::T_STU_INFO);
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
    function get($where = null,$limit = 25,$offset = 0,$orderby = null){
    	$this->db->select('zust_apply_order_info.*,zust_student_info.chname,zust_student_info.enname,zust_student_info.passport');
    	if($where != null){
			$this->db->where($where);
    	}
    	if($limit){
			$this->db->limit($limit,$offset);
    	}

    	if($orderby !== null){
    		$this->db->order_by($orderby);
    	}
    	$this->db->join('zust_student_info','zust_student_info.id=zust_apply_order_info.userid');
    	return $this->db->get(self::T_A_O_I)->result();
    }

}