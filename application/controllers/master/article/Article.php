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
		$this->load->model ( $this->view . 'category_model' );
		$this->load->model ( $this->view . 'special_model' );
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
			
			$sSearch_2 = mysql_real_escape_string ( $this->input->post ( 'sSearch_2' ) );
			if (! empty ( $sSearch_2 )) {
				$where .= " AND is_show= {$sSearch_2} ";
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
				$item->show = $this->_set_show ( $show );
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
					$item->operation .= '<li><a href="javascript:;" onclick="edit_show(' . $item->article_id . ',2)">隐藏</a></li>';
				} else {
					$item->operation .= '<li><a href="javascript:;" onclick="edit_show(' . $item->article_id . ',1)">显示</a></li>';
				}
				$item->operation .= '<li><a href="javascript:;" onclick="del(' . $item->article_id . ')">删除</a></li></ul></div>';
			}
			exit ( json_encode ( $output ) );
		}
		$this->_view ( 'article_index' );
	}
	
	/**
	 * 添加
	 */
	function add() {
		$type = $this->input->get('type');

		//文章分类
		$cats = $this->category_model->get();
		$cat_list = array();
		foreach ($cats as $key=>$va) {
			$cat_list[$key]['id'] = $va->cat_id;
			$cat_list[$key]['pId'] = $va->pid;
			$cat_list[$key]['name'] = $va->category_name;
		}

		//文章专题
		$special = $this->special_model->get();
		$special_list = array();
		foreach ($special as $skey=>$sva) {
			$special_list[$skey]['id'] = $sva->id;
			$special_list[$skey]['pId'] = $sva->pid;
			$special_list[$skey]['name'] = $sva->name;
		}

		$this->_view ( 'article_add',array (
			'cat_list' => $type == 1 ? json_encode($cat_list) : json_encode($special_list),
			'type' => $type,
		) );

	}

	/**
	 * 编辑
	 */
	function edit() {
		$id = intval ( trim ( $this->input->get ( '_id' ) ) );
		if (! empty ( $id )) {
			//文章分类
			$cats = $this->category_model->get();
			$cat_list = array();
			foreach ($cats as $key=>$va) {
				$cat_list[$key]['id'] = $va->cat_id;
				$cat_list[$key]['pId'] = $va->pid;
				$cat_list[$key]['name'] = $va->category_name;
			}

			//文章专题
			$special = $this->special_model->get();
			$special_list = array();
			foreach ($special as $skey=>$sva) {
				$special_list[$skey]['id'] = $sva->id;
				$special_list[$skey]['pId'] = $sva->pid;
				$special_list[$skey]['name'] = $sva->name;
			}

			$info = $this->article_model->get_one ( $id );
			if($info->type == 1) {
				$cat_info = $this->category_model->get_one("cat_id=".$info->cat_id);
				$cat_name = $cat_info->category_name;
			}
			if($info->type == 2) {
				$cat_info = $this->special_model->get_one($info->cat_id);
				$cat_name = $cat_info->name;
			}

			$this->_view ( 'article_add', array (
				'cat_list' => $info->type == 1 ? json_encode($cat_list) : json_encode($special_list),
				'info' => $info,
				'cat_name' => $cat_name
			) );
		}
	}

	/**
	 * 保存
	 */
	function save() {
		$data = $this->input->post ();
		if (! empty ( $data ['id'] )) {
			$id = $data ['id'];
		}
		unset ( $data ['id'] );

		if (! empty ( $id )) {
			$flag = $this->article_model->save ( $id, $data );
		} else {
			$data ['add_time'] = time ();
			$flag = $this->article_model->save ( null, $data );
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
			$is = $this->article_model->delete ( $id );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	
	/**
	 * 显示类型
	 */
	function _set_show($show = 1) {
		$show_array = array (
				'1'=>'显示',
				'2'=>'隐藏'
		);
		return $show_array [$show];
	}

	/**
	 * 修改显示类型
	 */
	function edit_show() {
		$id = intval ( $this->input->get ( 'id' ) );
		$show = intval ( $this->input->get ( 'show' ) );
		if ($id) {
			$is = $this->article_model->save ( $id, array (
				'is_show' => $show
			) );
			if ($is === true) {
				ajaxReturn ( '', '更新成功', 1 );
			}
		}
		ajaxReturn ( '', '更新失败', 0 );
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

}