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
class Building extends Master_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/enrollment/';
		
		$this->load->model ( $this->view . 'buildings_model' );
	}
	
	/**
	 * 主页
	 */
	function index() {
		
		// 获得语言的id
		$campid = intval ( trim ( $this->input->get ( 'campid' ) ) );
		
		// 如果是ajax请求则返回json数据列表
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
				$campid = intval ( trim ( $this->input->get ( 'campid' ) ) );
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$condition ['where'] ['columnid'] = $campid;
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->buildings_model->count ( $condition, null );
			$output ['aaData'] = $this->buildings_model->get ( $fields, $condition, null );
			foreach ( $output ['aaData'] as $item ) {
				$item->state = $this->_get_lists_state ( $item->state );
				$item->operation = '<div class="btn-group"><a class="btn btn-xs btn-info" href="/master/enrollment/building/edit?id=' . $item->id . '">编辑</a><button data-toggle="dropdown" class="btn btn-xs btn-info btn-white dropdown-toggle">
								更多
								<span class="ace-icon fa fa-caret-down icon-only"></span>
							</button>
							<ul class="dropdown-menu dropdown-info dropdown-menu-right">';
				
				
				$item->operation.= '
					<li><a title="编辑信息" href="/master/enrollment/building/edit_info?campid='.$campid.'&id=' . $item->id . '&label_id='.$_SESSION['language'].'">编辑信息</a></li>
				';
				$item->operation .= '
					<li><a href="/master/enrollment/buildingimg/index?buildingid=' . $item->id . '" title="添加图集">添加图集</a></li>
					<li><a href="/master/enrollment/buildingprice/index?buildingid=' . $item->id .  '"  title="管理房型">管理房型</a></li>
					<li class="divider"></li><li><a href="javascript:;" onclick="del(' . $item->id . ',' . $item->site_language . ')">删除</a></li>
					';
				$item->operation.='</ul></div>';
			}
			
			exit ( json_encode ( $output ) );
		}
		
		$this->load->view ( 'master/enrollment/building_index', array (
				'campid' => $campid 
		) );
	}
	
	/**
	 * 添加 编辑 校区
	 */
	function add_building() {
		$label_id = $this->input->get ( 'label_id' );
		$campid = intval ( trim ( $this->input->get ( 'campid' ) ) );
		$this->load->view ( 'master/enrollment/add_building_edit', array (
				'label_id' => $label_id,
				'campid' => $campid 
		) );
	}
	
	/**
	 * 编辑
	 */
	function edit() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id} ";
			$info = ( object ) $this->buildings_model->get_one ( $where );
			$where_floor_room = "bulidingid={$id}";
			$info_floor_room=$this->buildings_model->get_floor_room( $where_floor_room );
//            echo $this->db->last_query();
            if(empty($info_floor_room)){
                $floor=!empty($info->floor_num)?$info->floor_num:0;
            }else{
                $floor=0;
            }
			$this->load->view ( 'master/enrollment/add_building_edit', array (
                    'floor'=>$floor,
					'info' => $info,
					'info_floor_room'=>$info_floor_room
			) );
		}
	}
	/**
	 * 编辑
	 */
	function edit_info() {
		$id = intval ( $this->input->get ( 'id' ) );
		$campid= intval ( $this->input->get ( 'campid' ) );
		$label_id =$this->input->get ( 'label_id' );
		if ($id) {
			$where = "bulidingid = {$id} AND site_language = '{$label_id}'";
			$info =  $this->buildings_model->get_info_one ( $where );
			
			$this->load->view ( 'master/enrollment/add_building_edit_info', array (
					'info' => $info,
					'label_id' => $label_id ,
					'id'=>$id,
					'campid'=>$campid
			) );
		}
	}
	/**
	 * 插入信息表
	 */
	function insert_info() {
		$data=$this->input->post();
		if(!empty($data['aid'])){
			unset($data['aid']);
		}
		if (! empty ( $data )) {
			
			$id = $this->buildings_model->save_info ( null, $data );
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
		$data_one=$this->input->post();
		if(!empty($data_one['floor_room_num'])){
			$floor_room_num=$data_one['floor_room_num'];
			unset($data_one['floor_room_num']);
		}	
		$data = $this->_save_data ();
		if (! empty ( $data )) {
			
			$id = $this->buildings_model->save ( null, $data );
			if ($id) {
				$this->buildings_model->insert_floor_room_num($id,$floor_room_num);
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
			$data_one=$this->input->post();
			if(!empty($data_one['floor_room_num'])){
				$floor_room_num=$data_one['floor_room_num'];
				unset($data_one['floor_room_num']);
			}
			$data = $this->_save_data ();
			// 保存基本信息
			$this->buildings_model->save ( $id, $data );
			$this->buildings_model->update_floor_room_num($id,$floor_room_num);
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
		if(!empty($data['floor_room_num'])){
			$floor_room_num=$data['floor_room_num'];
			unset($data['floor_room_num']);
		}

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
			$is = $this->buildings_model->delete ( $where );
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
				'orderby',
				'site_language',
				'state' 
		);
	}
	
	/**
	 * 获取管理员状态
	 *
	 * @param string $statecode        	
	 * @param string $stateindexcode        	
	 * @return string
	 */
	private function _get_lists_state($statecode = null) {
		if ($statecode != null) {
			$statemsg = array (
					'<span class="label label-important">禁用</span>',
					'<span class="label label-success">启用</span>' 
			);
			return $statemsg [$statecode];
		}
		return;
	}
}

