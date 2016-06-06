<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 模型管理
 *
 * @author grf
 *        
 */
class Module extends Master_Basic {

	function __construct() {
		parent::__construct ();
		$this->view = 'master/cms/';
		$publics = CF ( 'publics', '', CONFIG_PATH );
		$this->load->model ( $this->view . 'module_model' );
	}
	
	/**
	 * 主页
	 */
	function index() {
		// 如果是ajax请求则返回json数据列表
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->module_model->count ( $condition );
			$output ['aaData'] = $this->module_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$item->state = $this->_get_lists_state ( $item->state );
				$item->createtime = date ( 'Y-m-d', $item->createtime );
				$item->lasttime = date ( 'Y-m-d', $item->lasttime );
				$item->operation = '
					<a class="green" href="' . $this->zjjp . 'module' . '/edit?id=' . $item->id . '"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
				';
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'module_index', array (
		) );
	}
	/**
	 *添加
	 **/	
	function add(){
		$this->_view ( 'module_edit', array (
		) );
	}
	/**
	 * 编辑
	 */
	function edit() {
		// 获取文章id
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = " . $id;
			$info = ( object ) $this->module_model->get_one ( $where );
			if (empty ( $info )) {
				$this->_alert ( '此文章不存在' );
			}
		}
		
		$this->_view ( 'module_edit', array (
				// 'programaids' => $this->programaids_news,
				'info' => ! empty ( $info ) ? $info : array (),
		) );
	}
	
	/**
	 * 保存数据
	 */
	function save() {
		$data = $this->input->post ();
		$id = ! empty ( $data ['id'] ) ? $data ['id'] : '';
		unset ( $data ['id'] );
		if (! empty ( $data )) {
			if (! empty ( $_FILES ['imagefile'] ['name'] )) {
				$data ['image'] = $this->_upload ();
			}
			
			if (! empty ( $data ['createtime'] )) {
				$data ['createtime'] = strtotime ( $data ['createtime'] );
				
				if (! empty ( $id )) {
					$data ['lasttime'] = time ();
					$flag = $this->module_model->save ( $id, $data );
				} else {
					$flag = $this->module_model->save ( null, $data );
				}
				
				if ($flag) {
					ajaxReturn ( '', '', 1 );
				} else {
					ajaxReturn ( '', '', 0 );
				}
			}
		} else {
			
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 上传
	 *
	 * @return string
	 */
	private function _upload() {
		$config = array (
				'save_path' => '/uploads/advance/' . date ( 'Ym' ) . '/' . date ( 'd' ),
				'upload_path' => $_SERVER ['DOCUMENT_ROOT'] . '/uploads/advance/' . date ( 'Ym' ) . '/' . date ( 'd' ),
				'allowed_types' => 'jpeg|jpg|png',
				'file_name' => time () . rand ( 100000, 999999 ) 
		);
		
		if (! empty ( $config )) {
			$this->load->library ( 'upload', $config );
			// 创建目录
			mk_dir ( $config ['upload_path'] );
			
			if (! $this->upload->do_upload ( 'imagefile' )) {
				ajaxReturn ( '', $this->upload->display_errors ( '', '' ), 0 );
			} else {
				$imgdata = $this->upload->data ();
				return $config ['save_path'] . '/' . $imgdata ['file_name'];
			}
		}
	}
	
	/**
	 * 更改文章状态
	 */
	function audit() {
		$id = intval ( $this->input->get_post ( 'id' ) );
		$action = $this->input->post ( 'action' );
		$type = intval ( $this->input->post ( 'type' ) );
		if (! empty ( $action ) && $action == 'true') {
			$result = $this->article_model->save_audit ( $id, $type );
			if ($result === true) {
				ajaxReturn ( '', '更改成功', 1 );
			}
		}
		$html = $this->_view ( 'article_audit', array (
				'id' => $id 
		), true );
		ajaxReturn ( $html, '', 1 );
	}
	
	/**
	 * 插入
	 */
	function insert() {
		$data = $this->_save_data ();
		
		if (! empty ( $data )) {
			$id = $this->module_model->save ( null, $data );
			if ($id) {
				
				ajaxReturn ( 'back', '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	
	/**
	 * 更新
	 */
	function update() {
		$id = intval ( $this->input->post ( 'id' ) );
		if ($id) {
			$data = $this->_save_data ();
			// 上传缩略图
			if (! empty ( $_FILES ['imagefile'] ['name'] )) {
				$data ['image'] = $this->_upload ();
			}
			// 保存基本信息
			$this->module_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	
	/**
	 * 删除 关联表中数据也会删除
	 */
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$info = ( object ) $this->module_model->get_one ( $where );
			$is = $this->module_model->delete ( $where );
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
				'id',
				'title',
				'table',
				'table_intro',
				'createtime',
				'lasttime',
				'state',
				'orderby',
		);
	}
	
	/**
	 * 获取模型状态
	 *
	 * @param string $statecode        	
	 * @param string $stateindexcode        	
	 * @return string
	 */
	private function _get_lists_state($statecode = null) {
		if ($statecode != null) {
			$statemsg = array (
					'<span class="label label-important">禁用</span>',
					'<span class="label label-success">正常</span>' 
			);
			return $statemsg [$statecode];
		}
		return;
	}
	
	/**
	 * 获取保存数据
	 */
	private function _save_data() {
		$time = time ();
		$return = array ();
		$data = $this->input->post ();
		if (! empty ( $data )) {
			foreach ( $data as $key => $value ) {
				if ($key == 'id' && empty ( $value )) {
					unset ( $data [$key] );
				}
				
				$data [$key] = trim ( $value );
				if ($key == 'createtime') {
					$data ['createtime'] = strtotime ( $value );
				}
			}
		}
		
		$data ['lasttime'] = $time;
		$data ['adminid'] = $this->adminid;
		return $data;
	}
	// ///////////////////////////////////////////////////////////////以下是 为了调取页面的 暂时不用
	/**
	 * 模版一 编辑 模版
	 */
	function zyj_template1_list1() {
		$this->_view ( 'zyj_template1_list1' );
	}
	
	/**
	 * 模版二 列表模版
	 */
	function zyj_template1_list2(){
		$this->_view ( 'zyj_template1_list2' );
	}
	/**
	 * 图集
	 */
	function zyj_template_img(){
		$this->_view ( 'zyj_template_img' );
	}
	
}

