<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * PPT管理
 *
 * @author junjiezhang
 *        
 */
class Img extends Master_Basic {
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
		$this->load->model ( $this->view . 'img_model' );
		$this->load->model ( $this->view . 'pages_model' );
	}
	
	/**
	 * 首页
	 * 管理图集
	 * 图集 列表
	 */
	function index() {
		// 获取栏目的id
		$label_id =$_SESSION['language'];
		$column_info=$this->img_model->get_news_colum();
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
			$like = array ();
			$where='site_language="'.$label_id.'"';
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
			
			$sSearch_0 = mysql_real_escape_string ( $this->input->get ( 'sSearch_0' ) );
			if (! empty ( $sSearch_0 )) {
				$where .= "
				AND (
				id = '{$sSearch_0}'
				)
				";
			}
			
			$sSearch_1 = mysql_real_escape_string ( $this->input->get ( 'sSearch_1' ) );
			if (! empty ( $sSearch_1 )) {
				$where .= "
				AND (
				title LIKE '%{$sSearch_1}%'
				)
				";
			}
			$sSearch_2 = mysql_real_escape_string ( $this->input->get ( 'sSearch_2' ) );
			if (! empty ( $sSearch_2 )) {
				$where .= "
				AND (
				orderby LIKE '%{$sSearch_2}%'
				)
				";
			}
			
			$sSearch_3 = mysql_real_escape_string ( $this->input->get ( 'sSearch_3' ) );
			if (! empty ( $sSearch_3 )) {
				if ($sSearch_3 == - 1) {
					$sSearch_3 = 0;
				}
				$where .= "
				AND (
					FROM_UNIXTIME(`createtime`,'%Y-%m-%d') LIKE '%{$sSearch_4}%'
				)
				";
			}
			
			$sSearch_4 = mysql_real_escape_string ( $this->input->get ( 'sSearch_4' ) );
			if (! empty ( $sSearch_4 )) {
				$where .= "
				AND (
				state = '{$sSearch_4}'
				)
				";
			}
			$sSearch_5 = mysql_real_escape_string ( $this->input->get ( 'sSearch_5' ) );
			if (! empty ( $sSearch_5 )) {
				$where .= "
				AND (
				columnid = '{$sSearch_5}'
				)
				";
			}
			// 输出
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->img_model->count ( $where );
			$output ['aaData'] = $this->img_model->get ( $where, $limit, $offset, 'orderby DESC' );
			foreach ( $output ['aaData'] as $item ) {
				$item->columnid =$this->img_model->get_colum_name($item->columnid);
				$item->createtime = ! empty ( $item->createtime ) ? date ( 'Y-m-d', $item->createtime ) : '';
				$state = $item->state;
				$item->state = $this->_set_state ( $state );
				$item->operation = '<a class="green" href="/master/cms/img/edit?id='.$item->id.'&label_id='.$label_id.'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				$item->operation.=' <a class="red" onclick="del('.$item->id.')" href="javascript:;"><i class="ace-icon fa fa-trash-o bigger-130"></i></a';
			}
			exit ( json_encode ( $output ) );
		}
		$this->_view ( 'img_index', array (
			'label_id'=>$label_id,
			'column_info'=>$column_info
		) );
	}
	
	/**
	 * 编辑内容
	 */
	function edit() {
		// 获取栏目的id
		$id=$this->input->get('id');
		$label_id = trim ( $this->input->get ( 'label_id' ) ) ;
		$column_info=$this->img_model->get_news_colum();
		if (! empty ( $id )) {
			$info = $this->img_model->get_one ( $id );
			$this->_view ( 'img_edit', array (
					'label_id'=>$label_id,
					'column_info'=>$column_info,
					'info' => $info 
			) );
		}
	}
	
	/**
	 * 添加ppt
	 */
	function add() {
		// 获取栏目的id
		$label_id = trim ( $this->input->get ( 'label_id' ) ) ;
		$column_info=$this->img_model->get_news_colum();
		if ($label_id) {
			$this->_view ( 'img_edit', array (
				'label_id'=>$label_id,
				'column_info'=>$column_info
			) );
		}
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
			$info = $this->img_model->get_one ( $id );
			$this->_view ( 'gallery_addgallery', array (
					'columnid' => $columnid,
					'info' => $info 
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
				'columnid',
				'state',
				'createtime' 
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

		if (! empty ( $id )) {
			$flag = $this->img_model->save ( $id, $data );
		} else {
			$data ['createtime'] = time ();
			$flag = $this->img_model->save ( null, $data );
		}
		if ($flag) {
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
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
			}else{
				$data ['createtime'] = time();
			}
			if ( empty ( $data ['isvideo'] )) {
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
	
	/**
	 * 删除
	 */
	function del() {
		$label_id = $this->input->get ( 'label_id'  );
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$is = $this->img_model->delete ( $id );
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
			$is = $this->img_model->save ( $id, array (
					'state' => $state 
			) );
			if ($is === true) {
				ajaxReturn ( '', '更新成功', 1 );
			}
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
}