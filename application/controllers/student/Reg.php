<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 前台 学生 控制器
 * 注册
 *
 * @author zyj
 *        
 */
class Reg extends Student_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		$this->load->vars ( 'nationality', $nationality );
		$this->load->model ( 'student/student_model' );
		$this->load->library ( 'sdyinc_email' );
		
	}
	
	/**
	 * 主页
	 */
	function index() {
		$this->load->library ( 'sdyinc_email' );
		$backurl = '';
		$backurl = trim ( $this->input->get ( 'backurl' ) );
		$error = '';
		if ($this->input->is_ajax_request () === true) {
			$data = $this->input->post ();
			unset ( $data ['code'] );
			unset ( $data ['repassowrd'] );
			unset ( $data ['submit'] );
			$data ['password'] = substr ( md5 ( $data ['password'] ), 0, 27 );
			$data ['registertime'] = time ();
			$data ['lasttime'] = time ();
			$data ['registerip'] = get_client_ip ();
			$data ['isactive'] = 0;
			$userinfo = $this->student_model->add ( $data );
			if ($userinfo) {
				$_SESSION ['student'] ['userinfo'] = $userinfo [0];
				if (! empty ( $backurl )) {
					$backurl = urldecode ( $backurl );
				}
				// 注册成功 发送邮件
				$web_email = CF ( 'web_student_email', '', 'application/cache/' );
				$title = $web_email ['reg_success_email'] ['title'];
				
				// 加密函数
				$encode_email = base64_encode ( authcode ( $userinfo [0] ['id'] . '-' . $userinfo [0] ['email'] . '-cucas', 'ENCODE', 'cucas-confirm-address', 0 ) );
				$url = "http://" . $_SERVER ['HTTP_HOST'] . '/' . $this->puri . "/student/reg/dosuccess?code=" . $encode_email;
				$content = $this->load->view ( 'student/email/reg_success_email', array (
						'title' => $title,
						'email' => $_SESSION ['student'] ['userinfo'] ['email'],
						'url' => $url 
				), true );
				$dataemail = array(
						'title' => $title,
						'email' => $_SESSION ['student'] ['userinfo'] ['email'],
						'url' => $url
				);
				//$this->_send_email ( $_SESSION ['student'] ['userinfo'] ['email'], $title, $content );
				$MAIL = new sdyinc_email ();
				$MAIL->dot_send_mail ( 4,$_SESSION ['student'] ['userinfo'] ['email'],$dataemail);
				ajaxReturn ( '', $_SESSION ['student'] ['userinfo'] ['email'], 1 );
			} else {
				ajaxReturn ( '', lang ( 'reg_fail' ), 2 );
			}
		}
		$this->load->view ( 'student/reg_index', array (
				'error' => $error 
		) );
	}
	
	/**
	 * 邮箱验证
	 */
	function checkemail() {
		$email = $this->input->get ( 'email' );
		if (! empty ( $email )) {
			if (! preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email )) {
				die ( json_encode ( lang ( 'email_error' ) ) );
			} else {
				$where = array (
						'email' => $email 
				);
				$email_true = $this->student_model->get_info_one ( $where );
				if ($email_true) {
					die ( json_encode ( lang ( 'email_exist' ) ) );
				} else {
					die ( json_encode ( true ) );
				}
			}
		}
	}
	
	/**
	 * 验证码
	 */
	public function verify() {
		$this->load->library ( 'verify' );
		$this->verify->buildImageVerify ();
	}
	
	/**
	 * 检查验证码
	 */
	function checkcode() {
		$verify = $this->input->get ( 'code' );
		if (! empty ( $verify )) {
			
			if (md5 ( $verify ) != $_SESSION ['verify']) {
				
				die ( json_encode ( lang ( 'code_error' ) ) );
			} else {
				
				die ( json_encode ( true ) );
			}
		}
	}
	
	/**
	 * ajax 注册
	 */
	function ajax_reg() {
		if ($this->input->is_ajax_request () === true) {
			$data = $this->input->post ();
			if (! empty ( $data ['courseid'] )) {
				$courseid = $data ['courseid'];
			}
			unset ( $data ['courseid'] );
			$data ['password'] = substr ( md5 ( $data ['password'] ), 0, 27 );
			$data ['registertime'] = time ();
			$data ['lasttime'] = time ();
			$data ['registerip'] = get_client_ip ();
			$data ['isactive'] = 0;
			if (! empty ( $data ['backurl'] )) {
				$backurl = $data ['backurl'];
			}
			unset ( $data ['backurl'] );
			$userinfo = $this->student_model->add ( $data );
			if ($userinfo) {
				
				$_SESSION ['student'] ['userinfo'] = $userinfo [0];
				// 发送邮件
				// $web_email = CF ( 'web_email' );
				// $title = $web_email ['reg_success_email'] ['title'];
				// $content = $this->load->view ( 'webemail/reg_success_email', array (
				// 'title' => $title,
				// 'email' => $_SESSION ['userinfo'] ['email']
				// ), true );
				// $this->_send_email ( $_SESSION ['userinfo'] ['email'], $title, $content );
				if (! empty ( $courseid )) {
					ajaxReturn ( '/' . $this->puri . '/student/apply?courseid=' . cucas_base64_encode ( $courseid ), '注册成功！', 1 );
				} else if (! empty ( $backurl )) {
					ajaxReturn ( $backurl, '注册成功！', 1 );
				}
			} else {
				ajaxReturn ( '', '', 2 );
			}
		}
	}
	
	/**
	 * 注册成功页面
	 */
	function success() {
		$email = trim ( $this->input->get ( 'email' ) );
		if (! empty ( $email )) {
			$where = "state = 1 AND email = '{$email}'";
			$info = $this->student_model->get_info_one ( $where );
			$email_type = explode ( '@', $email );
			$email_url = 'http://mail.' . $email_type [1] . '';
			$this->load->view ( 'student/reg_success', array (
					'email' => $email,
					'info' => ! empty ( $info ) ? $info [0] : array (),
					'email_url' => ! empty ( $email_url ) ? $email_url : '' 
			) );
		}
	}
	
	/**
	 * 执行验证的操作
	 */
	function dosuccess() {
		$code = trim ( $this->input->get ( 'code' ) );
		if (! empty ( $code )) {
			$decode_string = authcode ( base64_decode ( $code ), 'DECODE', 'cucas-confirm-address', 0 );
			$ver_info = explode ( '-', $decode_string );
			$email = ! empty ( $ver_info [1] ) ? $ver_info [1] : '';
			$flag = $this->student_model->basic_update ( "email = '{$email}'", array (
					'isactive' => 1 
			) );
			$this->load->view ( 'student/reg_dosuccess', array (
					'email' => $email 
			) );
		}
	}
	
	/**
	 * 注册成功 重新发送邮件
	 */
	function resendemail() {
		$email = trim ( $this->input->get ( 'email' ) );
		if (! empty ( $email )) {
			// 注册成功 发送邮件
			$web_email = CF ( 'web_student_email', '', 'application/cache/' );
			$title = $web_email ['reg_success_email'] ['title'];
			$where = "state = 1 AND email = '{$email}'";
			$info = $this->student_model->get_info_one ( $where );
			// 加密函数
			$encode_email = base64_encode ( authcode ( $info [0] ['id'] . '-' . $info [0] ['email'] . '-cucas', 'ENCODE', 'cucas-confirm-address', 0 ) );
			$url = "http://" . $_SERVER ['HTTP_HOST'] . '/' . $this->puri . "/student/reg/dosuccess?code=" . $encode_email;
			$content = $this->load->view ( 'student/email/reg_success_email', array (
					'title' => $title,
					'email' => $email,
					'url' => $url 
			), true );
			
			//$this->_send_email ( $email, $title, $content );
			$dataemail = array(
					'title' => $title,
					'email' => $email,
					'url' => $url
			);
			$MAIL = new sdyinc_email ();
			$MAIL->dot_send_mail ( 4,$email,$dataemail);
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
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

