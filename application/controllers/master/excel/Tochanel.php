<?php
defined('BASEPATH') or exit ('No direct script access allowed');

/**
 * 通知邮件配置
 *
 * @author JJ
 *
 */
class Tochanel extends Master_Basic
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
		$this->load->model($this->view . 'tochanel_model');
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
	
		$minfo=$this->tochanel_model->get_major_info();
		$mfields=$this->tochanel_model->get_fields();
		$cfields=$this->tochanel_model->get_course_fields();
		$sfields=$this->tochanel_model->get_score_fields();
		$checkfields=$this->tochanel_model->get_check_fields();
		$course_content=$this->tochanel_model->get_course_content();
		$course_images=$this->tochanel_model->get_course_images();
		$this->_view('tochanel_index', array(
			'mfields'=>$mfields,
			'cfields'=>$cfields,
			'sfields'=>$sfields,
			'minfo'=>$minfo,
			'checkfields'=>$checkfields,
			'course_content'=>$course_content,
			'course_images'=>$course_images,
		));
	}

	function educe_template(){
		$data=$this->input->post();
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$num=count($data);
			$i=65;
			foreach ($data as $k => $v) {
				
				$objActSheet->setCellValue ( chr($i).'1', $v);
			$i++;
			}
			
				$objValidation = $objActSheet->getCell("B1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"所属院系,汉语学院,文言文学院,杂技学院,信息学院dd"');
		      $objValidation = $objActSheet->getCell("E1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"非学历,专科,本科,硕士,博士"');
		$outputFileName = iconv ( 'UTF-8', 'gb2312', 'major'. time() . '.xlsx' );
		$objWriter->save ($outputFileName );
		if ( file_exists( $outputFileName) ) {
        header( 'Content-Description: File Transfer' );
        header( 'Content-Type: application/octet-stream' );
        header( 'Content-Disposition: attachment;filename = ' . basename( $outputFileName ) );
        header( 'Content-Transfer-Encoding: binary' );
        header( 'Expires: 0' );
        header( 'Cache-Control: must-revalidate, post-check = 0, pre-check = 0' );
        header( 'Pragma: public' );
        header( 'Content-Length: ' . filesize( $outputFileName ) );
        ob_clean();
        flush();
        readfile( $outputFileName );
        unlink( $outputFileName );
            exit;
        
    } 

	}
	
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

        $inputFileName ='./uploads/tempexcel/'.$_FILES["file"]["name"];
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
        $objReader = IOFactory::createReader($inputFileType);
        $WorksheetInfo = $objReader->listWorksheetInfo($inputFileName);
        //读取文件最大行数、列数，偶尔会用到。
        $maxRows = $WorksheetInfo[0]['totalRows'];
        $maxColumn = $WorksheetInfo[0]['totalColumns']; 
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
        $mfields=$this->tochanel_model->get_fields();
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
			foreach ($sheetData as $k => $v) {
				$value='';
				foreach ($v as $kk => $vv) {
					if($kk=='B'){
						$value.='"'.$this->tochanel_model->get_faculty($vv).'",';
					}elseif($kk=='E'){
						$value.='"'.$this->tochanel_model->get_degree($vv).'",';
					}else{
					$value.='"'.$vv.'",';
					}

				}
				$value=trim($value,',');
				$count=$this->tochanel_model->checkmajor($insert,$value);

				if($count>0){
					continue;
				}
				$this->tochanel_model->insert_fields($insert,$value);
			$i++;
			}
			return header("Location:".$this->zjjp."tochanel"); 
	}

	/**
	 * 课程模板
	 * */
	function educe_course_template(){
		$data=$this->input->post();
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$programs = $this->programaids_course ;
		$pinfo=$this->tochanel_model->get_programs($programs);
			 $objValidation = $objActSheet->getCell("A1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'分类,'.$pinfo.'"');
		$degreeinfo=$this->tochanel_model->get_degreename();
			 $objValidation = $objActSheet->getCell("C1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'学历id,'.$degreeinfo.'"');
		 $objValidation = $objActSheet->getCell("H1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'是否选修,是,否"');
		 $objValidation = $objActSheet->getCell("M1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'学制单位,week,month,semester,year"');
		 $publics=CF('publics','',CONFIG_PATH);
		 $str='';
		 foreach ($publics['hsk'] as $key => $value) {
		 	$str.=$value.',';
		 }
		  $objValidation = $objActSheet->getCell("Q1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'hsk要求,'.trim($str,',').'"');
		        $str='';
		 foreach ($publics['language'] as $key => $value) {
		 	$str.=$value.',';
		 }
		  $objValidation = $objActSheet->getCell("P1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'授课语言,'.trim($str,',').'"');
		   	$str='';
			 foreach ($publics['degree_type'] as $key => $value) {
			 	$str.=$value.',';
			 }
		  $objValidation = $objActSheet->getCell("R1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'最低学历要求,'.trim($str,',').'"');
		       $attat=$this->tochanel_model->get_attat();
		      $objValidation = $objActSheet->getCell("T1")->getDataValidation();
		       $objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		      -> setFormula1('"'.'请指定附件模板,'.$attat.'"');
		    $class=$this->tochanel_model->get_class();
		    $objValidation = $objActSheet->getCell("U1")->getDataValidation();
		       $objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		      -> setFormula1('"'.'请指定附件模板,'.$class.'"');
		       $ship=$this->tochanel_model->get_ship();
		    $objValidation = $objActSheet->getCell("V1")->getDataValidation();
		       $objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		      -> setFormula1('"'.'关联奖学金,'.$ship.'"');
		      $objValidation = $objActSheet->getCell("AB1")->getDataValidation();
		       $objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		      -> setFormula1('"'.'状态 0禁用 1正常,禁用,正常"');
		      $objValidation = $objActSheet->getCell("AE1")->getDataValidation();
		       $objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		      -> setFormula1('"'.'押金 1 需要 -1 不需要,需要,不需要"');
		      $objValidation = $objActSheet->getCell("S1")->getDataValidation();
		       $objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		      -> setFormula1('"'.'是否可申请 1可以 0否,可以,否"');


		$i=65;
		$k=65;
		foreach ($data as $key => $value) {
			if($i>90){
				$j=65;
				
				$objActSheet->setCellValue ( chr($j).chr($k).'1', $value);
				$k++;
			}else{
				$objActSheet->setCellValue ( chr($i).'1', $value);
			}
			
			$i++;

		}
		
			$objActSheet->setCellValue ( 'AN1', 'content站点语言');
			$objActSheet->setCellValue ( 'AR1', 'images排序');
			$objActSheet->setCellValue ( 'AS1', 'images站点语言');
				
		$outputFileName = iconv ( 'UTF-8', 'gb2312', 'course'. time() . '.xlsx' );
		$objWriter->save ($outputFileName );
		
		if ( file_exists( $outputFileName) ) {
        header( 'Content-Description: File Transfer' );
        header( 'Content-Type: application/octet-stream' );
        header( 'Content-Disposition: attachment;filename = ' . basename( $outputFileName ) );
        header( 'Content-Transfer-Encoding: binary' );
        header( 'Expires: 0' );
        header( 'Cache-Control: must-revalidate, post-check = 0, pre-check = 0' );
        header( 'Pragma: public' );
        header( 'Content-Length: ' . filesize( $outputFileName ) );
        ob_clean();
        flush();
        readfile( $outputFileName );
        unlink( $outputFileName );
            exit;
        
  	  } 
	
	}

function upload_course_excel(){
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

        $inputFileName ='./uploads/tempexcel/'.$_FILES["file"]["name"];
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
        $columns = array ( 'A', 'B', 'C', 'D', 'E', 'F', 'G','H', 'I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR' );
 		//      

        $cfields1=$this->tochanel_model->get_course_fields();
        $cfields2=$this->tochanel_model->get_course_content();
        $cfields3=$this->tochanel_model->get_course_images();
        $cfields4=array_merge($cfields1,$cfields2);
        $cfields=array_merge($cfields4,$cfields3);
        if($num!=count($cfields1)+count($cfields2)+count($cfields3)){
        	echo '字段个数不匹配';
        	exit();
        }
        $keysInFile = array ( );
        foreach ($cfields as $key => $value) {
        	$keysInFile[]=$value;
        }
        foreach( $columns as $keyIndex => $columnIndex ){
        	if($columnIndex=='AN'||$columnIndex=='AR'||$columnIndex=='AS'){continue;}
                if ( $keywords[$columnIndex] != $keysInFile[$keyIndex] ){
                        echo $warning . $columnIndex . '列应为' . $keysInFile[$keyIndex] . '，而非' . $keywords[$columnIndex];
                        exit();
                }
        }
         $insert='';

        foreach ($cfields as $k => $v) {

        	$insert.=$k.',';
        }

        unset($sheetData[1]);
			$i=65;
			$k=65;
			$value='';
			$value1='';
			$value2='';
			$programs = $this->programaids_course ;
			$publics=CF('publics','',CONFIG_PATH);
			foreach ($sheetData as $k => $v) {
				$i=0;
				foreach ($v as $kk => $vv) {
					$vv=trim($vv);
					// $vv=trim($vv);
					if($kk=='A'){
						$vv=$this->tochanel_model->get_programsid($programs,$vv);
					}
					if($kk=='C'){
						$vv=$this->tochanel_model->get_degree($vv);
					}
					if($kk=='H'){
						$vv=$vv=='是'?1:0;
					}
					if($kk=='I'){
						$vv=strtotime(trim($vv,"’‘"));
					}
					if($kk=='J'){
						$vv=strtotime(trim($vv,"’‘"));
					}
					if($kk=='K'){
						$vv=strtotime(trim($vv,"’‘"));
					}
					if($kk=='M'){
						
						$vv=$this->tochanel_model->get_program_unit($publics['program_unit'],$vv);

					}
					if($kk=='P'){
						$vv=$this->tochanel_model->get_language($publics['language'],$vv);
					}
					if($kk=='Q'){
						$vv=$this->tochanel_model->get_hsk($publics['hsk'],$vv);
					}
					if($kk=='R'){
						$vv=$this->tochanel_model->get_degree_type($publics['degree_type'],$vv);
					}
					if($kk=='S'){
						$vv=$vv=='可以'?1:2;
					}
					if($kk=='T'){
						$vv=$this->tochanel_model->get_attatemplatename($vv);
					}
					if($kk=='U'){
						$vv=$this->tochanel_model->get_applytemplatename($vv);
					}
					
					if($i==0){
						$value.='"'.$vv.'",';
					}elseif($i==1){
						$value1.='"'.$vv.'",';
					}elseif($i==2){
						$value2.='"'.$vv.'",';
					}
					if($kk=='AG'){
						$i=1;
					}elseif($kk=='AN'){
						$i=2;
					}
					
				}

				$value=trim($value,',');
				$value1=trim($value1,',');
				$value2=trim($value2,',');
				// $count=$this->tochanel_model->checkcourse($insert,$value);
				// if($count>0){
				// 	continue;
				// }

				$id=$this->tochanel_model->insert_course($value);
				$this->tochanel_model->insert_course_content($value1,$id);
				$this->tochanel_model->insert_course_images($value2,$id);
			$i++;
			}
			return header("Location:".$this->zjjp."tochanel"); 

	}

	/**
	 * 成绩模板
	 * */
	function educe_score_template(){
		$majorid=$this->input->post('majorid');
		$term=$this->input->post('nowterm');
		$data=$this->input->post();
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		$majorname=$this->tochanel_model->get_major_name($majorid);
		unset($data['majorid']);
		unset($data['nowterm']);
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$objActSheet->mergeCells('A1:E1');  
		//得到单元格的样式
		$objActSheet->setCellValue ( 'A1', $majorname.'--第'.$term.'学期--成绩模板');
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
		$i=65;
		$k=65;
		foreach ($data as $key => $value) {
			if($i>90){
				$j=65;
				
				$objActSheet->setCellValue ( chr($j).chr($k).'2', $value);
				$k++;
			}else{
				$objActSheet->setCellValue ( chr($i).'2', $value);
			}
			
			$i++;

		}
		$courseinfo=$this->tochanel_model->get_major_course($majorid);
		
			 $objValidation = $objActSheet->getCell("B2")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'课程id,'.$courseinfo.'"');
		$squadinfo=$this->tochanel_model->get_major_squad($majorid,$term);	
		$objValidation = $objActSheet->getCell("C2")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'班级id,'.$squadinfo.'"');
	   
		$scoretypeinfo=$this->tochanel_model->get_scoretype();	
		$objValidation = $objActSheet->getCell("E2")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'考试类型,'.$scoretypeinfo.'"');
		$outputFileName = iconv ( 'UTF-8', 'gb2312', 'score'. time() . '.xlsx' );
		$objWriter->save ($outputFileName );

		if ( file_exists( $outputFileName) ) {
        header( 'Content-Description: File Transfer' );
        header( 'Content-Type: application/octet-stream' );
        header( 'Content-Disposition: attachment;filename = ' . basename( $outputFileName ) );
        header( 'Content-Transfer-Encoding: binary' );
        header( 'Expires: 0' );
        header( 'Cache-Control: must-revalidate, post-check = 0, pre-check = 0' );
        header( 'Pragma: public' );
        header( 'Content-Length: ' . filesize( $outputFileName ) );
        ob_clean();
        flush();
        readfile( $outputFileName );
        unlink( $outputFileName );
            exit;
        
    } 
	
	}
	/**
	 * 导入成绩数据
	 * */
	function upload_score_excel(){
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

        $inputFileName ='./uploads/tempexcel/'.$_FILES["file"]["name"];
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
        $objReader = IOFactory::createReader($inputFileType);
        $WorksheetInfo = $objReader->listWorksheetInfo($inputFileName);
       
         //设置只读，可取消类似"3.08E-05"之类自动转换的数据格式，避免写库失败
        $objReader->setReadDataOnly(true);
       
        $objPHPExcel = $objReader->load($inputFileName);
        $sheetData = $objPHPExcel->getSheet(0)->toArray(null,true,true,true);
        //excel2003文件，可使用'$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);'
        //excel2007文件使用"getActiveSheet()"方法时会提示出错：对non-object使用了"toArray"方法。    
         $keywords = $sheetData[2];
   	     $num=count($sheetData[2]);
   	    	$str=$sheetData[1]['A'];
   	    	$arr=explode('--',$str);
   	    	$term=substr($arr[1], 3,1);
   	     $majorid=$this->tochanel_model->get_majorid($arr[0]);
        $warning = '上传文件字段格式不正确，请修改后重新上传！<br />';
        $columns = array ( 'A', 'B', 'C', 'D', 'E');
 		//     
        $cfields=$this->tochanel_model->get_score_fields();

        if($num!=count($cfield1)){
        	echo '字段个数不匹配';
        	exit();
        }
        $keysInFile = array ( );
        foreach ($cfields as $key => $value) {
        	$keysInFile[]=$value;
        }
        foreach( $columns as $keyIndex => $columnIndex ){
                if ( $keywords[$columnIndex] != $keysInFile[$keyIndex] ){
                        echo $warning . $columnIndex . '列应为' . $keysInFile[$keyIndex] . '，而非' . $keywords[$columnIndex];
                        exit();
                }
        }
        $insert='';
        foreach ($cfields as $k => $v) {

        	$insert.=$k.',';
        }
        $insert=trim($insert,',');
        unset($sheetData[1]);
        unset($sheetData[2]);
			$i=65;
			$k=65;
			foreach ($sheetData as $k => $v) {
				$value='';
				foreach ($v as $kk => $vv) {
					if($kk=='A'){
						$value.='"'.$this->tochanel_model->get_studentid($vv).'",';
					}
					elseif($kk=='B'){
						$value.='"'.$this->tochanel_model->get_courseid($vv).'",';
					}elseif($kk=='C'){	
						$value.='"'.$this->tochanel_model->get_squadid($vv).'",';
					}elseif($kk=='E'){
						$value.='"'.$this->tochanel_model->get_scoretypeid($vv).'",';
					}
					else{
						$value.='"'.$vv.'",';

					}

				}
				$value=trim($value,',');
				//判断是否有重复的记录
				$count=$this->tochanel_model->checkscore($insert,$value);

								if($count>0){
									continue;
								}
				$insert.=',majorid,term';
				$value.=','.$majorid.','.$term;
				$this->tochanel_model->insert_score_fields($insert,$value);
				
				
				//检查是否有重复记录
			
				//插入数据库
				//
				
			$i++;
			}
			
			 redirect(base_url() . "index.php".$this->zjjp.'tochanel');


	}
	/**
	 * 获取该专业学期
	 */
	public function get_nowterm($mid){
		$nowterm=$this->tochanel_model->get_major_nowterm($mid);
		$data['nowterm']=$nowterm;
		if(!empty($data['nowterm'])){
			ajaxReturn ( $data, '', 1 );
		}
		
	}




	/**
	 * 考勤模板
	 * */
	function educe_checking_template(){
		$majorid=$this->input->post('majorid');
		$term=$this->input->post('nowterm');
		$data=$this->input->post();
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		$majorname=$this->tochanel_model->get_major_name($majorid);
		unset($data['majorid']);
		unset($data['nowterm']);
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$objActSheet->mergeCells('A1:H1');  
		//得到单元格的样式
		$objActSheet->setCellValue ( 'A1', $majorname.'--第'.$term.'学期--考勤模板');
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
		$i=65;
		$k=65;
		foreach ($data as $key => $value) {
			if($i>90){
				$j=65;
				
				$objActSheet->setCellValue ( chr($j).chr($k).'2', $value);
				$k++;
			}else{
				$objActSheet->setCellValue ( chr($i).'2', $value);
			}
			
			$i++;

		}
		$teacherinfo=$this->tochanel_model->get_major_teacher($majorid);
			 $objValidation = $objActSheet->getCell("B2")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'老师id,'.$teacherinfo.'"');
		$courseinfo=$this->tochanel_model->get_major_course($majorid);
		
			 $objValidation = $objActSheet->getCell("C2")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'课程id,'.$courseinfo.'"');
		$squadinfo=$this->tochanel_model->get_major_squad($majorid,$term);	
		$objValidation = $objActSheet->getCell("D2")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'班级id,'.$squadinfo.'"');
	   
		$objValidation = $objActSheet->getCell("F2")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"类别 1 正点 2早退 3迟到 4缺勤5请假,缺勤,早退,迟到,请假"');
		$objValidation = $objActSheet->getCell("G2")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"当前星期,星期一,星期二,星期三,星期四,星期五,星期六,星期日"');
		$hour=CF('hour','',CONFIG_PATH);
		$hourinfo=$this->tochanel_model->get_hour($hour);	
		$objValidation = $objActSheet->getCell("H2")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'班级id,'.$hourinfo.'"');
		$outputFileName = iconv ( 'UTF-8', 'gb2312', 'checking'. time() . '.xlsx' );
		$objWriter->save ($outputFileName );

		if ( file_exists( $outputFileName) ) {
        header( 'Content-Description: File Transfer' );
        header( 'Content-Type: application/octet-stream' );
        header( 'Content-Disposition: attachment;filename = ' . basename( $outputFileName ) );
        header( 'Content-Transfer-Encoding: binary' );
        header( 'Expires: 0' );
        header( 'Cache-Control: must-revalidate, post-check = 0, pre-check = 0' );
        header( 'Pragma: public' );
        header( 'Content-Length: ' . filesize( $outputFileName ) );
        ob_clean();
        flush();
        readfile( $outputFileName );
        unlink( $outputFileName );
            exit;
        }
        
    } 
	/**
	 * 导入考勤数据
	 * */
	function upload_checking_excel(){
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

        $inputFileName ='./uploads/tempexcel/'.$_FILES["file"]["name"];
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
        $objReader = IOFactory::createReader($inputFileType);
        $WorksheetInfo = $objReader->listWorksheetInfo($inputFileName);
       
         //设置只读，可取消类似"3.08E-05"之类自动转换的数据格式，避免写库失败
        $objReader->setReadDataOnly(true);
       
        $objPHPExcel = $objReader->load($inputFileName);
        $sheetData = $objPHPExcel->getSheet(0)->toArray(null,true,true,true);
        //excel2003文件，可使用'$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);'
        //excel2007文件使用"getActiveSheet()"方法时会提示出错：对non-object使用了"toArray"方法。    
         $keywords = $sheetData[2];
   	     $num=count($sheetData[2]);
   	    	$str=$sheetData[1]['A'];
   	    	$arr=explode('--',$str);
   	    	$term=substr($arr[1], 3,1);
   	     $majorid=$this->tochanel_model->get_majorid($arr[0]);
        $warning = '上传文件字段格式不正确，请修改后重新上传！<br />';
        $columns = array ( 'A', 'B', 'C', 'D', 'E','F','G','H');
 		//     
        $cfields=$this->tochanel_model->get_check_fields();
        if($num!=count($cfields)){
        	echo '字段个数不匹配';
        	exit();
        }
        $keysInFile = array ( );
        foreach ($cfields as $key => $value) {
        	$keysInFile[]=$value;
        }
        foreach( $columns as $keyIndex => $columnIndex ){
                if ( $keywords[$columnIndex] != $keysInFile[$keyIndex] ){
                        echo $warning . $columnIndex . '列应为' . $keysInFile[$keyIndex] . '，而非' . $keywords[$columnIndex];
                        exit();
                }
        }
        $insert='';
        foreach ($cfields as $k => $v) {

        	$insert.=$k.',';
        }
        $insert=trim($insert,',');
        unset($sheetData[1]);
        unset($sheetData[2]);
			$i=65;
			$k=65;
			foreach ($sheetData as $k => $v) {
				$value='';
				foreach ($v as $kk => $vv) {
					if($kk=='A'){
						$value.='"'.$this->tochanel_model->get_studentid($vv).'",';
					}
					elseif($kk=='C'){
						$value.='"'.$this->tochanel_model->get_courseid($vv).'",';
					}elseif($kk=='D'){	
						$value.='"'.$this->tochanel_model->get_squadid($vv).'",';
					}elseif($kk=='B'){
						$value.='"'.$this->tochanel_model->get_teacherid($vv).'",';
					}elseif($kk=='F'){
						$value.='"'.$this->tochanel_model->get_checktype($vv).'",';
					}elseif($kk=='E'){

						$value.='"'.strtotime(trim($vv,"’‘")).'",';
					}elseif($kk=='G'){
						$value.='"'.$this->tochanel_model->get_week($vv).'",';
					}elseif($kk=='H'){
						$value.='"'.$this->tochanel_model->get_knob($vv).'",';
					}
					else{
						$value.='"'.$vv.'",';

					}

				}
				$value=trim($value,',');

				//判断是否有重复的记录
				$count=$this->tochanel_model->checkchecking($insert,$value);

								if($count>0){
									continue;
								}
				$insert.=',majorid,nowterm';
				$value.=','.$majorid.','.$term;
				
				$this->tochanel_model->insert_checking_fields($insert,$value);
				
				
				//检查是否有重复记录
			
				//插入数据库
				//
				
			$i++;
			}
			
			 redirect(base_url() . "index.php".$this->zjjp.'tochanel');


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



}