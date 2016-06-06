<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Activity extends Master_Basic {
	protected $_size = 3;
	protected $_count = 0;
	protected $_countpage = 0;
	protected $data_student = array ();
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/student/';
		$this->load->model ( $this->view . 'activity_model' );
	}
	
	/**
	 * 后台主页
	 */
	function index() {

		$label_id = $this->input->get ( 'label_id' );
		$label_id = ! empty ( $label_id ) ? $label_id : '1';
		// 1 待审核的活动 2 审核通过 并且是 进行中的活动 3 结束 未打分的活动 4 结束 已打分的活动
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			// 翻页
			$limit = "";
			$offset = "";
			if (isset ( $_GET ['iDisplayStart'] ) && $_GET ['iDisplayLength'] != '-1') {
				$offset = intval ( $_GET ['iDisplayStart'] );
				$limit = intval ( $_GET ['iDisplayLength'] );
			}
			
			// 活动筛选
			switch ($label_id) {
				case 1 :
					$where = 'state = 0';
					break;
				case 2 :
					$where = 'state = 1 AND ishandend = 1 AND UNIX_TIMESTAMP() > starttime AND UNIX_TIMESTAMP() < endtime';
					break;
				case 3 :
					$where = 'state = 1 AND (ishandend = 0 OR UNIX_TIMESTAMP() > endtime) AND isscoreend = 0';
					break;
				case 4 :
					$where = 'state = 1 AND (ishandend = 0 OR UNIX_TIMESTAMP() > endtime) AND isscoreend = 1';
					break;
				case 5 :
					$where = 'state = 2';
					break;
				case 6 :
					$where = 'state = 1';
					break;
			}
			
			$sSearch = mysql_real_escape_string ( $this->input->get ( 'sSearch' ) );
			if (! empty ( $sSearch )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch}%'
				OR
				ctitle LIKE '%{$sSearch}%'
				OR
				FROM_UNIXTIME(`starttime`,'%Y-%m-%d') LIKE '%{$sSearch}%'
				OR username LIKE '%{$sSearch}%'
				OR
				FROM_UNIXTIME(`endtime`,'%Y-%m-%d') LIKE '%{$sSearch}%'
				OR
				FROM_UNIXTIME(`createtime`,'%Y-%m-%d') LIKE '%{$sSearch}%'
				)
				OR isapply LIKE '%{$sSearch}%'
				";
			}
			
			$sSearch_0 = $this->input->get ( 'sSearch_0' );
			if (! empty ( $sSearch_0 )) {
				$where .= "
				AND 
				id LIKE '%{$sSearch_0}%'
				
				";
			}
			$sSearch_1 = $this->input->get ( 'sSearch_1' );
			if (! empty ( $sSearch_1 )) {
				$where .= "
				AND 
				ctitle LIKE '%{$sSearch_1}%'
				
				";
			}
			$sSearch_2 = $this->input->get ( 'sSearch_2' );
			if (! empty ( $sSearch_2 )) {
				$where .= "
				AND
				FROM_UNIXTIME(`starttime`,'%Y-%m-%d') LIKE '%{$sSearch_2}%'
				";
			}
			
			$sSearch_3 = $this->input->get ( 'sSearch_3' );
			if (! empty ( $sSearch_3 )) {
				$where .= "
				AND
				FROM_UNIXTIME(`endtime`,'%Y-%m-%d') LIKE '%{$sSearch_3}%'
				";
			}
			
			$sSearch_4 = $this->input->get ( 'sSearch_4' );
			if (! empty ( $sSearch_4 )) {
				$where .= "
				AND
				username LIKE '%{$sSearch_4}%'
			
				";
			}
			
			$sSearch_5 = $this->input->get ( 'sSearch_5' );
			if (! empty ( $sSearch_5 )) {
				if ($sSearch_5 == - 1) {
					$sSearch_5 = 0;
				}
				
				$where .= "
				AND
				isapply = '.$sSearch_5
				
				";
			}
			
			$sSearch_6 = $this->input->get ( 'sSearch_6' );
			if (! empty ( $sSearch_6 )) {
				$where .= "
				AND
				FROM_UNIXTIME(`createtime`,'%Y-%m-%d') LIKE '%{$sSearch_6}%'
				";
			}
            // 排序
            $orderby = 'id DESC';
            if (isset ( $_GET ['iSortCol_0'] )) {
                for($i = 0; $i < intval ( $_GET ['iSortingCols'] ); $i ++) {
                    if ($_GET ['bSortable_' . intval ( $_GET ['iSortCol_' . $i] )] == "true") {
                        $orderby = $fields [intval ( $_GET ['iSortCol_' . $i] )] . ' ' . mysql_real_escape_string ( $_GET ['sSortDir_' . $i] );
                    }
                }
            }
			
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->activity_model->count ( $where );
			$output ['aaData'] = $this->activity_model->get ( $where, $limit, $offset, $orderby );
			foreach ( $output ['aaData'] as $item ) {
				$item->starttime = date ( 'Y-m-d H:i', $item->starttime );
				$item->endtime = date ( 'Y-m-d H:i', $item->endtime );
				$item->isapply = $this->get_state ( $item->isapply );
				$item->createtime = date ( 'Y-m-d H:i:s', $item->createtime );
				if ($label_id == 1) {
					$item->operation = '
						<a href="/master/student/activity/lookdetail?id=' . $item->id . '" class="btn btn-xs btn-info">
					查看内容
					</a>
						/
					
					<a class="btn btn-xs btn-info btn-white" onclick="edit_state(' . $item->id . ',1)" href="javascript:;">
						通过
					</a>
						/
					<a onclick="edit_state(' . $item->id . ',2)" href="javascript:;" class="btn btn-xs btn-info btn-white">
					不通过
					</a>
			
				';
				} else if ($label_id != 5) {
					$item->operation = '
						<a href="/master/student/activity/ckuser?id=' . $item->id . '" class="btn btn-xs btn-info">
					查看报名人
					</a>
						<a href="/master/student/activity/edit?id=' . $item->id . '" class="btn btn-xs btn-info btn-white">
					编辑内容
					</a>
				';
				} else {
					$item->operation = '';
				}
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'activity_index', array (
				'label_id' => $label_id 
		) );
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'ctitle',
				'starttime',
				'endtime',
				'userid',
				'isapply',
				'createtime',
				'auditname',
				'audittime',
				'auditopinion',
				'isapply',
				'ishandend',
				'isscoreend' 
		);
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_fields() {
		return array (
				'id',
				'name',
				'sex',
				'major',
				'classid',
				'state',
				'nationality',
				'passport',
				'score',
				'userid'
		);
	}
	
	/**
	 * 修改活动的状态
	 */
	function edit_state() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		$state = intval ( trim ( $this->input->get ( 'state' ) ) );
		
		if ($id && $state) {
			$flag = $this->activity_model->update_base ( 'id = ' . $id, array (
					'state' => $state,
					'auditid' => $_SESSION ['master_user_info']->id,
					'auditname' => ! empty ( $_SESSION ['master_user_info']->username ) ? $_SESSION ['master_user_info']->username : '',
					'audittime' => time () 
			) );
			if ($flag) {
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 修改活动的状态
	 */
	function edit_user_state() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		$state = intval ( trim ( $this->input->get ( 'state' ) ) );
		
		if ($id && $state) {
			$flag = $this->activity_model->update_activity_user ( 'id = ' . $id, array (
					'state' => $state,
					'auditid' => $_SESSION ['master_user_info']->id,
					'auditname' => ! empty ( $_SESSION ['master_user_info']->username ) ? $_SESSION ['master_user_info']->username : '',
					'audittime' => time () 
			) );
			if ($flag) {
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 审核编辑活动
	 */
	function lookdetail() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		
		if ($id) {
			$result = $this->activity_model->get_one_base ( 'id = ' . $id );
			$content = $this->activity_model->get_one_content ( 'activityid = ' . $id . " AND site_language = '{$_SESSION['language']}'" );
			$this->_view ( 'activity_lookdetail', array (
					'info' => ! empty ( $result ) ? $result : array (),
					'content' => ! empty ( $content ) ? $content : array () 
			) );
		}
	}
	
	/**
	 * 审核
	 */
	function docheck() {
		$data = $this->input->post ();
		if (! empty ( $data )) {
			$id = ! empty ( $data ['id'] ) ? $data ['id'] : '';
			unset ( $data ['id'] );
			if ($data) {
				$data ['auditid'] = $_SESSION ['master_user_info']->id;
				$data ['auditname'] = ! empty ( $_SESSION ['master_user_info']->username ) ? $_SESSION ['master_user_info']->username : '';
				$data ['audittime'] = time ();
				$flag = $this->activity_model->update_base ( 'id = ' . $id, $data );
				if ($flag) {
					ajaxReturn ( '', '', 1 );
				} else {
					
					ajaxReturn ( '', '', 0 );
				}
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 获取状态
	 */
	function get_state($state = null) {
		if ($state != null) {
			$stateArray = array (
					0 => '否',
					1 => '是' 
			);
			return $stateArray [$state];
		} else {
			return false;
		}
	}
	
	/**
	 * 查看用户
	 */
	function ckuser() {

		$label_id = $this->input->get ( 'label_id' );
		$id = $this->input->get ( 'id' );
		$label_id = ! empty ( $label_id ) ? $label_id : '1';
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		
		// 1 待审核的活动 2 审核通过 并且是 进行中的活动 3 结束 未打分的活动 4 结束 已打分的活动
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_fields ();
			// 翻页
			$limit = "";
			$offset = "";
			if (isset ( $_GET ['iDisplayStart'] ) && $_GET ['iDisplayLength'] != '-1') {
				$offset = intval ( $_GET ['iDisplayStart'] );
				$limit = intval ( $_GET ['iDisplayLength'] );
			}
			
			// 活动筛选
			switch ($label_id) {
				case 1 :
					$where = 'state = 0 AND activityid = ' . $id;
					break;
				case 2 :
					$where = 'state = 1 AND activityid = ' . $id;
					break;
				case 3 :
					$where = 'state = 2 AND activityid = ' . $id;
					break;
			}
			
			$sSearch = mysql_real_escape_string ( $this->input->get ( 'sSearch' ) );
			if (! empty ( $sSearch )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch}%'
				OR
				name LIKE '%{$sSearch}%'
				
				OR passport LIKE '%{$sSearch}%'
				OR major LIKE '%{$sSearch}%'
				OR nationality = '%{$sSearch}%'
				OR sex = '%{$sSearch}%'
				OR score = '%{$sSearch}%'
				OR classid LIKE '%{$sSearch}%'
				)";
			}
			
			$sSearch_0 = $this->input->get ( 'sSearch_0' );
			if (! empty ( $sSearch_0 )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch_0}%'
				OR
				name LIKE '%{$sSearch_0}%'
				
				OR passport LIKE '%{$sSearch_0}%'
				OR major LIKE '%{$sSearch_0}%'
				OR nationality = '%{$sSearch_0}%'
				OR sex = '%{$sSearch_0}%'
				OR score = '%{$sSearch_0}%'
				OR classid LIKE '%{$sSearch_0}%'
				)";
			}
			$sSearch_1 = $this->input->get ( 'sSearch_1' );
			if (! empty ( $sSearch_1 )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch_1}%'
				OR
				name LIKE '%{$sSearch_1}%'
				
				OR passport LIKE '%{$sSearch_1}%'
				OR major LIKE '%{$sSearch_1}%'
				OR nationality = '%{$sSearch_1}%'
				OR sex = '%{$sSearch_1}%'
				OR score = '%{$sSearch_1}%'
				OR classid LIKE '%{$sSearch_1}%'
				)";
			}
			$sSearch_2 = $this->input->get ( 'sSearch_2' );
			if (! empty ( $sSearch_2 )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch_2}%'
				OR
				name LIKE '%{$sSearch_2}%'
				
				OR passport LIKE '%{$sSearch_2}%'
				OR major LIKE '%{$sSearch_2}%'
				OR nationality = '%{$sSearch_2}%'
				OR sex = '%{$sSearch_2}%'
				OR score = '%{$sSearch_2}%'
				OR classid LIKE '%{$sSearch_2}%'
				)";
			}
			
			$sSearch_3 = $this->input->get ( 'sSearch_3' );
			if (! empty ( $sSearch_3 )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch_3}%'
				OR
				name LIKE '%{$sSearch_3}%'
				
				OR passport LIKE '%{$sSearch_3}%'
				OR major LIKE '%{$sSearch_3}%'
				OR nationality = '%{$sSearch_3}%'
				OR sex = '%{$sSearch_3}%'
				OR score = '%{$sSearch_3}%'
				OR classid LIKE '%{$sSearch_3}%'
				)";
			}
			
			$sSearch_4 = $this->input->get ( 'sSearch_4' );
			if (! empty ( $sSearch_4 )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch_4}%'
				OR
				name LIKE '%{$sSearch_4}%'
				
				OR passport LIKE '%{$sSearch_4}%'
				OR major LIKE '%{$sSearch_4}%'
				OR nationality = '%{$sSearch_4}%'
				OR sex = '%{$sSearch_4}%'
				OR score = '%{$sSearch_4}%'
				OR classid LIKE '%{$sSearch_4}%'
				)";
			}
			
			$sSearch_5 = $this->input->get ( 'sSearch_5' );
			if (! empty ( $sSearch_5 )) {
				$where .= "
				AND (
				
				 sex = '{$sSearch_5}'
				
				)";
			}
			
			$sSearch_6 = $this->input->get ( 'sSearch_6' );
			if (! empty ( $sSearch_6 )) {
				$where .= "
				AND (
				
				nationality = '{$sSearch_6}'
				
				)";
			}
			
			$sSearch_7 = $this->input->get ( 'sSearch_7' );
			if (! empty ( $sSearch_7 )) {
				$where .= "
				AND (
				
				score = '{$sSearch_7}'
				
				)";
			}
			
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->activity_model->countuser ( $where );
			$output ['aaData'] = $this->activity_model->getuser ( $where, $limit, $offset, 'id DESC' );
			foreach ( $output ['aaData'] as $item ) {
				$item->checkbox='<input type="checkbox" name="sid[]" value="'.$item->userid.'">';
				$item->sex = $this->_set_sex ( $item->sex );
				$item->nationality = $nationality [$item->nationality];
				$item->score = $this->_set_score ( $item->score );
				$item->operation = '
					<a href="javascript:pub_alert_html(\'/master/student/activity/look_speciality?id=' . $item->userid . '&activityid=' . $item->activityid . '\');" class="blue" title="查看备注">
					<i class="ace-icon fa fa-pencil bigger-130"></i>
					</a>
						/
						<a href="javascript:pub_alert_html(\'/master/student/activity/ckscore?id=' . $item->id . '&activityid=' . $item->activityid . '\');" class="blue" title="打分">
					<i class="ace-icon fa fa-pencil bigger-130"></i>
					</a>
						/
			
					<a class="red" onclick="edit_user_state(' . $item->id . ',1)" href="javascript:;" title="通过">
						<i class="ace-icon fa fa-check green bigger-130"></i>
					</a>
						/
					<a onclick="edit_user_state(' . $item->id . ',2)" href="javascript:;" class="red" title="不通过">
					<i class="ace-icon glyphicon glyphicon-remove red"></i>
					</a>
		
									';
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'activity_ckuser', array (
				'label_id' => $label_id,
				'id' => $id,
				'nationality' => $nationality 
		) );
	}
	
	/**
	 * 打分
	 */
	function ckscore() {
		$id = intval ( $this->input->get ( 'id' ) );
		$activityid = intval ( trim ( $this->input->get ( 'activityid' ) ) );
		$scoreA = array (
				'1' => '非常差',
				'2' => '差',
				'3' => '一般',
				'4' => '好',
				'5' => '非常好' 
		);
		$html = $this->_view ( 'activity_ckscore', array (
				'id' => $id,
				'scoreA' => $scoreA,
				'activityid' => $activityid 
		), true );
		ajaxReturn ( $html, '', 1 );
	}
	
	/**
	 * 执行打分操作
	 */
	function do_score() {
		$id = intval ( $this->input->post ( 'id' ) );
		$score = intval ( trim ( $this->input->post ( 'score' ) ) );
		$activityid = intval ( trim ( $this->input->post ( 'activityid' ) ) );
		
		if ($id && $score && $activityid) {
			$f = $this->activity_model->update_activity_user ( 'id = ' . $id, array (
					'score' => $score 
			) );
			
			if ($f) {
				// 判断是否都已经打了分
				$df = $this->db->select ( 'score' )->get_where ( 'activity_user', 'state = 1 AND activityid = ' . $activityid )->result_array ();
				if (! empty ( $df )) {
					foreach ( $df as $key => $val ) {
						if (empty ( $val ['score'] )) {
							$b = 0;
							break;
						} else {
							$b = 1;
						}
					}
					if ($b == 1) {
						// 更新活动为已打分
						$this->activity_model->update_base ( 'id = ' . $activityid, array (
								'isscoreend' => 1 
						) );
					}
				}
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 设置性别
	 *
	 * @param string $sex        	
	 * @return string
	 */
	function _set_sex($sex = null) {
		if ($sex != null) {
			if ($sex == 1) {
				return '男';
			} else if ($sex == 2) {
				return '女';
			}
		} else {
			return '';
		}
	}
	
	/**
	 * 设置打分
	 *
	 * @param string $score        	
	 * @return Ambigous <string>|string
	 */
	function _set_score($score = null) {
		if ($score != null) {
			$scoreA = array (
					'1' => '非常差',
					'2' => '差',
					'3' => '一般',
					'4' => '好',
					'5' => '非常好' 
			);
			return $scoreA [$score];
		} else {
			return '';
		}
	}
	
	/**
	 * 添加 活动
	 */
	function add() {
		$this->_view ( 'activity_add', array () );
	}
	function save() {
		$data = $this->input->post ();
		if (! empty ( $data ['starttime'] )) {
			$data ['starttime'] = strtotime ( $data ['starttime'] );
		}
		if (! empty ( $data ['endtime'] )) {
			$data ['endtime'] = strtotime ( $data ['endtime'] );
		}
		$data ['state'] = 1;
		$data ['userid'] = $_SESSION ['master_user_info']->id;
		$data ['username'] = $_SESSION ['master_user_info']->username;
		$data ['createtime'] = time ();
		$data ['isapply'] = 1;
		$flag = $this->db->insert ( 'activity_base', $data );
		if ($flag) {
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 编辑嫩荣
	 */
	function edit() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if ($id) {
			$info = $this->db->select ( '*' )->get_where ( 'activity_base', 'id = ' . $id )->row ();
			$content = $this->db->select ( '*' )->get_where ( 'activity_content', 'activityid = ' . $id . " AND site_language = '{$_SESSION ['language']}'" )->row ();
			$this->_view ( 'activity_edit', array (
					'info' => $info,
					'content' => ! empty ( $content ) ? $content : array (),
					'id' => $id 
			) );
		}
	}
	function editsave() {
		$data = $this->input->post ();
		$id = ! empty ( $data ['id'] ) ? $data ['id'] : '';
		if ($id) {
			$dataBase = array (
					'ctitle' => ! empty ( $data ['ctitle'] ) ? $data ['ctitle'] : '',
					'etitle' => ! empty ( $data ['etitle'] ) ? $data ['etitle'] : '',
					'starttime' => ! empty ( $data ['starttime'] ) ? strtotime ( $data ['starttime'] ) : '',
					'endtime' => ! empty ( $data ['endtime'] ) ? strtotime ( $data ['endtime'] ) : '' 
			);
			// 现更新
			$this->db->update ( 'activity_base', $dataBase, 'id = ' . $id );
			$datacontet = array (
					'linkname' => ! empty ( $data ['linkname'] ) ? $data ['linkname'] : '',
					'budgeting' => ! empty ( $data ['budgeting'] ) ? $data ['budgeting'] : '',
					'address' => ! empty ( $data ['address'] ) ? $data ['address'] : '',
					'linktel' => ! empty ( $data ['linktel'] ) ? $data ['linktel'] : '',
					'content' => ! empty ( $data ['content'] ) ? $data ['content'] : '',
					'site_language' => $_SESSION ['language'],
					'activityid' => $id 
			);
			// 先删除
			$this->db->delete ( 'activity_content', 'activityid = ' . $id . " AND site_language = '{$_SESSION ['language']}'" );
			$flag = $this->db->insert ( 'activity_content', $datacontet );
			if ($flag) {
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	/**
	 * [look_techang 查看特长]
	 * @return [type] [description]
	 */
	function look_speciality(){	
		$id = intval ( $this->input->get ( 'id' ) );
		$student_info=$this->db->where('id',$id)->get('student_info')->row_array();
		$html = $this->_view ( 'student_speciality', array (
				'student_info'=>$student_info
				
		), true );
		ajaxReturn ( $html, '', 1 );
	}
}