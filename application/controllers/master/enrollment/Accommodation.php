<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 住宿
 *
 * @author zyj
 *        
 */
class Accommodation extends Master_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/enrollment/';
		
		$this->load->model ( $this->view . 'accommodation_model' );
	}
	
	/**
	 * 主页
	 */
	function index() {
		// 如果是ajax请求则返回json数据列表
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			
			$nationality = CF ( 'nationality', '', 'application/cache/' );
			
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->accommodation_model->count ( $condition );
			$output ['aaData'] = $this->accommodation_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$state = $item->state;
				$item->nationality = $nationality [$item->nationality];
				$item->state = $this->_get_lists_state ( $item->state );
				$item->subtime = ! empty ( $item->subtime ) ? date ( 'Y-m-d H:i:s', $item->subtime ) : '';
				$item->sex = $this->_get_lists_sex ( $item->sex );
				$item->operation = '
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="red" title="删除" id="del"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
					';
				
				if ($state == 0) {
					$item->operation .= '<a href="javascript:;" onclick="upstate(' . $item->id . ',2)"  title="不通过" id="upstate"><i class="ace-icon fa fa-check green bigger-130"></i></a>';
				} else {
					$item->operation .= '<a href="javascript:;" onclick="upstate(' . $item->id . ',1)" class="red" title="通过" id="upstate"><i class="ace-icon glyphicon glyphicon-remove red"></i></a>';
				}
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'pickup_index' );
	}
	
	/**
	 * 修改管理员的状态
	 */
	function upstate() {
		$id = intval ( $this->input->get_post ( 'id' ) );
		$state = intval ( $this->input->get_post ( 'state' ) );
		if (! empty ( $id )) {
			$result = $this->accommodation_model->save_audit ( $id, $state );
			if ($result === true) {
				$teacherlog = $this->accommodation_model->get_one ( 'id = ' . $id );
				$statelog = array (
						'处理中',
						'通过',
						'不通过' 
				);
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了接机用户' . $teacherlog->email . '的状态信息为' . $statelog [$state],
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
			$info = ( object ) $this->accommodation_model->get_one ( $where );
			$is = $this->accommodation_model->delete ( $where );
			if ($is === true) {
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '删除了接机用户' . $info->email . '的信息',
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
				'name',
				'nationality',
				'email',
				'sex',
				'tel',
				'mobile',
				'subtime',
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
					'<span class="label label-important">处理中</span>',
					'<span class="label label-important">不通过</span>',
					'<span class="label label-success">通过</span>' 
			);
			return $statemsg [$statecode];
		}
		return;
	}
	
	/**
	 * 获取管理员状态
	 *
	 * @param string $statecode        	
	 * @param string $stateindexcode        	
	 * @return string
	 */
	private function _get_lists_sex($statecode = null) {
		if ($statecode != null && $statecode != 0) {
			$statemsg = array (
					'-1' => '未填写',
					'1' => '男',
					'2' => '女' 
			);
			return $statemsg [$statecode];
		}
		return;
	}
}

