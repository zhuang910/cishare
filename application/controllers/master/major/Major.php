<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Major extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/major/';
		$this->load->model ( $this->view . 'major_model' );
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		// for($i=147;$i<157;$i++){
		// 	$this->db->query("UPDATE `zust_major` SET `opentime` = 1441036800, `endtime` = 1439568000, `schooling` = '3-8', `xzunit` = '4', `tuition` = '7500', `applytuition` = '400', `danwei` = '1', `language` = '2', `hsk` = '4', `minieducation` = '1', `isapply` = '1', `difficult` = '1', `applytemplate` = '6874', `attatemplate` = '1187', `orderby` = '0 ' WHERE `id` = ".$i);
		// }
		// exit;
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->major_model->count ( $condition );
			$output ['aaData'] = $this->major_model->get ( $fields, $condition );
			// var_dump($output['aaData']);exit;
			foreach ( $output ['aaData'] as $item ) {
				$state = $item->state;
				$item->state = $this->_get_lists_state ( $item->state );
				if (! empty ( $item->degree )) {
					$item->degree = $this->major_model->get_degree ( $item->degree );
				} else {
					$item->degree = '';
				}
				
				$item->squadnum = '<a href="squad?id=' . $item->id . '">' . $item->squadnum . '</a>';
				$item->coursenum = '<a href="javascript:;" onclick="pub_alert_html(\'' . $this->zjjp . 'major/major/add_course?majorid=' . $item->id . '&s=1\')" role="button" class="blue" data-toggle="modal">' . $item->coursenum . '</a>';
				// $item->coursenum='<a class="green" title="分班" onblur="input('.$item->id.')" data-toggle="modal" role="button" href="#modal-table">'.$item->coursenum.'</i></a>';
				$item->operation = '<div class="btn-group"><a class="btn btn-xs btn-info" href="' . $this->zjjp . 'major/major' . '/edit?id=' . $item->id . '">编辑</a><button data-toggle="dropdown" class="btn btn-xs btn-info btn-white dropdown-toggle">
								更多
								<span class="ace-icon fa fa-caret-down icon-only"></span>
							</button>
							<ul class="dropdown-menu dropdown-info dropdown-menu-right">';
				if ($state == 1) {
					$item->operation .= '<li><a href="javascript:;" onclick="upstate(' . $item->id . ',0)"  title="点击禁用" id="upstate">点击禁用</a></li>';
				} else {
					$item->operation .= '<li><a href="javascript:;" onclick="upstate(' . $item->id . ',1)" title="点击启用" id="upstate">点击启用</a></li>';
				}
				$item->operation .= '<li><a href="' . $this->zjjp . 'major/major' . '/extendedit?id=' . $item->id . '" title="属性">属性</a></li>';
				$item->operation .= '
				<li class="divider"></li>
					<li><a title="删除" href="javascript:;" onclick="del(' . $item->id . ')">删除</a></li>
				';
				$item->operation.='</ul></div>';
				
				
				
				
				
//				$item->operation .= ' &nbsp;<a class="green" href="' . $this->zjjp . 'major/majorimg' . '/index?majorid=' . $item->id . '" title="添加图集"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
//				$item->operation .= ' &nbsp;<a class="green" href="' . $this->zjjp . 'major/majorpl' . '/index?majorid=' . $item->id . '" title="添加评论"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
				// 循环出来 课程语言编辑
//				$item->operation .= ' &nbsp;<a class="green" href="' . $this->zjjp . 'major/major' . '/langedit?id=' . $item->id . '&label_id=' . $_SESSION ['language'] . '" title="编辑信息专业"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
			}
			
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'major_index' );
	}
	/**
	 * [add_course 添加课程]
	 */
	function add_course() {
		$courseinfo = $this->major_model->get_course_limit ();
		
		$s = intval ( $this->input->get ( 's' ) );
		if (! empty ( $s )) {
			$majorid = $this->input->get ( 'majorid' );
			$mcinfo = $this->major_model->get_m_c ( $majorid );
			// var_dump($mcinfo);exit;
			$html = $this->_view ( 'setcourse', array (
					'majorid' => $majorid,
					'courseinfo' => $courseinfo,
					'mcinfo' => $mcinfo,
					'up' => 0,
					'next' => 1 
			), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	// 课程下一页
	function next_course() {
		$num = $this->input->get ( 'page' );
		$majorid = $this->input->get ( 'majorid' );
		$search = $this->input->post ();
		if (! empty ( $search ['search'] )) {
			$data = $this->major_model->get_search_courseinfo ( $search ['search'] );
			$num_count = count ( $data );
			if ($num > $num_count / 10) {
				ajaxreturn ( '', '已经是最后一页了', 0 );
			}
			$s = $num * 10;
			$e = $s + 10;
			$arr = array ();
			foreach ( $data as $k => $v ) {
				if (($k + 1) > $s && ($k + 1) <= $e) {
					$arr [] = $v;
				}
			}
			$mcinfo = $this->major_model->get_m_c ( $majorid );
			$arrs ['c'] = $arr;
			$arrs ['num'] = $num + 1;
			$arrs ['mc'] = $mcinfo;
			ajaxreturn ( $arrs, '', 1 );
		} else {
			$data = $this->major_model->get_course ();
			$num_count = count ( $data );
			if ($num > $num_count / 10) {
				ajaxreturn ( '', '已经是最后一页了', 0 );
			}
			$s = $num * 10;
			$e = $s + 10;
			$arr = array ();
			foreach ( $data as $k => $v ) {
				if (($k + 1) > $s && ($k + 1) <= $e) {
					$arr [] = $v;
				}
			}
			$mcinfo = $this->major_model->get_m_c ( $majorid );
			$arrs ['c'] = $arr;
			$arrs ['num'] = $num + 1;
			$arrs ['mc'] = $mcinfo;
			ajaxreturn ( $arrs, '', 1 );
		}
	}
	// 课程上一页
	function up_course() {
		$num = $this->input->get ( 'page' );
		$majorid = $this->input->get ( 'majorid' );
		$search = $this->input->post ();
		if (! empty ( $search ['search'] )) {
			$data = $this->major_model->get_search_courseinfo ( $search ['search'] );
			$num_count = count ( $data );
			$e = $num * 10;
			$s = $e - 10;
			
			$arr = array ();
			foreach ( $data as $k => $v ) {
				if (($k + 1) > $s && ($k + 1) <= $e) {
					$arr [] = $v;
				}
			}
			$mcinfo = $this->major_model->get_m_c ( $majorid );
			$arrs ['c'] = $arr;
			$arrs ['num'] = $num - 1;
			$arrs ['mc'] = $mcinfo;
			ajaxreturn ( $arrs, '', 1 );
		} else {
			$data = $this->major_model->get_course ();
			$num_count = count ( $data );
			$e = $num * 10;
			$s = $e - 10;
			
			$arr = array ();
			foreach ( $data as $k => $v ) {
				if (($k + 1) > $s && ($k + 1) <= $e) {
					$arr [] = $v;
				}
			}
			$mcinfo = $this->major_model->get_m_c ( $majorid );
			$arrs ['c'] = $arr;
			$arrs ['num'] = $num - 1;
			$arrs ['mc'] = $mcinfo;
			ajaxreturn ( $arrs, '', 1 );
		}
	}
	// 课程搜索
	function get_search_course() {
		$data = $this->input->post ();
		$majorid = $this->input->get ( 'majorid' );
		$mcinfo = $this->major_model->get_m_c ( $majorid );
		if (! empty ( $data ['search'] )) {
			$result = $this->major_model->get_search_courseinfo_limit ( $data ['search'] );
			if (! empty ( $result )) {
				$arrs ['c'] = $result;
				$arrs ['mc'] = $mcinfo;
				$arrs ['num'] = 0;
				ajaxReturn ( $arrs, '', 1 );
			} else {
				ajaxReturn ( '', '没有该课程', 0 );
			}
		} else {
			$courseinfo = $this->major_model->get_course_limit ();
			$arrs ['c'] = $courseinfo;
			$arrs ['mc'] = $mcinfo;
			$arrs ['num'] = 0;
			ajaxReturn ( $arrs, '', 1 );
		}
	}
	// 编辑
	function edit() {
		$fdata = $this->major_model->get_faculty ();
		$degree = CF ( 'degree', '', CONFIG_PATH );
		
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id={$id}";
			$info = $this->major_model->get_one ( $where );
			$where_tuition = "majorid={$id}";
			$info_tuition = $this->major_model->get_major_tuition ( $where_tuition );
			if (empty ( $info )) {
				ajaxReturn ( '', '该学院不存在', 0 );
			}
		}
		$scholarship = $this->major_model->get_scholarshi ();
		$this->_view ( 'major_edit', array (
				
				'info' => $info,
				'degree' => $degree,
				'fdata' => $fdata,
				'info_tuition' => $info_tuition,
				'scholarship' => ! empty ( $scholarship ) ? $scholarship : array () 
		) );
	}
	
	/**
	 * 编辑属性
	 */
	function extendedit() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id={$id}";
			$info = $this->major_model->get_one ( $where );
			if (empty ( $info )) {
				ajaxReturn ( '', '该学院不存在', 0 );
			}
		}
		$publics = CF ( 'publics', '', CONFIG_PATH );
		
		$templates = $this->major_model->get_templates ();
		$attachments = $this->major_model->get_attachments ();
		
		$this->_view ( 'major_extendedit', array (
				'info' => $info,
				
				'publics' => ! empty ( $publics ) ? $publics : array (),
				
				'applytemplate' => ! empty ( $templates ) ? $templates : array (),
				'attatemplate' => ! empty ( $attachments ) ? $attachments : array () 
		) );
	}
	/**
	 * 更新学期
	 */
	function update_term() {
		// 获取所有的班级id
		$squad_id_all = $this->major_model->get_squadid_all ();
		if (! empty ( $squad_id_all )) {
			foreach ( $squad_id_all as $k => $v ) {
				if (! empty ( $v ['majorid'] )) {
					// 获取该班级所属的专业id抓取跨度
					$major_span = $this->major_model->get_major_span ( $v ['majorid'] );
					// 获取该班级的跨度
					$squad_span = $this->major_model->get_squad_span ( $v ['id'] );
					// 获取跨度值
					if (! empty ( $squad_span )) {
						$span = $squad_span;
					} else {
						$span = $major_span;
					}
					// 判断跨度是否为空
					if (empty ( $span )) {
						continue;
					}
					// 获取该班级的开班时间
					$squad_classtime = $this->major_model->get_squad_time ( $v ['id'] );
					$s = floor ( (time () - $squad_classtime) / 24 / 3600 / $span );
					// 获取该班级所在专业的学期数
					$major_num = $this->major_model->get_major_num ( $v ['majorid'] );
					if ($s >= $major_num) {
						// 结束该班级
						$this->major_model->end_squad ( $v ['id'] );
					} else {
						$this->major_model->update_squad_term ( $v ['id'], $s + 1 );
					}
				}
			}
			// foreach
		}
		// if(!empty($squad_id_all)){
	}
	/**
	 * 保存属性
	 */
	function save_extend() {
		$data = $this->input->post ();
		if (! empty ( $data ) && ! empty ( $data ['id'] )) {
			$id = $data ['id'];
			unset ( $data ['id'] );
			if (! empty ( $data ['opentime'] )) {
				
				$data ['opentime'] = strtotime ( $data ['opentime'] );
			}
			
			if (! empty ( $data ['endtime'] )) {
				$data ['endtime'] = strtotime ( $data ['endtime'] );
			}
			$flag = $this->major_model->save ( $id, $data );
			if ($flag) {
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	// 添加界面
	function add() {
		$degree = CF ( 'degree', '', CONFIG_PATH );
		$scholarship = $this->major_model->get_scholarshi ();
		$fdata = $this->major_model->get_faculty ();
		$this->_view ( 'major_edit', array (
				'fdata' => $fdata,
				'degree' => $degree,
				'scholarship' => ! empty ( $scholarship ) ? $scholarship : array () 
		) );
	}
	// 删除
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		
		if ($id) {
			$where = "id = {$id}";
			$is = $this->major_model->delete ( $where );
			if ($is === true) {
				$this->major_model->delete_guanlian ( $id );
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	// 更新
	function update() {
		$id = intval ( $this->input->post ( 'id' ) );
		if ($id) {
			$data = $this->input->post ();
			if (! empty ( $data ['scholarship'] )) {
				$data ['scholarship'] = implode ( ',', $data ['scholarship'] );
			} else {
				$data ['scholarship'] = null;
			}
			$total_tuition = !empty($data ['total_tuition'])?$data ['total_tuition']:'';
			// var_dump($total_tuition);exit;
			
			unset ( $data ['total_tuition'] );
			// 保存基本信息
			$this->major_model->save ( $id, $data );
			if(!empty($data['total_tuition'])){
				$this->major_model->update_major_tuition ( $id, $total_tuition );
			}
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	/**
	 * 插入
	 */
	function insert() {
		$data = $this->input->post ();
		if (! empty ( $data )) {
			$total_tuition = !empty($data ['total_tuition'])?$data ['total_tuition']:'';
			
			unset ( $data ['total_tuition'] );
			if (! empty ( $data ['scholarship'] )) {
				$data ['scholarship'] = implode ( ',', $data ['scholarship'] );
			} else {
				$data ['scholarship'] = null;
			}
			$id = $this->major_model->save ( null, $data );
			if ($id && !empty($total_tuition)) {
				$this->major_model->insert_major_tuition ( $id, $total_tuition );
				
			}
			ajaxReturn ( 'back', '添加成功', 1 );
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'zust_major.id',
				'zust_major.name',
				'zust_faculty.name',
				'zust_major.englishname',
				'alias',
				'degree',
				'termnum',
				'termdays',
				'coursenum',
				'squadnum',
				'zust_major.state' 
		);
	}
	
	/**
	 * 获取文章状态
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
	function set_m_c() {
		$data = $this->input->post ();
		
		if (! empty ( $data )) {
			
			$id = $this->major_model->save_course ( $data );
			
			if ($id == 'del') {
				ajaxReturn ( '', '删除成功', 1 );
			}
			if ($id) {
				
				ajaxReturn ( $id, '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	
	/**
	 * 修改管理员的状态
	 */
	function upstate() {
		$id = intval ( $this->input->get_post ( 'id' ) );
		$state = intval ( $this->input->get_post ( 'state' ) );
		if (! empty ( $id )) {
			$result = $this->major_model->save_audit ( $id, $state );
			if ($result === true) {
				$admininfo = $this->major_model->get_one ( 'id = ' . $id );
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
	 * 编辑专业
	 * 语言专业
	 */
	function langedit() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		$site_language = trim ( $this->input->get ( 'label_id' ) );
		if ($id && $site_language) {
			$where = "majorid = {$id} AND site_language = '{$site_language}'";
			$result = $this->major_model->get_course_content ( $where );
			// $imgs = $this->course_model->get_atlas_new ( 'courseid = ' . $id . ' AND site_language=' . $site_language );
		}
		
		$this->_view ( 'major_editmajor', array (
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
	function save_major_lang() {
		$data = $this->input->post ();
		if (! empty ( $data ['aid'] )) {
			unset ( $data ['aid'] );
		}
		if (! empty ( $data ['majorid'] ) && ! empty ( $data ['site_language'] )) {
			
			$this->major_model->del_major ( array (
					'majorid' => $data ['majorid'],
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
			$flag = $this->major_model->save_major ( null, $data );
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
	 * 导出页面
	 */
	function export_where() {
		$degree = $this->major_model->get_degree ( null );
		$faculty = $this->major_model->get_facultys ();
		$course = $this->major_model->get_major_course ();
		
		$s = intval ( $this->input->get ( 's' ) );
		if (! empty ( $s )) {
			$html = $this->_view ( 'export_where', array (
					'degree' => $degree,
					'faculty' => $faculty,
					'course' => $course 
			), true );
			ajaxReturn ( $html, '', 1 );
		}
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
		$d = $this->sdyinc_export->do_export_major ( $where );
		if (! empty ( $d )) {
			$this->load->helper ( 'download' );
			force_download ( 'major' . time () . '.xlsx', $d );
			return 1;
		}
	}
	/**
	 * 导出模板
	 */
	function tochaneltenplate() {
		$data = $this->major_model->get_major_fields ();
		$this->load->library ( 'sdyinc_export' );
		$d = $this->sdyinc_export->major_tochaneltenplate ( $data );
		if (! empty ( $d )) {
			$this->load->helper ( 'download' );
			force_download ( 'major' . time () . '.xlsx', $d );
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
		$str = $_SERVER ['DOCUMENT_ROOT'] . '/uploads/work/' . date ( 'Y' ) . '/' . date ( 'md' ) . '/';
		if (! is_dir ( $str )) {
			mk_dir ( $str );
		}
		$inputFileName = $str . '/' . $_FILES ["file"] ["name"];
		
		if (file_exists ( $inputFileName )) {
			// echo $_FILES["file"]["name"] . " already exists. <br />";
			unlink ( $inputFileName ); // 如果服务器上存在同名文件，则删除
		} else {
		}
		move_uploaded_file ( $_FILES ["file"] ["tmp_name"], $inputFileName );
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
		$zjj = $objPHPExcel->getActiveSheet ();
		$zjj->getStyle ( 'K1:K' . $maxRows )->getNumberFormat ()->setFormatCode ( 'YYYY-mm-dd' );
		$zjj->getStyle ( 'L1:L' . $maxRows )->getNumberFormat ()->setFormatCode ( 'YYYY-mm-dd' );
		$zjj->getStyle ( 'M1:M' . $maxRows )->getNumberFormat ()->setFormatCode ( 'YYYY-mm-dd' );
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
				'I',
				'J',
				'K',
				'L',
				'M',
				'N',
				'O',
				'P',
				'Q',
				'R',
				'S',
				'T',
				'U',
				'V',
				'W',
				'X',
				'Y',
				'Z',
				'AA',
				'AB',
				'AC',
				'AD' 
		)
		;
		$mfields = $this->major_model->get_major_fields ();
		unset ( $mfields ['createtime'] );
		if ($num != count ( $mfields )) {
			echo '字段个数不匹配';
			exit ();
		}
		$keysInFile = array ();
		foreach ( $mfields as $key => $value ) {
			$keysInFile [] = $value;
		}
		foreach ( $columns as $keyIndex => $columnIndex ) {
			if ($columnIndex == 'N') {
				if ($keywords [$columnIndex] != '指定学制(例如:3至5)') {
					echo $warning . $columnIndex . '列应为:指定学制(例如:3至5)，而非' . $keywords [$columnIndex];
					exit ();
				}
			} else {
				if ($keywords [$columnIndex] != $keysInFile [$keyIndex]) {
					echo $warning . $columnIndex . '列应为' . $keysInFile [$keyIndex] . '，而非' . $keywords [$columnIndex];
					exit ();
				}
			}
		}
		$publics = CF ( 'publics', '', CONFIG_PATH );
		$insert = '';
		foreach ( $mfields as $k => $v ) {
			if ($k == 'scholarship') {
				$insert .= $k . ',createtime,';
			} else {
				$insert .= $k . ',';
			}
		}
		$insert = trim ( $insert, ',' );
		unset ( $sheetData [1] );
		$i = 65;
		$m = 2;
		$ss = 2;
		$str = '';
		foreach ( $sheetData as $k => $v ) {
			
			$value = '';
			foreach ( $v as $kk => $vv ) {
				
				if ($kk == 'B') {
					$value .= '"' . $this->major_model->get_facultyid ( $vv ) . '",';
				} elseif ($kk == 'E') {
					$value .= '"' . $this->major_model->get_degrees ( $vv ) . '",';
				} elseif ($kk == 'J') {
					$vv = $vv == '是' ? 1 : 0;
					$value .= '"' . $vv . '",';
				} elseif ($kk == 'K' || $kk == 'L' || $kk == 'M') {
					$vv = strtotime ( $zjj->getCell ( $kk . $k )->getFormattedValue () );
					$value .= '"' . $vv . '",';
				} elseif ($kk == 'N') {
					$vv = trim ( $vv, "‘’" );
					$value .= '"' . $vv . '",';
				} elseif ($kk == 'O') {
					$vv = $this->major_model->get_program_unit ( $publics ['program_unit'], $vv );
					$value .= '"' . $vv . '",';
				} elseif ($kk == 'R') {
					$vv = $this->major_model->get_language ( $publics ['language'], $vv );
					$value .= '"' . $vv . '",';
				} elseif ($kk == 'S') {
					$vv = $this->major_model->get_hsk ( $publics ['hsk'], $vv );
					$value .= '"' . $vv . '",';
				} elseif ($kk == 'T') {
					$vv = $this->major_model->get_degree_type ( $publics ['degree_type'], $vv );
					$value .= '"' . $vv . '",';
				} elseif ($kk == 'U') {
					$vv = $vv == '可以申请' ? 1 : 2;
					$value .= '"' . $vv . '",';
				} elseif ($kk == 'V') {
					$vv = $this->major_model->get_attatemplatename ( $vv );
					$value .= '"' . $vv . '",';
				} elseif ($kk == 'W') {
					$vv = $this->major_model->get_applytemplatename ( $vv );
					$value .= '"' . $vv . '",';
				} elseif ($kk == 'X') {
					$arr = explode ( '-', $vv );
					$vv = $arr [0];
					$value .= '"' . $vv . '",';
					$value .= '"' . time () . '",';
				} elseif ($kk == 'Y') {
					$vv = $vv == '高' ? 1 : 2;
					$value .= '"' . $vv . '",';
				} elseif ($kk == 'Z') {
					$vv = $this->major_model->get_difficult ( $publics ['difficult'], $vv );
					$value .= '"' . $vv . '",';
				} elseif ($kk == 'AB') {
					$vv = $vv == '需要' ? 1 : - 1;
					$value .= '"' . $vv . '",';
				} else {
					$value .= '"' . $vv . '",';
				}
			}
			$value = trim ( $value, ',' );
			$count = $this->major_model->check_major ( $insert, $value );
			if ($count > 0) {
				$str .= '<br />excel中的' . $ss . "行专业名与数据库重复";
				$ss ++;
				continue;
			}
			$this->major_model->insert_fields ( $insert, $value );
			$i ++;
			$m ++;
			$ss ++;
		}
		if ($str != '') {
			echo $str;
		} else {
			echo '上传成功';
		}
	}
}