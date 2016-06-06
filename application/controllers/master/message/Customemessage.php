<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 通知邮件配置
 * 
 * @author JJ
 *        
 */
class Customemessage extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/message/';
		$this->load->model($this->view.'customemessage_model');
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
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->customemessage_model->count ( $condition );
			$output ['aaData'] = $this->customemessage_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$item->sendtime=date('Y-m-d',$item->sendtime);
				$item->operation = '

					<a class="btn btn-xs btn-info" href="' . $this->zjjp .'customemessage'. '/select?id=' . $item->id .'">编辑</a>
				';
			}
			//var_dump($output);die;	
			exit ( json_encode ( $output ) );
		}
		$this->_view ('customemessage_index');
	}
	/**
	 * 查看
	 */
	function select(){
		$id=$this->input->get('id');
		$info=$this->customemessage_model->get_one($id);
		$this->_view ('customemessage_select',array(
				'info'=>$info, 
			));
	}
	/**
	 * 编辑
	 */
	function add() {

		$this->_view ('customemessage_send',array(
			));
	}
	function insert(){

		$data=$this->input->post();

		$content=$this->input->post('content');
	
		$data['sendtime']=time();
		$data['content']=$content;
		$id = $this->customemessage_model->save($data);
			if ($id == true) {
		 $this->send_message($data,$content);
				ajaxReturn('', '添加成功', 1);
			} else {
				ajaxReturn('', '添加失败', 0);
			}
	}
	/**
	 * 自定义发送邮件
	 */
	function send_message($data,$content){
		
		$senttoid=explode(',',$data['sentto']);
		$this->load->library('sdyinc_message');
		foreach ($senttoid as $k => $v) {
			$this->sdyinc_message->custom_message($v,$data['title'],$content);
		}
		ajaxreturn('','操作成功',1);
	}
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'title',
				'sendtime',
				
				
		);
	}
}