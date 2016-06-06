<?php
defined('BASEPATH') or exit ('No direct script access allowed');

/**
 * 通知邮件配置
 *
 * @author JJ
 *
 */
class Educe extends Master_Basic
{
	protected $programaids_course = array ();
	public $programaid_parent = 0;
	/**
	 * 基础类构造函数
	 */
	function __construct()
	{
		parent::__construct();
		$this->view = 'master/excel/';
		$this->load->model($this->view . 'educe_model');
		$this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');
		$this->load->library('PHPExcel/Writer/Excel2007');
		$this->programaids_course = $this->_get_programaids ();
	}

	/**
	 * 配置主页
	 */
	function index()
	{	//获取所有专业
		$arr=CF('exporttemplate','',CONFIG_PATH);
		$minfo=$this->educe_model->get_major_info();
		$mfields=$this->educe_model->get_fields();
		$cfields=$this->educe_model->get_course_fields();
		$courseinfo=$this->educe_model->get_courseinfo();

		$sfields=$this->educe_model->get_score_fields();

		$checkfields=$this->educe_model->get_check_fields();

		
		$apply=$this->educe_model->get_app_fields();
		//学生导出字段
		$student=$this->educe_model->get_student_fields();
		//考试类型
		$itemsetting=$this->educe_model->get_itemsetting_fields();
		//书籍
		$books=$this->educe_model->get_books_fields();
		//老师
		$teacher=$this->educe_model->get_teacher_fields();
		$this->_view('educe_index', array(
			'mfields'=>$mfields,
			'cfields'=>$cfields,
			'sfields'=>$sfields,
			'minfo'=>$minfo,
			'checkfields'=>$checkfields,
			'courseinfo'=>$courseinfo,
			'apply'=>$apply,
			'arr'=>$arr,
			'student'=>$student,
			'itemsetting'=>$itemsetting,
			'books'=>$books,
			'teacher'=>$teacher
		));
		
	}
	/**
	 * 
	 * 导出major
	 * */
	function educe_major(){
		$data=$this->input->post();
		$num=count($data);
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$objActSheet->mergeCells('A1:'.chr(64+$num).'1');  
		//得到单元格的样式
		$objActSheet->setCellValue ( 'A1', '专业');
		$objStyleA1 = $objActSheet->getStyle('A1');
		//设置字体  
		$objFontA1 = $objStyleA1->getFont();  
		$objFontA1->setName('Courier New');  
		$objFontA1->setSize(15);  
		$objFontA1->setBold(true);  
		$objFontA1->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);  
		$objFontA1->getColor()->setARGB('255000000');   
		//设置对齐方式
		$objAlignA1 = $objStyleA1->getAlignment();  
		$objAlignA1->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
		$objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置填充颜色  
		$objFillA1 = $objStyleA1->getFill();  
		$objFillA1->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
		$objFillA1->getStartColor()->setARGB('FFEEEEEE');  
		$mdata=$this->educe_model->get_all_major($data);
			$r=65;
			foreach ($data as $k => $v) {
				
				$objActSheet->setCellValue ( chr($r).'2', $v);
			$r++;
			}
			$r=65;
			$i=3;
				# code...
				foreach ($mdata as $kk => $vv) {
					foreach ($data as $key => $value) {
						# code...
						if($key=='facultyid'){
							$fname=$this->educe_model->get_facultyname($vv[$key]);
							$objActSheet->setCellValue ( chr($r).$i, $fname);
						}elseif($key=='degree'){
							$dname=$this->educe_model->get_degreename($vv[$key]);
							$objActSheet->setCellValue ( chr($r).$i, $dname);
						}else{
							$objActSheet->setCellValue ( chr($r).$i, $vv[$key]);
						}
						$r++;
					}
					$r=65;
					$i++;
				}
				
		$outputFileName = iconv ( 'UTF-8', 'gb2312', 'major'. time() . '.xlsx' );
		$objWriter->save ($outputFileName );
		$data = file_get_contents($outputFileName);
		unlink($outputFileName);

