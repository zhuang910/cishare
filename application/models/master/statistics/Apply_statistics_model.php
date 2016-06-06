<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Apply_statistics_Model extends CI_Model {
	const T_A_I = 'apply_info';
	const T_MAJOR='major';

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 *
	 *获取数组
	 */
	function get_array($degree,$applystate,$stime=null,$etime=null){
		$array=array();
		foreach ($degree as $k => $v) {
			foreach ($applystate as $kk => $vv) {
				$array[$k][$kk]=$this->get_count($k,$kk,$stime,$etime);
			}
		}
		return $array;
	}
	/**
	 *
	 *获取数量
	 **/
	function get_count($degree,$applystate,$stime=null,$etime=null){
		$this->db->select('count(*) as num');
		$this->db->where('apply_info.state',$applystate);
		$this->db->where('major.degree',$degree);
		if(!empty($stime)){
			$this->db->where('apply_info.applytime >=',strtotime($stime));
		}
		if(!empty($etime)){
			$this->db->where('apply_info.applytime <=',strtotime($etime));
		}
		$this->db->join(self::T_MAJOR ,self::T_A_I.'.courseid='.self::T_MAJOR.'.id');
		$data=$this->db->get(self::T_A_I)->row_array();
		return $data['num'];
	}
}