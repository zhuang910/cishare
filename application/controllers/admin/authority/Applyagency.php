<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 权限管理 中介管理
 *
 * @author zhuangqianlin
 *        
 */
class Applyagency extends Admin_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		
		$this->view = 'admin/authority/';
		
		$this->load->model ( $this->view . 'applyagency_model' );
		
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
			$condition ['where'] ['checkstate'] = - 1;
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->applyagency_model->count ( $condition );
			$output ['aaData'] = $this->applyagency_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$item->licence = '<img src=' . $item->licence . '>';
				
				$item->createtime = ! empty ( $item->createtime ) ? date ( 'Y-m-d H:i:s', $item->createtime ) : '';
				
				$item->operation = '
					<a href="javascript:;" onclick="upstate(' . $item->id . ',1)"  title="点击通过" id="upstate"><i class="ace-icon fa fa-check green bigger-130"></i></a>
					<a href="javascript:;" onclick="upstate(' . $item->id . ',2)" class="red" title="点击不通过" id="upstate"><i class="ace-icon glyphicon glyphicon-remove red"></i></a>
					<a href="javascript:;" onclick="sendemail(' . $item->id . ')" class="green" title="点击发送邮件" id="upstate"><i class="ace-icon fa fa-envelope icon-animated-vertical"></i></a>
							';
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'applyagency_index' );
	}
	
	/**
	 * 修改管理员的状态
	 */
	function upstate() {
		$id = intval ( $this->input->get_post ( 'id' ) );
		$state = intval ( $this->input->get_post ( 'state' ) );
		if (! empty ( $id )) {
			$result = $this->applyagency_model->save_audit ( $id, $state );
			if ($result === true) {
				$agencyinfo = $this->applyagency_model->get_one ( 'id = ' . $id );
				$statelog = array (
						1 => '通过',
						2 => '不通过' 
				);
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了中介' . $agencyinfo->username . '的状态信息为' . $statelog [$state],
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
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'email',
				'company',
				'createtime',
				'licence',
				'tel',
				'mobile' 
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
	 * 获取管理员状态
	 *
	 * @param string $statecode        	
	 * @param string $stateindexcode        	
	 * @return string
	 */
	private function _get_lists_checkstate($statecode = null) {
		if ($statecode != null) {
			$statemsg = array (
					'-1' => '待审核',
					'1' => '通过',
					'2' => '不通过' 
			);
			return $statemsg [$statecode];
		}
		return;
	}
	
	/**
	 * 发邮件
	 */
	function sendemail() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$result = $this->applyagency_model->get_one ( 'id = ' . $id );
			$html = $this->_view ( 'applyagency_sendemail', array (
					'id' => ! empty ( $id ) ? $id : '',
					'info' => ! empty ( $result ) ? $result : array () 
			), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	
	/**
	 * 执行 发送邮件
	 */
	function dosendemail() {
		$email = trim ( $this->input->post ( 'email' ) );
		$title = trim ( $this->input->post ( 'title' ) );
		$content = trim ( $this->input->post ( 'content' ) );
		if (! empty ( $email )) {
			$content = $this->load->view ( 'admin/adminemail/angency_email', array (
					'title' => $title,
					'content' => $content,
					'email' => $email 
			), true );
			$this->_send_email ( $email, $title, $content );
			$agencyinfo = $this->applyagency_model->get_one ( "email = '$email'" );
			
			// 写入日志
			$datalog = array (
					'adminid' => $_SESSION ['master_user_info']->id,
					'adminname' => $_SESSION ['master_user_info']->username,
					'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '给中介' . $agencyinfo->username . '发送了标题为' . $title . '的邮件',
					'ip' => get_client_ip (),
					'lasttime' => time () 
			);
			if (! empty ( $datalog )) {
				$this->adminlog->savelog ( $datalog );
			}
			ajaxReturn ( '', '', 1 );
		}
	}
	
	/**
	 * 发送email
	 */
	private function _send_email($to = null, $title = null, $content = null) {
		if ($to && $title && $content) {
			$this->load->library ( 'mymail' );
			$MAIL = new Mymail ();
			$MAIL->domail ( $to, $title, $content );
		}
	}
}

