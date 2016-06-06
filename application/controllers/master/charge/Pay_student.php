<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *
 */
class Pay_student extends Master_Basic {
    protected $_size = 3;
    protected $_count = 0;
    protected $_countpage = 0;
    protected $data_student = array ();
    /**
     * 基础类构造函数
     */
    function __construct() {
        parent::__construct ();
        $this->view = 'master/student/';
        $this->load->model ( $this->view . 'student_model' );
        $this->load->model ( 'student/fee_model' );
        $this->load->model ( 'master/enrollment/acc_in_model' );
        // 求学生的数量
        $this->data_student = $this->count_tuition_notice ();
    }

    /**
     * 后台主页
     */
    function index() {
        $label_id = $this->input->get ( 'label_id' );
        $label_id = ! empty ( $label_id ) ? $label_id : '1';
        $major = $this->input->get ( 'major' );
        $squad = $this->input->get ( 'squad' );
        if (empty ( $squad )) {
            $squad = 0;
        }
        $squad_info = 0;
        if (empty ( $major )) {

            $major = 0;
        }

        if ($this->input->is_ajax_request () === true) {
            // 设置查询字段
            $fields = $this->_set_lists_field ();
            // 翻页
            $limit = "";
            $offset = "";
            if (isset ( $_GET ['iDisplayStart'] ) && $_GET ['iDisplayLength'] != '-1') {
                $offset = intval ( $_GET ['iDisplayStart'] );
                $limit = intval ( $_GET ['iDisplayLength'] );
            }
            $label_id = $this->input->get ( 'label_id' );
            $label_id = ! empty ( $label_id ) ? $label_id : '1';
            // 状态筛选
            if (! empty ( $label_id )) {
                $where = 'state=' . $label_id;
            }
            // 专业班级筛选
            $majorid = $this->input->get ( 'major' );
            $squadid = $this->input->get ( 'squad' );
            if (! empty ( $majorid )) {
                $where .= ' AND majorid = ' . $majorid;
            }
            if (! empty ( $squadid )) {
                $where .= ' AND squadid = ' . $squadid;
            }
            $sSearch_0 = $this->input->get ( 'sSearch_0' );
            if (! empty ( $sSearch_0 )) {
                $where .= "
				AND 
				id LIKE '%{$sSearch_0}%'
				
				";
            }
            $sSearch_1 = $this->input->get ( 'sSearch_1' );
            if (! empty ( $sSearch_1 )) {
                $where .= "
				AND (
				studentid LIKE '%{$sSearch_1}%'
				)
				";
            }
            $sSearch_2 = $this->input->get ( 'sSearch_2' );
            $sSearch_2_arr = explode ( '-zjj-', $sSearch_2 );
            if (! empty ( $sSearch_2_arr [1] )) {
                $where .= "
				AND
				nationality = '{$sSearch_2_arr[1]}'
				";
            }
            $sex = '';
            if ($sSearch_2_arr [0] == '女') {
                $sex = '2';
            }
            if ($sSearch_2_arr [0] == '男') {
                $sex = '1';
            }
            if (! empty ( $sSearch_2_arr [0] ) && ! empty ( $sex )) {
                $where .= "
				AND (
				sex LIKE '%{$sex}%'
				OR
				name LIKE '%{$sSearch_2_arr[0]}%'
				)
				";
            } else {
                $where .= "
				AND (
				id LIKE '%{$sSearch_2_arr[0]}%'
				OR
				name LIKE '%{$sSearch_2_arr[0]}%'
				OR
				firstname LIKE '%{$sSearch_2_arr[0]}%'
				OR
				lastname LIKE '%{$sSearch_2_arr[0]}%'
				OR
				mobile LIKE '%{$sSearch_2_arr[0]}%'
				OR
				tel LIKE '%{$sSearch_2_arr[0]}%'
				OR
				birthday LIKE '%{$sSearch_2_arr[0]}%'
				OR
				enname LIKE '%{$sSearch_2_arr[0]}%'
				)
				";
            }
            $sSearch_3 = $this->input->get ( 'sSearch_3' );
            $majorid = $this->student_model->get_major_ids ( $sSearch_3 );
            if (! empty ( $sSearch_3 ) && ! empty ( $majorid )) {
                $where .= "
				AND 
					majorid in ({$majorid})
				
				";
            } elseif (! empty ( $sSearch_3 )) {
                $where .= "
				AND (
				enroltime LIKE '%{$sSearch_3}%'
				OR
				leavetime LIKE '%{$sSearch_3}%'
				)
				";
            }
            $sSearch_4 = $this->input->get ( 'sSearch_4' );
            if (! empty ( $sSearch_4 )) {
                $where .= "
				AND (
				passport LIKE '%{$sSearch_4}%'
				)
				";
            }
            $sSearch_5 = $this->input->get ( 'sSearch_5' );
            if (! empty ( $sSearch_5 )) {
                $where .= "
				AND (
				passporttime LIKE '%{$sSearch_5}%'
				)
				";
            }
            $output ['sEcho'] = intval ( $_GET ['sEcho'] );
            $output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->student_model->count ( $where );
            $output ['aaData'] = $this->student_model->get ( $where, $limit, $offset, 'id DESC' );
            foreach ( $output ['aaData'] as $item ) {
                $item->checkbox = '<input type="checkbox" name="sid[]" value="' . $item->id . '">';
                $item->nationality = $this->student_model->get_nationality ( $item->nationality );
                $majorname = $this->student_model->get_majorname ( $item->majorid );
                $item->basic_info = "中文名字:" . (! empty ( $item->name ) ? $item->name : '&nbsp;&nbsp;--') . "<br />英文名字:" . $item->enname . "<br />性别:" . ($item->sex == 1 ? '男' : '女') . "<br />座机/手机:" . (! empty ( $item->tel ) ? $item->tel : '&nbsp;&nbsp;--') . "&nbsp;/&nbsp;" . (! empty ( $item->mobile ) ? $item->mobile : '&nbsp;&nbsp;--') . "<br />出生日期:" . $item->birthday . "<br />国籍:" . $item->nationality;
                $item->major_info = "所在专业:" . (! empty ( $majorname ) ? $majorname : '&nbsp;&nbsp;--') . "<br />所在班级:" . $this->student_model->get_squadname ( $item->squadid ) . "<br />入学时间:" . $item->enroltime . "<br />离校时间:" . $item->leavetime;
                ;
                $user_info=$this->db->get_where('student','id = '.$item->id)->row();

                $item->state = $this->get_state ( $item->state );
                $item->operation = '<div class="btn-group"><a class="btn btn-xs btn-info" href="/master/charge/pay/type_pay_page?userid=' . $user_info->userid. '">缴费</a>
							';


            }
            exit ( json_encode ( $output ) );
        }

        if ($major != 0) {
            $squad_info = $this->student_model->get_squad_info ( $major );
        }

        $major_info = $this->student_model->get_major_info ('id>0',0,0,'orderby desc');
		
        // 获取学历
        $major_info = $this->_get_major_by_degree($major_info);

        // 国籍
        $nationality = CF ( 'public', '', CACHE_PATH );
        $this->load->view ( 'master/charge/pay_student_index', array (
            'label_id' => $label_id,
            'major_info' => $major_info,
            'majorid' => $major,
            'squad_info' => $squad_info,
            'squadid' => $squad,
            'nationality' => $nationality
        ) );
    }

    /**
     * 分配房间页面
     */
    function room(){
        $userid = $this->input->get('userid');
        $campus_info=$this->acc_in_model->get_campus_info();
        $this->_view ( 'acc_in_adjust_index' ,array(
            'campus_info'=>$campus_info,
            'userid'=>$userid
        ));
    }

    /**
     * 分配房间页面
     */
    function fenpei(){
        $bulidingid=$this->input->get('bulidingid');
        $campusid=$this->input->get('campusid');
        $floor=$this->input->get('floor');
        $roomid=$this->input->get('roomid');
        $userid=$this->input->get('userid');
        $html = $this->load->view('master/student/user_room',array(
            'bulidingid'=>$bulidingid,
            'campusid'=>$campusid,
            'floor'=>$floor,
            'roomid'=>$roomid,
            'userid'=>$userid
        ),true);
        ajaxReturn($html,'',1);
    }

