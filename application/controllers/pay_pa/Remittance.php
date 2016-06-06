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
	
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		is_studentlogin ();
		$this->load->model ( 'home/pay_model' );
		$this->load->model ( 'home/apply_model' );
		$time = time ();
		
		$this->data ['updatetime'] = $time;
		$publics = CF ( 'publics', '', CONFIG_PATH );
		$this->ordertype = $publics ['ordertype'];
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
						$this->data ['userid'] = $_SESSION ['student'] ['userinfo'] ['id'];
						
						$flag = $this->pay_model->save_credentials ( null, $this->data );
					}
					
					return true;
					break;
				case 'Pick Up' :
					
					break;
				case 'Accommodation' :
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