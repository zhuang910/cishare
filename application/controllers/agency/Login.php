<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 前台 学生 登录
 *
 * @author zyj
 *        
 */
class Login extends agency_Basic {
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'agency/agency_model' );
	}
	
	/**
	 * 主页
	 */
	function index() {
		$backurl = '';
		$backurl = trim ( $this->input->get ( 'backurl' ) );
		$error = '';
		if ($this->input->is_ajax_request () === true) {
			$data = $this->input->post ();
			// 首先判断密码
			if (empty ( $data ['email'] )) {
				ajaxReturn ( array (
						'field' => 'email' 
				), lang ( 'email_empty' ), 0 );
			} else {
				if (! preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $data ['email'] )) {
					ajaxReturn ( array (
							'field' => 'email' 
					), lang ( 'email_error' ), 0 );
				} else {
					$where = array (
							'email' => $data ['email'] 
					);
					$email_true = $this->agency_model->get_info_one ( $where );
				}
			}
			
			if (empty ( $data ['password'] )) {
				ajaxReturn ( array (
						'field' => 'password' 
				), lang ( 'password_empty' ), 0 );
			} else {
				if (! empty ( $email_true )) {
					// 判断密码的正确性
					$salt = $email_true [0] ['salt'];
					$password = md5 ( $data ['password'] ) . md5 ( $salt );
					
					if ($password != $email_true [0] ['password']) {
						ajaxReturn ( array (
								'field' => 'email' 
						), lang ( 'login_error' ), 0 );
					} else {
						$_SESSION ['agency'] ['userinfo'] = $email_true [0];
						if (! empty ( $backurl )) {
							$backurl = urldecode ( $backurl );
						}
						ajaxReturn ( $backurl, lang ( 'login_success' ), 1 );
					}
				} else {
					ajaxReturn ( array (
							'field' => 'email' 
					), lang ( 'login_error' ), 0 );
				}
			}
		} else {
			// 禁用js 的极端方式
			$data = $this->input->post ();
			if (! empty ( $data )) {
				// 首先判断验证码
				if (empty ( $data ['code'] )) {
					$error .= lang ( 'code_empty' ) . '<br />';
				} else {
					if (md5 ( $data ['code'] ) != $_SESSION ['verify']) {
						
						$error .= lang ( 'code_error' ) . '<br>';
					}
				}
				
				// 判断邮箱
				if (empty ( $data ['email'] )) {
					$error .= lang ( 'email_empty' ) . '<br />';
				} else {
					// 邮箱格式
					if (! preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $data ['email'] )) {
						$error .= lang ( 'email_error' ) . '<br />';
					} else {
						$where = array (
								'email' => $data ['email'] 
						);
						$email_true = $this->agency_model->get_info_one ( $where );
						
						if (! $email_true) {
							$error .= lang ( 'login_error' ) . '<br />';
						}
					}
				}
				// 密码
				if (empty ( $data ['password'] )) {
					$error .= lang ( 'password_empty' ) . '<br />';
				}
				if ($error == '' && ! empty ( $data )) {
					// 判断密码的正确性
					$salt = $email_true [0] ['salt'];
					$password = md5 ( $data ['password'] ) . md5 ( $salt );
					
					if ($password != $email_true [0] ['password']) {
						$error .= lang ( 'login_error' ) . '<br />';
					} else {
						$_SESSION ['agency'] ['userinfo'] = $email_true [0];
						header ( "location:/agency" );
					}
				}
			}
		}
		$this->load->view ( 'agency/login_index', array (
				'error' => $error 
		) );
	}
	
	/**
	 * 弹出登录注册
	 */
	function ajax_login() {
		$courseid = intval ( trim ( $this->input->get ( 'courseid' ) ) );
		if ($this->input->is_ajax_request () === true) {
			$data = $this->input->post ();
			// 首先判断密码
			if (empty ( $data ['email'] )) {
				ajaxReturn ( array (
						'field' => 'email' 
				), lang ( 'email_empty' ), 0 );
			} else {
				if (! preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $data ['email'] )) {
					ajaxReturn ( array (
							'field' => 'email' 
					), lang ( 'email_error' ), 0 );
				} else {
					$where = array (
							'email' => $data ['email'] 
					);
					$email_true = $this->agency_model->get_info_one ( $where );
				}
			}
			
			if (empty ( $data ['password'] )) {
				ajaxReturn ( array (
						'field' => 'password' 
				), lang ( 'password_empty' ), 0 );
			} else {
				if (! empty ( $email_true )) {
					if (substr ( md5 ( $data ['password'] ), 0, 27 ) != $email_true [0] ['password']) {
						ajaxReturn ( array (
								'field' => 'email' 
						), lang ( 'login_error' ), 0 );
					} else {
						$_SESSION ['userinfo'] = $email_true [0];
						ajaxReturn ( '', lang ( 'login_success' ), 1 );
					}
				} else {
					ajaxReturn ( array (
							'field' => 'email' 
					), lang ( 'login_error' ), 0 );
				}
			}
		}
		$this->load->view ( 'agency/ajax_login', array (
				'courseid' => $courseid 
		) );
	}
	
	/**
	 * 验证邮箱是否存在
	 */
	function checkemail() {
		$email = trim ( $this->input->get ( 'email' ) );
		if ($email) {
			if (! preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email )) {
				die ( json_encode ( 'Please enter a valid email address.' ) );
			} else {
				$where = "state = 1 AND email = '{$email}'";
				$email_true = $this->agency_model->get_info_one ( $where );
				if ($email_true) {
					die ( json_encode ( true ) );
				} else {
					die ( json_encode ( 'Please enter a valid email address.' ) );
				}
			}
		}
	}
	
	/**
	 * 获取密码激活
	 */
	function cpassword() {
		$code = trim ( $this->input->get ( 'code' ) );
		$flag = 0;
		if (! empty ( $code )) {
			$decode_string = authcode ( base64_decode ( $code ), 'DECODE', 'cucas-confirm-address', 0 );
			$ver_info = explode ( '-', $decode_string );
			
			$where = "state = 1 AND id = " . $ver_info [0];
			$userinfo = $this->agency_model->get_info_one ( $where );
			if ($userinfo [0] ['email'] == $ver_info [1]) {
				$flag = 3;
			}
		}
		
		$this->load->view ( 'agency/login_cpassword', array (
				'flag' => $flag,
				'temp' => 2, // 激活码
				'uid' => ! empty ( $userinfo [0] ['id'] ) ? $userinfo [0] ['id'] : '',
				'email' => ! empty ( $userinfo [0] ['email'] ) ? $userinfo [0] ['email'] : '' 
		) );
	}
	
	/**
	 * 修改密码
	 */
	function docpassword() {
		$password = $this->input->post ( 'password' );
		$repassword = $this->input->post ( 'repassword' );
		$uid = $this->input->post ( 'uid' );
		if ($password == $repassword) {
			// 修改密码
			$this->load->helper ( 'string' );
			$rand = random_string ( 'alnum', 6 );
			$password = md5 ( $password ) . md5 ( $rand );
			$salt = $rand;
			$o = $this->agency_model->basic_update ( 'id = ' . $uid, array (
					'password' => $password ,
					'salt' => $salt
			) );
			if ($o) {
				// 修改成功 发邮件
				$info = $isverify = $this->agency_model->get_info_one ( 'id = ' . $uid );
				$web_email = CF ( 'web_agency_email', '', 'application/cache/' );
				$html = $this->_view ( 'agency/email/password_success_email', array (
						'email' => $info [0] ['email'],
						'flag' => 0,
						'title' => $web_email ['password_success_email'] ['title'] 
				), true );
				$this->load->library ( 'mymail' );
				$MAIL = new Mymail ();
				
				$MAIL->domail ( $info [0] ['email'], $web_email ['password_success_email'] ['title'], $html );
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 找回密码
	 */
	function fpassword() {
		$email = trim ( $this->input->post ( 'email' ) );
		if ($email) {
			$flag = 0; // 初始化 同时 也表示了 有没有此用户
			$where = "state = 1 AND email = '{$email}'";
			$userinfo = $this->agency_model->get_info_one ( $where );
			if (! empty ( $userinfo )) {
				$email_type = explode ( '@', $userinfo [0] ['email'] );
				$email_url = 'http://mail.' . $email_type [1] . '';
				// 加密函数
				$encode_email = base64_encode ( authcode ( $userinfo [0] ['id'] . '-' . $userinfo [0] ['email'] . '-cucas', 'ENCODE', 'cucas-confirm-address', 0 ) );
				
				$web_email = CF ( 'web_agency_email', '', 'application/cache/' );
				$url = "http://" . $_SERVER ['HTTP_HOST'] . "/agency/login/cpassword?code=" . $encode_email;
				$html = $this->load->view ( 'agency/email/password_active_email', array (
						'email' => $userinfo [0] ['email'],
						'url' => $url,
						'flag' => 1,
						'title' => $web_email ['password_active_email'] ['title'] 
				), true );
				$email_title = $web_email ['password_active_email'] ['title'] ? $web_email ['password_active_email'] ['title'] : 'CUCAS Service Team'; // $_POST['title'] ? $_POST['title'] : ''; // $email_content = $content ? $content : ''; // $_POST['content'] ? $_POST['content'] :
				$email_content = $html;
				$this->load->library ( 'mymail' );
				$MAIL = new Mymail ();
				$MAIL->domail ( $userinfo [0] ['email'], $web_email ['password_active_email'] ['title'], $html );
				$flag = 1;
			}
			$this->load->view ( 'agency/login_cpassword', array (
					'flag' => $flag,
					'email_url' => $email_url,
					'temp' => 1 
			) );
		} else {
			$this->load->view ( 'agency/login_fpassword' );
		}
	}
}
