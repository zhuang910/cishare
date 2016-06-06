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
		$this->load->view ( 'student/login_index' );
	}
	
	/**
	 * 获取 登录的数据
	 */
	function do_login() {
		$backurl = '';
		$backurl = trim ( $this->input->get ( 'backurl' ) );
		// 获取数据
		if ($this->input->is_ajax_request () === true) {
			$data = $this->input->post ();
			$type = ! empty ( $data ['type'] ) ? $data ['type'] : 1;
			unset ( $data ['type'] );
			unset ( $data ['code'] );
			unset ( $data ['submit'] );
			// 首先是 获取 相应的 邮箱 或是 护照 或是 密码
			if (empty ( $data ['email'] )) {
				ajaxReturn ( array (
						'field' => 'email' 
				), lang ( 'email_empty_flag' ), 0 );
			}
			// 密码
			if (empty ( $data ['password'] )) {
				ajaxReturn ( array (
						'field' => 'password' 
				), lang ( 'password_empty' ), 0 );
			}
			
			// 有值 但是 不能等于
			if ($data ['email'] == lang ( 'login_flag' )) {
				ajaxReturn ( array (
						'field' => 'email' 
				), lang ( 'email_empty_flag' ), 0 );
			}
			
			if ($type == 1) {
				// 学生登录
				// 都不为空的情况下
				// 判断是否是 邮箱 登录
				if (strstr ( $data ['email'], '@' )) {
					if (! preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $data ['email'] )) {
						ajaxReturn ( array (
								'field' => 'email' 
						), lang ( 'email_error' ), 0 );
					}
					// 处理登录
					$flag = $this->handle_login ( $data );
					
					switch ($flag) {
						case 0 :
							ajaxReturn ( array (
									'field' => 'email' 
							), lang ( 'email_error' ), 0 );
							break;
						case 1 :
							if (! empty ( $backurl )) {
								$backurl = urldecode ( $backurl );
							}
							ajaxReturn ( $backurl, lang ( 'login_success' ), 1 );
							break;
						case 2 :
							ajaxReturn ( array (
									'field' => 'email' 
							), lang ( 'login_error' ), 0 );
							break;
					}
				} else {
					// 可能是 护照登录
					$falgPASS = $this->is_passport ( $data );
					switch ($falgPASS) {
						case 0 :
							ajaxReturn ( array (
									'field' => 'email' 
							), lang ( 'email_error' ), 0 );
							break;
						case 1 :
							if (! empty ( $backurl )) {
								$backurl = urldecode ( $backurl );
							}
							ajaxReturn ( $backurl, lang ( 'login_success' ), 1 );
							break;
						case 2 :
						
						case 3 :
							ajaxReturn ( array (
									'field' => 'email' 
							), lang ( 'login_error' ), 0 );
							break;
						case 4 :
							$html = $this->load->view ( 'student/login_email', array (
									'passport' => $data ['email'],
									'password' => $data ['password'] 
							), true );
							ajaxReturn ( $html, '', 4 );
							break;
					}
				}
			} else if ($type == 2) {
				// 社团帐号
				if (! preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $data ['email'] )) {
					ajaxReturn ( array (
							'field' => 'email' 
					), lang ( 'email_error' ), 0 );
				}
				// 处理登录
				$flag = $this->handle_login_society ( $data );
				
				switch ($flag) {
					case 0 :
						ajaxReturn ( array (
								'field' => 'email' 
						), lang ( 'email_error' ), 0 );
						break;
					case 1 :
						
						$backurl = '/' . $this->puri . '/society/activity/launch';
						ajaxReturn ( $backurl, lang ( 'login_success' ), 1 );
						break;
					case 2 :
						ajaxReturn ( array (
								'field' => 'email' 
						), lang ( 'login_error' ), 0 );
						break;
				}
			}
		}
	}
	
	/**
	 * 处理登录的函数
	 * 接到的值是 密码和邮箱
	 */
	function handle_login($data = null) {
		if ($data != null) {
			$where = array (
					'email' => $data ['email'] 
			);
			// 通过邮箱 获取数据
			$email_true = $this->student_model->get_info_one ( $where );
			if (! empty ( $email_true )) {
				if (substr ( md5 ( $data ['password'] ), 0, 27 ) != $email_true [0] ['password']) {
					return 2;
				} else {
					$_SESSION ['student'] ['userinfo'] = $email_true [0];
					return 1;
				}
			} else {
				// 登录错误信息
				return 2;
			}
		}
		// 数据为空 邮箱和密码为空
		return 0;
	}
	
	/**
	 * 处理登录的函数
	 * 接到的值是 密码和邮箱
	 */
	function handle_login_society($data = null) {
		if ($data != null) {
			$where = array (
					'email' => $data ['email'],
					'state' => 1 
			);
			// 通过邮箱 获取数据
			$email_true = $this->student_model->get_info_society_one ( $where );
			
			if (! empty ( $email_true )) {
				if (md5 ( $data ['password'] ) . md5 ( $email_true [0] ['salt'] ) != $email_true [0] ['password']) {
					
					return 2;
				} else {
					$_SESSION ['society'] ['userinfo'] = $email_true [0];
					return 1;
				}
			} else {
				// 登录错误信息
				return 2;
			}
		}
		// 数据为空 邮箱和密码为空
		return 0;
	}
	
	/**
	 * 验证 护照登录
	 */
	function is_passport($data = null) {
		if ($data != null) {
			$where = array (
					'passport' => $data ['email'],
					'password' => substr ( md5 ( $data ['password'] ), 0, 27 ) 
			);
			// 通过护照号 查询 是否存在此用户 获取数据
			$passport_true = $this->student_model->get_info_one ( $where );
			if (empty ( $passport_true )) {
				// 直接返回 邮箱或护照 不正确
				return 3;
			}
			// 存在 用户 判断一下 邮箱 不为空
			if (! empty ( $passport_true [0] ['email'] )) {
				$flagP = $this->handle_login ( array (
						'email' => $passport_true [0] ['email'],
						'password' => $data ['password'] 
				) );
				return $flagP;
			}
			// 跳到设置邮箱的页面
			return 4;
		}
		// 数据为空
		return 0;
	}
	
	/**
	 * 修改邮箱
	 */
	function edit_email_login() {
		$data = $this->input->post ();
		if (empty ( $data )) {
			ajaxReturn ( '', lang ( 'email_empty' ), 0 );
		}
		
		// 格式
		if (! preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $data ['emails'] )) {
			ajaxReturn ( '', lang ( 'email_error' ), 0 );
		}
		
		// 是否存在
		
		$where = "email = '{$data ['emails']}'";
		$email_exist = $this->student_model->get_info_one ( $where );
		if ($email_exist) {
			ajaxReturn ( '', lang ( 'email_exist' ), 0 );
		}
		
		$flag_email = $this->db->update ( 'student_info', array (
				'email' => $data ['emails'] 
		), array (
				'passport' => $data ['passport'] 
		) );
		if ($flag_email) {
			// 处理登录
			$flag = $this->handle_login ( array (
					'email' => $data ['emails'],
					'password' => $data ['password'] 
			) );
			
			switch ($flag) {
				case 0 :
					ajaxReturn ( '', lang ( 'email_empty' ), 0 );
					break;
				case 1 :
					
					ajaxReturn ( '', lang ( 'login_success' ), 1 );
					break;
				case 2 :
					ajaxReturn ( '', lang ( 'login_error' ), 0 );
					break;
			}
		} else {
			ajaxReturn ( '', lang ( 'email_empty' ), 0 );
		}
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
				// $this->load->library ( 'mymail' );
				// $MAIL = new Mymail ();
				
				// $MAIL->domail ( $info [0] ['email'], $web_email ['password_success_email'] ['title'], $html );
				$dataemail = array (
						'email' => $info [0] ['email'] 
				);
				$MAIL = new sdyinc_email ();
				$MAIL->dot_send_mail ( 30, $info [0] ['email'], $dataemail );
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
				// $html = $this->load->view ( 'student/email/password_active_email', array (
				// 'email' => $userinfo [0] ['email'],
				// 'url' => $url,
				// 'flag' => 1,
				// 'title' => $web_email ['password_active_email'] ['title']
				// ), true );
				// $email_title = $web_email ['password_active_email'] ['title'] ? $web_email ['password_active_email'] ['title'] : 'CUCAS Service Team'; // $_POST['title'] ? $_POST['title'] : ''; // $email_content = $content ? $content : ''; // $_POST['content'] ? $_POST['content'] :
				// $email_content = $html;
				// $this->load->library ( 'mymail' );
				// $MAIL = new Mymail ();
				// $MAIL->domail ( $userinfo [0] ['email'], $web_email ['password_active_email'] ['title'], $html );
				$flag = 1;
				$dataemail = array (
						'email' => $userinfo [0] ['email'],
						'url' => $url 
				);
				$MAIL = new sdyinc_email ();
				$MAIL->dot_send_mail ( 6, $userinfo [0] ['email'], $dataemail );
			}
			$this->load->view ( 'student/login_cpassword', array (
					'flag' => $flag,
					'email_url' => $email_url,
					'temp' => 1,
					'email' => $userinfo [0] ['email'] 
			) );
		} else {
			$this->load->view ( 'student/login_fpassword' );
		}
	}
	
	/**
	 * 找回密码 重新发送邮件
	 */
	function cucasemail() {
		$email = trim ( $this->input->get ( 'email' ) );
		if (! empty ( $email )) {
			$where = "state = 1 AND email = '{$email}'";
			$userinfo = $this->student_model->get_info_one ( $where );
			// 加密函数
			$encode_email = base64_encode ( authcode ( $userinfo [0] ['id'] . '-' . $userinfo [0] ['email'] . '-cucas', 'ENCODE', 'cucas-confirm-address', 0 ) );
			
			$web_email = CF ( 'web_student_email', '', 'application/cache/' );
			$url = "http://" . $_SERVER ['HTTP_HOST'] . '/' . $this->puri . "/student/login/cpassword?code=" . $encode_email;
			// $html = $this->load->view ( 'student/email/password_active_email', array (
			// 'email' => $userinfo [0] ['email'],
			// 'url' => $url,
			// 'flag' => 1,
			// 'title' => $web_email ['password_active_email'] ['title']
			// ), true );
			// $email_title = $web_email ['password_active_email'] ['title'] ? $web_email ['password_active_email'] ['title'] : 'CUCAS Service Team'; // $_POST['title'] ? $_POST['title'] : ''; // $email_content = $content ? $content : ''; // $_POST['content'] ? $_POST['content'] :
			// $email_content = $html;
			// $this->load->library ( 'mymail' );
			// $MAIL = new Mymail ();
			// $MAIL->domail ( $userinfo [0] ['email'], $web_email ['password_active_email'] ['title'], $html );
			$dataemail = array (
					'email' => $userinfo [0] ['email'],
					'url' => $url,
					'flag' => 1,
					'title' => $web_email ['password_active_email'] ['title'] 
			);
			$MAIL = new sdyinc_email ();
			$MAIL->dot_send_mail ( 6, $userinfo [0] ['email'], $dataemail );
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
		echo '<script>window.parent.location.href="/' . $this->puri . '/student/login";</script>';
		die ();
	}
}
