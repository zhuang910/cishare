<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * PPT管理
 *
 * @author junjiezhang
 *        
 */
class Server extends Master_Basic {
	/**
	 * PPT管理
	 *
	 * @var array
	 */
	protected $type;
	protected $country;
	protected $purpose;
	protected $bread;
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/cms/';
		$this->load->model ( $this->view . 'server_model' );
		$this->load->model ( $this->view . 'gallery_model' );
		$columnid = intval ( trim ( $this->input->get_post ( 'columnid' ) ) );
		
		// 类型
		$this->type = $this->_get_type ( 1 );
		$this->load->vars ( 'type', $this->type );
		// 国别
		$this->country = $this->_get_type ( 2 );
		$this->load->vars ( 'country', $this->country );
		// 目的
		$this->purpose = $this->_get_type ( 3 );
		$this->load->vars ( 'purpose', $this->purpose );
	}
	
	/**
	 * 首页
	 * 管理ppt
	 * ppt 列表
	 */
	function index() {
		// 获取栏目的id
		$columnid = intval ( trim ( $this->input->get ( 'columnid' ) ) );
		
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			// 翻页
			$limit = "";
			$offset = "";
			if (isset ( $_POST ['iDisplayStart'] ) && $_POST ['iDisplayLength'] != '-1') {
				$offset = intval ( $_POST ['iDisplayStart'] );
				$limit = intval ( $_POST ['iDisplayLength'] );
			}
			
			$where = 'columnid = ' . $columnid;
			
			$like = array ();
			
			$sSearch = mysql_real_escape_string ( $this->input->post ( 'sSearch' ) );
			if (! empty ( $sSearch )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch}%'
				OR
				title LIKE '%{$sSearch}%'
				OR
				type LIKE '%{$sSearch}%'
				OR
				country LIKE '%{$sSearch}%'
				OR
				purpose LIKE '%{$sSearch}%'
				OR
				state LIKE '%{$sSearch}%'
				
		
				)
				";
			}
			
			$sSearch_0 = mysql_real_escape_string ( $this->input->post ( 'sSearch_0' ) );
			if (! empty ( $sSearch_0 )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch_0}%'
				OR
				title LIKE '%{$sSearch_0}%'
				OR
				type LIKE '%{$sSearch_0}%'
				OR
				country LIKE '%{$sSearch_0}%'
				OR
				purpose LIKE '%{$sSearch_0}%'
				OR
				state LIKE '%{$sSearch_0}%'
				
		
				)
				";
			}
			
			$sSearch_1 = mysql_real_escape_string ( $this->input->post ( 'sSearch_1' ) );
			if (! empty ( $sSearch_1 )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch_1}%'
				OR
				title LIKE '%{$sSearch_1}%'
				OR
				type LIKE '%{$sSearch_1}%'
				OR
				country LIKE '%{$sSearch_1}%'
				OR
				purpose LIKE '%{$sSearch_1}%'
				OR
				state LIKE '%{$sSearch_1}%'
				
				)
				";
			}
			$sSearch_2 = mysql_real_escape_string ( $this->input->post ( 'sSearch_2' ) );
			if (! empty ( $sSearch_2 )) {
				$where .= "
				AND (
				type = {$sSearch_2}
				
		
				)
				";
			}
			
			$sSearch_3 = mysql_real_escape_string ( $this->input->post ( 'sSearch_3' ) );
			if (! empty ( $sSearch_3 )) {
				
				$where .= "
				AND (
				
				country = {$sSearch_3}
				)
				";
			}
			
			$sSearch_4 = mysql_real_escape_string ( $this->input->post ( 'sSearch_4' ) );
			if (! empty ( $sSearch_4 )) {
				$where .= "
				AND (
				
				purpose = {$sSearch_4}
				)
				";
			}
			$sSearch_5 = mysql_real_escape_string ( $this->input->post ( 'sSearch_5' ) );
			if (! empty ( $sSearch_5 )) {
				if ($sSearch_5 == - 1) {
					$sSearch_5 = 0;
				}
				$where .= "
				AND (
				
				state = {$sSearch_5}
				)
				";
			}
			// 输出
			$output ['sEcho'] = intval ( $_POST ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->server_model->count_ppt ( $where );
			$output ['aaData'] = $this->server_model->get_ppt ( $where, $limit, $offset, 'orderby DESC' );
			
			foreach ( $output ['aaData'] as $item ) {
				$item->createtime = ! empty ( $item->createtime ) ? date ( 'Y-m-d', $item->createtime ) : '';
				$state = $item->state;
				$item->state = $this->_set_state ( $state );
				$item->type = ! empty ( $item->type ) && ! empty ( $this->type [$item->type] ) ? $this->type [$item->type] : '';
				$item->country = ! empty ( $item->country ) && ! empty ( $this->country [$item->country] ) ? $this->country [$item->country] : '';
				$item->purpose = ! empty ( $item->purpose ) && ! empty ( $this->purpose [$item->purpose] ) ? $this->purpose [$item->purpose] : '';
				$item->operation = '<a title="编辑内容" class="blue" href="/master/cms/server/edit?columnid=' . $item->columnid . '&_id=' . $item->id . '">
					<i class="ace-icon fa fa-pencil bigger-130"></i>
					</a>
						<a title="图集管理" class="blue" href="/master/cms/server/edit_image?columnid=' . $item->columnid . '&_id=' . $item->id . '">
					<i class="ace-icon fa fa-pencil bigger-130"></i>
					</a>
						<a title="删除" class="red" href="javascript:;" onclick="del(' . $item->columnid . ',' . $item->id . ')">
					<i class="ace-icon fa fa-trash-o bigger-130"></i>
					<a title="启用" class="red" href="javascript:;" onclick="edit_state(' . $item->columnid . ',' . $item->id . ',1)">
					<i class="ace-icon fa fa-check green bigger-130"></i>
					</a>/<a title="停用" class="red" href="javascript:;" onclick="edit_state(' . $item->columnid . ',' . $item->id . ',0)">
					<i class="ace-icon glyphicon glyphicon-remove red"></i>
					</a>';
			}
			exit ( json_encode ( $output ) );
		}
		$this->_view ( 'server_index', array (
				'columnid' => $columnid 
		) );
	}
	
	/**
	 * 编辑内容
	 */
	function edit() {
		$columnid = intval ( trim ( $this->input->get ( 'columnid' ) ) );
		$id = intval ( trim ( $this->input->get ( '_id' ) ) );
		if (! empty ( $id )) {
			$info = $this->server_model->get_one ( $id );
			$this->_view ( 'server_add', array (
					'columnid' => $columnid,
					'info' => $info 
			) );
		}
	}
	
	/**
	 * 发布图集
	 */
	function edit_image() {
		$columnid = intval ( trim ( $this->input->get ( 'columnid' ) ) );
		$id = intval ( trim ( $this->input->get ( '_id' ) ) );
		
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field1 ();
			// 翻页
			$limit = "";
			$offset = "";
			if (isset ( $_POST ['iDisplayStart'] ) && $_POST ['iDisplayLength'] != '-1') {
				$offset = intval ( $_POST ['iDisplayStart'] );
				$limit = intval ( $_POST ['iDisplayLength'] );
			}
			
			$where = 'istrue = 1 AND columnid = ' . $id;
			
			$like = array ();
			
			$sSearch = mysql_real_escape_string ( $this->input->post ( 'sSearch' ) );
			if (! empty ( $sSearch )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch}%'
				OR
				title LIKE '%{$sSearch}%'
				OR
				orderby LIKE '%{$sSearch}%'
				OR
				state LIKE '%{$sSearch}%'
				OR
				FROM_UNIXTIME(`createtime`,'%Y-%m-%d') LIKE '%{$sSearch}%'
		
				)
				";
			}
			
			$sSearch_0 = mysql_real_escape_string ( $this->input->post ( 'sSearch_0' ) );
			if (! empty ( $sSearch_0 )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch_0}%'
				OR
				title LIKE '%{$sSearch_0}%'
				OR
				orderby LIKE '%{$sSearch_0}%'
				OR
				state LIKE '%{$sSearch_0}%'
				OR
				FROM_UNIXTIME(`createtime`,'%Y-%m-%d') LIKE '%{$sSearch_0}%'
		
				)
				";
			}
			
			$sSearch_1 = mysql_real_escape_string ( $this->input->post ( 'sSearch_1' ) );
			if (! empty ( $sSearch_1 )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch_1}%'
				OR
				title LIKE '%{$sSearch_1}%'
				OR
				orderby LIKE '%{$sSearch_1}%'
				OR
				state LIKE '%{$sSearch_1}%'
				OR
				FROM_UNIXTIME(`createtime`,'%Y-%m-%d') LIKE '%{$sSearch_1}%'
		
				)
				";
			}
			$sSearch_2 = mysql_real_escape_string ( $this->input->post ( 'sSearch_2' ) );
			if (! empty ( $sSearch_2 )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch_2}%'
				OR
				title LIKE '%{$sSearch_2}%'
				OR
				orderby LIKE '%{$sSearch_2}%'
				OR
				state LIKE '%{$sSearch_2}%'
				OR
				FROM_UNIXTIME(`createtime`,'%Y-%m-%d') LIKE '%{$sSearch_2}%'
		
				)
				";
			}
			
			$sSearch_3 = mysql_real_escape_string ( $this->input->post ( 'sSearch_3' ) );
			if (! empty ( $sSearch_3 )) {
				if ($sSearch_3 == - 1) {
					$sSearch_3 = 0;
				}
				$where .= "
				AND (
				
				state = {$sSearch_3}
				)
				";
			}
			
			$sSearch_4 = mysql_real_escape_string ( $this->input->post ( 'sSearch_4' ) );
			if (! empty ( $sSearch_4 )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch_4}%'
				OR
				title LIKE '%{$sSearch_4}%'
				OR
				orderby LIKE '%{$sSearch_4}%'
				OR
				state LIKE '%{$sSearch_4}%'
				OR
				FROM_UNIXTIME(`createtime`,'%Y-%m-%d') LIKE '%{$sSearch_4}%'
		
				)
				";
			}
			// 输出
			$output ['sEcho'] = intval ( $_POST ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->gallery_model->count_ppt ( $where );
			$output ['aaData'] = $this->gallery_model->get_ppt ( $where, $limit, $offset, 'orderby DESC' );
			
			foreach ( $output ['aaData'] as $item ) {
				$item->createtime = ! empty ( $item->createtime ) ? date ( 'Y-m-d', $item->createtime ) : '';
				$state = $item->state;
				$item->state = $this->_set_state ( $state );
				$item->operation = '<a title="编辑" class="blue" href="/master/cms/server/editgallery?columnid=' . $columnid . '&_id=' . $item->id . '">
					<i class="ace-icon fa fa-pencil bigger-130"></i>
					</a>
						<a title="删除" class="red" href="javascript:;" onclick="del(' . $columnid . ',' . $item->id . ')">
					<i class="ace-icon fa fa-trash-o bigger-130"></i>
					<a title="启用" class="red" href="javascript:;" onclick="edit_state(' . $columnid . ',' . $item->id . ',1)">
					<i class="ace-icon fa fa-check green bigger-130"></i>
					</a>/<a title="停用" class="red" href="javascript:;" onclick="edit_state(' .$columnid . ',' . $item->id . ',0)">
					<i class="ace-icon glyphicon glyphicon-remove red"></i>
					</a>';
			}
			exit ( json_encode ( $output ) );
		}
		$this->_view ( 'server_edit_image', array (
				'columnid' => $columnid,
				'_id' => $id 
		) );
	}
	
	/**
	 * 编辑ppt
	 */
	function editgallery() {
		// 获取栏目的id
		$columnid = intval ( trim ( $this->input->get ( 'columnid' ) ) );
		// 获取栏目的id
		$id = intval ( trim ( $this->input->get ( '_id' ) ) );
		if ($columnid && $id) {
			$info = $this->gallery_model->get_one ( $id );
			$this->_view ( 'server_addgallery', array (
					'columnid' => $columnid,
					'info' => $info
			) );
		}
	}
	
	/**
	 * 删除
	 */
	function del_image() {
		$columnid = intval ( $this->input->get ( 'columnid' ) );
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$is = $this->gallery_model->delete ( $id );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	

	/**
	 * 修改状态
	 */
	function edit_state_image() {
		$columnid = intval ( $this->input->get ( 'columnid' ) );
		$id = intval ( $this->input->get ( 'id' ) );
		$state = intval ( $this->input->get ( 'state' ) );
		if ($id) {
			$is = $this->gallery_model->save ( $id, array (
					'state' => $state
			) );
			if ($is === true) {
				ajaxReturn ( '', '更新成功', 1 );
			}
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	
	/**
	 * 添加ppt
	 */
	function add() {
		
		// 获取栏目的id
		$columnid = intval ( trim ( $this->input->get ( 'columnid' ) ) );
		
		if ($columnid) {
			$this->_view ( 'server_add', array (
					'columnid' => $columnid 
			) );
		}
	}
	
	/**
	 * 添加ppt
	 */
	function addgallery() {
		// 获取栏目的id
		$columnid = intval ( trim ( $this->input->get ( 'columnid' ) ) );
		
		$id = intval ( trim ( $this->input->get ( '_id' ) ) );
		if ($columnid) {
			$this->_view ( 'server_addgallery', array (
					'columnid' => $columnid,
					'_id' => $id 
			) );
		}
	}
	
	/**
	 * 保存信息
	 */
	function save_image() {
		$data = $this->input->post ();
		if (! empty ( $data ['id'] )) {
			$id = $data ['id'];
		}
		unset ( $data ['id'] );
		$data ['lasttime'] = time ();
		$data ['adminid'] = $this->adminid;
		$data ['istrue'] = 1;
		unset ( $data ['columnid'] );
		if (! empty ( $data ['_id'] )) {
			$data ['columnid'] = $data ['_id'];
		}
		unset ( $data ['_id'] );
		
		if (! empty ( $id )) {
			$flag = $this->gallery_model->save ( $id, $data );
		} else {
			$data ['createtime'] = time ();
			$flag = $this->gallery_model->save ( null, $data );
		}
		if ($flag) {
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 状态
	 */
	function _set_state($state = 0) {
		$state_array = array (
				'停用',
				'启用' 
		);
		return $state_array [$state];
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
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'title',
				'orderby',
				'state',
				'createtime',
				'purpose',
				'type',
				'country' 
		);
	}
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field1() {
		return array (
				'id',
				'title',
				'orderby',
				'state',
				'createtime' 
		);
	}
	
	/**
	 * 保存信息
	 */
	function save() {
		$data = $this->input->post ();
		unset($data['aid']);
		if (! empty ( $data ['id'] )) {
			$id = $data ['id'];
		}
		unset ( $data ['id'] );
		$data ['lasttime'] = time ();
		$data ['adminid'] = $this->adminid;
		if (empty ( $data ['isvideo'] )) {
			$data ['isvideo'] = 0;
		}
		if (empty ( $data ['isindex'] )) {
			$data ['isindex'] = 0;
		}
		if (empty ( $data ['isjump'] )) {
			$data ['isjump'] = 0;
		}
		if (! empty ( $data ['createtime'] )) {
			$data ['createtime'] = strtotime ( $data ['createtime'] );
		} else {
			$data ['createtime'] = time ();
		}
		$data ['groupid'] = $_SESSION ['master_user_info']->groupid;
		$this->load->library ( 'py_class' );
		$py = new py_class ();
		$pinyin = mb_substr ( $py->str2py ( $data ['title'] ), 0, 1, 'utf-8' );
		$data ['letter'] = strtoupper ( $pinyin );
		;
		if (! empty ( $id )) {
			$flag = $this->server_model->save ( $id, $data );
		} else {
			$data ['createtime'] = time ();
			$flag = $this->server_model->save ( null, $data );
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
		$columnid = intval ( $this->input->get ( 'columnid' ) );
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$is = $this->server_model->delete ( $id );
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
		$columnid = intval ( $this->input->get ( 'columnid' ) );
		$id = intval ( $this->input->get ( 'id' ) );
		$state = intval ( $this->input->get ( 'state' ) );
		if ($id) {
			$is = $this->server_model->save ( $id, array (
					'state' => $state 
			) );
			if ($is === true) {
				ajaxReturn ( '', '更新成功', 1 );
			}
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	
	/**
	 * 从类别中获取 内容
	 */
	function _get_type($p = null) {
		$data = array ();
		if ($p != null) {
			$data_all = $this->db->select ( '*' )->get_where ( 'category_info', 'state = 1 AND parent_id = ' . $p )->result_array ();
			if (! empty ( $data_all )) {
				foreach ( $data_all as $k => $v ) {
					$data [$v ['id']] = $v ['title'];
				}
			}
		}
		return $data;
	}
}