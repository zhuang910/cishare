<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 住宿
 *
 * @author zyj
 *        
 */
class Acc_camp extends Master_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/enrollment/';
		
		$this->load->model ( $this->view . 'acc_camp_model' );
	}
	
	/**
	 * 主页
	 */
	function index() {
		// 如果是ajax请求则返回json数据列表
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->acc_camp_model->count ( $condition, null );
			$output ['aaData'] = $this->acc_camp_model->get ( $fields, $condition, null );
			foreach ( $output ['aaData'] as $item ) {
				$item->state = $this->_get_lists_state ( $item->state );
				$item->operation = '<div class="btn-group"><a class="btn btn-xs btn-info" href="/master/enrollment/acc_camp/edit?id=' . $item->id . '&label_id=1">编辑</a><button data-toggle="dropdown" class="btn btn-xs btn-info btn-white dropdown-toggle">
								更多
								<span class="ace-icon fa fa-caret-down icon-only"></span>
							</button>
							<ul class="dropdown-menu dropdown-info dropdown-menu-right">';
				
			
				$item->operation.= '
					
					<li><a href="/master/enrollment/acc_camp/edit_info?id=' . $item->id . '&label_id='.$_SESSION['language'].'" title="编辑信息">编辑信息</a></li>
					
				';
				$item->operation .= '
						<li><a href="/master/enrollment/building/index?campid=' . $item->id . '"  >管理住宿楼</a></li>
							';
				$item->operation.='<li class="divider"></li><li><a href="javascript:;" onclick="del(' . $item->id . ')">删除</a></li></ul></div>';
			}
			
			exit ( json_encode ( $output ) );
		}
		
		$this->load->view ( 'master/enrollment/acc_camp_index');
	}
	
	/**
	 * 添加 编辑 校区
	 */
	function add_camp() {
		
		$this->load->view ( 'master/enrollment/add_camp_edit' );
	}
	
	/**
	 * 编辑
	 */
	function edit() {
		$id = intval ( $this->input->get ( 'id' ) );
		// $label_id = intval ( $this->input->get ( 'label_id' ) );
		if ($id) {
			$where = "id = {$id}";
			$info = ( object ) $this->acc_camp_model->get_one ( $where );
			
			$this->load->view ( 'master/enrollment/add_camp_edit', array (
					
					'info' => $info,
					// 'label_id' => $label_id 
			) );
		}
	}
	/**
	 * 编辑
	 */
	function edit_info() {
		$id = intval ( $this->input->get ( 'id' ) );
		$label_id =  $this->input->get ( 'label_id' );
		if ($id && $label_id) {
			$where = "columnid = {$id} AND site_language = '{$label_id}'";
			$info = ( object ) $this->acc_camp_model->get_info_one( $where );
			
			$this->load->view ( 'master/enrollment/add_camp_edit_info', array (
					'id'=>$id,
					'info' => $info,
					'label_id' => $label_id 
			) );
		}
	}

	/**
	 * 插入校区信息表
	 */
	function insert_info() {
		$data = $this->input->post ();
		if(!empty($data['aid'])){
			unset($data['aid']);
		}
	
		if (! empty ( $data )) {
			$id = $this->acc_camp_model->save_info ( null, $data );
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
			
			$id = $this->acc_camp_model->save ( null, $data );
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
			
			// 保存基本信息
			$this->acc_camp_model->save ( $id, $data );
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
			$where = "id = {$id} ";
			$info = ( object ) $this->acc_camp_model->get_one ( $where );
			$is = $this->acc_camp_model->delete ( $where );
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

