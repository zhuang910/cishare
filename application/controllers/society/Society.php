<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 前台 学生 控制器
 *
 * @author zyj
 *        
 */
class Society extends Society_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		is_societylogin ();
		
		$this->load->model ( 'society/society_model' );
	}
	
	/**
	 * 主页
	 */
	function index() {
	}
	
	/**
	 * 修改密码
	 */
	function editinfo() {
		$userinfo = $this->society_model->get_info_one ( 'id = ' . $_SESSION ['society'] ['userinfo'] ['id'] );
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		$this->load->view ( 'society/society_editinfo', array (
				'nationality' => $nationality,
				'userinfo' => ! empty ( $userinfo [0] ) ? $userinfo [0] : array () 
		) );
	}
	
	/**
	 * 修改个人信息
	 * 保存
	 */
	function do_editinfo() {
		$data = $this->input->post ();
		if (! empty ( $data )) {
			if (! empty ( $data ['email'] )) {
				unset ( $data ['email'] );
			}
			$flag = $this->society_model->basic_update ( 'id = ' . $_SESSION ['society'] ['userinfo'] ['id'], $data );
			if ($flag) {
				ajaxReturn ( '', lang ( 'update_success' ), 1 );
			} else {
				ajaxReturn ( '', lang ( 'update_error' ), 0 );
			}
		} else {
			ajaxReturn ( '', lang ( 'update_error' ), 0 );
		}
	}
	
	/**
	 * 修改密码
	 */
	function editphoto() {
		$id = $_SESSION ['society'] ['userinfo'] ['id'];
		$pic = $this->society_model->get_pic ( $id );
		$this->load->view ( 'society/society_editphoto', array (
				'pic' => $pic 
		) );
	}
	
	/**
	 * 修改密码
	 */
	function editpassword() {
		$this->load->view ( 'society/society_editpassword' );
	}
	
	/**
	 * 验证原始密码
	 * 是否正确
	 */
	function checkpassword() {
		$password = $this->input->get ( 'oldpassword' );
		$where = "id = {$_SESSION['society']['userinfo']['id']}";
		$userinfo = $this->society_model->get_info_one ( $where );
		if (! empty ( $password )) {
			if (md5 ( $password ) . md5 ( $userinfo [0] ['salt'] ) != $userinfo [0] ['password']) {
				die ( json_encode ( lang ( 'password_error' ) ) );
			} else {
				die ( json_encode ( true ) );
			}
		} else {
			die ( json_encode ( lang ( 'password_error' ) ) );
		}
	}
	
	/**
	 * 执行 修改密码
	 */
	function do_editpassword() {
		$this->load->helper ( 'string' );
		$data = $this->input->post ();
		$where = "id = {$_SESSION['society']['userinfo']['id']}";
		$userinfo = $this->society_model->get_info_one ( $where );
		if (! empty ( $data )) {
			if (md5 ( $data ['oldpassword'] ) . md5 ( $userinfo [0] ['salt'] ) != $userinfo [0] ['password']) {
				ajaxReturn ( '', lang ( 'password_error' ), 0 );
			}
			
			if ($data ['password'] != $data ['repassword']) {
				ajaxReturn ( '', lang ( 'password_equal' ), 0 );
			}
			
			$rand = random_string ( 'alnum', 6 );
			$p = md5 ( $data ['password'] ) . md5 ( $rand );
			
			$flag = $this->society_model->basic_update ( array (
					'id' => $_SESSION ['society'] ['userinfo'] ['id'] 
			), array (
					'password' => $p,
					'salt' => $rand 
			) );
			
			if ($flag) {
				
				ajaxReturn ( '', lang ( 'update_success' ), 1 );
			} else {
				
				ajaxReturn ( '', lang ( 'update_error' ), 0 );
			}
		}
	}
	
	/**
	 * 用户图片上传
	 */
	function stu_pic() {
		$c = $_FILES ['doc'] ['tmp_name'];
		if (empty ( $c )) {
			echo "<script>window.location.href = '" . $this->zjjp . "society/society/editphoto'</script>";
			exit ();
		}
		$a = file_get_contents ( $c );
		
		$id = $_SESSION ['society'] ['userinfo'] ['id'];
		$filepath = date ( 'Ym', time () );
		
		if (! is_dir ( './uploads/society_pic' )) {
			mkdir ( './uploads/society_pic' );
		}
		$Yimgpath = './uploads/society_pic/' . $filepath;
		if (! is_dir ( $Yimgpath )) {
			mkdir ( $Yimgpath );
		}
		$mimgpath = date ( 'd', time () );
		if (! is_dir ( $Yimgpath . '/' . $mimgpath )) {
			mkdir ( $Yimgpath . '/' . $mimgpath );
		}
		
		$img = $Yimgpath . '/' . $mimgpath . '/' . $id . time () . '.jpg';
		$abc = file_put_contents ( $img, $a );
		if ($abc) {
			unlink ( $_FILES ['doc'] ['tmp_name'] );
		}
		
		$this->society_model->insert_pic ( $img, $id );
		
		//
		$img = trim ( $img, '.' );
		$_SESSION ['society'] ['userinfo'] ['photo'] = $img;
		echo "<script>window.location.href = '/" . $this->puri . "/society/society/editphoto'</script>";
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

