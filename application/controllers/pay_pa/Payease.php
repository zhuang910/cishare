<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Payease extends Student_Basic {
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'home/pay_model' );
		$this->load->model ( 'home/apply_model' );
		$this->load->model ( 'student/student_model' );
		$this->load->model ( 'home/course_model' );
		$this->load->library ( 'sdyinc_email' );
	}
	/**
	 * 北信支付
	 *
	 * 首易信
	 */
	function index($data = array()) {
		file_put_contents ( CACHE_PATH . 'payease_sdyinc2014.log', date ( 'Y-m-d H:i:s' ) . "\t" . $data ['v_oid'] . "\t" . $data ['v_pstatus'] . "\r\n", FILE_APPEND );
		$v_oid = $data ['v_oid']; // 支付提交时的订单编号，此时返回
		$v_pstatus = $data ['v_pstatus']; // 1.待处理。一般是指非实时的银行处理结果。现在很少见了。国内的一般电话银行会产生这个值，国外的是AE会有此情况产生。
		                                  // 20.支付成功
		                                  // 30 支付失败
		$v_pstring = urldecode ( $data ['v_pstring'] ); // 支付结果信息返回。当v_pstatus=1时-已提交。20-支付完成。30-支付失败
		$v_pmode = urldecode ( $data ['v_pmode'] ); // 支付方式。
		                                            // $v_pstring = iconv("GB2312", "UTF-8",urldecode($_GET['v_pstring']));
		                                            // $v_pmode = iconv("GB2312", "UTF-8",urldecode($_GET['v_pmode']));
		$v_amount = $data ['v_amount']; // 订单金额
		$v_moneytype = $data ['v_moneytype']; // 币种
		$v_md5info = $data ['v_md5info'];
		$v_md5money = $data ['v_md5money'];
		$v_sign = $data ['v_sign'];
		
		// MD5校验
		$MD5Key = "cucaschiwestucas"; // 签约后更改为，双方约定的密钥
		$source1 = $v_oid . $v_pstatus . $v_pstring . $v_pmode;
		$md5info = bin2hex ( mhash ( MHASH_MD5, $source1, $MD5Key ) );
		
		$source2 = $v_amount . $v_moneytype;
		$md5money = bin2hex ( mhash ( MHASH_MD5, $source2, $MD5Key ) );
		
		// 是否合法 开始
		if ($md5info != $v_md5info or $md5money != $v_md5money) {
			echo ("error");
		} else {
			$web_email = array (
					// 支付成功
					'pay_success_email' => array (
							'title' => "Payment received" 
					),
					// 支付失败
					'pay_fail_email' => array (
							'title' => "Payment failed" 
					) 
			);
			$userinfo = array ();
			// 验证是否存在记录
			// 分割订单号 因为订单号的形式是：20140903-6970-SDYIE90311310086
			$arr = explode ( '-', $v_oid );
			$item_number = $arr [2];
			// 查询订单信息
			$where_order = "ordernumber = '{$item_number}'";
			$order_infos = $this->pay_model->get_apply_order_info ( $where_order );
			$order_info = $order_infos [0];
			
			$time = time ();
			if (! empty ( $item_number )) {
				
				$pay_data = array (
						'method' => 2, // 1paypal 2 payease
						'type' => 1, // online
						'money' => $v_amount,
						'createtime' => $time 
				);
				
				$payease_data = array (
						"orderid" => $order_info ['id'],
						"state" => $v_pstatus,
						'ordernumber' => $item_number,
						"createtime" => $time,
						'subordernumber' => $v_oid 
				);
				
				$flag = null;
				
				if ($v_pstatus == 20) {
					$pay_data ['state'] = 1;
					$flag = 'Successful Payment';
				} else {
					$pay_data ['state'] = 2;
					$flag = 'Pay for failure';
				}
				switch ($order_info ['ordertype']) {
					case 1 : // 申请
					         // 修改订单表
						$dataOrder = array (
								'lasttime' => time (),
								'paytype' => 2,
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
								'paytype' => 2,
								'lasttime' => time () 
						);
						
						$this->apply_model->save_apply_info ( $where_apply, $dataApply );
						
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
						$title = $web_email [$template] ['title'];
						// 得到课程信息
						
						$apply_infos = $this->apply_model->get_apply_info ( $where_apply );
						$courses = $this->course_model->get_one_content ( 'majorid = ' . $apply_infos ['courseid'] . ' AND site_language = ' . $this->where_lang );
						
						$name = $courses->langname;
						
						// $content = $this->load->view ( 'student/email/' . $template, array (
						// 'title' => $title,
						// 'email' => $userinfo [0] ['email'],
						// 'usd' => $source2,
						// 'name' => ! empty ( $name ) ? $name : ''
						// ), true );
						// $this->_send_email ( $userinfo [0] ['email'], $title, $content );
						$val_arr = array (
								'email' => $userinfo [0] ['email'],
								'usd' => $source2,
								'name' => ! empty ( $name ) ? $name : '' 
						);
						$MAIL = new sdyinc_email ();
						$MAIL->dot_send_mail ( 7, $userinfo [0] ['email'], $val_arr );
						break;
					case 2 : // 代付费
						
						break;
					case 3 : // 接机
						
						break;
					case 4 : // 住宿
						
						break;
					case 5 : // 地址确认
						
						break;
					case 6 : // 其他
						break;
					default :
						exit ();
						break;
				}
				
				$this->db->insert ( 'payease', $payease_data );
			}
			// redirect ( 'http://chinese.bfsu.edu.cn/' . $this->puri . '/student/apply/make_paymeznt?applyid=' . cucas_base64_encode ( $order_info ['applyid'] ) );
		}
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
