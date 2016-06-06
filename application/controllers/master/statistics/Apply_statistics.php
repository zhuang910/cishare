<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Apply_statistics extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/statistics/';
		$this->load->model($this->view.'apply_statistics_model');
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
	
		$degree = CF ( 'degree', '', CONFIG_PATH );
		$applystate = CF ( 'publics', '', CONFIG_PATH );
		if(!empty($stime)&&!empty($etime)){
			$array=$this->apply_statistics_model->get_array($degree,$applystate['app_state'],$stime,$etime);
		}else{
			$array=$this->apply_statistics_model->get_array($degree,$applystate['app_state']);
		}
		$this->_view ('apply_statistics_index',array(
			'degree'=>$degree,
			'applystate'=>$applystate['app_state'],
			'array'=>$array,
			'stime'=>$stime,
			'etime'=>$etime
			));
	}
	

}