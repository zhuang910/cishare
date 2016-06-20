<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 权限管理 日志管理
 *
 * @author zhuangqianlin
 *        
 */
class Log extends Admin_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		
		$this->view = 'admin/authority/';
		
		$this->load->model ( $this->view . 'log_model' );
	}
	
	/**
	 * 主页
	 */
	function index() {
		$label_id = $this->input->get ( 'label_id' );
		$label_id = ! empty ( $label_id ) ? $label_id : '1';
		
		// 如果是ajax请求则返回json数据列表
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$condition ['where'] = 'type = ' . $label_id;
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->log_model->count ( $condition );
			$output ['aaData'] = $this->log_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$item->lasttime = ! empty ( $item->lasttime ) ? date ( 'Y-m-d H:i:s', $item->lasttime ) : '';
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'log_index', array (
				'label_id' => $label_id 
		) );
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'title',
				'adminname',
				'lasttime',
				'ip' 
		);
	}
	
	/**
	 * 删除日志
	 */
	function del_log() {
		$type = intval ( $this->input->get ( 'type' ) );
		if ($type) {
			$flag = $this->log_model->delete ( 'type = ' . $type );
			if ($flag) {
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
}

