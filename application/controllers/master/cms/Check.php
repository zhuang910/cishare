<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * PPT管理
 *
 * @author junjiezhang
 *        
 */
class Check extends Master_Basic {
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
		$this->load->model ( $this->view . 'article_model' );
	}
	
	/**
	 * 首页
	 * 管理ppt
	 * ppt 列表
	 */
	function index() {
		$label_id = intval ( trim ( $this->input->get ( 'label_id' ) ) );
		if (empty ( $label_id )) {
			$label_id = 0;
		}
		
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
			$where = 'columnid IN (15,16) AND ischeck = ' . $label_id;
			
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
				
				columnid = {$sSearch_2}
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
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->article_model->count_ppt ( $where );
			$output ['aaData'] = $this->article_model->get_ppt ( $where, $limit, $offset, 'orderby DESC' );
			$a = array (
					'15' => 'News',
					'16' => 'Events' 
			);
			foreach ( $output ['aaData'] as $item ) {
				$item->createtime = ! empty ( $item->createtime ) ? date ( 'Y-m-d', $item->createtime ) : '';
				$state = $item->state;
				$item->state = $this->_set_state ( $state );
				$columnid = $item->columnid;
				$item->columnid = $a [$columnid];
				$item->checktime = ! empty ( $item->checktime ) ? date ( 'Y-m-d H:i:s', $item->checktime ) : '';
				$item->checkadmin = $this->_checkadmin ( $item->checkadmin );
				$item->operation = '<a title="预览" class="blue" href="#">
					<i class="ace-icon fa fa-pencil bigger-130"></i>
					</a>
						
					<a title="通过" class="red" href="javascript:;" onclick="edit_state(' . $item->id . ',1)">
					<i class="ace-icon fa fa-check green bigger-130"></i>
					</a>/<a title="不通过" class="red" href="javascript:;" onclick="edit_state(' . $item->id . ',2)">
					<i class="ace-icon glyphicon glyphicon-remove red"></i>
					</a>';
			}
			exit ( json_encode ( $output ) );
		}
		$this->_view ( 'check_index', array (
				'label_id' => $label_id 
		) );
	}
	function _checkadmin($id = null) {
		if ($id != null) {
			$a = $this->db->select ( '*' )->get_where ( 'admin_info', 'id = ' . $id )->row ();
			if(!empty($a->username)){
				return $a->username;
			}else{
				return '';
			}
		}else{
			return '';
		}
	}
	
	/**
	 * 获取权限
	 */
	function _get_power() {
		$g = $this->db->select ( '*' )->get_where ( 'group_info', 'id = ' . $_SESSION ['master_user_info']->groupid )->row ();
		if ($g->is_read == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 编辑内容
	 */
	function edit() {
		$columnid = intval ( trim ( $this->input->get ( 'columnid' ) ) );
		$id = intval ( trim ( $this->input->get ( '_id' ) ) );
		if (! empty ( $id )) {
			$info = $this->article_model->get_one ( $id );
			$this->_view ( 'article_add', array (
					'columnid' => $columnid,
					'info' => $info 
			) );
		}
	}
	
	/**
	 * 添加ppt
	 */
	function add() {
		
		// 获取栏目的id
		$columnid = intval ( trim ( $this->input->get ( 'columnid' ) ) );
		
		if ($columnid) {
			$this->_view ( 'article_add', array (
					'columnid' => $columnid 
			) );
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
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'title',
				'orderby',
				'state',
				'createtime',
				'columnid',
				'checktime',
				'checkadmin' 
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
		if (! empty ( $id )) {
			$flag = $this->article_model->save ( $id, $data );
		} else {
			$data ['createtime'] = time ();
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
		$columnid = intval ( $this->input->get ( 'columnid' ) );
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
	 * 修改状态
	 */
	function edit_state() {
		$id = intval ( $this->input->get ( 'id' ) );
		$state = intval ( $this->input->get ( 'state' ) );
		if ($id) {
			$is = $this->article_model->save ( $id, array (
					'ischeck' => $state,
					'checktime' => time (),
					'checkadmin' => $this->adminid 
			) );
			if ($is === true) {
				ajaxReturn ( '', '更新成功', 1 );
			}
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
}