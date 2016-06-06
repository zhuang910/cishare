<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Faculty extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/basic/';
		$this->load->model($this->view.'faculty_model');
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
				
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->faculty_model->count ( $condition );
			$output ['aaData'] = $this->faculty_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$item->state = $this->_get_lists_state ( $item->state );
				$item->operation = '

					<a class="btn btn-xs btn-info" href="' . $this->zjjp .'faculty'. '/edit?id=' . $item->id .'">编辑</a>
					<a href="javascript:;" onclick="del('.$item->id.')" class="btn btn-xs btn-info btn-white">删除</a>
				';
				/*
				 * $item->operation = ' <a title="查看" class="btn btn-small btn-success" href="javascript:pub_alert_html(\'' . $this->zjjp . '/edit?id=' . $item->id . '\',true,true);"><i class="icon-edit"></i></a> <a title="审核" class="btn btn-small btn-success" href="javascript:pub_alert_confirm(this,\'确定要修改吗？\',\'' . $this->zjjp . '/editstate?id=' . $item->id . '\');"><i class="icon-remove"></i></a> ';
				*/
			}
			//var_dump($output);die;	
			exit ( json_encode ( $output ) );
		}
		$this->_view ('faculty_index');
	}
	function edit(){
		$id=intval($this->input->get('id'));
		if($id){
			$where="id={$id}";
			$info=$this->faculty_model->get_one($where);
			if(empty($info)){
				ajaxReturn('','该学院不存在',0);
			}
		}
		//获取该学院登录的信息
		$admin_info=$this->db->where('id ='.$info->userid)->get('admin_info')->row_array();
		$info->password=$admin_info['password'];
		$info->username=$admin_info['username'];
		$info->nikename=$admin_info['nikename'];
		$info->email=$admin_info['email'];
        $info->salt=$admin_info['salt'];
		$this->_view ( 'faculty_edit', array (
					
					'info' => $info ,
					
			) );
	}
		
	
	function add() {


		$this->_view ('faculty_edit');
	}
	
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		
		if ($id) {
			//删除admmin表信息
			$info=$this->db->where('id = '.$id)->get('faculty')->row_array();
			$this->db->delete('admin_info','id ='.$info['userid']);
			$where = "id = {$id}";
			$is = $this->faculty_model->delete ( $where );

			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	function update() {
		$id = intval ( $this->input->post ( 'id' ) );
		if ($id) {
			$data=$this->input->post();
			
			
			// 保存基本信息
			$this->faculty_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	/**
	 * 插入
	 */
	function insert() {
		
		$data = $this->input->post();
		//var_dump($data);exit;
		if (! empty ( $data )) {
			
			$id = $this->faculty_model->save ( null, $data );
			if ($id) {
				
				ajaxReturn ( 'back', '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'name',
				'englishname',
				'address',
				'teachername',
				'phone',
				'state',
				
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

        if(!empty($data['salt'])){
            $salt = trim($data['salt']);
        }
		$this->load->helper ( 'string' );
		if (! empty ( $data )) {

			if ($id != null) {

				// 编辑管理员
				// 如果没有修改密码 则还原老的密码
				if (empty ( $data ['password'] )) {
					$data ['password'] = $oldpass;
                    $data['salt'] = $salt;
				} else {
					$rand = random_string ( 'alnum', 6 );
					$data ['password'] = md5 ( $data ['password'] ) . md5 ( $rand );
					$data ['salt'] = $rand;
				}
				//获取userid更新admianv表
				$where="id={$id}";
				$faculty_info=$this->faculty_model->get_one($where);
				$userid=$faculty_info->userid;
				if(!empty($userid)){
					$this->faculty_model->save_admin($userid,$data);
				}
					unset($data['username']);
					unset($data['nikename']);
					unset($data['password']);
					unset($data['email']);
					unset($data['salt']);
				$flag = $this->faculty_model->save ( $id, $data );
				
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了中介' . $data ['name'] . '的信息',
						'ip' => get_client_ip (),
						'lasttime' => time () 
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
					ajaxReturn('','',1);

				}
				ajaxReturn('','',0);
			} else {
				$rand = random_string ( 'alnum', 6 );
				$data ['password'] = md5 ( $data ['password'] ) . md5 ( $rand );
				$data ['salt'] = $rand;
				// var_dump($data);exit;
				
				//插入管理员表   权限组中介  groupid=8  返回userid
				$userid=$this->faculty_model->save_admin(null,$data);
				if(!empty($userid)){
					unset($data['username']);
					unset($data['nikename']);
					unset($data['password']);
					unset($data['email']);
					unset($data['salt']);
					$data['userid']=$userid;
					$flag = $this->faculty_model->save ( null, $data );

				}
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '添加了学院' . $data ['name'] . '的信息',
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


	function check_username(){
		$username = trim ( $this->input->get ( 'username' ) );
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if (! empty ( $username )) {
				$username_true =$this->db->where('id ='.$id)->get('admin_info')->row_array();
				if ($username_true) {
					// die ( json_encode ( true ) );
					if ($username_true['username'] == $username) {
						die ( json_encode ( true ) );
					} else {
						//验证admin表邮箱唯一性
						$is=$this->faculty_model->check_admin_username($username);
						if($is>0){
							die ( json_encode ( '用户名已被占用' ) );
						}
						// die ( json_encode ( 'Email does not exist ' ) );
						die ( json_encode ( true ) );
					}
				} else {
					//验证admin表邮箱唯一性
					$is=$this->faculty_model->check_admin_username($username);
					if($is>0){
						die ( json_encode ( '用户名已被占用' ) );
					}
					// die ( json_encode ( 'Email does not exist ' ) );
					die ( json_encode ( true ) );
				}
		} else {
			die ( json_encode ( '用户名不能为空！' ) );
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
				$email_true  =$this->db->where('id ='.$id)->get('admin_info')->row_array();
				if ($email_true) {
					// die ( json_encode ( true ) );
					if ($email_true['email'] == $email) {
						die ( json_encode ( true ) );
					} else {
						//验证admin表邮箱唯一性
						$is=$this->faculty_model->check_admin_email($email);
						if($is>0){
							die ( json_encode ( '邮箱已被占用' ) );
						}
						// die ( json_encode ( 'Email does not exist ' ) );
						die ( json_encode ( true ) );
					}
				} else {
					//验证admin表邮箱唯一性
					$is=$this->faculty_model->check_admin_email($email);
					if($is>0){
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
}