    /**
     * 提交的房间
     */
    function sub_room(){
        $data=$this->input->post();
        if(!empty($data['accstarttime'])&&!empty($data['endtime'])){
            $accstarttime=strtotime($data['accstarttime']);
            $endtime=strtotime($data['endtime']);
            if($accstarttime>$endtime){
                ajaxReturn('','',0);
            }
            $xx=$endtime-$accstarttime;

            $xx=$xx/3600/24;
            $data['accstarttime']=$accstarttime;
            $data['accendtime']=$xx;
        }else{
            ajaxReturn('','',0);
        }
        //获取房间的价格
        $room_info=$this->db->get_where('school_accommodation_prices','id = '.$data['roomid'])->row_array();
        if(!empty($room_info['prices'])){
            $data['registeration_fee']=$room_info['prices'];
        }else{
            $data['registeration_fee']=0;
        }
        //计算剩余金额
        $nowxx=time()-$accstarttime;
        if($nowxx>0){
            $nowxx=$nowxx/3600/24;
            $data['residual_amount']=$nowxx;
        }else{
            $data['residual_amount']=0;
        }
        //插入所有收支表
        $budget_data['userid']=$data['userid'];
        $budget_data['budget_type']=1;
        $budget_data['type']=4;
        $budget_data['term']=1;
        $budget_data['payable']=$data['accendtime']*$data['registeration_fee'];
        $budget_data['paid_in']=$data['accendtime']*$data['registeration_fee'];
        $budget_data['paystate']=1;
        $budget_data['createtime']=time();
        $budget_data['adminid']=$_SESSION ['master_user_info']->id;
        $this->db->insert('budget',$budget_data);
        $budgetid=$this->db->insert_id();
        $data['budgetid']=$budgetid;
        $data['applytime']=time();
        $data['paystate']=1;
        $data['paytime']=time();
        $data['acc_state']=6;
        $data['adminid']=$_SESSION ['master_user_info']->id;
        unset($data['endtime']);
        $this->db->insert('accommodation_info',$data);
        $this->db->update('student',array('acc_state'=>1),'userid = '.$data['userid']);
        ajaxReturn('','',1);
    }
    /**
     * 打印offer
     */
    function moban_offer(){
        $parentid=$this->input->get('printid');
        $info=$this->db->get_where('print_template','parentid = '.$parentid)->result_array();
        $studentid=$this->input->get('studentid');
        $html = $this->load->view('master/student/print_offer_select',array(
            'info'=>$info,
            'studentid'=>$studentid
        ),true);
        ajaxReturn($html,'',1);
    }


