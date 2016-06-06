<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Learning_Model extends CI_Model {
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
	function get_array($degree,$language){
		$array=array();
		foreach ($degree as $k => $v) {
			foreach ($language as $kk => $vv) {
				$array[$v['title']][$kk]=$this->get_count($v['id'],$kk);
			}
		}
		return $array;
	}
	/**
	 *
	 *获取数量
	 **/
	function get_count($degree,$language){
		// var_dump($stime);
		// var_dump($etime);exit;
		$this->db->select('coursenum');
		$this->db->where('degree',$degree);
		$this->db->where('language',$language);
		$data=$this->db->get(self::T_MAJOR)->result_array();
		$num=0;
		foreach ($data as $k => $v) {
			$num+=$v['coursenum'];
		}
		return $num;
	}
}