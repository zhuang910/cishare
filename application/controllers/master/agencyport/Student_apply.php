<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * ºóÌ¨Ê×Ò³
 *
 * @author JJ
 *
 */
class Student_apply extends Master_Basic {
    protected $issubmit = 0;
    protected $isstart = 0;
    protected $html_main = null;
    protected $html_left = null;
    protected $html_form = null;
    protected $agencyid=0;
    /**
     * »ù´¡Àà¹¹Ôìº¯Êý
     */
    function __construct() {
        parent::__construct ();
        $this->view = 'master/agencyport/';
        $this->load->model ( $this->view . 'student_apply_model' );
        $this->load->library ( 'sdyinc_email' );
        $publics = CF ( 'publics', '', CONFIG_PATH );
        $this->load->vars ( 'publics', $publics );
        // $this->load->model ( 'home/fillingoutforms_model', 'model' );
        $this->load->model ( 'home/user_model' );
        $this->load->model ( 'home/apply_model' );
        $this->load->model ( 'home/course_model' );
        $this->load->model ( 'home/fillingoutforms_model' );
        $this->agencyid=$this->get_agencyid($_SESSION['master_user_info']->id);
		
        $this->load->model ('master/student/student_model' );
    }

    /**
     * ºóÌ¨Ö÷Ò³
     */
    function index() {
        // var_dump($_SESSION);exit;
        if ($this->input->is_ajax_request () === true) {
            // ÉèÖÃ²éÑ¯×Ö¶Î
            $nationality=CF('public','',CACHE_PATH);
            $fields = $this->_set_lists_field ();
            // ²éÑ¯Ìõ¼þ×éºÏ
            $condition = dateTable_where_order_limit ( $fields );

            $output ['sEcho'] = intval ( $_GET ['sEcho'] );

            $output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->student_apply_model->count ( $condition);

            $output ['aaData'] = $this->student_apply_model->get ( $fields, $condition );

            foreach ( $output ['aaData'] as $item ) {
                //查询该学生所报对的专业
                $major=$this->student_apply_model->get_user_major($item->id);
                $item->major=!empty($item->age)?$item->age:'';
                $app_state=$this->student_apply_model->get_apply_student_info($item->id);
                $item->acc_state=!empty($item->nationality)?$nationality['global_country'][$item->nationality]:'';
                $item->operation = '
					<a href="/master/agencyport/student_apply/add?id=' . $item->id . '" class="green" title="Edit" id="edit"><i class="ace-icon fa fa-pencil bigger-130"></i>Edit</a>
					<a class="green" title="Add Application" href="javascript:;" onclick="add_apply('.$item->id.')" ><i class="ace-icon fa fa-leaf bigger-130"></i>Add Application</a>
				';
            }
            // var_dump($output);die;
            exit ( json_encode ( $output ) );
        }

        $this->_view ( 'student_apply_index' ,array(
        ));
    }
    /**
     * ÉèÖÃÁÐ±í×Ö¶Î
     */
    private function _set_lists_field() {
        return array (
            "id",
            "enname",
            "email",
            "passport",
            'nationality'
        );
    }
    /**
     * [add 添加]
     */
    function add() {
        $id = intval ( trim ( $this->input->get ( 'id' ) ) );

        if ($id) {
            $result = $this->student_apply_model->get_one ( 'id =' . $id );
        }
        //¹ú¼®
        $nationality=CF('public','',CACHE_PATH);
		  $major_info = $this->student_model->get_major_info ('id>0',0,0,'language desc');
		
        // 获取学历
        $major_info = $this->_get_major_by_degree($major_info);
        $this->_view ('student_edit',array(
            'info' => ! empty ( $result ) ? $result : array () ,
            'nationality'=>!empty($nationality['global_country'])?$nationality['global_country']:array(),
			'major_info'=>$major_info
        ));
    }
	
