<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 接机
 *
 * @author zyj
 *        
 */
class Vacant_room extends Master_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/enrollment/';
		
		$this->load->model ( $this->view . 'vacant_room_model' );
	}
	/**
	 * [adjust 调剂页面]
	 * @return [type] [description]
	 */
	function index(){
		$campus_info=$this->vacant_room_model->get_campus_info();
		$this->_view ( 'vacant_room_index' ,array(
			'campus_info'=>$campus_info,
			));
	}
	/**
	 * [get_buliding 获取该校区的住宿楼]
	 * @return [type] [description]
	 */
	function get_buliding(){
		$cid=intval($this->input->get('cid'));
		if(!empty($cid)){
			$data=$this->vacant_room_model->get_buliding_info($cid);
			ajaxReturn($data,'',1);
		}
		ajaxReturn('','',0);
	}
	/**
	 * [get_buliding_floor 获取楼房的层数]
	 * @return [type] [description]
	 */
	function get_buliding_floor(){
		$bid=intval($this->input->get('bid'));
		if(!empty($bid)){
			$data=$this->vacant_room_model->get_buliding_floor($bid);
			ajaxReturn($data,'',1);
		}
		ajaxReturn('','',0);
	}
	/**
	 * [adjust_sure 筛选出来的房间]
	 * @return [type] [description]
	 */
	function adjust_sure(){
		$data=$this->input->post();
		if(empty($data['campusid'])){
			ajaxReturn('','请选择校区',0);
		}
		if(empty($data['bulidingid'])){
			ajaxReturn('','请选择宿舍楼',0);
		}
		//查找该楼的房间
		$room_info=$this->vacant_room_model->get_room_info($data);
		$data['room_info']=$room_info;
		$data['c_name']=$this->vacant_room_model->get_campus_name($data['campusid']);
		$data['b_name']=$this->vacant_room_model->get_buliding_name($data['bulidingid']);
		if(!empty($room_info)){
			ajaxReturn($data,'',1);
		}
		ajaxReturn('','该楼层下还没有房间',0);
	}

}

