<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 前台 学生 登录
 *
 * @author zyj
 *        
 */
class Login extends Student_Basic {
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'student/student_model' );
		$this->load->library ( 'sdyinc_email' );
	}
	
	/**
	 * 主页
	 */
	function index() {
		var_dump(123);exit;
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
					$email_true = $this->student_model->get_info_one ( $where );
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
						$_SESSION ['student'] ['userinfo'] = $email_true [0];
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
		}
		$this->load->view ( 'student/login_index', array (
				'error' => $error
		) );
	}
	
	/**
	 * 弹出登录注册
	 */
	function ajax_login() {
		if ($this->input->is_ajax_request () === true) {
			$data = $this->input->post ();
			
			if (! empty ( $data ['courseid'] )) {
				$courseid = $data ['courseid'];
			}
			if (! empty ( $data ['backurl'] )) {
				$backurl = $data ['backurl'];
			}
			unset ( $data ['backurl'] );
			unset ( $data ['courseid'] );
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
					$email_true = $this->student_model->get_info_one ( $where );
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
						$_SESSION ['student'] ['userinfo'] = $email_true [0];
						if (! empty ( $courseid )) {
							ajaxReturn ( '/' . $this->puri . '/student/apply?courseid=' . cucas_base64_encode ( $courseid ), lang ( 'login_success' ), 1 );
						}
						if (! empty ( $backurl )) {
							ajaxReturn ( $backurl, '', 1 );
						}
					}
				} else {
					ajaxReturn ( array (
							'field' => 'email' 
					), lang ( 'login_error' ), 0 );
				}
			}
		}
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
				$email_true = $this->student_model->get_info_one ( $where );
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
			$userinfo = $this->student_model->get_info_one ( $where );
			if ($userinfo [0] ['email'] == $ver_info [1]) {
				$flag = 3;
			}
		}
		
		$this->load->view ( 'student/login_cpassword', array (
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
			$password = substr ( md5 ( $password ), 0, 27 );
			$o = $this->student_model->basic_update ( 'id = ' . $uid, array (
					'password' => $password 
			) );
			if ($o) {
				// 修改成功 发邮件
				$info = $isverify = $this->student_model->get_info_one ( 'id = ' . $uid );
				$web_email = CF ( 'web_student_email', '', 'application/cache/' );
				$html = $this->_view ( 'student/email/password_success_email', array (
						'email' => $info [0] ['email'],
						'flag' => 0,
						'title' => $web_email ['password_success_email'] ['title'] 
				), true );
// 				$this->load->library ( 'mymail' );
// 				$MAIL = new Mymail ();
				
// 				$MAIL->domail ( $info [0] ['email'], $web_email ['password_success_email'] ['title'], $html );
				$dataemail = array(
						'email' => $info [0] ['email']
						
				);
				$MAIL = new sdyinc_email ();
				$MAIL->dot_send_mail ( 4,$info [0] ['email'],$dataemail);
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
			$userinfo = $this->student_model->get_info_one ( $where );
			if (! empty ( $userinfo )) {
				$email_type = explode ( '@', $userinfo [0] ['email'] );
				$email_url = 'http://mail.' . $email_type [1] . '';
				// 加密函数
				$encode_email = base64_encode ( authcode ( $userinfo [0] ['id'] . '-' . $userinfo [0] ['email'] . '-cucas', 'ENCODE', 'cucas-confirm-address', 0 ) );
				
				$web_email = CF ( 'web_student_email', '', 'application/cache/' );
				$url = "http://" . $_SERVER ['HTTP_HOST'] . '/' . $this->puri . "/student/login/cpassword?code=" . $encode_email;
// 				$html = $this->load->view ( 'student/email/password_active_email', array (
// 						'email' => $userinfo [0] ['email'],
// 						'url' => $url,
// 						'flag' => 1,
// 						'title' => $web_email ['password_active_email'] ['title'] 
// 				), true );
// 				$email_title = $web_email ['password_active_email'] ['title'] ? $web_email ['password_active_email'] ['title'] : 'CUCAS Service Team'; // $_POST['title'] ? $_POST['title'] : ''; // $email_content = $content ? $content : ''; // $_POST['content'] ? $_POST['content'] :
// 				$email_content = $html;
// 				$this->load->library ( 'mymail' );
// 				$MAIL = new Mymail ();
// 				$MAIL->domail ( $userinfo [0] ['email'], $web_email ['password_active_email'] ['title'], $html );
				$flag = 1;
				$dataemail = array(
						'email' => $userinfo [0] ['email'],
						'url' => $url,
						'flag' => 1,
						'title' => $web_email ['password_active_email'] ['title']
				);
				$MAIL = new sdyinc_email ();
				$MAIL->dot_send_mail ( 6, $userinfo [0] ['email'],$dataemail);
			}
			$this->load->view ( 'student/login_cpassword', array (
					'flag' => $flag,
					'email_url' => $email_url,
					'temp' => 1 ,
					'email' => $userinfo [0] ['email']
			) );
		} else {
			$this->load->view ( 'student/login_fpassword' );
		}
	}
	
	/**
	 * 找回密码 重新发送邮件
	 */
	function resendemail() {
		$email = trim ( $this->input->get ( 'email' ) );
		if (! empty ( $email )) {
			$where = "state = 1 AND email = '{$email}'";
			$userinfo = $this->student_model->get_info_one ( $where );
			// 加密函数
			$encode_email = base64_encode ( authcode ( $userinfo [0] ['id'] . '-' . $userinfo [0] ['email'] . '-cucas', 'ENCODE', 'cucas-confirm-address', 0 ) );
			
			$web_email = CF ( 'web_student_email', '', 'application/cache/' );
			$url = "http://" . $_SERVER ['HTTP_HOST'] . '/' . $this->puri . "/student/login/cpassword?code=" . $encode_email;
// 			$html = $this->load->view ( 'student/email/password_active_email', array (
// 					'email' => $userinfo [0] ['email'],
// 					'url' => $url,
// 					'flag' => 1,
// 					'title' => $web_email ['password_active_email'] ['title'] 
// 			), true );
// 			$email_title = $web_email ['password_active_email'] ['title'] ? $web_email ['password_active_email'] ['title'] : 'CUCAS Service Team'; // $_POST['title'] ? $_POST['title'] : ''; // $email_content = $content ? $content : ''; // $_POST['content'] ? $_POST['content'] :
// 			$email_content = $html;
// 			$this->load->library ( 'mymail' );
// 			$MAIL = new Mymail ();
// 			$MAIL->domail ( $userinfo [0] ['email'], $web_email ['password_active_email'] ['title'], $html );
			$dataemail = array(
					'email' => $userinfo [0] ['email'],
					'url' => $url,
					'flag' => 1,
					'title' => $web_email ['password_active_email'] ['title']
			);
			$MAIL = new sdyinc_email ();
			$MAIL->dot_send_mail ( 6, $userinfo [0] ['email'],$dataemail);
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 退出登录
	 */
	function out() {
		session_destroy ();
		// ajaxReturn ( '', '', 1 );
		echo '<script>window.parent.location.href="/'.$this->puri.'/student/login";</script>';
		die ();
	}
}
