<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Acc_history extends Master_Basic {
	protected $_size = 3;
	protected $_count = 0;
	protected $_countpage = 0;
	protected $data_student = array ();
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/enrollment/';
		$this->load->model ( $this->view . 'acc_history_model' );
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		$label_id = $this->input->get ( 'label_id' );
		$label_id = ! empty ( $label_id ) ? $label_id : '1';
		// 如果是ajax请求则返回json数据列表
		if ($this->input->is_ajax_request () === true) {
			//biaoqian
			$label_id = $this->input->get ( 'label_id' );
			$label_id = ! empty ( $label_id ) ? $label_id : '1';
			//状态筛选
			if(!empty($label_id)){
				$where='state = '.$label_id;
			}
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->acc_history_model->count ( $condition,$where);
			$output ['aaData'] = $this->acc_history_model->get ( $fields, $condition ,$where);
			foreach ( $output ['aaData'] as $item ) {
				$item->userid=$this->acc_history_model->get_user_name($item->userid);
				//获取校区名
				$item->campusid=$this->acc_history_model->get_campus_name($item->campusid);
				//楼层名字
				$item->buildingid=$this->acc_history_model->get_buliding_name($item->buildingid);
				$item->roomid=$this->acc_history_model->get_room_name($item->roomid);
				$item->floor='第'.$item->floor.'层';
				if($label_id==1&&!empty($item->in_time)){
					$item->time=date('Y-m-d',$item->in_time);
				}elseif($label_id==2&&!empty($item->leave_time)){
					$item->time=date('Y-m-d',$item->leave_time);
				}else{
					$item->time='';
				}
				$item->operation = '
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="red" title="删除" id="del"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
					';
				
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'acc_history_index',array(
				'label_id' => $label_id,
			) );
	}
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'userid',
				'campusid',
				'buildingid',
				'floor',
				'roomid',
				'in_time',
				'leave_time',
				'state'
		);
	}
	
	/**
	 * 获取状态
	 */
	function get_state($state = null) {
		if ($state != null) {
			$stateArray = array (
					0=>'',
					1 => '<span class="label label-success">在校</span>',
					2 => '<span class="label label-success">转学</span>',
					3 => '<span class="label label-success">正常离开</span>',
					4 => '<span class="label label-success">非正常离开</span>',
					5 => '<span class="label label-success">休学</span>',
					6 => '<span class="label label-success">申请</span>', 
					7 => '<span class="label label-success">已报到</span>',
					8 => '<span class="label label-success">未报到</span>' ,
			);
			return $stateArray [$state];
		} else {
			return false;
		}
	}
	
}