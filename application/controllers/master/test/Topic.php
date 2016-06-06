	<?php
	defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
	
	/**
	 * 后台首页
	 *
	 * @author JJ
	 *        
	 */
	class Topic extends Master_Basic {
		/**
		 * 基础类构造函数
		 */
		function __construct() {
			parent::__construct ();
			$this->view = 'master/test/';
			$this->load->model ( 'master/test/topic_model' );
		
		}
		
		
	/**
	 * [paper_item 试题项页面]
	 * @return [type] [description]
	 */
	function index(){
		if ($this->input->is_ajax_request () === true) {
				// 设置查询字段
				
				$fields = $this->_set_lists_field_item ();
				// 查询条件组合
				$condition = dateTable_where_order_limit ( $fields );
				
				$output ['sEcho'] = intval ( $_GET ['sEcho'] );
				
				$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->topic_model->count_paper_item ( $condition );
				
				$output ['aaData'] = $this->topic_model->get_paper_item ( $fields, $condition);
				foreach ( $output ['aaData'] as $item ) {
					
					$item->state = $this->_get_lists_state ( $item->state );
					if(!empty($item->topic_type)&&$item->topic_type==1){
						$item->correct_value=$item->one_correct_answer;
					}elseif(!empty($item->topic_type)&&$item->topic_type==2&&!empty($item->more_correct_answer)){
						$item->correct_value=$this->get_more_correct_answer($item->more_correct_answer);
					}
					
					$item->operation = '
					<a class="green" href="/master/test/topic/edit_paper_item?id='.$item->id.'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
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
				'topic_type',
				'one_correct_answer',
				'more_correct_answer',
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
	 * [insert_paper_item 插曲试题项]
	 * @return [type] [description]
	 */
	function insert_paper_item(){
		$data=$this->input->post();
		if(!empty($data['aid'])){
			unset($data['aid']);
		}
		if($data['topic_type']==2){
			$data['more_correct_answer']=json_encode($data['more_correct_answer']);
		}
		if (! empty ( $data )) {
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$id = $this->topic_model->save_paper_item ( null, $data );
			if ($id) {
				ajaxReturn ( 'back', '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
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
		 * [get_more_correct_answer json转换答案]
		 * @param  [type] $json [description]
		 * @return [type]       [description]
		 */
		function get_more_correct_answer($json){
			if(!empty($json)){
				$str='';
				$arr=json_decode($json);
				foreach ($arr as $key => $value) {
					$str.=$value.',';
				}
				return trim($str,',');
			}
			return '';
		}
		/**
	 * [add_paper_group 添加考试组页面]
	 */
	 function edit_paper_item(){
	 	$s = intval ( $this->input->get ( 's' ) );
	 	$id=intval ( $this->input->get ( 'id' ) );
	 	$where=" id = {$id}";
	 	$info=$this->topic_model->get_one_paper_item($where);
		$this->_view ( 'add_paper_item',array(
				'info'=>$info
			));
	}
	//更新
	function update_paper_item() {
		$id = intval ( $this->input->post ( 'id' ) );
		$data=$this->input->post();
		if(!empty($data['aid'])){
			unset($data['aid']);
		}

		if($data['topic_type']==2){
			$data['more_correct_answer']=json_encode($data['more_correct_answer']);
		}
		if ($id) {
			// 保存基本信息
			$this->topic_model->save_paper_item ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	//删除
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$is = $this->topic_model->delete ( $where );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
}