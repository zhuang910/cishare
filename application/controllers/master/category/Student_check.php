<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 *在学 管理 考勤提醒
 *
 * @author zyj
 *        
 */
class Student_check extends Master_Basic {
	/**
	 * 文章管理
	 *
	 * @var array
	 */

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/student/';
		
		$this->load->model ( $this->view . 'student_check_model' );
	}
	
	/**
	 * 主页
	 */
	function index() {
		
		// 获得语言的id
		$label_id = $this->input->get ( 'label_id' );
		if (empty ( $label_id )) {
			$label_id =0;
		}
		
		// 如果是ajax请求则返回json数据列表
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$condition ['where'] ['site_language'] = $label_id;
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->student_check_model->count ( $condition );
			$output ['aaData'] = $this->student_check_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				
				$item->state = $this->_get_lists_state ( $item->state );
				$item->createtime = date ( 'Y-m-d H:i:s', $item->createtime );
				$item->operation = '
					<a href="/master/cms/news/edit?id=' . $item->id . '&label_id=' . $item->site_language . '" class="green"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
				';
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'student_check_index', array (
				'label_id' => $label_id 
		) );
	}
	/**
	 *查看即将通知的学生
	 **/
	function student_checking(){
			// 如果是ajax请求则返回json数据列表
			// 获得语言的id
			$label_id = $this->input->get ( 'label_id' );
			if (empty ( $label_id )) {
				$label_id =0;
			}
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			$label_id=$this->input->get('label_id');
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->student_check_model->count_checking ( $condition,$label_id);
			$output ['aaData'] = $this->student_check_model->get_checking ( $fields, $condition ,$label_id);
			foreach ( $output ['aaData'] as $item ) {
				$item->check_num=$this->student_check_model->get_stu_num($item->id);
				$item->majorid=$this->student_check_model->get_majorname($item->majorid);
				$item->squadid=$this->student_check_model->get_squadname($item->squadid);
				$item->operation = '
					<a href="javascript:pub_alert_html(\'/master/student/student_check/look_checking?id=' . $item->id  . '\');" class="blue" title="查看详细考勤">
					<i class="ace-icon fa fa-pencil bigger-130"></i>
					</a>
				';
			}
			exit ( json_encode ( $output ) );
		}
		$this->_view ( 'student_checking_notice', array (
			'label_id'=>$label_id,
		) );
	}
	/**
	 *
	 *查看签证到期的学生
	 **/
	function student_visaend(){
			// 如果是ajax请求则返回json数据列表
			// 获得语言的id
			$label_id = $this->input->get ( 'label_id' );
			if (empty ( $label_id )) {
				$label_id =2;
			}
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_fields ();
			$label_id=$this->input->get('label_id');
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->student_check_model->count_visaend_s ( $condition,$label_id);
			
			$output ['aaData'] = $this->student_check_model->get_visaend_s ( $fields, $condition ,$label_id);
			
			foreach ( $output ['aaData'] as $item ) {
				$studentid = $item->studentid;
				//获取 个人信息
				$item->studentid = $this->get_student_info($studentid);
				$item->visatime=date('Y-m-d',$item->visatime);
			
			}
			exit ( json_encode ( $output ) );
		}
		$this->_view ( 'student_visaend_notice', array (
			'label_id'=>$label_id,
		) );
	}
	
	function get_student_info($id = null){
		$data = '';
		if($id != null){
			//姓名
			$student = $this->db->get_where('student','id = '.$id)->row();
			if(!empty($student)){
				$data .= '姓名：';
				$data .= !empty($student->enname)?$student->enname:'';
				//专业
				$major = $this->db->get_where('major','id = '.$student->majorid)->row();
				$data .= '<br />专业：';
				$data .= !empty($major->name)?$major->name:'';
			}
		}
		return $data;
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'studentid',
				'visanumber',
				'visatime',
				'squadid',
		);
	}
	/**
	 * 设置列表字段
	 */
	private function _set_lists_fields() {
		return array (
				'id',
				'name',
				'enname',
				'majorid',
				'squadid',
				'visaendtime',
		);
	}
	/**
	 * [look_checking 查看详细考勤]
	 * @return [type] [description]
	 */
	function look_checking(){
		$id=$this->input->get('id');
		//查看该学生的考勤详细
		$info=$this->student_check_model->get_student_checking($id);
		$html = $this->_view ( 'look_checking', array (
			'info'=>$info,
		), true );
		ajaxReturn ( $html, '', 1 );
	}
}

