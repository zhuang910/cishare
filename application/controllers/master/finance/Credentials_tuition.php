<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Credentials_tuition extends Master_Basic {
	public $run_unit_nationality = array(); // 国籍
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->run_unit_nationality = CF('public','',CACHE_PATH);//获取国籍
		$this->view = 'master/finance/';
		$this->load->model ( $this->view . 'credentials_tuition_model' );
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		$label_id = $this->input->get ( 'label_id' );
		$label_id = ! empty ( $label_id ) ? $label_id : 3;
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$condition ['where'] = 'ordertype = 6';
			$condition ['where'] .= ' AND state = '.$label_id;
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->credentials_tuition_model->count ( $condition );
			$output ['aaData'] = $this->credentials_tuition_model->get ( $fields, $condition );
			
			foreach ( $output ['aaData'] as $item ) {
				$item->userid = $this->credentials_tuition_model->get_username ( $item->userid, 6 );
				$item->createtime = ! empty ( $item->createtime ) ? date ( 'Y-m-d H:i:s', $item->createtime ) : '';
				$item->amount = $item->amount . ' RMB';
				if (! empty ( $item->paytime )) {
					$item->paytime = date ( 'Y-m-d', $item->paytime );
				} else {
					$item->paytime = '';
				}
				$item->state = $this->credentials_tuition_model->get_paystate ( $item->state );
				$item->state.='<br />'.'<a href="javascript:pub_alert_html(\'/master/enrollment/appmanager/editproof?id=' . $item->id . '\');">查看凭据</a><br />';
				$item->state.='<a href="javascript:pub_alert_html(\'/master/enrollment/appmanager/lookproof?id=' . $item->id . '\');">查看汇款信息</a><br />';
				
				$item->operation = '<div class="btn-group"><a class="btn btn-xs btn-info" href="javascript:end_applys(' . $item->id . ',1);" title="通过" rel="tooltip">通过</a>
								<a class="btn btn-xs btn-info btn-white dropdown-toggle" href="javascript:end_apply(' . $item->id . ',2);" title="不通过" rel="tooltip">不通过</a><a class="btn btn-xs btn-info btn-white dropdown-toggle" href="javascript:pub_alert_html(\'/master/enrollment/appmanager/addproofremark?id='.$item->id.'\');" title="查看备注" rel="tooltip" data-pk="492^text" data-value="" data-placement="left" data-type="textarea" id="remark">查看备注</a>	
							<ul class="dropdown-menu dropdown-info dropdown-menu-right">';
			
				
				$item->operation.='</ul></div>';
				
			}
			// var_dump($output);die;
			exit ( json_encode ( $output ) );
		}
		$this->_view ( 'credentials_tuition_index', array (
				'label_id' => $label_id 
		) );
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'orderid',
				'ordernumber',
				'userid',
				'number',
				'amount',
				'file',
				'item',
				'currency',
				'way',
				'state',
				'remark',
				'ordertype',
				'createtime' 
		);
	}
	
	/**
	 * 编辑 凭据
	 */
	function doproof() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		$state = intval ( trim ( $this->input->get ( 'state' ) ) );
		$this->load->library ( 'sdyinc_email' );
		if (! empty ( $id ) && ! empty ( $state )) {
			// 凭据信息
			$c = $this->db->select ( '*' )->get_where ( 'credentials', 'id = ' . $id )->row ();
			// 订单信息
			$o = $this->db->select ( '*' )->get_where ( 'apply_order_info', 'id = ' . $c->orderid )->row ();
			// 更新凭据信息
			$flag1 = $this->db->update ( 'credentials', array (
					'state' => $state,
	                'updateuser'=>$_SESSION ['master_user_info']->id,
	                'updatetime'=>time()
			), 'id = ' . $id );
			// 更新学费（申请表）信息
			$flag2 = $this->db->update ( 'tuition_info', array (
					'paystate' => $state 
			), 'order_id = ' . $o->id );
			// 更新订单表
			$flag3 = $this->db->update ( 'apply_order_info', array (
					'paystate' => $state 
			), 'id = ' . $c->orderid );
			 //更新收支表
             $flag4 = $this->db->update('budget', array(
                'paid_in'=>$o->ordermondey,//实缴费用
                'lasttime'=>time(),
                'adminid'=>$_SESSION ['master_user_info']->id,
                'paystate' => $state
            ), 'id = ' . $o->budget_id);
             //获取该学费的信息
             $tuition_info=$this->db->get_where('tuition_info','order_id = ' . $o->id)->row_array();
             //查询有没有重修费
             //查询有没有重修费
                $chong=$this->db->get_where('student_rebuild','tuitionid = '.$tuition_info['id'])->result_array();
                if(!empty($chong)){
                    foreach($chong as $kk=>$vv){
                      //更新收支表
                       $this->db->update('budget', array(
			                'paid_in'=>$o->ordermondey,//实缴费用
			                'lasttime'=>time(),
			                'adminid'=>$_SESSION ['master_user_info']->id,
			                'paystate' => $state
			            ), 'id = ' . $vv['budgetid']);
                        $this->db->update('student_rebuild', array(
			                'adminid'=>$_SESSION ['master_user_info']->id,
			                'paytime'=>time(),
			                'state' => $state
			            ), 'id = ' . $vv['id']);
                    }
                }
              
                //查询有没有换证费
                $huan=$this->db->get_where('student_barter_card','tuitionid = '.$tuition_info['id'])->result_array();
                if(!empty($huan)){
                    foreach($huan as $kkk=>$vvv){
                       $this->db->update('budget', array(
			                'paid_in'=>$o->ordermondey,//实缴费用
			                'lasttime'=>time(),
			                'adminid'=>$_SESSION ['master_user_info']->id,
			                'paystate' => $state
			            ), 'id = ' . $vv['budgetid']);
                        $this->db->update('student_barter_card', array(
			                'adminid'=>$_SESSION ['master_user_info']->id,
			                'paytime'=>time(),
			                'state' => $state
			            ), 'id = ' . $vvv['id']);
                    }
                }
			// 查用户
			$user = $this->db->get_where ( 'student_info', 'id = ' . $o->userid )->result_array ();
			
			$email = $user [0] ['email'];
			$usd = $o->ordermondey.' RMB';
			$name = 'Tuition fees';

			$val_arr = array (
					'email' => ! empty ( $email ) ? $email : '',
					'usd' => ! empty ( $usd ) ? $usd : '',
					'name' => ! empty ( $name ) ? $name : '' 
			);
			$MAIL = new sdyinc_email ();
			if($state == 1){
				$a = 27;
				$operation = '通过';
			}else{
				$a = 28;
				$operation = '不通过';
			}
			$MAIL->dot_send_mail ( $a,$email,$val_arr);
			if ($flag1 && $flag2 && $flag3) {
				// 写入日志
				
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了(学费)凭据用户' .$email . '的信息为'.$operation,
						'ip' => get_client_ip (),
						'lasttime' => time (),
						'type' => 6
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

}