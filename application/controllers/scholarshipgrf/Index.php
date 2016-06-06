<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 奖学金 前台
 *
 * @author zyj
 *        
 */
class Index extends Student_Basic {
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
	}
	
	/**
	 * 主页
	 */
	function index() {
        $apply_id=$this->input->get('applyid');
        
		// 先看自己的 专业
		$apply_info = $this->db->select ( 'courseid' )->order_by ( 'id DESC' )->limit ( 1 )->get_where ( 'apply_info', 'userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] )->row ();
		if (! empty ( $apply_info->courseid )) {
			// 查看专业 所关联的 奖学金
			$scholarshipids = $this->db->select ( 'scholarship' )->get_where ( 'major', 'id = ' . $apply_info->courseid )->row ();
			// 新生的奖学金
			if (! empty ( $scholarshipids )&&$scholarshipids->scholarship!=null) {
				$result = $this->db->select ( '*' )->get_where ( 'scholarship_info', 'id > 0 AND state = 1 AND apply_state = 2 AND ischinascholorship = 0 AND id IN (' . $scholarshipids->scholarship . ')' )->result_array ();
			}
		}
		
		$this->load->view ( 'scholarship/scholarship_index', array (
				'result' => ! empty ( $result ) ? $result : array () ,
                 'applyid'=>$apply_id
		) );
	}
	
	/**
	 * 我申请的奖学金
	 */
	function myscholarship() {
	    $apply_id=$this->input->get('applyid');
		// 先去 获得申请表的信息
		$apply_info = $this->db->select ( '*' )->order_by ( 'applytime DESC' )->get_where ( 'applyscholarship_info', 'type != 1 AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] )->result_array ();
		if (! empty ( $apply_info )) {
			foreach ( $apply_info as $k => $v ) {
				$ids [] = $v ['scholarshipid'];
			}
			
			$resultall = $this->db->select ( '*' )->get_where ( 'scholarship_info', 'id > 0 AND state = 1 AND apply_state = 2 AND id IN (' . implode ( ',', $ids ) . ')' )->result_array ();
			foreach ( $resultall as $key => $val ) {
				if ($this->puri == 'en') {
					$result [$val ['id']] = $val ['entitle'];
				} else {
					$result [$val ['id']] = $val ['title'];
				}
			}
		}
		
		$this->load->view ( 'scholarship/scholarship_myscholarship', array (
				'result' => ! empty ( $result ) ? $result : array (),
				'apply_info' => ! empty ( $apply_info ) ? $apply_info : array (),
				'applyid'=>$apply_id
		) );
	}
	
	/**
	 * 奖学金详情
	 */
	function scholarship_detail() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if ($id) {
			$result = $this->db->select ( '*' )->get_where ( 'scholarship_info', 'id = ' . $id . ' AND state = 1 AND apply_state = 2 AND ischinascholorship = 0' )->row ();
			
			$this->load->view ( 'scholarship/scholarship_detail', array (
					'result' => ! empty ( $result ) ? ( array ) $result : array () 
			) );
		}
	}
}
