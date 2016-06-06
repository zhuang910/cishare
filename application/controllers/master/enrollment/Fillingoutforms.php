<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 填写表单
 *
 * @author junjiezhang
 *        
 */
class FillingOutForms extends Master_Basic {
	protected $html_main = null;
	protected $html_left = null;
	protected $html_form = null;
	protected $form_state = array ();
	protected $user_form_data = array ();
	protected $issubmit = 0;
	protected $isstart = 0;
	function __construct() {
		parent::__construct ();
		$this->view = 'student/';
		// $this->load->model ( 'home/fillingoutforms_model', 'model' );
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
		$a_id = $applyid;
		
		$template = ( int ) $this->input->get ( 'template' );
		
		$applyid = ( int ) cucas_base64_decode ( $applyid );
		if (! empty ( $applyid )) {
			$where_apply_info = "id = {$applyid}";
			$apply_info = $this->apply_model->get_apply_info ( $where_apply_info );
			
			$cid = $apply_info ['courseid'];
			
			if ($cid) {
				// 验证课程
				$course_info = $this->fillingoutforms_model->get_course_info ( $cid );
				$this->get_form_state ( $cid, $applyid );
				// 获取表单数据
				$form_data = $this->get_form_data ( $cid );
				$coursenames = $this->course_model->get_one ( 'id = ' . $cid );
				// 生成表单html
				$this->get_form_html ( $form_data );
				
				if (empty ( $template )) {
					$this->load->view ( 'master/enrollment/studentedit/fill_edit',array(
							'a_id' => $a_id
					) );
				} else {
					$this->load->view ( 'master/enrollment/studentedit/fill_form', array (
							'html_left' => $this->html_left,
							'html_form' => $this->html_form,
							
							'cid' => cucas_base64_encode ( $cid ),
							
							'apply_info' => ! empty ( $apply_info ) ? $apply_info : array (),
							'courseid' => $apply_info ['courseid'],
							
							'coursenames' => $coursenames,
							'a_id' => $a_id 
					) );
				}
			}
		}
	}
	
	/**
	 * 保存数据
	 */
	function save($c_id) {
		if (IS_AJAX) {
			// 申请表的id
			$cid = ( int ) cucas_base64_decode ( $c_id );
			
			if ($cid) {
				$apply_info = $this->apply_model->get_apply_info ( 'id = ' . $cid );
				
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
					$dataSz ['sex'] = $data ['gender'];
				}
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
				if (! empty ( $dataSz )) {
					$this->db->update ( 'student_info', $dataSz, 'id = ' . $apply_info ['userid'] );
				}
				isset ( $data ['isDeclaration'] ) ? $data ['isDeclaration'] : $data ['isDeclaration'] = '';
				// $this->load->library ( 'cimongo/cimongo' );
				
				// $is = $this->fillingoutforms_model->get_apply_info ( $cid, $_SESSION ['student'] ['userinfo'] ['id'] );
				$where_apply_info = "id = {$cid} AND userid = {$apply_info['userid']}";
				$is = $this->apply_model->get_apply_info ( $where_apply_info );
				
				if (! empty ( $is )) {
					$where_del = "applyid = {$cid} AND userid = {$is['userid']}";
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
									'userid' => $is ['userid'],
									'courseid' => $is ['courseid'],
									'time' => time () 
							) );
						}
					}
					ajaxReturn ( '', 'Success', 1 );
				}
			}
		}
		ajaxReturn ( '', 'Error', 0 );
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
							case 2 : // 日期控件
								if ($item)
									$is_date_time = $xitem->formType == 2 ? ' datepick' : '';
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
	private function get_form_state($cid = 0, $applyid = 0) {
		$user_form_data = $this->get_user_form_data ( $applyid );
		
		foreach ( $user_form_data as $item ) {
			$this->user_form_data [$item->key] = $item->value;
		}
	}
	
	/**
	 * 获取表单数据
	 */
	private function get_form_data($cid = 0) {
		if ($cid) {
			// 获取申请表
			$course_info = $this->fillingoutforms_model->get_course_info ( $cid );
			
			$form_id = $this->fillingoutforms_model->get_form_id ( $cid );
			if (! $form_id) { // 查找院校中是否设置默认申请表
				$form_id = $this->fillingoutforms_model->get_default_form_id ();
			}
			
			// 获取申请表数据
			$form_data = $this->fillingoutforms_model->get_form_data ( ( int ) $form_id );
			
			foreach ( $form_data as $item ) {
				$item->pages = $this->fillingoutforms_model->get_form_data ( ( int ) $item->id, 3 );
				foreach ( $item->pages as $pitem ) {
					$pitem->items = $this->fillingoutforms_model->get_form_item ( $pitem->id );
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
}