    /**
     * 打印在校证明模板
     */
    function moban() {
        $parentid = $this->input->get ( 'parentid' );
        $studentid = $this->input->get ( 'studentid' );
        $info = $this->db->get_where ( 'print_template', 'parentid = ' . $parentid )->result_array ();
        $html = $this->_view ( 'moban_select', array (
            'info' => $info,
            'studentid' => $studentid
        ), true );
        ajaxReturn ( $html, '', 1 );
    }
    /**
     * 获取学生成绩
     */
    function get_student_score() {
        // 获取的学期及默认的学期
        // $nowterm=!empty($this->input->get('term'))?$this->input->get('term'):1;
        $nowterm = $this->input->get ( 'term' );

        if (empty ( $nowterm )) {
            $nowterm = 1;
        }
        $userid = $this->input->get ( 'id' );
        // 总学期数
        $term = $this->student_model->get_term ( $userid );

        // 考试类型
        $scoretype = CF ( 'scoretype', '', CONFIG_PATH );
        $scoretype = $this->db->get_where ( 'set_score', 'state = 1' )->result_array ();
        // 获取的考试类型及默认的考试类型
        foreach ( $scoretype as $k => $v ) {
            $morenscoretype = $v ['id'];
            break;
        }
        // 获取考试类型
        // $scoretypes = ! empty ( $this->input->get ( 'scoretype' ) ) ? $this->input->get ( 'scoretype' ) : $morenscoretype;
        $scoretypes = $this->input->get ( 'scoretype' );
        if (empty ( $scoretypes )) {
            $scoretypes = $morenscoretype;
        }
        $achievement = $this->student_model->get_achievement ( $userid, $nowterm, $scoretypes, 'all' );
        $achievements = $this->student_model->get_achievement ( $userid, $nowterm, $scoretypes, 'all' );
        $count = count ( $achievements );

        // 计算平均分
        $avgscore = $this->student_model->avg_score ( $achievements );
        $this->_view ( 'student_score', array (
            'achievement' => $achievement,
            'term' => $term,
            'nowterm' => $nowterm,
            'count' => $count,
            'scoretype' => $scoretype,
            'scoretypes' => $scoretypes,
            'avgscore' => $avgscore,
            'id' => $userid
        ) );
    }
    /**
     * 学生考勤
     */
    function get_student_checking() {
        // $nowterm=!empty($this->input->get('term'))?$this->input->get('term'):1;
        $nowterm = $this->input->get ( 'term' );
        if (empty ( $nowterm )) {
            $nowterm = 1;
        }
        $userid = $this->input->get ( 'id' );
        $term = $this->student_model->get_term ( $userid );

        $attendance = $this->student_model->get_attendance ( $userid, $nowterm, 'all' );
        $attendances = $this->student_model->get_attendance ( $userid, $nowterm, 'all' );
        $count = count ( $attendances );
        $this->_view ( 'student_checking', array (
            'attendance' => $attendance,
            'term' => $term,
            'nowterm' => $nowterm,
            'count' => $count,
            'id' => $userid
        ) );
    }
    /**
     * 学生考勤更多信息
     */
    function checking_more() {
        // $nowterm=!empty($this->input->get('term'))?$this->input->get('term'):1;
        $nowterm = $this->input->get ( 'term' );
        if (empty ( $nowterm )) {
            $nowterm = 1;
        }
        $userid = $this->input->get ( 'id' );
        $attendance = $this->student_model->get_attendance ( $userid, $nowterm, 'all' );
        for($i = 0; $i <= 5; $i ++) {
            unset ( $attendance [$i] );
        }
        if (! empty ( $attendance )) {
            ajaxReturn ( $attendance, '', 1 );
        }
        ajaxReturn ( '', '该学生的考勤为空', 0 );
    }
    /**
     * [del 删除学生]
     *
     * @return [type] [description]
     */
    function del() {
        $id = intval ( $this->input->get ( 'id' ) );
        if (! empty ( $id )) {
            $where = 'id = ' . $id;
            $this->student_model->delete ( $where );
            ajaxReturn ( '', '', 1 );
        }
        ajaxReturn ( '', '', 0 );
    }
    /**
     * 编辑在学学生
     */
    function edit() {
        $id = intval ( $this->input->get ( 'id' ) );

        if ($id) {
            $where = "id={$id}";
            $info = $this->student_model->get_one ( $where );
            $info_visa = $this->student_model->get_visa_one ( $id );
            $info_insurance = $this->student_model->get_insurance_one ( $info->userid );
            if (empty ( $info )) {
                ajaxReturn ( '', '该学生不存在', 0 );
            }
            $info->squadid = $this->student_model->get_squad_name ( $info->squadid );
        }
        $nationality = CF ( 'public', '', CACHE_PATH );
        // 状态
        $info->state = $this->student_model->get_student_state_str ( $info->state );
        $info->nationality = $this->student_model->get_nationality ( $info->nationality );
        $info->degreeid = $this->student_model->get_degree_name_one ( $info->degreeid );
        $degree = $this->student_model->get_degree_name ();

        // 查看奖学金授予的情况
        $jiangxuejin = $this->student_model->get_jiangxuejin_info ( $info->userid );
        // 查看参加活动情况
        $activity_info = $this->student_model->get_activity_info ( $info->userid );
        $this->_view ( 'student_edit', array (
            'info' => $info,
            'info_visa' => $info_visa,
            'id' => $id,
            'state' => $info->state,
            'nationality' => $nationality ['global_country_cn'],
            'select_nationlity' => $info->nationality,
            'degree' => $degree,
            'select_degree' => $info->degreeid,
            'info_insurance' => $info_insurance,
            'select_language_level' => $info->language_level,
            'jiangxuejin' => $jiangxuejin,
            'activity_info' => $activity_info
        ) );
    }
    function select_language_level($v) {
        switch ($v) {
            case 0 :
                return 'empty';
                break;
            case 1 :
                return '零水平';
                break;
            case 2 :
                return '初级水平';
                break;
            case 3 :
                return '中级水平';
                break;
            case 4 :
                return '高级水平';
                break;
        }
    }
    /**
     * 编辑字段
     */
    function edit_fields() {
        $data = $this->input->post ();
        // 获取userid
        $userid = $this->db->where ( 'id', $data ['pk'] )->get ( 'student' )->row_array ();
        $arr = explode ( '-', $data ['name'] );
        if (! empty ( $data )) {
            if ($arr [0] == 'visa') {
                $this->student_model->update_visa_fields ( $data, $arr [1] );
            } elseif ($arr [0] == 'insurance') {
                $this->student_model->update_insurance_fields ( $data, $arr [1] );
            } else {
                if ($data ['name'] == 'speciality') {
                    $this->db->update ( 'student_info', array (
                        'speciality' => $data ['value']
                    ), 'id =' . $userid ['userid'] );
                }
                $this->student_model->update_fields ( $data );
            }
            ajaxReturn ( '', '更新成功', 1 );
        }
        ajaxReturn ( '', '更新失败', 0 );
    }
    // 更新
    function update() {
        $id = intval ( $this->input->post ( 'id' ) );
        if ($id) {
            $data = $this->input->post ();
            $data ['enroltime'] = strtotime ( $data ['enroltime'] );
            $data ['leavetime'] = strtotime ( $data ['leavetime'] );
            $data ['visaendtime'] = strtotime ( $data ['visaendtime'] );
            // 保存基本信息
            $this->student_model->save ( $id, $data );
            ajaxReturn ( '', '更新成功', 1 );
        }
        ajaxReturn ( '', '更新失败', 0 );
    }
    function dayin() {
        $this->load->library ( 'sdyinc_print' );
        $print_data = $this->input->post ();
        if (! empty ( $print_data )) {
            $this->sdyinc_print->do_pdf_prints ( 80, $print_data );
            return 1;
        }
        $id = $this->input->get ( 'id' );
        $data = $this->student_model->get_student_on_info ( $id );
        // var_dump($id );exit;
        $data ['nationality'] = $this->student_model->get_nationality ( $data ['nationality'] );
        $data ['sex'] = ! empty ( $data ['sex'] ) && $data ['sex'] == 1 ? '男' : '女';
        $data ['marital'] = ! empty ( $data ['marital'] ) && $data ['marital'] == 1 ? '是' : '否';
        if (! empty ( $data ['majorid'] )) {
            $major_info = $this->student_model->get_major_info_one ( $data ['majorid'] );

            $dayin_opentime = strtotime ( $major_info ['opentime'] );
            $data ['opentime_year'] = date ( 'Y', $dayin_opentime );
            $data ['opentime_month'] = date ( 'm', $dayin_opentime );
            $data ['opentime_day'] = date ( 'd', $dayin_opentime );
        }
        if (! empty ( $data ['majorid'] )) {
            $major_info = $this->student_model->get_major_info_one ( $data ['majorid'] );
            $dayin_endtime = strtotime ( $major_info ['endtime'] );
            $data ['endtime_year'] = date ( 'Y', $dayin_endtime );
            $data ['endtime_month'] = date ( 'm', $dayin_endtime );
            $data ['endtime_day'] = date ( 'd', $dayin_endtime );
        }
        if (! empty ( $data ['majorid'] )) {
            $data ['majorid'] = $this->student_model->get_majorname ( $data ['majorid'] );
        }
        if (! empty ( $data ['birthday'] )) {
            $dayin_birthday = strtotime ( $data ['birthday'] );
            $data ['birthday_year'] = date ( 'Y', $dayin_birthday );
            $data ['birthday_month'] = date ( 'm', $dayin_birthday );
            $data ['birthday_day'] = date ( 'd', $dayin_birthday );
        }
        if (! empty ( $data ['degreeid'] )) {
            $data ['last_degreeid'] = $this->student_model->get_degree_name_one ( $data ['degreeid'] );
        }

        // var_dump($dayin_birthday);exit;
        $data ['school'] = "北京信息职业技术学院";
        $url = '/master/student/student/dayin';
        if ($id) {
            $this->sdyinc_print->do_print ( 80, $data, false, $url );
        }
        return 1;
    }
    // 打印在校证明
    function print_in_school() {
        $this->load->library ( 'sdyinc_print' );
        $print_data = $this->input->post ();
        if (! empty ( $print_data )) {
            $this->sdyinc_print->do_pdf_prints ( 85, $print_data );
            return 1;
        }
        $id = $this->input->get ( 'id' );
        $data = $this->student_model->get_student_on_infos ( $id );
        // var_dump($id );exit;
        $data ['nationality'] = $this->student_model->get_nationality ( $data ['nationality'] );
        $data ['sex'] = ! empty ( $data ['sex'] ) && $data ['sex'] == 1 ? '男' : '女';
        if (! empty ( $data ['majorid'] )) {
            $major_info = $this->student_model->get_major_info_one ( $data ['majorid'] );

            $dayin_opentime = $major_info ['opentime'];
            // $data['enroltime']=date('Y-m-d',$major_info['opentime']);
            $time = strtotime ( $data ['enroltime'] );
            $data ['incnyear'] = date ( 'Y', $time );
            $data ['incnmonth'] = date ( 'm', $time );
        }
        if (! empty ( $data ['leavetime'] )) {
            $leavetime = strtotime ( $data ['leavetime'] );
            $data ['inyear'] = date ( 'Y', $leavetime );
            $data ['inmonth'] = date ( 'm', $leavetime );
        }

        if (! empty ( $data ['majorid'] )) {
            $major_info = $this->student_model->get_major_info_one ( $data ['majorid'] );
            $ltime = strtotime ( $data ['leavetime'] );
            $dayin_endtime = strtotime ( $major_info ['endtime'] );
            $data ['endtime_year'] = date ( 'Y', $ltime );
            $data ['endtime_month'] = date ( 'm', $ltime );
            $data ['endtime_day'] = date ( 'd', $ltime );
        }
        $data ['en_passport'] = ! empty ( $data ['passport'] ) ? $data ['passport'] : '';
        // var_dump($dayin_birthday);exit;
        $data ['school'] = "北京信息职业技术学院";
        $url = '/master/student/student/dayin';
        if ($id) {
            $this->sdyinc_print->do_print ( 85, $data, false, $url );
        }
        return 1;
    }
    // 打印离校证明
    function print_leave_school() {
        $this->load->library ( 'sdyinc_print' );
        $print_data = $this->input->post ();
        if (! empty ( $print_data )) {
            $this->sdyinc_print->do_pdf_prints ( 86, $print_data );
            return 1;
        }
        $id = $this->input->get ( 'id' );
        $data = $this->student_model->get_student_on_infos ( $id );
        // var_dump($id );exit;
        $data ['nationality'] = $this->student_model->get_nationality ( $data ['nationality'] );
        $data ['sex'] = ! empty ( $data ['sex'] ) && $data ['sex'] == 1 ? '男' : '女';
        if (! empty ( $data ['majorid'] )) {
            $major_info = $this->student_model->get_major_info_one ( $data ['majorid'] );

            $dayin_opentime = strtotime ( $data ['enroltime'] );
            $data ['leaveyear'] = date ( 'Y', $dayin_opentime );
            $data ['leavemonth'] = date ( 'm', $dayin_opentime );
        }
        if (! empty ( $data ['leavetime'] )) {
            $leavetime = strtotime ( $data ['leavetime'] );
            $data ['leavesyear'] = date ( 'Y', $leavetime );
            $data ['leavesmonth'] = date ( 'm', $leavetime );
        }

        $data ['en_passport'] = ! empty ( $data ['passport'] ) ? $data ['passport'] : '';
        // var_dump($dayin_birthday);exit;
        $data ['school'] = "北京信息职业技术学院";
        $url = '/master/student/student/dayin';
        if ($id) {
            $this->sdyinc_print->do_print ( 86, $data, false, $url );
        }
        return 1;
    }
    // 打印结业证
    function print_graduate() {
        $this->load->library ( 'sdyinc_print' );
        $print_data = $this->input->post ();
        if (! empty ( $print_data )) {
            $this->sdyinc_print->do_pdf_prints ( 84, $print_data );
            return 1;
        }
        $id = $this->input->get ( 'id' );
        $data = $this->student_model->get_student_on_infos ( $id );
        if (! empty ( $data )) {
            $data ['nationality'] = $this->student_model->get_nationality ( $data ['nationality'] );
            if (! empty ( $data ['majorid'] )) {
                $major_info = $this->student_model->get_major_info_one ( $data ['majorid'] );

                $dayin_opentime = strtotime ( $major_info ['opentime'] );
                // $data['enroltime']=date('Y-m-d',$dayin_opentime);
            }
            if (! empty ( $data ['majorid'] )) {
                $data ['majorid'] = $this->student_model->get_majorname ( $data ['majorid'] );
            }
            $y = date ( 'Y', time () );
            $data ['certificate_no'] = $y . '-' . $data ['id'];
            $data ['en_passport'] = ! empty ( $data ['passport'] ) ? $data ['passport'] : '';
            // var_dump($dayin_birthday);exit;
            $data ['school'] = "北京信息职业技术学院";
            $url = '/master/student/student/dayin';
            if ($id) {
                $this->sdyinc_print->do_print ( 84, $data, false, $url );
            }
            return 1;
        } else {
            $this->_view ( 'print_graduate_1' );
        }
    }
    // 打印中文通知书
    function print_notification() {
        $this->load->library ( 'sdyinc_print' );
        $print_data = $this->input->post ();
        if (! empty ( $print_data )) {
            $this->sdyinc_print->do_pdf_prints ( 81, $print_data );
            return 1;
        }
        $id = $this->input->get ( 'id' );
        $data = $this->student_model->get_student_on_infos ( $id );
        // var_dump($id );exit;
        $data ['nationality'] = $this->student_model->get_nationality ( $data ['nationality'] );
        if (! empty ( $data ['majorid'] )) {
            $major_info = $this->student_model->get_major_info_one ( $data ['majorid'] );

            $dayin_opentime = strtotime ( $data ['enroltime'] );
            // $data['enroltime']=date('Y-m-d',$dayin_opentime);
            $data ['s_year'] = date ( 'Y', $dayin_opentime );
            $data ['s_month'] = date ( 'm', $dayin_opentime );
            $data ['s_day'] = date ( 'd', $dayin_opentime );
        }
        if (! empty ( $data ['majorid'] )) {
            $data ['majorid'] = $this->student_model->get_majorname ( $data ['majorid'] );
        }
        $data ['en_passport'] = ! empty ( $data ['passport'] ) ? $data ['passport'] : '';
        // var_dump($dayin_birthday);exit;
        $data ['school'] = "北京信息职业技术学院";
        $url = '/master/student/student/dayin';
        if ($id) {
            $this->sdyinc_print->do_print ( 81, $data, false, $url );
        }
        return 1;
    }
    // 打印英文通知书
    function print_notifications() {
        $this->load->library ( 'sdyinc_print' );
        $print_data = $this->input->post ();
        if (! empty ( $print_data )) {
            $this->sdyinc_print->do_pdf_prints ( 89, $print_data );
            return 1;
        }
        $id = $this->input->get ( 'id' );
        $data = $this->student_model->get_student_on_infos ( $id );
        // var_dump($id );exit;
        $data ['nationality'] = $this->student_model->get_nationality ( $data ['nationality'] );
        $data ['enroltimes'] = ! empty ( $data ['leavetime'] ) ? $data ['leavetime'] : '';
        if (! empty ( $data ['majorid'] )) {
            $data ['majorid'] = $this->student_model->get_majorname ( $data ['majorid'] );
        }
        $data ['en_passport'] = ! empty ( $data ['passport'] ) ? $data ['passport'] : '';
        $url = '/master/student/student/dayin';
        if ($id) {
            $this->sdyinc_print->do_print ( 89, $data, false, $url );
        }
        return 1;
    }
    // 打印住宿登记
    function print_putup() {
        $this->load->library ( 'sdyinc_print' );
        $print_data = $this->input->post ();
        if (! empty ( $print_data )) {
            $this->sdyinc_print->do_pdf_prints ( 88, $print_data );
            return 1;
        }
        $id = $this->input->get ( 'id' );
        $data = $this->student_model->get_student_on_infos ( $id );
        // var_dump($id );exit;
        $data ['nationality'] = $this->student_model->get_nationality ( $data ['nationality'] );
        $data ['sex'] = ! empty ( $data ['sex'] ) && $data ['sex'] == 1 ? '男' : '女';
        // var_dump($dayin_birthday);exit;
        $data ['school'] = "北京信息职业技术学院";
        $url = '/master/student/student/dayin';
        if ($id) {
            $this->sdyinc_print->do_print ( 88, $data, false, $url );
        }
        return 1;
    }
    /**
     * 设置列表字段
     */
    private function _set_lists_field() {
        return array (
            'id',
            'studentid',
            'name',
            'enname',
            'firstname',
            'tel',
            'mobile',
            'lastname',
            'passport',
            'passporttime',
            'birthday',
            'nationality',
            'enroltime',
            'visaendtime',
            'leavetime',
            'state',
            'sex',
            'majorid',
            'squadid',
            'state',
            'userid'
        );
    }

