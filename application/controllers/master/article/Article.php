<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 文章管理
 *
 * @author zhuangqianlin
 *        
 */
class Article extends Master_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/article/';
		$this->load->model ( $this->view . 'article_model' );
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
			$where = 'article_id > 0';
			
			$like = array ();
			
			$sSearch = mysql_real_escape_string ( $this->input->post ( 'sSearch' ) );
			if (! empty ( $sSearch )) {
				$where .= "
				AND (
				article_id LIKE '%{$sSearch}%'
				OR
				title LIKE '%{$sSearch}%'
				OR
				FROM_UNIXTIME(`add_time`,'%Y-%m-%d') LIKE '%{$sSearch}%'
		
				)
				";
			}
			
			$sSearch_0 = mysql_real_escape_string ( $this->input->post ( 'sSearch_0' ) );
			if (! empty ( $sSearch_0 )) {
				$where .= " AND article_id LIKE '%{$sSearch_0}%' ";
			}
			
			$sSearch_1 = mysql_real_escape_string ( $this->input->post ( 'sSearch_1' ) );
			if (! empty ( $sSearch_1 )) {
				$where .= " AND title LIKE '%{$sSearch_1}%' ";
			}
			
			$sSearch_4 = mysql_real_escape_string ( $this->input->post ( 'sSearch_4' ) );
			if (! empty ( $sSearch_4 )) {
				$where .= " AND FROM_UNIXTIME(`add_time`,'%Y-%m-%d') LIKE '%{$sSearch_4}%' ";
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
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->article_model->count_ppt ( $where );
			$output ['aaData'] = $this->article_model->getList ( $where, $limit, $offset, $orderby );

			foreach ( $output ['aaData'] as $item ) {
				$show = $item->is_show;
				$item->show = $this->_set_state ( $show );
				$item->operation = '
				<div class="btn-group">
				<a href="/master/article/article/edit?&_id=' . $item->article_id . '" class="btn btn-xs btn-info">修改</a>
			<button data-toggle="dropdown" class="btn btn-xs btn-info btn-white dropdown-toggle">
	    更多
        <span class="ace-icon fa fa-caret-down icon-only"></span>
    </button>
    <ul class="dropdown-menu dropdown-info dropdown-menu-right">
					';
				if ($show == 1) {
					$item->operation .= '<li><a href="javascript:;" onclick="edit_state(' . $item->article_id . ',2)">隐藏</a></li>';
				} else {
					$item->operation .= '<li><a href="javascript:;" onclick="edit_state(' . $item->article_id . ',1)">显示</a></li>';
				}
				$item->operation .= '<li><a href="javascript:;" onclick="del(' . $item->article_id . ')">删除</a></li></ul></div>';
			}
			exit ( json_encode ( $output ) );
		}
		$this->_view ( 'article_index' );
	}
	
	/**
	 * 编辑内容
	 */
	function edit() {
		$id = intval ( trim ( $this->input->get ( '_id' ) ) );
		if (! empty ( $id )) {
			$info = $this->notice_model->get_one ( $id );
			$this->_view ( 'notice_add', array (
					
					'info' => $info 
			) );
		}
	}
	
	/**
	 * 添加ppt
	 */
	function add() {
		$this->_view ( 'notice_add' );
	}
	
	/**
	 * 状态
	 */
	function _set_state($state = 1) {
		$state_array = array (
				'1'=>'显示',
				'2'=>'隐藏'
		);
		return $state_array [$state];
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'article_id',
				'title',
				'is_show',
				'add_time'
		);
	}
	
	/**
	 * 保存信息
	 */
	function save() {
		$data = $this->input->post ();
		if (! empty ( $data ['id'] )) {
			$id = $data ['id'];
		}
		unset ( $data ['id'] );
		$data ['lasttime'] = time ();
		$data ['adminid'] = $this->adminid;
		
		if (empty ( $data ['isjump'] )) {
			$data ['isjump'] = 0;
		}
		if (! empty ( $data ['createtime'] )) {
			$data ['createtime'] = strtotime ( $data ['createtime'] );
		} else {
			$data ['createtime'] = time ();
		}
		
		$data ['site_language'] = $_SESSION ['language'];
		
		if (! empty ( $id )) {
			$flag = $this->notice_model->save ( $id, $data );
		} else {
			$data ['createtime'] = time ();
			$flag = $this->notice_model->save ( null, $data );
		}
		if ($flag) {
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 删除
	 */
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$is = $this->notice_model->delete ( $id );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	
	/**
	 * 修改状态
	 */
	function edit_state() {
		$id = intval ( $this->input->get ( 'id' ) );
		$state = intval ( $this->input->get ( 'state' ) );
		if ($id) {
			$is = $this->notice_model->save ( $id, array (
					'state' => $state 
			) );
			if ($is === true) {
				ajaxReturn ( '', '更新成功', 1 );
			}
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
}