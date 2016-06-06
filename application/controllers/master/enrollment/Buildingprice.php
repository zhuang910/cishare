<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 住宿楼
 *
 * @author zyj
 *        
 */
class Buildingprice extends Master_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/enrollment/';
		$publics = CF ( 'publics', '', CONFIG_PATH );
		$this->load->vars ( 'room', $publics ['room'] );
		$this->load->model ( $this->view . 'buildingprice_model' );
	}
	
	/**
	 * 主页
	 */
	function index() {
		$publics = CF ( 'publics', '', CONFIG_PATH );
		// 获得语言的id
		$buildingid = intval ( trim ( $this->input->get ( 'buildingid' ) ) );
		// 如果是ajax请求则返回json数据列表
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$condition ['where'] ['bulidingid'] = $buildingid;
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->buildingprice_model->count ( $condition, null );
			$output ['aaData'] = $this->buildingprice_model->get ( $fields, $condition, null );
			foreach ( $output ['aaData'] as $item ) {
				$item->is_reserve = $this->_get_lists_state ( $item->is_reserve );
				$item->campusid=$publics['room'][$item->campusid];
				if(!empty($item->floor)){
					$item->floor='第'.$item->floor.'层';
				}
				$item->operation = '
					<a title="编辑" href="/master/enrollment/buildingprice/edit?id=' . $item->id . '" class="btn btn-xs btn-info">编辑</a>
					<a title="编辑房间信息" href="/master/enrollment/buildingprice/edit_info?buildingid='.$buildingid.'&id=' . $item->id . '&label_id='.$_SESSION['language'].'" class="btn btn-xs btn-info btn-white">编辑房间信息</a>
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="btn btn-xs btn-info btn-white">删除</a>
				';
			}
			
			exit ( json_encode ( $output ) );
		}
		
		$this->load->view ( 'master/enrollment/buildingprice_index', array (
				
				'buildingid' => $buildingid 
		) );
	}
	/**
	 * 获取文章状态
	 *
	 * @param string $statecode        	
	 * @param string $stateindexcode        	
	 * @return string
	 */
	private function _get_lists_state($statecode = null) {
		if ($statecode != null) {
			$statemsg = array (
					'<span class="label label-important">否</span>',
					'<span class="label label-success">是</span>' ,
					'<span class="label label-important">已满</span>' 
			);
			return $statemsg [$statecode];
		}
		return;
	}
	/**
	 * 添加 编辑 校区
	 */
	function add_buildingprice() {
		$buildingid = intval ( trim ( $this->input->get ( 'buildingid' ) ) );
		$floor_num_all=$this->buildingprice_model->get_building_floor_num($buildingid);
		$this->load->view ( 'master/enrollment/add_buildingprice_edit', array (
				
				'buildingid' => $buildingid ,
				'floor_num_all'=>$floor_num_all
		) );
	}
	
	/**
	 * 编辑
	 */
	function edit() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$info = ( object ) $this->buildingprice_model->get_one ( $where );
			$floor_num_all=$this->buildingprice_model->get_building_floor_num($info->bulidingid);
			$this->load->view ( 'master/enrollment/add_buildingprice_edit', array (
					'floor_num_all'=>$floor_num_all,
					'info' => $info 
			) );
		}
	}
	/**
	 * 编辑
	 */
	function edit_info() {
		$id = intval ( $this->input->get ( 'id' ) );
		$label_id =  $this->input->get ( 'label_id' );
		$buildingid=  $this->input->get ( 'buildingid' );
		if ($id && $label_id) {
			$where = "roomid = {$id} AND site_language = '{$label_id}'";
			$info = ( object ) $this->buildingprice_model->get_info_one( $where );
			
			$this->load->view ( 'master/enrollment/add_buildingprice_edit_info', array (
					'id'=>$id,
					'info' => $info,
					'label_id' => $label_id ,
					'buildingid'=>$buildingid
			) );
		}
	}
	/**
	 * 插入房间信息表
	 */
	function insert_info() {
		$data = $this->input->post ();
		if(!empty($data['aid'])){
			unset($data['aid']);
		}
	
		if (! empty ( $data )) {
			$id = $this->buildingprice_model->save_info ( null, $data );
			if ($id) {
				
				ajaxReturn ( 'back', '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	/**
	 * 插入
	 */
	function insert() {
		$data = $this->_save_data ();
		
		if (! empty ( $data )) {
			if (! empty ( $data ['bulidingid'] )) {
				$c = $this->db->select ( '*' )->get_where ( 'school_accommodation_buliding', 'id = ' . $data ['bulidingid'] )->result_array ();
				if (! empty ( $c )) {
					$data ['columnid'] = $c [0] ['columnid'];
				}
			}
			unset ( $data ['id'] );
			
			if(!empty($data ['bulidingid'])&&!empty($data['floor'])){
				$room_num=$this->buildingprice_model->get_room_num($data ['bulidingid'],$data['floor']);
				$bulidingid_floor_room_num=$this->buildingprice_model->get_bulidingid_floor_room_num($data ['bulidingid'],$data['floor']);
				$room_num=$room_num+1;
				if($room_num>$bulidingid_floor_room_num){
					ajaxReturn ( '', '该楼层已满', 0 );
				}
			}
			
			$id = $this->buildingprice_model->save ( null, $data );
			if ($id) {
				
				ajaxReturn ( 'back', '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	
	/**
	 * 更新
	 */
	function update() {
		$id = intval ( $this->input->post ( 'id' ) );
		if ($id) {
			$data = $this->_save_data ();
			unset ( $data ['id'] );
			// 保存基本信息
			if (! empty ( $data ['bulidingid'] )) {
				$c = $this->db->select ( '*' )->get_where ( 'school_accommodation_buliding', 'id = ' . $data ['bulidingid'] )->result_array ();
				if (! empty ( $c )) {
					$data ['columnid'] = $c [0] ['columnid'];
				}
			}
			if(!empty($data ['bulidingid'])&&!empty($data['floor'])){
				$room_num=$this->buildingprice_model->get_room_num($data ['bulidingid'],$data['floor']);
				$bulidingid_floor_room_num=$this->buildingprice_model->get_bulidingid_floor_room_num($data ['bulidingid'],$data['floor']);
				$room_num=$room_num+1;
				if($room_num>$bulidingid_floor_room_num){
					ajaxReturn ( '', '该楼层已满', 0 );
				}
			}
			$this->buildingprice_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	
	/**
	 * 获取保存数据
	 */
	private function _save_data() {
		$time = time ();
		$return = array ();
		$data = $this->input->post ();
		if (! empty ( $data )) {
			foreach ( $data as $key => $value ) {
				if ($key == 'id' && empty ( $value )) {
					unset ( $data [$key] );
				}
				
				$data [$key] = trim ( $value );
			}
		}
		
		return $data;
	}
	
	/**
	 * 删除 关联表中数据也会删除
	 */
	function delete() {
		$id = intval ( $this->input->get ( 'id' ) );
		
		if ($id) {
			$where = "id = {$id}";
			$info = ( object ) $this->buildingprice_model->get_one ( $where );
			$is = $this->buildingprice_model->delete ( $where );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'name',
				'enname',
				'prices',
				'floor',
				'is_reserve',
				'remark',
				'campusid' 
		);
	}
	/**
	 * [get_room_num 获取该楼层还有多少房间]
	 * @return [type] [description]
	 */
	function get_room_num(){
		$buildingid=intval($this->input->get('buildingid'));
		$floor=intval($this->input->get('floor'));
		if(!empty($buildingid)&&!empty($floor)){
				$room_num=$this->buildingprice_model->get_room_num($buildingid,$floor);
				$bulidingid_floor_room_num=$this->buildingprice_model->get_bulidingid_floor_room_num($buildingid,$floor);
				$num=$bulidingid_floor_room_num-$room_num;
					ajaxReturn ( $num, '该楼层已满', 1 );
			}
		ajaxReturn ( '', '', 0 );
	}
	/**
	 * [check_maxuser 更改人数检查现在住的人数是否超过以前设置人数]
	 * @return [type] [description]
	 */
	function check_maxuser(){
		$roomid=intval($this->input->get('roomid'));
		$num=intval($this->input->get('num'));
		if(!empty($roomid)){
			$is=$this->buildingprice_model->update_room_shate($roomid,$num);
			ajaxReturn($is,'',1);
		}
		ajaxReturn('','',0);
	}
}