	   private function _get_major_by_degree($major_lists = array()){
        $temp = array();
        if(!empty($major_lists)){
            $degree = $this->student_model->get_degree_name('id > 0',0,0,'orderby ASC');
			
            foreach($degree as $key => $item){
                foreach($major_lists as $info){
                    if($info->degree == $item['id']){
                        $temp[$key]['degree_title'] = $item['title'];
                        $temp[$key]['degree_major'][] = $info;
                    }
                }
            }
        }
        return $temp;
    }
    /**
     * ±£´æÊý¾Ý
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
                // ±à¼­¹ÜÀíÔ±
                // Èç¹ûÃ»ÓÐÐÞ¸ÄÃÜÂë Ôò»¹Ô­ÀÏµÄÃÜÂë
                if (empty ( $data ['password'] )) {
                    $data ['password'] = substr ( md5 ( $data ['password'] ), 0, 27 );
                } else {
                    $data ['password'] = substr ( md5 ( $data ['password'] ), 0, 27 );

                }
              //  $data['birthday']=strtotime($data['birthday']);
              //  $data['chname']=$data['chfirstname'].$data['chlastname'];
             //   $data['enname']=$data['lastname'].' '.$data['firstname'];
                $data['enname']=!empty($data['enname'])?$data['enname']:'';
              //  $data['validfrom']=strtotime($data['validfrom']);
              //  $data['validuntil']=strtotime($data['validuntil']);
                $flag = $this->student_apply_model->save ( $id, $data );
            } else {
                $data ['password'] = substr ( md5 ( $data ['password'] ), 0, 27 );
                $data ['registertime'] = time ();
               // $data['birthday']=strtotime($data['birthday']);
               // $data['chname']=$data['chfirstname'].$data['chlastname'];
              //  $data['enname']=$data['lastname'].' '.$data['firstname'];
			   $data['enname']=!empty($data['enname'])?$data['enname']:'';
                $data['registerip']=get_client_ip ();
                $data['state']=1;
                $data['isactive']=0;
              //  $data['validfrom']=strtotime($data['validfrom']);
              //  $data['validuntil']=strtotime($data['validuntil']);
                // var_dump($data);exit;
                $flag = $this->student_apply_model->save ( null, $data );
                if($flag){
                    //¸øÑ§Éú·¢ÓÊ¼þ
                    // ×¢²á³É¹¦ ·¢ËÍÓÊ¼þ
                    $web_email = CF ( 'web_student_email', '', 'application/cache/' );
                    $title = $web_email ['reg_success_email'] ['title'];
                    // ¼ÓÃÜº¯Êý
                    $encode_email = base64_encode ( authcode ( $flag['id'] . '-' . $flag ['email'] . '-cucas', 'ENCODE', 'cucas-confirm-address', 0 ) );
                    $url = "http://" . $_SERVER ['HTTP_HOST'] . "/student/reg/dosuccess?code=" . $encode_email;
                    $content = $this->load->view ( 'student/email/reg_success_email', array (
                        'title' => $title,
                        'email' => $flag ['email'],
                        'url' => $url
                    ), true );
                    $dataemail = array(
                        'title' => $title,
                        'email' => $flag ['email'],
                        'url' => $url
                    );
                    //$this->_send_email ( $_SESSION ['student'] ['userinfo'] ['email'], $title, $content );
                    $MAIL = new sdyinc_email ();
                    $MAIL->dot_send_mail ( 4,$flag ['email'],$dataemail);
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
     * ¼ì²éÓÊÏä ÊÇ·ñ ÖØ¸´
     */
    function checkemail() {
        $email = trim ( $this->input->get ( 'email' ) );
        $id = intval ( trim ( $this->input->get ( 'id' ) ) );
        if (! empty ( $email )) {
            if (! preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email )) {
                // die ( json_encode ( 'Email address format is not correct ' ) );
                die ( json_encode ( '邮箱格式不正确' ) );
            } else {
                $email_true = $this->student_apply_model->get_one ( array (
                    'email' => $email
                ) );

                if ($email_true) {
                    // die ( json_encode ( true ) );
                    if ($email_true->id == $id) {
                        die ( json_encode ( true ) );
                    } else {
                        die ( json_encode ( '邮箱已占用' ) );
                    }
                } else {
                    // die ( json_encode ( 'Email does not exist ' ) );
                    die ( json_encode ( true ) );
                }
            }
        } else {
            die ( json_encode ( '邮箱不能空' ) );
        }
    }
    /**
     * [apply_major Ìî±íÒ³Ãæ]
     * @return [type] [description]
     */
    function apply_major(){
        $userid=intval(trim($this->input->get('userid')));
        $this->_view ( 'apply_major_index', array (
            'userid'=>$userid,
        ) );

    }

    function course_index(){
        $userid=intval(trim($this->input->get('userid')));
        $degree = intval ( trim ( $this->input->get ( 'degree' ) ) );
        if (empty ( $degree )) {
            $degree = 1;
            $_GET ['degree'] = 1;
        }
        $searchname = intval ( trim ( $this->input->get ( 'searchname' ) ) );

        $where = 'id > 0 AND state = 1';

        if (! empty ( $degree )) {
            if ($degree == 1) {
                $where .= ' AND degree NOT IN (2,3,4,5)';
            } else {
                $where .= ' AND degree = ' . $degree;
            }
        }

        if (! empty ( $searchname )) {
            $where .= ' AND id = ' . $searchname;
        }

        $course = $this->student_apply_model->get_course_base ( $where );

        $course_name_all = $this->student_apply_model->get_course_base ( 'state = 1' );
        if (! empty ( $course_name_all )) {
            foreach ( $course_name_all as $k => $v ) {
                $course_name [$v ['id']] = $v ['name'];
            }
        }
        $this->_view ( 'course_index', array (
            'course' => ! empty ( $course ) ? $course : array (),
            'course_name' => ! empty ( $course_name ) ? $course_name : array () ,
            'userid'=>$userid,
            'puri'=>'cn',
        ) );
    }
    /**
     * [is_course_login 跳转申请页面]
     * @return boolean [description]
     */
    function is_course_login(){
        $courseid = intval ( trim ( $this->input->get ( 'courseid' ) ) );
        $userid=intval(trim($this->input->get('userid')));

        //插入申请表
        $aid=$this->student_apply_model->insert_apply_info_one($userid,$courseid);
        ajaxReturn ( '/master/agencyport/student_apply/apply_page?applyid='.$aid.'&userid='.$userid.'&courseid=' . $courseid , '', 1 );
    }
    /**
     * [apply_page 跳转页面]
     * @return [type] [description]
     */
    function apply_page(){
        $courseid = intval ( trim ( $this->input->get ( 'courseid' ) ) );
        $userid=intval(trim($this->input->get('userid')));
        $applyid = trim ( $this->input->get ( 'applyid' ) );
        if (! empty ( $applyid )) {
            $where_apply_info = "id = {$applyid} AND userid = $userid";
            $apply_info = $this->apply_model->get_apply_info ( $where_apply_info );
            $this->issubmit = $apply_info ['issubmit'];
            $this->isstart = $apply_info ['isstart'];
            $cid = $apply_info ['courseid'];
            if ($userid) {
                // 生成表单html
                /// 获取表单数据
                $form_data = $this->get_form_data ( $courseid );

                $secret_key = 'class_id=' . $cid . "," . "&uid=" . $userid . "&form_type=application&applyid=" . $apply_info ['id'];
                $pay_key_secret = cucas_base64_encode ( $secret_key );
                $coursenames = $this->course_model->get_one ( 'id = ' . $cid );

                $this->get_form_html ( $form_data );
                $this->_view ( 'fill_form', array (
                    'html_left' => $this->html_left,
                    'html_form' => $this->html_form,
                    'userid'=>$userid,
                    'cid' => $cid,
                    'puri'=>'cn',
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
     * 保存数据
     */
    function save_apply($c_id, $ischeck = null) {
        if (IS_AJAX) {
            // 申请表的id
            $cid = ( int ) cucas_base64_decode ( $c_id );

            $ischeck = trim ( $ischeck );
            if ($cid) {
                $data = $this->input->post ();
                if(!empty($data['validfrom'])){
                    $dataSz['validfrom'] = strtotime($data['validfrom']);
                }
                if(!empty($data['validuntil'])){
                    $dataSz['validuntil'] = strtotime($data['validuntil']);
                }

                if(!empty($data['birthdate_year'])){
                    $dataSz['birthday'] = strtotime($data['birthdate_year']);
                }

                if(!empty($data['gender'])){
                    if($data['gender']=='Male'){
                        $sex=1;
                    }elseif($data['gender']=='Female'){
                        $sex=2;
                    }else{
                        $sex='';
                    }

                    $dataSz['sex'] = $sex;
                }
                // var_dump($dataSz);exit;
                if(!empty($data['lastname'])){
                    $dataSz['lastname'] = $data['lastname'];
                }
                if(!empty($data['firstname'])){
                    $dataSz['firstname'] = $data['firstname'];
                }
                if(!empty($data['passportno'])){
                    $dataSz['passport'] = $data['passportno'];
                }

                if(!empty( $data['lastname']) ){
                    $dataSz['enname'] = $data['lastname'];
                }


                if(!empty( $data['chinesename']) ){
                    $dataSz['chname'] = $data['chinesename'];
                }

                if(!empty( $data['religion']) ){
                    $dataSz['religion'] = $data['religion'];
                }
                if(!empty( $data['Cmobile']) ){
                    $dataSz['mobile'] = $data['Cmobile'];
                }
                if(!empty( $data['Cphone']) ){
                    $dataSz['tel'] = $data['Cphone'];
                }
                if(!empty($dataSz)){
                    $this->db->update ( 'student_info', $dataSz, 'id = ' . $data['userid'] );
                }
                isset ( $data ['isDeclaration'] ) ? $data ['isDeclaration'] : $data ['isDeclaration'] = '';
                // $this->load->library ( 'cimongo/cimongo' );

                // $is = $this->model->get_apply_info ( $cid, $_SESSION ['student'] ['userinfo'] ['id'] );
                $where_apply_info = "id = {$cid} AND userid = {$data['userid']}";
                $is = $this->apply_model->get_apply_info ( $where_apply_info );

                if (! empty ( $is )) {
                    $where_del = "applyid = {$cid} AND userid = {$data['userid']}";
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
                                'userid' => $data['userid'],
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

                        ajaxReturn ( $url, '更新成功', 1 );
                    } else {
                        ajaxReturn ( '', '更新成功', 1 );
                    }
                }
            }
        }
        ajaxReturn ( '', '', 0 );
    }

    /**
     * 获取中介公司的账号id
     */
    function get_agencyid($userid){
        $data=$this->db->select ( 'id' )->get_where ( 'agency_info', 'userid = ' . $userid )->row_array ();
        return $data['id'];
    }
    //添加申请
    function add_open_apply(){
        $userid=$this->input->get('userid');
        if(!empty($userid)){
            $userinfo=$this->db->get_where('student_info','id = '.$userid)->row_array();
            $userinfo['agency_id']=$this->agencyid;
            $_SESSION['student']['userinfo']=$userinfo;
            ajaxReturn('','',1);
        }
        ajaxReturn('','',0);
    }

}
