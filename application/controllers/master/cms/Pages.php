<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * PPT管理
 *
 * @author junjiezhang
 *        
 */
class Pages extends Master_Basic {
	/**
	 * PPT管理
	 *
	 * @var array
	 */
	protected $type;
	protected $bread;
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/cms/';
		$this->load->model ( $this->view . 'pages_model' );
		$columnid = intval ( trim ( $this->input->get_post ( 'columnid' ) ) );
		$this->bread = $this->_set_bread ( $columnid );
		$this->load->vars ( 'bread_1', $this->bread ['bread_1'] );
		$this->load->vars ( 'bread_2', $this->bread ['bread_2'] );
		$this->load->vars ( 'bread_3', $this->bread ['bread_3'] );
	}
	
	/**
	 * 编辑内容
	 */
	function edit() {
		$columnid = intval ( trim ( $this->input->get ( 'columnid' ) ) );
		if (! empty ( $columnid )) {
			$info = $this->pages_model->get_one ( $columnid );
			$this->_view ( 'pages_edit', array (
					'columnid' => $columnid,
					'info' => $info 
			) );
		}
	}
	
	/**
	 * 获取栏目的信息
	 */
	function _set_bread($columnid = null) {
		$data = array ();
		if ($columnid != null) {
			
			// 获取所有栏目
			$column_all = $this->db->select ( 'id,title' )->get_where ( 'column_info', 'id > 0' )->result_array ();
			if (! empty ( $column_all )) {
				foreach ( $column_all as $k => $v ) {
					$column_title [$v ['id']] = $v ['title'];
				}
			}
			// 判断是否有父级
			$column_id_2 = $this->db->select ( 'parent_id' )->get_where ( 'column_info', 'id = ' . $columnid )->row ();
			if (! empty ( $column_id_2->parent_id )) {
				$data ['bread_2'] = ! empty ( $column_title [$column_id_2->parent_id] ) ? $column_title [$column_id_2->parent_id] : '';
				$column_id_3 = $this->db->select ( 'parent_id' )->get_where ( 'column_info', 'id = ' . $column_id_2->parent_id )->row ();
			}
			if (! empty ( $column_id_3->parent_id )) {
				$data ['bread_3'] = ! empty ( $column_title [$column_id_3->parent_id] ) ? $column_title [$column_id_3->parent_id] : '';
				$column_id_4 = $this->db->select ( 'parent_id' )->get_where ( 'column_info', 'id = ' . $column_id_3->parent_id )->row ();
			}
			$data ['bread_1'] = $column_title [$columnid];
		}
		return $data;
	}
	
	/**
	 * 保存信息
	 */
	function pages_save() {
		$data = $this->input->post ();
		$data ['lasttime'] = time ();
		$data ['adminid'] = $this->adminid;
		
		if (! empty ( $data ['columnid'] )) {
			if (! empty ( $data ['createtime'] )) {
				$data ['createtime'] = strtotime ( $data ['createtime'] );
			} else {
				$data ['createtime'] = time ();
			}
			if (empty ( $data ['isvideo'] )) {
				$data ['isvideo'] = 0;
			}
			
			$pages = $this->pages_model->get_one ( $data ['columnid'] );
			if ($pages) {
				$flag = $this->pages_model->save ( $data ['columnid'], $data );
			} else {
				
				$flag = $this->pages_model->save ( null, $data );
			}
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