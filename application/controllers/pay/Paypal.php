<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 支付
 *
 * @author junjiezhang
 *        
 */
class Paypal extends Student_Basic {
	protected $table = array ();
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'home/pay_model' );
		$this->load->model ( 'home/apply_model' );
		$this->load->model ( 'home/apply_pa_model' );
		$this->load->model ( 'student/student_model' );
		$this->load->model ( 'home/course_model' );
		$this->table = array (
				'3' => 'pickup_info',
				'4' => 'accommodation_info',
				'5' => 'deposit_info',
				'6' => 'tuition_info',
				'8'=>'books_fee',
				'14'=>'electric_pledge'
		);
		
		$this->load->library ( 'sdyinc_email' );
	}
	
	/**
	 * 主页面
	 */
	function index() {
		
		$publics = CF ( 'publics', '', CONFIG_PATH );
		$ordertype = $publics ['ordertype'];
		// create log
		$log = fopen ( 'application/cache/' . "ipn2014_test.log", "a" );
		fwrite ( $log, "\n\nipn - " . gmstrftime ( "%b %d %Y %H:%M:%S", time () ) . "\n" );
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		foreach ( $_POST as $key => $value ) {
			$value = urlencode ( stripslashes ( $value ) );
			$req .= "&$key=$value";
		}
		
		// Set whether or not you're in sandbox mode and also whether or not your web server has SSL or not.
		$sandbox = true; // false
		$ssl = false;
		
		// Set end-point based on sandbox value
		if ($sandbox)
			$ppHost = "www.sandbox.paypal.com";
		else
			$ppHost = "www.paypal.com";
			
			// post back to PayPal system to validate
		if ($ssl) {
			$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
			$header .= "Host: " . $ppHost . ":443\r\n";
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header .= "Content-Length: " . strlen ( $req ) . "\r\n\r\n";
			$fp = fsockopen ( 'ssl://' . $ppHost, 443, $errno, $errstr, 30 );
		} else {
			$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
			$header .= "Host: " . $ppHost . ":80\r\n";
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header .= "Content-Length: " . strlen ( $req ) . "\r\n\r\n";
			$fp = fsockopen ( $ppHost, 80, $errno, $errstr, 30 );
		}
		// 获取订单信息 //就能知道 是申请 还是 接机 住宿了
		$ordernumber = $_POST ['item_number'];
		$where_order = "ordernumber = '{$ordernumber}'";
		$order_infos = $this->pay_model->get_apply_order_info ( $where_order );
		$order_info = $order_infos [0];
		// 类型 1 申请 2 代付费 3 接机 4 住宿
		$flag_type = $order_info ['ordertype'];
		// assign posted variables to local variables
		$item_name = $ordertype [$order_info ['ordertype']] ['name']; // 类型 1 申请 2 代付费 3 接机 4 住宿
		$business = $_POST ['business'];
		$item_number = $_POST ['item_number']; // 订单号
		$mc_gross = $_POST ['mc_gross']; // 付费 美元
		$payment_currency = $_POST ['mc_currency'];
		$txn_id = $_POST ['txn_id'];
		$receiver_email = $_POST ['receiver_email'];
		$receiver_id = $_POST ['receiver_id'];
		$quantity = $_POST ['quantity'];
		$num_cart_items = $_POST ['num_cart_items'];
		$payment_date = $_POST ['payment_date'];
		$first_name = $_POST ['first_name'];
		$last_name = $_POST ['last_name'];
		$payment_type = $_POST ['payment_type'];
		$payment_status = $_POST ['payment_status']; // 状态 Completed 成功 Pending 等待 Refunded 退款 Reversed 退款
		$payment_gross = $_POST ['payment_gross'];
		$payment_fee = $_POST ['payment_fee'];
		$settle_amount = $_POST ['settle_amount'];
		$memo = $_POST ['memo'];
		$payer_email = $_POST ['payer_email'];
		$txn_type = $_POST ['txn_type'];
		$payer_status = $_POST ['payer_status'];
		$address_street = $_POST ['address_street'];
		$address_city = $_POST ['address_city'];
		$address_state = $_POST ['address_state'];
		$address_zip = $_POST ['address_zip'];
		$address_country = $_POST ['address_country'];
		$address_status = $_POST ['address_status'];
		// $item_number = $_POST['item_number'];
		$tax = $_POST ['tax'];
		$option_name1 = $_POST ['option_name1'];
		$option_selection1 = $_POST ['option_selection1'];
		$option_name2 = $_POST ['option_name2'];
		$option_selection2 = $_POST ['option_selection2'];
		$for_auction = $_POST ['for_auction'];
		$invoice = $_POST ['invoice'];
		$custom = $_POST ['custom'];
		$notify_version = $_POST ['notify_version'];
		$verify_sign = $_POST ['verify_sign'];
		$payer_business_name = $_POST ['payer_business_name'];
		$payer_id = $_POST ['payer_id'];
		$mc_currency = $_POST ['mc_currency'];
		$mc_fee = $_POST ['mc_fee'];
		$exchange_rate = $_POST ['exchange_rate'];
		$settle_currency = $_POST ['settle_currency'];
		$parent_txn_id = $_POST ['parent_txn_id'];
		$pending_reason = $_POST ['pending_reason'];
		$reason_code = $_POST ['reason_code'];
		
		// auction specific vars
		$for_auction = $_POST ['for_auction'];
		$auction_closing_date = $_POST ['auction_closing_date'];
		$auction_multi_item = $_POST ['auction_multi_item'];
		$auction_buyer_id = $_POST ['auction_buyer_id'];
		
		// write log with vals
		fwrite ( $log, "Vals:" . $receiver_email . "/" . $item_name . "/" . $business . "/" . $item_number . "/" . $quantity . "/" . $payment_status . "/" . $pending_reason . "/" . $payment_date . "/" . $payment_gross . "/" . $payment_fee . "/" . $txn_id . "/" . $txn_type . "/" . $first_name . "/" . $last_name . "/" . $address_street . "/" . $address_city . "/" . $address_state . "/" . $address_zip . "/" . $address_country . "/" . $address_status . "/" . $payer_email . "/" . $payer_status . "/" . $payment_type . "/" . $notify_version . "/" . $verify_sign . "\n" );
		// 申请处理逻辑
		$time = time ();
		$userinfo = array ();
		
		if (! empty ( $item_number )) {
			
			$pay_data = array (
					'method' => 1, // 1paypal
					'type' => 1, // online
					'money' => $payment_gross,
					'createtime' => $time 
			);
			
			$paypal_data = array (
					"paymentstatus" => $payment_status,
					"buyer_email" => $payer_email,
					"firstname" => $first_name,
					"lastname" => $last_name,
					"street" => $address_street,
					"city" => $address_city,
					"state" => $address_state,
					"zipcode" => $address_zip,
					"country" => $address_country,
					"mc_gross" => $mc_gross,
					"mc_fee" => $mc_fee,
					"itemnumber" => $item_number,
					"itemname" => $item_name,
					"os0" => $option_name1,
					"on0" => $option_selection1,
					"os1" => $option_name2,
					"on1" => $option_selection2,
					"quantity" => $quantity,
					"memo" => $memo,
					"paymenttype" => $payment_type,
					"paymentdate" => $payment_date,
					"txnid" => $txn_id,
					"pendingreason" => $pending_reason,
					"reasoncode" => $reason_code,
					"tax" => $tax,
					"datecreation" => $time,
					'ordernumber' => $item_number 
			);
			//$web_email = CF ( 'web_student_email', '', 'application/cache/' );
			$flag = null;
			
			if ($payment_status == 'Completed') {
				$pay_data ['state'] = 1;
				$flag = 'Successful Payment';
			} else if ($payment_status == 'Pending') {
				$pay_data ['state'] = 3;
				$flag = 'Pay for failure';
			} else {
				$pay_data ['state'] = 3;
				$flag = 'Wait for confirmation of payment';
			}
			switch ($order_info ['ordertype']) {
				case 1 : // 申请
				         // 修改订单表
					$dataOrder = array (
							'lasttime' => time (),
							'paytype' => 1,
							'paystate' => $pay_data ['state'],
							'paytime' => time () 
					);

					$this->pay_model->save_apply_order_info ( $where_order, $dataOrder );
					
					$ids = $order_info ['applyid'];
					// 修改申请表
					$where_apply = "id = {$ids}";
					
					$dataApply = array (
							'paystate' => $pay_data ['state'],
							'paytime' => time (),
							'paytype' => 1,
							'lasttime' => time () 
					);
					
					$this->apply_model->save_apply_info ( $where_apply, $dataApply );
					//更新收支表里
					$budget_arr=array(
							'paid_in'=>$order_info['ordermondey'],
							'paystate'=>1,
							'paytime'=>time(),
							'paytype'=>1
						);
					$this->db->update('budget',$budget_arr,'id = '.$order_info['budget_id']);
					// 写入日志文件
					// $this->pay_model->save_apply_history ( array (
					// 'userid' => $order_info ['userid'],
					// 'app_id' => $order_info ['applyid'],
					// 'action' => $flag,
					// 'adminid' => 0,
					// 'createtime' => time ()
					// ) );
					
					// 发送邮件
					$where_user = "id = {$order_info['userid']}";
					$userinfo = $this->student_model->get_info_one ( $where_user );
					$template = $pay_data ['state'] == 1 ? 'pay_success_email' : 'pay_fail_email';
				//	$title = $web_email [$template] ['title'];
					// 得到课程信息
					
					$apply_infos = $this->apply_model->get_apply_info ( $where_apply );
					$courses = $this->course_model->get_one_content ( 'majorid = ' . $apply_infos ['courseid'] . ' AND site_language = ' . $this->where_lang );
					
					$name = $courses->langname;
					
// 					$content = $this->load->view ( 'student/email/' . $template, array (
// 							'title' => $title,
// 							'email' => $userinfo [0] ['email'],
// 							'usd' => $payment_gross,
// 							'name' => ! empty ( $name ) ? $name : '' 
// 					), true );
					//$this->_send_email ( $userinfo [0] ['email'], $title, $content );
					$val_arr = array(
							'email' => $userinfo [0] ['email'],
							'usd' => $payment_gross,
							'name' => ! empty ( $name ) ? $name : '',
							
					);
					$MAIL = new sdyinc_email ();
					$MAIL->dot_send_mail ( 7,$userinfo [0] ['email'],$val_arr);
					
					break;
				case 2 : // 代付费
					
					break;
				case 3 : // 接机
					
					$dataOrder = array (
							'lasttime' => time (),
							'paytype' => 1,
							'paystate' => $pay_data ['state'],
							'paytime' => time () 
					);
					$this->pay_model->save_apply_order_info ( $where_order, $dataOrder );
					
					$ids = $order_info ['applyid'];
					// 修改申请表
					$where_apply = "order_id = {$order_info['id']}";
					
					$dataApply = array (
							'paystate' => $pay_data ['state'],
							'paytime' => time (),
							'paytype' => 1,
							'lasttime' => time () 
					);
					
					$this->apply_pa_model->save_apply_info ( $where_apply, $dataApply, $this->table [$flag_type] );
					//更新收支表里
					$budget_arr=array(
							'paid_in'=>$order_info['ordermondey'],
							'paystate'=>1,
							'paytime'=>time(),
							'paytype'=>1
						);
					$this->db->update('budget',$budget_arr,'id = '.$order_info['budget_id']);
					// 写入日志文件
					// $this->pay_model->save_apply_history ( array (
					// 'userid' => $order_info ['userid'],
					// 'app_id' => $order_info ['applyid'],
					// 'action' => $flag,
					// 'adminid' => 0,
					// 'createtime' => time ()
					// ) );
					// 发送邮件
					$where_user = "id = {$order_info['userid']}";
					$userinfo = $this->student_model->get_info_one ( $where_user );
					$template = $pay_data ['state'] == 1 ? 'pay_success_email' : 'pay_fail_email';
				//	$title = $web_email [$template] ['title'];
					// 得到课程信息
					
					$name = 'Pickup';
					
// 					$content = $this->load->view ( 'student/email/' . $template, array (
// 							'title' => $title,
// 							'email' => $userinfo [0] ['email'],
// 							'usd' => $payment_gross,
// 							'name' => ! empty ( $name ) ? $name : '' 
// 					), true );
					//$this->_send_email ( $userinfo [0] ['email'], $title, $content );
					$val_arr = array(
							'email' => $userinfo [0] ['email'],
							'usd' => $payment_gross,
							'name' => ! empty ( $name ) ? $name : '',
							
					);
					$MAIL = new sdyinc_email ();
					$MAIL->dot_send_mail ( 7,$userinfo [0] ['email'],$val_arr);
					break;
				case 4 : // 住宿
					
					$dataOrder = array (
							'lasttime' => time (),
							'paytype' => 1,
							'paystate' => $pay_data ['state'],
							'paytime' => time () 
					);
					$this->pay_model->save_apply_order_info ( $where_order, $dataOrder );
					
					$ids = $order_info ['applyid'];
					// 修改申请表
					$where_apply = "order_id = {$order_info['id']}";
					
					$dataApply = array (
							'paystate' => $pay_data ['state'],
							'paytime' => time (),
							'paytype' => 1,
							'lasttime' => time () 
					);
					
					$this->apply_pa_model->save_apply_info ( $where_apply, $dataApply, $this->table [$flag_type] );
					//更新收支表里
					$budget_arr=array(
							'paid_in'=>$order_info['ordermondey'],
							'paystate'=>1,
							'paytime'=>time(),
							'paytype'=>1
						);
					$this->db->update('budget',$budget_arr,'id = '.$order_info['budget_id']);
					// 写入日志文件
					// $this->pay_model->save_apply_history ( array (
					// 'userid' => $order_info ['userid'],
					// 'app_id' => $order_info ['applyid'],
					// 'action' => $flag,
					// 'adminid' => 0,
					// 'createtime' => time ()
					// ) );
					//查看有没有住宿押金的订单
					$acc_info=$this->db->get_where('accommodation_info',$where_apply)->row_array();
					$this->db->update('accommodation_info',array('acc_state'=>2),'id = '.$acc_info['id']);
					$acc_pledge=$this->db->get_where('acc_pledge_info','acc_id = '.$acc_info['id'])->row_array();
					if(!empty($acc_pledge)){
						//更新住宿押金呢表
						$acc_pledge_arr=array(
								'state'=>1,
								'paytime'=>time(),
								'isproof'=>0
						);
						$this->db->update('acc_pledge_info',$acc_pledge_arr,'id = '.$acc_pledge['id']);
						//更新订单表
						$acc_dataOrder = array (
							'lasttime' => time (),
							'paytype' => 1,
							'paystate' => $pay_data ['state'],
							'paytime' => time () 
							);
						$this->pay_model->save_apply_order_info ( 'id = '.$acc_pledge['order_id'], $acc_dataOrder );
						//更新收支表o
						$acc_pledge_order=$this->db->get_where('apply_order_info','id = '.$acc_pledge['order_id'])->row_array();
						$acc_budget_arr=array(
							'paid_in'=>$acc_pledge_order['ordermondey'],
							'paystate'=>1,
							'paytime'=>time(),
							'paytype'=>1
						);
						$this->db->update('budget',$acc_budget_arr,'id = '.$acc_pledge_order['budget_id']);
					}
					// 发送邮件
					$where_user = "id = {$order_info['userid']}";
					$userinfo = $this->student_model->get_info_one ( $where_user );
					$template = $pay_data ['state'] == 1 ? 'pay_success_email' : 'pay_fail_email';
					//$title = $web_email [$template] ['title'];
					
					$name = 'Accommodation';
					
// 					$content = $this->load->view ( 'student/email/' . $template, array (
// 							'title' => $title,
// 							'email' => $userinfo [0] ['email'],
// 							'usd' => $payment_gross,
// 							'name' => ! empty ( $name ) ? $name : '' 
// 					), true );
// 					$this->_send_email ( $userinfo [0] ['email'], $title, $content );
					$val_arr = array(
							'email' => $userinfo [0] ['email'],
							'usd' => $payment_gross,
							'name' => ! empty ( $name ) ? $name : '',
					
					);
					$MAIL = new sdyinc_email ();
					$MAIL->dot_send_mail ( 7,$userinfo [0] ['email'],$val_arr);
					break;
				case 5 : // 押金
					
					$dataOrder = array (
							'lasttime' => time (),
							'paytype' => 1,
							'paystate' => $pay_data ['state'],
							'paytime' => time () 
					);
					$this->pay_model->save_apply_order_info ( $where_order, $dataOrder );
					// 订单表中的 申请id 在 押金中是 押金表的id
					$ids = $order_info ['applyid'];
					// 修改申请表
					$where_apply = "id = {$ids}";
					
					$dataApply = array (
							'deposit_state' => $pay_data ['state'],
							'deposit_time' => time (),
							'deposit_type' => 1,
							'deposit_fee' => $order_info['ordermondey']
					);
					
					$this->apply_pa_model->save_apply_info ( $where_apply, $dataApply, 'apply_info' );
					//更新收支表
					//更新收支表里
					$budget_arr=array(
							'paid_in'=>$order_info['ordermondey'],
							'paystate'=>1,
							'paytime'=>time(),
							'paytype'=>1
						);
					$this->db->update('budget',$budget_arr,'id = '.$order_info['budget_id']);
					// 同时 查出 专业申请表的 数据 进行 更新一下 专业申请表中 有关 押金的状态
					
						// 查询 押金表
						$deposit = $this->db->select ( '*' )->get_where ( 'deposit_info', 'applyid = ' . $ids )->row ();
						// 更新申请专业表
						$dataZY = array (
								'paystate' => $pay_data ['state'],
								'paytime' => time (),
								'paytype' => 1 
						);
						$this->db->update ( 'deposit_info', $dataZY, 'id = ' . $deposit->id );
             
					// 写入日志文件
					// $this->pay_model->save_apply_history ( array (
					// 'userid' => $order_info ['userid'],
					// 'app_id' => $order_info ['applyid'],
					// 'action' => $flag,
					// 'adminid' => 0,
					// 'createtime' => time ()
					// ) );
					
					// 发送邮件
					$where_user = "id = {$order_info['userid']}";
					$userinfo = $this->student_model->get_info_one ( $where_user );
					$template = $pay_data ['state'] == 1 ? 'pay_success_email' : 'pay_fail_email';
					//$title = $web_email [$template] ['title'];
					//押金计入学费
					   $tui_dep=CF('tuition','',CONFIG_PATH);
                if(!empty($tui_dep['pledgejiru'])&&$tui_dep['pledgejiru']=='yes'){
                    //开始计入学费1插入收支表2插入学费表
                    //没有收支表插收支表
                    $budget_arr=array(
                            'userid'=>$userinfo [0] ['id'],
                            'budget_type'=>1,
                            'type'=>6,
                            'term'=>1,
                            'payable'=>$order_info['ordermondey'],
                            'paid_in'=>$order_info['ordermondey'],
                            'paystate'=>1,
                            'paytime'=>time(),
                            'paytype'=>3,
                            'createtime'=>time(),
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
                        'userid'=>$userinfo [0] ['id'],
                        'tuition'=>$order_info['ordermondey'],
                        'danwei'=>'rmb',
                        'paystate'=>1,
                        'paytime'=>time(),
                        'paytype'=>8,
                        'createtime'=>time(),
                        'remark'=>'押金计入学费',
                        'lasttime'=>time(),
                        );  
                    $this->db->insert('tuition_info',$tuition_arr);
                    //插入一个退费表
                    //没有收支表插收支表
                    $budget_arr=array(
                            'userid'=>$userinfo [0] ['id'],
                            'budget_type'=>2,
                            'type'=>6,
                            'term'=>1,
                            'should_returned'=>$order_info['ordermondey'],
                            'true_returned'=>$order_info['ordermondey'],
                            'paystate'=>1,
                            'returned_time'=>time(),
                            'paytype'=>3,
                            'createtime'=>time(),
                            'proof_number'=>'',
                            'file_path'=>'',
                            'remark'=>'押金计入学费'
                        );
                    $this->db->insert('budget',$budget_arr);
                              
                }



					$name = 'Deposit';
					
// 					$content = $this->load->view ( 'student/email/' . $template, array (
// 							'title' => $title,
// 							'email' => $userinfo [0] ['email'],
// 							'usd' => $payment_gross,
// 							'name' => ! empty ( $name ) ? $name : '' 
// 					), true );
// 					$this->_send_email ( $userinfo [0] ['email'], $title, $content );
					$val_arr = array(
							'email' => $userinfo [0] ['email'],
							'usd' => $payment_gross,
							'name' => ! empty ( $name ) ? $name : '',
								
					);
					$MAIL = new sdyinc_email ();
					$MAIL->dot_send_mail ( 7,$userinfo [0] ['email'],$val_arr);
					break;
				case 6 : // 学费
					$dataOrder = array (
							'lasttime' => time (),
							'paytype' => 1,
							'paystate' => $pay_data ['state'],
							'paytime' => time () 
					);
					$this->pay_model->save_apply_order_info ( $where_order, $dataOrder );
					
					$ids = $order_info ['applyid'];
					// 修改申请表
					$where_apply = "order_id = {$order_info['id']}";
					
					$dataApply = array (
							'paystate' => $pay_data ['state'],
							'paytime' => time (),
							'paytype' => 1,
							'lasttime' => time () 
					);
					
					$this->apply_pa_model->save_apply_info ( $where_apply, $dataApply, $this->table [$flag_type] );
					//更新收支表
					$budget_arr=array(
							'paid_in'=>$order_info['ordermondey'],
							'paystate'=>1,
							'paytime'=>time(),
							'paytype'=>1
						);
					$this->db->update('budget',$budget_arr,'id = '.$order_info['budget_id']);
					$tuition_info=$this->db->get_where('tuition_info',$where_apply)->row_array();
					if(!empty($tuition_info)){
						//查询有没有重修费
						$rebuild_info=$this->db->get_where('student_rebuild','tuitionid = '.$tuition_info['id'])->result_array();
						
						if(!empty($rebuild_info)){
							foreach ($rebuild_info as $k => $v) {
								//更新重修费表
								$update_rebuild=array(
									'state'=>$pay_data ['state'],
									'paytime'=>time(),
									);
								//持平收费表
								$this->db->update('student_rebuild',$update_rebuild,'id = '.$v['id']);
								//保持平衡
								$tui_budget=array(
									'userid'=>$v['userid'],
									'term'=>$v['term'],
									'budget_type'=>2,
									'type'=>12,
									'should_returned'=>$v['money'],
									'true_returned'=>$v['money'],
									'returned_time'=>time(),
									'createtime'=>time(),
									'adminid'=>$_SESSION['master_user_info']->id
									);
								$this->db->insert('budget',$tui_budget);
								//更新收支表
								$rebuild_budget_arr=array(
									'paid_in'=>$v['money'],
									'paystate'=>1,
									'paytime'=>time(),
									'paytype'=>1
								);
								$this->db->update('budget',$rebuild_budget_arr,'id = '.$v['budgetid']);
							}
						}
						//查询有没有换证费
						$barter_card_info=$this->db->get_where('student_barter_card','tuitionid = '.$tuition_info['id'])->result_array();
						
						if(!empty($barter_card_info)){
							foreach ($barter_card_info as $k => $v) {
								$update_barter_card=array(
									'state'=>$pay_data ['state'],
									'paytime'=>time(),
									);
								$this->db->update('student_barter_card',$update_barter_card,'id = '.$v['id']);
								//更新收支表
								$barter_card_budget_arr=array(
									'paid_in'=>$v['money'],
									'paystate'=>1,
									'paytime'=>time(),
									'paytype'=>1
								);
								$this->db->update('budget',$barter_card_budget_arr,'id = '.$v['budgetid']);
								//保持平衡
								$tui_budget=array(
									'userid'=>$v['userid'],
									'term'=>$v['term'],
									'budget_type'=>2,
									'type'=>11,
									'should_returned'=>$v['money'],
									'true_returned'=>$v['money'],
									'returned_time'=>time(),
									'createtime'=>time(),
									'adminid'=>$_SESSION['master_user_info']->id
									);
								$this->db->insert('budget',$tui_budget);
							}
						}
					}
					
					// 写入日志文件
					// $this->pay_model->save_apply_history ( array (
					// 'userid' => $order_info ['userid'],
					// 'app_id' => $order_info ['applyid'],
					// 'action' => $flag,
					// 'adminid' => 0,
					// 'createtime' => time ()
					// ) );
					
					// 发送邮件
					$where_user = "id = {$order_info['userid']}";
					$userinfo = $this->student_model->get_info_one ( $where_user );
					$template = $pay_data ['state'] == 1 ? 'pay_success_email' : 'pay_fail_email';
					//$title = $web_email [$template] ['title'];
					
					$name = 'Tuition fees';
					
// 					$content = $this->load->view ( 'student/email/' . $template, array (
// 							'title' => $title,
// 							'email' => $userinfo [0] ['email'],
// 							'usd' => $payment_gross,
// 							'name' => ! empty ( $name ) ? $name : '' 
// 					), true );
// 					$this->_send_email ( $userinfo [0] ['email'], $title, $content );
					$val_arr = array(
							'email' => $userinfo [0] ['email'],
							'usd' => $payment_gross,
							'name' => ! empty ( $name ) ? $name : '',
								
					);
					$MAIL = new sdyinc_email ();
					$MAIL->dot_send_mail ( 7,$userinfo [0] ['email'],$val_arr);
					break;
				case 8 :
					$dataOrder = array (
							'lasttime' => time (),
							'paytype' => 1,
							'paystate' => $pay_data ['state'],
							'paytime' => time () 
					);
					$this->pay_model->save_apply_order_info ( $where_order, $dataOrder );
					
					$ids = $order_info ['applyid'];
					// 修改申请表
					$where_apply = "orderid = {$order_info['id']}";
					
					$dataApply = array (
							'paid_in'=>$order_info['ordermondey'],
							'paystate' => $pay_data ['state'],
							'paytime' => time ()
					);
					
					$this->apply_pa_model->save_apply_info ( $where_apply, $dataApply, $this->table [$flag_type] );
					//更新收支表里
					$budget_arr=array(
							'paid_in'=>$order_info['ordermondey'],
							'paystate'=>1,
							'paytime'=>time(),
							'paytype'=>1
						);
					$this->db->update('budget',$budget_arr,'id = '.$order_info['budget_id']);
					
					// 发送邮件
					$where_user = "id = {$order_info['userid']}";
					$userinfo = $this->student_model->get_info_one ( $where_user );
					$template = $pay_data ['state'] == 1 ? 'pay_success_email' : 'pay_fail_email';
					//$title = $web_email [$template] ['title'];
					// 得到课程信息
					
					// $apply_infos = $this->apply_model->get_apply_info ( $where_apply );
					
					$name = 'Book Fee';
					
// 					$content = $this->load->view ( 'student/email/' . $template, array (
// 							'title' => $title,
// 							'email' => $userinfo [0] ['email'],
// 							'usd' => $payment_gross,
// 							'name' => ! empty ( $name ) ? $name : '' 
// 					), true );
// 					$this->_send_email ( $userinfo [0] ['email'], $title, $content );
					$val_arr = array(
							'email' => $userinfo [0] ['email'],
							'usd' => $payment_gross,
							'name' => ! empty ( $name ) ? $name : '',
								
					);
					$MAIL = new sdyinc_email ();
					$MAIL->dot_send_mail ( 7,$userinfo [0] ['email'],$val_arr);
					break;
					case 14 :
					$this->db->insert('course_books',array('courseid'=>8,'booksid'=>88));
					$dataOrder = array (
							'lasttime' => time (),
							'paytype' => 1,
							'paystate' => $pay_data ['state'],
							'paytime' => time () 
					);
					$this->pay_model->save_apply_order_info ( $where_order, $dataOrder );
					// 修改申请表
					$where_apply = "order_id = {$order_info['id']}";
					
					$dataApply = array (
							'paid_in'=>$order_info['ordermondey'],
							'paystate' => $pay_data ['state'],
							'paytime' => time ()
					);
					
					$this->apply_pa_model->save_apply_info ( $where_apply, $dataApply, $this->table [$flag_type] );
					//更新收支表里
					$budget_arr=array(
							'paid_in'=>$order_info['ordermondey'],
							'paystate'=>1,
							'paytime'=>time(),
							'paytype'=>1
						);
					$this->db->update('budget',$budget_arr,'id = '.$order_info['budget_id']);
					
					// 发送邮件
					$where_user = "id = {$order_info['userid']}";
					$userinfo = $this->student_model->get_info_one ( $where_user );
					$template = $pay_data ['state'] == 1 ? 'pay_success_email' : 'pay_fail_email';
					//$title = $web_email [$template] ['title'];
					// 得到课程信息
					
					// $apply_infos = $this->apply_model->get_apply_info ( $where_apply );
					
					$name = 'Electricity Deposit';
					
// 					$content = $this->load->view ( 'student/email/' . $template, array (
// 							'title' => $title,
// 							'email' => $userinfo [0] ['email'],
// 							'usd' => $payment_gross,
// 							'name' => ! empty ( $name ) ? $name : '' 
// 					), true );
// 					$this->_send_email ( $userinfo [0] ['email'], $title, $content );
					$val_arr = array(
							'email' => $userinfo [0] ['email'],
							'usd' => $payment_gross,
							'name' => ! empty ( $name ) ? $name : '',
								
					);
					$MAIL = new sdyinc_email ();
					$MAIL->dot_send_mail ( 7,$userinfo [0] ['email'],$val_arr);
					break;
				default :
					exit ();
					break;
			}
			
			// 插入支付主表
			// $this->pay_model->save_pay($pay_data);
			// 写入paypal表
			$this->pay_model->save_paypal (null, $paypal_data );
		}
		fclose ( $log );
	}
	
	/**
	 * 成功确认
	 */
	function confirm($is = null) {
		if ($is) {
			$this->load->view ( 'pay/success' );
		}
		
	}
	
	/**
	 * 发送email
	 */
	private function _send_email($to = null, $title = null, $content = null) {
		if ($to && $title && $content) {
			$this->load->library ( 'mymail' );
			$MAIL = new Mymail ();
			$MAIL->domail ( $to, $title, $content );
		}
	}
}
