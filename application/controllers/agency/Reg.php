<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 前台 中介 控制器
 * 注册
 *
 * @author zyj
 *        
 */
class Reg extends Agency_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		$this->load->vars ( 'nationality', $nationality );
		$this->load->model ( 'agency/agency_model' );
	}
	
	/**
	 * 主页
	 */
	function index() {
		$backurl = '';
		$backurl = trim ( $this->input->get ( 'backurl' ) );
		$error = '';
		$this->load->helper ( 'string' );
		if ($this->input->is_ajax_request () === true) {
			$data = $this->input->post ();
			unset ( $data ['code'] );
			unset ( $data ['repassowrd'] );
			
			$rand = random_string ( 'alnum', 6 );
			$data ['password'] = md5 ( $data ['password'] ) . md5 ( $rand );
			$data ['salt'] = $rand;
			
			$data ['checkstate'] = - 1;
			$data ['createtime'] = time ();
			$data ['createip'] = get_client_ip ();
			$userinfo = $this->agency_model->add ( $data );
			if ($userinfo) {
				$_SESSION ['agency'] ['userinfo'] = $userinfo [0];
				if (! empty ( $backurl )) {
					$backurl = urldecode ( $backurl );
				}
				// 注册成功 发送邮件
				$web_email = CF ( 'web_agency_email', '', 'application/cache/' );
				$title = $web_email ['reg_success_email'] ['title'];
				$content = $this->load->view ( 'agency/email/reg_success_email', array (
						'title' => $title,
						'email' => $_SESSION ['agency'] ['userinfo'] ['email'] 
				), true );
				$this->_send_email ( $_SESSION ['agency'] ['userinfo'] ['email'], $title, $content );
				ajaxReturn ( $backurl, '', 1 );
			} else {
				ajaxReturn ( '', '', 2 );
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
						if ($email_true) {
							$error .= lang ( 'email_exist' ) . '<br />';
						}
					}
				}
				// 密码
				if (empty ( $data ['password'] )) {
					$error .= lang ( 'password_empty' ) . '<br />';
				} else {
					if (strlen ( $data ['password'] ) < 6) {
						$error .= lang ( 'password_length' ) . '<br />';
					}
				}
				// 确认密码
				if (empty ( $data ['repassowrd'] )) {
					$error .= lang ( 'repassowrd_empty' ) . '<br />';
				} else {
					if (strlen ( $data ['repassowrd'] ) < 6) {
						$error .= lang ( 'repassowrd_length' ) . '<br />';
					}
				}
				
				// 国籍
				if (empty ( $data ['nationality'] )) {
					$error .= lang ( 'nationality_empty' ) . '<br />';
				}
				
				// 兴趣
				if (empty ( $data ['interest'] )) {
					$error .= lang ( 'interest_empty' ) . '<br />';
				}
				
				// 获取信息方式
				if (empty ( $data ['inquiries'] )) {
					$error .= lang ( 'inquiries_empty' ) . '<br />';
				}
				
				if ($error == '' && ! empty ( $data )) {
					unset ( $data ['code'] );
					unset ( $data ['repassowrd'] );
					
					$rand = random_string ( 'alnum', 6 );
					$data ['password'] = md5 ( $data ['password'] ) . md5 ( $rand );
					$data ['salt'] = $rand;
					
					$data ['checkstate'] = - 1;
					$data ['createtime'] = time ();
					$data ['createip'] = get_client_ip ();
					$userinfo = $this->agency_model->add ( $data );
					if ($userinfo) {
						$_SESSION ['agency'] ['userinfo'] = $userinfo [0];
						// 注册成功 发送邮件
						$web_email = CF ( 'web_agency_email', '', 'application/cache/' );
						$title = $web_email ['reg_success_email'] ['title'];
						$content = $this->load->view ( 'agency/email/reg_success_email', array (
								'title' => $title,
								'email' => $_SESSION ['agency'] ['userinfo'] ['email'] 
						), true );
						$this->_send_email ( $_SESSION ['student'] ['userinfo'] ['email'], $title, $content );
						header ( "location:/agency" );
					}
				}
			}
		}
		$this->load->view ( 'agency/reg_index', array (
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
				$email_true = $this->agency_model->get_info_one ( $where );
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
			$data ['password'] = substr ( md5 ( $data ['password'] ), 0, 27 );
			$data ['registertime'] = time ();
			$data ['lasttime'] = time ();
			$data ['registerip'] = get_client_ip ();
			$userinfo = $this->user_model->add ( $data );
			if ($userinfo) {
				$this->user_model->save_extend ( array (
						'userid' => $userinfo [0] ['id'] 
				) );
				$_SESSION ['userinfo'] = $userinfo [0];
				// 发送邮件
				// $web_email = CF ( 'web_email' );
				// $title = $web_email ['reg_success_email'] ['title'];
				// $content = $this->load->view ( 'webemail/reg_success_email', array (
				// 'title' => $title,
				// 'email' => $_SESSION ['userinfo'] ['email']
				// ), true );
				// $this->_send_email ( $_SESSION ['userinfo'] ['email'], $title, $content );
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 2 );
			}
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