    /**
     * 获取班级信息
     */
    function get_squad($majorid) {
        if ($data = $this->student_model->get_squad_info ( $majorid )) {
            if ($this->input->is_ajax_request () === true) {
                ajaxReturn ( $data, '', 1 );
            } else {
                return $data;
            }
        }

        ajaxReturn ( '', '该专业没有班级', 0 );
    }
    /**
     * 分班
     */
    function addqm() {
        $data = $this->input->post ();

        $s = $this->student_model->add_qm ( $data );
        if ($s) {
            ajaxReturn ( '', '', 1 );
        } else {
            ajaxReturn ( '', '', 1 );
        }
    }

    /**
     * 获取状态
     */
    function get_state($state = null) {
        if ($state != null) {
            $stateArray = array (
                0 => '',
                1 => '<span class="label label-success">在校</span>',
                2 => '<span class="label label-success">转学</span>',
                3 => '<span class="label label-success">正常离开</span>',
                4 => '<span class="label label-success">非正常离开</span>',
                5 => '<span class="label label-success">休学</span>',
                6 => '<span class="label label-success">申请</span>',
                7 => '<span class="label label-success">已报到</span>',
                8 => '<span class="label label-success">未报到</span>',
                9 => '<span class="label label-success">留级</span>',
            );
            return $stateArray [$state];
        } else {
            return false;
        }
    }
    /**
     * 获取学生状态
     */
    function get_student_state($state = null) {
        if ($state != null) {
            $stateArray = array (
                1 => '<span class="label label-success">正常</span>',
                2 => '<span class="label label-danger">异常</span>'
            );
            return $stateArray [$state];
        } else {
            return '<span class="label label-success">正常</span>';
        }
    }
    /**
     * 弹框
     * 发送交费 通知
     */
    function tuition_notice() {
        $this->_count = count ( $this->data_student );
        $this->_countpage = ceil ( $this->_count / $this->_size );

        $html = $this->_view ( 'tuition_notice', array (
            'count' => $this->_count,
            'countpage' => $this->_countpage,
            'size' => $this->_size
        ), true );
        ajaxReturn ( $html, '', 1 );
    }

    /**
     * 执行 发送邮件
     */
    function do_tuition_notice() {
        $count = ( int ) $this->input->post ( 'count' );
        $countpage = ( int ) $this->input->post ( 'countpage' );
        $page = ( int ) $this->input->post ( 'page' );
        var_dump ( $this->data_student );
    }

