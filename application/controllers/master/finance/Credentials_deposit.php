<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Credentials_deposit extends Master_Basic
{
    /**
     * 基础类构造函数
     */
    function __construct()
    {
        parent::__construct();
        $this->view = 'master/finance/';
        $this->load->model($this->view . 'credentials_deposit_model');
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
            $output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->credentials_deposit_model->count($condition);
            $output ['aaData'] = $this->credentials_deposit_model->get($fields, $condition);
            // 读取国籍缓存
            $nationality = CF('nationality', '', 'application/cache/');
            foreach ($output ['aaData'] as $item) {
                $state = $this->_get_lists_state($item->state);
                $currency = '人民币';
                //订单编号
                $apply_info=$this->db->where('id',$item->applyid)->get('apply_info')->row_array();
                $major_info=$this->db->where('id',$apply_info['courseid'])->get('major')->row_array();
                $item->duixiang = !empty($major_info['name'])?$major_info['name']:'';
                $item->ren = '中文名:' . $item->chname . '<br />' . '英文名:' . $item->enname . '<br />国籍:' . $nationality[$item->nationality] . '<br />邮箱:' . $item->email . '<br />护照:' . $item->passport;
                $item->shijian = $state . '<br />总钱数:' . $item->amount . ' &nbsp;&nbsp;'.$currency.'<br />创建时间:' . date('Y-m-d H:i:s', $item->createtime).'<br />';
                $item->shijian.='<a href="javascript:pub_alert_html(\'/master/enrollment/appmanager/editproof?id=' . $item->id . '\');">查看凭据</a><br />';
                $item->shijian.='<a href="javascript:pub_alert_html(\'/master/enrollment/appmanager/lookproof?id=' . $item->id . '\');">查看汇款信息</a>';


                $item->state = $this->credentials_deposit_model->get_paystate($item->state);
                $item->operation = '<div class="btn-group"><a class="btn btn-xs btn-info" href="javascript:ends_apply(' . $item->id . ',1);" title="通过" rel="tooltip">通过</a><a class="btn btn-xs btn-info btn-white dropdown-toggle" href="javascript:end_apply(' . $item->id . ',2);" title="不通过" rel="tooltip">
								不通过
							</a><a class="btn btn-xs btn-info btn-white dropdown-toggle" href="javascript:pub_alert_html(\'/master/enrollment/appmanager/addproofremark?id='.$item->id.'\');" title="查看备注" rel="tooltip" data-pk="492^text" data-value="" data-placement="left" data-type="textarea" id="remark">查看备注</a>	
							';
                $item->operation .= ' <li></li>';
                
                $item->operation.='</ul></div>';
                }
            // var_dump($output);die;
            exit (json_encode($output));
        }
        $this->_view('credentials_deposit_index');
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
             //更新申请表里的状态
              $flag4 = $this->db->update('apply_info', array(
                'deposit_state' => $state
            ), 'id = ' . $o->applyid);
            // 查用户
            $user = $this->db->get_where('student_info', 'id = ' . $o->userid)->result_array();

            $email = $user [0] ['email'];

            $usd = $o->ordermondey . ' RMB';
            $name = 'Deposit';

            $val_arr = array(
                'email' => !empty ($email) ? $email : '',
                'usd' => !empty ($usd) ? $usd : '',
                'name' => !empty ($name) ? $name : ''
            );
            $MAIL = new sdyinc_email ();
             if($state==1){
                $tui_dep=CF('tuition','',CONFIG_PATH);
                if(!empty($tui_dep['pledgejiru'])&&$tui_dep['pledgejiru']=='yes'){
                    //开始计入学费1插入收支表2插入学费表
                    //没有收支表插收支表
                    $budget_arr=array(
                            'userid'=>$o->userid,
                            'budget_type'=>1,
                            'type'=>6,
                            'term'=>1,
                            'payable'=>$o->ordermondey,
                            'paid_in'=>$o->ordermondey,
                            'paystate'=>1,
                            'paytime'=>time(),
                            'paytype'=>3,
                            'createtime'=>time(),
                            'adminid'=>$_SESSION['master_user_info']->id,
                            'proof_number'=>'',
                            'file_path'=>'',
                            'remark'=>'押金计入学费'
                        );
                    $this->db->insert('budget',$budget_arr);
                    $bgetid=$this->db->insert_id();
                    //插入学表
                    $tuition_arr=array(
                        'budgetid'=>$bgetid,
                        'nowterm'=>1,
                        'userid'=>$o->userid,
                        'tuition'=>$o->ordermondey,
                        'danwei'=>'rmb',
                        'paystate'=>1,
                        'paytime'=>time(),
                        'paytype'=>8,
                        'createtime'=>time(),
                        'remark'=>'押金计入学费',
                        'lasttime'=>time(),
                        'adminid'=>$_SESSION['master_user_info']->id,
                        );  
                    $this->db->insert('tuition_info',$tuition_arr);
                    //插入一个退费表
                    //没有收支表插收支表
                    $budget_arr=array(
                            'userid'=>$o->userid,
                            'budget_type'=>2,
                            'type'=>6,
                            'term'=>1,
                            'should_returned'=>$o->ordermondey,
                            'true_returned'=>$o->ordermondey,
                            'paystate'=>1,
                            'returned_time'=>time(),
                            'paytype'=>3,
                            'createtime'=>time(),
                            'adminid'=>$_SESSION['master_user_info']->id,
                            'proof_number'=>'',
                            'file_path'=>'',
                            'remark'=>'押金计入学费'
                        );
                    $this->db->insert('budget',$budget_arr);
                              
                }
            }
            if ($state == 1) {
                //更新房间的押金和住宿费和状态
                $a = 27;
                $operation = '通过';
                //查看押金食肉计入学费
                
            } else {
                $a = 28;
                $operation = '不通过';
            }
            $MAIL->dot_send_mail($a, $email, $val_arr);
            if ($flag1 && $flag3) {
                // 写入日志

                $datalog = array(
                    'adminid' => $_SESSION ['master_user_info']->id,
                    'adminname' => $_SESSION ['master_user_info']->username,
                    'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date('Y-m-d H:i:s', time()) . '修改了(押金)凭据用户' . $email . '的信息为' . $operation,
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

    }