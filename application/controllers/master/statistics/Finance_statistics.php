<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Finance_statistics extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/statistics/';
		$this->load->model($this->view.'finance_statistics_model');
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		// $stime=$this->input->get('stime');
		// $etime=$this->input->get('etime');
		$data=$this->input->post();
		$stime='';
		$etime='';
		if(!empty($data)){
			$stime=$data['stime'];
			$etime=$data['etime'];
		}
		$array=$this->finance_statistics_model->get_array($stime,$etime);
		$this->_view ('finance_statistics_index',array(
			'array'=>$array,
			'stime'=>$stime,
			'etime'=>$etime
			));
	}
	

}