		$this->load->helper('download');
		force_download('major'. time().'.xlsx', $data);

	}
	/**
	 * 
	 * 导出score
	 * */
	function educe_score(){
		$data=$this->input->post();
		$num=count($data);
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$objActSheet->mergeCells('A1:'.chr(64+$num).'1');  
		//得到单元格的样式
		$objActSheet->setCellValue ( 'A1', '成绩');
		$objStyleA1 = $objActSheet->getStyle('A1');
		//设置字体  
		$objFontA1 = $objStyleA1->getFont();  
		$objFontA1->setName('Courier New');  
		$objFontA1->setSize(15);  
		$objFontA1->setBold(true);  
		$objFontA1->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);  
		$objFontA1->getColor()->setARGB('255000000');   
		//设置对齐方式
		$objAlignA1 = $objStyleA1->getAlignment();  
		$objAlignA1->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
		$objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置填充颜色  
		$objFillA1 = $objStyleA1->getFill();  
		$objFillA1->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
		$objFillA1->getStartColor()->setARGB('FFEEEEEE');  
		$mdata=$this->educe_model->get_all_score($data);
			$r=65;
			foreach ($data as $k => $v) {
				
				$objActSheet->setCellValue ( chr($r).'2', $v);
			$r++;
			}
			$r=65;
			$i=3;
				# code...
				foreach ($mdata as $kk => $vv) {
					foreach ($data as $key => $value) {
						# code...
						if($key=='studentid'){
							$sname=$this->educe_model->get_studentname($vv[$key]);
							$objActSheet->setCellValue ( chr($r).$i, $sname);
						}elseif($key=='majorid'){
							$mname=$this->educe_model->get_majorname($vv[$key]);
							$objActSheet->setCellValue ( chr($r).$i, $mname);
						}elseif($key=='courseid'){
							$cname=$this->educe_model->get_coursename($vv[$key]);
							$objActSheet->setCellValue ( chr($r).$i, $cname);
						}elseif($key=='squadid'){
							$sqname=$this->educe_model->get_squadname($vv[$key]);
							$objActSheet->setCellValue ( chr($r).$i, $sqname);
						}elseif($key=='term'){
							$objActSheet->setCellValue ( chr($r).$i, '第'.$vv[$key].'学期');
						}elseif($key=='scoretype'){
							$tyname=$this->educe_model->get_scoretype($vv[$key]);
							$objActSheet->setCellValue ( chr($r).$i, $tyname);
						}else{
							$objActSheet->setCellValue ( chr($r).$i, $vv[$key]);
						}
						
						$r++;
					}
					$r=65;
					$i++;
				}
				
		$outputFileName = iconv ( 'UTF-8', 'gb2312', 'score'. time() . '.xlsx' );
		$objWriter->save ($outputFileName );
		$data = file_get_contents($outputFileName);
		unlink($outputFileName);

		$this->load->helper('download');
		force_download('score'. time().'.xlsx', $data);

	}
	/**
	 * 
	 * 导出score
	 * */
	function educe_checking(){
		$data=$this->input->post();
		$num=count($data);
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$objActSheet->mergeCells('A1:'.chr(64+$num).'1');  
		//得到单元格的样式
		$objActSheet->setCellValue ( 'A1', '考勤');
		$objStyleA1 = $objActSheet->getStyle('A1');
		//设置字体  
		$objFontA1 = $objStyleA1->getFont();  
		$objFontA1->setName('Courier New');  
		$objFontA1->setSize(15);  
		$objFontA1->setBold(true);  
		$objFontA1->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);  
		$objFontA1->getColor()->setARGB('255000000');   
		//设置对齐方式
		$objAlignA1 = $objStyleA1->getAlignment();  
		$objAlignA1->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
		$objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置填充颜色  
		$objFillA1 = $objStyleA1->getFill();  
		$objFillA1->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
		$objFillA1->getStartColor()->setARGB('FFEEEEEE');  
		$mdata=$this->educe_model->get_all_checking($data);
			$r=65;
			foreach ($data as $k => $v) {
				
				$objActSheet->setCellValue ( chr($r).'2', $v);
			$r++;
			}
			$r=65;
			$i=3;
				# code...
				foreach ($mdata as $kk => $vv) {
					foreach ($data as $key => $value) {
						# code...
						if($key=='studentid'){
							$sname=$this->educe_model->get_studentname($vv[$key]);
							$objActSheet->setCellValue ( chr($r).$i, $sname);
						}elseif($key=='majorid'){
							$mname=$this->educe_model->get_majorname($vv[$key]);
							$objActSheet->setCellValue ( chr($r).$i, $mname);
						}elseif($key=='courseid'){
							$cname=$this->educe_model->get_coursename($vv[$key]);
							$objActSheet->setCellValue ( chr($r).$i, $cname);
						}elseif($key=='squadid'){
							$sqname=$this->educe_model->get_squadname($vv[$key]);
							$objActSheet->setCellValue ( chr($r).$i, $sqname);
						}elseif($key=='nowterm'){
							$objActSheet->setCellValue ( chr($r).$i, '第'.$vv[$key].'学期');
						}elseif($key=='teacherid'){
							$tname=$this->educe_model->get_teachername($vv[$key]);
							$objActSheet->setCellValue ( chr($r).$i, $tname);
						}elseif($key=='date'){
							$objActSheet->setCellValue ( chr($r).$i, date('Y-m-d',$vv[$key]));
						}elseif($key=='type'){
							$tyname=$this->educe_model->get_checktype($vv[$key]);
							$objActSheet->setCellValue ( chr($r).$i, $tyname);
						}elseif($key=='week'){
							$tyname=$this->educe_model->get_week($vv[$key]);
							$objActSheet->setCellValue ( chr($r).$i, $tyname);
						}elseif($key=='knob'){
							$tyname=$this->educe_model->get_hour($vv[$key]);
							$objActSheet->setCellValue ( chr($r).$i, $tyname);
						}else{
							$objActSheet->setCellValue ( chr($r).$i, $vv[$key]);
						}
						
						$r++;
					}
					$r=65;
					$i++;
				}
				
		$outputFileName = iconv ( 'UTF-8', 'gb2312', 'checking'. time() . '.xlsx' );
		$objWriter->save ($outputFileName );
		$data = file_get_contents($outputFileName);
		unlink($outputFileName);

		$this->load->helper('download');
		force_download('checking'. time().'.xlsx', $data);

	}
	/**
	 * 
	 * 获取申请模板
	 * */
	function get_attatemplateid ($cid){
		$data=$this->educe_model->get_attatemplateinfo($cid);
		$data1=$this->educe_model->get_shenqing1($data['tClass_id']);
		$data2=$this->educe_model->get_shenqing2($data1);
		$data3=$this->educe_model->get_shenqing3($data2);
		$data4['s']=$data;
		$data4['k']=$data3;
		ajaxreturn($data4,'',1);
	}
	/**
	 * 
	 * 导出score
	 * */
	function educe_shenqing(){
		$data=$this->input->post();
		$num=count($data);
		unset($data['courseid']);
		unset($data['attatemplate']);
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$abc=$this->educe_model->bc();
			$i=0;
		foreach ($data as $key => $value) {
			
				$objActSheet->setCellValue($abc[$i],$value);
			$i++;
		}
		$shendata=$this->educe_model->get_all_shenqing($data);
			$i=0;
		foreach ($shendata as $key => $value) {
			
				$objActSheet->setCellValue(str_replace(1, 2,$abc[$i]),$value);
			$i++;
		}
		$outputFileName = iconv ( 'UTF-8', 'gb2312', 'shenqing'. time() . '.xlsx' );
		$objWriter->save ($outputFileName );
		$data = file_get_contents($outputFileName);
		unlink($outputFileName);

		$this->load->helper('download');
		force_download('shenqing'. time().'.xlsx', $data);
		

	}

	/**
	 * 
	 * 导出course
	 * */
	function educe_course(){
		$data=$this->input->post();
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$i=65;
		$k=65;
		$coursenum=0;
		$course_content_num=0;
		$r=0;
		foreach ($data as $key => $value) {
			if($value=='主表'){
				$coursenum=$r;
				
			}
			if($value=='content表'){
				$course_content_num=$r;
				
			}
			
			if($i>90){
				$j=65;
				
				$objActSheet->setCellValue ( chr($j).chr($k).'1', $value);
				$k++;
			}else{
				$objActSheet->setCellValue ( chr($i).'1', $value);
			}
			
			$i++;
			$r++;
		}

			// $objActSheet->setCellValue ( 'AR1', 'images排序');
			// $objActSheet->setCellValue ( 'AS1', 'images站点语言');
			$i=0;
			$insert1=array();
			$insert2=array();
			$insert3=array();
			foreach ($data as $key => $value) {
				if($i>$coursenum&&$i<$course_content_num){
					$insert2[]=$key;
				}elseif($i>$course_content_num){
					$insert3[]=$key;
				}else{
					$insert1[]=$key;
				}
				$i++;
			}
			$cdata=$this->educe_model->get_all_course($insert1);
			
			  // var_dump($insert1);
			  //var_dump($cdata);exit;
			//var_dump($data);
			
			$y=2;
			foreach ($cdata as $k => $v) {
				$x=65;
				$a=65;
				$condata=$this->educe_model->get_all_course_content($insert2,$v['id']);
				$imgdata=$this->educe_model->get_all_course_images($insert3,$v['id']);

				foreach ($insert1 as $kk => $vv) {
					if($vv=='line1'||$vv=='line2'){
						continue;
					}
					if($vv=='columnid'){
						$v[$vv]=$this->get_programs($v[$vv]);
					}
					if($vv=='degreeid'){
						$v[$vv]=$this->educe_model-> get_degreename($v[$vv]);
					}
					if($vv=='variable'){
						$v[$vv]=$v[$vv]==1?'安排':'选课';
					}
					if($vv=='opentime'){
						$v[$vv]=date('Y-m-d',$v[$vv]);
					}
					if($vv=='endtime'){
						$v[$vv]=date('Y-m-d',$v[$vv]);
					}
					if($vv=='regtime'){
						$v[$vv]=date('Y-m-d',$v[$vv]);
					}
					if($vv=='createtime'){
						$v[$vv]=date('Y-m-d',$v[$vv]);
					}
					 $publics=CF('publics','',CONFIG_PATH);
					if($vv=='language'){
						$v[$vv]=$publics['language'][$v[$vv]];
					}
					if($vv=='hsk'){
						$v[$vv]=$publics['hsk'][$v[$vv]];
					}
					if($vv=='minieducation'){
						$v[$vv]=$publics['degree_type'][$v[$vv]];
					}
					if($vv=='isapply'){
						$v[$vv]=$v[$vv]==1?'可以申请':'不可以申请';
					}
					if($vv=='difficult'){
						$v[$vv]=$publics['difficult'][$v[$vv]];
					}
					if($vv=='xzunit'){
						$v[$vv]=$publics['program_unit'][$v[$vv]];
					}
					if($vv=='applytemplate'){
						$v[$vv]=$this->educe_model-> get_applytemplate($v[$vv]);
					}
					if($vv=='attatemplate'){
						$v[$vv]=$this->educe_model-> get_attatemplate($v[$vv]);
					}
					if($vv=='scholarship'){
						$v[$vv]=$this->educe_model-> get_ship($v[$vv]);
					}
					// echo chr($x).$y;exit;
					if($x>90){
						
						$objActSheet->setCellValue ( 'A'.chr($a).$y, $v[$vv]);
						$a++;
					}else{
						$objActSheet->setCellValue ( chr($x).$y, $v[$vv]);
					}
					$x++;
				}
				$a++;
				foreach ($insert2 as $kk => $vv) {

					if($x>90){
						
						$objActSheet->setCellValue ( 'A'.chr($a).$y, $condata[$vv]);
						$a++;
					}else{
						$objActSheet->setCellValue ( chr($x).$y, $condata[$vv]);
					}
					$x++;
				}
				$a++;
				foreach ($insert3 as $kk => $vv) {
					if($vv=='imaorder'){
						$vv='orderby';
					}
					if($vv=='ima_language'){
						$vv='site_language';
					}
					if($x>90){
						
						$objActSheet->setCellValue ( 'A'.chr($a).$y, $imgdata[$vv]);
						$a++;
					}else{
						$objActSheet->setCellValue ( chr($x).$y, $imgdata[$vv]);
					}
					$x++;
				}
				$y++;
			}
			
		$outputFileName = iconv ( 'UTF-8', 'gb2312', 'shenqing'. time() . '.xlsx' );
		$objWriter->save ($outputFileName );
		$data = file_get_contents($outputFileName);
		unlink($outputFileName);

		$this->load->helper('download');
		force_download('course'. time().'.xlsx', $data);
		

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

		function get_programs($id){
			$programs = $this->programaids_course ;
			foreach ($programs as $key => $value) {
				if($value['programaid']==$id){
					return $value['title'];
				}
			}

		}
	/**
	 *设置申请表字段
	 *
	 **/
	function save_shenqing(){
		$data=$this->input->post();
		$arr=CF('exporttemplate','',CONFIG_PATH);
		$arr['shenqing']=$data;
		CF('exporttemplate',$arr,CONFIG_PATH);
		ajaxreturn('','',1);
	}

	/**
	 *设置成绩字段
	 *
	 **/
	function save_score(){
		$data=$this->input->post();
		$arr=CF('exporttemplate','',CONFIG_PATH);
		$arr['chengji']=$data;
		CF('exporttemplate',$arr,CONFIG_PATH);
		ajaxreturn('','',1);
	}
	/**
	 *设置考勤字段
	 *
	 **/
	function save_checking(){
		$data=$this->input->post();
		$arr=CF('exporttemplate','',CONFIG_PATH);
		$arr['checking']=$data;
		CF('exporttemplate',$arr,CONFIG_PATH);
		ajaxreturn('','',1);
	}
	/**
	 *设置专业字段
	 *
	 **/
	function save_major(){
		$data=$this->input->post();
		$arr=CF('exporttemplate','',CONFIG_PATH);
		$arr['major']=$data;
		CF('exporttemplate',$arr,CONFIG_PATH);
		ajaxreturn('','',1);
	}
	/**
	 *设置课程字段
	 *
	 **/
	function save_course(){
		$data=$this->input->post();
		$arr=CF('exporttemplate','',CONFIG_PATH);
		$arr['course']=$data;
		CF('exporttemplate',$arr,CONFIG_PATH);
		ajaxreturn('','',1);
	}
	/**
	 *设置学生字段
	 *
	 **/
	function save_student(){
		$data=$this->input->post();
		$arr=CF('exporttemplate','',CONFIG_PATH);
		$arr['student']=$data;
		CF('exporttemplate',$arr,CONFIG_PATH);
		ajaxreturn('','',1);
	}
	/**
	 *设置考试类型
	 *
	 **/
	function save_itemsetting(){
		$data=$this->input->post();
		// var_dump($data);exit;
		$arr=CF('exporttemplate','',CONFIG_PATH);
		$arr['itemsetting']=$data;
		CF('exporttemplate',$arr,CONFIG_PATH);
		ajaxreturn('','',1);
	}
	/**
	 *设置考试类型
	 *
	 **/
	function save_books(){
		$data=$this->input->post();
		$arr=CF('exporttemplate','',CONFIG_PATH);
		$arr['books']=$data;
		CF('exporttemplate',$arr,CONFIG_PATH);
		ajaxreturn('','',1);
	}
	/**
	 *设置老师
	 *
	 **/
	function save_teacher(){
		$data=$this->input->post();
		$arr=CF('exporttemplate','',CONFIG_PATH);
		$arr['teacher']=$data;
		CF('exporttemplate',$arr,CONFIG_PATH);
		ajaxreturn('','',1);
	}
}