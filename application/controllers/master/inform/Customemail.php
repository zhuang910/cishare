<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 通知邮件配置
 * 
 * @author JJ
 *        
 */
class Customemail extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/inform/';
		$this->load->model($this->view.'customemail_model');
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
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->customemail_model->count ( $condition );
			$output ['aaData'] = $this->customemail_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$item->sendtime=date('Y-m-d',$item->sendtime);
				$item->operation = '

					<a class="btn btn-xs btn-info" href="' . $this->zjjp .'customemail'. '/select?id=' . $item->id .'">查看</a>
				';
			}
			//var_dump($output);die;	
			exit ( json_encode ( $output ) );
		}
		$this->_view ('customemail_index');
	}
	/**
	 * 查看
	 */
	function select(){
		$id=$this->input->get('id');
		$info=$this->customemail_model->get_one($id);
		$this->_view ('customemail_select',array(
				'info'=>$info, 
			));
	}
	/**
	 * 编辑
	 */
	function add() {

		$adrset=$this->customemail_model->get_addresserset();
		$this->_view ('customemail_edit',array(
				'addresserset'=>$adrset, 
			));
	}
	function insert(){
		$data=$this->input->post();
		$content=$this->input->post('content');
		$data['sendtime']=time();
		$data['content']=$content;
		$id = $this->customemail_model->save($data);
			if ($id == true) {
				$this->send_email($data,$content);
				
			} else {
				ajaxReturn('', '添加失败', 0);
			}
	}
	/**
	 * 自定义发送邮件
	 */
	function send_email($data,$content){
		
		
		$this->load->library('sdyinc_email');
		$senttoid=explode(',',$data['sentto']);
		foreach ($senttoid as $k => $v) {
			$this->sdyinc_email->do_send_mail($v,$data['addresserset'],$data['title'],$content,$data['reply_to']);
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
				'reply_to',
				'sendtime',
				
				
		);
	}
}