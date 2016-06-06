<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Evaluate_item extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/evaluate/';
		$this->load->model($this->view.'evaluate_item_model');
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		$classid=intval(trim($this->input->get('classid')));
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段

			$fields = $this->_set_lists_field ();
			$classid=intval(trim($this->input->get('classid')));
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$condition['where']=' classid = '.$classid;
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->evaluate_item_model->count ( $condition );
			$output ['aaData'] = $this->evaluate_item_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$item->state = $this->_get_lists_state ( $item->state );
				$item->operation = '
					<a class="btn btn-xs btn-info" title="编辑" onclick="pub_alert_html(\'' . $this->zjjp . 'evaluate_item/edit?classid='.$item->classid.'&id=' . $item->id . '&s=1\')" href="javascript:;">编辑</a>
					&nbsp;<a class="btn btn-xs btn-info btn-white" href="' . $this->zjjp . 'evaluate_item' . '/editinfo?classid='.$item->classid.'&itemid=' . $item->id . '&label_id=' . $_SESSION['language'] . '" title="编辑项信息">编辑项信息</a>
					<a href="javascript:;" title="删除" onclick="del('.$item->id.')" class="btn btn-xs btn-info btn-white">删除</a>
				';
				$item->operation .= ' ';

			}
			exit ( json_encode ( $output ) );
		}
		$this->_view ('evaluate_item_index',array(
				'classid'=>$classid
			));
	}
	//编辑
	function edit() {
		$id = intval ( $this->input->get ( 'id' ) );
		$classid=intval(trim($this->input->get('classid')));
		if ($id) {
			$where = "id={$id}";
			$info = $this->evaluate_item_model->get_one ( $where );
			if (empty ( $info )) {
				ajaxReturn ( '', '该类不存在', 0 );
			}
			$s = intval ( $this->input->get ( 's' ) );
			if (! empty ( $s )) {
				$html = $this->_view ( 'add_evaluate_item', array (
					'info'=>$info,
					'classid'=>$classid
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
		$classid=intval(trim($this->input->get('classid')));
		if (! empty ( $s )) {
			$html = $this->_view ( 'add_evaluate_item', array (
				'classid'=>$classid
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		
		if ($id) {
			$where = "id = {$id}";
			$is = $this->evaluate_item_model->delete ( $where );
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
			$id = $this->evaluate_item_model->save ( null, $data );
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
			$this->evaluate_item_model->save ( $id, $data );
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
				'orderby',
				'state',
				'classid'
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
			$this->evaluate_item_model->set_evaluate_time($data);
			ajaxReturn('','',1);
		}

	}
	/**
	 * [edit_info 编辑信息的项]
	 * @return [type] [description]
	 */
	function editinfo(){
		$label_id=$this->input->get('label_id');
		$itemid=intval($this->input->get('itemid'));
		$classid=intval($this->input->get('classid'));
		$where="itemid = {$itemid} AND site_language = '{$label_id}'";
		$info=$this->evaluate_item_model->get_info_one($where);
		$this->_view ('evaluate_item_info',array(
				'label_id'=>$label_id,
				'itemid'=>$itemid,
				'classid'=>$classid,
				'info'=>$info
			));
	}
	/**
	 * [update_info 编辑多语言信息]
	 * @return [type] [description]
	 */
	function update_info(){
		$data=$this->input->post();
		$data['answer_score']=json_encode($data['answer_score']);
		$id=$this->evaluate_item_model->update_info($data);
		if(!empty($id)){
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);
	}
}