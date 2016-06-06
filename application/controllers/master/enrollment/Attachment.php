<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
header ( 'Content-Type: text/html; charset=utf8' );
/**
 *
 * @name 申请表管理
 * @package Citys
 * @author cucas Team [zyj]
 * @copyright Copyright (c) 2014-1-06, cucas
 */
class Attachment extends Master_Basic {
	function __construct() {
		parent::__construct ();
		$this->view = 'master/enrollment/';
		$this->load->model ( $this->view . 'attachment_model' );
	}
	/**
	 * 模版管理
	 */
	function index() {
		$where_t = 'atta_id > 0';
		
		$lists = $this->attachment_model->get_attachment_info ( $where_t );
		if (! empty ( $lists )) {
			foreach ( $lists as $k => $v ) {
				// 查询模版中项的数量
				$where_page = 'atta_id = ' . $v ['atta_id'];
				$count = $this->attachment_model->get_attachmentitem_count ( $where_page );
				$lists [$k] ['count'] = ! empty ( $count ) ? $count : 0;
			}
		}
		$this->_view ( 'attachment_index', array (
				'lists' => $lists 
		) );
	}
	
	/**
	 * 新建附件模版
	 */
	function attachment_add() {
		$atta_id = intval ( trim ( $this->input->get ( 'atta_id' ) ) );
		
		if (! empty ( $atta_id )) {
			$result = $this->attachment_model->get_attachment_info ( 'atta_id = ' . $atta_id );
		}
		$this->_view ( 'attachment_attachment_add', array (
				'result' => ! empty ( $result ) ? $result [0] : array () 
		) );
	}
	
