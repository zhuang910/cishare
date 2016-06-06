<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 汇款
 *
 * @author zyj
 *        
 */
class Remittance extends Student_Basic {
	private $data = array ();
	private $ordertype = array ();
	private $order_info = array ();
	protected $table = array ();
	
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		is_studentlogin ();
		$this->load->model ( 'home/pay_model' );
		$this->load->model ( 'home/apply_model' );
		$this->load->model ( 'home/apply_pa_model' );
		$this->load->model ( 'student/fee_model' );
		$time = time ();
		$this->table = array (
				'3' => 'pickup_info',
				'4' => 'accommodation_info',
				'5' => 'deposit_info',
				'6' => 'tuition_info' ,
				'10'=>'acc_pledge_info'
		);
		
		$this->data ['updatetime'] = $time;
		$publics = CF ( 'publics', '', CONFIG_PATH );
		$this->ordertype = $publics ['ordertype'];
		$this->load->library ( 'sdyinc_email' );
	}
	
	/**
	 * 西联汇款
	 */
	function westernunion() {
		$this->remittance ();
	}
	
	/**
	 * 凭据支付处理
	 */
	function remittance() {
		$this->data ['file'] = $this->_do_upload ();
		$submit_data=$this->input->post();
		
		$is = $this->check ($submit_data);
		
		if ($is === true) {
			ajaxReturn ( '', lang ( 'pj_success' ), 1 );
		}
		ajaxReturn ( '', 'Error!', 0 );
	}
	
	/**
	 * 验证
	 *
	 * @return boolean
	 */
	function check($submit_data) {
		// 订单号
		$ordernumber = trim ( $this->input->post ( 'key' ) );
		$orderid = trim ( $this->input->post ( 'orderid' ) );
		if (! empty ( $orderid )) {
			$where_order = "id = {$orderid} AND ordernumber = '{$ordernumber}'";
			$order_infos = $this->pay_model->get_apply_order_info ( $where_order );
			
			
			// 信息为空的时候返回错误
			if (empty ( $order_infos )) {
				
				return false;
			}
			
			// 不是本用户 也返回错误吧
			if ($order_infos [0] ['userid'] != $_SESSION ['student'] ['userinfo'] ['id']) {
				
				return false;
			}
			
			$this->order_info = $order_infos [0];
			$flag_type = $this->order_info ['ordertype'];
			$format = $this->ordertype [$this->order_info ['ordertype']] ['name'];
		
			switch ($format) {
				case 'Transfer' :
					
					break;
				// 申请
				case 'Apply' :
					// 修改订单表
					$dataOrder = array (
							'lasttime' => time (),
							'paytype' => 3,
							'paystate' => 3,
							'paytime' => time () 
					);
					$this->pay_model->save_apply_order_info ( $where_order, $dataOrder );
					
					$ids = $this->order_info ['applyid'];
					// 修改申请表
					$where_apply = "userid = {$_SESSION['student'] ['userinfo'] ['id']} AND id = {$ids} AND ordernumber = '{$ordernumber}'";
					
					$dataApply = array (
							'paystate' => 3,
							'paytime' => time (),
							'paytype' => 3,
							'isproof' => 1,
							'lasttime' => time () 
					);
					
					$this->apply_model->save_apply_info ( $where_apply, $dataApply );
					//更新收支表
					//更新收支表里
					$acc_budgetData=array(
							'paystate'=>3,
							'paytime'=>time(),
							'paytype'=>3,
							'lasttime'=>time()
						);
					$this->apply_pa_model->save_apply_info ( 'id = '.$this->order_info['budget_id'], $acc_budgetData, 'budget' );
					// 查询凭据信息
					
					$this->data ['state'] = 3;
					$where_credentials = "userid = {$_SESSION['student'] ['userinfo'] ['id']} AND orderid = '{$orderid}'";
					$result = $this->pay_model->get_credentials ( $where_credentials );
					if (! empty ( $result )) {
						$this->pay_model->save_credentials ( $where_credentials, $this->data );
					} else {
						$this->data ['ordernumber'] = $this->order_info ['ordernumber'];
						$this->data ['orderid'] = $this->order_info ['id'];
						$this->data ['amount'] = $this->order_info ['ordermondey'];
						$this->data ['item'] = 1;
						$this->data ['currency'] = 1;
						$this->data ['createtime'] = time ();
						$this->data ['ordertype'] = 1;
						$this->data ['userid'] = $_SESSION ['student'] ['userinfo'] ['id'];
						$this->data ['remit_name'] = $submit_data['remit_name'];
						$this->data ['remit_nationality'] = $submit_data['remit_nationality'];
						$this->data ['remit_money'] = $submit_data['remit_money'];
						$this->data ['student_name'] = $submit_data['student_name'];
						$this->data ['remit_remark'] = $submit_data['remit_remark'];
						$this->data ['userid']=$_SESSION['student'] ['userinfo'] ['id'];
						$flag = $this->pay_model->save_credentials ( null, $this->data );
					}
					
					return true;
					break;
				case 'Pick Up' :
					// 修改订单表
					$dataOrder = array (
							'lasttime' => time (),
							'paytype' => 3,
							'paystate' => 3,
							'paytime' => time () 
					);
					$this->pay_model->save_apply_order_info ( $where_order, $dataOrder );
					
					$ids = $this->order_info ['applyid'];
					// 修改申请表
					$where_apply = "userid = {$_SESSION['student'] ['userinfo'] ['id']} AND id = {$ids} AND ordernumber = '{$ordernumber}'";
					
					$dataApply = array (
							'paystate' => 3,
							'paytime' => time (),
							'paytype' => 3,
							'isproof' => 1,
							'lasttime' => time () 
					);
					
					$this->apply_pa_model->save_apply_info ( $where_apply, $dataApply, $this->table [$flag_type] );
					
					// 查询凭据信息
					
					$this->data ['state'] = 3;
					$where_credentials = "userid = {$_SESSION['student'] ['userinfo'] ['id']} AND orderid = '{$orderid}'";
					$result = $this->pay_model->get_credentials ( $where_credentials );
					
					if (! empty ( $result )) {
						$this->pay_model->save_credentials ( $where_credentials, $this->data );
					} else {
						$this->data ['ordernumber'] = $this->order_info ['ordernumber'];
						$this->data ['orderid'] = $this->order_info ['id'];
						$this->data ['amount'] = $this->order_info ['ordermondey'];
						$this->data ['item'] = 3;
						$this->data ['currency'] = 1;
						$this->data ['ordertype'] = 3;
						$this->data ['createtime'] = time ();
						$this->data ['remit_name'] = $submit_data['remit_name'];
						$this->data ['remit_nationality'] = $submit_data['remit_nationality'];
						$this->data ['remit_money'] = $submit_data['remit_money'];
						$this->data ['student_name'] = $submit_data['student_name'];
						$this->data ['remit_remark'] = $submit_data['remit_remark'];
						$this->data ['userid'] = $_SESSION ['student'] ['userinfo'] ['id'];
						
						$flag = $this->pay_model->save_credentials ( null, $this->data );
						
						//auth zyj 同时更新 申请表的 信息
						$this->db->update('pickup_info',array('paystate' => 3,'paytime' =>time(),'paytype' => 3,'isproof' => 3),"userid = {$_SESSION['student'] ['userinfo'] ['id']} AND order_id = {$this->order_info ['id']}");
					}
					
					return true;
					break;
				case 'Accommodation' :
					// 修改订单表
					$dataOrder = array (
							'lasttime' => time (),
							'paytype' => 3,
							'paystate' => 3,
							'paytime' => time () 
					);
					$this->pay_model->save_apply_order_info ( $where_order, $dataOrder );
					$ids = $this->order_info ['applyid'];
					// 修改申请表
					$where_apply = "userid = {$_SESSION['student'] ['userinfo'] ['id']} AND order_id = '{$orderid}'";
					$dataApply = array (
							'paystate' => 3,
							'paytime' => time (),
							'paytype' => 3,
							'isproof' => 1,
							'lasttime' => time () 
					);
					
					$this->apply_pa_model->save_apply_info ( $where_apply, $dataApply, $this->table [$flag_type] );

					//更新收支表里
					$acc_budgetData=array(
							'paystate'=>3,
							'paytime'=>time(),
							'paytype'=>3,
							'lasttime'=>time()
						);
					$this->apply_pa_model->save_apply_info ( 'id = '.$this->order_info['budget_id'], $acc_budgetData, 'budget' );

					//更新申请表状态
					
					// 查询凭据信息
					
					$this->data ['state'] = 3;
					$where_credentials = "userid = {$_SESSION['student'] ['userinfo'] ['id']} AND orderid = '{$orderid}'";
					$result = $this->pay_model->get_credentials ( $where_credentials );
					$acc_info=$this->db->where('order_id' ,$orderid)->get('accommodation_info')->row_array();
					//更新住宿押金表
					//查询是否有押金的订单
					$pledge_info=$this->db->where('acc_id',$acc_info['id'])->get('acc_pledge_info')->row_array();
					if(!empty($pledge_info)){
						//开始更新押金表的订单
						// 修改订单表
						$pledgeOrder = array (
								'lasttime' => time (),
								'paytype' => 3,
								'paystate' => 3,
								'paytime' => time () 
						);
						$this->pay_model->save_apply_order_info ( 'id = '.$pledge_info['order_id'], $pledgeOrder );
						// 修改申请表
						$pledgeApply = array (
								'state' => 3,
								'paytime' => time (),
								'isproof' => 1
						);

						$this->apply_pa_model->save_apply_info ( 'id = '.$pledge_info['id'], $pledgeApply, $this->table ['10'] );
						//更新住宿押金收支表
						$pledge_order_info=$this->pay_model->get_apply_order_info('id = '.$pledge_info['order_id']);
						//更新收支表里
						$pledge_budgetData=array(
								'paystate'=>3,
								'paytime'=>time(),
								'paytype'=>3,
								'lasttime'=>time()
							);
						$this->apply_pa_model->save_apply_info ( 'id = '.$pledge_order_info[0]['budget_id'], $pledge_budgetData, 'budget' );
					}
					if (! empty ( $result )) {
						$this->pay_model->save_credentials ( $where_credentials, $this->data );
					} else {
						//获取该订单的信息
						
						$this->data ['ordernumber'] = $this->order_info ['ordernumber'];
						$this->data ['orderid'] = $this->order_info ['id'];
						$this->data ['amount'] = $acc_info ['registeration_fee']*$acc_info ['accendtime'];

						//查看押金有没有开
						//判断用不用交住宿押金   如果用就把住宿押金交上
						$is_yajin=CF('acc_pledge','',CONFIG_PATH);
						// var_dump($is_yajin);exit;
						if($is_yajin['acc_pledge']=='yes'){
							
							if ($is_yajin ['acc_pledgeway'] == 'acc_pledgermb') {
								$this->data ['amount'] =$this->data ['amount']+$is_yajin['acc_pledgemoney'] ;
							}
							if ($is_yajin ['acc_pledgeway']  == 'acc_pledgeusd') {
								$pledge_money= ceil ( $is_yajin['acc_pledgemoney'] * get_rate ( 'USD', 'CNY' ) );
								$this->data ['amount']=$this->data ['amount']+$pledge_money  ;
							}
							
						}
						$this->data ['item'] = 4;
						$this->data ['currency'] = 1;
						$this->data ['createtime'] = time ();
						$this->data ['ordertype'] = 4;
						$this->data ['remit_name'] = $submit_data['remit_name'];
						$this->data ['remit_nationality'] = $submit_data['remit_nationality'];
						$this->data ['remit_money'] = $submit_data['remit_money'];
						$this->data ['remit_remark'] = $submit_data['remit_remark'];
						$this->data ['student_name'] = $submit_data['student_name'];
						$this->data ['userid']=$_SESSION['student'] ['userinfo'] ['id'];
						
						$flag = $this->pay_model->save_credentials ( null, $this->data );
                        if(!empty($flag)){
                            //更新房间的的预定人数
                            //查询寻订单的该房间
                           $acc_info= $this->pay_model->get_acc_info($where_apply);
                            if(!empty($acc_info['roomid'])){
                                $acc_where='paystate = 3 AND roomid ='.$acc_info['roomid'];
                                $count=$this->db->select('count(*) as num')->where($acc_where)->get('accommodation_info')->row_array();
                                //更新房间的预定人数
                                $this->db->update('school_accommodation_prices',array('in_user_num'=>$count['num']),'id = '.$acc_info['roomid']);
                            }
                        }
					}
					// 更新学生的房间预订人数
					$this->grf_update_room();
					return true;
					break;
				case 'Tuition fees' :
					// 修改订单表
					$dataOrder = array (
							'lasttime' => time (),
							'paytype' => 3,
							'paystate' => 3,
							'paytime' => time () 
					);
					$this->pay_model->save_apply_order_info ( $where_order, $dataOrder );
					
					// 修改申请表
					$where_apply = "userid = {$_SESSION['student'] ['userinfo'] ['id']} AND order_id = {$orderid}";
					
					$dataApply = array (
							'paystate' => 3,
							'paytime' => time (),
							'paytype' => 3,
							'isproof' => 1,
							'lasttime' => time () 
					);
					
					$this->apply_pa_model->save_apply_info ( $where_apply, $dataApply, $this->table [$flag_type] );
					//更新收支表里
					$tuition_budgetData=array(
							'paystate'=>3,
							'paytime'=>time(),
							'paytype'=>3,
							'lasttime'=>time()
						);
					$this->apply_pa_model->save_apply_info ( 'id = '.$this->order_info['budget_id'], $tuition_budgetData, 'budget' );
					// 查询凭据信息
					
					$this->data ['state'] = 3;
					$this->data ['remit_name'] = $submit_data['remit_name'];
					$this->data ['remit_nationality'] = $submit_data['remit_nationality'];
					$this->data ['remit_money'] = $submit_data['remit_money'];
					$this->data ['student_name'] = $submit_data['student_name'];
					$this->data ['remit_remark'] = $submit_data['remit_remark'];
					$where_credentials = "userid = {$_SESSION['student'] ['userinfo'] ['id']} AND orderid = '{$orderid}' AND ordertype = {$flag_type}";
					$result = $this->pay_model->get_credentials ( $where_credentials );
					if (! empty ( $result )) {
						$this->pay_model->save_credentials ( $where_credentials, $this->data );
					} else {
						$this->data ['ordernumber'] = $this->order_info ['ordernumber'];
						$this->data ['orderid'] = $this->order_info ['id'];
						$this->data ['amount'] = $this->order_info ['ordermondey'];
						$this->data ['item'] = 6;
						$this->data ['currency'] = 1;
						$this->data ['createtime'] = time ();
						$this->data ['ordertype'] = 6;
						$this->data ['userid'] = $_SESSION ['student'] ['userinfo'] ['id'];
						
						$this->data ['userid']=$_SESSION['student'] ['userinfo'] ['id'];
						$flag = $this->pay_model->save_credentials ( null, $this->data );
					}
					
					return true;
					break;
				case 'Deposit' :
					// 修改订单表
					$dataOrder = array (
							'lasttime' => time (),
							'paytype' => 3,
							'paystate' => 3,
							'paytime' => time () 
					);
					$this->pay_model->save_apply_order_info ( $where_order, $dataOrder );
					
					// 修改申请表
					$applyData=array(
							'deposit_state'=>3,
							'deposit_time'=>time(),
							'deposit_type'=>3,
							'deposit_fee'=>$this->order_info['ordermondey']
						);
					$this->db->update('apply_info',$applyData,' id = '.$this->order_info['applyid']);
					$where_apply = "userid = {$_SESSION['student'] ['userinfo'] ['id']} AND order_id = {$orderid}";
					
					$dataApply = array (
							'paystate' => 3,
							'paytime' => time (),
							'paytype' => 3,
							'isproof' => 1,
							'lasttime' => time () 
					);
					
					$this->apply_pa_model->save_apply_info ( $where_apply, $dataApply, $this->table [$flag_type] );
					//更新收支表里
					$tuition_budgetData=array(
							'paystate'=>3,
							'paytime'=>time(),
							'paytype'=>3,
							'lasttime'=>time()
						);
					$this->apply_pa_model->save_apply_info ( 'id = '.$this->order_info['budget_id'], $tuition_budgetData, 'budget' );
					// 查询凭据信息
					
					$this->data ['state'] = 3;
					$where_credentials = "userid = {$_SESSION['student'] ['userinfo'] ['id']} AND orderid = '{$orderid}' AND ordertype = {$flag_type}";
					$result = $this->pay_model->get_credentials ( $where_credentials );
					if (! empty ( $result )) {
						$this->data ['ordernumber'] = $this->order_info ['ordernumber'];
						$this->data ['orderid'] = $this->order_info ['id'];
						$this->data ['amount'] = $this->order_info ['ordermondey'];
						$this->data ['item'] = 5;
						$this->data ['currency'] = 1;
						$this->data ['createtime'] = time ();
						$this->data ['ordertype'] = 5;
						$this->data ['userid'] = $_SESSION ['student'] ['userinfo'] ['id'];
						$this->data ['remit_name'] = $submit_data['remit_name'];
						$this->data ['remit_nationality'] = $submit_data['remit_nationality'];
						$this->data ['remit_money'] = $submit_data['remit_money'];
						$this->data ['remit_money'] = $submit_data['remit_money'];
						$this->data ['remit_remark'] = $submit_data['remit_remark'];
						$this->data ['userid']=$_SESSION['student'] ['userinfo'] ['id'];
						$this->pay_model->save_credentials ( $where_credentials, $this->data );
					} else {
						$this->data ['ordernumber'] = $this->order_info ['ordernumber'];
						$this->data ['orderid'] = $this->order_info ['id'];
						$this->data ['amount'] = $this->order_info ['ordermondey'];
						$this->data ['item'] = 5;
						$this->data ['currency'] = 1;
						$this->data ['createtime'] = time ();
						$this->data ['ordertype'] = 5;
						$this->data ['userid'] = $_SESSION ['student'] ['userinfo'] ['id'];
						$this->data ['remit_name'] = $submit_data['remit_name'];
						$this->data ['remit_nationality'] = $submit_data['remit_nationality'];
						$this->data ['remit_money'] = $submit_data['remit_money'];
						$this->data ['student_name'] = $submit_data['student_name'];
						$this->data ['remit_remark'] = $submit_data['remit_remark'];
						$this->data ['userid']=$_SESSION['student'] ['userinfo'] ['id'];
						$flag = $this->pay_model->save_credentials ( null, $this->data );
					}
					
					return true;
					break;
				default :
					break;
			}
		}
		return false;
	}
	
	/**
	 * 上传方法
	 */
	private function _do_upload() {
		$path = 'pay/' . date ( 'Y' ) . '/' . date ( 'md' ) . '/';
		$config ['upload_path'] = UPLOADS . $path;
		$config ['allowed_types'] = 'gif|jpg|png';
		$config ['max_size'] = '4086';
		$config ['encrypt_name'] = TRUE;
		
		mk_dir ( $config ['upload_path'] );
		
		$this->load->library ( 'upload', $config );
		if (! $this->upload->do_upload ( 'imgfile' )) {
			ajaxReturn ( '', $this->upload->display_errors ( '', '' ), 0 );
		} else {
			$data = $this->upload->data ();
			return '/uploads/' . $path . $data ['file_name'];
		}
	}
}