<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Degree extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/basic/';
		$this->load->model ( $this->view . 'degree_model' );
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
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->degree_model->count ( $condition );
			$output ['aaData'] = $this->degree_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$state = $item->state;
				$item->state = $this->_get_lists_state ( $item->state );
				$item->createtime = ! empty ( $item->createtime ) ? date ( 'Y-m-d H:i:s', $item->createtime ) : '';
				
					$item->operation = '
					<a href="/master/basic/degree/add?id=' . $item->id . '" class="btn btn-xs btn-info">编辑</a>
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="btn btn-xs btn-info btn-white">删除</a>
				';
				
				// if ($state == 1) {
				// $item->operation .= '<a href="javascript:;" onclick="upstate(' . $item->id . ',0)" title="点击禁用" id="upstate"><i class="ace-icon fa fa-check green bigger-130"></i></a>';
				// } else {
				// $item->operation .= '<a href="javascript:;" onclick="upstate(' . $item->id . ',1)" class="red" title="点击启用" id="upstate"><i class="ace-icon glyphicon glyphicon-remove red"></i></a>';
				// }
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'degree_index' );
	}
	
	/**
	 * 添加
	 */
	function add() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if ($id) {
			$result = $this->degree_model->get_one ( 'id =' . $id );
		}
		$this->_view ( 'degree_edit', array (
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
			$data ['lasttime'] = time ();
			$data ['adminid'] = $_SESSION ['master_user_info']->id;
			if (! empty ( $id )) {
				$flag = $this->degree_model->save ( $id, $data );
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了名为' . $data ['title'] . '的学历',
						'ip' => get_client_ip (),
						'lasttime' => time () 
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
			} else {
				$data ['createtime'] = time ();
				$flag = $this->degree_model->save ( null, $data );
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '添加了名为' . $data ['title'] . '的学历',
						'ip' => get_client_ip (),
						'lasttime' => time () 
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
			}
			if ($flag) {
				// 更新缓存
				$cachedata = $this->degree_model->cache_data ();
				CF ( 'degree', $cachedata, CONFIG_PATH );
				ajaxReturn ( '', '操作成功！', 1 );
			} else {
				ajaxReturn ( '', '操作失败！', 0 );
			}
		} else {
			ajaxReturn ( '', '操作失败！', 0 );
		}
	}
	
	/**
	 * 修改群组的状态
	 */
	function upstate() {
		$id = intval ( $this->input->get_post ( 'id' ) );
		$state = intval ( $this->input->get_post ( 'state' ) );
		if (! empty ( $id )) {
			$result = $this->degree_model->save_audit ( $id, $state );
			if ($result === true) {
				$grouplog = $this->degree_model->get_one ( 'id = ' . $id );
				$statelog = array (
						'禁用',
						'启用' 
				);
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了学历' . $grouplog->title . '的状态信息为' . $statelog [$state],
						'ip' => get_client_ip (),
						'lasttime' => time () 
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
				$cachedata = $this->degree_model->cache_data ();
				CF ( 'degree', $cachedata, CONFIG_PATH );
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
			$info = ( object ) $this->degree_model->get_one ( $where );
			$is = $this->degree_model->delete ( $where );
			if ($is === true) {
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '删除了学历' . $info->title . '的信息',
						'ip' => get_client_ip (),
						'lasttime' => time () 
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
				$cachedata = $this->degree_model->cache_data ();
				CF ( 'degree', $cachedata, CONFIG_PATH );
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
				'entitle',
				'createtime',
				'orderby',
				'state' ,
				'isdegree'
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
}