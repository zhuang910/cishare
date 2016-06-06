<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Messagedot extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/message/';
		$this->load->model($this->view.'messagedot_model');
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
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->messagedot_model->count ( $condition );
			$output ['aaData'] = $this->messagedot_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$item->createtime=date('Y-m-d',$item->createtime);
				$item->operation = '

					<a class="btn btn-xs btn-info" onclick="pub_alert_html(\''.$this->zjjp.'messagedot/edit?id='.$item->id.'\')">编辑</a>
					<a href="javascript:;" onclick="del('.$item->id.')" class="btn btn-xs btn-info btn-white">删除</a>
				';
			}
			//var_dump($output);die;	
			exit ( json_encode ( $output ) );
		}
		$this->_view ('messagedot_index');
	}
	/**
	 * 修改
	 */
	function edit()
	{
		$id = (int)$this->input->get('id');
		if (!empty($id)) {
			$info = $this->messagedot_model->get_one($id);
			$html = $this->_view('messagedot_edit', array(
				'action' => 'edit',
				'info' => $info,
			), true);
			ajaxReturn($html, '', 1);
		}
	}
		
	
	/**
	 * 添加
	 */
	function add()
	{
		$html = $this->_view('messagedot_edit', array(
			'action' => 'add',
		), true);
		ajaxReturn($html, '', 1);
	}
	
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		
		if ($id) {
			$where = "id = {$id}";
			$is = $this->messagedot_model->delete ( $where );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	function update() {
		$id = intval ( $this->input->post ( 'id' ) );
		if ($id) {
			$data=$this->input->post();
			
			
			// 保存基本信息
			$this->messagedot_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	/**
	 * 插入
	 */
	function insert() {
		
		$data = $this->input->post();
		$data['content']=$this->input->get('content');
		$data['createtime']=time();
		//var_dump($data);exit;
		if (! empty ( $data )) {
			
			$id = $this->messagedot_model->save ( null, $data );
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
				'title',
				'addresser',
				'createtime',
				
				
		);
	}


}