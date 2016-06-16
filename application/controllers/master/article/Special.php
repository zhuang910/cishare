<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 专题管理
 *
 * @author zhuangqianlin
 *        
 */
class Special extends Master_Basic {
	
	/**
	 * 专题管理
	 *
	 * @var array
	 */
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/article/';
		$this->load->model ( $this->view . 'special_model' );
	}
	
	/**
	 * 首页
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
			$where = 'id > 0';
			
			$like = array ();
			
			$sSearch = mysql_real_escape_string ( $this->input->post ( 'sSearch' ) );
			if (! empty ( $sSearch )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch}%'
				OR
				name LIKE '%{$sSearch}%'
				)
				";
			}
			
			$sSearch_0 = mysql_real_escape_string ( $this->input->post ( 'sSearch_0' ) );
			if (! empty ( $sSearch_0 )) {
				$where .= " AND id LIKE '%{$sSearch_0}%' ";
			}
			
			$sSearch_1 = mysql_real_escape_string ( $this->input->post ( 'sSearch_1' ) );
			if (! empty ( $sSearch_1 )) {
				$where .= " AND name LIKE '%{$sSearch_1}%' ";
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
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->special_model->count( $where );
			$output ['aaData'] = $this->special_model->get( $where, $limit, $offset, $orderby );
			foreach ( $output ['aaData'] as $item ) {
				$item->operation = '
					<div class="btn-group">
					<a href="/master/article/special/edit?&id=' . $item->id . '" class="btn btn-xs btn-info">修改</a>
					<button data-toggle="dropdown" class="btn btn-xs btn-info btn-white dropdown-toggle">
						更多
						<span class="ace-icon fa fa-caret-down icon-only"></span>
					</button>
					<ul class="dropdown-menu dropdown-info dropdown-menu-right">
				';
				$item->operation .= '<li><a href="javascript:;" onclick="del(' . $item->id . ')">删除</a></li></ul></div>';
			}
			exit ( json_encode ( $output ) );
		}
		$this->_view ( 'special_index' );
	}
	
	/**
	 * 编辑内容
	 */
	function edit() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if (! empty ( $id )) {
			$info = $this->special_model->get_one ( $id );
			$p_info = $this->special_model->get_one("{$info->pid}");
			if($p_info) {
				$info->pid_name = $p_info->name;
			}
			
			if (empty ( $info )) {
				ajaxReturn ( '', '该专题不存在', 0 );
			}

		}

		$list = $this->special_model->get();
		$list1 = array();
		foreach ($list as $key=>$va) {
			$list1[$key]['id'] = $va->id;
			$list1[$key]['pId'] = $va->pid;
			$list1[$key]['name'] = $va->name;
		}

		$this->_view ( 'special_add', array (
			'list' => json_encode($list1),
			'info' => $info
		) );
	}
	
	/**
	 * 添加
	 */
	function add() {
		$this->_view ( 'special_add' );
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'pid',
				'name',
				'face_pic',
				'desc',
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
		
		if (! empty ( $id )) {
			$flag = $this->special_model->save ( $id, $data );
		} else {
			$data ['add_time'] = time ();
			$flag = $this->special_model->save ( null, $data );
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
			$is = $this->special_model->delete ( $id );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}

}