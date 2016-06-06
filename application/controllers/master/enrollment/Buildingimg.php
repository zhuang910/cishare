<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 住宿楼
 *
 * @author zyj
 *        
 */
class Buildingimg extends Master_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/enrollment/';
		
		$this->load->model ( $this->view . 'buildingimg_model' );
	}
	
	/**
	 * 主页
	 */
	function index() {
		// 获得语言的id
		$label_id = !empty($_SESSION['language'])?$_SESSION['language']:'cn';
	
		$buildingid = intval ( trim ( $this->input->get ( 'buildingid' ) ) );
		
		// 如果是ajax请求则返回json数据列表
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			$label_id = !empty($_SESSION['language'])?$_SESSION['language']:'cn';
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$condition ['where'] ['site_language'] =$label_id;
			$condition ['where'] ['bulidingid'] = $buildingid;
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->buildingimg_model->count ( $condition, null );
			$output ['aaData'] = $this->buildingimg_model->get ( $fields, $condition, null );
			foreach ( $output ['aaData'] as $item ) {
				$item->time = date ( 'Y-m-d H:i:s', $item->time );
				$item->operation = '
					<a href="/master/enrollment/buildingimg/edit?id=' . $item->id . '" class="btn btn-xs btn-info">编辑</a>
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="btn btn-xs btn-info btn-white">删除</a>
				';
			}
			
			exit ( json_encode ( $output ) );
		}
		
		$this->load->view ( 'master/enrollment/buildingimg_index', array (
				'label_id' => $label_id,
				'buildingid' => $buildingid 
		) );
	}
	
	/**
	 * 添加 编辑 校区
	 */
	function add_buildingimg() {
		$buildingid = intval ( trim ( $this->input->get ( 'buildingid' ) ) );
		$label_id=intval ( trim ( $this->input->get ( 'label_id' ) ) );
		$this->load->view ( 'master/enrollment/add_buildingimg_edit', array (
			'label_id'=>$label_id,
				'buildingid' => $buildingid 
		) );
	}
	
	/**
	 * 编辑
	 */
	function edit() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$info = ( object ) $this->buildingimg_model->get_one ( $where );
			$this->load->view ( 'master/enrollment/add_buildingimg_edit', array (
					
					'info' => $info 
			) );
		}
	}
	
	/**
	 * 插入
	 */
	function insert() {
		$data = $this->_save_data ();

		if (! empty ( $data )) {
//			if (! empty ( $_FILES ['imagefile'] ['name'] )) {
//				$data ['pictures'] = $this->_upload ();
//			}
            $data ['pictures'] = $data ['dan'];
            unset($data ['dan']);
			unset ( $data ['id'] );
			$data ['time'] = time ();
			if (! empty ( $data ['bulidingid'] )) {
				$c = $this->db->select ( '*' )->get_where ( 'school_accommodation_buliding', 'id = ' . $data ['bulidingid'] )->result_array ();
				if (! empty ( $c )) {
					$data ['columnid'] = $c [0] ['columnid'];
				}
			}
			$id = $this->buildingimg_model->save ( null, $data );
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
			unset ( $data ['id'] );
//			if (! empty ( $_FILES ['imagefile'] ['name'] )) {
//				$data ['pictures'] = $this->_upload ();
//			}
            $data ['pictures'] = $data ['dan'];
            unset($data ['dan']);

            // 保存基本信息
			$data ['time'] = time ();
			if (! empty ( $data ['bulidingid'] )) {
				$c = $this->db->select ( '*' )->get_where ( 'school_accommodation_buliding', 'id = ' . $data ['bulidingid'] )->result_array ();
				if (! empty ( $c )) {
					$data ['columnid'] = $c [0] ['columnid'];
				}
			}
			$this->buildingimg_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	
	/**
	 * 上传
	 *
	 * @return string
	 */
	private function _upload() {
		$config = array (
				'save_path' => '/uploads/accommodation/' . date ( 'Ym' ) . '/' . date ( 'd' ),
				'upload_path' => $_SERVER ['DOCUMENT_ROOT'] . '/uploads/accommodation/' . date ( 'Ym' ) . '/' . date ( 'd' ),
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
				$config ['image_library'] = 'gd2';
				$config ['source_image'] = $imgdata ['full_path'];
				$config ['create_thumb'] = TRUE;
				$config ['maintain_ratio'] = TRUE;
				$config ['new_image'] = $imgdata ['file_path'];
				$config ['width'] = 120;
				$config ['height'] = 64;
				
				$this->load->library ( 'image_lib', $config );
				$this->image_lib->resize ();
				return $config ['save_path'] . '/' . $imgdata ['file_name'];
			}
		}
	}
	
	/**
	 * 获取保存数据
	 */
	private function _save_data() {
		$time = time ();
		$return = array ();
		$data = $this->input->post ();
		if(!empty($data['aid'])){
			unset($data['aid']);
		}
		if (! empty ( $data )) {
			foreach ( $data as $key => $value ) {
				if ($key == 'id' && empty ( $value )) {
					unset ( $data [$key] );
				}
				
				$data [$key] = trim ( $value );
			}
		}
		
		return $data;
	}
	
	/**
	 * 删除 关联表中数据也会删除
	 */
	function delete() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$info = ( object ) $this->buildingimg_model->get_one ( $where );
			$is = $this->buildingimg_model->delete ( $where );
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
				'name',
				'orderby',
				'time' 
		);
	}
	
	/**
	 * 获取管理员状态
	 *
	 * @param string $statecode        	
	 * @param string $stateindexcode        	
	 * @return string
	 */
	private function _get_lists_state($statecode = null) {
		if ($statecode != null) {
			$statemsg = array (
					'<span class="label label-important">禁用</span>',
					'<span class="label label-success">启用</span>' 
			);
			return $statemsg [$statecode];
		}
		return;
	}
}

