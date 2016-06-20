<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 登陆
 *
 * @author zhuangqianlin
 *        
 */
class Login extends BASE_Ext {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'admin/core/';
		
		$this->load->model ( $this->view . 'user_model' );
	}
	
	/**
	 * 登陆页面
	 */
	function index() {
		$this->_view ();
	}
	
	/**
	 * 登陆
	 */
	function dologin() {
		if (IS_AJAX) {
			// // //来读一下汇率
			// $rate=CF('rate','',CONFIG_PATH);
			// if(empty($rate)){
			// 	//开始读汇率存到配置文件中
			// 	$rmb_to_usd=get_rate ( 'CNY', 'USD' );
			// 	$usd_to_rmb=get_rate ( 'USD', 'CNY' );
			// 	$date=strtotime(date("Y-m-d H:i:s",mktime(0,0,0,date('d',time()),date('m',time()),date('Y',time()))))+3600*24;
			// 	$data['rmb_to_usd']=$rmb_to_usd;
			// 	$data['usd_to_rmb']=$usd_to_rmb;
			// 	$data['date']=$date;
			// 	$rate=CF('rate',$data,CONFIG_PATH);

			// }else{
			// 	if($rate['date']<time()){
			// 		$rmb_to_usd=get_rate ( 'CNY', 'USD' );
			// 		$usd_to_rmb=get_rate ( 'USD', 'CNY' );
			// 		$date=strtotime(date("Y-m-d H:i:s",mktime(0,0,0,date('d',time()),date('m',time()),date('Y',time()))))+3600*24;
			// 		$data['rmb_to_usd']=$rmb_to_usd;
			// 		$data['usd_to_rmb']=$usd_to_rmb;
			// 		$data['date']=$date;
			// 		$rate=CF('rate',$data,CONFIG_PATH);
			// 	}
			// }

			$username = trim ( $this->input->post ( 'username' ) );
			$password = trim ( $this->input->post ( 'password' ) );
			if (empty ( $username )) {
				ajaxReturn ( '', '用户名不能为空', 0 );
			}
			if (empty ( $password )) {
				ajaxReturn ( '', '密码不能为空', 0 );
			}
			// 验证用户是否存在，是否合法
			$state = $this->check ( $username, $password );
			if ($state === true) {
				$this->user_model->dologin ( $username, get_client_ip () );
				// 写入权限
				// if (! empty ( $_SESSION ['master_user_info'] )) {
				// $access_user_menu = $this->user_model->get_user_authority ( $_SESSION ['master_user_info']->groupid );
				// $access_user_list = array ();
				// foreach ( $access_user_menu as $item ) {
				// $access_user_list [] = $item->pin;
				// }
				// if (! empty ( $access_user_list )) {
				// $_SESSION ['access_user_list'] = $access_user_list;
				// }
				// }
				// 获取权限
				$power = $this->user_model->get_power ( 'groupid = ' . $_SESSION ['master_user_info']->groupid );
				if (! empty ( $power )) {
					$_SESSION ['power'] = $power;
				}
				ajaxReturn ( '', '登录成功，跳转中...', 1 );
			} else {
				ajaxReturn ( '', $state, 0 );
			}
		}
	}
	
	/**
	 * 退出登录
	 */
	function logout() {
		session_destroy ();
		// ajaxReturn ( '', '', 1 );
		echo '<script>window.parent.location.href="/admin/core/login";</script>';
		die ();
	}
	
	/**
	 * 验证帐号密码可用性 密码错误则少一次登录机会
	 *
	 * @param string $username        	
	 * @param string $password        	
	 */
	private function check($username = null, $password = null) {
		if ($username != null && $password != null) {
			// 根据用户名 查到数据
			$userinfo = $this->user_model->get_user_info ( $username );
			if (! empty ( $userinfo )) {
				// 组合密码
				$true_password = $userinfo->password;
				$input_password = md5 ( $password ) . md5 ( $userinfo->salt );
				// var_dump($userinfo);exit;
				// 验证用户合法性
				if ($userinfo->state == 0) {
					return '用户已被禁用';
				}
				// 验证密码
				if ($true_password != $input_password) {
					return '密码不正确';
					// 密码错误达到上限则禁用
					// if ($userinfo->errcount == 3) {
					// $this->user_model->user_forbidden ( $userinfo->adminid );
					// return '密码错误次数达到上限，已被禁用';
					// }
					// $this->user_model->password_add_errcount ( $userinfo->adminid, $userinfo->errcount );
					// return '密码错误，您还有 ' . (3 - intval ( $userinfo->errcount )) . ' 次机会。';
				}
				// 密码通过验证 则密码错误次数复位
				// $this->user_model->password_reset_errcount ( $userinfo->adminid );
				
				$_SESSION ['master_user_key'] = true;
				$_SESSION ['master_user_info'] = $userinfo;
				return true;
			} else {
				return '用户不存在';
			}
		}
		return false;
	}
}