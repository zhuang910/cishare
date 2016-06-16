<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 评论管理
 *
 * @author zhuangqianlin
 *        
 */
class Reply extends Master_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/article/';
		$this->load->model ( $this->view . 'reply_model' );
	}
	
	/**
	 * 列表
	 */
	function index() {
		if ($this->input->is_ajax_request () === true) {
            $o = $this->input->post ();
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			// 翻页
			$limit = "";
			$offset = "";
			if (isset ( $_POST ['iDisplayStart'] ) && $_POST ['iDisplayLength'] != '-1') {
				$offset = intval ( $_POST ['iDisplayStart'] );
				$limit = intval ( $_POST ['iDisplayLength'] );
			}
			$where = 'r.reply_id > 0';
			
			$like = array ();
			
			$sSearch = mysql_real_escape_string ( $this->input->post ( 'sSearch' ) );
			if (! empty ( $sSearch )) {
				$where .= "
				AND (
				r.reply_id LIKE '%{$sSearch}%'
				OR
				u.user_name LIKE '%{$sSearch}%'
				OR
				a.title LIKE '%{$sSearch}%'
				OR
				FROM_UNIXTIME(r.`add_time`,'%Y-%m-%d') LIKE '%{$sSearch}%'
				)
				";
			}
			
			$sSearch_0 = mysql_real_escape_string ( $this->input->post ( 'sSearch_0' ) );
			if (! empty ( $sSearch_0 )) {
				$where .= " AND r.reply_id LIKE '%{$sSearch_0}%' ";
			}
			
			$sSearch_1 = mysql_real_escape_string ( $this->input->post ( 'sSearch_1' ) );
			if (! empty ( $sSearch_1 )) {
				$where .= " AND a.title LIKE '%{$sSearch_1}%' ";
			}
			$sSearch_2 = mysql_real_escape_string ( $this->input->post ( 'sSearch_2' ) );
			if (! empty ( $sSearch_2 )) {
				$where .= " AND u.user_name LIKE '%{$sSearch_2}%' ";
			}
			
			$sSearch_3 = mysql_real_escape_string ( $this->input->post ( 'sSearch_3' ) );
			if (! empty ( $sSearch_3 )) {
				$where .= " AND FROM_UNIXTIME(r.`add_time`,'%Y-%m-%d') LIKE '%{$sSearch_3}%' ";
			}
            // 排序
            $orderby = null;
            if (isset ( $_POST ['iSortCol_0'] )) {
                for($i = 0; $i < intval ( $_POST ['iSortingCols'] ); $i ++) {
                    if ($_POST ['bSortable_' . intval ( $_POST ['iSortCol_' . $i] )] == "true") {
                        $orderby = $fields [intval ( $_POST ['iSortCol_' . $i] )] . ' ' . mysql_real_escape_string ( $_POST ['sSortDir_' . $i] );
                    }
                }
            }

			// 输出
			$output ['sEcho'] = intval ( $_POST ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->reply_model->count_ppt ( $where );
			$output ['aaData'] = $this->reply_model->getList ( $where, $limit, $offset, $orderby );

			foreach ( $output ['aaData'] as $item ) {
				$item->content = html_entity_decode($item->content);
				$item->add_time = ! empty ( $item->add_time ) ? date ( 'Y-m-d', $item->add_time ) : '';
				$item->operation = '
				<div class="btn-group">
				<a href="javascript:;" onclick="del(' . $item->reply_id . ')" class="btn btn-xs btn-info">删除</a>
				</div>
				';
			}
			exit ( json_encode ( $output ) );
		}
		$this->_view ( 'reply_index' );
	}

	/**
	 * 删除
	 */
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$is = $this->reply_model->delete ( $id );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'r.reply_id',
				'r.add_time'
		);
	}

}