    /**
     * 基数按数量
     */
    function count_tuition_notice() {
        $studentAll = array (); // 所有的在学的学生
        $studentzl = array (); // 整理好的 学生 用id 指向内容
        // 第一步 查找 所有的 在学的学生
        $data = array (); // 组织 学费表的数据
        $studentids = array (); // 学生的id
        $majorfees = array (); // 专业的学费
        $major_term_tuition = array (); // array[专业id][学期][学费]
        $majorfees = $this->_get_major_fees ();

        // 学生
        $studentAll = $this->_get_student ();
        // 学期与班级的关系
        $classxq = $this->_get_class_newterm ();
        // 获取专业每学期的学费
        // $major_term_tuition=$this->_get_major_term_tuition();
        if (! empty ( $studentAll )) {
            foreach ( $studentAll as $k => $v ) {
                $studentzl [$v ['id']] = $v;
                $studentids [] = $v ['id'];
                // 查找班级 如果没有班级 怎认为 该学生是 第一学期 组合数据
                if (empty ( $v ['squadid'] )) {
                    // 没有分班 学期为第一学期
                    $data [$v ['id']] ['nowterm'] = 1;
                } else {
                    // 分班了 就能找到指定的学期
                    $data [$v ['id']] ['nowterm'] = ! empty ( $classxq [$v ['squadid']] ) ? $classxq [$v ['squadid']] : '';
                }
                // 先查所在专业 如果 所在专业为空 再查 报名专业
                if (empty ( $v ['majorid'] )) {
                    $majorxuefei = $v ['major'];
                } else {
                    $majorxuefei = $v ['majorid'];
                }

                // 学费
                $data [$v ['id']] ['tuition'] = ! empty ( $majorfees [$majorxuefei] ) ? $majorfees [$majorxuefei] : '';
                // 其他信息
                $data [$v ['id']] ['userid'] = $v ['id'];
                $data [$v ['id']] ['name'] = ! empty ( $v ['enname'] ) ? $v ['enname'] : '';
                $data [$v ['id']] ['email'] = ! empty ( $v ['email'] ) ? $v ['email'] : '';
                $data [$v ['id']] ['nationality'] = ! empty ( $v ['nationality'] ) ? $v ['nationality'] : '';
                $data [$v ['id']] ['mobile'] = ! empty ( $v ['mobile'] ) ? $v ['mobile'] : '';
                $data [$v ['id']] ['tel'] = ! empty ( $v ['tel'] ) ? $v ['tel'] : '';
                $data [$v ['id']] ['passport'] = ! empty ( $v ['passport'] ) ? $v ['passport'] : '';
                $data [$v ['id']] ['paystate'] = 0;
                $data [$v ['id']] ['paytime'] = 0;
                $data [$v ['id']] ['paytype'] = 0;
                $data [$v ['id']] ['isproof'] = 0;
                $data [$v ['id']] ['remark'] = '';
            }
            return $data;
        } else {
            return array ();
        }
    }

    /**
     * 获取所有的学生
     */
    function _get_student() {
        $studentAll = $this->db->select ( '*' )->get_where ( 'student', 'id > 0 AND state = 1' )->result_array ();
        if (! empty ( $studentAll )) {
            return $studentAll;
        } else {
            return array ();
        }
    }

    /**
     * 获取 学期 与班级的关系
     */
    function _get_class_newterm() {
        $classAll = array (); // 所有的班级
        $classxq = array (); // 组合数据 班级的id => 学期
        $classAll = $this->db->select ( '*' )->get_where ( 'squad', 'id > 0' )->result_array ();
        if (! empty ( $classAll )) {
            foreach ( $classAll as $key => $val ) {
                $classxq [$val ['id']] = $val ['nowterm'];
            }
            return $classxq;
        }
        return array ();
    }

