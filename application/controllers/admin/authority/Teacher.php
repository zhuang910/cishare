<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 权限管理 管理员管理
 *
 * @author zyj
 *        
 */
class Teacher extends Admin_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'admin/authority/';
		
		$this->load->model ( $this->view . 'teacher_model' );
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
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->teacher_model->count ( $condition );
			$output ['aaData'] = $this->teacher_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$state = $item->state;
				$item->state = $this->_get_lists_state ( $item->state );
				$item->lasttime = ! empty ( $item->lasttime ) ? date ( 'Y-m-d H:i:s', $item->lasttime ) : '';
				$item->sex = $this->_get_lists_sex ( $item->sex );
				$item->operation = '<div class="btn-group"><a class="btn btn-xs btn-info" href="/admin/authority/teacher/add?id=' . $item->id . '">编辑</a><button data-toggle="dropdown" class="btn btn-xs btn-info btn-white dropdown-toggle">
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
		
		$this->_view ( 'teacher_index' );
	}
	
	/**
	 * 添加
	 */
	function add() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		
		if ($id) {
			$result = $this->teacher_model->get_one ( 'id =' . $id );
		}
		$this->_view ( 'teacher_edit', array (
				'info' => ! empty ( $result ) ? $result : array () 
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
				$userid = $this->teacher_model->save ( $id, $data );
				if (! empty ( $userid )) {
					$flag = $this->teacher_model->save_admin ( $userid, $data );
				}
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了教师' . $data ['name'] . '的信息',
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
				$data ['createip'] = get_client_ip ();
				$flag_admin_id = $this->teacher_model->save_admin ( null, $data );
				if (! empty ( $flag_admin_id )) {
					$data ['userid'] = $flag_admin_id;
					$flag = $this->teacher_model->save ( null, $data );
				}
				
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '添加了教师名为' . $data ['name'] . '的教师',
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
	 * 修改管理员的状态
	 */
	function upstate() {
		$id = intval ( $this->input->get_post ( 'id' ) );
		$state = intval ( $this->input->get_post ( 'state' ) );
		if (! empty ( $id )) {
			$result = $this->teacher_model->save_audit ( $id, $state );
			if ($result === true) {
				$teacherlog = $this->teacher_model->get_one ( 'id = ' . $id );
				$statelog = array (
						'禁用',
						'启用' 
				);
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了教师' . $teacherlog->name . '的状态信息为' . $statelog [$state],
						'ip' => get_client_ip (),
						'lasttime' => time () 
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
				$info=$this->db->get_where('teacher','id = '.$id)->row_array();
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
	 * 重置管理员的
	 * 密码
	 */
	function uppassword() {
		$id = intval ( $this->input->get_post ( 'id' ) );
		if ($id) {
			$this->load->helper ( 'string' );
			$rand = random_string ( 'alnum', 6 );
			$data ['password'] = md5 ( '123456' ) . md5 ( $rand );
			$data ['salt'] = $rand;
			$flag = $this->teacher_model->save ( $id, $data );
			if ($flag) {
				$teacherlog = $this->teacher_model->get_one ( 'id = ' . $id );
				
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了教师' . $teacherlog->name . '的密码',
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
				$email_true = $this->teacher_model->get_one ( array (
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
	 * 删除 关联表中数据也会删除
	 */
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$info = ( object ) $this->teacher_model->get_one ( $where );
			$is = $this->teacher_model->delete ( $where );
			if ($is === true) {
				$this->teacher_model->delete_admin ( $info->userid );
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '删除了教师' . $info->name . '的信息',
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
				'name',
				'email',
				'sex',
				'tel',
				'phone',
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
	private function _get_lists_sex($statecode = null) {
		if ($statecode != null) {
			$statemsg = array (
					'0' => '未填写',
					'1' => '男',
					'2' => '女' 
			);
			return $statemsg [$statecode];
		}
		return;
	}
	/**
	 * 导入页面
	 */
	function tochanel() {
		$s = intval ( $this->input->get ( 's' ) );
		if (! empty ( $s )) {
			$html = $this->_view ( 'tochanel', array (), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 * 导出模板
	 */
	function tochaneltenplate() {
		$data = $this->teacher_model->get_teacher_fields ();
		$this->load->library ( 'sdyinc_export' );
		$d = $this->sdyinc_export->teacher_tochaneltenplate ( $data );
		if (! empty ( $d )) {
			$this->load->helper ( 'download' );
			force_download ( 'teacher' . time () . '.xlsx', $d );
			return 1;
		}
	}
	/**
	 * 导出
	 */
	function export() {
		$this->load->library ( 'sdyinc_export' );
		$d = $this->sdyinc_export->do_export_teacher ();
		if (! empty ( $d )) {
			$this->load->helper ( 'download' );
			force_download ( 'teacher' . time () . '.xlsx', $d );
			return 1;
		}
	}
	/**
	 * 上传major
	 */
	function upload_excel() {
		$this->load->helper ( 'string' );
		// 判断文件类型，如果不是"xls"或者"xlsx"，则退出
		if ($_FILES ["file"] ["type"] == "application/vnd.ms-excel") {
			$inputFileType = 'Excel5';
		} elseif ($_FILES ["file"] ["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
			$inputFileType = 'Excel2007';
		} else {
			echo "Type: " . $_FILES ["file"] ["type"] . "<br />";
			echo "您选择的文件格式不正确";
			exit ();
		}
		
		if ($_FILES ["file"] ["error"] > 0) {
			echo "Error: " . $_FILES ["file"] ["error"] . "<br />";
			exit ();
		}
		$str = $_SERVER ['DOCUMENT_ROOT'] . '/uploads/work/' . date ( 'Ym' ) . '/' . date ( 'd' );
		if (! is_dir ( $str )) {
			mk_dir ( $str );
		}
		$inputFileName = $str . '/' . $_FILES ["file"] ["name"];
		if (file_exists ( $inputFileName )) {
			// echo $_FILES["file"]["name"] . " already exists. <br />";
			unlink ( $inputFileName ); // 如果服务器上存在同名文件，则删除
		} else {
		}
		move_uploaded_file ( $_FILES ["file"] ["tmp_name"], $inputFileName );
		echo "Stored in: " . $inputFileName . '<br />';
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$this->load->library ( 'PHPExcel/Writer/Excel2007' );
		$objReader = IOFactory::createReader ( $inputFileType );
		$WorksheetInfo = $objReader->listWorksheetInfo ( $inputFileName );
		
		// 读取文件最大行数、列数，偶尔会用到。
		$maxRows = $WorksheetInfo [0] ['totalRows'];
		$maxColumn = $WorksheetInfo [0] ['totalColumns'];
		
		// 设置只读，可取消类似"3.08E-05"之类自动转换的数据格式，避免写库失败
		$objReader->setReadDataOnly ( true );
		
		$objPHPExcel = $objReader->load ( $inputFileName );
		$sheetData = $objPHPExcel->getSheet ( 0 )->toArray ( null, true, true, true );
		
		// $zjj = $objPHPExcel->getActiveSheet();
		// $zjj->getStyle('D1:D'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
		// $zjj->getStyle('G1:G'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
		// $zjj->getStyle('M1:M'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
		// $zjj->getStyle('O1:O'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
		// $zjj->getStyle('R1:R'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
		// $zjj->getStyle('U1:U'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
		// $zjj->getStyle('Z1:Z'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
		// $zjj->getStyle('AD1:AD'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
		// $zjj->getStyle('AE1:AE'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
		
		// excel2003文件，可使用'$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);'
		// excel2007文件使用"getActiveSheet()"方法时会提示出错：对non-object使用了"toArray"方法。
		$keywords = $sheetData [1];
		$num = count ( $sheetData [1] );
		$warning = '上传文件字段格式不正确，请修改后重新上传！<br />';
		$columns = array (
				'A',
				'B',
				'C',
				'D',
				'E',
				'F',
				'G',
				'H',
				'I',
				'J',
				'K' 
		);
		$mfields = $this->teacher_model->get_teacher_fields ();
		if ($num != count ( $mfields )) {
			echo '字段个数不匹配';
			exit ();
		}
		$keysInFile = array ();
		foreach ( $mfields as $key => $value ) {
			$keysInFile [] = $value;
		}
		foreach ( $columns as $keyIndex => $columnIndex ) {
			if ($keywords [$columnIndex] != $keysInFile [$keyIndex]) {
				echo $warning . $columnIndex . '列应为' . $keysInFile [$keyIndex] . '，而非' . $keywords [$columnIndex];
				unlink ( $inputFileName );
				exit ();
			}
		}
		// 插入字段组合
		$insert = '';
		foreach ( $mfields as $k => $v ) {
			$insert .= $k . ',';
		}
		$insert .= 'createtime,salt,userid';
		$insert = trim ( $insert, ',' );
		
		unset ( $sheetData [1] );
		$i = 65;
		$str = '';
		$ss = 2;
		foreach ( $sheetData as $k => $v ) {
			$value = '';
			$email = $v ['G'];
			$username = $v ['C'];
			$sss = 0;
			// 检查邮箱是否输入
			if (empty ( $email )) {
				$str .= $ss . '行没有输入邮箱,该行没有插入<br />';
				continue;
			}
			// 检查邮箱格式是否正确
			$checkemail = $this->checkemail_geshi ( $email );
			if ($checkemail == 0) {
				$str .= $ss . '行填入的邮箱格式有误,该行没有插入<br />';
				continue;
			}
			// 检查邮箱是否已经存在该数据库
			$e_num = $this->teacher_model->checke_email ( $email );
			if ($e_num > 0) {
				$str .= $ss . '行填入的邮箱已经存在老师表,该行没有插入<br />';
				continue;
			}
			// 检查邮箱是否已经存在该数据库
			$ea_num = $this->teacher_model->checke_admin_email ( $email );
			if ($ea_num > 0) {
				$str .= $ss . '行填入的邮箱已经存在管理员表,该行没有插入<br />';
				continue;
			}
			// 检查用户名是否已经存在该数据库
			$username_num = $this->teacher_model->checke_username ( $username );
			if ($username_num > 0) {
				$str .= $ss . '行填入的邮箱已经存在,该行没有插入<br />';
				continue;
			}
			$pass_a = trim ( $v ['D'] );
			$rand_a = random_string ( 'alnum', 6 );
			$password = md5 ( $pass_a ) . md5 ( $rand_a );
			$admin = array (
					'username' => $username,
					'nikename' => $v ['A'],
					'password' => $password,
					'salt' => $rand_a,
					'groupid' => 4,
					'email' => $email,
					'tel' => $v ['F'],
					'mobile' => $v ['H'],
					'createip' => get_client_ip (),
					'createtime' => time () 
			);
			// 插入管理员表
			$userid = $this->teacher_model->insert_admin ( $admin );
			// 组合插入字段
			foreach ( $v as $kk => $vv ) {
				if ($kk == 'L') {
					$vv = $vv == '是' ? 1 : 0;
					$value .= '"' . $vv . '",';
				} elseif ($kk == 'D') {
					$pass = trim ( $vv );
					$rand = random_string ( 'alnum', 6 );
					$pass = md5 ( $pass ) . md5 ( $rand );
					$value .= '"' . $pass . '",';
				} elseif ($kk == 'E') {
					$sex = trim ( $vv );
					if ($sex == '男') {
						$vv = 1;
					} elseif ($sex == '女') {
						$vv = 2;
					} else {
						$vv = 0;
					}
					$value .= '"' . $vv . '",';
				} else {
					$value .= '"' . $vv . '",';
				}
			}
			$value .= '"' . time () . '",';
			$value .= '"' . $rand . '",';
			$value .= '"' . $userid . '",';
			$value = trim ( $value, ',' );
			if ($sss == 1) {
				continue;
			}
			
			// $insert=explode(',', $insert);
			// $value=explode(',', $insert);
			// var_dump($insert);
			// var_dump($value);exit;
			$this->teacher_model->insert_fields ( $insert, $value );
			$ss ++;
			$i ++;
		}
		if ($str != '') {
			echo $str;
		}
	}
	/**
	 * 邮箱验证
	 */
	function checkemail_geshi($email) {
		if (! empty ( $email )) {
			if (! preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email )) {
				return 0;
			}
		}
		return 1;
	}
}

