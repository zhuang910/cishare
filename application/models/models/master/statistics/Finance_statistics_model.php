<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Finance_statistics_Model extends CI_Model {
	const T_A_O_I='apply_order_info';

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
	function get_array($stime=null,$etime=null){
		$paytype['paypal']=1;
		$paytype['payease']=2;
		$paytype['pingju']=3;
		$ordertype['apply']=1;
		//$ordertype['transfer']=2;
		$ordertype['pickup']=3;
		$ordertype['accommodation']=4;
		$ordertype['depos']=5;
		$ordertype['turi']=6;
		$array=array();
		foreach ($ordertype as $k => $v) {
			foreach ($paytype as $kk => $vv) {
				$array[$k][$kk]=$this->get_count($v,$vv,$stime,$etime);
			}
		}
		return $array;
	}
	/**
	 *
	 *获取数量
	 **/
	function get_count($ordertype,$paytype,$stime=null,$etime=null){
		// var_dump($stime);
		// var_dump($etime);exit;
		$this->db->select('count(*) as num');
		$this->db->where('paytype',$paytype);
		$this->db->where('ordertype',$ordertype);
		if(!empty($stime)){
			$this->db->where('createtime >=',strtotime($stime));
		}
		if(!empty($etime)){
			$this->db->where('createtime <=',strtotime($etime));
		}
		$data=$this->db->get(self::T_A_O_I)->row_array();
		return $data['num'];
	}
}