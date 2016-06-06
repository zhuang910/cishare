<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Student_arrearage extends Master_Basic {
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
		$this->load->model ( $this->view . 'student_arrearage_model' );
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		$label_id = $this->input->get ( 'label_id' );
		$label_id = ! empty ( $label_id ) ? $label_id : '6';
		if ($this->input->is_ajax_request () === true) {
				// 设置查询字段
				$label_id = $this->input->get ( 'label_id' );
				$label_id = ! empty ( $label_id ) ? $label_id : '6';
				$fields = $this->_set_lists_field ();
				
				// 查询条件组合
				$condition = dateTable_where_order_limit ( $fields );

            $field = array('','student.id','student.name','student.enname','student.nationality','student.passport');
            // 排序
            $orderby = null;
            if (isset ( $_GET ['iSortCol_0'] )) {
                for($i = 0; $i < intval ( $_GET ['iSortingCols'] ); $i ++) {
                    if ($_GET ['bSortable_' . intval ( $_GET ['iSortCol_' . $i] )] == "true") {
                        $orderby = $field [intval ( $_GET ['iSortCol_' . $i] )] . ' ' . mysql_real_escape_string ( $_GET ['sSortDir_' . $i] );
                    }
                }
            }
            $condition['orderby'] = $orderby;
				
				$output ['sEcho'] = intval ( $_GET ['sEcho'] );
				
				$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->student_arrearage_model->count ( $condition ,$label_id);
				
				$output ['aaData'] = $this->student_arrearage_model->get ( $fields, $condition,$label_id );
				//echo $this->db->last_query();
				foreach ( $output ['aaData'] as $item ) {
				$item->checkbox='<input type="checkbox" name="sid[]" value="'.$item->id.'">';
				$item->majorsquad=$this->student_arrearage_model->get_major_squad($item->id);
			
				$item->qian_price=$this->student_arrearage_model->get_qianfeijine($label_id,$item->userid);
				$item->operation = '
				
				';
					$item->nowterm='第'.$item->nowterm.'学期';
				}
				// var_dump($output);die;
				exit ( json_encode ( $output ) );
			}
		$this->_view ( 'student_arrearage_index',array(
			'label_id' => $label_id
			));
	}
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
			return array (
				'zust_student.id',
				'zust_student.name',
				'zust_student.firstname',
				'zust_student.lastname',
				'zust_student.enname',
				'zust_student.passport',
				'zust_student.squadid'
		);
	}
	
	/**
	 * 获取班级信息
	 */
	function get_squad($majorid) {
		if ($data = $this->student_arrearage_model->get_squad_info ( $majorid )) {
			if ($this->input->is_ajax_request () === true) {
				ajaxReturn ( $data, '', 1 );
			} else {
				return $data;
			}
		}
		
		ajaxReturn ( '', '该专业没有班级', 0 );
	}
	/**
	 * [send_message 批量发站内信]
	 * @return [type] [description]
	 */
	function send_message(){
		$data=$this->input->post();
		$userid=$this->student_arrearage_model->get_userid_arr($data['sid']);
		$idstr='';
		$url='/master/student/send_book';
		foreach ($userid as $k => $v) {
			$idstr.=$v.',';
		}
		$this->_view ('customemessage_send',array(
			'ids'=>$userid,
			'idstr'=>$idstr,
			'url'=>$url
			));
	}
	function insert_message(){
		$data=$this->input->post();
		$content=$this->input->post('content');
		$data['sendtime']=time();
		$data['content']=$content;

		$id = $this->student_arrearage_model->save_message($data);

			if ($id == true) {
			 $this->send_messages($data,$content);

				ajaxReturn('', '添加成功', 1);
			} else {
				ajaxReturn('', '添加失败', 0);
			}
	}
	/**
	 * 自定义发送消息
	 */
	function send_messages($data,$content){
		
		$senttoid=explode(',',$data['sentto']);
		$this->load->library('sdyinc_message');
		foreach ($senttoid as $k => $v) {
			$this->sdyinc_message->custom_message($v,$data['title'],$content);
		}
		ajaxreturn('','操作成功',1);
	}
	/**
	 * [send_email 批量发邮件]
	 * @return [type] [description]
	 */
	function send_email(){
		$data=$this->input->post();
		$emailarr=$this->student_arrearage_model->get_email_arr($data['sid']);
		$emailstr='';
		$url='/master/student/send_book';
		foreach ($emailarr as $k => $v) {
			$emailstr.=$v.',';
		}
		$adrset=$this->student_arrearage_model->get_addresserset();
		$this->_view ('customemail_edit',array(
				'addresserset'=>$adrset,
				'emailarr'=> $emailarr,
				'emailstr'=>$emailstr,
				'url'=>$url
			));
	}
	function insert_email(){
		$data=$this->input->post();
		$content=$this->input->post('content');
		$data['sendtime']=time();
		$data['content']=$content;
		$id = $this->student_arrearage_model->save_email($data);
			if ($id == true) {
				$this->send_emails($data,$content);
				
			} else {
				ajaxReturn('', '添加失败', 0);
			}
	}
	/**
	 * 自定义发送邮件
	 */
	function send_emails($data,$content){
		
		
		$this->load->library('sdyinc_email');
		$senttoid=explode(',',$data['sentto']);
		foreach ($senttoid as $k => $v) {
			$this->sdyinc_email->do_send_mail($v,$data['addresserset'],$data['title'],$content,$data['reply_to']);
		}
		ajaxreturn('','操作成功',1);
	}
	/**
	 * [edit_book 学生发书弹框]
	 * @return [type] [description]
	 */
	function edit_book(){
		$s = intval ( $this->input->get ( 's' ) );
		//获取该选课课程的排课信息
		$nowterm=intval($this->input->get ( 'nowterm' ));
		//学生id
		$studentid=intval($this->input->get ( 'id' ));
		//专业id
		$majorid=intval($this->input->get ( 'majorid' ));
		//获取该专业选的所有书籍
		$m_book=$this->student_arrearage_model->get_major_books($majorid);
		$s_b_info=$this->student_arrearage_model->get_student_book_info($studentid);
		if (! empty ( $s )) {
			$html = $this->_view ( 'edit_student_book', array (
				'studentid'=>$studentid,
				'nowterm'=>$nowterm,
				'm_book'=>$m_book,
				's_b_info'=>$s_b_info
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 * 保存学生以发的书
	 */
	function save_student_book(){
		$data=$this->input->post();
		if(!empty($data)){
			$id=$this->student_arrearage_model->do_save_student_book($data);
			if(!empty($id)){
				ajaxReturn('','',1);
			}
		}
		ajaxReturn('','',0);
	}
}