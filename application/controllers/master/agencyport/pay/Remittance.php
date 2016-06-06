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
	private $userid =0;
	
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		// is_studentlogin ();
		$this->load->model ( 'home/pay_model' );
		$this->load->model ( 'home/apply_model' );
		$this->load->model ( 'home/apply_pa_model' );
		$this->load->model ( 'student/student_model' );

		$time = time ();
		$this->table = array (
				'3' => 'pickup_info',
				'4' => 'accommodation_info',
				'5' => 'deposit_info',
				'6' => 'tuition_info' 
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
		$this->data ['way'] = 1;
		$this->remittance ();
	}
	
	/**
	 * 国外银行汇款
	 */
	function abroad() {
		$this->data ['way'] = 2;
		$this->remittance ();
	}
	
	/**
	 * 国内银行汇款
	 */
	function internal() {
		$this->data ['way'] = 3;
		$this->remittance ();
	}
	
	/**
	 * 凭据支付处理
	 */
	function remittance() {
		if (! empty ( $this->data ['way'] )) {
			$this->data ['file'] = $this->_do_upload ();
			$is = $this->check ();
			if ($is === true) {
				ajaxReturn ( '', lang ( 'pj_success' ), 1 );
			}
		}
		ajaxReturn ( '', 'Error!', 0 );
	}
	
	/**
	 * 验证
	 *
	 * @return boolean
	 */
	function check() {
		// 订单号
		$ordernumber = trim ( $this->input->post ( 'key' ) );
		$orderid = trim ( $this->input->post ( 'orderid' ) );
		$userid = trim ( $this->input->post ( 'userid' ) );
		if (! empty ( $orderid )) {
			$where_order = "id = {$orderid} AND ordernumber = '{$ordernumber}'";
			$order_infos = $this->pay_model->get_apply_order_info ( $where_order );
			// 信息为空的时候返回错误
			if (empty ( $order_infos )) {
				return false;
			}
			// 不是本用户 也返回错误吧
			if ($order_infos [0] ['userid'] != $userid) {
				return false;
			}
			
			$this->order_info = $order_infos [0];
			$flag_type = $this->order_info ['ordertype'];
			$format = $this->ordertype [$this->order_info ['ordertype']] ['name'];
			switch ($format) {

				case 'Transfer' :
					
					break;
				//押金
				case 'Deposit':
					$dataOrder = array (
								'lasttime' => time (),
								'paytype' => 3,
								'paystate' => 5,
								'paytime' => time () 
						);
						$this->pay_model->save_apply_order_info ( $where_order, $dataOrder );
						$ids = $this->order_info ['applyid'];

						// 修改申请表
						$where_apply = "id = {$ids}";
						
						$dataApply = array (
								'paystate' => 5,
								'paytime' => time (),
								'paytype' => 3,
								'lasttime' => time () ,
								'isproof' => 1,
						);
						$this->apply_pa_model->save_apply_info ( $where_apply, $dataApply, $this->table [$flag_type] );
						if ($flag_type == 5) {
							// 查询 押金表
							$deposit = $this->db->select ( '*' )->get_where ( 'deposit_info', 'applyid = ' . $ids )->row ();
							// 更新申请专业表
							$dataZY = array (
									'deposit_state' => 0,
									'deposit_time' => time (),
									'deposit_type' => 3
							);

							$this->db->update ( 'apply_info', $dataZY, 'id = ' . $deposit->applyid );
						}

						// 查询凭据信息
					
						$this->data ['state'] = 5;
						$where_credentials = "userid = {$userid} AND orderid = '{$orderid}'";
						$result = $this->pay_model->get_credentials ( $where_credentials );
						if (! empty ( $result )) {
							$this->pay_model->save_credentials ( $where_credentials, $this->data );
						} else {
							$this->data ['ordernumber'] = $this->order_info ['ordernumber'];
							$this->data ['orderid'] = $this->order_info ['id'];
							$this->data ['amount'] = $this->order_info ['ordermondey'];
							$this->data ['item'] = 5;
							$this->data ['currency'] = 1;
							$this->data ['createtime'] = time ();
							$this->data ['ordertype'] = 5;
							$this->data ['userid'] = $userid;
							
							$flag = $this->pay_model->save_credentials ( null, $this->data );
						}
						
						// // 写入日志文件
						// // $this->pay_model->save_apply_history ( array (
						// // 'userid' => $order_info ['userid'],
						// // 'app_id' => $order_info ['applyid'],
						// // 'action' => $flag,
						// // 'adminid' => 0,
						// // 'createtime' => time ()
						// // ) );
						
						// // 发送邮件
						// $where_user = "id = {$this->order_info['userid']}";
						// $userinfo = $this->student_model->get_info_one ( $where_user );
						// $template = 5 == 1 ? 'pay_success_email' : 'pay_fail_email';
						// $title = $web_email [$template] ['title'];
						// // 得到课程信息
						
						// $apply_infos = $this->apply_model->get_apply_info ( $where_apply );
						
						// $name = 'Deposit';
						
						// // $content = $this->load->view ( 'student/email/' . $template, array (
						// // 'title' => $title,
						// // 'email' => $userinfo [0] ['email'],
						// // 'usd' => $source2,
						// // 'name' => ! empty ( $name ) ? $name : ''
						// // ), true );
						// // $this->_send_email ( $userinfo [0] ['email'], $title, $content );
						// $val_arr = array (
						// 		'email' => $userinfo [0] ['email'],
						// 		'usd' => $source2,
						// 		'name' => ! empty ( $name ) ? $name : '' 
						// );
						// $MAIL = new sdyinc_email ();
						// $MAIL->dot_send_mail ( 7, $userinfo [0] ['email'], $val_arr );
						return true;
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
					$where_apply = "userid = {$userid} AND id = {$ids} AND ordernumber = '{$ordernumber}'";
					
					$dataApply = array (
							'paystate' => 3,
							'paytime' => time (),
							'paytype' => 3,
							'isproof' => 1,
							'lasttime' => time () 
					);
					
					$this->apply_model->save_apply_info ( $where_apply, $dataApply );
					
					// 查询凭据信息
					
					$this->data ['state'] = 3;
					$where_credentials = "userid = {$userid} AND orderid = '{$orderid}'";
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
						$this->data ['userid'] = $userid;
						
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
					$where_apply = "userid = {$userid} AND id = {$ids} AND ordernumber = '{$ordernumber}'";
					
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
					$where_credentials = "userid = {$userid} AND orderid = '{$orderid}'";
					$result = $this->pay_model->get_credentials ( $where_credentials );
					if (! empty ( $result )) {
						$this->pay_model->save_credentials ( $where_credentials, $this->data );
					} else {
						$this->data ['ordernumber'] = $this->order_info ['ordernumber'];
						$this->data ['orderid'] = $this->order_info ['id'];
						$this->data ['amount'] = $this->order_info ['ordermondey'];
						$this->data ['item'] = 1;
						$this->data ['currency'] = 1;
						$this->data ['ordertype'] = 3;
						$this->data ['createtime'] = time ();
						$this->data ['userid'] = $userid;
						
						$flag = $this->pay_model->save_credentials ( null, $this->data );
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
					$where_apply = "userid = {$userid} AND id = {$ids} AND ordernumber = '{$ordernumber}'";
					
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
						$this->data ['item'] = 1;
						$this->data ['currency'] = 1;
						$this->data ['createtime'] = time ();
						$this->data ['ordertype'] = 4;
						$this->data ['userid'] = $_SESSION ['student'] ['userinfo'] ['id'];
						
						$flag = $this->pay_model->save_credentials ( null, $this->data );
					}
					
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
					
					$ids = $this->order_info ['applyid'];
					// 修改申请表
					$where_apply = "userid = {$userid} AND id = {$ids} AND ordernumber = '{$ordernumber}'";
					
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
					$where_credentials = "userid = {$userid} AND orderid = '{$orderid}' AND ordertype = {$flag_type}";
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
						$this->data ['ordertype'] = 6;
						$this->data ['userid'] = $userid;
						
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