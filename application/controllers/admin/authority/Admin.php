<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 权限管理 管理员管理
 *
 * @author zhuangqianlin
 *        
 */
class Admin extends Admin_Basic {
	  
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'admin/authority/';
		
		$this->load->model ( $this->view . 'admin_model' );
	
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
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->admin_model->count ( $condition );
            $fields['email'] = 'email';
			$output ['aaData'] = $this->admin_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
                $item->username = '<a href="mailto:'.$item->email.'">'.$item->username.'</a>';
				$item->groupid = $this->_get_lists_group ( $item->groupid );
				$state = $item->state;
				$item->state = $this->_get_lists_state ( $item->state );
				$item->lasttime = ! empty ( $item->lasttime ) ? date ( 'Y-m-d H:i:s', $item->lasttime ) : '';
				$item->operation = '<div class="btn-group"><a class="btn btn-xs btn-info" href="/admin/authority/admin/add?id=' . $item->id . '">编辑</a><button data-toggle="dropdown" class="btn btn-xs btn-info btn-white dropdown-toggle">
								更多
								<span class="ace-icon fa fa-caret-down icon-only"></span>
							</button>
							<ul class="dropdown-menu dropdown-info dropdown-menu-right">';
				
				
				if ($state == 1) {
					$item->operation .= '<li><a href="javascript:;" onclick="upstate(' . $item->id . ',0)"  id="upstate">点击禁用</a></li>';
				} else {
					$item->operation .= '<li><a href="javascript:;" onclick="upstate(' . $item->id . ',1)" >点击启用</a></li>';
				}
				$item->operation .= '<li><a href="javascript:;" onclick="uppassword(' . $item->id . ')">重置密码为123456</a></li>';
				$item->operation .= '<li class="divider"></li>
					<li><a href="javascript:;" onclick="del(' . $item->id . ')" id="del">删除</a></li>
					';
				$item->operation .='</ul></div>';
			
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'admin_index' );
	}
	
	/**
	 * 添加
	 */
	function add() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		// 获取 权限组
		$group = $this->admin_model->get_group ( 'state = 1' );
		
		if ($id) {
			$result = $this->admin_model->get_one ( 'id =' . $id );
		}
		$this->_view ( 'admin_edit', array (
				'info' => ! empty ( $result ) ? $result : array (),
				'group' => ! empty ( $group ) ? $group : array () 
		) );
	}
	
	/**
	 * 保存数据
	 */
	function save() {
		$data = $this->input->post ();
		$id = null;
		$oldpass = null;
		if (! empty ( $data ['id'] )) {
			$id = $data ['id'];
		}
		unset ( $data ['id'] );
		if (! empty ( $data ['oldpass'] )) {
			$oldpass = trim ( $data ['oldpass'] );
		}
		unset ( $data ['oldpass'] );
		$this->load->helper ( 'string' );
		if (! empty ( $data )) {
			if ($id != null) {
				// 编辑管理员
				// 如果没有修改密码 则还原老的密码
				if (empty ( $data ['password'] )) {
					$data ['password'] = $oldpass;
				} else {
					$rand = random_string ( 'alnum', 6 );
					$data ['password'] = md5 ( $data ['password'] ) . md5 ( $rand );
					$data ['salt'] = $rand;
				}
				//是否是教师组的
				if($data['groupid']!=4){
					$is_teacher=$this->admin_model->admin_is_teacher($id);
					if($is_teacher==1){
						$this->admin_model->admin_del_teacher($id);
					}
				}
				$flag = $this->admin_model->save ( $id, $data );
				if($data['groupid']==4){
					$flag = $this->admin_model->save_teacher ( $id, $data );
				}
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了管理员' . $data ['username'] . '的信息',
						'ip' => get_client_ip (),
						'lasttime' => time () 
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
			} else {
				$rand = random_string ( 'alnum', 6 );
				$data ['password'] = md5 ( $data ['password'] ) . md5 ( $rand );
				$data ['salt'] = $rand;
				$data ['createtime'] = time ();
				$data ['createip'] = get_client_ip ();
				$flag = $this->admin_model->save ( null, $data );
				if($data['groupid']==4){
					$data['userid']=$flag;
					$this->admin_model->save_teacher ( null, $data );
				}
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '添加了用户名为' . $data ['username'] . '的管理员',
						'ip' => get_client_ip (),
						'lasttime' => time () 
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
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
	 * 检查邮箱 是否 重复
	 */
	function checkusername() {
		$username = trim ( $this->input->get ( 'username' ) );
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if (! empty ( $username )) {
				
			$email_true = $this->admin_model->get_one ( array (
					'username' => $username
			) );
			if ($email_true) {
				// die ( json_encode ( true ) );
				if ($email_true->id == $id) {
					die ( json_encode ( true ) );
				} else {
					die ( json_encode ( '用户名已被占用' ) );
				}
			} else {
				// die ( json_encode ( 'Email does not exist ' ) );
				die ( json_encode ( true ) );
			}
	
		} else {
			die ( json_encode ( '用户名不能为空！' ) );
		}
	}
	
	/**
	 * 修改管理员的状态
	 */
	function upstate() {
		$id = intval ( $this->input->get_post ( 'id' ) );
		$state = intval ( $this->input->get_post ( 'state' ) );
		if (! empty ( $id )) {
			$result = $this->admin_model->save_audit ( $id, $state );
			
			if ($result === true) {
				$admininfo = $this->admin_model->get_one ( 'id = ' . $id );
				$statelog = array (
						'禁用',
						'启用' 
				);
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了管理员' . $admininfo->username . '的状态信息为' . $statelog [$state],
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
	 * 重置管理员的
	 * 密码
	 */
	function uppassword() {
		$id = intval ( $this->input->get_post ( 'id' ) );
		if ($id) {
			$this->load->helper ( 'string' );
			$rand = random_string ( 'alnum', 6 );
			$data ['password'] = md5 ( '123456' ) . md5 ( $rand );
			$data ['salt'] = $rand;
			$flag = $this->admin_model->save ( $id, $data );
			if ($flag) {
				$admininfo = $this->admin_model->get_one ( 'id = ' . $id );
				
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了管理员' . $admininfo->username . '的密码',
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
	}
	/**
	 * 检查邮箱 是否 重复
	 */
	function checkemail() {
		$email = trim ( $this->input->get ( 'email' ) );
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if (! empty ( $email )) {
			if (! preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email )) {
				// die ( json_encode ( 'Email address format is not correct ' ) );
				die ( json_encode ( '邮箱格式不正确！' ) );
			} else {
				$email_true = $this->admin_model->get_one ( array (
						'email' => $email 
				) );
				if ($email_true) {
					// die ( json_encode ( true ) );
					if ($email_true->id == $id) {
						die ( json_encode ( true ) );
					} else {
						die ( json_encode ( '邮箱已被占用' ) );
					}
				} else {
					// die ( json_encode ( 'Email does not exist ' ) );
					die ( json_encode ( true ) );
				}
			}
		} else {
			die ( json_encode ( '邮箱不能为空！' ) );
		}
	}
	
	/**
	 * 删除 关联表中数据也会删除
	 */
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$info = ( object ) $this->admin_model->get_one ( $where );
			if($info->groupid==4){
				$this->admin_model->delete_teacher($id);
			}
			$is = $this->admin_model->delete ( $where );
			if ($is === true) {
				
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '删除了管理员' . $info->username . '的信息',
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
				'username',
				'nikename',
				'tel',
				'mobile',
				'groupid',
				'lasttime',
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
	
	/**
	 * 修改个人 资料
	 */
	function profile() {
		$info = $this->admin_model->get_one ( 'id = ' . $_SESSION ['master_user_info']->id );
		$this->_view ( 'admin_profile', array (
				'info' => ! empty ( $info ) ? $info : array () 
		) );
	}
	
	/**
	 * 保存个人资料
	 */
	function saveprofile() {
		$data = $this->input->post ();
		if (! empty ( $data ['id'] )) {
			$id = $data ['id'];
			unset ( $data ['id'] );
			if (! empty ( $data )) {
				$flag = false;
				$flag = $this->admin_model->save ( $id, $data );
				if ($flag) {
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
	 * 修改密码
	 */
	function password() {
		$this->_view ( 'admin_password', array (
				'id' => $_SESSION ['master_user_info']->id 
		) );
	}
	
	/**
	 * 匹配 旧密码
	 */
	function checkpassword() {
		$old = trim ( $this->input->get ( 'old' ) );
		$id = intval ( $this->input->get ( 'id' ) );
		if ($old && $id) {
			$result = $this->admin_model->get_one ( 'id = ' . $id );
			$salt = $result->salt;
			$password = md5 ( $old ) . md5 ( $salt );
			if ($password != $result->password) {
				die ( json_encode ( '旧密码不正确！' ) );
			} else {
				die ( json_encode ( true ) );
			}
		} else {
			die ( json_encode ( '旧密码不能为空！' ) );
		}
	}
	
	/**
	 * 执行 修改密码
	 */
	function savepassword() {
		$password = trim ( $this->input->post ( 'password' ) );
		$id = intval ( trim ( $this->input->post ( 'id' ) ) );
		if ($id && $password) {
			$this->load->helper ( 'string' );
			$rand = random_string ( 'alnum', 6 );
			$data ['password'] = md5 ( $password ) . md5 ( $rand );
			$data ['salt'] = $rand;
			$flag = $this->admin_model->save ( $id, $data );
			if ($flag) {
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
}

