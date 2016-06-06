<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Teacher extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/teacher/';
		$this->load->model ( $this->view . 'teacher_model' );
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->teacher_model->count ( $condition );
			$output ['aaData'] = $this->teacher_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				
				$item->operation = '

					<a class="btn btn-xs btn-info" title="编辑" href="' . $this->zjjp . 'teacher/teacher' . '/edit?id=' . $item->id . '">编辑</a>
					
					<a class="btn btn-xs btn-info btn-white" title="关联课程" href="' . $this->zjjp . 'teacher/teacher_course?teacherid=' . $item->id . '">关联课程</a>
							<a href="javascript:;" onclick="del(' . $item->id . ')" class="btn btn-xs btn-info btn-white">删除</a>
				';
				/*
				 * $item->operation = ' <a title="查看" class="btn btn-small btn-success" href="javascript:pub_alert_html(\'' . $this->zjjp . '/edit?id=' . $item->id . '\',true,true);"><i class="icon-edit"></i></a> <a title="审核" class="btn btn-small btn-success" href="javascript:pub_alert_confirm(this,\'确定要修改吗？\',\'' . $this->zjjp . '/editstate?id=' . $item->id . '\');"><i class="icon-remove"></i></a> ';
				 */
			}
			// var_dump($output);die;
			exit ( json_encode ( $output ) );
		}
		$this->_view ( 'teacher_index' );
	}
	function edit() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id={$id}";
			$info = $this->teacher_model->get_one ( $where );
			if (empty ( $info )) {
				ajaxReturn ( '', '该老师不存在', 0 );
			}
		}
		$this->_view ( 'teacher_edit', array (
				
				'info' => $info 
		)
		 );
	}
	function add() {
		if ($this->input->is_ajax_request () === true) {
			$data = $this->input->post ();
			$this->db->insert ( 'teacher', $data );
			ajaxReturn ( '', '', 1 );
			// var_dump($this->input->post());exit;
		}
		$this->_view ( 'teacher_edit' );
	}
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		
		if ($id) {
			$where = "id = {$id}";
			$is = $this->teacher_model->delete ( $where );
			if ($is === true) {
				$this->teacher_model->delete_guanlian ( $id );
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	function update() {
		$id = intval ( $this->input->post ( 'id' ) );
		if ($id) {
			$data = $this->input->post ();
			
			// 保存基本信息
			$this->teacher_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	/**
	 * 插入
	 */
	function insert() {
		$data = $this->input->post ();
		
		if (! empty ( $data )) {
			
			$id = $this->teacher_model->save ( null, $data );
			if ($id) {
				
				ajaxReturn ( 'back', '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'name',
				'englishname',
				'email',
				'phone',
				'post' 
		)
		;
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
					'<span class="label label-important">禁用</span>',
					'<span class="label label-success">正常</span>' 
			);
			return $statemsg [$statecode];
		}
		return;
	}
}