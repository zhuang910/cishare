<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Visa extends Master_Basic {
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
		$this->load->model ( $this->view . 'visa_model' );
	}
	
	/**
	 * 后台主页
	 */
	function index() {
        $label_id = $this->input->get ( 'label_id' );
        $label_id = ! empty ( $label_id ) ? $label_id : '1';
		$major = $this->input->get ( 'major' );
        $squad = $this->input->get ( 'squad' );
		
		 $this->load->model ( 'master/student/student_model' );
        if (empty ( $squad )) {
            $squad = 0;
        }
        $squad_info = 0;
        if (empty ( $major )) {

            $major = 0;
        }
		
		if ($this->input->is_ajax_request () === true) {
				// 设置查询字段
				
				$fields = $this->_set_lists_field ();

               
				// 查询条件组合
				$condition = dateTable_where_order_limit ( $fields );
				if(!empty($major)){
					if(!empty($condition['where'])){
						$condition['where'] .= ' AND zust_student.majorid = '.$major;
					}else{
						$condition['where'] = 'zust_student.majorid = '.$major;
					}
					
				}
			
				if(!empty($squad)){
					if(!empty($condition['where'])){
						$condition['where'] .= ' AND zust_student.squadid = '.$squad;
					}else{
						$condition['where'] = 'zust_student.squadid = '.$squad;
					}
					
				}

            $field = array('','zust_student.id','zust_student.enname','zust_student.majorid','zust_student.mobile','student_visa.visatime','student_visa.manage_state');
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
				
				$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->visa_model->count ( $condition ,$label_id);
				
				$output ['aaData'] = $this->visa_model->get ( $fields, $condition ,$label_id);
				//echo $this->db->last_query();die;
				foreach ( $output ['aaData'] as $item ) {
				$item->checkbox='<input type="checkbox" name="sid[]" value="'.$item->id.'">';
                    $item->squadid = $this->student_model->get_squadname ( $item->squadid );
                    $item->majorid = $this->student_model->get_majorname ( $item->majorid );
                    $item->major_class = "
                        专业：{$item->majorid}<br>
                        班级：{$item->squadid}
                    ";
				$item->manage_state=$item->manage_state==1?'正常':'办理续期中';
				$item->visastate=$this->visa_model->get_visastate($item->id);
				$item->visatime = date('Y-m-d',$item->visatime);
				$item->operation = '
					<a class="btn btn-xs btn-info" href="' . $this->zjjp . 'visa' . '/edit?id=' . $item->id . '">编辑</a>
				';
				}
				// var_dump($output);die;
				exit ( json_encode ( $output ) );
			}
		
        if ($major != 0) {
            $squad_info = $this->student_model->get_squad_info ( $major );
        }
		$major_info = $this->student_model->get_major_info ('id>0',0,0,'language desc');
		
        // 获取学历
        $major_info = $this->_get_major_by_degree($major_info);
		$this->_view ( 'visa_index',array(
            'label_id' => $label_id,
			 'major_info' => $major_info,
            'majorid' => $major,
            'squad_info' => $squad_info,
            'squadid' => $squad,
        ));
	}
	
		
	    private function _get_major_by_degree($major_lists = array()){
        $temp = array();
        if(!empty($major_lists)){
            $degree = $this->student_model->get_degree_name('id > 0',0,0,'language desc');
			
            foreach($degree as $key => $item){
                foreach($major_lists as $info){
                    if($info->degree == $item['id']){
                        $temp[$key]['degree_title'] = $item['title'];
                        $temp[$key]['degree_major'][] = $info;
                    }
                }
            }
        }
        return $temp;
    }
	/**
	 * 编辑在学学生
	 */
	function edit() {
		$id = intval ( $this->input->get ( 'id' ) );

		if ($id) {
			$where = "id={$id}";
			$info = $this->visa_model->get_one ( $where );
			$info_visa=$this->visa_model->get_visa_one($id);
			if (empty ( $info )) {
				ajaxReturn ( '', '该学生不存在', 0 );
			}
			$info->squadid=$this->visa_model->get_squad_name($info->squadid);
		}
		//状态
		
		$this->_view ( 'visa_edit', array (
				'info' => $info ,
				'info_visa'=>$info_visa,
				'id'=>$id,
		) );
	}
	/**
	 *
	 *编辑字段
	 **/
	function edit_fields(){
		$data=$this->input->post();
		$arr=explode('-', $data['name']);
		if(!empty($data)){
			if($arr[0]=='visa'){
				$this->visa_model->update_visa_fields($data,$arr[1]);
			}elseif($arr[0]=='insurance'){
				$this->visa_model->update_insurance_fields($data,$arr[1]);
			}else{
				$this->visa_model->update_fields($data);
			}
			ajaxReturn('','更新成功',1);
		}
		ajaxReturn('','更新失败',0);
	}
	// 更新
	function update() {
		$id = intval ( $this->input->post ( 'id' ) );
		if ($id) {
			$data = $this->input->post ();
			$data ['enroltime'] = strtotime ( $data ['enroltime'] );
			$data ['leavetime'] = strtotime ( $data ['leavetime'] );
			$data ['visaendtime'] = strtotime ( $data ['visaendtime'] );
			// 保存基本信息
			$this->visa_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'zust_student.id',
				'zust_student.enname',
                'zust_student.majorid',
                'zust_student.squadid',
                'zust_student.mobile'
		);
	}
	
	/**
	 * 获取班级信息
	 */
	function get_squad($majorid) {
		if ($data = $this->visa_model->get_squad_info ( $majorid )) {
			if ($this->input->is_ajax_request () === true) {
				ajaxReturn ( $data, '', 1 );
			} else {
				return $data;
			}
		}
		
		ajaxReturn ( '', '该专业没有班级', 0 );
	}
	/**
	 * 分班
	 */
	function addqm() {
		$data = $this->input->post ();
		
		$s = $this->visa_model->add_qm ( $data );
		if ($s) {
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 1 );
		}
	}
	
	/**
	 * 获取状态
	 */
	function get_state($state = null) {
		if ($state != null) {
			$stateArray = array (
					0=>'',
					1 => '<span class="label label-success">在校</span>',
					2 => '<span class="label label-success">转学</span>',
					3 => '<span class="label label-success">正常离开</span>',
					4 => '<span class="label label-success">非正常离开</span>',
					5 => '<span class="label label-success">休学</span>',
					6 => '<span class="label label-success">申请</span>', 
					7 => '<span class="label label-success">已报到</span>',
					8 => '<span class="label label-success">未报到</span>' ,
			);
			return $stateArray [$state];
		} else {
			return false;
		}
	}
	/**
	 * 设置列表字段
	 */
	private function part_class_field() {
		return array (
				'student.id',
				'student.studentid',
				'student.name',
				'student.firstname',
				'student.lastname',
				'student.passport',
				'student.majorid',
				'student.nationality'
		);
	}

	/**
	 * 上传
	 *
	 * @return string
	 */
	private function _upload() {
		$config = array (
				'save_path' => '/uploads/work/' . date ( 'Ym' ) . '/' . date ( 'd' ),
				'upload_path' => $_SERVER ['DOCUMENT_ROOT'] . '/uploads/work/' . date ( 'Ym' ) . '/' . date ( 'd' ),
				'allowed_types' => 'xls|xlsx',
				'file_name' => time () . rand ( 100000, 999999 ) 
		);
		
		if (! empty ( $config )) {
			$this->load->library ( 'upload', $config );
			// 创建目录
			mk_dir ( $config ['upload_path'] );
			
			if (! $this->upload->do_upload ( 'file' )) {
				ajaxReturn ( '', $this->upload->display_errors ( '', '' ), 0 );
			} else {
				$imgdata = $this->upload->data ();
				return $config ['save_path'] . '/' . $imgdata ['file_name'];
			}
		}
	}
	

	/**
	 * [send_message 批量发站内信]
	 * @return [type] [description]
	 */
	function send_message(){
		$data=$this->input->post();
		$userid=$this->visa_model->get_userid_arr($data['sid']);
		$idstr='';
		foreach ($userid as $k => $v) {
			$idstr.=$v.',';
		}
		$this->_view ('customemessage_send',array(
			'ids'=>$userid,
			'idstr'=>$idstr
			));
	}
	function insert_message(){
		$data=$this->input->post();
		$content=$this->input->post('content');
		$data['sendtime']=time();
		$data['content']=$content;

		$id = $this->visa_model->save_message($data);

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
		$emailarr=$this->visa_model->get_email_arr($data['sid']);
		$emailstr='';
		foreach ($emailarr as $k => $v) {
			$emailstr.=$v.',';
		}
		$adrset=$this->visa_model->get_addresserset();
		$this->_view ('customemail_edit',array(
				'addresserset'=>$adrset,
				'emailarr'=> $emailarr,
				'emailstr'=>$emailstr
			));
	}
	function insert_email(){
		$data=$this->input->post();
		$content=$this->input->post('content');
		$data['sendtime']=time();
		$data['content']=$content;
		$id = $this->visa_model->save_email($data);
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
}