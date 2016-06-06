<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Student_tuition extends Master_Basic {
	protected $_size = 3;
	protected $_count = 0;
	protected $_countpage = 0;
	protected $_total = 0;
	
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/student/';
		$this->load->model ( 'student/fee_model' );
		$this->load->model ( 'master/charge/pay_model' );
		$this->load->library ( 'sdyinc_email' );
		$this->load->model('master/major/major_model');
	}
	
	/**
	 * 弹框
	 * 发送交费 通知
	 */
	function tuition_notice() {
		$this->statistics ();
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
		$content = $this->input->post ( 'content' );
		
		if ($count > 0) {
			$studentAll = array (); // 所有的在学的学生
			$studentzl = array (); // 整理好的 学生 用id 指向内容
			                       // 第一步 查找 所有的 在学的学生
			$data = array (); // 组织 学费表的数据
			$studentids = array (); // 学生的id
			$majorfees = array (); // 专业的学费
			$major_term_tuition=array();//array[专业id][学期][学费] 
			$majorfees = $this->_get_major_fees ();
			
			// 学生
			$studentAll = $this->_get_student ();
			// 学期与班级的关系
			$classxq = $this->_get_class_newterm ();
		    $major_term_tuition=$this->_get_major_term_tuition();
			if (! empty ( $studentAll )) {
				foreach ( $studentAll as $k => $v ) {
					$studentzl [$v ['id']] = $v;
					$studentids [] = $v ['id'];
					// 查找班级 如果没有班级 怎认为 该学生是 第一学期 组合数据
					if (empty ( $v ['squadid'] )) {
						// 没有分班 学期为第一学期
						$data ['nowterm'] = 1;
					} else {
						// 分班了 就能找到指定的学期
						$data ['nowterm'] = ! empty ( $classxq [$v ['squadid']] ) ? $classxq [$v ['squadid']] : '';
					}
				
					// 先查所在专业 如果 所在专业为空 再查 报名专业
					if (empty ( $v ['majorid'] )) {
						$majorxuefei = $v ['major'];
					} else {
						$majorxuefei = $v ['majorid'];
					}
					//专业名
					$major_info = $this->major_model->get_one('id = '.$majorxuefei);
					
					
					// 向收费表中 查找数据
					$flag = $this->db->select ( '*' )->get_where ( 'tuition_info', 'userid = ' . $v ['userid'] . ' AND nowterm = ' . $data ['nowterm'] .' AND paystate = 1')->result_array();
					$now_tuition['tuition']=! empty ( $major_term_tuition[$majorxuefei][$data ['nowterm']] ) ? $major_term_tuition[$majorxuefei][$data ['nowterm']] : '';
					$is=0;
					$before_tuition=0;
					if(!empty($flag)){
						foreach ($flag as $kk => $vv) {
							$before_tuition+=$vv['tuition'];
						}
					}
					$surplus_tuition=$now_tuition['tuition']-$before_tuition;

					if($surplus_tuition>0){
						$is=1;
					}
					if($is==0){
						continue;
					}
					$flags= $this->db->select ( '*' )->get_where ( 'tuition_info', 'userid = ' . $v ['userid'] . ' AND nowterm = ' . $data ['nowterm'].' AND paystate <> 1' )->row();
					
					// 学费
					$data ['tuition'] = ! empty ( $surplus_tuition ) ? $surplus_tuition : '';
					
					// 其他信息
					$data ['userid'] = $v ['userid'];
					$data ['name'] = ! empty ( $v ['enname'] ) ? $v ['enname'] : '';
					$data ['email'] = ! empty ( $v ['email'] ) ? $v ['email'] : '';
					$data ['nationality'] = ! empty ( $v ['nationality'] ) ? $v ['nationality'] : '';
					$data ['mobile'] = ! empty ( $v ['mobile'] ) ? $v ['mobile'] : '';
					$data ['tel'] = ! empty ( $v ['tel'] ) ? $v ['tel'] : '';
					$data ['passport'] = ! empty ( $v ['passport'] ) ? $v ['passport'] : '';
					$data ['paystate'] = 0;
					$data ['paytime'] = 0;
					$data ['paytype'] = 0;
					$data ['isproof'] = 0;
					$data ['remark'] = '';
					// 有数据的话 看缴费状态 如果 交费了 就不发了 否则 生成新纪录 并发邮件
					$url = 'http://' . $_SERVER ['HTTP_HOST'] . '/en/student/student/tuition';
					// 为空则生成一条新记录 并且发邮件
					$t =array(
							'1' => '1st Semester',
							'2' => '2nd Semester',
							'3' => '3rd Semester',
							'4' => '4th Semester',
							'5' => '5th Semester',
							'6' => '6th Semester',
							'7' => '7th Semester',
							'8' => '8th Semester',
							'9' => '9th Semester',
							'10' => '10th Semester',
					);
					$val_arr = array (
							'tuition' => $data ['tuition'],
							'email' => $data ['email'],
							'url' => $url,
							'content' => ! empty ( $content ) ? $content : '' ,
							'nowterm' => $t[$data ['nowterm']],
							'major_name' => $major_info->englishname
					);
					
					if (empty($flags)&&$is==1) {

						//查询这个学期有没有重修费
						$chongxiufei=$this->db->get_where('student_rebuild','userid = '.$data['userid'].' AND term = '.$data['nowterm'].' AND state = 0')->result_array();
						if(!empty($chongxiufei)){
							foreach ($chongxiufei as $kkk => $vvv) {
								$data['tuition']+=$vvv['money'];
							}
						}
						//查询有这个学期有没有换证费
						$huanzhengfei=$this->db->get_where('student_barter_card','userid = '.$data['userid'].' AND term = '.$data['nowterm'].' AND state = 0')->result_array();
						//查看有没有换证费
						if(!empty($huanzhengfei)){
							foreach ($huanzhengfei as $kkkk => $vvvv) {
								$data['tuition']+=$vvvv['money'];
							}
						}
						$data ['number'] = build_order_no ();
						$data ['createtime'] = time ();
						//插入收支收支表
						$budget=array(
						'userid'=>$data['userid'],
						'budget_type'=>1,
						'type'=>6,
						'payable'=>$data ['tuition'],
						'paystate'=>0,
						'createtime'=>time(),
						'term'=>$data ['nowterm']
						);
						$budgetid=$this->fee_model->insert_budget($budget);
						$max_cucasid = build_order_no ();
						//再生成所有订单表
						$order_info=array(
								'budget_id'=>$budgetid,
								'createtime'=>time(),
								'ordernumber'=>'ZUST'.$max_cucasid,
								'ordertype'=>6,
								'userid'=>$data['userid'],
								'ordermondey'=>$data ['tuition'],
								'paystate'=>0

							);
						$orderid=$this->fee_model->insert_order($order_info);
						$data['order_id']=$orderid;
						$tuition_id = $this->insert_tuition($data);
						//更新申请减免学费表
						if(!empty($shenqingjianmian)){
							$this->db->update('apply_remission_tuition',array('tuitionid'=>$tuition_id),'id ='.$shenqingjianmian['id']);
						}
						//更新重修费表
						if(!empty($chongxiufei)){
							foreach ($chongxiufei as $kc => $vc) {
								$this->db->update('student_rebuild',array('tuitionid'=>$tuition_id),'id = '.$vc['id']);
							}
						}
						//更新换证费
						if(!empty($huanzhengfei)){
							foreach ($huanzhengfei as $kz => $vz) {
								$this->db->update('student_barter_card',array('tuitionid'=>$tuition_id),'id = '.$vz['id']);
							}
						}
						if ($tuition_id) {
							$this->_total = $this->_total + 1;
							$MAIL = new sdyinc_email ();
							$MAIL->dot_send_mail ( 26, $data ['email'], $val_arr );
						}
					} else {
						// 有记录 看是否成功支付 如果没有支付成功 发邮件 提醒
							if($is==1){
								// $data['number'] = build_order_no ();
								// $data['createtime'] = time();
								// $tuition_id = $this->db->insert ( 'tuition_info', $data );
								// if ($tuition_id) {
								$this->_total = $this->_total + 1;
								$MAIL = new sdyinc_email ();
								$MAIL->dot_send_mail ( 26, $data ['email'], $val_arr );
							}
							
				}
				}
				ajaxReturn ( $this->_total, round ( $page / $countpage * 100, 2 ), 1 );
			}
		}
	}
	/**
	 * [insert_tuition 插入学费]
	 * @return [type] [description]
	 */
	function insert_tuition($data){
		if(!empty($data)){
			$this->db->insert('tuition_info',$data);
			return $this->db->insert_id();
		}
		return 0;
	}
	/**
	 * [_get_major_term_tuition 获取专业每学期的学费]
	 * @return [type] [description]
	 */
	function _get_major_term_tuition(){
		$data=array();
		$majorAll = $this->db->select ( '*' )->get_where ( 'major', 'id > 0' )->result_array ();
		if(!empty($majorAll)){
			foreach ($majorAll as $k => $v) {
				$major_term=$this->db->select ( '*' )->get_where ( 'major_tuition', 'majorid = '.$v['id'] )->result_array ();
				if(!empty($major_term)){
					foreach ($major_term as $k => $v) {
						$data[$v['majorid']][$v['term']]=$v['tuition'];
					}
				}
			}
			if(!empty($data)){
				return $data;
			}
		}
		return $data;
	}
	// /**
	//  * 基数按数量
	//  */
	// function count_tuition_notice() {
	// 	$studentAll = array (); // 所有的在学的学生
	// 	$studentzl = array (); // 整理好的 学生 用id 指向内容
	// 	                       // 第一步 查找 所有的 在学的学生
	// 	$data = array (); // 组织 学费表的数据
	// 	$studentids = array (); // 学生的id
	// 	$majorfees = array (); // 专业的学费
	// 	                       // 专业 和 学费
	// 	$majorfees = $this->_get_major_fees ();
		
	// 	// 学生
	// 	$studentAll = $this->_get_student ();
	// 	// 学期与班级的关系
	// 	$classxq = $this->_get_class_newterm ();
	// 	if (! empty ( $studentAll )) {
	// 		foreach ( $studentAll as $k => $v ) {
	// 			$studentzl [$v ['id']] = $v;
	// 			$studentids [] = $v ['id'];
	// 			// 查找班级 如果没有班级 怎认为 该学生是 第一学期 组合数据
	// 			if (empty ( $v ['squadid'] )) {
	// 				// 没有分班 学期为第一学期
	// 				$data [$v ['id']] ['nowterm'] = 1;
	// 			} else {
	// 				// 分班了 就能找到指定的学期
	// 				$data [$v ['id']] ['nowterm'] = ! empty ( $classxq [$v ['squadid']] ) ? $classxq [$v ['squadid']] : '';
	// 			}
	// 			// 先查所在专业 如果 所在专业为空 再查 报名专业
	// 			if (empty ( $v ['majorid'] )) {
	// 				$majorxuefei = $v ['major'];
	// 			} else {
	// 				$majorxuefei = $v ['majorid'];
	// 			}
				
	// 			// 学费
	// 			$data [$v ['id']] ['tuition'] = ! empty ( $majorfees [$majorxuefei] ) ? $majorfees [$majorxuefei] : '';
	// 			// 其他信息
	// 			$data [$v ['id']] ['userid'] = $v ['id'];
	// 			$data [$v ['id']] ['name'] = ! empty ( $v ['enname'] ) ? $v ['enname'] : '';
	// 			$data [$v ['id']] ['email'] = ! empty ( $v ['email'] ) ? $v ['email'] : '';
	// 			$data [$v ['id']] ['nationality'] = ! empty ( $v ['nationality'] ) ? $v ['nationality'] : '';
	// 			$data [$v ['id']] ['mobile'] = ! empty ( $v ['mobile'] ) ? $v ['mobile'] : '';
	// 			$data [$v ['id']] ['tel'] = ! empty ( $v ['tel'] ) ? $v ['tel'] : '';
	// 			$data [$v ['id']] ['passport'] = ! empty ( $v ['passport'] ) ? $v ['passport'] : '';
	// 			$data [$v ['id']] ['paystate'] = 0;
	// 			$data [$v ['id']] ['paytime'] = 0;
	// 			$data [$v ['id']] ['paytype'] = 0;
	// 			$data [$v ['id']] ['isproof'] = 0;
	// 			$data [$v ['id']] ['remark'] = '';
	// 		}
	// 		return $data;
	// 	} else {
	// 		return array ();
	// 	}
	// }
	
	/**
	 * 获取所有的学生
	 */
	function _get_student() {
		$studentAll = $this->db->select ( '*' )->get_where ( 'student', 'id > 0 AND majorid != 0 AND major != 0 AND state = 1' )->result_array ();
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
	 * 统计
	 */
	private function statistics() {
		$studentAll = array ();
		$studentAll = $this->db->select ( '*' )->get_where ( 'student', 'id > 0 AND majorid != 0 AND major != 0 AND state = 1' )->result_array ();
		
		$this->_count = count ( $studentAll );
		$this->_countpage = ceil ( $this->_count / $this->_size );
		
	}
}