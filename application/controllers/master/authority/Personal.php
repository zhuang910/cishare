<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 管理员管理
 *
 * @author zyj
 *        
 */
class Personal extends Master_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/authority/';
		
		$this->load->model ( $this->view . 'personal_model' );
		
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
				$email_true = $this->personal_model->get_one ( array (
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
	 * 获取 群组的名称
	 *
	 * @param string $statecode        	
	 * @param string $stateindexcode        	
	 * @return string
	 */
	private function _get_lists_group($groupid = null) {
		if ($groupid != null) {
			// 获取管理员的群组
			$group = $this->personal_model->get_group ( 'type = 1' );
			
			return ! empty ( $group [$groupid] ) ? $group [$groupid] : '';
		}
		return;
	}
	
	/**
	 * 修改个人 资料
	 */
	function profile() {
		$info = $this->personal_model->get_one ( 'id = ' . $_SESSION ['master_user_info']->id );
		$this->_view ( 'personal_profile', array (
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
				$flag = $this->personal_model->save ( $id, $data );
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
		$this->_view ( 'personal_password', array (
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
			$result = $this->personal_model->get_one ( 'id = ' . $id );
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
			$flag = $this->personal_model->save ( $id, $data );
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
	 * 修改头像
	 */
	function editphoto(){
	
		if (! empty ( $_FILES ['avatar'] ['name'] )) {
				$data ['image'] = $this->_upload ();
				$flag = $this->db->update('ci_admin_info',$data,'id ='.$_SESSION['master_user_info']->id);
				$result = $this->db->select('image')->get_where('ci_admin_info','id ='.$_SESSION['master_user_info']->id)->row();
				$_SESSION['master_user_info']->image = $result->image;
				ajaxReturn($result->image,'',1);
			}else{
				ajaxReturn('','',0);
			}
			
		
			
	}
	
	/**
	 * 上传
	 *
	 * @return string
	 */
	private function _upload() {
		$config = array (
				'save_path' => '/uploads/admin/' . date ( 'Ym' ) . '/' . date ( 'd' ),
				'upload_path' => $_SERVER ['DOCUMENT_ROOT'] . '/uploads/admin/' . date ( 'Ym' ) . '/' . date ( 'd' ),
				'allowed_types' => 'jpeg|jpg|png',
				'file_name' => time () . rand ( 100000, 999999 )
		);
	
		if (! empty ( $config )) {
			$this->load->library ( 'upload', $config );
			// 创建目录
			mk_dir ( $config ['upload_path'] );
				
			if (! $this->upload->do_upload ( 'avatar' )) {
				ajaxReturn ( '', $this->upload->display_errors ( '', '' ), 0 );
			} else {
				$imgdata = $this->upload->data ();
				return $config ['save_path'] . '/' . $imgdata ['file_name'];
			}
		}
	}
	//设置语言
	function set_language(){
		//获取控制器方法名
		$la=$this->input->get('v');
		foreach ($this->site_language_admin as $k => $v) {
			if($la==$v){
				$_SESSION['language']=$k;
			}	
		}
		ajaxReturn('','',1);
	}
	
}