	/**
	 * 保存附件模版
	 */
	function attachment_save() {
		$data = $this->input->post ();
		$atta_id = ! empty ( $data ['atta_id'] ) ? $data ['atta_id'] : '';
		unset ( $data ['tClass_id'] );
		if (empty ( $data ['aKind'] )) {
			$data ['aKind'] = 'N';
		}
		if (! empty ( $atta_id )) {
			// 更新数据
			$flag = $this->attachment_model->save_attachment ( 'atta_id = ' . $atta_id, $data );
		} else {
			// 保存数据
			
			$flag = $this->attachment_model->save_attachment ( null, $data );
		}
		if ($flag) {
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 删除模版
	 */
	function attachment_del() {
		$atta_id = intval ( trim ( $this->input->get ( 'atta_id' ) ) );
		if (! empty ( $atta_id )) {
			$flag = $this->attachment_model->del_attachment ( 'atta_id = ' . $atta_id );
			if ($flag) {
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 全局项列表
	 */
	function attachment_item() {
		$where = 'atta_id = 1';
		$lists = $this->attachment_model->get_attachmentstopic_info ( $where );
		$this->_view ( 'attachment_attachment_item', array (
				'lists' => $lists 
		) );
	}
	
	/**
	 * 添加 编辑 全局项
	 */
	function attachment_add_item() {
		$aTopic_id = intval ( trim ( $this->input->get ( 'aTopic_id' ) ) );
		
		if (! empty ( $aTopic_id )) {
			$result = $this->attachment_model->get_attachmentstopic_info ( 'aTopic_id = ' . $aTopic_id );
		}
		$this->_view ( 'attachment_attachment_add_item', array (
				'result' => ! empty ( $result ) ? $result [0] : array () 
		) );
	}
	
	/**
	 * 保存全局项
	 */
	function attachment_item_save() {
		$data = $this->input->post ();
		$aTopic_id = ! empty ( $data ['aTopic_id'] ) ? $data ['aTopic_id'] : '';
		unset ( $data ['aTopic_id'] );
		
		if (! empty ( $aTopic_id )) {
			// 更新数据
			$flag = $this->attachment_model->save_attachmentstopic ( 'aTopic_id = ' . $aTopic_id, $data );
		} else {
			// 保存数据
			$data ['atta_id'] = 1;
			$flag = $this->attachment_model->save_attachmentstopic ( null, $data );
		}
		if ($flag) {
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 删除附件项
	 */
	function attachment_item_del() {
		$aTopic_id = intval ( trim ( $this->input->get ( 'aTopic_id' ) ) );
		if (! empty ( $aTopic_id )) {
			$flag = $this->attachment_model->del_attachmentstopic ( 'aTopic_id = ' . $aTopic_id );
			if ($flag) {
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 查询 模版 下的项
	 */
	function attachment_mb_item() {
		$atta_id = intval ( trim ( $this->input->get ( 'atta_id' ) ) );
		
		// 查询模版下的项
		$where = 'atta_id = ' . $atta_id;
		// 查询模版名称
		$name = $this->attachment_model->get_attachment_info ( $where );
		$lists = $this->attachment_model->get_attachmentstopic_info ( $where );
		$this->_view ( 'attachment_mb_item', array (
				'lists' => $lists,
				'name' => ! empty ( $name ) ? $name [0] ['AttaName'] : '',
				'atta_id' => ! empty ( $atta_id ) ? $atta_id : '' 
		) );
	}
	
	/**
	 * 编辑 模版下的项
	 */
	function attachment_mb_edititem() {
		$aTopic_id = intval ( trim ( $this->input->get ( 'aTopic_id' ) ) );
		$atta_id = intval ( trim ( $this->input->get ( 'atta_id' ) ) );
		$where = 'atta_id = ' . $atta_id;
		$name = $this->attachment_model->get_attachment_info ( $where );
		if (! empty ( $aTopic_id )) {
			$result = $this->attachment_model->get_attachmentstopic_info ( 'aTopic_id = ' . $aTopic_id );
		}
		$this->_view ( 'attachment_mb_edititem', array (
				'result' => ! empty ( $result ) ? $result [0] : array (),
				'name' => ! empty ( $name ) ? $name [0] ['AttaName'] : '' 
		) );
	}
	
	/**
	 * 保存模版下的项
	 */
	function attachment_mb_saveitem() {
		$data = $this->input->post ();
		$aTopic_id = ! empty ( $data ['aTopic_id'] ) ? $data ['aTopic_id'] : '';
		unset ( $data ['aTopic_id'] );

		if (! empty ( $aTopic_id )) {
			// 更新数据
			$flag = $this->attachment_model->save_attachmentstopic ( 'aTopic_id = ' . $aTopic_id, $data );
		} else {
			// 保存数据
			
			$flag = $this->attachment_model->save_attachmentstopic ( null, $data );
		}
		if ($flag) {
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 删除模版下的项
	 */
	function attachment_mb_delitem() {
		$aTopic_id = intval ( trim ( $this->input->get ( 'aTopic_id' ) ) );
		if (! empty ( $aTopic_id )) {
			$flag = $this->attachment_model->del_attachmentstopic ( 'aTopic_id = ' . $aTopic_id );
			if ($flag) {
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 模版下 请求 全局项
	 */
	function attachment_mb_ajaxitem() {
		$atta_id = intval ( trim ( $this->input->get ( 'atta_ids' ) ) );
		$where = 'atta_id = ' . $atta_id;
		// 查询模版名称
		$name = $this->attachment_model->get_attachment_info ( $where );
		$lists = $this->attachment_model->get_attachmentstopic_info ( $where );
		
		if (! empty ( $lists )) {
			foreach ( $lists as $key => $val ) {
				$parent_id [] = $val ['parent_id'];
			}
		}
		
		// 查询全局项
		$where_g = 'atta_id = 1';
		
		if (isset ( $parent_id ) && ! empty ( $parent_id )) {
			$where_g .= ' AND aTopic_id NOT IN (' . implode ( ',', $parent_id ) . ')';
		}
		
		$global = $this->attachment_model->get_attachmentstopic_info ( $where_g );
		$html = $this->_view ( 'attachment_mb_ajaxitem', array (
				'global' => ! empty ( $global ) ? $global : array (),
				'atta_id' => ! empty ( $atta_id ) ? $atta_id : '' 
		), true );
		ajaxReturn ( $html, '', 1 );
	}
	
	/**
	 * 向模版中添加项
	 */
	function attachment_mb_savembitem() {
		$data = $this->input->post ();
		if (! empty ( $data ['aTopic_id'] )) {
			$aTopic_id = $data ['aTopic_id'];
		}
		if (! empty ( $data ['atta_id'] )) {
			$atta_id = $data ['atta_id'];
		}
		if (! empty ( $aTopic_id ) && ! empty ( $atta_id )) {
			foreach ( $aTopic_id as $k => $v ) {
				$result = $this->attachment_model->get_attachmentstopic_info ( 'aTopic_id = ' . $v );
				if (! empty ( $result )) {
					$r = $result [0];
					unset ( $r ['aTopic_id'] );
					$r ['atta_id'] = $atta_id;
					$r ['parent_id'] = $v;
					$flag = $this->attachment_model->save_attachmentstopic ( null, $r );
				}
			}
			if (! empty ( $flag )) {
				ajaxReturn ( '', '添加成功', 1 );
			} else {
				ajaxReturn ( '', '添加失败！', 0 );
			}
		} else {
			ajaxReturn ( '', '添加失败！', 0 );
		}
	}
}