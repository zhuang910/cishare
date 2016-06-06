<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Stuscore extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/score/';
		$this->load->model ( $this->view . 'stuscore_model' );
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		$mdata=$this->db->order_by('language DESC')->get_where('major','id > 0')->result();
		
        // 获取学历
        $mdata = $this->_get_major_by_degree($mdata);
		//查表获取考试的类型  
		$scoretype=$this->db->where('state = 1')->get('set_score')->result_array();
		$this->_view ( 'stuscore_index', array (
				'mdata' => $mdata,
				'scoretype' => $scoretype 
		) );
	}
		private function _get_major_by_degree($major_lists = array()){
        $temp = array();
        if(!empty($major_lists)){
           
			$degree = $this->db->order_by('orderby DESC')->get('degree_info','id > 0')->result_array();
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
	 * 获取该专业学期
	 */
	public function get_nowterm($mid) {
		$nowterm = $this->stuscore_model->get_major_nowterm ( $mid );
		$course = $this->stuscore_model->get_course ( $mid );
		$data ['nowterm'] = $nowterm;
		if (! empty ( $course )) {
			$data ['course'] = $course;
			ajaxReturn ( $data, '', 1 );
		} else {
			ajaxReturn ( $data, '该专业还没有课程', 2 );
		}
	}
	/**
	 * 获取该学期的专业
	 */
	function get_squad() {
		$mid = $this->input->get ( 'mid' );
		$term = $this->input->get ( 'term' );
		$squad = $this->stuscore_model->get_squadinfo ( $mid, $term );
		if (empty ( $squad )) {
			ajaxReturn ( '', '该学期下还没有班级', 0 );
		}
		// var_dump($squad);exit;
		if (! empty ( $squad )) {
			ajaxReturn ( $squad, '', 1 );
		}
	}
	
	/**
	 * 获取学生
	 */
	function get_student() {
		$data = $this->input->post ();
		if ($data ['majorid'] != '0' && $data ['courseid'] != '0' && $data ['squadid'] != '0' && $data ['nowterm'] != '0') {
			
			$scoreinfo = $this->stuscore_model->get_stu_score ();
			if (! empty ( $data ['key'] ) && ! empty ( $data ['value'] )) {
				$sdata = $this->stuscore_model->get_student_one ( $data ['squadid'], $data ['key'], $data ['value'] );
				if (empty ( $sdata )) {
					ajaxReturn ( '', '没有所查找的学生', 0 );
				}
			} else {
				$sdata = $this->stuscore_model->get_studentinfo ( $data ['squadid'] );
			}
            //查询代课老师的联系方式
            $info=$this->stuscore_model->get_laoshixinxi($data);
            $data['info']=$info;
			$data ['stu'] = $sdata;
			$data ['scoreinfo'] = $scoreinfo;
			if (! empty ( $sdata )) {
				ajaxReturn ( $data, '', 1 );
			}
		} elseif (! empty ( $data ['key'] ) && ! empty ( $data ['value'])&& $data ['nowterm'] != '0' && !empty($data['scoretype']) && $data['courseid']!='0') {
			$sdata=$this->stuscore_model->get_student_one($data);
			if(!empty($sdata)){
				$scoreinfo = $this->stuscore_model->get_stu_score ();
				$data ['stu'] = $sdata;
				$data ['scoreinfo'] = $scoreinfo;
				ajaxReturn ( $data, '', 1 );
			}else{
				ajaxReturn ( '', '没有所查找的学生', 0 );
			}
		} else {
			ajaxReturn ( '', '学期班级课程不能为空', 0 );
		}
	}
	/**
	 * 保存成绩
	 */
	function save_score() {
		$data ['majorid'] = $this->input->get ( 'majorid' );
		$data ['squadid'] = $this->input->get ( 'squadid' );
		$data ['term'] = $this->input->get ( 'nowterm' );
		$data ['scoretype'] = $this->input->get ( 'scoretype' );
		$data ['courseid'] = $this->input->get ( 'courseid' );
		$dataone = $this->input->get ();
		$datatwo = $this->input->post ();
		
		if ($this->stuscore_model->insert_score ( $data, $datatwo )) {
			// 成绩不及格 发邮件
			// 不及格的学生
			$userids = array ();
			foreach ( $datatwo as $zk => $zv ) {
				if (! empty ( $zv ) && $zv < 60) {
					$userids [$zk] = $zv;
				}
			}
			
			// 发邮件
			if (! empty ( $userids )) {
				$this->do_email ( $data, $userids );
			}
			
			$scoreinfo = $this->stuscore_model->get_stu_score ();
			if (! empty ( $dataone ['key'] ) && ! empty ( $dataone ['value'] )) {
				$sdata = $this->stuscore_model->get_student_one ( $dataone ['squadid'], $dataone ['key'], $dataone ['value'] );
				if (empty ( $sdata )) {
					ajaxReturn ( '', '没有所查找的学生', 0 );
				}
			} else {
				$sdata = $this->stuscore_model->get_studentinfo ( $data ['squadid'] );
			}
			$data ['stu'] = $sdata;
			$data ['scoreinfo'] = $scoreinfo;
			ajaxReturn ( $data, '', 1 );
		}
	}
	
	/**
	 * 不及格发邮件
	 */
	function do_email($data = null, $userids = null) {
		if ($data != null && $userids != null) {
			$this->load->library ( 'sdyinc_email' );
			$MAIL = new sdyinc_email ();
			$type = $term = array ();
			// 获取 考试类型
			$scoretype = CF ( 'scoretype', '', CONFIG_PATH );
			if (! empty ( $scoretype )) {
				foreach ( $scoretype as $zsk => $zcv ) {
					$type [$zcv ['id']] = $zcv ['englishtypename'];
				}
			}
			
			// 课程名称
			$coursename = $this->db->select ( 'englishname' )->get_where ( 'course', 'id = ' . $data ['courseid'] )->row ();
			
			// 学期
			$term = array (
					'1' => '1st Semester',
					'2' => '2nd Semester',
					'3' => '3rd Semester',
					'4' => '4th Semester',
					'5' => '5th Semester',
					'6' => '6th Semester',
					'7' => '7th Semester',
					'8' => '8th Semester',
					'9' => '9th Semester',
					'10' => '10th Semester' 
			);
			
			// 查学生 发邮件
			foreach ( $userids as $zuk => $zuv ) {
				$email = $this->db->select ( 'email' )->get_where ( 'student', 'id = ' . $zuk )->row ();
				$val_arr = array (
						'term' => $term [$data ['term']],
						'type' => $type [$data ['scoretype']],
						'coursename' => $coursename->englishname,
						'score' => $zuv ,
						'email' => $email->email
				);
				if (! empty ( $email->email )) {
					$MAIL->dot_send_mail ( 29, $email->email, $val_arr );
				}
			}
		}
	}
	
	/**
	 * 删除
	 */
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		$sid = intval ( $this->input->get ( 'sid' ) );
		$key = $this->input->get ( 'key' );
		$value = $this->input->get ( 'value' );
		if ($id) {
			$where = "id = {$id}";
			$is = $this->stuscore_model->delete ( $where );
			if ($is === true) {
				$scoreinfo = $this->stuscore_model->get_stu_score ();
				if (! empty ( $key ) && ! empty ( $value )) {
					$sdata = $this->stuscore_model->get_student_one ( $sid, $key, $value );
				} else {
					$sdata = $this->stuscore_model->get_studentinfo ( $sid );
				}
				$data ['stu'] = $sdata;
				$data ['scoreinfo'] = $scoreinfo;
				ajaxReturn ( $data, '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	/**
	 * 导出
	 */
	function export() {
		$where = $this->input->post ();
		foreach ( $where as $key => $value ) {
			if ($value == 0) {
				unset ( $where [$key] );
			}
		}
		$this->load->library ( 'sdyinc_export' );
		
		$d = $this->sdyinc_export->do_export_score ( $where );
		if (! empty ( $d )) {
			$this->load->helper ( 'download' );
			force_download ( 'chengji' . time () . '.xlsx', $d );
			return 1;
		}
	}
	/**
	 * 导入页面
	 */
	function tochanel() {
		$mdata = $this->stuscore_model->get_majorinfo ();
		$scoretype=$this->db->where('state = 1')->get('set_score')->result_array();
		$s = intval ( $this->input->get ( 's' ) );
		if (! empty ( $s )) {
			$html = $this->_view ( 'tochanel', array (
					'mdata' => $mdata,
					'scoretype' => $scoretype 
			), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	
	/**
	 * 导出模板
	 */
	function tochaneltenplate() {
		$ddata = $this->input->post ();
		$sdata = $this->stuscore_model->get_studentinfo ( $ddata ['squadid'] );
		// var_dump($sdata);
		// var_dump($data);exit;
		$data = $this->stuscore_model->get_stuscore_fields ();
		$this->load->library ( 'sdyinc_export' );
		$d = $this->sdyinc_export->stuscore_tochaneltenplate ( $data, $ddata, $sdata );
		if (! empty ( $d )) {
			$this->load->helper ( 'download' );
			force_download ( 'chengji' . time () . '.xlsx', $d );
			return 1;
		}
	}
	
	/**
	 * 上传major
	 */
	function upload_excel() {
		// 判断文件类型，如果不是"xls"或者"xlsx"，则退出
		if ($_FILES ["file"] ["type"] == "application/vnd.ms-excel") {
			$inputFileType = 'Excel5';
		} elseif ($_FILES ["file"] ["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
			$inputFileType = 'Excel2007';
		} else {
			echo "Type: " . $_FILES ["file"] ["type"] . "<br />";
			echo "您选择的文件格式不正确";
			exit ();
		}
		
		if ($_FILES ["file"] ["error"] > 0) {
			echo "Error: " . $_FILES ["file"] ["error"] . "<br />";
			exit ();
		}
	  $str='./uploads/work/' . date ( 'Ym' ) . '/' . date ( 'd' );
		if(!is_dir($str)){
			mk_dir($str);
		}
		 $inputFileName =$str.'/'.$_FILES["file"]["name"];
		if (file_exists ( $inputFileName )) {
			// echo $_FILES["file"]["name"] . " already exists. <br />";
			unlink ( $inputFileName ); // 如果服务器上存在同名文件，则删除
		} else {
		}

		move_uploaded_file ( $_FILES ["file"] ["tmp_name"], $inputFileName );
		echo "Stored in: " . $inputFileName;
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$this->load->library ( 'PHPExcel/Writer/Excel2007' );
		$objReader = IOFactory::createReader ( $inputFileType );
		$WorksheetInfo = $objReader->listWorksheetInfo ( $inputFileName );
		// 读取文件最大行数、列数，偶尔会用到。
		$maxRows = $WorksheetInfo [0] ['totalRows'];
		$maxColumn = $WorksheetInfo [0] ['totalColumns'];
		
		// 设置只读，可取消类似"3.08E-05"之类自动转换的数据格式，避免写库失败
		$objReader->setReadDataOnly ( true );
		
		$objPHPExcel = $objReader->load ( $inputFileName );
		$sheetData = $objPHPExcel->getSheet ( 0 )->toArray ( null, true, true, true );
		// excel2003文件，可使用'$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);'
		// excel2007文件使用"getActiveSheet()"方法时会提示出错：对non-object使用了"toArray"方法。
		$keywords = $sheetData [1];
		$num = count ( $sheetData [1] );
		$warning = '上传文件字段格式不正确，请修改后重新上传！<br />';
		$columns = array (
				'A',
				'B',
				'C',
				'D',
				'E',
				'F',
				'G',
				'H',
				'I' ,
				'J'
		);
		$mfields = $this->stuscore_model->get_stuscore_fields ();
		if ($num != count ( $mfields )) {
			echo '字段个数不匹配';
			exit ();
		}
		$keysInFile = array ();
		foreach ( $mfields as $key => $value ) {
			$keysInFile [] = $value;
		}
		foreach ( $columns as $keyIndex => $columnIndex ) {
			if ($keywords [$columnIndex] != $keysInFile [$keyIndex]) {
				echo $warning . $columnIndex . '列应为' . $keysInFile [$keyIndex] . '，而非' . $keywords [$columnIndex];
				exit ();
			}
		}
		$insert = '';
		foreach ( $mfields as $k => $v ) {
			
			$insert .= $k . ',';
		}
		$insert = trim ( $insert, ',' );
		unset ( $sheetData [1] );
		$i = 65;
		$m = 2;
		$str = '';
		foreach ( $sheetData as $k => $v ) {
			
			$value = '';
			$mid = '';
			foreach ( $v as $kk => $vv ) {
				if ($kk == 'A') {
					$value .= '"' . $this->stuscore_model->get_studentid ( $vv, $v ['J'] ) . '",';
				} elseif ($kk == 'B') {
					$value .= '"' . $this->stuscore_model->get_majorid ( $vv ) . '",';
					$mid = $this->stuscore_model->get_majorid ( $vv );
				} elseif ($kk == 'C') {
					$value .= '"' . $this->stuscore_model->get_courseid ( trim ( $vv, '“”' ) ) . '",';
				} elseif ($kk == 'D') {
					
					$value .= '"' . $this->stuscore_model->get_squadid ( $vv, $mid ) . '",';
				} elseif ($kk == 'F') {
					$vv = mb_convert_encoding ( $vv, 'GB2312', 'UTF-8' );
					$vv = substr ( $vv, 2, 1 );
					$value .= '"' . $vv . '",';
				} elseif ($kk == 'I') {
					$value .= '"' . $this->stuscore_model->get_scoretypeid ( $vv ) . '",';
				} else {
					$value .= '"' . $vv . '",';
				}
			}
			
			$value = trim ( $value, ',' );
			$count = $this->stuscore_model->check_score ( $insert, $value );
			if ($count > 0) {
				$str .= '<br />excel中的' . $m . "行与数据库重复";
				$m ++;
				continue;
			}
			$m ++;
			$this->stuscore_model->insert_fields ( $insert, $value );
			$i ++;
		}
		if ($str != '') {
			echo $str;
		}
	}
}