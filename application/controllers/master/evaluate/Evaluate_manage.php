<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Evaluate_manage extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/evaluate/';
		$this->load->model ( $this->view . 'evaluate_manage_model' );
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		$label_id=$this->input->get('label_id');
		if(empty($label_id)){
			$label_id=1;
		}
		if ($this->input->is_ajax_request () === true) {
				// 设置查询字段
				
				$fields = $this->_set_lists_field ();
				$label_id=$this->input->get('label_id');

				// 查询条件组合
				$condition = dateTable_where_order_limit ( $fields );
				
				$output ['sEcho'] = intval ( $_GET ['sEcho'] );
				
				$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->evaluate_manage_model->count ( $condition,$label_id);
				
				$output ['aaData'] = $this->evaluate_manage_model->get ( $fields, $condition ,$label_id);
				foreach ( $output ['aaData'] as $item ) {
				$item->checkbox='<input type="checkbox" name="sid[]" value="'.$item->userid.'">';
				$item->majorsquad=$this->evaluate_manage_model->get_major_squad($item->id);
				$item->operation = '
					<a class="btn btn-xs btn-info" href="javascript:;" onclick="pub_alert_html(\'' . $this->zjjp . 'evaluate_manage/look_evaluate_schedule/?userid='.$item->userid.'&s=1\')">查看评教进度</a>
				';
					$item->nowterm='第'.$item->nowterm.'学期';
				}
				// var_dump($output);die;
				exit ( json_encode ( $output ) );
			}
		$this->_view ( 'evaluate_manage_index',array(
			'label_id'=>$label_id
			));
	}
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
			return array (
				'zust_student.firstname',
				'zust_student.id',
				'zust_student.enname',
				'zust_student.passport',
				'zust_student.squadid',
				'zust_student.lastname',
				'zust_student.userid'
		);
	}
	
	/**
	 * 获取班级信息
	 */
	function get_squad($majorid) {
		if ($data = $this->evaluate_manage_model->get_squad_info ( $majorid )) {
			if ($this->input->is_ajax_request () === true) {
				ajaxReturn ( $data, '', 1 );
			} else {
				return $data;
			}
		}
		
		ajaxReturn ( '', '该专业没有班级', 0 );
	}
	/**
	 * [send_message 批量发站内信]
	 * @return [type] [description]
	 */
	function send_message(){
		$data=$this->input->post();
		$userid=$this->evaluate_manage_model->get_userid_arr($data['sid']);
		$idstr='';
		$url='/master/student/send_book';
		foreach ($userid as $k => $v) {
			$idstr.=$v.',';
		}
		$this->_view ('customemessage_send',array(
			'ids'=>$userid,
			'idstr'=>$idstr,
			'url'=>$url
			));
	}
	function insert_message(){
		$data=$this->input->post();
		$content=$this->input->post('content');
		$data['sendtime']=time();
		$data['content']=$content;

		$id = $this->evaluate_manage_model->save_message($data);

			if ($id == true) {
			 $this->send_messages($data,$content);

				ajaxReturn('', '添加成功', 1);
			} else {
				ajaxReturn('', '添加失败', 0);
			}
	}
	/**
	 * 自定义发送消息
	 */
	function send_messages($data,$content){
		
		$senttoid=explode(',',$data['sentto']);
		$this->load->library('sdyinc_message');
		foreach ($senttoid as $k => $v) {
			$this->sdyinc_message->custom_message($v,$data['title'],$content);
		}
		ajaxreturn('','操作成功',1);
	}
	/**
	 * [send_email 批量发邮件]
	 * @return [type] [description]
	 */
	function send_email(){
		$data=$this->input->post();
		$emailarr=$this->evaluate_manage_model->get_email_arr($data['sid']);
		$emailstr='';
		$url='/master/student/send_book';
		foreach ($emailarr as $k => $v) {
			$emailstr.=$v.',';
		}
		$adrset=$this->evaluate_manage_model->get_addresserset();
		$this->_view ('customemail_edit',array(
				'addresserset'=>$adrset,
				'emailarr'=> $emailarr,
				'emailstr'=>$emailstr,
				'url'=>$url
			));
	}
	function insert_email(){
		$data=$this->input->post();
		$content=$this->input->post('content');
		$data['sendtime']=time();
		$data['content']=$content;
		$id = $this->evaluate_manage_model->save_email($data);
			if ($id == true) {
				$this->send_emails($data,$content);
				
			} else {
				ajaxReturn('', '添加失败', 0);
			}
	}
	/**
	 * 自定义发送邮件
	 */
	function send_emails($data,$content){
		
		
		$this->load->library('sdyinc_email');
		$senttoid=explode(',',$data['sentto']);
		foreach ($senttoid as $k => $v) {
			$this->sdyinc_email->do_send_mail($v,$data['addresserset'],$data['title'],$content,$data['reply_to']);
		}
		ajaxreturn('','操作成功',1);
	}
	/**
	 * [look_evaluate_schedule 查看学生进度]
	 * @return [type] [description]
	 */
	function look_evaluate_schedule(){
		$s = intval ( $this->input->get ( 's' ) );
		$userid=intval($this->input->get('userid'));
		$course_teacher=$this->evaluate_manage_model->get_course_teacher($userid);
		// var_dump($course_teacher);exit;
		//判断完成情况
		foreach ($course_teacher as $k => $v) {
			$state=$this->evaluate_manage_model->check_student_evaluate($userid,$v);
			if($state>0){
				$course_teacher[$k]['e_state']='已完成';
			}else{
				$course_teacher[$k]['e_state']='未完成';
			}
		}
		if (! empty ( $s )) {
			$html = $this->_view ( 'look_evaluate_schedule', array (
				'course_teacher'=>$course_teacher,
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
}