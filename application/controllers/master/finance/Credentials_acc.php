<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Credentials_acc extends Master_Basic
{
    /**
     * 基础类构造函数
     */
    function __construct()
    {
        parent::__construct();
        $this->view = 'master/finance/';
        $this->load->model($this->view . 'credentials_acc_model');
        $this->load->model ( 'home/pay_model' );
        $this->load->model ( 'home/apply_pa_model' );
    }

    /**
     * 后台主页
     */
    function index()
    {
        if ($this->input->is_ajax_request() === true) {
            // 设置查询字段
            $fields = $this->_set_lists_field();

            // 查询条件组合
            $condition = dateTable_where_order_limit($fields);
            $output ['sEcho'] = intval($_GET ['sEcho']);
            $output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->credentials_acc_model->count($condition);
            $output ['aaData'] = $this->credentials_acc_model->get($fields, $condition);
            // 读取国籍缓存
            $nationality = CF('nationality', '', 'application/cache/');
            foreach ($output ['aaData'] as $item) {
                $state = $this->_get_lists_state($item->state);
                $currency = '人民币';
                //订单编号
                $acc_info=$this->credentials_acc_model->get_acc_info($item->oid);
                $campname=$this->credentials_acc_model->get_campus_name($acc_info['campid']);
                $buildname=$this->credentials_acc_model->get_buliding_name($acc_info['buildingid']);
                $room_info=$this->credentials_acc_model->get_room_name($acc_info['roomid']);
                $item->duixiang = '校区名:'.$campname.'<br />楼宇:'.$buildname.'<br />第'.$room_info['floor'].'层<br />房间名:'.$room_info['name'];
                $item->ren = '中文名:' . $item->chname . '<br />' . '英文名:' . $item->enname . '<br />国籍:' . $nationality[$item->nationality] . '<br />邮箱:' . $item->email . '<br />护照:' . $item->passport;
                $item->shijian = $state . '<br />总钱数:' . $item->amount . ' &nbsp;&nbsp;'.$currency.'<br />创建时间:' . date('Y-m-d H:i:s', $item->createtime);
                $item->shijian.='<br /><a href="javascript:pub_alert_html(\'/master/enrollment/appmanager/editproof?id=' . $item->id . '\');">查看凭据</a><br />';
                $item->shijian.='<a href="javascript:pub_alert_html(\'/master/enrollment/appmanager/lookproof?id=' . $item->id . '\');">查看汇款信息</a>';
                $item->state = $this->credentials_acc_model->get_paystate($item->state);
                $item->operation = '<div class="btn-group"><a class="btn btn-xs btn-info" href="javascript:end_apply(' . $item->id . ',1);" title="通过" rel="tooltip">通过</a>
								<a class="btn btn-xs btn-info btn-white dropdown-toggle" href="javascript:end_apply(' . $item->id . ',2);" title="不通过" rel="tooltip">不通过</a>
							<ul class="dropdown-menu dropdown-info dropdown-menu-right">';
                $item->operation.='</ul></div>';
                
                
            }
            // var_dump($output);die;
            exit (json_encode($output));
        }
        $this->_view('credentials_acc_index');
    }

    /**
     * 设置列表字段
     */
    private function _set_lists_field()
    {
        return array(
            'zust_credentials.id',
            'zust_credentials.state',
            'zust_credentials.updatetime',
            'zust_credentials.createtime',
            'zust_credentials.file',
            'zust_credentials.amount',
            'zust_credentials.way',
            'zust_credentials.currency',
            'zust_student_info.chname',
            'zust_student_info.enname',
            'zust_student_info.passport',
            'zust_student_info.email',
            'zust_student_info.nationality',
            'zust_apply_order_info.applyid',
            'zust_apply_order_info.id as oid'
        );
    }

    /**
     * 编辑 凭据
     */
    function doproof()
    {
        $id = intval(trim($this->input->get('id')));
        $state = intval(trim($this->input->get('state')));
        $this->load->library('sdyinc_email');
        if (!empty ($id) && !empty ($state)) {
            // 凭据信息
            $c = $this->db->select('*')->get_where('credentials', 'id = ' . $id)->row();
            // 订单信息
            $o = $this->db->select('*')->get_where('apply_order_info', 'id = ' . $c->orderid)->row();
            // 更新凭据信息
            $flag1 = $this->db->update('credentials', array(
                'state' => $state,
                'updateuser'=>$_SESSION ['master_user_info']->id,
                'updatetime'=>time()
            ), 'id = ' . $id);
            // 更新订单表
            $flag3 = $this->db->update('apply_order_info', array(
                'paystate' => $state
            ), 'id = ' . $c->orderid);
            //更新收支表
             $flag4 = $this->db->update('budget', array(
                'paid_in'=>$o->ordermondey,//实缴费用
                'lasttime'=>time(),
                'adminid'=>$_SESSION ['master_user_info']->id,
                'paystate' => $state
            ), 'id = ' . $o->budget_id);
            //更改房间预订的状态
            $acc=$this->db->select('*')->get_where('accommodation_info', 'order_id = ' . $c->orderid)->row();

            //查询有没有押金的订单
            $pledge_info=$this->db->where('acc_id',$acc->id)->get('acc_pledge_info')->row_array();
            if(!empty($pledge_info)){
                //开始更新押金表的订单
                // 修改订单表
                $pledgeOrder = array (
                        'paystate' => $state
                );
                $this->pay_model->save_apply_order_info ( 'id = '.$pledge_info['order_id'], $pledgeOrder );
                // 修改申请表
                $pledgeApply = array (
                        'state' => $state
                );

                $this->apply_pa_model->save_apply_info ( 'id = '.$pledge_info['id'], $pledgeApply, 'acc_pledge_info' );
                //更新住宿押金收支表
                $pledge_order_info=$this->pay_model->get_apply_order_info('id = '.$pledge_info['order_id']);
                //更新收支表里
                $pledge_budgetData=array(
                        'paid_in'=>$pledge_order_info[0]['ordermondey'],//实缴费用
                        'lasttime'=>time(),
                        'adminid'=>$_SESSION ['master_user_info']->id,
                        'paystate' => $state
                    );
                $this->apply_pa_model->save_apply_info ( 'id = '.$pledge_order_info[0]['budget_id'], $pledge_budgetData, 'budget' );
            }
            // 查用户
            $user = $this->db->get_where('student_info', 'id = ' . $o->userid)->result_array();

            $email = $user [0] ['email'];

            $usd = $o->ordermondey . ' RMB';
            $name = 'Accommodation';

            $val_arr = array(
                'email' => !empty ($email) ? $email : '',
                'usd' => !empty ($usd) ? $usd : '',
                'name' => !empty ($name) ? $name : ''
            );
            $MAIL = new sdyinc_email ();
            if ($state == 1) {
                //更新房间的押金和住宿费和状态
                $this->db->update('accommodation_info',array('acc_state'=>2,'paystate'=>1),'id = '.$acc->id);
                $a = 27;
                $operation = '通过';
                $this->grf_update_room();
            } else {
                //更新房间预订的人数
                $this->db->update('accommodation_info', array('acc_state' => 1,'paystate'=>2), 'id = ' . $acc->id);
                $a = 28;
                $operation = '不通过';
                $this->grf_update_room();
            }
            $MAIL->dot_send_mail($a, $email, $val_arr);

            if ($flag1 && $flag3) {
                // 写入日志

                $datalog = array(
                    'adminid' => $_SESSION ['master_user_info']->id,
                    'adminname' => $_SESSION ['master_user_info']->username,
                    'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date('Y-m-d H:i:s', time()) . '修改了(住宿费)凭据用户' . $email . '的信息为' . $operation,
                    'ip' => get_client_ip(),
                    'lasttime' => time(),
                    'type' => 6
                );
                if (!empty ($datalog)) {
                    $this->adminlog->savelog($datalog);
                }


                ajaxReturn('', '', 1);
            } else {
                ajaxReturn('', '', 0);
            }
        } else {
            ajaxReturn('', '', 0);
        }
    }

    /**
     * 获取书籍状态
     *
     * @param string $statecode
     * @param string $stateindexcode
     * @return string
     */
    private function _get_lists_state($statecode = null)
    {
        if ($statecode != null) {
            $statemsg = array(
                '<span class="label label-important">待支付</span>',
                '<span class="label label-success">成功</span>',
                '<span class="label label-important">失败</span>',
                '<span class="label label-important">待审核</span>'
            );
            return $statemsg [$statecode];
        }
        return '';
    }
    /**
     * [add_shibai 添加失败备注]
     */
    function add_shibai(){
    	$id=$this->input->get('id');
    	$info=$this->db->get_where('credentials','id = '.$id)->row_array();
    $type=$this->input->get('type');
    	$html = $this->load->view('/master/finance/remark', array(
    			'id'=>$id,
    			'type'=>$type
    		), true);
		ajaxReturn($html, '', 1);
    }
    /**
     * [save_remark 保存失败原因]
     * @return [type] [description]
     */
    function save_remark(){
    	$info=$this->input->post();
    	if(!empty($info)){
    		$id=$info['id'];
    		unset($info['id']);
    		$this->db->update('credentials',array('remark' => !empty($info['cause'])?$info['cause']:''),'id = '.$id);
    		ajaxReturn('','',1);
    	}
    }
    }