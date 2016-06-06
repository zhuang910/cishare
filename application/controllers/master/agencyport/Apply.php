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
	protected $isapplypay = 1; // 需支付
	protected $applyfee = 0; // 设置的费用
	protected $applydanwei = ''; // 设置的单位
    protected $agencyid=0;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'home/user_model' );
		$this->load->model ( 'home/apply_model' );
		$this->load->model ( 'home/course_model' );
		$this->load->model ( 'home/fillingoutforms_model' );
		$this->load->model ( 'home/validate_model' );
		$aid = $this->input->get ( 'applyid' );
        $this->agencyid=$this->get_agencyid($_SESSION['master_user_info']->id);
		if ($aid) {
			$aid = cucas_base64_decode ( $aid );
			// $this->permissions ( $aid );
		}
		
		// 查找 支付 申请的 开关
		$apply_on = CF ( 'apply', '', CONFIG_PATH );
		if (! empty ( $apply_on )) {
			if ($apply_on ['apply'] == 'no') {
				// 无需支付
				$this->isapplypay = 0;
			} else {
				$this->applyfee = $apply_on ['applymoney'];
				if ($apply_on ['applyway'] == 'applyrmb') {
					$this->applydanwei = 2;
				} else if ($apply_on ['applyway'] == 'applyusd') {
					$this->applydanwei = 1;
				}
			}
		}
	}
	
	/**
	 * 权限验证
	 */
	private function permissions($aid = null) {
		if ($aid != null) {
			$where_apply_info_permissions = "id = {$aid} AND userid = {$_SESSION['student'] ['userinfo'] ['id']}";
			$apply_info_permissions = $this->apply_model->get_apply_info ( $where_apply_info_permissions );
			if (! in_array ( $apply_info_permissions ['state'], array (
					0,
					2 
			) )) {
				$html = $this->load->view ( 'student/apply_permissions', array (), true );
				echo $html;
				die ();
			}
		}
	}
	
	/**
	 * 主页
	 */
	function index() {
		
		// 通过课程id 和 userid 锁定一条 记录 如果 已经支付 就插入一条 如果 没有就插入一条记录
		// 新的记录 否则还是 继续这条条记录 以后用申请id 去唯一标识所有哦的步骤
		$courseid = trim ( $this->input->get ( 'courseid' ) );
		// 验证是否有权限
		$userid=intval(trim($this->input->get('userid')));
		if (! empty ( $courseid )) {
			$courseid = cucas_base64_decode ( $courseid );
			
			// 判断 信息用的
			$flag = $this->is_new_apply ( $courseid ,$userid);
			if ($flag ['flag'] != 2) {
				echo '<script>window.location.href="' . $flag ['jumpurl'] . ' "</script>';
				die ();
			}
			// 查询数据
			$where_apply_info = "courseid = {$courseid} AND userid = {$userid}";
			$apply_info = $this->apply_model->get_apply_info ( $where_apply_info );
			$this->issubmit = ! empty ( $apply_info->issubmit ) ? $apply_info->issubmit : 0;
			$this->isstart = ! empty ( $apply_info->isstart ) ? $apply_info->isstart : 0;
			$this->apply_history ( '开始申请', $apply_info ['id'] ,$userid);
			$coursename = $this->course_model->get_one ( 'id = ' . $courseid );
			$flag = 0;
			$flag_count = '--';
			// 奖学金开关
			$scholarship_on = CF ( 'scholarship', '', CONFIG_PATH );
			if (! empty ( $scholarship_on ) && $scholarship_on ['scholarship'] == 'yes') {
				// 开启了 奖学金 设置
				if (! empty ( $coursename ['scholarship'] )) {
					$scholarship_major = $this->db->select ( '*' )->get_where ( 'scholarship_info', 'state = 1 AND id = ' . $coursename ['scholarship'] )->result_array ();
					
					// 已经申请的数量 且通过的
					$count_apply = 0;
					// 数量
					$count = $this->db->select ( 'id' )->get_where ( 'apply_info', 'scholorshipid = ' . $scholarship_major [0] ['id'] . ' AND state = 8' )->result_array ();
					if (! empty ( $count )) {
						$count_apply = count ( $count );
					}
					
					if (empty ( $scholarship_major [0] ['count'] )) {
						$flag = 1;
					} else {
						if (($scholarship_major [0] ['count'] - $count_apply) > 0) {
							$flag = 1;
							$flag_count = $scholarship_major [0] ['count'] - $count_apply;
						} else {
							$flag_count = 0;
						}
					}
				}
			}
		}
		
		$this->load->view ( '/master/agencyport/apply_index', array (
				'courseid' => ! empty ( $courseid ) ? $courseid : '',
				'apply_info' => ! empty ( $apply_info ) ? $apply_info : array (),
				'issubmit' => $this->issubmit,
				'coursenames' => $coursename,
				'isstart' => $this->isstart,
				'scholarship_major' => ! empty ( $scholarship_major ) ? $scholarship_major [0] : array (),
				'flag' => $flag,
				'flag_count' => $flag_count ,
				'userid'=>$userid
		) );
	}
	
	/**
	 * 提交资料
	 */
	function upload_materials() {
		$applyid = trim ( $this->input->get ( 'applyid' ) );
		$userid=intval(trim($this->input->get('userid')));
		if ($applyid) {
			$applyid = intval ( cucas_base64_decode ( $applyid ) );
			$where_apply_info = "id = {$applyid} AND userid = {$userid}";
			
			// 首先要更改 isstart = 1
			
			$dataS = array (
					'isstart' => 1,
					'lasttime' => time () 
			);
			
			$this->apply_model->save_apply_info ( $where_apply_info, $dataS );
			// 查询数据
			$apply_info = $this->apply_model->get_apply_info ( $where_apply_info );
			$this->isapply ( $apply_info ['courseid'] ,$userid);
			$where_c = "id = {$apply_info['courseid']} AND state = 1";
			$course = $this->course_model->get_one ( $where_c );
			$coursenames = $this->course_model->get_one ( 'id = ' . $apply_info ['courseid'] );
			// 首先看一下该课程有没有添加附件信息 如果是 空的 就是所有的附件信息
			if (! empty ( $course ['attatemplate'] )) {
				$where_attachment = 'atta_id = ' . $course ['attatemplate'];
			} else {
				$where_attachment = 'atta_id > 1 AND aKind = "Y" AND admin_id = 0';
			}
			$attachment = $this->apply_model->get_apply_attachment ( $where_attachment );
			
			// 填写的附件的信息
			$where_apply_attachment_info = "applyid = {$applyid} AND userid = {$userid}";
			$attachment_info = $this->apply_model->get_apply_attachment_info ( $where_apply_attachment_info );
			$attachment_content = $this->apply_model->get_attachmentstopic ( 'atta_id = ' . $attachment ['atta_id'] );
			
			$this->issubmit = ! empty ( $apply_info->issubmit ) ? $apply_info->issubmit : 0;
			$this->isstart = ! empty ( $apply_info->isstart ) ? $apply_info->isstart : 0;
		}
		$this->load->view ( '/master/agencyport/apply_upload_materials', array (
				'courseid' => ! empty ( $apply_info ['courseid'] ) ? $apply_info ['courseid'] : '',
				'attachment' => ! empty ( $attachment ) ? $attachment : array (),
				'attachment_info' => ! empty ( $attachment_info ) ? $attachment_info : array (),
				'apply_info' => ! empty ( $apply_info ) ? $apply_info : array (),
				'issubmit' => $this->issubmit,
				'isstart' => $this->isstart,
				'attachment_content' => ! empty ( $attachment_content ) ? $attachment_content : '',
				'coursenames' => $coursenames ,
				'userid'=>$userid
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
		$userid=$this->input->post('userid');
		// $value = json_encode ( $data );
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
		$config ['upload_path'] = UPLOADS . 'my/apply/' . date ( 'Y' ) . '/' . date ( 'md' ) . '/';
		
		$config ['allowed_types'] = 'gif|jpg|jpeg|png|pdf|doc|docx|rar|zip';
		$config ['max_size'] = '8192';
		$config ['file_name'] = build_order_no ();
		$save_path = '/uploads/my/apply/' . date ( 'Y' ) . '/' . date ( 'md' ) . '/';
		
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
		$userid=intval(trim($this->input->get('userid')));
		if ($applyid) {
			$applyid = intval ( cucas_base64_decode ( $applyid ) );
			$where_apply_info = "id = {$applyid} AND userid = {$userid}";
			
			$apply_info = $this->apply_model->get_apply_info ( $where_apply_info );
			
			$where_c = "id = {$apply_info['courseid']} AND state = 1";
			$course = $this->course_model->get_one ( $where_c );
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
					$where_apply_info = "id = {$applyid} AND userid = {$userid}";
					
					// 首先要更改 isstart = 1
					
					$dataS = array (
							'lasttime' => time (),
							'isatt' => 1 
					);
					$this->apply_model->save_apply_info ( $where_apply_info, $dataS );
					ajaxReturn ( '', '', 1 );
				}
			} else {
				$where_apply_info = "id = {$applyid} AND userid = {$userid}";
				
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
	 * 支付
	 */
	function make_paymeznt() {
		$applyid = trim ( $this->input->get ( 'applyid' ) );
//        var_dump($applyid);exit;
		$userid=intval(trim($this->input->get('userid')));
		if ($applyid) {
			$applyid = intval ( cucas_base64_decode ( $applyid ) );
			$where_apply_info = "id = {$applyid} AND userid = {$userid}";
			// 首先要更改 isstart = 1
			
			$dataS = array (
					'isstart' => 1,
					'lasttime' => time () 
			);
			// 不收费
			if ($this->isapplypay == 0) {
				$data ['paystate'] = 1;
				$data ['paytime'] = time ();
				$data ['registration_fee'] = 0;
				$this->apply_model->save_apply_info ( $where_apply_info, $data );
			}
			$this->apply_model->save_apply_info ( $where_apply_info, $dataS );
			// 查询数据
			$apply_info = $this->apply_model->get_apply_info ( $where_apply_info );
			// 查询的课程信息
			$this->isapply ( $apply_info ['courseid'] ,$userid);
			$where_c = "id = {$apply_info['courseid']} AND state = 1";
			$course = $this->course_model->get_one ( $where_c );
			
			// 查询课程的详情页
			$coursename = $this->course_model->get_one_content ( 'majorid = ' . $apply_info ['courseid'] . ' AND site_language = ' . $this->where_lang );
			
			$this->issubmit = ! empty ( $apply_info ['issubmit'] ) ? $apply_info ['issubmit'] : 0;
			$this->isstart = ! empty ( $apply_info ['isstart'] ) ? $apply_info ['isstart'] : 0;
			$this->apply_history ( '点击支付', $applyid ,$userid);
		}
		
		$this->load->view ( '/master/agencyport/apply_make_payment', array (
				'courseid' => ! empty ( $apply_info ['courseid'] ) ? $apply_info ['courseid'] : '',
				'apply_info' => ! empty ( $apply_info ) ? $apply_info : array (),
				'course' => ! empty ( $course ) ? $course : array (),
				'issubmit' => $this->issubmit,
				'isstart' => $this->isstart,
				'coursename' => ! empty ( $coursename ) ? ( object ) $coursename : array (),
				'isapplypay' => ! empty ( $this->isapplypay ) ? $this->isapplypay : 0,
				'coursenames' => ! empty ( $course ) ? $course : array () ,
                'userid'=>$userid
		) );
	}
	
	/**
	 * 提交
	 */
	function submit_application() {
		$applyid = trim ( $this->input->get ( 'applyid' ) );
        $userid=intval(trim($this->input->get('userid')));
		if ($applyid) {
			$applyid = intval ( cucas_base64_decode ( $applyid ) );
			$where_apply_info = "id = {$applyid} AND userid = {$userid}";
			
			// 首先要更改 isstart = 1
			
			$dataS = array (
					'lasttime' => time () 
			);
			
			$this->apply_model->save_apply_info ( $where_apply_info, $dataS );
			// 查询数据
			$apply_info = $this->apply_model->get_apply_info ( $where_apply_info );
			$this->isapply ( $apply_info ['courseid'] );
			$where_c = "id = {$apply_info['courseid']} AND state = 1";
			$course = $this->course_model->get_one ( $where_c );
			$this->issubmit = ! empty ( $apply_info ['issubmit'] ) ? $apply_info ['issubmit'] : 0;
			$this->isstart = ! empty ( $apply_info ['isstart'] ) ? $apply_info ['isstart'] : 0;
		}
		$this->load->view ( '/master/agencyport/apply_submit_application', array (
				'courseid' => ! empty ( $apply_info ['courseid'] ) ? $apply_info ['courseid'] : '',
				'apply_info' => ! empty ( $apply_info ) ? $apply_info : array (),
				'course' => ! empty ( $course ) ? $course : array (),
				'issubmit' => $this->issubmit,
				'isstart' => $this->isstart,
				'coursenames' => $course ,
                'userid'=>$userid
		) );
	}
	
	/**
	 * 最后一步 提交
	 */
	function save_submit() {
		$applyid = trim ( $this->input->get ( 'applyid' ) );
        $userid=intval(trim($this->input->get('userid')));
		if (! empty ( $applyid )) {
			$applyid = intval ( cucas_base64_decode ( $applyid ) );
			$where_apply_info = "id = {$applyid} AND userid = {$userid}";
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
            //更新该申请是中介端操作的
            $age=array(
                'is_agency'=>1,
                'agency_id'=>$this->agencyid
            );
            $this->apply_model->save_apply_info ( $where_apply_info, $age );
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
	function apply_history($action = null, $applyid = null,$userid=0) {
		$data = array (
				'userid' => $userid,
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
	private function is_new_apply($courseid = null,$userid) {
		if (! empty ( $courseid )) {
			$flag = false; // 勇于判断 是否 是 插入数据
			               // 获取 信息
			$where_c = "id = {$courseid} AND state = 1";
			$course = $this->course_model->get_one ( $where_c );
			
			// 获取 申请信息
			$where_apply_info = "courseid = {$courseid} AND userid = {$userid}";
			$apply_info = $this->apply_model->get_apply_info ( $where_apply_info );
			// 根据 课程id 和 userid 确定一条记录 如果没有 就 生成一条新的记录
			// 如果 有了 就要 判断了 首先 同一 学期 不能申请两次 不同学期的 可以申请
			// 产生一条 新纪录 如果是 已经申请了 一年内 就要 判断 现在 他所在的 步骤 跳到 相应的步骤中去
			// 看看 提交了 吗 如果是 提交了 就不能 跳到 这里了 直接到 个人中心去
			// 已有数据
			if (! empty ( $apply_info )) {
				
				// 开学时间
				$opening = ! empty ( $apply_info ['opening'] ) ? $apply_info ['opening'] : 0;
				
				// 一年后
				$opening = $opening + 3600 * 24 * 365;
				// 看是否是超过了一年
				
				if (time () - $opening > 0) {
					
					$flag = true;
				} else {
					// 看是否提交了
					$issubmit = $apply_info ['issubmit'];
					if ($issubmit == 0) {
						// 未提交 跳到 相应的 步骤去 跳到开始去
						if ($apply_info ['isstart'] == 0) {
							$jumpurl = '/' . $this->puri . '/student/apply?courseid=' . cucas_base64_encode ( $courseid );
							return $data = array (
									'jumpurl' => $jumpurl,
									'flag' => 2 
							);
						}
						
						// 未提交 跳到 相应的 步骤去 填表
						if ($apply_info ['isinformation'] == 0) {
							$jumpurl = '/' . $this->puri . '/student/fillingoutforms/apply?applyid=' . cucas_base64_encode ( $apply_info ['id'] );
							return $data = array (
									'jumpurl' => $jumpurl,
									'flag' => 1 
							);
						}
						
						// 未提交 跳到 相应的 步骤去 填表
						if ($apply_info ['isatt'] == 0) {
							$jumpurl = '/' . $this->puri . '/student/apply/upload_materials?applyid=' . cucas_base64_encode ( $apply_info ['id'] );
							return $data = array (
									'jumpurl' => $jumpurl,
									'flag' => 1 
							);
						}
						
						// 未提交 跳到 相应的 步骤去 支付
						if ($apply_info ['paystate'] == 0) {
							$jumpurl = '/' . $this->puri . '/student/apply/make_paymeznt?applyid=' . cucas_base64_encode ( $apply_info ['id'] );
							return $data = array (
									'jumpurl' => $jumpurl,
									'flag' => 1 
							);
						}
						$jumpurl = '/' . $this->puri . '/student/apply/submit_application?applyid=' . cucas_base64_encode ( $apply_info ['id'] );
						return $data = array (
								'jumpurl' => $jumpurl,
								'flag' => 1 
						);
					} else {
						// 打回
						if ($apply_info ['state'] == 3) {
							$jumpurl = '/' . $this->puri . '/student/fillingoutforms/apply?applyid=' . cucas_base64_encode ( $apply_info ['id'] );
							return $data = array (
									'jumpurl' => $jumpurl,
									'flag' => 1 
							);
						} else {
							// 跳到个人中心
							$jumpurl = '/' . $this->puri . '/student';
							return $data = array (
									'jumpurl' => $jumpurl,
									'flag' => 1 
							);
						}
					}
				}
			} else {
				$flag = true;
			}
			
			if ($flag == true) {
				// 新数据
				$max_cucasid = build_order_no ();
				
				// 向申请表中添加数据
				$data = array (
						'number' => $max_cucasid,
						'userid' => $userid,
						'courseid' => $courseid,
						'tuition' => ! empty ( $course ['tuition'] ) ? $course ['tuition'] : '',
						'applytime' => time (),
						'registration_fee' => ! empty ( $course ['applytuition'] ) ? $course ['applytuition'] : '',
						'danwei' => $course['danwei'],
						'paystate' => 0,
						'lasttime' => time (),
						'isstart' => 0,
						'opening' => ! empty ( $course ['opentime'] ) ? $course ['opentime'] : 0 
				// 'isscholar' => ! empty ( $course ['scholarship'] ) ? $course ['scholarship'] : 0
								);
				
				// 不收费
				if ($this->isapplypay == 0) {
					$data ['paystate'] = 1;
					$data ['paytime'] = time ();
					$data ['registration_fee'] = 0;
				} else {
					if (empty ( $course ['applytuition'] )) {
						$data ['registration_fee'] = $this->applyfee;
						$data['danwei'] = $this->applydanwei;
					}
				}
				$a_id = $this->apply_model->save_apply_info ( null, $data );
				if (! empty ( $a_id )) {
					// 查询是否 交押金
					
					$pledge = CF ( 'pledge', '', CONFIG_PATH );
					if (! empty ( $pledge ) && $pledge ['pledge'] == 'yes') {
						$pledge_ons = 1;
						$pledge_feess = $pledge ['pledgemoney'];
						if ($pledge ['pledgeway'] == 'pledgeusd') {
							$yjdws = 'USD';
						} else if ($pledge ['pledgeway'] == 'pledgermb') {
							$yjdws = 'RMB';
						}
						
						$datadep = array (
								'userid' => $userid,
								'applyid' => $a_id,
								'registeration_fee' => $pledge_feess,
								'danwei' => $yjdws,
								'applytime' => time (),
								'paystate' => 0,
								'lasttime' => time () 
						);
						$this->db->insert ( 'deposit_info', $datadep );
						$this->db->update ( 'apply_info', array (
								'deposit_fee' => $pledge_feess 
						), 'id = ' . $a_id );
					}
				}
				$jumpurl = '/' . $this->puri . '/student/apply?courseid=' . cucas_base64_encode ( $courseid );
				return $data = array (
						'jumpurl' => $jumpurl,
						'flag' => 2 
				);
			}
		}
		return $data = array (
				'jumpurl' => '/student',
				'flag' => 0 
		);
	}
	
	/**
	 * 验证课程是否可申请
	 */
	private function isapply($courseid,$userid=0) {
		$this->load->model ( 'home/fillingoutforms_model', 'model' );
		$this->load->model ( 'home/validate_model' );
		$is = $this->validate_model->isapply ( $courseid );
		if ($is === false) {
			// $this->user_msg ( 'Application Deadline Passed', 'http://'.$_SERVER['HTTP_HOST'].'/student/index', 1, 3 );
			$html = $this->load->view ( 'student/apply_permissions', array (
					'info' => 'Application Deadline Passed' 
			), true );
			echo $html;
			die ();
		}
		
		$is = $this->model->get_apply_info ( $courseid, $userid );

		// 验证是否可填写
		$lock_arr = array (
				0,
				2,
				11 
		);
		if (! in_array ( !empty($is->state)?$is->state:0, $lock_arr )) {
			// $this->user_msg ( 'CUCAS has to verify your information, documents modification unavailable now. Please try again later.', 'http://'.$_SERVER['HTTP_HOST'].'/student/index', 1, 3 );
			$html = $this->load->view ( 'student/apply_permissions', array (
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
    /**
     * 获取中介公司的账号id
     */
    function get_agencyid($userid){
        $data=$this->db->select ( 'id' )->get_where ( 'agency_info', 'userid = ' . $userid )->row_array ();
        return $data['id'];
    }
}