    /**
     * 获得 专业 和学费
     */
    function _get_major_fees() {
        $majorAll = array (); // 所有的专业
        $majorfees = array (); // 组合数据
        $majorAll = $this->db->select ( '*' )->get_where ( 'major', 'id > 0' )->result_array ();
        if (! empty ( $majorAll )) {
            foreach ( $majorAll as $key => $val ) {
                $majorfees [$val ['id']] = $val ['tuition'];
            }
            return $majorfees;
        }
        return array ();
    }
    /**
     * [_get_major_term_tuition 获取专业每学期的学费]
     *
     * @return [type] [description]
     */
    function _get_major_term_tuition() {
        $majorAll = $this->db->select ( '*' )->get_where ( 'major', 'id > 0' )->result_array ();
        if (! empty ( $majorAll )) {
            foreach ( $majorAll as $k => $v ) {
                $major_term = $this->db->select ( '*' )->get_where ( 'major', 'majorid = ' . $v ['id'] )->result_array ();
                var_dump ( $major_term );
                exit ();
            }
        }
    }
    /**
     * 导入
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
        $data = $this->student_model->get_student_fields ();
        $this->load->library ( 'sdyinc_export' );
        $d = $this->sdyinc_export->student_tochaneltenplate ( $data );
        if (! empty ( $d )) {
            $this->load->helper ( 'download' );
            force_download ( 'student' . time () . '.xlsx', $d );
            return 1;
        }
    }
    /**
     * 导出保险清单模板
     */
    function tochaneltenplate_insurance() {
        $data = $this->student_model->get_student_insurance_fields ();
        $this->load->library ( 'sdyinc_export' );
        $d = $this->sdyinc_export->student_tochaneltenplate ( $data );
        if (! empty ( $d )) {
            $this->load->helper ( 'download' );
            force_download ( 'student_insurance' . time () . '.xlsx', $d );
            return 1;
        }
    }
    /**
     * 上传保险单表
     */
    function insurance_upload_excel() {
        set_time_limit ( 0 );
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

        $inputFileName = $_SERVER ['DOCUMENT_ROOT'] . $this->_upload ();
        // 插入该表中数据
        $sheetData = $this->insurance_insert_excel ( $inputFileType, $inputFileName );
        // 验证excel表
        $is_insert = $this->insurance_check_excel ( $sheetData );
        if ($is_insert == 1) {
            echo $this->insurance_insert_data ( $sheetData );
        }
    }
    // 插入保险表中数据
    function insurance_insert_excel($inputFileType, $inputFileName) {
        $this->load->library ( 'PHPExcel' );
        $this->load->library ( 'PHPExcel/IOFactory' );
        $this->load->library ( 'PHPExcel/Writer/Excel2007' );
        $objReader = IOFactory::createReader ( $inputFileType );
        $WorksheetInfo = $objReader->listWorksheetInfo ( $inputFileName );
        // 设置只读，可取消类似"3.08E-05"之类自动转换的数据格式，避免写库失败
        $objReader->setReadDataOnly ( true );

        $objPHPExcel = $objReader->load ( $inputFileName );
        $sheetData = $objPHPExcel->getSheet ( 0 )->toArray ( null, true, true, true );
        // excel2003文件，可使用'$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);'
        // excel2007文件使用"getActiveSheet()"方法时会提示出错：对non-object使用了"toArray"方法。
        return $sheetData;
    }
    /**
     * 验证
     */
    function insurance_check_excel($sheetData) {
        $str = '';
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
            'I'
        );
        $mfields = $this->student_model->get_student_insurance_fields ();
        if ($num != count ( $mfields )) {
            $str .= '字段个数不匹配';
        }
        $keysInFile = array ();
        foreach ( $mfields as $key => $value ) {
            $keysInFile [] = $value;
        }
        foreach ( $columns as $keyIndex => $columnIndex ) {
            if ($keywords [$columnIndex] != $keysInFile [$keyIndex]) {
                $str .= $warning . $columnIndex . '列应为' . $keysInFile [$keyIndex] . '，而非' . $keywords [$columnIndex];
            }
        }
        if (empty ( $str )) {
            return 1;
        } else {
            echo $str;
        }
    }
    /**
     * 插入数据库
     */
    function insurance_insert_data($sheetData) {
        unset ( $sheetData [1] );
        $i = 65;
        $m = 2;
        $ss = 2;
        $str = '';

        foreach ( $sheetData as $k => $v ) {
            // 护照号
            $papers_num = $v ['E'];
            $value = '';
            $studentid = $this->student_model->get_student_id ( $papers_num );
            $value .= '"' . $studentid . '",';
            foreach ( $v as $kk => $vv ) {
                if ($kk == 'G') {
                    $value .= '"' . $vv . '",';
                } elseif ($kk == 'H') {
                    $value .= '"' . $vv . '",';
                } elseif ($kk == 'I') {
                    $value .= '"' . $vv . '",';
                }
            }
            $value = trim ( $value, ',' );
            // $insert_student_info_fields=explode(',',$insert_student_info_fields);
            // $info_value=explode(',', $info_value);
            // var_dump($insert_student_info_fields);
            // var_dump($info_value);exit;
            $count = $this->student_model->insurance_check_student ( $studentid );
            if ($count > 0) {
                $str .= '<br />excel中的' . $ss . "行护照号与数据库重复,,该行没有插入";
                $ss ++;
                continue;
            }

            if (! empty ( $value )) {
                $insert = 'studentid,premium,deadline,effect_time';
                // 插入学生签证表
                $this->student_model->insert_insurance_fields ( $insert, $value );
            }

            $i ++;
            $m ++;
            $ss ++;
        }
        // if($str!=''){
        // echo $str;
        // }
        if (empty ( $str )) {
            return '插入成功';
        } else {
            return $str;
        }
    }
    /**
     * 上传major
     */
    function upload_excel() {
        set_time_limit ( 0 );
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

        $inputFileName = $_SERVER ['DOCUMENT_ROOT'] . $this->_upload ();

        // 插入该表中数据
        $sheetData = $this->insert_excel ( $inputFileType, $inputFileName );
        // 验证excel表
        $is_insert = $this->check_excel ( $sheetData );
        if ($is_insert == 1) {
            echo $this->insert_data ( $sheetData );
        }
    }
    /**
     * 插入excel数据
     */
    function insert_excel($inputFileType, $inputFileName) {
        $this->load->library ( 'PHPExcel' );
        $this->load->library ( 'PHPExcel/IOFactory' );
        $this->load->library ( 'PHPExcel/Writer/Excel2007' );
        $objReader = IOFactory::createReader ( $inputFileType );
        $WorksheetInfo = $objReader->listWorksheetInfo ( $inputFileName );
        // 设置只读，可取消类似"3.08E-05"之类自动转换的数据格式，避免写库失败
        $objReader->setReadDataOnly ( true );

        $objPHPExcel = $objReader->load ( $inputFileName );
        $sheetData = $objPHPExcel->getSheet ( 0 )->toArray ( null, true, true, true );
        // excel2003文件，可使用'$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);'
        // excel2007文件使用"getActiveSheet()"方法时会提示出错：对non-object使用了"toArray"方法。
        return $sheetData;
    }
    /**
     * 验证
     */
    function check_excel($sheetData) {
        $str = '';
        if (! empty ( $sheetData [1] )) {
            foreach ( $sheetData [1] as $key => $value ) {
                if (empty ( $value )) {
                    unset ( $sheetData [1] [$key] );
                }
            }
        }
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
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z',
            'AA',
            'AB',
            'AC',
            'AD',
            'AE',
            'AF',
            'AG',
            'AH',
            'AI',
            'AJ'
        );
        $mfields = $this->student_model->get_student_fields ();
        if ($num != count ( $mfields )) {
            $str .= '字段个数不匹配';
        }
        $keysInFile = array ();
        foreach ( $mfields as $key => $value ) {
            $keysInFile [] = $value;
        }
        foreach ( $columns as $keyIndex => $columnIndex ) {
            if ($keywords [$columnIndex] != $keysInFile [$keyIndex]) {
                $str .= $warning . $columnIndex . '列应为' . $keysInFile [$keyIndex] . '，而非' . $keywords [$columnIndex];
            }
        }
        if (empty ( $str )) {
            return 1;
        } else {
            echo $str;
        }
    }
    /**
     * 插入数据库
     */
    function insert_data($sheetData) {
        $student_fields = $this->student_model->insert_student_fields ();
        $student_visa_fields = $this->student_model->insert_student_visa_fields ();
        $student_info_fields = $this->student_model->insert_student_info_fields ();
        $degree = CF ( 'degree', '', CONFIG_PATH );
        // 组合student表插入的字段
        $insert = '';
        foreach ( $student_fields as $k => $v ) {
            $insert .= $k . ',';
        }
        $insert_student_fields = trim ( $insert, ',' );
        $insert_student_fields .= ',userid,enname';
        // 组合student_visa表插入的字段
        $inserts = '';
        foreach ( $student_visa_fields as $k => $v ) {
            $inserts .= $k . ',';
        }
        $insert_info = '';
        foreach ( $student_info_fields as $k => $v ) {
            $insert_info .= $k . ',';
        }
        $insert_student_info_fields = trim ( $insert_info, ',' );
        $insert_student_visa_fields = trim ( $inserts, ',' );
        $insert_student_visa_fields .= ',studentid';
        unset ( $sheetData [1] );
        $i = 65;
        $m = 2;
        $ss = 2;
        $str = '';

        // var_dump($insert);
        // var_dump($sheetData[2]);exit;
        foreach ( $sheetData as $k => $v ) {
            $is_insert = 1;
            // 护照号
            $papers_num = '';
            $value = '';
            $visa_value = '';
            $info_value = '';
            $enname = $v ['F'] . " " . $v ['G'];
            foreach ( $v as $kk => $vv ) {
                if(empty($vv)){
                    $vv=='';
                }
                if ($kk == 'Z' || $kk == 'AC') {
                    $vv = $vv == '是' ? 1 : 0;
                    $value .= '"' . $vv . '",';
                } elseif ($kk == 'A') {
                    $nationality = $this->student_model->get_nationality_id ( $vv );
                    $value .= '"' . $nationality . '",';
                } elseif ($kk == 'I') {
                    $vv = $vv == '男' ? 1 : 2;
                    $value .= '"' . $vv . '",';
                    $info_value .= '"' . $vv . '",';
                } elseif ($kk == 'P') {
                    $vv = $this->student_model->get_degree_type ( $degree, $vv );
                    $value .= '"' . $vv . '",';
                } elseif ($kk == 'V') {
                    $vv = $this->student_model->get_student_state ( $vv );
                    $value .= '"' . $vv . '",';
                } elseif ($kk == 'AB') {
                    $vv = $vv == '校内' ? 1 : 0;
                    $value .= '"' . $vv . '",';
                } elseif ($kk == 'Q' || $kk == 'R' || $kk == 'S' || $kk == 'T' || $kk == 'U') {
                    $visa_value .= '"' . $vv . '",';
                } else {
                    $value .= '"' . $vv . '",';
                }

                // student_info插入数据
                if ($kk == 'A') {
                    $nationality = $this->student_model->get_nationality_id ( $vv );
                    $info_value .= '"' . intval ( $nationality ) . '",';
                } elseif ($kk == 'H') {

                    $info_value .= '"' . $vv . '",';
                } elseif ($kk == 'F') {
                    $info_value .= '"' . $vv . '",';
                } elseif ($kk == 'G') {
                    $info_value .= '"' . $vv . '",';
                } elseif ($kk == 'W') {
                    if (! empty ( $vv )) {
                        $info_value .= '"' . $vv . '",';
                    } else {
                        $vv = '';
                        $info_value .= '"' . $vv . '",';
                    }
                } elseif ($kk == 'C') {
                    $info_value .= '"' . $vv . '",';
                } elseif ($kk == 'J') {
                    $vv = strval ( $vv );
                    $time = strtotime ( $vv );
                    $info_value .= '"' . $time . '",';
                } elseif ($kk == 'L') {
                    $info_value .= '"' . $vv . '",';
                    $password = substr ( md5 ( '123456' ), 0, 27 );
                    $info_value .= '"' . $password . '",';
                    $info_value .= '"' . time () . '",';
                    $ip = get_client_ip ();
                    $info_value .= '"' . $ip . '",';
                } elseif ($kk == 'D') {
                    $vv = strval ( $vv );
                    $time = strtotime ( $vv );
                    $info_value .= '"' . $time . '",';
                }
                if ($kk == 'C') {

                    if (empty ( $vv )) {
                        $is_insert = 0;
                    } else {
                        $papers_num = $vv;
                    }
                }
            }

            $info_value = trim ( $info_value, ',' );
            if ($is_insert == 0) {
                $str .= '<br />excel中的' . $ss . "行C列为空,该行没有插入";
                $ss ++;
                continue;
            }
            // $insert_student_info_fields=explode(',',$insert_student_info_fields);
            // $info_value=explode(',', $info_value);
            // var_dump($insert_student_info_fields);
            // var_dump($info_value);exit;
            $count = $this->student_model->check_student ( $papers_num );
            $count_info = $this->student_model->check_student_info ( $papers_num );
            if ($count > 0 || $count_info > 0) {
                $str .= '<br />excel中的' . $ss . "行护照号与数据库重复,,该行没有插入";
                $ss ++;
                continue;
            }
            // 插入学生注册表
            $userid = $this->student_model->insert_student_info ( $insert_student_info_fields, $info_value );
            if (! empty ( $userid )) {
                // 插入学生表
                $value .= $userid;
                $value .= ',"' . $enname . '"';
                $studentid = $this->student_model->insert_fields ( $insert_student_fields, $value );
            }

            if (! empty ( $studentid )) {
                $visa_value .= $studentid;
                // 插入学生签证表
                $this->student_model->insert_visa_fields ( $insert_student_visa_fields, $visa_value );
            }

            // $insert_student_fields=explode(',', $insert_student_visa_fields);
            // $value=explode(',', $visa_value);
            // var_dump($insert_student_fields);
            // var_dump($value);exit;
            $i ++;
            $m ++;
            $ss ++;
        }
        // if($str!=''){
        // echo $str;
        // }
        if (empty ( $str )) {
            return '插入成功';
        } else {
            return $str;
        }
    }
    // 批量分班
    function part_class() {
        if ($this->input->is_ajax_request () === true) {
            // 设置查询字段
            $fields = $this->part_class_field ();
            $limit = "";
            $offset = "";
            if (isset ( $_GET ['iDisplayStart'] ) && $_GET ['iDisplayLength'] != '-1') {
                $offset = intval ( $_GET ['iDisplayStart'] );
                $limit = intval ( $_GET ['iDisplayLength'] );
            }
            $where = 'state=1 AND squadid=0';
            $sSearch_1 = $this->input->get ( 'sSearch_1' );
            if (! empty ( $sSearch_1 )) {
                $where .= "
				AND (
				id LIKE '%{$sSearch_1}%'
				)
				";
            }
            $sSearch_2 = $this->input->get ( 'sSearch_2' );
            if (! empty ( $sSearch_2 )) {
                $where .= "
				AND (
				studentid LIKE '%{$sSearch_2}%'
				)
				";
            }
            $sSearch_3 = $this->input->get ( 'sSearch_3' );
            if (! empty ( $sSearch_3 )) {
                $where .= "
				AND (
				name LIKE '%{$sSearch_3}%'
				)
				";
            }
            $sSearch_4 = $this->input->get ( 'sSearch_4' );
            if (! empty ( $sSearch_4 )) {
                $where .= "
				AND (
				firstname LIKE '%{$sSearch_4}%'
				)
				";
            }
            $sSearch_5 = $this->input->get ( 'sSearch_5' );
            if (! empty ( $sSearch_5 )) {
                $where .= "
				AND (
				lastname LIKE '%{$sSearch_5}%'
				)
				";
            }
            $sSearch_6 = $this->input->get ( 'sSearch_6' );
            if (! empty ( $sSearch_6 )) {
                $where .= "
				AND (
				passport LIKE '%{$sSearch_6}%'
				)
				";
            }
            $sSearch_7 = $this->input->get ( 'sSearch_7' );
            if (! empty ( $sSearch_7 )) {
                $where .= "
				AND (
				majorid = '{$sSearch_7}'
				)
				";
            }
            $sSearch_8 = $this->input->get ( 'sSearch_8' );
            if (! empty ( $sSearch_8 )) {
                $where .= "
				AND (
				nationality = '{$sSearch_8}'
				)
				";
            }
            $sSearch_9 = $this->input->get ( 'sSearch_9' );
            if (! empty ( $sSearch_9 )) {
                $where .= "
				AND (
				language_level = '{$sSearch_9}'
				)
				";
            }
            $output ['sEcho'] = intval ( $_GET ['sEcho'] );
            $output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->student_model->count_part_class ( $where );
            $output ['aaData'] = $this->student_model->get_part_class ( $where, $limit, $offset, 'id DESC' );
            foreach ( $output ['aaData'] as $item ) {
                $majorname = $this->student_model->get_majorname ( $item->majorid );
                $item->majorid = ! empty ( $majorname ) ? $majorname : '&nbsp;&nbsp;--';
                $nationality = $this->student_model->get_nationality ( $item->nationality );
                $item->nationality = $nationality;
                $item->score = $this->student_model->get_test_score ( $item->userid );
                $item->checkbox = '<input type="checkbox" name="sid[]" value="' . $item->id . '">';
                $item->operation = '
					<a class="btn btn-xs btn-info" onblur="input(' . $item->id . ')" data-toggle="modal" role="button" href="#modal-table">分班</a>
				';
            }
            $sSearch_10 = $this->input->get ( 'sSearch_10' );
            if (! empty ( $sSearch_10 )) {
                foreach ( $output ['aaData'] as $k => $v ) {
                    if (strstr ( $v->score, $sSearch_10 ) == false) {
                        unset ( $output ['aaData'] [$k] );
                    }
                }
                $output ['aaData'] = array_values ( $output ['aaData'] );
            }
            exit ( json_encode ( $output ) );
        }
        $major_info = $this->student_model->get_major_info ();
        // 国籍
        $nationality = CF ( 'public', '', CACHE_PATH );
        $this->_view ( 'student_part_class', array (
            'major_info' => $major_info,
            'nationality' => $nationality
        ) );
    }
    function do_student_part_class() {
        $data = $this->input->post ();
        $majorid = $this->input->get ( 'majorid' );
        $squadid = $this->input->get ( 'squadid' );
        // 批量分班
        $is = $this->student_model->do_student_class ( $data, $majorid, $squadid );
        if ($is) {
            ajaxReturn ( '', '分班成功', 1 );
        } else {
            ajaxReturn ( '', '分班失败', 0 );
        }
    }
    /**
     * 设置列表字段
     */
    private function part_class_field() {
        return array (
            'id',
            'studentid',
            'name',
            'firstname',
            'lastname',
            'passport',
            'majorid',
            'nationality',
            'language_level',
            'userid'
        );
    }

    /**
     * 上传
     *
     * @return string
     */
    private function _upload() {
        $config = array (
            'save_path' => '/uploads/work/' . date ( 'Ym' ) . '/' . date ( 'd' ),
            'upload_path' => $_SERVER ['DOCUMENT_ROOT'] . '/uploads/work/' . date ( 'Ym' ) . '/' . date ( 'd' ),
            'allowed_types' => 'xls|xlsx',
            'file_name' => time () . rand ( 100000, 999999 )
        );

        if (! empty ( $config )) {
            $this->load->library ( 'upload', $config );
            // 创建目录
            mk_dir ( $config ['upload_path'] );

            if (! $this->upload->do_upload ( 'file' )) {
                ajaxReturn ( '', $this->upload->display_errors ( '', '' ), 0 );
            } else {
                $imgdata = $this->upload->data ();
                return $config ['save_path'] . '/' . $imgdata ['file_name'];
            }
        }
    }
    /**
     * 导出条件模板
     */
    function export_where() {
        $nationality = CF ( 'public', '', CACHE_PATH );
        $s = intval ( $this->input->get ( 's' ) );
        if (! empty ( $s )) {
            $html = $this->_view ( 'export_where', array (
                'nationality' => $nationality
            ), true );
            ajaxReturn ( $html, '', 1 );
        }
    }
    /**
     * 导出
     */
    function export() {
        $where = $this->input->post ();
        foreach ( $where as $k => $v ) {
            if ($v == 0) {
                unset ( $where [$k] );
            }
        }
        $this->load->library ( 'sdyinc_export' );

        $data = $this->sdyinc_export->do_export_student ( $where, 'where' );

        if (! empty ( $data )) {
            $this->load->helper ( 'download' );
            force_download ( 'student' . time () . '.xlsx', $data );
            return 1;
        }
    }
    /**
     * 导出部分学生
     */
    function derive_part() {
        $data = $this->input->post ();
        $str = '';
        foreach ( $data ['sid'] as $k => $v ) {
            $str .= 'student.id=' . $v . " or ";
        }
        $str = trim ( $str, ' or ' );
        $this->load->library ( 'sdyinc_export' );

        $d = $this->sdyinc_export->do_export_student ( $str, 'in' );

        if (! empty ( $d )) {
            $this->load->helper ( 'download' );
            force_download ( 'student' . time () . '.xlsx', $d );
            return 1;
        }
    }
    /**
     * [onsite 现场缴费]
     *
     * @return [type] [html]
     */
    function onsite() {
        $s = intval ( $this->input->get ( 's' ) );
        $userid = $this->input->get ( 'userid' );
        $id = intval ( $this->input->get ( 'id' ) );
        $type = 7;
        $url = '/master/student/student/do_onsite';
        $jump_url = '/master/student/student';
        if (! empty ( $s )) {
            $html = $this->_view ( 'onsite', array (
                'userid' => $userid,
                'id' => $id,
                'type' => $type,
                'url' => $url,
                'jump_url' => $jump_url
            ), true );
            ajaxReturn ( $html, '', 1 );
        }
    }
    /**
     * [do_onsite 现场缴费]
     *
     * @return [type] [ajax]
     */
    function do_onsite() {
        $data = $this->input->post ();
        if (! empty ( $data )) {
            $result = $this->student_model->pay_change_state ( $data );
            if ($result == 0) {
                ajaxReturn ( '', '该学期已经交过学费', 0 );
            }
            if ($result) {
                $results = $this->student_model->insert_pay_record ( $data );
                if ($results) {
                    ajaxReturn ( '', '提交成功', 1 );
                }
            }
        }
        ajaxReturn ( '', '未知错误', 0 );
    }
    /**
     * [send_message 批量发站内信]
     *
     * @return [type] [description]
     */
    function send_message() {
        $data = $this->input->post ();
        if (! empty ( $data ['is_userid'] )) {
            $userid = $data ['sid'];
        } else {
            $userid = $this->student_model->get_userid_arr ( $data ['sid'] );
        }

        $idstr = '';
        foreach ( $userid as $k => $v ) {
            $idstr .= $v . ',';
        }
        $this->_view ( 'customemessage_send', array (
            'ids' => $userid,
            'idstr' => $idstr
        ) );
    }
    function insert_message() {
        $data = $this->input->post ();
        $content = $this->input->post ( 'content' );
        $data ['sendtime'] = time ();
        $data ['content'] = $content;

        $id = $this->student_model->save_message ( $data );

        if ($id == true) {
            $this->send_messages ( $data, $content );

            ajaxReturn ( '', '添加成功', 1 );
        } else {
            ajaxReturn ( '', '添加失败', 0 );
        }
    }
    /**
     * 自定义发送消息
     */
    function send_messages($data, $content) {
        $senttoid = explode ( ',', $data ['sentto'] );
        $this->load->library ( 'sdyinc_message' );
        foreach ( $senttoid as $k => $v ) {
            $this->sdyinc_message->custom_message ( $v, $data ['title'], $content );
        }
        ajaxreturn ( '', '操作成功', 1 );
    }
    /**
     * [send_email 批量发邮件]
     *
     * @return [type] [description]
     */
    function send_email() {
        $data = $this->input->post ();
        if (! empty ( $data ['is_userid'] )) {
            $emailarr = $this->student_model->get_email_user_arr ( $data ['sid'] );
        } else {
            $emailarr = $this->student_model->get_email_arr ( $data ['sid'] );
        }
        $emailstr = '';
        foreach ( $emailarr as $k => $v ) {
            $emailstr .= $v . ',';
        }
        $adrset = $this->student_model->get_addresserset ();
        $this->_view ( 'customemail_edit', array (
            'addresserset' => $adrset,
            'emailarr' => $emailarr,
            'emailstr' => $emailstr
        ) );
    }
    function insert_email() {
        $data = $this->input->post ();
        $content = $this->input->post ( 'content' );
        $data ['sendtime'] = time ();
        $data ['content'] = $content;
        $id = $this->student_model->save_email ( $data );
        if ($id == true) {
            $this->send_emails ( $data, $content );
        } else {
            ajaxReturn ( '', '添加失败', 0 );
        }
    }
    /**
     * 自定义发送邮件
     */
    function send_emails($data, $content) {
        $this->load->library ( 'sdyinc_email' );
        $senttoid = explode ( ',', $data ['sentto'] );
        foreach ( $senttoid as $k => $v ) {
            $this->sdyinc_email->do_send_mail ( $v, $data ['addresserset'], $data ['title'], $content, $data ['reply_to'] );
        }
        ajaxreturn ( '', '操作成功', 1 );
    }
    /**
     * [add_rebuild 添加重修费页面]
     */
    function add_rebuild() {
        $userid = intval ( $this->input->get ( 'userid' ) );
        $where = 'userid = ' . $userid;
        $info = $this->student_model->get_one ( $where );

        $mdata = $this->student_model->get_major_info_one ( $info->majorid );
        $this->_view ( 'add_rebuild', array (
            'userid' => $userid,
            'mdata' => $mdata,
            'info' => $info
        ) );
    }
    /**
     * [insert_rebuild 插入重修费表]
     *
     * @return [type] [description]
     */
    function insert_rebuild() {
        $data = $this->input->post ();
        if (! empty ( $data )) {
            // 先插入收支表
            // 组合收支表的字段
            $budget = array (
                'userid' => $data ['userid'],
                'budget_type' => 1,
                'type' => 12,
                'payable' => $data ['money'],
                'paystate' => 0,
                'createtime' => time (),
                'term' => $data ['term']
            );
            $budgetid = $this->fee_model->insert_budget ( $budget );
            $data ['createtime'] = time ();
            $data ['adminid'] = $_SESSION ['master_user_info']->id;
            $data ['budgetid'] = $budgetid;
            $id = $this->student_model->save_rebuild ( $data );
            if (! empty ( $id )) {
                ajaxreturn ( '', '', 1 );
            }
        }
        ajaxreturn ( '', '', 0 );
    }
    /**
     * [add_rebuild 添加重修费页面]
     */
    function add_barter_card() {
        $userid = intval ( $this->input->get ( 'userid' ) );
        $where = 'userid = ' . $userid;
        $info = $this->student_model->get_one ( $where );

        $mdata = $this->student_model->get_major_info_one ( $info->majorid );
        $this->_view ( 'add_barter_card', array (
            'userid' => $userid,
            'mdata' => $mdata,
            'info' => $info
        ) );
    }
    /**
     * [insert_add_barter_card 插入重修费表]
     *
     * @return [type] [description]
     */
    function insert_barter_card() {
        $data = $this->input->post ();
        if (! empty ( $data )) {
            // 组合收支表的字段
            $budget = array (
                'userid' => $data ['userid'],
                'budget_type' => 1,
                'type' => 11,
                'payable' => $data ['money'],
                'paystate' => 0,
                'createtime' => time (),
                'term' => $data ['term']
            );
            $budgetid = $this->fee_model->insert_budget ( $budget );
            $data ['createtime'] = time ();
            $data ['adminid'] = $_SESSION ['master_user_info']->id;
            $data ['budgetid'] = $budgetid;
            $id = $this->student_model->save_add_barter_card ( $data );
            if (! empty ( $id )) {
                ajaxreturn ( '', '', 1 );
            }
        }
        ajaxreturn ( '', '', 0 );
    }

    /**
     * 设置活动次数 清零
     */
    function set_zero() {
        $id = intval ( $this->input->get ( 'id' ) );
        if ($id) {
            $flag = $this->student_model->save ( $id, array (
                'joincounts' => 0
            ) );
            ajaxReturn ( '', '', 1 );
        } else {
            ajaxReturn ( '', '', 0 );
        }
    }
    /**
     * [outline 教学大纲页面]
     *
     * @return [type] [description]
     */
    function outline() {
        $id = intval ( $this->input->get ( 'id' ) );
        $remark = $this->student_model->get_student_remark ( $id );
        // var_dump($t_c_info);exit;
        $s = intval ( $this->input->get ( 's' ) );
        if (! empty ( $s )) {
            // var_dump($mcinfo);exit;
            $html = $this->_view ( 'outline', array (
                'remark' => $remark,
                'id' => $id
            ), true );
            ajaxReturn ( $html, '', 1 );
        }
    }
    /**
     * [edit_remark 编辑学生备注]
     *
     * @return [type] [description]
     */
    function edit_remark() {
        $data = $this->input->post ();
        if (! empty ( $data ['id'] )) {
            $arr ['remark'] = $data ['remark'];
            $this->db->update ( 'student', $arr, 'id = ' . $data ['id'] );
            ajaxReturn ( '', '', 1 );
        }
        ajaxReturn ( '', '', 1 );
    }

    /**
     * 费用列表
     */
    function student_fee() {
        $userid = $this->input->get ( 'userid' );
        if (! empty ( $userid )) {
            $info = $this->db->get_where ( 'budget', 'userid = ' . $userid . ' AND budget_type = 1' )->result_array ();
            $info_tui = $this->db->get_where ( 'budget', 'userid = ' . $userid . ' AND budget_type = 2' )->result_array ();
            $html = $this->_view ( 'student_fee', array (
                'info' => ! empty ( $info ) ? $info : array (),
                'info_tui' => ! empty ( $info_tui ) ? $info_tui : array ()
            ), true );
            ajaxReturn ( $html, '', 1 );
        }
    }

    private function _get_major_by_degree($major_lists = array()){
        $temp = array();
        if(!empty($major_lists)){
            $degree = $this->student_model->get_degree_name('id > 0',0,0,'orderby desc');
			
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
}