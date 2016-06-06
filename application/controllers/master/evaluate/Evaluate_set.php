<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Evaluate_set extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/evaluate/';
		$this->load->model($this->view.'evaluate_set_model');
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
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->evaluate_set_model->count ( $condition );
			$output ['aaData'] = $this->evaluate_set_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$item->state = $this->_get_lists_state ( $item->state );
				// $item->checkbox='<input type="checkbox" name="sid[]" value="'.$item->id.'">';
				if(!empty($item->starttime)){
					$item->starttime=date('Y-m-d',$item->starttime);
				}
				if(!empty($item->endtime)){
					$item->endtime=date('Y-m-d',$item->endtime);
				}
				$item->operation = '<a class="btn btn-xs btn-info" onclick="pub_alert_html(\'' . $this->zjjp . 'evaluate_set/edit?id=' . $item->id . '&s=1\')" href="javascript:;">编辑</a>';
				if($item->type==1){
					$item->operation .= '<a class="btn btn-xs btn-info btn-white" href="/master/evaluate/evaluate_item?classid='.$item->id.'">添加项</a>';
				}
				$item->operation .= '<a href="javascript:;" onclick="del('.$item->id.')" class="btn btn-xs btn-info btn-white">删除</a>';
				$item->type=!empty($item->type)?$item->type==1?'单选项':'文本项':'';
				
				/*
				 * $item->operation = ' <a title="查看" class="btn btn-small btn-success" href="javascript:pub_alert_html(\'' . $this->zjjp . '/edit?id=' . $item->id . '\',true,true);"><i class="icon-edit"></i></a> <a title="审核" class="btn btn-small btn-success" href="javascript:pub_alert_confirm(this,\'确定要修改吗？\',\'' . $this->zjjp . '/editstate?id=' . $item->id . '\');"><i class="icon-remove"></i></a> ';
				*/
			}
			//var_dump($output);die;	
			exit ( json_encode ( $output ) );
		}
		$this->_view ('evaluate_set_index');
	}
	//编辑
	function edit() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id={$id}";
			$info = $this->evaluate_set_model->get_one ( $where );
			if (empty ( $info )) {
				ajaxReturn ( '', '该类不存在', 0 );
			}
			$s = intval ( $this->input->get ( 's' ) );
			if (! empty ( $s )) {
				$html = $this->_view ( 'add_evaluate', array (
					'info'=>$info
					), true );
				ajaxReturn ( $html, '', 1 );
			}
		}
		
	}
	/**
	 * [add 添加编辑页面]
	 */
	function add(){
		$s = intval ( $this->input->get ( 's' ) );
		if (! empty ( $s )) {
			$html = $this->_view ( 'add_evaluate', array (), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		
		if ($id) {
			$where = "id = {$id}";
			$is = $this->evaluate_set_model->delete ( $where );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	/**
	 * 插入
	 */
	function insert() {
		$data = $this->input->post ();
		if (! empty ( $data )) {
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			// $data['endtime']=strtotime($data['endtime']);
			// $data['starttime']=strtotime($data['starttime']);
			$id = $this->evaluate_set_model->save ( null, $data );
			if ($id) {
				ajaxReturn ( 'back', '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	
	function update() {
		$id = intval ( $this->input->post ( 'id' ) );
		if ($id) {
			$data=$this->input->post();
			
			
			// 保存基本信息
			$this->evaluate_set_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
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
				'starttime',
				'endtime',
				'type',
				'state',
				
		);
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
	/**
	 *
	 *设置开始结束时间
	 **/
	function settime(){
		$s = intval ( $this->input->get ( 's' ) );
		$data=$this->input->post();
		$ids=json_encode($data['sid']);
		if (! empty ( $s )) {
			$html = $this->_view ( 'set_time', array (
				'ids'=>$ids
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 * [save_time 设置开始结束时间]
	 * @return [type] [description]
	 */
	function save_time(){
		$data=$this->input->post();
		if(!empty($data)){
			$this->evaluate_set_model->set_evaluate_time($data);
			ajaxReturn('','',1);
		}

	}
}