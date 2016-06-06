	<?php
	defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
	
	/**
	 * 后台首页
	 *
	 * @author JJ
	 *        
	 */
	class Course extends Master_Basic {
		protected $programaids_course = array ();
		public $programaid_parent = 0;
		/**
		 * 基础类构造函数
		 */
		function __construct() {
			parent::__construct ();
			$this->view = 'master/basic/';
			$this->load->model ( $this->view . 'course_model' );
			// 获取学历
			$degree = CF ( 'degree', '', CONFIG_PATH );
			$this->load->vars ( 'degree', $degree );
			$this->programaids_course = $this->_get_programaids ();
		}
		
		/**
		 * 后台主页
		 */
		function index() {
			if ($this->input->is_ajax_request () === true) {
				// 设置查询字段
				
				$fields = $this->_set_lists_field ();
				
				// 查询条件组合
				$condition = dateTable_where_order_limit ( $fields );
				
				$output ['sEcho'] = intval ( $_GET ['sEcho'] );
				
				$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->course_model->count ( $condition );
				
				$output ['aaData'] = $this->course_model->get ( $fields, $condition );
				
				foreach ( $output ['aaData'] as $item ) {
					$item->state = $this->_get_lists_state ( $item->state );
					$state = $item->state;
					$item->operation = '
					<a class="btn btn-xs btn-info" href="' . $this->zjjp . 'course' . '/edit?id=' . $item->id . '">编辑</a>
					<a class="btn btn-xs btn-info btn-white" href="javascript:;" onclick="pub_alert_html(\'' . $this->zjjp . 'course/add_books?courseid=' . $item->id . '&s=1\')">为课程关联课本 </a>
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="btn btn-xs btn-info btn-white">删除</a>
				';
					$item->variable = $item->variable == 1 ? '必修' : '选修';
				}
				// var_dump($output);die;
				exit ( json_encode ( $output ) );
			}
			
			$this->_view ( 'course_index' );
		}
		/**
		 * 获取课程状态
		 *
		 * @param string $statecode        	
		 * @param string $stateindexcode        	
		 * @return string
		 */
		private function _get_lists_state($statecode = null) {
			if ($statecode != null) {
				$statemsg = array (
						'<span class="label label-important">禁用</span>',
						'<span class="label label-success">正常</span>' 
				);
				return $statemsg [$statecode];
			}
			return;
		}
	/**
	 * [add_books 关联书籍]
	 */
	function add_books(){
		$bookinfo = $this->course_model->get_books_limit ();

		$s = intval ( $this->input->get ( 's' ) );
		if (! empty ( $s )) {
			$courseid = $this->input->get ( 'courseid' );
			//已经关联的书籍
			$cbinfo = $this->course_model->get_c_b ( $courseid );
			// var_dump($mcinfo);exit;
			$html = $this->_view ( 'setbooks', array (
					'courseid' => $courseid,
					'bookinfo' => $bookinfo,
					'cbinfo' => $cbinfo ,
					'up'=>0,
					'next'=>1
			), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
		//编辑
		function edit() {
			$id = intval ( $this->input->get ( 'id' ) );
			$scholarship = $this->course_model->get_scholarshi ();
			$publics = CF ( 'publics', '', CONFIG_PATH );
			$major = $this->course_model->get_major ();
			if ($id) {
				$where = "id={$id}";
				$info = $this->course_model->get_one ( $where );
				if (empty ( $info )) {
					ajaxReturn ( '', '该学院不存在', 0 );
				}
			}
			$templates = $this->course_model->get_templates ();
			$attachments = $this->course_model->get_attachments ();
			
			$this->_view ( 'course_edit', array (
					'info' => $info,
					'scholarship' => ! empty ( $scholarship ) ? $scholarship : array (),
					'publics' => ! empty ( $publics ) ? $publics : array (),
					'major' => ! empty ( $major ) ? $major : array (),
					'applytemplate' => ! empty ( $templates ) ? $templates : array (),
					'attatemplate' => ! empty ( $attachments ) ? $attachments : array (),
					'programs' => $this->programaids_course 
			) );
		}
		function add() {
			$publics = CF ( 'publics', '', CONFIG_PATH );
			$scholarship = $this->course_model->get_scholarshi ();
			$major = $this->course_model->get_major ();
			
			$templates = $this->course_model->get_templates ();
			$attachments = $this->course_model->get_attachments ();
			$this->_view ( 'course_edit', array (
					'publics' => ! empty ( $publics ) ? $publics : array (),
					'scholarship' => ! empty ( $scholarship ) ? $scholarship : array (),
					'major' => ! empty ( $major ) ? $major : array (),
					'applytemplate' => ! empty ( $templates ) ? $templates : array (),
					'attatemplate' => ! empty ( $attachments ) ? $attachments : array (),
					'programs' => $this->programaids_course 
			) );
		}
		function del() {
			$id = intval ( $this->input->get ( 'id' ) );
			
			if ($id) {
				
				$is = $this->course_model->delete ( $id );
				if ($is === true) {
					$this->course_model->delete_guanlian($id);
					ajaxReturn ( '', '删除成功', 1 );
				}
			}
			ajaxReturn ( '', '删除失败', 0 );
		}
		/**
		 * 插入
		 */
		function insert() {
			$data = $this->input->post ();
			if(!empty($data['term_start'])){
					$data['term_start']=json_encode($data['term_start']);
				}
			if (! empty ( $data )) {
				if (! empty ( $data ['opentime'] )) {
					$data ['opentime'] = strtotime ( $data ['opentime'] );
				}
				if (! empty ( $data ['endtime'] )) {
					$data ['endtime'] = strtotime ( $data ['endtime'] );
				}
				
				$id = $this->course_model->save ( null, $data );
				if ($id) {
					
					ajaxReturn ( 'back', '添加成功', 1 );
				}
			}
			ajaxReturn ( '', '添加失败', 0 );
		}
		function update() {
			$id = intval ( $this->input->post ( 'id' ) );
			
			if ($id) {
				$data = $this->input->post ();
				if(!empty($data['term_start'])){
					$data['term_start']=json_encode($data['term_start']);
				}else{
					$data['term_start']='';
				}
				if (! empty ( $data ['opentime'] )) {
					$data ['opentime'] = strtotime ( $data ['opentime'] );
				}
				if (! empty ( $data ['endtime'] )) {
					$data ['endtime'] = strtotime ( $data ['endtime'] );
				}
				// 保存基本信息
				$this->course_model->save ( $id, $data );
				ajaxReturn ( '', '更新成功', 1 );
			}
			ajaxReturn ( '', '更新失败', 0 );
		}
		
		/**
		 * 修改管理员的状态
		 */
		function upstate() {
			$id = intval ( $this->input->get_post ( 'id' ) );
			$state = intval ( $this->input->get_post ( 'state' ) );
			if (! empty ( $id )) {
				$result = $this->course_model->save_audit ( $id, $state );
				
				if ($result === true) {
					$admininfo = $this->course_model->get_one ( 'id = ' . $id );
					$statelog = array (
							'禁用',
							'启用' 
					);
					
					ajaxReturn ( '', '更改成功', 1 );
				} else {
					ajaxReturn ( '', '', 0 );
				}
			} else {
				ajaxReturn ( '', '', 0 );
			}
		}
		
		/**
		 * 批量修改
		 */
		function get_html() {
			$ids = trim ( $this->input->get ( 'ids' ) );
			$edit_type = trim ( $this->input->get ( 'edit_fei' ) );
			$publics = CF ( 'publics', '', CONFIG_PATH );
			if (! empty ( $ids ) && ! empty ( $edit_type )) {
				$type = explode ( '-', $edit_type );
				switch ($type [1]) {
					// 输入框
					case 1 :
						break;
					// 日期
					case 2 :
						break;
					// 单选
					case 3 :
						$data = array (
								'0' => '选课',
								'1' => '安排' 
						);
						break;
					case 4 :
						switch ($type [0]) {
							case 'majorid' :
								$data = $this->course_model->get_major ();
								break;
							case 'degreeid' :
								$degree = CF ( 'degree', '', CONFIG_PATH );
								foreach ( $degree as $k => $v ) {
									$data [$v ['id']] = $v ['title'];
								}
								break;
							case 'language' :
								$data = $publics ['language'];
								break;
							case 'hsk' :
								$data = $publics ['hsk'];
								break;
							case 'minieducation' :
								$data = $publics ['education'];
								break;
							case 'isapply' :
								$data = $publics ['isapply'];
								break;
							case 'attatemplate' :
								$data = $this->course_model->get_attachments ();
								break;
							case 'applytemplate' :
								$data = $this->course_model->get_templates ();
								break;
							case 'scholarship' :
								$data = $this->course_model->get_scholarshi ();
								break;
							case 'state' :
								$data = array (
										'0' => '禁用',
										'1' => '启用' 
								);
								break;
							case 'difficult' :
								$data = $publics ['difficult'];
								break;
						}
						break;
					case 5 :
						break;
				}
				
				$editfield = array (
						'majorid' => '专业',
						'degreeid' => '学历',
						'hour' => '学时',
						'absenteeism' => '缺勤通知线',
						'expel' => '开除通知线',
						'variable' => '是否选修',
						'opentime' => '开学日期',
						'endtime' => '申请截至日期',
						'schooling' => '指定学制',
						'tuition' => '学费',
						'applytuition' => '指定申请费',
						'language' => '授课语言',
						'hsk' => 'HSK要求',
						'minieducation' => '最低学历要求',
						'isapply' => '是否可以申请',
						'attatemplate' => '指定附件模版',
						'applytemplate' => '指定申请表模版',
						'scholarship' => '奖学金设置',
						'state' => '状态',
						'difficult' => '录取难度',
						'feature' => '课程特色',
						'introduce' => '课程介绍',
						'requirement' => '入学要求',
						'applymaterial' => '申请材料',
						'video' => '课程视频' 
				);
				$html = $this->_view ( 'get_html', array (
						'name' => $type [0],
						'type' => $type [1],
						'data' => ! empty ( $data ) ? $data : array (),
						'ids' => $ids,
						'program_unit' => $publics ['program_unit'],
						'editfield' => $editfield 
				), true );
				ajaxReturn ( $html, '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		}
		
		/**
		 * 修改 批量
		 */
		function someupdate() {
			$data = $this->input->post ();
			
			if (! empty ( $data )) {
				if (! empty ( $data ['ids'] )) {
					$ids = $data ['ids'];
					unset ( $data ['ids'] );
				}
				if (! empty ( $data ['type'] )) {
					$type = $data ['type'];
					unset ( $data ['type'] );
				}
				if ($type == 2) {
					foreach ( $data as $k => $v ) {
						$data [$k] = strtotime ( $v );
					}
				}
				
				$idall = explode ( ',', $ids );
				foreach ( $idall as $k => $v ) {
					if (empty ( $v )) {
						unset ( $idall [$k] );
					}
				}
				$id = implode ( ',', $idall );
				
				$where = 'id IN (' . $id . ')';
				
				$flag = $this->course_model->someupdate ( $where, $data );
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
		 * 专业
		 */
		function sel_majorid() {
			$degreeid = intval ( trim ( $this->input->get ( 'degreeid' ) ) );
			if ($degreeid) {
				$data = $this->db->select ( '*' )->get_where ( 'major', 'state = 1 AND degree = ' . $degreeid )->result_array ();
				$html = '<option value="">--请选择--</option>';
				foreach ( $data as $key => $value ) {
					$html .= '<option value=' . $value['id'] . '>' . $value ['name'] . '</option>';
				}
				ajaxReturn ( $html, '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		}
		
		/**
		 * 复制 课程
		 */
		function copycourse() {
			$id = intval ( $this->input->get ( 'id' ) );
			
			if ($id) {
				$course = $this->course_model->get_one ( 'id = ' . $id );
				$data = ( array ) $course;
				if (! empty ( $data )) {
					unset ( $data ['id'] );
					$flag = $this->course_model->save ( null, $data );
					if ($flag) {
						ajaxReturn ( '', '', 1 );
					} else {
						ajaxReturn ( '', '', 0 );
					}
				} else {
					ajaxReturn ( '', '', 0 );
				}
			}
			ajaxReturn ( '', '更新失败', 0 );
		}
		
		/**
		 * 编辑课程
		 * 语言课程
		 */
		function langedit() {
			$id = intval ( trim ( $this->input->get ( 'id' ) ) );
			$site_language = intval ( trim ( $this->input->get ( 'site_language' ) ) );
			if ($id && $site_language) {
				$where = "courseid = {$id} AND site_language = {$site_language}";
				$result = $this->course_model->get_course_content ( $where );
				// $imgs = $this->course_model->get_atlas_new ( 'courseid = ' . $id . ' AND site_language=' . $site_language );
			}
			$this->_view ( 'course_editcourse', array (
					'info' => ! empty ( $result ) ? $result : array (),
					'id' => $id,
					'site_language' => $site_language,
					'site_language_admin' => $this->site_language_admin 
			// 'imgs' => ! empty ( $imgs ) ? $imgs : array ()
						) );
		}
		
		/**
		 * 保存 信息
		 */
		function savecourse() {
			$data = $this->input->post ();
			if (! empty ( $data ['courseid'] ) && ! empty ( $data ['site_language'] )) {
				$this->course_model->del_course ( array (
						'courseid' => $data ['courseid'],
						'site_language' => $data ['site_language'] 
				) );
				// 上传缩略图
				// if (! empty ( $_FILES ['imagefile'] ['name'] )) {
				// $data ['image'] = $this->_upload ();
				// }
				// $imgs = $this->input->post ( 'imgs' );
				
				// if (! empty ( $imgs )) {
				// $imgs ['site_language'] = $data ['site_language'];
				// $imgs ['courseid'] = $data ['courseid'];
				// $result = $this->course_model->save_atlas ( 'courseid = ' . $data ['courseid'] . ' AND site_language=' . $data ['site_language'], $imgs );
				// }
				// unset($data['imgs']);
				$flag = $this->course_model->save_course ( null, $data );
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
		 * 获取单页栏目ID
		 *
		 * @return multitype:Ambigous <>
		 */
		private function _get_programaids() {
			$programa = CF ( 'programa', '', 'application/cache/' );
			
			$programas = menu_tree ( $this->programaid_parent, $programa, 'programaid' );
			$this->programaids = find_child_str ( $programas, 'programaid' );
			$this->programaids_where = '\'' . str_replace ( " , ", " ", implode ( "','", explode ( ',', $this->programaids ) ) ) . '\'';
			
			$array = array ();
			if (! empty ( $this->programaids ) && ! empty ( $programa )) {
				$programa_fmba = explode ( ',', $this->programaids );
				if (! empty ( $programa_fmba ) && is_array ( $programa_fmba )) {
					foreach ( $programa_fmba as $programaid ) {
						if ($programa [$programaid] ['moduleid'] == 9) {
							$array [$programaid] = $programa [$programaid];
						}
					}
				}
			}
			// 干掉名师团队这个栏目
			foreach ( $array as $k => $v ) {
				// if ($v ['title'] == 'NEWS&EVENTS') {
				// unset ( $array [$k] );
				// }
				// if ($v ['title'] == '国际交流') {
				// unset ( $array [$k] );
				// }
				// if ($v ['title'] == '友情链接') {
				// unset ( $array [$k] );
				// }
			}
			
			return $array;
		}
		
		/**
		 * 设置列表字段
		 */
		private function _set_lists_field() {
			return array (
					'id',
					'name',
					'englishname',
					'hour',
					'credit',
					'absenteeism',
					'expel',
					'variable',
					'state' 
			);
		}

		/**
		 *
		 *导出
		 **/
		function export(){
			$this->load->library ( 'sdyinc_export' );
			$d = $this->sdyinc_export->do_export_course ();
			if (! empty ( $d )) {
				$this->load->helper ( 'download' );
				force_download ( 'course' . time () . '.xlsx', $d );
				return 1;
			}
		}

	 /**
	 * 导入页面
	 */
	function tochanel() {
		$s = intval ( $this->input->get ( 's' ) );
		if (! empty ( $s )) {
			$html = $this->_view ( 'tochanel', array (), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 * 导出模板
	 */
	function tochaneltenplate() {
		$data = $this->course_model->get_course_fields ();
		$this->load->library ( 'sdyinc_export' );
		$d = $this->sdyinc_export->course_tochaneltenplate ( $data );
		if (! empty ( $d )) {
			$this->load->helper ( 'download' );
			force_download ( 'course' . time () . '.xlsx', $d );
			return 1;
		}
	}

		/**
	 *
	 *上传major
	 **/
	function upload_excel(){
		    //判断文件类型，如果不是"xls"或者"xlsx"，则退出
        if ( $_FILES["file"]["type"] == "application/vnd.ms-excel" ){
                $inputFileType = 'Excel5';
        }
        elseif ( $_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ){
                $inputFileType = 'Excel2007';
        }
        else {
                echo "Type: " . $_FILES["file"]["type"] . "<br />";
                echo "您选择的文件格式不正确";
                exit();
        }
        
        if ($_FILES["file"]["error"] > 0)
        {
                echo "Error: " . $_FILES["file"]["error"] . "<br />";
                exit();
        }
		$str = $_SERVER ['DOCUMENT_ROOT'] . '/uploads/work/' . date ( 'Y' ) . '/' . date ( 'md' ) . '/';
			if(!is_dir($str)){
				mk_dir($str);
			}
        $inputFileName =$str.'/'.$_FILES["file"]["name"];
        if (file_exists($inputFileName))
        {
                //echo $_FILES["file"]["name"] . " already exists. <br />";
                unlink($inputFileName);    //如果服务器上存在同名文件，则删除
        }
        else
        {
        }
        move_uploaded_file($_FILES["file"]["tmp_name"],$inputFileName);
        echo "Stored in: " . $inputFileName;
        $this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');
		$this->load->library('PHPExcel/Writer/Excel2007');
        $objReader = IOFactory::createReader($inputFileType);
        $WorksheetInfo = $objReader->listWorksheetInfo($inputFileName);
        
         //设置只读，可取消类似"3.08E-05"之类自动转换的数据格式，避免写库失败
        $objReader->setReadDataOnly(true);
      
        $objPHPExcel = $objReader->load($inputFileName);
        $sheetData = $objPHPExcel->getSheet(0)->toArray(null,true,true,true);
        //excel2003文件，可使用'$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);'
        //excel2007文件使用"getActiveSheet()"方法时会提示出错：对non-object使用了"toArray"方法。    
         $keywords = $sheetData[1];
   	     $num=count($sheetData[1]);
         $warning = '上传文件字段格式不正确，请修改后重新上传！<br />';
        $columns = array ( 'A', 'B', 'C', 'D', 'E', 'F', 'G' );
        $mfields=$this->course_model->get_course_fields();
         if($num!=count($mfields)){
        	echo '字段个数不匹配';
        	exit();
        }
        $keysInFile = array ( );
        foreach ($mfields as $key => $value) {
        	$keysInFile[]=$value;
        }
        foreach( $columns as $keyIndex => $columnIndex ){
                if ( $keywords[$columnIndex] != $keysInFile[$keyIndex] ){
                        echo $warning . $columnIndex . '列应为' . $keysInFile[$keyIndex] . '，而非' . $keywords[$columnIndex];
                         unlink($inputFileName);   
                        exit();
                }
        }
        $insert='';
        foreach ($mfields as $k => $v) {
        	$insert.=$k.',';
        }
        $insert=trim($insert,',');
        unset($sheetData[1]);
			$i=65;
			$ss=2;
			$str='';
			foreach ($sheetData as $k => $v) {
				$value='';
				foreach ($v as $kk => $vv) {

					if($kk=='F'){
						$vv=$vv=='是'?1:0;
						$value.='"'.$vv.'",';
					}elseif($kk=='G'){
						$vv=$vv=='是'?1:0;
						$value.='"'.$vv.'",';
					}else{

						$value.='"'.$vv.'",';
					}
				}
				$value=trim($value,',');
				$count=$this->course_model->check_course($insert,$value);
				if($count>0){
					$str.='<br />excel中的'.$ss."行专业名与数据库重复";
					$ss++;
					continue;
				}
				$this->course_model->insert_fields($insert,$value);
			$i++;
			$ss++;
			}
			if($str!=''){
				echo $str;
			}
	}
	//设置关联书籍
	function set_c_b() {
		$data = $this->input->post ();
		
		if (! empty ( $data )) {
			
			$id = $this->course_model->save_books ( $data );
			
			if ($id == 'del') {
				ajaxReturn ( '', '删除成功', 1 );
			}
			if ($id) {
				
				ajaxReturn ( $id, '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	//书籍上一页
	function up_books(){
		$num=$this->input->get('page');
		$courseid = $this->input->get ( 'courseid' );
		$search=$this->input->post();
		if(!empty($search['search'])){
			$data=$this->course_model->get_search_booksinfo($search['search']);
			$num_count=count($data);
			$e=$num*10;
			$s=$e-10;

			$arr=array();
			foreach ($data as $k => $v) {
				if(($k+1)>$s&&($k+1)<=$e){
					$arr[]=$v;
				}
			}
			$mcinfo = $this->course_model->get_c_b ( $courseid );
			$arrs['c']=$arr;
			$arrs['num']=$num-1;
			$arrs['mc']=$mcinfo;
			ajaxreturn($arrs,'',1);
		}else{
			$data=$this->course_model->get_books();
			$num_count=count($data);
			$e=$num*10;
			$s=$e-10;

			$arr=array();
			foreach ($data as $k => $v) {
				if(($k+1)>$s&&($k+1)<=$e){
					$arr[]=$v;
				}
			}
			$mcinfo = $this->course_model->get_c_b ( $courseid );
			$arrs['c']=$arr;
			$arrs['num']=$num-1;
			$arrs['mc']=$mcinfo;
			ajaxreturn($arrs,'',1);
		}
		
	}
	//课程下一页
	function next_books(){
		$num=$this->input->get('page');
		$courseid = $this->input->get ( 'courseid' );
		$search=$this->input->post();
		if(!empty($search['search'])){
			$data=$this->course_model->get_search_booksinfo($search['search']);
			$num_count=count($data);
			if($num>$num_count/10){
					ajaxreturn('','已经是最后一页了',0);
				}
			$s=$num*10;
			$e=$s+10;
			$arr=array();
			foreach ($data as $k => $v) {
				if(($k+1)>$s&&($k+1)<=$e){
					$arr[]=$v;
				}
			}
			$mcinfo = $this->course_model->get_c_b ( $courseid );
			$arrs['c']=$arr;
			$arrs['num']=$num+1;
			$arrs['mc']=$mcinfo;
			ajaxreturn($arrs,'',1);
		}else{
			$data=$this->course_model->get_books();
			$num_count=count($data);
			if($num>$num_count/10){
					ajaxreturn('','已经是最后一页了',0);
				}
			$s=$num*10;
			$e=$s+10;
			$arr=array();
			foreach ($data as $k => $v) {
				if(($k+1)>$s&&($k+1)<=$e){
					$arr[]=$v;
				}
			}
			$mcinfo = $this->course_model->get_c_b ( $courseid );
			$arrs['c']=$arr;
			$arrs['num']=$num+1;
			$arrs['mc']=$mcinfo;
			ajaxreturn($arrs,'',1);
		}
		
	}
	//书籍搜索
	function get_search_books(){
		$data=$this->input->post();
		$courseid = $this->input->get ( 'courseid' );
		$mcinfo = $this->course_model->get_c_b ( $courseid );
		if(!empty($data['search'])){
			$result=$this->course_model->get_search_booksinfo_limit($data['search']);
			if(!empty($result)){
				$arrs['c']=$result;
				$arrs['mc']=$mcinfo;
				$arrs['num']=0;
				ajaxReturn($arrs,'',1);
			}else{
				ajaxReturn('','没有该课程',0);
			}
		}else{
			$courseinfo = $this->course_model->get_books_limit ();
			$arrs['c']=$courseinfo;
			$arrs['mc']=$mcinfo;
			$arrs['num']=0;
			ajaxReturn($arrs,'',1);
		}
	}
}