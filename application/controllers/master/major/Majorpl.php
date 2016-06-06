<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 专业图集
 *
 * @author zyj
 *        
 */
class Majorpl extends Master_Basic {
	/**
	 * 专业图集
	 *
	 * @var array
	 */
	
	/**
	 *
	 * @var number
	 */
	
	/**
	 * 栏目字符串
	 *
	 * @var array
	 */
	
	/**
	 * 栏目查询字符串
	 *
	 * @var sting
	 */
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/major/';
		
		$this->load->model ( $this->view . 'majorpl_model' );
	}
	
	/**
	 * 主页
	 */
	function index() {
		$majorid = intval ( trim ( $this->input->get ( 'majorid' ) ) );
		// 获得语言的id
		$label_id =  !empty($_SESSION['language'])?$_SESSION['language']:'cn';
		
		// 如果是ajax请求则返回json数据列表
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$condition ['where'] ['site_language'] = $label_id;
			$condition ['where'] ['majorid'] = $majorid;
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->majorpl_model->count ( $condition );
			$output ['aaData'] = $this->majorpl_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$item->operation = '
					<a href="/master/major/majorpl/edit?id=' . $item->id . '&label_id=' . $item->site_language . '" class="green"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
				';
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'majorpl_index', array (
				'label_id' => $label_id,
				'majorid' => $majorid 
		) );
	}
	
	/**
	 * 添加
	 */
	function add() {
		$label_id = $this->input->get ( 'label_id' );
		$majorid = intval ( trim ( $this->input->get ( 'majorid' ) ) );
		if (empty ( $label_id )) {
			$label_id = $this->open_language [0];
		}
		$this->_view ( 'majorpl_edit', array (
				'label_id' => $label_id,
				'majorid' => $majorid 
		) );
	}
	
	/**
	 * 编辑
	 */
	function edit() {
		// 获取文章id
		$id = intval ( $this->input->get ( 'id' ) );
		$majorid = intval ( $this->input->get ( 'majorid' ) );
		// 获得语言的id
		$label_id = $this->input->get ( 'label_id' );
		if (empty ( $label_id )) {
			$label_id = $this->open_language [0];
		}
		if ($id) {
			$where = "id = " . $id;
			$info = ( object ) $this->majorpl_model->get_one ( $where );
			if (empty ( $info )) {
				$this->_alert ( '此文章不存在' );
			}
		}
		
		$this->_view ( 'majorpl_edit', array (
				'info' => ! empty ( $info ) ? $info : array (),
				'label_id' => $label_id,
				'majorid' => !empty($majorid)?$majorid:'' 
		) );
	}
	
	/**
	 * 保存数据
	 */
	function save() {
		$data = $this->input->post ();
		if(!empty($data['aid'])){
			unset($data['aid']);
		}
		$id = ! empty ( $data ['id'] ) ? $data ['id'] : '';
		unset ( $data ['id'] );
		if (! empty ( $data )) {
			if (! empty ( $_FILES ['imagefile'] ['name'] )) {
				$data ['image'] = $this->_upload ();
			}
			
			if (! empty ( $id )) {
				$flag = $this->majorpl_model->save ( $id, $data );
			} else {
				$flag = $this->majorpl_model->save ( null, $data );
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
	 * 上传
	 *
	 * @return string
	 */
	private function _upload() {
		$config = array (
				'save_path' => '/uploads/major/' . date ( 'Ym' ) . '/' . date ( 'd' ),
				'upload_path' => $_SERVER ['DOCUMENT_ROOT'] . '/uploads/major/' . date ( 'Ym' ) . '/' . date ( 'd' ),
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
			// 上传缩略图
			if (! empty ( $_FILES ['imagefile'] ['name'] )) {
				$data ['image'] = $this->_upload ();
			}
			$id = $this->majorpl_model->save ( null, $data );
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
			$this->majorpl_model->save ( $id, $data );
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
			$info = ( object ) $this->majorpl_model->get_one ( $where );
			$is = $this->majorpl_model->delete ( $where );
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
				'orderby',
				'info',
				'site_language' 
		);
	}
	
	/**
	 * 获取保存数据
	 */
	private function _save_data() {
		$time = time ();
		$return = array ();
		$data = $this->input->post ();
		if (! empty ( $data )) {
			unset ( $data ['imagefile'] );
			foreach ( $data as $key => $value ) {
				if ($key == 'id' && empty ( $value )) {
					unset ( $data [$key] );
				}
				
				$data [$key] = trim ( $value );
			}
		}
		
		return $data;
	}
}

