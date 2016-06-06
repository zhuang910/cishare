	<?php
	defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
	
	/**
	 * 后台首页
	 *
	 * @author JJ
	 *        
	 */
	class Barter_card extends Master_Basic {
		protected $programaids_course = array ();
		public $programaid_parent = 0;
		/**
		 * 基础类构造函数
		 */
		function __construct() {
			parent::__construct ();
			$this->view = 'master/charge/';
			$this->load->model ( $this->view . 'barter_card_model' );
		
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
				
				$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->barter_card_model->count ( $condition );
				
				$output ['aaData'] = $this->barter_card_model->get ( $fields, $condition );
				
				foreach ( $output ['aaData'] as $item ) {
					$item->majorid=$this->barter_card_model->get_majorname($item->majorid);
					$item->squadid=$this->barter_card_model->get_squadname($item->squadid);
					$item->operation = '
					<a class="btn btn-xs btn-info" title="编辑" href="javascript:;" onclick="pub_alert_html(\'' . $this->zjjp . 'barter_card/look_remark?id=' . $item->id . '&s=1\')">编辑</a>
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="btn btn-xs btn-info btn-white">删除</a>
				';
				}
				// var_dump($output);die;
				exit ( json_encode ( $output ) );
			}
		
			$this->_view ( 'barter_card_index' );
		}
		/**
		 * 设置列表字段
		 */
		private function _set_lists_field() {
			return array (
					'id',
					'name',
					'userid',
					'squadid',
					'term' ,
					'email' ,
					'passport' ,
					'majorid',
					'money' ,
			);
		}
		/**
		 * 获取书籍状态
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
		 * [add 添加编辑页面]
		 */
		function add(){
			$s = intval ( $this->input->get ( 's' ) );
			if (! empty ( $s )) {
				$html = $this->_view ( 'barter_card_model', array (), true );
				ajaxReturn ( $html, '', 1 );
			}
		}
	/**
	 * 插入
	 */
	function insert() {
		$data = $this->input->post ();
		if (! empty ( $data )) {
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$id = $this->barter_card_model->save ( null, $data );
			if ($id) {
				ajaxReturn ( 'back', '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	//编辑
	function edit() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id={$id}";
			$info = $this->barter_card_model->get_one ( $where );
			if (empty ( $info )) {
				ajaxReturn ( '', '该书籍不存在', 0 );
			}
			$s = intval ( $this->input->get ( 's' ) );
			if (! empty ( $s )) {
				$html = $this->_view ( 'barter_card_model', array (
					'info'=>$info
					), true );
				ajaxReturn ( $html, '', 1 );
			}
		}
		
	}
	//更新
	function update() {
		$data=$this->input->post();
		$id = $data['id'];
		unset($data['id']);
		if ($id) {
			// 保存基本信息
			$this->barter_card_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	//删除
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$is = $this->barter_card_model->delete ( $where );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	/**
	 * [look_remark 查看备注]
	 * @return [type] [description]
	 */
	function look_remark(){
		$s=$this->input->get('s');
		$id=intval($this->input->get('id'));
		$where='id = '.$id;
		$info=$this->barter_card_model->get_one($where);
		$mdata=$this->barter_card_model->get_major_info_one($info->majorid);
		if (! empty ( $s )) {
				$html = $this->_view ( 'edit_barter_card', array (
					'info'=>$info,
					'mdata'=>$mdata
					), true );
				ajaxReturn ( $html, '', 1 );
			}
	}
}