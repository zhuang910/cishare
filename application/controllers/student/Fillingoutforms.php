<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 填写表单
 *
 * @author junjiezhang
 *        
 */
class FillingOutForms extends Student_Basic {
	protected $html_main = null;
	protected $html_left = null;
	protected $html_form = null;
	protected $form_state = array ();
	protected $user_form_data = array ();
	protected $issubmit = 0;
	protected $isstart = 0;
	function __construct() {
		parent::__construct ();
		is_studentlogin ();
		$this->view = 'student/';
		$this->load->model ( 'home/fillingoutforms_model', 'model' );
		$this->load->model ( 'home/user_model' );
		$this->load->model ( 'home/apply_model' );
		$this->load->model ( 'home/course_model' );
		$this->load->model ( 'home/fillingoutforms_model' );
	}
	
	/**
	 * 申请表
	 *
	 * @param number $cid        	
	 */
	function apply() {
		$applyid = trim ( $this->input->get ( 'applyid' ) );
		$scholorshipid = intval ( trim ( $this->input->get ( 'scholorshipid' ) ) );
		$issch = intval ( trim ( $this->input->get ( 'issch' ) ) );
		$applyid = ( int ) cucas_base64_decode ( $applyid );
		
		if (! empty ( $applyid )) {
			$where_apply_info = "id = {$applyid} AND userid = {$_SESSION['student'] ['userinfo'] ['id']}";
			
			// 首先要更改 isstart = 1
			
			$dataS = array (
					'isstart' => 1,
					'lasttime' => time () 
			);
			
			// 申请了 中国政府奖学金
			if (! empty ( $scholorshipid ) && $issch > 0) {
				$dataS ['scholorshipid'] = $scholorshipid;
				$dataS ['isscholar'] = 1;
				$dataS ['scholorstate'] = 0;
				
				// 奖学金 申请表 中的数据
				$max_number = build_order_no ();
				$dataScholarship = array (
						'number' => $max_number,
						'userid' => $_SESSION ['student'] ['userinfo'] ['id'],
						'scholarshipid' => $scholorshipid,
						'type' => 3,
						'name' => ! empty ( $_SESSION ['student'] ['userinfo'] ['enname'] ) ? $_SESSION ['student'] ['userinfo'] ['enname'] : '',
						'passport' => ! empty ( $_SESSION ['student'] ['userinfo'] ['passport'] ) ? $_SESSION ['student'] ['userinfo'] ['passport'] : '',
						'email' => ! empty ( $_SESSION ['student'] ['userinfo'] ['email'] ) ? $_SESSION ['student'] ['userinfo'] ['email'] : '',
						'nationality' => ! empty ( $_SESSION ['student'] ['userinfo'] ['nationality'] ) ? $_SESSION ['student'] ['userinfo'] ['nationality'] : '',
						'applytime' => time (),
						'isstart' => 1,
						'isinformation' => 1,
						'isatt' => 1,
						'issubmit' => 1,
						'state' => 0,
						'lasttime' => time () 
				);
				
				if (! empty ( $dataScholarship )) {
					$this->db->insert ( 'applyscholarship_info', $dataScholarship );
				}
			}
			
			$this->apply_model->save_apply_info ( $where_apply_info, $dataS );
			
			$apply_info = $this->apply_model->get_apply_info ( $where_apply_info );
			$this->issubmit = $apply_info ['issubmit'];
			$this->isstart = $apply_info ['isstart'];
			$cid = $apply_info ['courseid'];
			$this->isapply ( $cid );
			if ($cid) {
				// 验证是否已经删除
				$is = $this->model->get_apply_info ( $cid, $_SESSION ['student'] ['userinfo'] ['id'] );
				if (empty ( $is )) {
					// $this->user_msg ( 'Application Error', 'http://'.$_SERVER['HTTP_HOST'].'/student/index', 1, 3 );
					$html = $this->load->view ( 'student/apply_permissions', array (
							'info' => 'Application Error' 
					), true );
					echo $html;
					die ();
				}
				
				// 验证课程
				$course_info = $this->model->get_course_info ( $cid );
				if (empty ( $course_info )) {
					// $this->user_msg ( ', 'http://'.$_SERVER['HTTP_HOST'].'/student/index', 1, 3 );
					$html = $this->load->view ( 'student/apply_permissions', array (
							'info' => 'No Program' 
					), true );
					echo $html;
					die ();
				}
				
				// 获取状态
				$state = $this->get_apply_state ( $cid, $_SESSION ['student'] ['userinfo'] ['id'] );
				if (empty ( $state )) {
					redirect ( 'http://' . $_SERVER ['HTTP_HOST'] . '/' . $this->puri . '/student/apply?courseid=' . cucas_base64_encode ( $cid ) );
				}
				$this->get_form_state ( $cid );
				
				// 获取表单数据
				$form_data = $this->get_form_data ( $cid );
				$secret_key = 'class_id=' . $cid . "," . "&uid=" . $_SESSION ['student'] ['userinfo'] ['id'] . "&form_type=application&applyid=" . $apply_info ['id'];
				$pay_key_secret = cucas_base64_encode ( $secret_key );
				$coursenames = $this->course_model->get_one ( 'id = ' . $cid );
				// 生成表单html
				$this->get_form_html ( $form_data );
				$this->_view ( 'fill_form', array (
						'html_left' => $this->html_left,
						'html_form' => $this->html_form,
						'main_state' => $state,
						'cid' => cucas_base64_encode ( $cid ),
						'step' => 1,
						'pay_key_secret' => $pay_key_secret,
						'apply_info' => ! empty ( $apply_info ) ? $apply_info : array (),
						'courseid' => $apply_info ['courseid'],
						'issubmit' => $this->issubmit,
						'isstart' => $this->isstart,
						'coursenames' => $coursenames 
				) );
			}
		}
	}
	
