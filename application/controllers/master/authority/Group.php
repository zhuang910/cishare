<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 权限管理 管理员群组管理
 *
 * @author zyj
 *        
 */
class Group extends Master_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		
		$this->view = 'master/authority/';
		
		$this->load->model ( $this->view . 'group_model' );
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
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->group_model->count ( $condition );
			$output ['aaData'] = $this->group_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$state = $item->state;
				$item->state = $this->_get_lists_state ( $item->state );
				$item->createtime = ! empty ( $item->createtime ) ? date ( 'Y-m-d H:i:s', $item->createtime ) : '';
				$item->operation = '
					<a href="/master/authority/group/add?id=' . $item->id . '" class="btn btn-xs btn-info" >编辑</a>
					
				';
				
				$item->operation .= '<a href="/master/authority/group/power?id=' . $item->id . '" class="btn btn-xs btn-info btn-white">授权</a>';
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'group_index' );
	}
	
	/**
	 * 添加
	 */
	function add() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if ($id) {
			$result = $this->group_model->get_one ( 'id =' . $id );
		}
		$this->_view ( 'group_edit', array (
				'info' => ! empty ( $result ) ? $result : array () 
		) );
	}
	
	/**
	 * 保存数据
	 */
	function save() {
		$data = $this->input->post ();
		if (! empty ( $data ['id'] )) {
			$id = $data ['id'];
		}
		unset ( $data ['id'] );
		if (! empty ( $data )) {
			if (! empty ( $id )) {
				$flag = $this->group_model->save ( $id, $data );
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了名为' . $data ['title'] . '的群组',
						'ip' => get_client_ip (),
						'lasttime' => time () 
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
			} else {
				$data ['createtime'] = time ();
				$flag = $this->group_model->save ( null, $data );
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '添加了名为' . $data ['title'] . '的群组',
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
	 * 授权
	 */
	function get_power() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$power = CF ( 'power', '', 'application/cache/' );
			$html = $this->_view ( 'group_get_power', array (
					'power' => ! empty ( $power ) ? $power : array () 
			), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	
	/**
	 * 授权
	 */
	function power() {
		$id = intval ( $this->input->get ( 'id' ) );
		// 获取 权限
		$powerall = $this->group_model->get_power ( 'groupid = ' . $id );
		if (! empty ( $powerall )) {
			foreach ( $powerall as $k => $v ) {
				$power [] = $v ['power'];
			}
		}
		$this->_view ( 'group_power', array (
				'power' => ! empty ( $power ) ? $power : array (),
				'id' => ! empty ( $id ) ? $id : array () 
		) );
	}
	
	/**
	 * 保存权限
	 */
	function savepower() {
		$data = $this->input->post ();
		if (! empty ( $data ['id'] )) {
			$id = $data ['id'];
			if (! empty ( $data ['power'] )) {
				$power = $data ['power'];
				// 清除 改组的 所有权限
				$this->group_model->delpower ( 'groupid = ' . $id );
				foreach ( $power as $k => $v ) {
					$flag = $this->group_model->savepower ( array (
							'groupid' => $id,
							'power' => $v 
					) );
				}
				if ($flag) {
					// 写入日志
					$grouplog = $this->group_model->get_one ( 'id = ' . $id );
					$datalog = array (
							'adminid' => $_SESSION ['master_user_info']->id,
							'adminname' => $_SESSION ['master_user_info']->username,
							'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '给群组' . $grouplog->title . '授权',
							'ip' => get_client_ip (),
							'lasttime' => time () 
					);
					if (! empty ( $datalog )) {
						$this->adminlog->savelog ( $datalog );
					}
					ajaxReturn ( '', '', 1 );
				} else {
					ajaxReturn ( '', '', 0 );
				}
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 修改群组的状态
	 */
	function upstate() {
		$id = intval ( $this->input->get_post ( 'id' ) );
		$state = intval ( $this->input->get_post ( 'state' ) );
		if (! empty ( $id )) {
			$result = $this->group_model->save_audit ( $id, $state );
			if ($result === true) {
				$grouplog = $this->group_model->get_one ( 'id = ' . $id );
				$statelog = array (
						'禁用',
						'启用' 
				);
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了群组' . $grouplog->title . '的状态信息为' . $statelog [$state],
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
			$info = ( object ) $this->group_model->get_one ( $where );
			$is = $this->group_model->delete ( $where );
			if ($is === true) {
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '删除了群组' . $info->title . '的信息',
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
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'title',
				'createtime',
				'state' 
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
					'<span class="label label-success">正常</span>' 
			);
			return $statemsg [$statecode];
		}
		return;
	}
	
	/**
	 * 获取 群组的名称
	 *
	 * @param string $statecode        	
	 * @param string $stateindexcode        	
	 * @return string
	 */
	private function _get_lists_group($groupid = null) {
		if ($groupid != null) {
			// 获取管理员的群组
			$group = $this->admin_model->get_group ( 'id > 0' );
			
			return ! empty ( $group [$groupid] ) ? $group [$groupid] : '';
		}
		return;
	}
}

