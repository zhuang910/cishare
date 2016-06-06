<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 奖学金 前台
 *
 * @author zyj
 *        
 */
class Stustu extends Student_Basic {
	protected $scholarship_on = 0; // 开关
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		is_studentlogin ( $this->re_url );
		// 查询是否 交押金
		// 奖学金开关
		$scholarship_on_off = CF ( 'scholarship', '', CONFIG_PATH );
		if (! empty ( $scholarship_on_off ) && $scholarship_on_off ['scholarship'] == 'yes') {
			$this->scholarship_on = 1;
		}
		// 判断一下是否是 在学学生
		$is_student = $this->db->select ( '*' )->get_where ( 'student', 'state = 1 AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] )->result_array ();
		
		if (empty ( $is_student ) || $this->scholarship_on == 0) {
			echo '<script>window.location.href="/' . $this->puri . '/student/index"</script>';
		}
	}
	
	/**
	 * 主页
	 */
	function index() {
		
		// 先看 自己 所在的专业
		$apply_info = $this->db->select ( 'majorid' )->get_where ( 'student', 'userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] )->row ();
		if (! empty ( $apply_info->majorid )) {
			// 查看专业 所关联的 奖学金
			$scholarshipids = $this->db->select ( 'scholarship' )->get_where ( 'major', 'id = ' . $apply_info->majorid )->row ();
			
			// 新生的奖学金
			if (! empty ( $scholarshipids )&&$scholarshipids->scholarship!=null) {
				$result = $this->db->select ( '*' )->get_where ( 'scholarship_info', 'id > 0 AND state = 1 AND apply_state = 1 AND ischinascholorship = 0 AND id IN (' . $scholarshipids->scholarship . ')' )->result_array ();
			}
		}
		$this->load->view ( 'scholarship/stuscholarship_index', array (
				'result' => ! empty ( $result ) ? $result : array () 
		) );
	}
	
	/**
	 * 我申请的奖学金
	 */
	function myscholarship() {
		// 先去 获得申请表的信息
		$apply_info = $this->db->select ( '*' )->order_by ( 'applytime DESC' )->get_where ( 'applyscholarship_info', ' userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] )->result_array ();
		if (! empty ( $apply_info )) {
			foreach ( $apply_info as $k => $v ) {
				$ids [] = $v ['scholarshipid'];
			}
			
			$resultall = $this->db->select ( '*' )->get_where ( 'scholarship_info', 'id > 0 AND state = 1 AND id IN (' . implode ( ',', $ids ) . ')' )->result_array ();
			foreach ( $resultall as $key => $val ) {
				if ($this->puri == 'en') {
					$result [$val ['id']] = $val ['entitle'];
				} else {
					$result [$val ['id']] = $val ['title'];
				}
			}
		}
		
		$this->load->view ( 'scholarship/stuscholarship_myscholarship', array (
				'result' => ! empty ( $result ) ? $result : array (),
				'apply_info' => ! empty ( $apply_info ) ? $apply_info : array () 
		) );
	}
	
	/**
	 * 奖学金详情
	 */
	function scholarship_detail() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if ($id) {
			$result = $this->db->select ( '*' )->get_where ( 'scholarship_info', 'id = ' . $id . ' AND state = 1 AND ischinascholorship = 0' )->row ();
			$this->load->view ( 'scholarship/stuscholarship_detail', array (
					'result' => ! empty ( $result ) ? ( array ) $result : array () 
			) );
		}
	}
	
	/**
	 * 申请 奖学金
	 */
	function apply() {
		$id = trim ( $this->input->get ( 'id' ) );
		if ($id) {
			$id = cucas_base64_decode ( $id );
			// 判断 申请权限
			$flag = $this->_apply_authority ( $id );
			//学期抓过来
			$term=1;
			$student_info=$this->db->get_where('student','userid = '.$_SESSION ['student'] ['userinfo'] ['id'])->row_array();
			if(!empty($student_info['squadid'])){
				$squad_info=$this->db->get_where('squad','id = '.$student_info['squadid'])->row_array();
				if(!empty($squad_info['nowterm'])){
					$term=$squad_info['nowterm'];
				}
			}	
			switch ($flag) {
				case 0 :
					// 无权限
					$html = $this->load->view ( 'scholarship/stuscholarship_permissions', array (
							'info' => 'CUCAS has to verify your information, documents modification unavailable now. Please try again later.' 
					), true );
					echo $html;
					die ();
					break;
				case 2 :
					// 已经申请了
					echo '<script>window.location.href="/' . $this->puri . '/scholarship/stuscholarship/myscholarship"</script>';
					die ();
					break;
				case 1 :
					// 可以申请
					$max_number = build_order_no ();
					$data = array (
							'number' => $max_number,
							'userid' => $_SESSION ['student'] ['userinfo'] ['id'],
							'scholarshipid' => $id,
							'type' => 1,
							'name' => ! empty ( $_SESSION ['student'] ['userinfo'] ['enname'] ) ? $_SESSION ['student'] ['userinfo'] ['enname'] : '',
							'passport' => ! empty ( $_SESSION ['student'] ['userinfo'] ['passport'] ) ? $_SESSION ['student'] ['userinfo'] ['passport'] : '',
							'email' => ! empty ( $_SESSION ['student'] ['userinfo'] ['email'] ) ? $_SESSION ['student'] ['userinfo'] ['email'] : '',
							'nationality' => ! empty ( $_SESSION ['student'] ['userinfo'] ['nationality'] ) ? $_SESSION ['student'] ['userinfo'] ['nationality'] : '',
							'applytime' => time (),
							'isstart' => 1,
							'isinformation' => 1,
							'isatt' => 1,
							'issubmit' => 1,
							'state' => 0,
							'lasttime' => time () ,
							'term'=>$term
					);
					if (! empty ( $data )) {
						$this->db->insert ( 'applyscholarship_info', $data );
						echo '<script>window.location.href="/' . $this->puri . '/scholarship/stuscholarship/myscholarship"</script>';
						die ();
					}
					break;
			}
		} else {
			// 无权限
			$html = $this->load->view ( 'scholarship/stuscholarship_permissions', array (
					'info' => 'ZUST has to verify your information, documents modification unavailable now. Please try again later.' 
			), true );
			echo $html;
			die ();
		}
	}
	
	/**
	 * 判断权限
	 */
	function _apply_authority($id = null) {
		if ($id != null) {
			// 查看信息
			$result = $this->db->select ( '*' )->get_where ( 'scholarship_info', 'id = ' . $id . ' AND state = 1 AND apply_state = 1 AND ischinascholorship = 0' )->row ();
			if (! empty ( $result )) {
				// 查看 是否 关联了 专业
				// 先看 自己 所在的专业
				$apply_info = $this->db->select ( 'majorid' )->get_where ( 'student', 'userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] )->row ();
				if (! empty ( $apply_info->majorid )) {
					// 查看专业 所关联的 奖学金
					$scholarshipids = $this->db->select ( 'scholarship' )->get_where ( 'major', 'id = ' . $apply_info->majorid )->row ();
					
					// 新生的奖学金
					if (! empty ( $scholarshipids )) {
						$result = $this->db->select ( '*' )->get_where ( 'scholarship_info', 'id > 0 AND state = 1 AND apply_state = 1 AND ischinascholorship = 0 AND id IN (' . $scholarshipids->scholarship . ')' )->result_array ();
						if (! empty ( $result )) {
							foreach ( $result as $k => $v ) {
								$ids [] = $v ['id'];
							}
							if (in_array ( $id, $ids )) {
								// 专业 与 奖学金 关联了
								// 判断是否申请过
								$result = $this->db->select ( '*' )->get_where ( 'applyscholarship_info', 'userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] . ' AND scholarshipid = ' . $id . ' AND type = 1' )->result_array ();
								if (! empty ( $result )) {
									return 2;
								} else {
									return 1;
								}
							} else {
								return 0;
							}
						} else {
							return 0;
						}
					} else {
						return 0;
					}
				} else {
					return 0;
				}
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
}