	/**
	 * 保存数据
	 */
	function save($c_id, $ischeck = null) {
		if (IS_AJAX) {
			// 申请表的id
			$cid = ( int ) cucas_base64_decode ( $c_id );
			
			$ischeck = trim ( $ischeck );
			if ($cid) {
				$data = $this->input->post ();
				if (! empty ( $data ['validfrom'] )) {
					$dataSz ['validfrom'] = strtotime ( $data ['validfrom'] );
				}
				if (! empty ( $data ['validuntil'] )) {
					$dataSz ['validuntil'] = strtotime ( $data ['validuntil'] );
				}
				
				if (! empty ( $data ['birthdate_year'] )) {
					$dataSz ['birthday'] = strtotime ( $data ['birthdate_year'] );
				}
				
				if (! empty ( $data ['gender'] )) {
					if ($data ['gender'] == 'Male') {
						$sex = 1;
					} elseif ($data ['gender'] == 'Female') {
						$sex = 2;
					} else {
						$sex = '';
					}
					
					$dataSz ['sex'] = $sex;
				}
				// var_dump($dataSz);exit;
				if (! empty ( $data ['lastname'] )) {
					$dataSz ['lastname'] = $data ['lastname'];
				}
				if (! empty ( $data ['firstname'] )) {
					$dataSz ['firstname'] = $data ['firstname'];
				}
				if (! empty ( $data ['passportno'] )) {
					$dataSz ['passport'] = $data ['passportno'];
				}
				
				if (! empty ( $data ['lastname'] )) {
					$dataSz ['enname'] = $data ['lastname'];
				}
				
				if (! empty ( $data ['chinesename'] )) {
					$dataSz ['chname'] = $data ['chinesename'];
				}
				
				if (! empty ( $data ['religion'] )) {
					$dataSz ['religion'] = $data ['religion'];
				}
				if (! empty ( $data ['Cmobile'] )) {
					$dataSz ['mobile'] = $data ['Cmobile'];
				}
				if (! empty ( $data ['Cphone'] )) {
					$dataSz ['tel'] = $data ['Cphone'];
				}
				if (! empty ( $data ['Chinese_yes'] )) {
					$dataSz ['language_level'] = $data ['Chinese_yes'];
				}

				if (! empty ( $dataSz )) {
					$this->db->update ( 'student_info', $dataSz, 'id = ' . $_SESSION ['student'] ['userinfo'] ['id'] );
				}
				isset ( $data ['isDeclaration'] ) ? $data ['isDeclaration'] : $data ['isDeclaration'] = '';
				if(isset($data['is_in_china']) && !empty($data['is_in_china'])){
					$this->db->update ( 'apply_info', array('isinchina' => 1), 'id = ' . $cid );
					
				}else{
					$this->db->update ( 'apply_info', array('isinchina' => 0), 'id = ' . $cid );
				}
				// $this->load->library ( 'cimongo/cimongo' );
				
				// $is = $this->model->get_apply_info ( $cid, $_SESSION ['student'] ['userinfo'] ['id'] );
				$where_apply_info = "id = {$cid} AND userid = {$_SESSION['student'] ['userinfo'] ['id']}";
				$is = $this->apply_model->get_apply_info ( $where_apply_info );
				
				if (! empty ( $is )) {
					$where_del = "applyid = {$cid} AND userid = {$_SESSION['student'] ['userinfo'] ['id']}";
					$del = $this->apply_model->del_apply_template_info ( $where_del );
					if ($del) {
						foreach ( $data as $k => $item ) {
							if (is_array ( $item )) {
								$item = serialize ( $item );
							}
							$this->apply_model->save_apply_template_info ( array (
									'key' => $k,
									'value' => $item,
									'applyid' => $cid,
									'userid' => $_SESSION ['student'] ['userinfo'] ['id'],
									'courseid' => $is ['courseid'],
									'time' => time () 
							) );
						}
					}
					// $this->update_state ( $is ['courseid'], $is ['id'], $ischeck );
					
					$url = $ischeck == 'ischeck' ? '/' . $this->puri . '/student/apply/upload_materials?applyid=' . $c_id : '';
					if ($ischeck) {
						// 还要更新一条数据 就是申请信息的 表单 填写 情况
						$dataApply = array (
								'isinformation' => 1,
								'lasttime' => time () 
						);
						$whereApply = "id = {$cid}";
						$this->apply_model->save_apply_info ( $whereApply, $dataApply );
						
						$this->apply_history ( '填写申请表', $cid );
						ajaxReturn ( $url, lang ( 'update_success' ), 1 );
					} else {
						$this->apply_history ( '填写申请表', $cid );
						ajaxReturn ( '', lang ( 'update_success' ), 1 );
					}
				}
			}
		}
		ajaxReturn ( '', lang ( 'update_error' ), 0 );
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
	 * 获取表单html
	 */
	private function get_form_html($form_data = array()) {
		if (! empty ( $form_data ) && is_array ( $form_data )) {
			$this->html_left .= $this->html_left === null ? '<div class="f_l applyonline-left-nav appNav"><ul class="appNavA">' : '';
			
			$apply = CF ( 'apply', '', CACHE_PATH );
			
			$form_data [] = $apply ['Mailing_Address']; // 模拟地址确认数据
			$form_data [] = $apply ['Declaration']; // 模拟签名数据
			$form_data [] = $apply ['IsinChina']; // 模拟签名数据
			$o_html = $this->get_user_form_item_html ( $form_data );
			
			$is_left_show = null;
			// $is_form_show = null;
			
			foreach ( $form_data as $step => $item ) { // 页
				if (empty ( $item->pages ))
					continue;
				$state = ! empty ( $this->form_state ) && isset ( $this->form_state [$step] ) && $this->form_state [$step] != 0 ? $this->form_state [$step] : 3;
				$is_left_show = (empty ( $this->form_state ) || (! empty ( $this->form_state [$step] ) && $this->form_state [$step] != 1)) && $is_left_show === null ? 'class="appNavAc"' : ($is_left_show === null ? null : '');
				// $is_form_show = (empty ( $this->form_state ) || (! empty ( $this->form_state ) && $this->form_state [$step] != 1)) && $is_form_show === null ? 'appInfoTSelect' : ($is_form_show === null ? null : '');
				$this->html_left .= '<li><a href="javascript:;" ><i>&nbsp;</i><span>' . $item->name . '</span></a></li>';
				$this->html_form .= '<div class="appInfo"><div class="appInfoTitle">' . $item->name . '</div><div class="appInfoContent">';
				$ul = null;
				
				foreach ( $item->pages as $pitem ) { // 群
					$ul .= '<h2 class="appInfoH2 m_b10">' . $pitem->name . '</h2><ul class="apply_form">';
					$other = null;
					if (! empty ( $item->type ) && $item->type > 0 && $item->type < 4) { // 家庭 教育 工作
						$name = ($item->type == 1 ? 'group_family' : ($item->type == 2 ? 'group_edu' : 'group_work'));
						$other = '<li class="jFormer"><div class="jFormComponent jFormComponentName">';
					}
					$li = null;
					
					foreach ( $pitem->items as $xitem ) { // 项
						$isinput = ! empty ( $xitem->isInput ) && $xitem->isInput == 'Y' ? 'validate="required:true"' : '';
						$ishidden = ! empty ( $xitem->isHidden ) && $xitem->isHidden == 'Y' ? 'style="display:none"' : '';
						$value = isset ( $this->user_form_data [$xitem->formID] ) && ! empty ( $this->user_form_data [$xitem->formID] ) ? $this->user_form_data [$xitem->formID] : '';
						$li .= '<li ' . $ishidden . ' id="controlid_' . @$xitem->topic_id . '">';
						$li .= '<span class="appFormEx">' . (! empty ( $xitem->isInput ) && $xitem->isInput == 'Y' ? '<font color="red">*</font>' : '&nbsp;');
						$li .= '</span>';
						switch ($xitem->formType) {
							case 1 : // 文本框
							case 2 : // 日期控件 年月日
								if ($item)
									$is_date_time = $xitem->formType == 2 ? ' datepick' : '';
								if (! empty ( $item->type ) && $item->type > 0 && $item->type < 4) { // 家庭 教育 工作
									$other .= '<div class="middleInitialDiv"><div class="form-group"><input type="text" name="' . $name . '[0][' . $xitem->formID . ']" data-name="' . $xitem->formID . '" data-field="' . $name . '"' . $isinput . ' placeholder="' . $xitem->formTitle . '" autocomplete="off" class="form-control' . $is_date_time . '"></div></div>';
								} else {
									$li .= '<input type="text" name="' . $xitem->formID . '" value="' . $value . '" ' . $isinput . ' placeholder="' . $xitem->formTitle . '" autocomplete="off" class="FormText' . $is_date_time . '">';
								}
								break;
                            case 8: // 日期控件 年月
                                if ($item)
                                    $is_date_time = $xitem->formType == 8 ? ' datepick-ym' : '';
                                if (! empty ( $item->type ) && $item->type > 0 && $item->type < 4) { // 家庭 教育 工作
                                    $other .= '<div class="middleInitialDiv"><div class="form-group"><input type="text" name="' . $name . '[0][' . $xitem->formID . ']" data-name="' . $xitem->formID . '" data-field="' . $name . '"' . $isinput . ' placeholder="' . $xitem->formTitle . '" autocomplete="off" class="form-control' . $is_date_time . '"></div></div>';
                                } else {
                                    $li .= '<input type="text" name="' . $xitem->formID . '" value="' . $value . '" ' . $isinput . ' placeholder="' . $xitem->formTitle . '" autocomplete="off" class="FormText' . $is_date_time . '">';
                                }
                                break;
							case 3 : // 文本域
								$li .= '<textarea class="form-control" name="' . $xitem->formID . '" ' . $isinput . ' rows="3" placeholder="' . $xitem->formTitle . '" autocomplete="off">' . $value . '</textarea>';
								break;
							case 4 : // 单选
							case 5 : // 复选
								$is_check_or_radio = $xitem->formType == 4 ? 'radio' : 'checkbox';
								$is_name = $xitem->formType == 5 ? '[]' : '';
								$li .= '<span class="appFormExtit">' . $xitem->formTitle . '</span>';
								if ($xitem->formType == 5) {
									$value = unserialize ( $value );
								}
								
								foreach ( $xitem->options as $k => $oitem ) {
									$is = $k == 0 ? $isinput : '';
									$ischeck = $xitem->formType == 5 && ! empty ( $value ) ? (in_array ( $oitem->formValue, $value ) ? 'checked="checked"' : '') : ($oitem->formValue == $value ? 'checked="checked"' : '');
									$is_c = ! empty ( $oitem->ControlID ) ? 'onclick="zjj_show(' . $oitem->ControlID . ',' . $xitem->topic_id . ')" rel="' . $item->id . '_' . $xitem->topic_id . '"' : '';
									$is = (! empty ( $oitem->ControlID ) && $k = 0) || empty ( $oitem->ControlID ) ? $is : '';
									$li .= '<label class="Formredio"><input type="' . $is_check_or_radio . '" name="' . $xitem->formID . $is_name . '" value="' . $oitem->formValue . '" ' . $is . ' ' . $ischeck . ' ' . $is_c . '  class="redio"> ' . stripslashes ( $oitem->itemTitle ) . '</label>';
								}
								break;
							case 6 : // 下拉列表
								$public = CF ( 'public', '', CACHE_PATH );
								$arr = array ();
								$li .= '<span class="appFormExtit">' . $xitem->formTitle . '</span>';
								if (! empty ( $xitem->phpArrar )) {
									$arr = $public [$xitem->phpArrar];
									$li .= '<select name="' . $xitem->formID . '" ' . $isinput . ' class="Formselect">';
									foreach ( $arr as $val => $name ) {
										$li .= '<option value="' . $val . '" ' . ($val == $value ? 'selected' : '') . '>' . $name . '</option>';
									}
									$li .= '</select>';
								} else {
									$li .= '<select name="' . $xitem->formID . '" ' . $isinput . ' class="Formselect">';
									foreach ( $xitem->options as $val => $i ) {
										$li .= '<option value="' . $i->formValue . '" ' . ($i->formValue == $value ? 'selected' : '') . '>' . $i->itemTitle . '</option>';
									}
									$li .= '</select>';
								}
								
								break;
							case 7 : // 标签
								$li .= '<div>' . $xitem->formTitle . '</div>';
							default :
								break;
						}
						$li .= ! empty ( $xitem->formHelp ) ? '<span class="RFormQues" title="' . $xitem->formHelp . '"></span>' : '';
						$li .= ! empty ( $xitem->des ) ? '<span class="RFormdes">' . $xitem->des . '</span>' : '';
						$li .= '</li>';
					}
					
					if (isset ( $o_html [$step] ) && ! empty ( $o_html [$step] )) {
						$o_item_html = $o_html [$step] . '<input type="button" class="jFormComponentAddInstanceButton" value=" Add"></li>';
					} else {
						$o_item_html = ($other === null ? $li : $other . '</div><input type="button" class="jFormComponentAddInstanceButton" value=" Add"></li>');
					}
					$ul .= $o_item_html . '</ul>';
				}
				$this->html_form .= $ul . '</div></div>';
			}
			$this->html_left .= '</ul></div>';
		}
	}
	
	/**
	 * 获取组表单项
	 *
	 * @param string $name        	
	 */
	private function get_user_form_item_html($form_data = array()) {
		$return = array ();
		$is_check_data = $this->get_is_check_type ( $form_data );
		if (! empty ( $this->user_form_data ) && ! empty ( $form_data )) {
			foreach ( $form_data as $step => $item ) {
				if (empty ( $item->pages ))
					continue;
				if (! empty ( $item->type ) && $item->type > 0 && $item->type < 4) { // 家庭 教育 工作
					
					$name = ($item->type == 1 ? 'group_family' : ($item->type == 2 ? 'group_edu' : 'group_work'));
					$o_value = isset ( $this->user_form_data [$name] ) && ! empty ( $this->user_form_data [$name] ) ? unserialize ( $this->user_form_data [$name] ) : array ();
					$return [$step] = '<li class="jFormer">';
					foreach ( $o_value as $k => $o_item ) {
						$return [$step] .= '<div class="jFormComponent jFormComponentName">';
						foreach ( $o_item as $field => $citem ) {
							$isinput = ! empty ( $is_check_data [$step] ['check'] ) && in_array ( $field, $is_check_data [$step] ['check'] ) ? 'validate="required:true"' : '';
							$is_date_time = ! empty ( $is_check_data [$step] ['isdate'] ) && in_array ( $field, $is_check_data [$step] ['isdate'] ) ? ' datepick' : '';
							$return [$step] .= '<div class="middleInitialDiv"><div class="form-group"><input type="text" value="' . $citem . '" name="' . $name . '[' . $k . '][' . $field . ']" data-name="' . $field . '" data-field="' . $name . '"' . $isinput . ' placeholder="' . (! empty ( $is_check_data [$step] ['title'] [$field] ) ? $is_check_data [$step] ['title'] [$field] : '') . '" autocomplete="off" class="form-control' . $is_date_time . '"></div></div>';
						}
						
						$return [$step] .= ($k > 0 ? '<input type="button" class="jFormComponentRemoveInstanceButton" value="&#12288; Remove">' : '') . '</div>';
					}
				}
			}
		}
		return $return;
	}
	
	/**
	 * 统计状态
	 *
	 * @param number $aid        	
	 */
	private function get_form_state($cid = 0) {
		$is = $this->model->get_apply_info ( $cid, $_SESSION ['student'] ['userinfo'] ['id'] );
		if (! empty ( $is )) {
			/*
			 * $this->load->library ( 'cimongo/cimongo' ); $form_state = $this->cimongo->where ( array ( 'id' => ( int ) $is->id ) )->get ( 'apply_form_state' )->result (); foreach ( $form_state as $item ) { $this->form_state [$item->step] = $item->state; }
			 */
			
			// 获取用户填写数据
			$user_form_data = $this->get_user_form_data ( $is->id );
			
			foreach ( $user_form_data as $item ) {
				$this->user_form_data [$item->key] = $item->value;
			}
		}
	}
	
	/**
	 * 获取表单数据
	 */
	private function get_form_data($cid = 0) {
		if ($cid) {
			// 获取申请表
			$course_info = $this->model->get_course_info ( $cid );
			
			$form_id = $this->model->get_form_id ( $cid );
			if (! $form_id) { // 查找院校中是否设置默认申请表
				$form_id = $this->model->get_default_form_id ();
			}
			
			// 获取申请表数据
			$form_data = $this->model->get_form_data ( ( int ) $form_id );
			
			foreach ( $form_data as $item ) {
				$item->pages = $this->model->get_form_data ( ( int ) $item->id, 3 );
				foreach ( $item->pages as $pitem ) {
					$pitem->items = $this->model->get_form_item ( $pitem->id );
				}
			}
			return $form_data;
		}
	}
	
	/**
	 * 获取用户填写数据
	 *
	 * @param number $cid        	
	 */
	private function get_user_form_data($id = 0) {
		if ($id) {
			return $this->apply_model->get_apply_template_info ( 'applyid = ' . $id );
		}
		return array ();
	}
	
	/**
	 * 获取表单属性
	 */
	private function get_is_check_type($form_data = array()) {
		$is_check_field = array ();
		foreach ( $form_data as $key => $item ) {
			if (! empty ( $item->type ) && $item->type == 4) {
				continue;
			}
			
			if (! empty ( $item->type ) && $item->type > 0 && $item->type < 4) { // 家庭 教育 工作
				$is_check_field [$key] ['check'] ['field'] = ($item->type == 1 ? 'group_family' : ($item->type == 2 ? 'group_edu' : 'group_work'));
			}
			foreach ( $item->pages as $pitem ) {
				foreach ( $pitem->items as $xitem ) {
					if (! empty ( $xitem->isInput ) && $xitem->isInput == 'Y') {
						$is_check_field [$key] ['check'] [] = $xitem->formID;
					}
					if (! empty ( $xitem->formType ) && $xitem->formType == '2') {
						$is_check_field [$key] ['isdate'] [] = $xitem->formID;
					}
					$is_check_field [$key] ['title'] [$xitem->formID] = $xitem->formTitle;
				}
			}
		}
		return $is_check_field;
	}
	
	/**
	 * 更新状态
	 *
	 * @param number $cid        	
	 */
	private function update_state($cid = 0, $id = 0, $ischeck = null) {
		if ($cid) {
			
			$form_data = $this->get_form_data ( $cid );
			
			$apply = CF ( 'apply', '', CACHE_PATH );
			$form_data [] = $apply ['Mailing_Address']; // 模拟地址确认数据
			$form_data [] = $apply ['Declaration']; // 模拟签名数据
			$form_data [] = $apply ['IsinChina']; // 模拟签名数据
			$user_form_data = $this->get_user_form_data ( $id );
			
			$key_value = array ();
			foreach ( $user_form_data as $item ) {
				$key_value [$item->key] = $item->value;
			}
			
			$is_check_field = array ();
			
			foreach ( $form_data as $key => $item ) {
				if (! empty ( $item->type ) && $item->type == 4) {
					continue;
				}
				
				if (! empty ( $item->type ) && $item->type > 0 && $item->type < 4) { // 家庭 教育 工作
					$is_check_field [$key] ['field'] = ($item->type == 1 ? 'group_family' : ($item->type == 2 ? 'group_edu' : 'group_work'));
				}
				foreach ( $item->pages as $pitem ) {
					foreach ( $pitem->items as $xitem ) {
						if (! empty ( $xitem->isInput ) && $xitem->isInput == 'Y') {
							$is_check_field [$key] [] = $xitem->formID;
						}
					}
				}
			}
			
			// 验证
			$state = array ();
			foreach ( $is_check_field as $step => $item ) {
				$count = count ( $item );
				$is = 0;
				foreach ( $item as $k => $field ) {
					if (is_string ( $k )) {
						$other = unserialize ( $key_value [$field] );
						unset ( $item ['field'] );
						$count = count ( $item );
						foreach ( @$other [0] as $field => $val ) {
							if (in_array ( $field, $item ) && ! empty ( $val )) {
								$is ++;
							}
						}
					} else if (isset ( $key_value [$field] ) && ! empty ( $key_value [$field] )) {
						$is ++;
					}
				}
				
				if ($count == $is || $ischeck == 'ischeck') {
					$state [$step] = 1; // 完成
				} else if ($is == 0) {
					$state [$step] = 0; // 未开始
				} else {
					$state [$step] = 2; // 填写中
				}
			}
			
			// $is = 0;
			// $count = count ( $state );
			// // 验证mongo中是否存在状态
			// $this->load->library ( 'cimongo/cimongo' );
			// $del = $this->cimongo->where ( array (
			// 'id' => ( int ) $id
			// ) )->delete ( 'apply_form_state' );
			// if ($del) {
			// foreach ( $state as $k => $val ) {
			// if ($val == 1)
			// $is ++;
			// $this->cimongo->insert ( 'apply_form_state', array (
			// 'id' => ( int ) $id,
			// 'step' => $k,
			// 'state' => $val
			// ) );
			// }
			// }
			
			// $val = 0;
			// // 修改总状态
			// if ($is == $count) {
			// $val = 1;
			// } else if ($is == 0) {
			// $val = 0;
			// } else {
			// $val = 2;
			// }
			
			// // 修改申请表
			// if ($val == 1 || $ischeck == 'ischeck') {
			// $UDB = $this->load->database ( 'cucasuser', true );
			// $UDB->update ( 'apply', array (
			// 'isstart' => 1,
			// 'isinformation' => 1,
			// 'lasttime' => time ()
			// ), 'id = ' . $id );
			
			// // 完成申请 日志记录
			// $UDB->insert ( 'apply_history', array (
			// 'userid' => $_SESSION ['student'] ['userinfo'] ['id'],
			// 'app_id' => $id,
			// 'action' => 'Application form to complete',
			// 'adminid' => 0,
			// 'createtime' => time ()
			// ) );
			// } else {
			// $UDB = $this->load->database ( 'cucasuser', true );
			// $UDB->update ( 'apply', array (
			// 'isstart' => 1,
			// 'isinformation' => 0,
			// 'lasttime' => time ()
			// ), 'id = ' . $id );
			// $val = 2;
			// }
			
			// $is = $this->cimongo->where ( array (
			// 'id' => ( int ) $id
			// ) )->get ( 'apply_state' )->row ();
			
			// if ($is) {
			// $this->cimongo->set ( array (
			// 'form' => $val
			// ) )->where ( array (
			// 'id' => ( int ) $id
			// ) )->update ( 'apply_state' );
			// } else {
			// $this->cimongo->insert ( 'apply_state', array (
			// 'id' => ( int ) $id,
			// 'ready' => 1,
			// 'form' => $val,
			// 'attachment' => 0,
			// 'payment' => 0,
			// 'submit' => 0,
			// 'lasttime' => time ()
			// ) );
			// }
		}
	}
	
	/**
	 * 检测mongo是否存在申请
	 *
	 * @param number $id        	
	 */
	private function check_mongo_isapply($id = 0) {
		if ($id) {
			$is = $this->cimongo->where ( array (
					'id' => ( int ) $id 
			) )->limit ( 1 )->get ( 'apply_data' )->row ();
			return ! empty ( $is ) ? true : false;
		}
		return array ();
	}
	
	/**
	 * 验证课程是否可申请
	 */
	private function isapply($courseid) {
		$this->load->model ( 'home/validate_model' );
		$is = $this->validate_model->isapply ( $courseid );
		if ($is === false) {
			// $this->user_msg ( 'Application Deadline Passed', 'http://'.$_SERVER['HTTP_HOST'].'/student/index', 1, 3 );
			$html = $this->load->view ( 'student/apply_permissions', array (
					'info' => lang ( 'deadline' ) 
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
			$html = $this->load->view ( 'student/apply_permissions', array (
					'info' => lang ( 'nopermissions' ) 
			), true );
			echo $html;
			die ();
		}
		return true;
	}
}