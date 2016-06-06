<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Learning extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/statistics/';
		$this->load->model($this->view.'learning_model');
	}
	/**
	 * 后台主页
	 */
	function index() {
		// $stime=$this->input->get('stime');
		// $etime=$this->input->get('etime');
		$degree = CF ( 'degree', '', CONFIG_PATH );
		$publics = CF ( 'publics', '', CONFIG_PATH );
		$array=$this->learning_model->get_array($degree,$publics['language']);
		$this->_view ('learning_index',array(
			'degree'=>$degree,
			'array'=>$array,
			));
	}
	

}