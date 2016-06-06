<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 类别管理
 *
 * @author grf
 *        
 */
class Type extends Master_Basic {
	function __construct() {
		parent::__construct ();
		$this->view = 'master/cms/';
		$publics = CF ( 'publics', '', CONFIG_PATH );
		$this->load->model ( $this->view . 'type_model' );
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
			$condition ['where'] = 'parent_id = 0';
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->type_model->count ( $condition );
			$output ['aaData'] = $this->type_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$item->state = $this->_get_lists_state ( $item->state );
				$item->createtime = date ( 'Y-m-d', $item->createtime );
				$item->lasttime = date ( 'Y-m-d', $item->lasttime );
				$item->operation = '
					<a title="编辑" class="green" href="' . $this->zjjp . 'type' . '/edit?id=' . $item->id . '"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
					<a title="管理内容" class="green" href="' . $this->zjjp . 'type' . '/edittype?parent_id=' . $item->id . '"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
							';
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'type_index', array () );
	}
	
	/**
	 * 类别内容页
	 */
	function edittype() {
		$parent_id = intval ( trim ( $this->input->get ( 'parent_id' ) ) );
		// 如果是ajax请求则返回json数据列表
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			if ($parent_id) {
				$condition ['where'] = 'parent_id = ' . $parent_id;
			}
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->type_model->count ( $condition );
			$output ['aaData'] = $this->type_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$item->state = $this->_get_lists_state ( $item->state );
				$item->createtime = date ( 'Y-m-d', $item->createtime );
				$item->lasttime = date ( 'Y-m-d', $item->lasttime );
				$item->operation = '
					<a title="编辑" class="green" href="' . $this->zjjp . 'type' . '/edit_type?id=' . $item->id . '&parent_id='.$item->parent_id.'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
				';
			}
			exit ( json_encode ( $output ) );
		}
		// 查询类别名称
		$name = $this->type_model->get_one ( 'id = ' . $parent_id );
		$this->_view ( 'edittype_index', array (
				'parent_id' => $parent_id,
				'name' => ! empty ( $name->title ) ? $name->title : '' 
		) );
	}
	/**
	 * 添加
	 */
	function add() {
		$this->_view ( 'type_edit', array () );
	}
	/**
	 * 编辑
	 */
	function edit() {
		// 获取文章id
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = " . $id;
			$info = ( object ) $this->type_model->get_one ( $where );
			if (empty ( $info )) {
				$this->_alert ( '此文章不存在' );
			}
		}
		
		$this->_view ( 'type_edit', array (
				// 'programaids' => $this->programaids_news,
				'info' => ! empty ( $info ) ? $info : array () 
		) );
	}
	
	/**
	 * 编辑
	 */
	function edit_type() {
		// 获取文章id
		$id = intval ( $this->input->get ( 'id' ) );
		$parent_id = intval ( $this->input->get ( 'parent_id' ) );
		if ($id) {
			$where = "id = " . $id;
			$info = ( object ) $this->type_model->get_one ( $where );
			if (empty ( $info )) {
				$this->_alert ( '此文章不存在' );
			}
		}
		
		$this->_view ( 'edit_type', array (
				'info' => ! empty ( $info ) ? $info : array (),
				'parent_id' => $parent_id 
		) );
	}
	
	/**
	 * 插入
	 */
	function insert() {
		$data = $this->_save_data ();
		
		if (! empty ( $data )) {
			$data ['parent_id'] = 0;
			$id = $this->type_model->save ( null, $data );
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
			$this->type_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	
	/**
	 * 删除 关联表中数据也会删除
	 */
	function del_type() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$info = ( object ) $this->type_model->get_one ( $where );
			$is = $this->type_model->delete ( $where );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	
	/**
	 * 保存
	 */
	function save_type() {
		$data = $this->input->post ();
		if ($data) {
			if (! empty ( $data ['id'] )) {
				$id = $data ['id'];
			}
			unset ( $data ['id'] );
			$data ['lasttime'] = time ();
			$data ['adminid'] = $this->adminid;
			if (!empty($id)) {
				$flag = $this->type_model->save ( $id, $data );
			} else {
				$flag = $this->type_model->save ( null, $data );
			}
			if ($flag) {
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'title',
				'orderby',
				'createtime',
				'lasttime',
				'state' ,
				'parent_id'
		);
	}
	
	/**
	 * 获取模型状态
	 *
	 * @param string $statecode        	
	 * @param string $stateindexcode        	
	 * @return string
	 */
	private function _get_lists_state($statecode = null) {
		if ($statecode != null) {
			$statemsg = array (
					'<span class="label label-important">禁用</span>',
					'<span class="label label-success">正常</span>' 
			);
			return $statemsg [$statecode];
		}
		return;
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
		
		$data ['lasttime'] = $time;
		$data ['adminid'] = $this->adminid;
		return $data;
	}
	// ///////////////////////////////////////////////////////////////以下是 为了调取页面的 暂时不用
	/**
	 * 模版一 编辑 模版
	 */
	function zyj_template1_list1() {
		$this->_view ( 'zyj_template1_list1' );
	}
	
	/**
	 * 模版二 列表模版
	 */
	function zyj_template1_list2() {
		$this->_view ( 'zyj_template1_list2' );
	}
	/**
	 * 图集
	 */
	function zyj_template_img() {
		$this->_view ( 'zyj_template_img' );
	}
}

