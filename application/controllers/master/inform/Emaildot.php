<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Emaildot extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/inform/';
		$this->load->model($this->view.'emaildot_model');
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
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->emaildot_model->count ( $condition );
			$output ['aaData'] = $this->emaildot_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$item->createtime=date('Y-m-d',$item->createtime);
				$item->operation = '

					<a class="btn btn-xs btn-info" onclick="pub_alert_html(\''.$this->zjjp.'emaildot/edit?id='.$item->id.'\')">编辑</i></a>
					<a href="javascript:;" onclick="del('.$item->id.')" class="btn btn-xs btn-info btn-white">删除</a>
				';
			}
			//var_dump($output);die;	
			exit ( json_encode ( $output ) );
		}
		$this->_view ('emaildot_index');
	}
	/**
	 * 修改
	 */
	function edit()
	{
		$id = (int)$this->input->get('id');
		if (!empty($id)) {
			$adrset=$this->emaildot_model->get_addresserset();
			$info = $this->emaildot_model->get_one($id);
			$html = $this->_view('emaildot_edit', array(
				'action' => 'edit',
				'info' => $info,
				'addresserset'=>$adrset, 
			), true);
			ajaxReturn($html, '', 1);
		}
	}
		
	
	/**
	 * 添加
	 */
	function add()
	{
		$adrset=$this->emaildot_model->get_addresserset();
		$html = $this->_view('emaildot_edit', array(
			'action' => 'add',
			'addresserset'=>$adrset, 
		), true);
		ajaxReturn($html, '', 1);
	}
	
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		
		if ($id) {
			$where = "id = {$id}";
			$is = $this->emaildot_model->delete ( $where );
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
			$this->emaildot_model->save ( $id, $data );
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
			
			$id = $this->emaildot_model->save ( null, $data );
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
				'theme',
				'addresser',
				'createtime',
				
				
		);
	}


}