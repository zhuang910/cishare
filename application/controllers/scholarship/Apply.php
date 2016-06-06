<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 申请表
 *
 * @author zyj
 *        
 */
class Apply extends Student_Basic {
	protected $html_main = null;
	protected $html_left = null;
	protected $html_form = null;
	protected $form_state = array ();
	protected $user_form_data = array ();
	protected $issubmit = 0;
	protected $isstart = 0;
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		is_studentlogin ();
		$this->load->model ( 'scholarship/user_model' );
		$this->load->model ( 'scholarship/scholarship_model' );
		$this->load->model ( 'scholarship/apply_model' );
		$this->load->model ( 'scholarship/fillingoutforms_model' );
		$this->load->model ( 'scholarship/validate_model' );
		$aid = $this->input->get ( 'id' );
		if ($aid) {
			$aid = cucas_base64_decode ( $aid );
			$this->permissions ( $aid );
		}
	}
	
	/**
	 * 权限验证
	 */
	private function permissions($aid = null) {
		if ($aid != null) {
			$where_apply_info_permissions = "type = 2 AND scholarshipid = {$aid} AND userid = {$_SESSION['student'] ['userinfo'] ['id']}";
			$apply_info_permissions = $this->apply_model->get_apply_info ( $where_apply_info_permissions );
			if (! in_array ( $apply_info_permissions ['state'], array (
					0,
					2 
			) )) {
				$html = $this->load->view ( 'scholarship/apply_permissions', array (), true );
				echo $html;
				die ();
			}
		}
	}
	
	/**
	 * 主页
	 */
	function index() {
		$id = trim ( $this->input->get ( 'id' ) );
		$applyid=trim($this->input->get('applyid'));
		// 验证是否有权限
		
		if (! empty ( $id )) {
			$id = cucas_base64_decode ( $id );
			$applyid=cucas_base64_decode($applyid);
			// 判断 信息用的
			$flag = $this->is_new_apply ( $id ,$applyid);
			if ($flag ['flag'] != 2) {
				echo '<script>window.location.href="' . $flag ['jumpurl'] . ' "</script>';
				die ();
			}
			// 查询数据
			$where_apply_info = "scholarshipid = {$id} AND userid = {$_SESSION['student'] ['userinfo'] ['id']}";
			$apply_info = $this->apply_model->get_apply_info ( $where_apply_info );
			$this->issubmit = ! empty ( $apply_info->issubmit ) ? $apply_info->issubmit : 0;
			$this->isstart = ! empty ( $apply_info->isstart ) ? $apply_info->isstart : 0;
			
			$coursename = $this->scholarship_model->get_one ( 'id = ' . $id );
		}
		
		$this->load->view ( 'scholarship/apply_index', array (
				'id' => ! empty ( $id ) ? $id : '',
				'courseid' => ! empty ( $id ) ? $id : '',
				'apply_info' => ! empty ( $apply_info ) ? $apply_info : array (),
				'issubmit' => $this->issubmit,
				'coursenames' => $coursename,
				'isstart' => $this->isstart 
		) );
	}
	
	/**
	 * 提交资料
	 */
	function upload_materials() {
		$applyid = trim ( $this->input->get ( 'applyid' ) );
		if ($applyid) {
			$applyid = intval ( cucas_base64_decode ( $applyid ) );
			$where_apply_info = "id = {$applyid} AND userid = {$_SESSION['student'] ['userinfo'] ['id']}";
			
			// 首先要更改 isstart = 1
			
			$dataS = array (
					'isstart' => 1,
					'lasttime' => time () 
			);
			
			$this->apply_model->save_apply_info ( $where_apply_info, $dataS );
			// 查询数据
			$apply_info = $this->apply_model->get_apply_info ( $where_apply_info );
			$this->isapply ( $apply_info ['scholarshipid'] );
			$where_c = "id = {$apply_info['scholarshipid']} AND state = 1";
			$course = $this->scholarship_model->get_one ( $where_c );
			$coursenames = $this->scholarship_model->get_one ( 'id = ' . $apply_info ['scholarshipid'] );
			// 首先看一下该课程有没有添加附件信息 如果是 空的 就是所有的附件信息
			if (! empty ( $course ['attatemplate'] )) {
				$where_attachment = 'atta_id = ' . $course ['attatemplate'];
			} else {
				$where_attachment = 'atta_id > 1 AND aKind = "Y" AND admin_id = 0';
			}
			$attachment = $this->apply_model->get_apply_attachment ( $where_attachment );
			
			// 填写的附件的信息
			$where_apply_attachment_info = "applyid = {$applyid} AND userid = {$_SESSION['student'] ['userinfo'] ['id']}";
			$attachment_info = $this->apply_model->get_apply_attachment_info ( $where_apply_attachment_info );
			$attachment_content = $this->apply_model->get_attachmentstopic ( 'atta_id = ' . $attachment ['atta_id'] );
			
			$this->issubmit = ! empty ( $apply_info->issubmit ) ? $apply_info->issubmit : 0;
			$this->isstart = ! empty ( $apply_info->isstart ) ? $apply_info->isstart : 0;
		}
		$this->load->view ( 'scholarship/apply_upload_materials', array (
				'courseid' => ! empty ( $apply_info ['scholarshipid'] ) ? $apply_info ['scholarshipid'] : '',
				'attachment' => ! empty ( $attachment ) ? $attachment : array (),
				'attachment_info' => ! empty ( $attachment_info ) ? $attachment_info : array (),
				'apply_info' => ! empty ( $apply_info ) ? $apply_info : array (),
				'issubmit' => $this->issubmit,
				'isstart' => $this->isstart,
				'attachment_content' => ! empty ( $attachment_content ) ? $attachment_content : '',
				'coursenames' => $coursenames 
		) );
	}
	
	/**
	 * 保存提交资料
	 */
	function save_upload_atta() {
		$courseid = intval ( trim ( $this->input->post ( 'courseid' ) ) );
		$attachmentid = intval ( trim ( ($this->input->post ( 'attachmentid' )) ) );
		$applyid = trim ( ($this->input->post ( 'applyid' )) );
		$applyid = cucas_base64_decode ( $applyid );
		$data = $this->input->post ( 'datas' );
		$url = $data ['url'];
		// $value = json_encode ( $data );
		$userid = $_SESSION ['student'] ['userinfo'] ['id'];
		$truename = $data ['truename'];
		$thumbnailUrl = $data ['thumbnailUrl'];
		$addData = array (
				'courseid' => $courseid,
				'attachmentid' => $attachmentid,
				'userid' => $userid,
				// 'value' => $value,
				'url' => $url,
				'truename' => $truename,
				'thumbnailUrl' => $thumbnailUrl,
				'time' => time (),
				'applyid' => $applyid 
		);
		$result = $this->apply_model->save_upload_attachment ( $addData );
		$this->apply_history ( '上传附件', $applyid );
		echo $result;
		exit ();
	}
	
	/**
	 * 上传附件
	 */
	public function upload_files() {
		$config ['upload_path'] = UPLOADS . 'my/scholarship/' . date ( 'Y' ) . '/' . date ( 'md' ) . '/';
		
		$config ['allowed_types'] = 'gif|jpg|jpeg|png|pdf|doc|docx|rar|zip';
		$config ['max_size'] = '8192';
		$config ['file_name'] = build_order_no ();
		$save_path = '/uploads/my/scholarship/' . date ( 'Y' ) . '/' . date ( 'md' ) . '/';
		
		$this->load->library ( 'upload', $config );
		mk_dir ( $config ['upload_path'] );
		if (! $this->upload->do_upload ( 'files' )) {
			$info = $this->upload->display_errors ( '', '' );
			ajaxReturn ( '', $info, 0 );
		} else {
			$r = $this->upload->data ();
			$data = array (
					'name' => $r ['file_name'],
					'size' => $r ['file_size'],
					'type' => $r ['file_type'],
					'ext' => $r ['file_ext'],
					'url' => $save_path . $r ['orig_name'],
					'truename' => $r ['client_name'] 
			);
			ajaxReturn ( $data, '', 1 );
		}
	}
	
	/**
	 * 验证图片 必填
	 */
	function validatorsaveMateerial() {
		$applyid = trim ( $this->input->get ( 'applyid' ) );
		$data = $this->input->get ();
		
		if ($applyid) {
			$applyid = intval ( cucas_base64_decode ( $applyid ) );
			$where_apply_info = "id = {$applyid} AND userid = {$_SESSION['student'] ['userinfo'] ['id']}";
			
			$apply_info = $this->apply_model->get_apply_info ( $where_apply_info );
			
			$where_c = "id = {$apply_info['scholarshipid']} AND state = 1";
			$course = $this->scholarship_model->get_one ( $where_c );
			// 首先看一下该课程有没有添加附件信息 如果是 空的 就是所有的附件信息
			if (! empty ( $course ['attatemplate'] )) {
				$where_attachment = 'atta_id = ' . $course ['attatemplate'];
			} else {
				$where_attachment = 'atta_id > 1 AND aKind = "Y" AND admin_id = 0';
			}
			$attachment = $this->apply_model->get_apply_attachment ( $where_attachment );
			
			$attachment_content = $this->apply_model->get_attachmentstopic ( 'atta_id = ' . $attachment ['atta_id'] );
			foreach ( $attachment_content as $k => $v ) {
				if ($v ['isrequired'] == 1) {
					$att [] = $v ['aTopic_id'];
					$att_n [$v ['aTopic_id']] = $v ['TopicName'];
				}
			}
			
			// 找到 所有 必填的id
			if (! empty ( $att )) {
				if (empty ( $data )) {
					ajaxReturn ( '', '', 0 );
				}
				asort ( $att );
				$att_form1 = $att_form2 = array ();
				foreach ( $data as $key => $val ) {
					if (! empty ( $val ) && $val == 'flag') {
						if (is_numeric ( $key )) {
							$att_form1 [] = $key;
						} else {
							if (strstr ( $key, '-' )) {
								$arr = explode ( '-', $key );
								$att_form2 [] = $arr [1];
							}
						}
					}
				}
				
				$att_t = array_merge ( $att_form1, $att_form2 );
				$att_t = array_unique ( $att_t );
				
				asort ( $att_t );
				$t = array_diff ( $att, $att_t );
				$str = '';
				if (! empty ( $t )) {
					
					foreach ( $t as $item ) {
						$str .= $att_n [$item] . ' <font color=red>Required</font><br >';
					}
					ajaxReturn ( '', $str, 0 );
				} else {
					$where_apply_info = "id = {$applyid} AND userid = {$_SESSION['student'] ['userinfo'] ['id']}";
					
					// 首先要更改 isstart = 1
					
					$dataS = array (
							'lasttime' => time (),
							'isatt' => 1 
					);
					$this->apply_model->save_apply_info ( $where_apply_info, $dataS );
					ajaxReturn ( '', '', 1 );
				}
			} else {
				$where_apply_info = "id = {$applyid} AND userid = {$_SESSION['student'] ['userinfo'] ['id']}";
				
				// 首先要更改 isstart = 1
				
				$dataS = array (
						'lasttime' => time (),
						'isatt' => 1 
				);
				$this->apply_model->save_apply_info ( $where_apply_info, $dataS );
				ajaxReturn ( '', '', 1 );
			}
		}
	}
	
	/**
	 * 删除图片
	 */
	public function delFiles() {
		$id = $this->input->post ( 'id' );
		$where = "id = {$id}";
		// 先要去查询一下 这条数据 属不属这个人
		$result = $this->apply_model->get_apply_attachment_info ( $where );
		if (empty ( $result ) || $result [0] ['userid'] != $_SESSION ['student'] ['userinfo'] ['id']) {
			echo 0;
			exit ();
		}
		$flag = $this->apply_model->del_upload_attachment ( $where );
		$this->apply_history ( '删除附件', $result [0] ['applyid'] );
		if ($flag) {
			echo 1;
		} else {
			echo 0;
		}
		exit ();
	}
	

	
	/**
	 * 提交
	 */
	function submit_application() {
		$applyid = trim ( $this->input->get ( 'applyid' ) );
		if ($applyid) {
			$applyid = intval ( cucas_base64_decode ( $applyid ) );
			$where_apply_info = "id = {$applyid} AND userid = {$_SESSION['student'] ['userinfo'] ['id']}";
			
			// 首先要更改 isstart = 1
			
			$dataS = array (
					'lasttime' => time () 
			);
			
			$this->apply_model->save_apply_info ( $where_apply_info, $dataS );
			// 查询数据
			$apply_info = $this->apply_model->get_apply_info ( $where_apply_info );
			$this->isapply ( $apply_info ['scholarshipid'] );
			$where_c = "id = {$apply_info['scholarshipid']} AND state = 1";
			$course = $this->scholarship_model->get_one ( $where_c );
			$this->issubmit = ! empty ( $apply_info ['issubmit'] ) ? $apply_info ['issubmit'] : 0;
			$this->isstart = ! empty ( $apply_info ['isstart'] ) ? $apply_info ['isstart'] : 0;
		}
		$this->load->view ( 'scholarship/apply_submit_application', array (
				'courseid' => ! empty ( $apply_info ['scholarshipid'] ) ? $apply_info ['scholarshipid'] : '',
				'apply_info' => ! empty ( $apply_info ) ? $apply_info : array (),
				'course' => ! empty ( $course ) ? $course : array (),
				'issubmit' => $this->issubmit,
				'isstart' => $this->isstart,
				'coursenames' => $course 
		) );
	}
	
	/**
	 * 最后一步 提交
	 */
	function save_submit() {
		$applyid = trim ( $this->input->get ( 'applyid' ) );
		if (! empty ( $applyid )) {
			$applyid = intval ( cucas_base64_decode ( $applyid ) );
			$where_apply_info = "id = {$applyid} AND userid = {$_SESSION['student'] ['userinfo'] ['id']}";
			$apply_info = $this->apply_model->get_apply_info ( $where_apply_info );
			// 如果资料没有完成 或是 支付状态没有完成 不能提交
			// if ($apply_info->isinformation != 1 || $apply_info->paystate != 1) {
			// $lang = lang ( 'user_submit_error' );
			// ajaxReturn ( '', $lang, 0 );
			// }
			
			if ($apply_info ['state'] == 0) {
				$state = 1;
			}
			
			if ($apply_info ['state'] == 2) {
				$state = 3;
			}
			// 修改申请表
			$data = array (
					'issubmit' => 1,
					'issubmittime' => time (),
					'state' => $state 
			);
			$flag = $this->apply_model->save_apply_info ( $where_apply_info, $data );
			if ($flag) {
				$this->apply_history ( '提交信息', $applyid );
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', 'Error', 0 );
			}
		} else {
			ajaxReturn ( '', 'Error', 0 );
		}
	}
	
	/**
	 * 申请历史
	 */
	function apply_history($action = null, $applyid = null) {
		$data = array (
				'userid' => $_SESSION ['student'] ['userinfo'] ['id'],
				'appid' => $applyid,
				'action' => $action,
				'actiontime' => time (),
				'actionip' => get_client_ip () 
		);
		$this->apply_model->save_apply_history ( $data );
	}
	
	/**
	 * 判断信息
	 */
	private function is_new_apply($id = null,$applyid) {
		if (! empty ( $id )) {
			$flag = false; // 勇于判断 是否 是 插入数据
			               // 获取 信息
			$where_c = "id = {$id} AND state = 1 AND apply_state = 2 AND ischinascholorship = 0";
			$course = $this->scholarship_model->get_one ( $where_c );
			// 获取 申请信息
			$where_apply_info = "scholarshipid = {$id} AND userid = {$_SESSION['student'] ['userinfo'] ['id']} AND type = 2";
			$apply_info = $this->apply_model->get_apply_info ( $where_apply_info );
			// 根据 课程id 和 userid 确定一条记录 如果没有 就 生成一条新的记录
			// 如果 有了 就要 判断了 首先 同一 学期 不能申请两次 不同学期的 可以申请
			// 产生一条 新纪录 如果是 已经申请了 一年内 就要 判断 现在 他所在的 步骤 跳到 相应的步骤中去
			// 看看 提交了 吗 如果是 提交了 就不能 跳到 这里了 直接到 个人中心去
			// 已有数据
			if (! empty ( $apply_info )) {
				// 看是否提交了
				$issubmit = $apply_info ['issubmit'];
				if ($issubmit == 0) {
					// 未提交 跳到 相应的 步骤去 跳到开始去
					if ($apply_info ['isstart'] == 0) {
						$jumpurl = '/' . $this->puri . '/scholarship/apply?id=' . cucas_base64_encode ( $id );
						return $data = array (
								'jumpurl' => $jumpurl,
								'flag' => 2 
						);
					}
					
					// 未提交 跳到 相应的 步骤去 填表
					if ($apply_info ['isinformation'] == 0) {
						$jumpurl = '/' . $this->puri . '/scholarship/fillingoutforms/apply?applyid=' . cucas_base64_encode ( $apply_info ['id'] );
						return $data = array (
								'jumpurl' => $jumpurl,
								'flag' => 1 
						);
					}
					
					// 未提交 跳到 相应的 步骤去 填表
					if ($apply_info ['isatt'] == 0) {
						$jumpurl = '/' . $this->puri . '/scholarship/apply/upload_materials?applyid=' . cucas_base64_encode ( $apply_info ['id'] );
						return $data = array (
								'jumpurl' => $jumpurl,
								'flag' => 1 
						);
					}
					
					$jumpurl = '/' . $this->puri . '/scholarship/apply/submit_application?applyid=' . cucas_base64_encode ( $apply_info ['id'] );
					return $data = array (
							'jumpurl' => $jumpurl,
							'flag' => 1 
					);
				} else {
					// 打回
					if ($apply_info ['state'] == 3) {
						$jumpurl = '/' . $this->puri . '/scholarship/fillingoutforms/apply?applyid=' . cucas_base64_encode ( $apply_info ['id'] );
						return $data = array (
								'jumpurl' => $jumpurl,
								'flag' => 1 
						);
					} else {
						// 跳到个人中心
						$jumpurl = '/' . $this->puri . '/scholarship';
						return $data = array (
								'jumpurl' => $jumpurl,
								'flag' => 1 
						);
					}
				}
			} else {
				$flag = true;
			}
			
			if ($flag == true) {
				//获取该学生的学期
				// 新数据
				$max_cucasid = build_order_no ();
				
				// 向申请表中添加数据
				$data = array (
						'number' => $max_cucasid,
						'userid' => $_SESSION ['student'] ['userinfo'] ['id'],
						'scholarshipid' => $id,
						'type' => 2,
						'name' => ! empty ( $_SESSION ['student'] ['userinfo'] ['enname'] ) ? $_SESSION ['student'] ['userinfo'] ['enname'] : '',
						'passport' => ! empty ( $_SESSION ['student'] ['userinfo'] ['passport'] ) ? $_SESSION ['student'] ['userinfo'] ['passport'] : '',
						'email' => ! empty ( $_SESSION ['student'] ['userinfo'] ['email'] ) ? $_SESSION ['student'] ['userinfo'] ['email'] : '',
						'nationality' => ! empty ( $_SESSION ['student'] ['userinfo'] ['nationality'] ) ? $_SESSION ['student'] ['userinfo'] ['nationality'] : '',
						'applytime' => time (),
						'isstart' => 1,
						'isinformation' => 0,
						'isatt' => 0,
						'issubmit' => 0,
						'state' => 0,
						'lasttime' => time (),
						'term'=>1,
                        'applyid'=>$applyid
				);
				
				$a_id = $this->apply_model->save_apply_info ( null, $data );
				
				$jumpurl = '/' . $this->puri . '/scholarship/apply?id=' . cucas_base64_encode ( $id );
				return $data = array (
						'jumpurl' => $jumpurl,
						'flag' => 2 
				);
			}
		}
		return $data = array (
				'jumpurl' => '/scholarship',
				'flag' => 0 
		);
	}
	
	/**
	 * 验证课程是否可申请
	 */
	private function isapply($courseid) {
		$this->load->model ( 'scholarship/fillingoutforms_model', 'model' );
		$this->load->model ( 'scholarship/validate_model' );
		$is = $this->validate_model->isapply ( $courseid );
		if ($is === false) {
			// $this->user_msg ( 'Application Deadline Passed', 'http://'.$_SERVER['HTTP_HOST'].'/student/index', 1, 3 );
			$html = $this->load->view ( 'scholarship/apply_permissions', array (
					'info' => 'Application Deadline Passed' 
			), true );
			echo $html;
			die ();
		}
		
		$is = $this->model->get_apply_info ( $courseid, $_SESSION ['student'] ['userinfo'] ['id'] );
		
		// 验证是否可填写
		$lock_arr = array (
				0,
				2,
				11 
		);
		if (! in_array ( $is->state, $lock_arr )) {
			// $this->user_msg ( 'CUCAS has to verify your information, documents modification unavailable now. Please try again later.', 'http://'.$_SERVER['HTTP_HOST'].'/student/index', 1, 3 );
			$html = $this->load->view ( 'scholarship/apply_permissions', array (
					'info' => 'CUCAS has to verify your information, documents modification unavailable now. Please try again later.' 
			), true );
			echo $html;
			die ();
		}
		return true;
	}

/**
 * 删除所有的 申请 和 订单
 *
 * function del() {
 * $where = "userid = {$_SESSION['student'] ['userinfo'] ['id']}";
 * $this->db->delete ( 'apply_info', $where );
 * $this->db->delete ( 'apply_order_info', $where );
 * }
 * function aaaaa() {
 * if (strstr ( 'BFSUE70978813725', 'BFSU' )) {
 * echo 112;
 * }
 * }
 */
}