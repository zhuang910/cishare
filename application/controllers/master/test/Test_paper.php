	<?php
	defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
	
	/**
	 * 后台首页
	 *
	 * @author JJ
	 *        
	 */
	class Test_paper extends Master_Basic {
		protected $programaids_course = array ();
		public $programaid_parent = 0;
		/**
		 * 基础类构造函数
		 */
		function __construct() {
			parent::__construct ();
			$this->view = 'master/test/';
			$this->load->model ( $this->view . 'test_paper_model' );
		
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
				
				$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->test_paper_model->count ( $condition );
				
				$output ['aaData'] = $this->test_paper_model->get ( $fields, $condition );
				
				foreach ( $output ['aaData'] as $item ) {
					$item->checkbox='<input type="checkbox" name="sid[]" value="'.$item->id.'">';
					$item->state = $this->_get_lists_state ( $item->state );
					$item->scope_all=$this->get_scope($item->scope_all,$item->id);
					$item->operation = '
					<a class="btn btn-xs btn-info" href="/master/test/test_paper/edit?id='.$item->id.'">编辑</a>
					<a class="btn btn-xs btn-info btn-white" href="/master/test/test_paper/group_item?id='.$item->id.'">组合试卷</a>
				';
					if($item->id!=1){
					
						$item->operation.='<a href="javascript:;" onclick="del(' . $item->id . ')" class="btn btn-xs btn-info btn-white">删除</a>';
					}

				}
				exit ( json_encode ( $output ) );
			}
		
			$this->_view ( 'test_paper_index' );
		}
		/**
		 * 设置列表字段
		 */
		private function _set_lists_field() {
			return array (
					'state' ,
					'id',
					'name',
					'enname',
					'scope_all'
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
		function state_change(){
			$data=$this->input->post();
			if(!empty($data['sid'])){
				$is=$this->test_paper_model->change_state($data['sid']);
				if($is==1){
					ajaxReturn('','',1);
				}else{
					ajaxReturn('','',0);
				}
				
			}
		}
		/**
		 * [add 添加编辑页面]
		 */
		function add(){
			//学历信息
			$degree_info=$this->test_paper_model->get_degree_info();
			$this->_view ( 'test_paper_edit',array(
				'degree_info'=>$degree_info
				));
		
		}
	/**
	 * 插入
	 */
	function insert() {
		$data = $this->input->post ();
		if (! empty ( $data )) {
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$id = $this->test_paper_model->save ( null, $data );
			if ($id) {
				ajaxReturn ( 'back', '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	//编辑
	function edit() {
		$id = intval ( $this->input->get ( 'id' ) );
		$info=array();
		$major_info=array();
		if(!empty($id)){
			$where=" id = {$id}";
			$info = $this->test_paper_model->get_one ( $where );
			if($info->scope_all==0){
				//专业信息
				$major_info=$this->test_paper_model->get_maojor_info($info->degreeid);
			}
			
		}
		//学历信息
		$degree_info=$this->test_paper_model->get_degree_info();
		
		$this->_view ( 'test_paper_edit',array(
			'degree_info'=>$degree_info,
			'info'=>$info,
			'major_info'=>$major_info
			));
		
	}
	//更新
	function update() {
		$id = intval ( $this->input->post ( 'id' ) );
		$data=$this->input->post();
		if ($id) {
			// 保存基本信息
			$this->test_paper_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	//删除
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$is = $this->test_paper_model->delete ( $where );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	/**
	 * [get_maojor 获取学位下的专业]
	 * @param  [type] $degreeid 学位id
	 * @return [type]           [description]
	 */
	function get_maojor($degreeid){
		if(!empty($degreeid)){
			$major_info=$this->test_paper_model->get_maojor_info($degreeid);
			if(!empty($major_info)){
				ajaxReturn($major_info,'',1);
			}else{
				ajaxReturn('','该学位下没有专业',0);
			}
		}
		ajaxReturn('','',0);
	}
	/**
	 * [get_scope 获取范围]
	 * @param  [type] $scope [标识]
	 * @return [type]        [str]
	 */
	function get_scope($scope=null,$id=null){
		if($scope==1){
				return '全部';
		}else{
				$where=" id = {$id}";
				$info = $this->test_paper_model->get_one ( $where );
				$str=$this->test_paper_model->get_degree_major($info->degreeid,$info->majorid);
				return $str;
		}

	}
	/**
	 * [set 设置考试页面]
	 */
	function set(){
		$paperid=intval($this->input->get('paperid'));
		if ($this->input->is_ajax_request () === true) {
				// 设置查询字段
				
				$fields = $this->_set_lists_field_group ();
				$paperid=intval($this->input->get('paperid'));
				// 查询条件组合
				$condition = dateTable_where_order_limit ( $fields );
				
				$output ['sEcho'] = intval ( $_GET ['sEcho'] );
				
				$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->test_paper_model->count_paper ( $condition,$paperid );
				
				$output ['aaData'] = $this->test_paper_model->get_paper ( $fields, $condition ,$paperid);
				foreach ( $output ['aaData'] as $item ) {
					$item->state = $this->_get_lists_state ( $item->state );
					$item->operation = '
					<a class="green" href="javascript:;" onclick="pub_alert_html(\'' . $this->zjjp . 'test_paper/edit_paper_group?paperid='.$item->paperid.'&id=' . $item->id . '&s=1\')"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
					<a class="green" href="/master/test/test_paper/group_item?groupid='.$item->id.'" title="编辑试题项"> <i class="ace-icon fa fa-cog bigger-130"></i></a>
					<a class="green" href="/master/test/test_paper/group_item?groupid='.$item->id.'" title="编辑试题项"> <i class="ace-icon fa fa-cog bigger-130"></i></a>
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="red" title="删除"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
				';
				}
				exit ( json_encode ( $output ) );
			}
		
			$this->_view ( 'set_paper' ,array(
					'paperid'=>$paperid
				));
	}
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field_group() {
		return array (
				'id',
				'paperid',
				'name',
				'all_score',
				'state' 
		);
	}
	/**
	 * [add_paper_group 添加考试组页面]
	 */
	 function add_paper_group(){
	 	$s = intval ( $this->input->get ( 's' ) );
	 	$paperid=intval($this->input->get ( 'paperid' ));
		if (! empty ( $s )) {
			$html = $this->_view ( 'add_paper_group', array (
				'paperid'=>$paperid
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 * 插入
	 */
	function insert_paper_group() {
		$data = $this->input->post ();
		if (! empty ( $data )) {
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$id = $this->test_paper_model->save_paper_group( null, $data );
			if ($id) {
				ajaxReturn ( $id, '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	/**
	 * [add_paper_group 添加考试组页面]
	 */
	 function edit_paper_group(){
	 	$s = intval ( $this->input->get ( 's' ) );
	 	$id=intval ( $this->input->get ( 'id' ) );
	 	$where=" id = {$id}";
	 	$info=$this->test_paper_model->get_one_paper_group($where);
	 	$paperid=intval($this->input->get ( 'paperid' ));
		if (! empty ( $s )) {
			$html = $this->_view ( 'add_paper_group', array (
				'paperid'=>$paperid,
				'info'=>$info
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	//更新
	function update_paper_group() {
		$id = intval ( $this->input->post ( 'id' ) );
		$data=$this->input->post();
		if ($id) {
			// 保存基本信息
			$this->test_paper_model->save_paper_group ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	//删除
	function del_paper_group() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$is = $this->test_paper_model->delete_paper_group ( $where );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	/**
	 * [paper_item 试题项页面]
	 * @return [type] [description]
	 */
	function paper_item(){
		if ($this->input->is_ajax_request () === true) {
				// 设置查询字段
				
				$fields = $this->_set_lists_field_item ();
				// 查询条件组合
				$condition = dateTable_where_order_limit ( $fields );
				
				$output ['sEcho'] = intval ( $_GET ['sEcho'] );
				
				$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->test_paper_model->count_paper_item ( $condition );
				
				$output ['aaData'] = $this->test_paper_model->get_paper_item ( $fields, $condition);
				foreach ( $output ['aaData'] as $item ) {
					$item->state = $this->_get_lists_state ( $item->state );
					$item->operation = '
					<a class="green" href="javascript:;" onclick="pub_alert_html(\'' . $this->zjjp . 'test_paper/edit_paper_group?groupid='.$item->groupid.'&id=' . $item->id . '&s=1\')"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
					<a class="green" href="/master/test/test_paper/paper_item?groupid='.$item->id.'" title="编辑试题项"> <i class="ace-icon fa fa-cog bigger-130"></i></a>
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="red" title="删除"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
				';
				}
				exit ( json_encode ( $output ) );
			}
		
			$this->_view ( 'paper_item' );
	}
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field_item() {
		return array (
				'id',
				'name',
				'correct_value',
				'score',
				'state' 
		);
	}
	/**
	 * [add_paper_group 添加考试组页面]
	 */
	 function add_paper_item(){
	 	$s = intval ( $this->input->get ( 's' ) );
	 	$this->_view ( 'add_paper_item');
	}
	
	/**
	 * [group_item 添加题页]
	 * @return [type] [description]
	 */
	function group_item(){
		$id = intval ( $this->input->get ( 'id' ) );
		$info=array();
		$major_info=array();
		if(!empty($id)){
			$where=" id = {$id}";
			$info = $this->test_paper_model->get_one ( $where );
			if($info->scope_all==0){
				//专业信息
				$major_info=$this->test_paper_model->get_maojor_info($info->degreeid);
			}
			
		}
		//学历信息
		$degree_info=$this->test_paper_model->get_degree_info();
		$group_info=$this->test_paper_model->get_group_info($id);
		$item_info=$this->test_paper_model->get_item_info($group_info);
		$this->_view ( 'group_item',array(
			'degree_info'=>$degree_info,
			'info'=>$info,
			'major_info'=>$major_info,
			'id'=>$id,
			'group_info'=>$group_info,
			'item_info'=>$item_info
			));
		
	}
	/**
	 * [ajax_add_group 添加考试组页面]
	 * @return [type] [html]
	 */
	function ajax_add_item(){
		$s = intval ( $this->input->get ( 's' ) );
		$groupid=intval ( $this->input->get ( 'groupid' ) );
		$jump_id=intval ( $this->input->get ( 'jump_id' ) );
			if (! empty ( $s )) {
				$html = $this->_view ( 'ajax_add_item', array (
					'groupid'=>$groupid,
					'jump_id'=>$jump_id
					), true );
				ajaxReturn ( $html, '', 1 );
			}
	}
	/**
	 * [ajax_add_group 添加考试组页面]
	 * @return [type] [html]
	 */
	function ajax_edit_item(){
		$s = intval ( $this->input->get ( 's' ) );
		$id=intval ( $this->input->get ( 'id' ) );
		$jump_id=intval ( $this->input->get ( 'jump_id' ) );
		$groupid=intval ( $this->input->get ( 'groupid' ) );
			if (! empty ( $s )) {
				$where=" id = {$id}";
	 			$info=$this->test_paper_model->get_one_paper_item($where);
				$html = $this->_view ( 'ajax_add_item', array (
					'info'=>$info,
					'groupid'=>$groupid,
					'jump_id'=>$jump_id
					), true );
				ajaxReturn ( $html, '', 1 );
			}
	}
	//删除
	function ajax_del_item() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$is = $this->test_paper_model->delete_item ( $where );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	/**
	 * [insert_paper_item 插曲试题项]
	 * @return [type] [description]
	 */
	function insert_paper_item(){
		$data=$this->input->post();
			// 判断是否超过大题分数
		if(!empty($data['groupid'])){
			$all_score=$this->test_paper_model->get_group_allscore($data['groupid']);

			$shiji_all_score=$this->test_paper_model->get_item_shiji_allscore($data['groupid']);
			$shiji_all_score=$shiji_all_score+$data['score'];
			if($shiji_all_score>$all_score){
				ajaxReturn ( '', '该小题的分数已经超过该大题的总分数', 2 );
			}
		}
		if(!empty($data['aid'])){
			unset($data['aid']);
		}
		if($data['topic_type']==2){
			$data['more_correct_answer']=json_encode($data['more_correct_answer']);
		}
		if (! empty ( $data )) {
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$id = $this->test_paper_model->save_paper_item ( null, $data );
			if ($id) {
				ajaxReturn ( 'back', '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	//更新
	function update_paper_item() {
		$id = intval ( $this->input->post ( 'id' ) );
		$data=$this->input->post();
		//判断是否超过大题分数
		if(!empty($data['groupid'])){
			$all_score=$this->test_paper_model->get_group_allscore($data['groupid']);
			$shiji_all_score=$this->test_paper_model->get_item_shiji_allscore($data['groupid']);
			//这个题的分
			$item_score=$this->test_paper_model->get_item_one_score($id);
			$shiji_all_score=$shiji_all_score-$item_score;
			$shiji_all_score=$shiji_all_score+$data['score'];
			if($shiji_all_score>$all_score){
				ajaxReturn ( '', '该小题的分数已经超过该大题的总分数', 2 );
			}
		}
		if(!empty($data['aid'])){
			unset($data['aid']);
		}

		if($data['topic_type']==2){
			$data['more_correct_answer']=json_encode($data['more_correct_answer']);
		}
		if ($id) {
			// 保存基本信息
			$this->test_paper_model->save_paper_item ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
}