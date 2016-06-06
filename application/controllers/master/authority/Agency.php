<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 权限管理 中介管理
 *
 * @author zyj
 *        
 */
class Agency extends Master_Basic {
	public $is_username = 0;
	public $is_email = 0;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/authority/';
		
		$this->load->model ( $this->view . 'agency_model' );
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
			
			$condition ['where'] ['checkstate'] = 1;
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->agency_model->count ( $condition );
			$output ['aaData'] = $this->agency_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$state = $item->state;
				$item->state = $this->_get_lists_state ( $item->state );
				$item->lasttime = ! empty ( $item->lasttime ) ? date ( 'Y-m-d H:i:s', $item->lasttime ) : '';
				$student_num = $this->agency_model->get_student_num ( $item->id );
				$item->student_num = '<a href="/master/agency/commission?agency_id=' . $item->id . '">' . $student_num . '</a>';
				$item->operation = '<div class="btn-group"><a class="btn btn-xs btn-info" href="/master/authority/agency/add?id=' . $item->id . '">编辑</a><button data-toggle="dropdown" class="btn btn-xs btn-info btn-white dropdown-toggle">
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
				$item->operation .= '</ul></div>';
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'agency_index' );
	}
	
	/**
	 * 添加
	 */
	function add() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		
		if ($id) {
			$result = $this->agency_model->get_one ( 'id =' . $id );
		}
		// 国籍
		$nationality = CF ( 'public', '', CACHE_PATH );
		$this->_view ( 'agency_edit', array (
				'info' => ! empty ( $result ) ? $result : array (),
				'nationality' => ! empty ( $nationality ['global_country_cn'] ) ? $nationality ['global_country_cn'] : array () 
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
				// 获取userid更新admianv表
				$userid = $this->agency_model->get_userid ( $id );
				if (! empty ( $userid )) {
					$this->agency_model->save_admin ( $userid, $data );
				}
				$flag = $this->agency_model->save ( $id, $data );
				
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了中介' . $data ['username'] . '的信息',
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
				$data ['checkstate'] = 1;
				// var_dump($data);exit;
				
				// 插入管理员表 权限组中介 groupid=8 返回userid
				$userid = $this->agency_model->save_admin ( null, $data );
				if (! empty ( $userid )) {
					$data ['userid'] = $userid;
					$flag = $this->agency_model->save ( null, $data );
				}
				
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '添加了中介' . $data ['username'] . '的信息',
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
	 * 修改的状态
	 */
	function upstate() {
		$id = intval ( $this->input->get_post ( 'id' ) );
		$state = intval ( $this->input->get_post ( 'state' ) );
		if (! empty ( $id )) {
			$result = $this->agency_model->save_audit ( $id, $state );
			if ($result === true) {
				$agencyinfo = $this->agency_model->get_one ( 'id = ' . $id );
				$statelog = array (
						'禁用',
						'启用' 
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
				$info=$this->db->get_where('agency_info','id = '.$id)->row_array();
				$this->db->update('admin_info',array('state'=>$state),'id = '.$info['userid']);
				ajaxReturn ( '', '更改成功', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 重置
	 * 密码
	 */
	function uppassword() {
		$id = intval ( $this->input->get_post ( 'id' ) );
		if ($id) {
			$this->load->helper ( 'string' );
			$rand = random_string ( 'alnum', 6 );
			$data ['password'] = md5 ( '123456' ) . md5 ( $rand );
			$data ['salt'] = $rand;
			$flag = $this->agency_model->save ( $id, $data );
			
			if ($flag) {
				$agencyinfo = $this->agency_model->get_one ( 'id = ' . $id );
				
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了中介' . $agencyinfo->username . '的密码',
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
				$email_true = $this->agency_model->get_one ( array (
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
					// 验证admin表邮箱唯一性
					$is = $this->agency_model->check_admin_email ( $email );
					if ($is > 0) {
						die ( json_encode ( '邮箱已被占用' ) );
					}
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
			$info = ( object ) $this->agency_model->get_one ( $where );
			$is = $this->agency_model->delete ( $where );
			if ($is === true) {
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '删除了中介' . $info->username . '的信息',
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
				'email',
				'tel',
				'mobile',
				'company',
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
	function check_username() {
		$username = trim ( $this->input->get ( 'username' ) );
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if (! empty ( $username )) {
			$username_true = $this->agency_model->get_one ( array (
					'username' => $username 
			) );
			
			if ($username_true) {
				// die ( json_encode ( true ) );
				if ($username_true->id == $id) {
					die ( json_encode ( true ) );
				} else {
					die ( json_encode ( '用户名已被占用' ) );
				}
			} else {
				// 验证admin表邮箱唯一性
				$is = $this->agency_model->check_admin_username ( $username );
				if ($is > 0) {
					die ( json_encode ( '用户名已被占用' ) );
				}
				// die ( json_encode ( 'Email does not exist ' ) );
				die ( json_encode ( true ) );
			}
		} else {
			die ( json_encode ( '邮箱不能为空！' ) );
		}
	}
}

