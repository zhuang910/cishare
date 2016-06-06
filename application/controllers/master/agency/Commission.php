	<?php
	defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
	
	/**
	 * 后台首页
	 *
	 * @author JJ
	 *        
	 */
	class Commission extends Master_Basic {
		protected $programaids_course = array ();
		public $programaid_parent = 0;
		/**
		 * 基础类构造函数
		 */
		function __construct() {
			parent::__construct ();
			$this->view = 'master/agency/';
			$this->load->model ( $this->view . 'commission_model' );
		
		}
		
		/**
		 * 后台主页
		 */
		function index() {
			$label_id=intval(trim($this->input->get('label_id')));
			if(empty($label_id)){
				$label_id=0;
			}
			$agency_id=intval(trim($this->input->get('agency_id')));
			if(empty($agency_id)){
				$agency_id=0;
			}
			if ($this->input->is_ajax_request () === true) {
				// 设置查询字段
				
				$fields = $this->_set_lists_field ();

				$agency_id=intval(trim($this->input->get('agency_id')));
				if(empty($agency_id)){
					$agency_id=0;
				}
				$label_id=intval(trim($this->input->get('label_id')));
				if(empty($label_id)){
					$label_id=0;
				}
				// 查询条件组合

				$condition = dateTable_where_order_limit ( $fields );
				
				$output ['sEcho'] = intval ( $_GET ['sEcho'] );
				
				$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->commission_model->count ( $condition ,$agency_id,$label_id);
				
				$output ['aaData'] = $this->commission_model->get ( $fields, $condition ,$agency_id,$label_id);
				$nationality_arr = CF ( 'public', '', 'data/cache/' );
				$publics = CF ( 'publics', '', CONFIG_PATH );
				foreach ( $output ['aaData'] as $item ) {
                    $item->id = $item->id;
					$item->checkbox='<input type="checkbox" name="sid[]" value="'.$item->id.'">';
					$item->tuition='1';
					$nationality=!empty($item->nationality)?$nationality_arr['global_country_cn'][$item->nationality]:'--';
					$item->nationality=$nationality;
					//获取授课语言
					$item->degree=$this->commission_model->get_student_degree($item->degree);
					$item->language=$publics['language'][$item->language];
					//学费  默认是第学期的学费
					$item->tuition=$this->commission_model->get_student_tuition($item->majorid);
					if($label_id==0){
						$item->commission='<a upload-config="true" data-pk="'.$item->id.'" data-name="commission" href="javascript:;">'.$item->commission.'</a>';
					}
					//缴费状态
					$tuition_state=$this->commission_model->get_tuition_state($item->id);
					if($tuition_state>0){
						$item->tuition_state='<span class="label label-success">已结</span>';
					}else{
						$item->tuition_state='<span class="label label-important">未结</span>';
					}
					$item->operation = '
					<a class="btn btn-xs btn-info"  href="javascript:;" onclick="pub_alert_html(\'' . $this->zjjp . 'commission/outline?aid=' . $item->id . '&s=1\')">备注</a>
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="btn btn-xs btn-info btn-white">删除</a>
				';
				}
				// var_dump($output);die;
				exit ( json_encode ( $output ) );
			}
		
			$this->_view ( 'commission_index' ,array(
					'agency_id'=>$agency_id,
					'label_id'=>$label_id
				));
		}
		/**
		 * 设置列表字段
		 */
		private function _set_lists_field() {
			return array (
                '',
				 "zust_apply_info.id",
				 "zust_apply_info.number",
				 "zust_student_info.chname",
				 "zust_student_info.enname",
				 "zust_student_info.nationality",
				 "zust_student_info.passport",
				 "zust_major.degree",
				 "zust_major.language",
				 "zust_apply_info.tuition",
				 "zust_apply_info.commission",
                '',
                ''
			);
		}
		/**
		 * [edit_commission 编辑字段]
		 * @return [type] [description]
		 */
		function edit_commission(){
			$data=$this->input->post();
			if(!empty($data)){
				$this->commission_model->edit_apply_commission($data);
				ajaxreturn('','',1);
			}
		}
		/**
		 * [insert_record 确认缴费]
		 * @return [type] [description]
		 */
		function insert_record(){
			$aid=intval($this->input->get('aid'));
			if(!empty($aid)){
				$id=$this->commission_model->insert_commission_record($aid);
				if(!empty($id)){
					ajaxreturn('','',1);
				}
			}
			ajaxreturn('','',0);
		}
		/**
		 * [del 删除缴费记录]
		 * @return [type] [description]
		 */
		function del(){
			$aid=intval($this->input->get('aid'));
			if(!empty($aid)){
				$id=$this->commission_model->delete($aid);
				if(!empty($id)){
					ajaxreturn('','',1);
				}
			}
			ajaxreturn('','',0);
		}
	/**
	 * [outline 教学大纲页面]
	 * @return [type] [description]
	 */
	function outline(){
		$aid=intval($this->input->get('aid'));
		$s = intval ( $this->input->get ( 's' ) );
		$info=$this->commission_model->get_commission_info($aid);
		if (! empty ( $s )) {
			// var_dump($mcinfo);exit;
			$html = $this->_view ( 'outline', array (
				'aid'=>$aid,
				'info'=>$info
			), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 * [edit_beizhu 编辑备注]
	 * @return [type] [description]
	 */
	function edit_beizhu(){
		$data=$this->input->post();
		if(!empty($data)){
			$this->commission_model->update_beizhu($data);
			ajaxReturn('','',1);
		}
	}
	/**
	 * [querenjiaofei 更改缴费状态]
	 * @return [type] [description]
	 */
	function querenjiaofei(){
		$data=$this->input->post();
		if(!empty($data['sid'])){
			foreach ($data['sid'] as $k => $v) {
				$yongjin=$this->commission_model->get_apply_yongjin($v);
				$this->commission_model->insert_commission_record($v,$yongjin);
			}
			ajaxreturn('','',1);
		}
	}
}