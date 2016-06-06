<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 奖学金
 *
 * @author zyj
 *        
 */
class Scholarship extends Master_Basic {
	/**
	 * 奖学金
	 *
	 * @var array
	 */
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/basic/';
		$this->load->model ( $this->view . 'scholarship_model' );
	}
	
	/**
	 * 主页
	 */
	function index() {
		
		// 获得语言的id
		$label_id = ! empty ( $_SESSION ['language'] ) ? $_SESSION ['language'] : 'cn';
		
		// 如果是ajax请求则返回json数据列表
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->scholarship_model->count ( $condition );
			$output ['aaData'] = $this->scholarship_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$state = $item->state;
				$item->state = $this->_get_lists_state ( $item->state );
				$item->apply_state = $this->_apply_state ( $item->apply_state );
				$item->createtime = date ( 'Y-m-d', $item->createtime );
				$item->applyendtime = date ( 'Y-m-d', $item->applyendtime );
				$item->operation = '
					<a href="/master/basic/scholarship/add?id=' . $item->id . '" class="btn btn-xs btn-info">编辑</a>
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="btn btn-xs btn-info btn-white">删除</a>
				';
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'scholarship_index', array (
				'label_id' => $label_id 
		) );
	}
	
	/**
	 * 模版信息
	 */
	function get_templates() {
		$data = array ();
		$where = "classType = 1";
		$where .= ' AND admin_id = 0';
		$data = $this->db->where ( $where )->get ( 'templateclass' )->result_array ();
		if ($data) {
			foreach ( $data as $k => $v ) {
				$basic [$v ['tClass_id']] = $v ['ClassName'];
			}
			return $basic;
		}
		return array ();
	}
	
	/**
	 * 附件信息
	 */
	function get_attachments() {
		$data = array ();
		$data = $this->db->where ( 'atta_id > 1' )->get ( 'attachments' )->result_array ();
		if ($data) {
			foreach ( $data as $k => $v ) {
				$basic [$v ['atta_id']] = $v ['AttaName'];
			}
			return $basic;
		}
		return array ();
	}
	
	/**
	 * 添加
	 */
	function add() {
		
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if ($id) {
			$result = $this->scholarship_model->get_one ( 'id =' . $id );
		}
		
		$templates = $this->get_templates ();
		$attachments = $this->get_attachments ();
		$this->_view ( 'scholarship_edit', array (
				'info' => ! empty ( $result ) ? $result : array (),
				
				'applytemplate' => ! empty ( $templates ) ? $templates : array (),
				'attatemplate' => ! empty ( $attachments ) ? $attachments : array () 
		) );
	}
	
	/**
	 * 编辑
	 */
	function edit() {
		// 获取文章id
		$id = intval ( $this->input->get ( 'id' ) );
		// 获得语言的id
		$label_id = $this->input->get ( 'label_id' );
		if (empty ( $label_id )) {
			$label_id = $this->open_language [0];
		}
		if ($id) {
			$where = "id = " . $id;
			$info = ( object ) $this->scholarship_model->get_one ( $where );
			if (empty ( $info )) {
				$this->_alert ( '此文章不存在' );
			}
		}
		
		$templates = $this->get_templates ();
		$attachments = $this->get_attachments ();
		
		$this->_view ( 'scholarship_edit', array (
				// 'programaids' => $this->programaids_news,
				'info' => ! empty ( $info ) ? $info : array (),
				'label_id' => $label_id,
				
				'applytemplate' => ! empty ( $templates ) ? $templates : array (),
				'attatemplate' => ! empty ( $attachments ) ? $attachments : array () 
		) );
	}
	
	/**
	 * 保存数据
	 */
	/**
	 * 保存数据
	 */
	function save() {
		$data = $this->input->post ();

		if (! empty ( $data ['aid'] )) {
			unset ( $data ['aid'] );
		}
		
		if (! empty ( $data ['cost_cover'] )) {
			$data ['cost_cover'] = json_encode ( $data ['cost_cover'] );
		}
		if (! empty ( $data ['id'] )) {
			$id = $data ['id'];
		}
		unset ( $data ['id'] );
		if (! empty ( $data )) {
			$data ['lasttime'] = time ();
			$data ['adminid'] = $_SESSION ['master_user_info']->id;
			if (! empty ( $data ['applyendtime'] )) {
				$data ['applyendtime'] = strtotime ( $data ['applyendtime'] );
			}
			if (! empty ( $id )) {
				$flag = $this->scholarship_model->save ( $id, $data );
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了名为' . $data ['title'] . '的奖学金',
						'ip' => get_client_ip (),
						'lasttime' => time () 
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
			} else {
				$data ['createtime'] = time ();
				$flag = $this->scholarship_model->save ( null, $data );
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '添加了名为' . $data ['title'] . '的奖学金',
						'ip' => get_client_ip (),
						'lasttime' => time () 
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
			}
			if ($flag) {
				
				ajaxReturn ( '', '操作成功！', 1 );
			} else {
				ajaxReturn ( '', '操作失败！', 0 );
			}
		} else {
			ajaxReturn ( '', '操作失败！', 0 );
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
	 * 修改群组的状态
	 */
	function upstate() {
		$id = intval ( $this->input->get_post ( 'id' ) );
		$state = intval ( $this->input->get_post ( 'state' ) );
		if (! empty ( $id )) {
			$result = $this->scholarship_model->save_audit ( $id, $state );
			if ($result === true) {
				$grouplog = $this->scholarship_model->get_one ( 'id = ' . $id );
				$statelog = array (
						'禁用',
						'启用' 
				);
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了奖学金' . $grouplog->title . '的状态信息为' . $statelog [$state],
						'ip' => get_client_ip (),
						'lasttime' => time () 
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
				
				ajaxReturn ( '', '更改成功', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 删除 关联表中数据也会删除
	 */
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$info = ( object ) $this->scholarship_model->get_one ( $where );
			$is = $this->scholarship_model->delete ( $where );
			if ($is === true) {
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '删除了奖学金' . $info->title . '的信息',
						'ip' => get_client_ip (),
						'lasttime' => time () 
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
				
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
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
			$id = $this->scholarship_model->save ( null, $data );
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
			$this->scholarship_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'title',
				'count',
				'money',
				'applyendtime',
				'createtime',
				'state',
				'site_language',
				'apply_state' 
		);
	}
	
	/**
	 * 获取文章状态
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
	 * 获取文章状态
	 *
	 * @param string $statecode
	 * @param string $stateindexcode
	 * @return string
	 */
	private function _apply_state($statecode = null) {
		if ($statecode != null) {
			$statemsg = array (
					1 => '<span class="label label-success">在学</span>',
					2 => '<span class="label label-success">新生</span>'
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
			unset ( $data ['imagefile'] );
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
}

