	<?php
	defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
	
	/**
	 * 后台首页
	 *
	 * @author JJ
	 *        
	 */
	class Examination extends Master_Basic {
		protected $programaids_course = array ();
		public $programaid_parent = 0;
		/**
		 * 基础类构造函数
		 */
		function __construct() {
			parent::__construct ();
			$this->view = 'master/test/';
			$this->load->model ( $this->view . 'examination_model' );
		
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
				
				$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->examination_model->count ( $condition );
				
				$output ['aaData'] = $this->examination_model->get ( $fields, $condition );
				
				foreach ( $output ['aaData'] as $item ) {
					// $item->checkbox='<input type="checkbox" name="sid[]" value="'.$item->id.'">';
					$num = $this->examination_model->get_paper_num($item->id);
					$item->num='<a href="/master/test/examination/studennt_ex?paperid='.$item->id.'" >'.$num.'</a>';
				}
				exit ( json_encode ( $output ) );
			}
		
			$this->_view ( 'examination_index' );
		}
		/**
		 * 设置列表字段
		 */
		private function _set_lists_field() {
			return array (
					'id',
					'name',
					'enname',
					'scope_all',
					'state' 
			);
		}
		function studennt_ex(){
			$paperid=intval($this->input->get('paperid'));
			if ($this->input->is_ajax_request () === true) {
				// 设置查询字段
				
				$fields = $this->_set_lists_field_ex ();
				$paperid=intval($this->input->get('paperid'));
				// 查询条件组合
				$condition = dateTable_where_order_limit ( $fields );
				
				$output ['sEcho'] = intval ( $_GET ['sEcho'] );
				
				$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->examination_model->count_ex ( $condition ,$paperid);
				
				$output ['aaData'] = $this->examination_model->get_ex ( $fields, $condition ,$paperid);
				
				foreach ( $output ['aaData'] as $item ) {
					$item->checkbox='<input type="checkbox" name="sid[]" value="'.$item->sid.'">';
					$num = $this->examination_model->get_paper_num($item->id);
					$item->paperid=$this->examination_model->get_paper_name($item->paperid);
					if(!empty($item->finish_state)){
						$item->finish_state=$item->finish_state;
					}
					
					if(!empty($item->time)){
						$item->time=date('Y-m-d H:i:s',$item->time);
					}
					$item->num='<a href="/master/test/examination/studennt_ex?paperid='.$item->id.'" >'.$num.'</a>';
				}
				exit ( json_encode ( $output ) );
			}
		
			$this->_view ( 'student_ex_index' ,array(
					'paperid'=>$paperid
				));
		}
		/**
		 * 设置列表字段
		 */
		private function _set_lists_field_ex() {
			return array (
					'zust_examination_info.id',
					'zust_student.name',
					'zust_student.userid',
					'zust_student.passport',
					'zust_examination_info.paperid',
					'zust_examination_info.score',
					'zust_examination_info.finish_state',
					'zust_examination_info.used_time',
					'zust_examination_info.time',
			);
		}
}