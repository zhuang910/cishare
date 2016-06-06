<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sdyinc_export{

	private $CI;
	const T_DEGREE_INFO='degree_info';
	const T_APP='apply_info';
	const T_STU_INFO='student_info';
	const T_SCORE='score';
	const T_CHE='checking';
	const T_MAJOR_COURSE='major_course';
	const T_MAJOR='major';
	const T_STU='student';
	const T_STU_VISA='student_visa';
	const T_SQUAD='squad';
	const T_COURSE='course';
	const T_TEACHER='teacher';
	const T_TEACHER_COURSE='teacher_course';
	const T_FACULTY='faculty';
	const T_ATTA='attachments';
	const T_TEMPLATECLASS='templateclass';
	const T_SHIP='scholarship_info';
	const T_INSUR='insurance_info';
	const T_SET_SCORE='set_score';
	const T_BOOKS='books';

	function __construct() {
		$this->CI = & get_instance ();
		$this->CI->load->library('PHPExcel');
		$this->CI->load->library('PHPExcel/IOFactory');
		$this->CI->load->library('PHPExcel/Writer/Excel2007');
		
	}
	/**
	 *导出申请
	 **/
	function do_export_shenqing($where){
		$data=CF('exporttemplate','',CONFIG_PATH);
		if(!empty($data['shenqing'])){
			$app=$this->get_app_info($where,$data['shenqing']);
				$objExcel = new PHPExcel();  
				$objWriter = new Excel2007($objExcel);
				$objProps = $objExcel->getProperties ();
				$objExcel->setActiveSheetIndex( 0 );
				$objActSheet = $objExcel->getActiveSheet ();
				$publics=CF('publics','',CONFIG_PATH);
				$abc=$this->bc();
					$i=0;
				foreach ($data['shenqing'] as $key => $value) {
						$objActSheet->setCellValue($abc[$i].'1',$value);
					$i++;
				}
					$i=2;
					foreach ($app as $k => $v) {
						$q=0;
						$u_id=$v['userid'];
						$email=$this->get_student_email($u_id);
						foreach ($v as $kk => $vv) {
							if($kk=='courseid'){
								$vv=$this->get_major_name($vv);
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='userid'){
								$vv=$this->get_user_name($vv);
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='applytime'||$kk=='paytime'||$kk=='issubmittime'||$kk=='lasttime'||$kk=='pagesend_time'||$kk=='address_ctime'||$kk=='opening'||$kk=='validfrom'||$kk=='validuntil'){
								if(empty($vv)){
									$objActSheet->setCellValue($abc[$q].$i,' ');
								}else{
									$vv=date('Y-m-d',$vv);
									$objActSheet->setCellValue($abc[$q].$i,$vv);
								}
							}elseif($kk=='paystate'){
								$vv=$this->get_paystate($vv);
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='paytype'){
								$vv=$this->get_paytype($vv);
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='isstart'||$kk=='isinformation'||$kk=='isatt'||$kk=='issubmit'||$kk=='isproof'||$kk=='isscholar'){
								$vv=$vv==1?'是':'否';
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='state'){
								$vv=$publics['app_state'][$vv];
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='addressconfirm'||$kk=='confirm_admission'){
								$vv=$vv==1?'确认':'未确认';
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='deposit_state'){
								$vv=$vv==1?'已交':'未交';
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='e_offer_status'||$kk=='pagesend_status'){
								$vv=$vv==1?'发送':'未发送';
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='scholorstate'){
								$vv=$this->get_scholorstate($vv);
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='scholorshipid'){
								$vv=$this->get_shipname($vv);
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}else{
								$objActSheet->setCellValue($abc[$q].$i,$vv);
								
							}
							$objActSheet->setCellValue($abc[$q+1].$i,$u_id);
							$objActSheet->setCellValue($abc[$q+2].$i,$email);
							$q++;	
						}
						$i++;
					}
					
				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
				mk_dir($path);
				$file_name = build_order_no().'.xlsx';
				$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
		}	
	}
	function get_student_email($id){
		if(!empty($id)){
			$this->CI->db->select('email');
			$this->CI->db->where('id',$id);
			$data=$this->CI->db->get(self::T_STU_INFO)->row_array();
			return $data['email'];
		}
		return '';
	}
	/**
	 * 
	 * 获取奖学金名字
	 * */
	function get_shipname($id){
		if(!empty($id)){
			$this->CI->db->select('name');
			$this->CI->db->where('id',$id);
			$data=$this->CI->db->get(self::T_SHIP)->result_array();
			return $data['name'];
		}
		return null;
	}
	function get_scholorstate($s){
		switch ($s)
			{
			case 0:
			  return '待审核';
			  break;
			case 1:
			  return '通过';
			  break;
			case 2:
			  return '不通过';
			  break;
			}
	}
	//获取支付方式
	function get_paytype($s){
		switch ($s)
			{
			case 1:
			  return 'paypal ';
			  break;
			case 2:
			  return 'payease';
			  break;
			case 3:
			  return '凭据';
			  break;
			}
	}
	//获取支付状态
	function get_paystate($s){
		switch ($s)
			{
			case 0:
			  return '未支付';
			  break;  
			case 1:
			  return '成功';
			  break;
			case 2:
			  return '失败';
			  break;
			case 3:
			  return '待确认';
			  break;
			}
	}
	function get_user_name($id){
		$this->CI->db->where('id',$id);
		$data=$this->CI->db->get(self::T_STU_INFO)->row_array();
		return $data['chname'];
	}
	/**
	 *
	 *获取申请受数据
	 **/
	function get_app_info($where,$arr){
		unset($arr['user']);
		unset($arr['email']);
		$str='';
		foreach ($arr as $key => $value) {
			$str.=$key.',';
		}
		$str=trim($str,',');
		$n=isset($where['nationality'])?$this->get_nationality_student($where['nationality']):'';
		$this->CI->db->select($str);

		if(!empty($where)){
			foreach ($where as $k => $v) {
				if($k=='starttime'&&$v!=0){
					$this->CI->db->where('applytime >',strtotime($v) );
				}elseif($k=='endtime'){
					if($v==0){
						continue;
					}
					$this->CI->db->where('applytime <',strtotime($v) );
				}elseif($k=='nationality'){
					$this->CI->db->where_in('userid',$n);
				}else{
					$this->CI->db->where($k,$v);
				}
			}
		}
		return $this->CI->db->get(self::T_APP)->result_array();
	}
	/**
	 *获取该国籍的所有学生id
	 *
	 **/	
	function get_nationality_student($nationalityid){
		$this->CI->db->select('id');
		$this->CI->db->where('nationality',$nationalityid);
		$data=$this->CI->db->get(self::T_STU_INFO)->result_array();
		$arr=array();
		foreach ($data as $key => $value) {
			$arr[]=$value['id'];
		}
		
		return $arr;
	}
#################
	function shenqing_tochaneltenplate($data){
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		unset($data['number']);
		unset($data['ordernumber']);
		$abc=$this->bc();
			$i=0;
		foreach ($data as $key => $value) {
				$objActSheet->setCellValue($abc[$i].'1',$value);
			$i++;
		}
		$objActSheet->setCellValue('AF1','学生邮箱');
		$objActSheet->setCellValue('A1','中文名');
		  
		       // -> setFormula1('"'.'专业ID,'.$majorinfo.'"');
			 $objExcel->setActiveSheetIndex(0); //切换到新创建的工作表
			 $objValidation = $objActSheet->getCell("B1")->getDataValidation(); //这一句为要设置数据有效性的单元格
						$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
				       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
				       ->setAllowBlank(true) 
		               ->setShowInputMessage(true)
		               ->setShowErrorMessage(true) 
		               ->setShowDropDown(true)
		               ->setErrorTitle('输入的值有误') 
		               ->setError('您输入的值不在下拉框列表内.')
		               ->setPromptTitle('下拉选择框')
		               ->setPrompt('请从下拉框中选择您需要的值！');
				      $objValidation->setFormula1("card_message!A1:A42");   
				
		       $objValidation = $objActSheet->getCell("F1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'支付状态 0未支付 1成功 2失败 3待确认,未支付,成功,失败,待确认"');

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
		       -> setFormula1('"'.'支付方式 1paypal 2payease 3凭据,paypal,payease,凭据"');

		       $objValidation = $objActSheet->getCell("I1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'是否开始 1是 0否,是,否"');


		       $objValidation = $objActSheet->getCell("J1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'资料是否完成 0否 1是,是,否"');

		       $objValidation = $objActSheet->getCell("K1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'附件是否完成 1是 0否,是,否"');


		        $objValidation = $objActSheet->getCell("L1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'是否提交 1是 0否,是,否"');


		       $objValidation = $objActSheet->getCell("N1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'是否为凭据用户 1是 0否,是,否"');
		       $publics=CF('publics','',CONFIG_PATH);
		       $str=implode($publics['app_state'],',');
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
		       -> setFormula1('"'.'申请状态 1 审核中 2 打回 3 打回提交 4 拒绝 5 调剂 6 预录取 7 录取 8 已入学 9 结束,'.$str.'"');


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
		       -> setFormula1('"'.'地址是否确认 -1 未确认 1确认,未确认,确认"');


		       $objValidation = $objActSheet->getCell("S1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'交押金状态 -1 未交 1 已交,未交,已交"');


		        $objValidation = $objActSheet->getCell("V1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'是否是 奖学金 1 是 0 否,是,否"');


		       $objValidation = $objActSheet->getCell("X1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'是否发送纸质offer -1未发送 1 发送,未发送,发送"');


		         $objValidation = $objActSheet->getCell("Y1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'e_offer是否发送 -1 未发送 1发送,未发送,发送"');


		        $objValidation = $objActSheet->getCell("AA1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'是否确认入学 -1 未确认 1 确认,未确认,确认"');
		       //关联奖学金
			       $ship=$this->get_ship();
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
			      -> setFormula1('"'.'关联奖学金,'.$ship.'"');
		       $objValidation = $objActSheet->getCell("AC1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'0 待审核 1 通过 2 不通过,待审核,通过,不通过"');
		       //创建第二个工作表
		        $msgWorkSheet = new PHPExcel_Worksheet($objExcel, 'card_message'); //创建一个工作表
		        $objExcel->addSheet($msgWorkSheet); //插入工作表
		        $objExcel->setActiveSheetIndex(1); //切换到新创建的工作表
		       	$arr=array(1,2,3,4);
		       	 $majorinfo=$this->get_major();

				// $str_len = strlen($majorinfo);
				
		        $str_list_arr = explode(',', $majorinfo); 
		        if($str_list_arr) 
		              foreach($str_list_arr as $i =>$d){
			              	if($i+1==1){
			              		 $objExcel->getActiveSheet()->setCellValue('A1','专业ID'); 
			              	}else{
			              		 $c = "A".($i+1);
		                     $objExcel->getActiveSheet()->setCellValue($c,$d); 
			              	}
		                    
		               } 
		         $endcell = $c;
		          $objExcel->getActiveSheet()->getColumnDimension('A')->setVisible(true); 
				
		        // $objExcel->getActiveSheet()->fromArray(
		        //         $arr, // 赋值的数组
		        //         NULL, // 忽略的值,不会在excel中显示
		        //         'A1' // 赋值的起始位置
		        // );
		       $objExcel->setActiveSheetIndex(0); 
				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
				mk_dir($path);
				$file_name = build_order_no().'.xlsx';
				$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
	}
	function get_major(){
		$this->CI->db->select('name');
		$data=$this->CI->db->get(self::T_MAJOR)->result_array();
		$str='';
		foreach ($data as $key => $value) {
			$str.=$value['name'].',';
		}
		if(empty($str)){
			return '还没有专业';
		}
		$str=trim($str,',');
		return $str;
	}
#############################导出成绩###############################
	/**
	 *
	 *导出成绩
	 **/
	function do_export_score($where){

		$data=CF('exporttemplate','',CONFIG_PATH);
		if(!empty($data['chengji'])){

			$s=$this->get_score_info($where,$data['chengji']);
				$mname=$this->get_major_name($where['majorid']);
				$sname=$this->get_squad_name($where['squadid']);
				$cname=$this->get_course_name($where['courseid']);
				$objExcel = new PHPExcel();  
				$objWriter = new Excel2007($objExcel);
				$objProps = $objExcel->getProperties ();
				$objExcel->setActiveSheetIndex( 0 );
				$objActSheet = $objExcel->getActiveSheet ();
				$objActSheet->mergeCells('A1:H1'); 
				$objActSheet->setCellValue ( 'A1', $mname.'--第'.$where['nowterm'].'学期--'.$sname.'--'.$cname.'考试成绩');
				$objStyleA1 = $objActSheet->getStyle('A1');
				//设置字体  
				$objFontA1 = $objStyleA1->getFont();  
				$objFontA1->setName('Courier New');  
				$objFontA1->setSize(12);  
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
				$abc=$this->bc();
					$i=0;
				foreach ($data['chengji'] as $key => $value) {
						$objActSheet->setCellValue($abc[$i].'2',$value);
					$i++;
				}
					$i=3;
					foreach ($s as $k => $v) {
						$q=0;
						foreach ($v as $kk => $v) {

							if($kk=='studentid'){
								$v=$this->get_stuname($v);
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}elseif($kk=='majorid'){
								$v=$this->get_major_name($v);
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}elseif($kk=='courseid'){
								$v=$this->get_course_name($v);
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}elseif($kk=='squadid'){
								$v=$this->get_squad_name($v);
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}elseif($kk=='term'){
								$v='第'.$v.'学期';
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}elseif($kk=='scoretype'){
								$v=$this->get_scoretype($v);
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}else{
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}
							$q++;	
						}
						$i++;
					}
				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
				mk_dir($path);
				$file_name = build_order_no().'.xlsx';
				$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
		}	
	}
	/**
	 *获取成绩信息
	 *
	 **/
	function get_score_info($where,$arr){
		$str='';
		foreach ($arr as $key => $value) {
			$str.=$key.',';
		}
		$str=trim($str,',');
		$this->CI->db->select($str);
		if(!empty($where)){
			foreach ($where as $k => $v) {
				if($k=='nowterm'){
					$this->CI->db->where('term',$v);
				}else{
				 	$this->CI->db->where($k,$v);
				}
			}
		}	
		return $this->CI->db->get(self::T_SCORE)->result_array();
	}

######################
function stuscore_tochaneltenplate($data,$ddata,$sdata){
		$mname=$this->get_major_name($ddata['majorid']);
		$sname=$this->get_squad_name($ddata['squadid']);
		$cname=$this->get_course_name($ddata['courseid']);
		$scoretype=$this->CI->db->where('state = 1')->get('set_score')->result_array();
		$scoretypename='';
		foreach ($scoretype as $key => $value) {
			if($value['id']==$ddata['scoretype']){
				$scoretypename=$value['id'].'-'.$value['name'];
			}
		}
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$abc=$this->bc();
			$i=0;
		foreach ($data as $key => $value) {
				$objActSheet->setCellValue($abc[$i].'1',$value);
			$i++;
		}

		$i=2;
			foreach ($sdata as $k => $v) {
			
				// var_dump($v);exit;
					$objActSheet->setCellValue('A'.$i,$v['name']);
					$objActSheet->setCellValue('B'.$i,$mname);
					$objActSheet->setCellValue('C'.$i,$cname);

					$objActSheet->setCellValue('D'.$i,$sname);
					$objActSheet->setCellValue('E'.$i,' ');
					$objActSheet->setCellValue('F'.$i,'第'.$ddata['nowterm'].'学期');
	
					$objActSheet->setCellValue('I'.$i,$scoretypename);
					$objActSheet->setCellValue('J'.$i,$v['email']);

				$i++;
			}

				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
				mk_dir($path);
				$file_name = build_order_no().'.xlsx';
				$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
	}

function get_stuname($id=null){
	if($id!=null){
		$this->CI->db->where('id',$id);
	}
	$data=$this->CI->db->get(self::T_STU)->row_array();

	return $data['name'];
}

function get_major_name($id){
	$this->CI->db->where('id',$id);
	$data=$this->CI->db->get(self::T_MAJOR)->row_array();
	return $data['name'];
}
function get_squad_name($id){
	$this->CI->db->where('id',$id);
	$data=$this->CI->db->get(self::T_SQUAD)->row_array();
	return $data['name'];
}

function get_course_name($id){
	$this->CI->db->where('id',$id);
	$data=$this->CI->db->get(self::T_COURSE)->row_array();
	return $data['name'];
}
//获取考试类型
function get_scoretype($id){
	$scoretype=CF('scoretype','',CONFIG_PATH);
	foreach ($scoretype as $k => $v) {
		if($v['id']==$id){
			return $v['typename'].'-'.$v['englishtypename'];
		}
	}
	
	return 'null';
}
//获取老师姓名
function get_teacher_name($id){
	$this->CI->db->where('id',$id);
	$data=$this->CI->db->get(self::T_TEACHER)->row_array();
	return $data['name'];
}

#############################导出考勤###############################
	/**
	 *
	 *导出考勤
	 **/
	function do_export_checking($where){
		$data=CF('exporttemplate','',CONFIG_PATH);
		if(!empty($data['checking'])){
			$s=$this->get_checking_info($where,$data['checking']);
				$objExcel = new PHPExcel();  
				$objWriter = new Excel2007($objExcel);
				$objProps = $objExcel->getProperties ();
				$objExcel->setActiveSheetIndex( 0 );
				$objActSheet = $objExcel->getActiveSheet ();
				$abc=$this->bc();
					$i=0;
				foreach ($data['checking'] as $key => $value) {
						$objActSheet->setCellValue($abc[$i].'1',$value);
					$i++;
				}
					$i=2;
					foreach ($s as $k => $v) {
						$q=0;
						foreach ($v as $kk => $v) {
							if($kk=='date'){
								$objActSheet->setCellValue($abc[$q].$i,date('Y-m-d',$v));
							}elseif($kk=='studentid'){
								$v=$this->get_stuname($v);
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}elseif($kk=='majorid'){
								$v=$this->get_major_name($v);
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}elseif($kk=='teacherid'){
								$v=$this->get_teacher_name($v);
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}elseif($kk=='courseid'){
								$v=$this->get_course_name($v);
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}elseif($kk=='squadid'){
								$v=$this->get_squad_name($v);
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}elseif($kk=='nowterm'){
								$v='第'.$v.'学期';
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}elseif($kk=='type'){
								$v=$this->get_checking_type($v);
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}elseif($kk=='week'){
								$v=$this->get_checking_week($v);
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}elseif($kk=='knob'){
								$v=$this->get_checking_knob($v);
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}else{
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}
							$q++;	
						}
						$i++;
					}
				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
				mk_dir($path);
				$file_name = build_order_no().'.xlsx';
				$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
		}	
	}	
	/**
	 *
	 *获取考勤类别
	 **/
	function get_checking_type($v){
		switch ($v)
			{
			case 0:
			  return '正点';
			  break;  
			case 1:
			  return '缺勤';
			  break;
			case 2:
			  return '早退';
			  break;
			case 3:
			  return '迟到';
			  break;
			case 4:
			  return '请假';
			  break;
		
			}
	}
	/**
	 * 
	 * 获取考勤星期
	 * */
	function get_checking_week($v){
			switch ($v)
			{
			case 1:
			  return '星期一';
			  break;  
			case 2:
			  return '星期二';
			  break;
			case 3:
			  return '星期三';
			  break;
			case 4:
			  return '星期四';
			  break; 
			case 5:
			  return '星期五';
			  break;
			case 6:
			  return '星期六';
			case 7:
		      return '星期日';
			  break;
		
			}
	}
	/**
	 * 
	 * 获取考勤第几节课
	 * */
	function get_checking_knob($v){
			switch ($v)
			{
			case 1:
			  return '1-2节课';
			  break;  
			case 2:
			  return '3-4节课';
			  break;
			case 3:
			  return '5-6节课';
			  break;
			case 4:
			  return '7-8节课';
			  break;
			case 5:
			  return '9-10节课';
		
		
			}
	}
	/**
	 *获取考勤信息
	 *
	 **/
	function get_checking_info($where,$arr){
		$str='';
		foreach ($arr as $key => $value) {
			$str.=$key.',';
		}
		$str=trim($str,',');
		$this->CI->db->select($str);
		if(!empty($where)){
			foreach ($where as $key => $value) {
				$this->CI->db->where($key,$value);
			}
		}
		return $this->CI->db->get(self::T_CHE)->result_array();
	}

###############
	function checking_tochaneltenplate($file,$data,$sdata){
		$mname=$this->get_major_name($data['majorid']);
		$sname=$this->get_squad_name($data['squadid']);
	$majorid=$data['majorid'];
		$term=$data['nowterm'];
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		$majorname=$this->get_major_name($majorid);
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$objActSheet->mergeCells('A1:I1');  
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
		$d=$this->get_checking_fields();
		$i=65;
		$k=65;
			unset($file['teacherid']);
		unset($file['courseid']);
		unset($file['week']);
		foreach ($file as $key => $value) {
			if($i>90){
				$j=65;
				
				$objActSheet->setCellValue ( chr($j).chr($k).'2', $value);
				$k++;
			}else{
				$objActSheet->setCellValue ( chr($i).'2', $value);
			}
			
			$i++;

		}
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
		       -> setFormula1('"类别0 正点 1旷课 2请假 3迟到,正点,旷课,请假,迟到"');
		$hour=CF('hour','',CONFIG_PATH);
		$hourinfo=$this->get_hour($hour);	
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
		       -> setFormula1('"'.'节课,'.$hourinfo.'"');
		$ss=3;
		foreach ($sdata as $k => $v) {
				$objActSheet->setCellValue('A'.$ss,$v['name']);
				$objActSheet->setCellValue('B'.$ss,$data['majorid'].'-'.$mname);

				$objActSheet->setCellValue('C'.$ss,$data['squadid'].'-'.$sname);
				$objActSheet->setCellValue('D'.$ss,'null');
				$objActSheet->setCellValue('E'.$ss,'第'.$data['nowterm'].'学期');

				$objActSheet->setCellValue('I'.$ss,$v['email']);

				$ss++;
		}
				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
				mk_dir($path);
				$file_name = build_order_no().'.xlsx';
				$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
	}

/**
	 * 
	 * 获取考勤字段
	 * */
	function get_checking_fields(){
		return array(
			 'studentid' =>  '学生id',
			  'teacherid' =>  '老师id',
			  'courseid' =>  '课程id',
			  'squadid' =>  '班级id',
			  'date' =>  '日期',
			  'type' =>  '类别0 正点 1旷课 2请假 3迟到' ,
			  'week' =>  '当前星期' ,
			  'knob' =>  '当前节课' ,
			  'email' =>  '学生邮箱' ,
			  'remark' =>  '备注'
			);
	}
		/**
	 * 
	 * 获取配置hour
	 * */
	function get_hour($hour){
		$str='';
		foreach($hour['hour'] as $k =>$v){
			$str.= $v.'节课,';
		}
		$str=trim($str,',');
		return $str;
	}
		/**
	 * 获取该专业的班级
	 **/
	
	function get_major_squad($majorid,$term){
		$this->CI->db->select('name');
		$this->CI->db->where('majorid',$majorid);
		$this->CI->db->where('nowterm',$term);
		$data=$this->CI->db->get(self::T_SQUAD)->result_array();
		$str='';
		foreach ($data as $key => $value) {
			$str.=$value['name'].',';
		}
		if(empty($str)){
			return '还没有班级';
		}
		$str=trim($str,',');
		return $str;
	}
	/**
	 * 获取该专业的课程
	 **/
	
	function get_major_course_str($majorid){
		$this->CI->db->select('sdyinc_course.name');
		$this->CI->db->join(self::T_COURSE ,self::T_COURSE.'.id='.self::T_MAJOR_COURSE.'.courseid');
		$this->CI->db->where('sdyinc_major_course.majorid',$majorid);
		$data=$this->CI->db->get(self::T_MAJOR_COURSE)->result_array();
		$str='';
		foreach ($data as $key => $value) {
			$str.=$value['name'].',';
		}
		if(empty($str)){
			return '还没有设置课程';
		}
		$str=trim($str,',');
		return $str;
	}
/**
	 * 获取该专业的老师
	 * 
	 * */
	function get_major_teacher($majorid){
		$this->CI->db->select('sdyinc_course.id');
		$this->CI->db->join(self::T_COURSE ,self::T_COURSE.'.id='.self::T_MAJOR_COURSE.'.courseid');
		$this->CI->db->where('sdyinc_major_course.majorid',$majorid);
		$data=$this->CI->db->get(self::T_MAJOR_COURSE)->result_array();
		$str='';
		
		foreach ($data as $key => $value) {
			$this->CI->db->select('sdyinc_teacher.name');
			$this->CI->db->where('courseid',$value['id']);	
			$this->CI->db->where('week',99);
			$this->CI->db->where('knob',99);
			$this->CI->db->join(self::T_TEACHER ,self::T_TEACHER.'.id='.self::T_TEACHER_COURSE.'.teacherid');	
			$datat=$this->CI->db->get(self::T_TEACHER_COURSE)->result_array();
			foreach ($datat as $k => $v) {
				$str.=$v['name'].',';
			}
		}
		if(empty($str)){
			return '还没有设置老师';
		}
		$str=trim($str,',');
		
		return $str;
	}

#############################导出专业###############################
	function do_export_major($where){
		$data=CF('exporttemplate','',CONFIG_PATH);
		if(!empty($data['major'])){
			$app=$this->get_major_info($where,$data['major']);
				$objExcel = new PHPExcel();  
				$objWriter = new Excel2007($objExcel);
				$objProps = $objExcel->getProperties ();
				$objExcel->setActiveSheetIndex( 0 );
				$objActSheet = $objExcel->getActiveSheet ();
				 $publics=CF('publics','',CONFIG_PATH);
				$abc=$this->bc();
					$i=0;
				foreach ($data['major'] as $key => $value) {
						$objActSheet->setCellValue($abc[$i].'1',$value);
					$i++;
				}
					$i=2;
					foreach ($app as $k => $v) {
						$q=0;
						foreach ($v as $kk => $vv) {
							if($kk=='facultyid'){
								$vv=$this->get_faculty_name($vv);
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='degree'){
								$vv=$this->get_degree_name($vv);
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='state'){
								$vv=$vv==1?'是':'否';
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='opentime'||$kk=='endtime'||$kk=='regtime'||$kk=='createtime'){
								if(empty($vv)){
									$objActSheet->setCellValue($abc[$q].$i,'null');
								}else{
									$vv=date('Y-m-d',$vv);
									$objActSheet->setCellValue($abc[$q].$i,$vv);
								}
								
							}elseif($kk=='language'){
								if(empty($vv)){
									$objActSheet->setCellValue($abc[$q].$i,'null');
								}else{
									$vv=$publics['language'][$vv];
									$objActSheet->setCellValue($abc[$q].$i,$vv);
								}
								
							}elseif($kk=='hsk'){
								if(empty($vv)){
									$objActSheet->setCellValue($abc[$q].$i,'null');
								}else{
									$vv=$publics['hsk'][$vv];
									$objActSheet->setCellValue($abc[$q].$i,$vv);
								}
								
							}
							elseif($kk=='minieducation'){
								if(empty($vv)){
									$objActSheet->setCellValue($abc[$q].$i,'null');
								}else{
									$vv=$publics['degree_type'][$vv];
									$objActSheet->setCellValue($abc[$q].$i,$vv);
								}
							
							}
							elseif($kk=='isapply'){
								if(empty($vv)){
									$objActSheet->setCellValue($abc[$q].$i,'null');
								}else{
									$vv=$publics['isapply'][$vv];
									$objActSheet->setCellValue($abc[$q].$i,$vv);
								}
								
							}
							elseif($kk=='difficult'){
								if(empty($vv)){
									$objActSheet->setCellValue($abc[$q].$i,'null');
								}else{
									$vv=$publics['difficult'][$vv];
									$objActSheet->setCellValue($abc[$q].$i,$vv);
								}
							}
							elseif($kk=='xzunit'){
								if(empty($vv)){
									$objActSheet->setCellValue($abc[$q].$i,'null');
								}else{
									$vv=$publics['program_unit'][$vv];
									$objActSheet->setCellValue($abc[$q].$i,$vv);
								}
							}elseif($kk=='applytemplate'){
								if(empty($vv)){
									$objActSheet->setCellValue($abc[$q].$i,'null');
								}else{
									$vv=$this->get_class_name($vv);
									$objActSheet->setCellValue($abc[$q].$i,$vv);
								}
							}elseif($kk=='attatemplate'){
								if(empty($vv)){
									$objActSheet->setCellValue($abc[$q].$i,'null');
								}else{
									$vv=$this->get_class_name($vv);
									$objActSheet->setCellValue($abc[$q].$i,$vv);
								}
							}elseif($kk=='isdeposit'||$kk=='ispageoffer'){
								if(empty($vv)){
									$objActSheet->setCellValue($abc[$q].$i,'null');
								}else{
									$vv=$vv==1?'需要':'不需要';
									$objActSheet->setCellValue($abc[$q].$i,$vv);
								}
							}else{
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}
							$q++;	
						}
						$i++;
					}
			
					
				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
				mk_dir($path);
				$file_name = build_order_no().'.xlsx';
				$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
		}	
	}
	function get_faculty_name($id){
		$this->CI->db->where('id',$id);
		$data=$this->CI->db->get(self::T_FACULTY)->row_array();
		return $data['name'];
	}
	function get_degree_name($id){
		$this->CI->db->where('id',$id);
		$data=$this->CI->db->get(self::T_DEGREE_INFO)->row_array();
		return $data['title'];
	}
	/**
	 * 
	 * 获取申请表名称
	 * */
	function get_class_name($id){
		$this->CI->db->where('tClass_id',$id);
		$data=$this->CI->db->get(self::T_TEMPLATECLASS)->row_array();
		return $data['ClassName'];
	}
	/**
	 * 
	 * 获取附件模板名称
	 * */
	function get_attat_name($id){
		$this->CI->db->where('atta_id',$id);
		$data=$this->CI->db->get(self::T_ATTA)->row_array();
		return $data['AttaName'];
	}	
	/**
	 *
	 *获取专业数据
	 **/
	function get_major_info($where,$arr){
		$str='';
		foreach ($arr as $key => $value) {
			$str.=$key.',';
		}
		$str=trim($str,',');
		$n=isset($where['course'])?$this->get_major_course($where['course']):'';
		$this->CI->db->select($str);
		if(!empty($where)){
			foreach ($where as $k => $v) {
				if($k=='course'){
					$this->CI->db->where_in('id',$n);
				}else{
					$this->CI->db->where($k,$v);
				}
			}
		}
		return $this->CI->db->get(self::T_MAJOR)->result_array();
	}
	/**
	 *获取该课程所关联的所有专业
	 *
	 **/	
	function get_major_course($courseid){
		$this->CI->db->select('majorid');
		$this->CI->db->where('courseid',$courseid);
		$data=$this->CI->db->get(self::T_MAJOR_COURSE)->result_array();
		$arr=array();
		foreach ($data as $key => $value) {
			$arr[]=$value['majorid'];
		}
		
		return $arr;
	}
##############
	function major_tochaneltenplate($data){
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		unset($data['createtime']);
		$abc=$this->bc();
			$i=0;
		foreach ($data as $key => $value) {
				$objActSheet->setCellValue($abc[$i].'1',$value);
			$i++;
		}
		$publics=CF('publics','',CONFIG_PATH);
		$facultyinfo=$this->get_faculty();
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
		       -> setFormula1('"'.'学院id,'.$facultyinfo.'"');
		   $degreeinfo=$this->get_degreename();
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
		       -> setFormula1('"'.'学历id,'.$degreeinfo.'"');
		   $objValidation = $objActSheet->getCell("J1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'是否停用,是,否"');
		       //学制的单位
		     $str='';
			 foreach ($publics['program_unit'] as $key => $value) {
			 	$str.=$value.',';
			 }
		  $objValidation = $objActSheet->getCell("O1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'学制的单位 ,'.trim($str,',').'"');
		       //授课语言
		    $str='';
			 foreach ($publics['language'] as $key => $value) {
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
		       -> setFormula1('"'.'授课语言  ,'.trim($str,',').'"');
		       //HSK
			 $str='';
			 foreach ($publics['hsk'] as $key => $value) {
			 	$str.=$value.',';
			 }
		  $objValidation = $objActSheet->getCell("S1")->getDataValidation(); //这一句为要设置数据有效性的单元格
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
		       //最低学历要求
		   $str='';
			 foreach ($publics['degree_type'] as $key => $value) {
			 	$str.=$value.',';
			 }
		  $objValidation = $objActSheet->getCell("T1")->getDataValidation(); //这一句为要设置数据有效性的单元格
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
		     //是否可以申请
		   	$str='';
			 foreach ($publics['isapply'] as $key => $value) {
			 	$str.=$value.',';
			 }
		  $objValidation = $objActSheet->getCell("U1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'是否可以申请,'.trim($str,',').'"');
		   //请指定附件模板
		        $attat=$this->get_attat();

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
		      -> setFormula1('"'.'请指定附件模板,'.$attat.'"');
		    //请指定申请表模板
		     $class=$this->get_class();
		    $objValidation = $objActSheet->getCell("W1")->getDataValidation();
		       $objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		      -> setFormula1('"'.'请指定申请表模板,'.$class.'"');
		    //关联奖学金
		       $ship=$this->get_ship();
		    $objValidation = $objActSheet->getCell("X1")->getDataValidation();
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
		    //录取难度
		   	$str='';
			 foreach ($publics['difficult'] as $key => $value) {
			 	$str.=$value.',';
			 }
		  $objValidation = $objActSheet->getCell("Y1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'录取难度,'.trim($str,',').'"');
		     //押金 1 需要 -1 不需要	
		       $objValidation = $objActSheet->getCell("AA1")->getDataValidation(); //这一句为要设置数据有效性的单元格
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
		       //纸质offer 1 需要 -1 不需要
		       $objValidation = $objActSheet->getCell("AB1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'纸质offer 1 需要 -1 不需要,需要,不需要"');
		       //指定学制
		  $objActSheet->setCellValue('N1','指定学制(例如:3至5)');
				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
				mk_dir($path);
				$file_name = build_order_no().'.xlsx';
				$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
	}
	/**
	 * 
	 * 获取奖学金
	 * */
	function get_ship(){
		$str='';
		$data=$this->CI->db->get(self::T_SHIP)->result_array();
		foreach ($data as $key => $value) {
			$str.=$value['id'].'-'.$value['title'].',';
		}
		return trim($str,',');
	}
	/**
	 * 
	 * 获取申请表
	 * */
	function get_class(){
		$str='';
		$this->CI->db->where('parent_id',0);
		$this->CI->db->where('classType',1);
		$data=$this->CI->db->get(self::T_TEMPLATECLASS)->result_array();
		unset($data[0]);
		foreach ($data as $key => $value) {
			$str.=$value['ClassName'].',';
		}
		return trim($str,',');
	}
	/**
	 * 
	 * 获取附件模板
	 * */
	function get_attat(){
		$str='';
		$this->CI->db->where('atta_id <>',1);
		$data=$this->CI->db->get(self::T_ATTA)->result_array();
		foreach ($data as $key => $value) {
			$str.=$value['AttaName'].',';
		}
		return trim($str,',');
	}
	/**
	 * 获取学历
	 */
	function get_degreename(){
		$data=$this->CI->db->get(self::T_DEGREE_INFO)->result_array();
		$str='';
		foreach ($data as $key => $value) {
			$str.=$value['title'].',';
		}
		return trim($str,',');
	}
	/**
	 * 获取该专业的班级
	 **/
	
	function get_faculty(){
		$this->CI->db->select('name');
		$data=$this->CI->db->get(self::T_FACULTY)->result_array();
		$str='';
		foreach ($data as $key => $value) {
			$str.=$value['name'].',';
		}
		if(empty($str)){
			return '还没有院系';
		}
		$str=trim($str,',');
		return $str;
	}
	/**
	 *用户端学生考勤导出
	 *@$attendance 导出的数据
	 **/
	function student_attendance_export($attendance,$type){
		$objExcel = new PHPExcel();  
				$objWriter = new Excel2007($objExcel);
				$objProps = $objExcel->getProperties ();
				$objExcel->setActiveSheetIndex( 0 );
				$objActSheet = $objExcel->getActiveSheet ();
				if($type=='en'){
					$objActSheet->setCellValue('A1','Date');
					$objActSheet->setCellValue('B1','Program-Course');
					$objActSheet->setCellValue('C1','Attendance');
					$objActSheet->setCellValue('D1','Instruction');
				}else{
					$objActSheet->setCellValue('A1','日期');
					$objActSheet->setCellValue('B1','专业-课程');
					$objActSheet->setCellValue('C1','考勤类型');
					$objActSheet->setCellValue('D1','说明');
				}
				$i=2;
				foreach ($attendance as $k => $v) {
						$objActSheet->setCellValue('A'.$i,$v['date']);
						if($type=='en'){
							$objActSheet->setCellValue('B'.$i,$v['menglishname'].'-'.$v['englishname']);
						}else{
							$objActSheet->setCellValue('B'.$i,$v['mname'].'-'.$v['name']);
						}
						$v['type']=$this->get_student_checking_type($v['type'],$type);
						$objActSheet->setCellValue('C'.$i,$v['type']);
						if($type=='en'){
							$objActSheet->setCellValue('D'.$i,$v['knob'].'Lesson');
						}else{
							$objActSheet->setCellValue('D'.$i,$v['knob'].'节课');
						}
					$i++;
					}	
				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
				mk_dir($path);
				$file_name = build_order_no().'.xlsx';
				$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
	}
	/**
	 *
	 *获取考勤类别
	 **/
	function get_student_checking_type($v,$type){
		if($type=='cn'){
			switch ($v)
				{
				case 0:
				  return '正点';
				  break;  
				case 1:
				  return '缺勤';
				  break;
				case 2:
				  return '早退';
				  break;
				case 3:
				  return '迟到';
				  break;
				case 4:
				  return '请假';
				  break;
			
				}
			}else{
				switch ($v)
				{
				case 0:
				  return 'On time';
				  break;  
				case 1:
				  return 'On time';
				  break;
				case 2:
				  return 'Leave early';
				  break;
				case 3:
				  return 'Late';
				  break;
				case 4:
				  return 'One day off';
				  break;
			
				}
			}
	}
	/**
	 *用户端学生成绩导出
	 *@$achievement 导出的数据
	 **/
	function student_achievement_export($achievement,$type){
		$objExcel = new PHPExcel();  
				$objWriter = new Excel2007($objExcel);
				$objProps = $objExcel->getProperties ();
				$objExcel->setActiveSheetIndex( 0 );
				$objActSheet = $objExcel->getActiveSheet ();
				if($type=='en'){
					$objActSheet->setCellValue('A1','Course');
					$objActSheet->setCellValue('B1','Score');
					$objActSheet->setCellValue('C1','Type');
				}else{
					$objActSheet->setCellValue('A1','课程');
					$objActSheet->setCellValue('B1','分数');
					$objActSheet->setCellValue('C1','类型');
				}
				
				$i=2;
				foreach ($achievement as $k => $v) {
						if($type=='en'){
							$objActSheet->setCellValue('A'.$i,$v['englishname']);
						}else{
							$objActSheet->setCellValue('A'.$i,$v['name']);
						}
						$objActSheet->setCellValue('B'.$i,$v['score']);
						$v['type']=$this->get_student_scoretype($v['type'],$type);
						$objActSheet->setCellValue('C'.$i,$v['type']);
					$i++;
					}	
				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
				mk_dir($path);
				$file_name = build_order_no().'.xlsx';
				$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
	}

	function get_student_scoretype ($id,$type){
		if($type=='cn'){
			switch ($id)
				{
				case 1:
				  return '优秀';
				  break;
				case 2:
				  return '良好';
				  break;
				case 3:
				  return '中等';
				  break;
				case 4:
				  return '及格';
				  break;
				case 5:
				  return '不及格';
				  break;
			
				}
			}else{
				switch ($id)
				{
				case 1:
				  return 'A';
				  break;
				case 2:
				  return 'B';
				  break;
				case 3:
				  return 'C';
				  break;
				case 4:
				  return 'D';
				  break;
				case 5:
				  return 'E';
				  break;
			
				}
			}
			
	
	}

########################课程导出################################
	/**
	 *导出课程
	 **/
	function do_export_course(){
		$data=CF('exporttemplate','',CONFIG_PATH);
		if(!empty($data['course'])){
				$objExcel = new PHPExcel();  
				$objWriter = new Excel2007($objExcel);
				$objProps = $objExcel->getProperties ();
				$objExcel->setActiveSheetIndex( 0 );
				$objActSheet = $objExcel->getActiveSheet ();
				$abc=$this->bc();
					$i=0;
					$str='';
				foreach ($data['course'] as $key => $value) {
						$objActSheet->setCellValue($abc[$i].'1',$value);
						$str.=$key.',';
					$i++;
				}
				$cdata=$this->get_course_info(trim($str,','));
					$i=2;
					foreach ($cdata as $k => $v) {
						$q=0;
						foreach ($v as $kk => $v) {
							if($kk=='state'){
								$v=$v==1?'是':'否';
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}elseif($kk=='variable'){
								$v=$v==1?'是':'否';
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}else{
							$objActSheet->setCellValue($abc[$q].$i,$v);
							}
							$q++;	
						}
						$i++;
					}
				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
					mk_dir($path);
					$file_name = build_order_no().'.xlsx';
					$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
		}	
	}
	function course_tochaneltenplate($data){
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$abc=$this->bc();
			$i=0;
		foreach ($data as $key => $value) {
				$objActSheet->setCellValue($abc[$i].'1',$value);
			$i++;
		}
		$objValidation = $objActSheet->getCell("F1")->getDataValidation(); //这一句为要设置数据有效性的单元格
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
		$objValidation = $objActSheet->getCell("G1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'状态(是否启用),是,否"');
				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
				mk_dir($path);
				$file_name = build_order_no().'.xlsx';
				$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
	}
	/**
	 *
	 *
	 *获取课程的信息
	 **/
	function get_course_info($str){
		$this->CI->db->select($str);
		return $this->CI->db->get(self::T_COURSE)->result_array();
	}
////////////////在学导出模板////////////////////
	function student_tochaneltenplate($data){
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$abc=$this->bc();
			$i=0;
		foreach ($data as $key => $value) {
				$objActSheet->setCellValue($abc[$i].'1',$value);
			$i++;
		}
		
	       $objValidation = $objActSheet->getCell("V1")->getDataValidation(); //这一句为要设置数据有效性的单元格
			$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
	       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
	       -> setAllowBlank(false)
	       -> setShowInputMessage(true)
	       -> setShowErrorMessage(true)
	       -> setShowDropDown(true)
	       -> setErrorTitle('输入的值有误')
	       -> setError('您输入的值不在下拉框列表内.')
	       -> setPromptTitle('设备类型')
	       -> setFormula1('"'.'在校,转学,正常离开,非正常离开,休学,申请,已报到,未报到"');
	        $objValidation = $objActSheet->getCell("AJ1")->getDataValidation(); //这一句为要设置数据有效性的单元格
			$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
	       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
	       -> setAllowBlank(false)
	       -> setShowInputMessage(true)
	       -> setShowErrorMessage(true)
	       -> setShowDropDown(true)
	       -> setErrorTitle('输入的值有误')
	       -> setError('您输入的值不在下拉框列表内.')
	       -> setPromptTitle('设备类型')
	       -> setFormula1('"'.'None,Primary,Intermediate,Advanced,Beginner"');

		//关联奖学金
			       $ship=$this->get_ship();
			    $objValidation = $objActSheet->getCell("M1")->getDataValidation();
			       $objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
			       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
			       -> setAllowBlank(false)
			       -> setShowInputMessage(true)
			       -> setShowErrorMessage(true)
			       -> setShowDropDown(true)
			       -> setErrorTitle('输入的值有误')
			       -> setError('您输入的值不在下拉框列表内.')
			       -> setPromptTitle('设备类型')
			      -> setFormula1('"'.'资金来源,'.$ship.'"');
		   $objValidation = $objActSheet->getCell("AB1")->getDataValidation(); //这一句为要设置数据有效性的单元格
		  $objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'住宿状态,校内,校外"');
		
		$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
					mk_dir($path);
					$file_name = build_order_no().'.xlsx';
					$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
	}
	/**
	 *
	 *在学导出
	 **/
	function do_export_student($where,$in){
		$data=CF('exporttemplate','',CONFIG_PATH);
		if(!empty($data['student'])){
			$s=$this->get_student_info($where,$data['student'],$in);
			var_dump($data['student'],$s);
			die;
			// var_dump($s);exit;
				$objExcel = new PHPExcel();  
				$objWriter = new Excel2007($objExcel);
				$objProps = $objExcel->getProperties ();
				$objExcel->setActiveSheetIndex( 0 );
				$objActSheet = $objExcel->getActiveSheet ();
				$abc=$this->bc();
					$i=0;
				foreach ($data['student'] as $key => $value) {
						$objActSheet->setCellValue($abc[$i].'1',$value);
					$i++;
				}
					$i=2;
					foreach ($s as $k => $v) {
						$q=0;
						// var_dump($v);exit;
						$passport=$v['passport'];
						foreach ($v as $kk => $vv) {
							if($kk=='isshort'){
								$vv=$vv==1?'是':'否';
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='firstname'){
								//组合英文姓和英文名
								$vv=$this->get_student_enname($passport);
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='nationality'){
								$vv=$this->get_student_nationality($vv);
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='birthday'||$kk=='enroltime'||$kk=='leavetime'||$kk=='applytime'){
								if(!empty($vv)){
									$time=strtotime($vv);
									$date=date('Y-m-d',$time);
								}else{
									$date='';
								}
								
								$objActSheet->setCellValue($abc[$q].$i,$date);
							}elseif($kk=='degreeid'){
								if(!empty($vv)){
									$vv=$this->get_student_degree_name($vv);
								}else{
									$vv='';
								}
								
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='squadid'){
								if(!empty($vv)){
									$vv=$this->get_student_squad_name($vv);
								}else{
									$vv='';
								}
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='state'){
								if(!empty($vv)){
									$vv=$this->get_student_state_str($vv);
								}else{
									$vv='';
								}
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}elseif($kk=='visatime'){
								if(!empty($vv)){
									//$time=strtotime($vv);
									$date=date('Y-m-d',$time);
								}else{
									$date='';
								}
								
								$objActSheet->setCellValue($abc[$q].$i,$date);
								$q=$q+1;
								$objActSheet->setCellValue($abc[$q].$i,'浙江科技学院');
							}elseif($kk=='faculty'){
								if(!empty($vv)){
									$vv=$this->get_student_faculty_name($vv);
								}else{
									$vv='';
								}
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}else{
								$objActSheet->setCellValue($abc[$q].$i,$vv);
							}
							$q++;	
						}
						$i++;	
					}

					$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
					mk_dir($path);
					$file_name = build_order_no().'.xlsx';
					$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
		}	
	}
	//获取学生数据
	function get_student_info($where,$fields,$in){
		$strs='';
		$str='';
		$data=array();
		foreach ($fields as $key => $value) {
			if($key=='school'){
				continue;
			}
			if($key=='studentid'){
				$key='student.studentid';
			}
			if($key=='premium'){
				$ids=explode('=', $where);
				$strs.=$key.',';
				continue;
			}
			
			if($key=='effect_time'){
				$ids=explode('=', $where);
				$strs.=$key.',';
				continue;
			}
			if($key=='deadline'){
				$ids=explode('=', $where);
				$strs.=$key.',';
				continue;
			}
			$str.=$key.',';
		}
		$str=trim($str,',');
		$this->CI->db->select('student.id,'.$str);
		if($in=='where'){
			if(!empty($where)){
				foreach ($where as $k => $v) {
					$this->CI->db->where($k,$v);
				}
			}
		}
		if($in=='in'){
			$this->CI->db->where($where);
		}
		$this->CI->db->join(self::T_STU_VISA,self::T_STU . '.id=' . self::T_STU_VISA . '.studentid');
		$arr= $this->CI->db->get(self::T_STU)->result_array();
		foreach ($arr as $k => $v) {
			unset($arr[$k]['id']);
		}
		return $arr;
	}
	function get_insurance_info($strs,$id){
		$this->CI->db->select($strs);
		$this->CI->db->where('studentid',$id);
		$data=$this->CI->db->get(self::T_INSUR)->row_array();
		return $data;
	}
	//组合英文姓英文名
	function get_student_enname($passport){
		if(!empty($passport)){
			$this->CI->db->select('firstname,lastname');
			$this->CI->db->where('passport',$passport);
			$data=$this->CI->db->get(self::T_STU)->row_array();
			$str=$data['firstname'].','.$data['lastname'];
			return $str;
		}
		return '';
	}
	//获取国家的名字
	function get_student_nationality($id){
		$data=CF('public','',CACHE_PATH);
		if(!empty($id)){
			return $data['global_country_cn'][$id];
		}
		return false;
	}
	//获取学生学历名字
	function get_student_degree_name($id){
		if(!empty($id)){
			$this->CI->db->select('title');
			$this->CI->db->where('id',$id);
			$data=$this->CI->db->get(self::T_DEGREE_INFO)->row_array();
			return $data['title'];
		}
		return '';
	}
		//获取学生班级名字
	function get_student_squad_name($id){
		if(!empty($id)){
			$this->CI->db->select('name');
			$this->CI->db->where('id',$id);
			$data=$this->CI->db->get(self::T_SQUAD)->row_array();
			return $data['name'];
		}
		return '';
	}
	//获取学生状态
	function get_student_state_str($id){
        switch ($id)
        {
            case 1:
                return '在校';
                break;
            case 2:
                return '毕业离校';
                break;
            case 3:
                return '主动退学';
                break;
            case 4:
                return '应予退学（开除）';
                break;
            default:
                return null;
                break;
        }

	}
	//获取学生院系名字
	function get_student_faculty_name($id){
		if(!empty($id)){
			$this->CI->db->select('name');
			$this->CI->db->where('id',$id);
			$data=$this->CI->db->get(self::T_FACULTY)->row_array();
			return $data['name'];
		}
		return '';
	}
	//考试类型导出模板
	function itemsetting_tochaneltenplate($data){
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$abc=$this->bc();
		$i=0;
		foreach ($data as $key => $value) {
				$objActSheet->setCellValue($abc[$i].'1',$value);
			$i++;
		}
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
		       -> setFormula1('"'.'状态(是否启用),是,否"');
				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
				mk_dir($path);
				$file_name = build_order_no().'.xlsx';
				$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
	}
	/**
	 *导出考试类型
	 **/
	function do_export_itemsetting(){
		$data=CF('exporttemplate','',CONFIG_PATH);
		if(!empty($data['itemsetting'])){
				$objExcel = new PHPExcel();  
				$objWriter = new Excel2007($objExcel);
				$objProps = $objExcel->getProperties ();
				$objExcel->setActiveSheetIndex( 0 );
				$objActSheet = $objExcel->getActiveSheet ();
				$abc=$this->bc();
					$i=0;
					$str='';
				foreach ($data['itemsetting'] as $key => $value) {
						$objActSheet->setCellValue($abc[$i].'1',$value);
						$str.=$key.',';
					$i++;
				}
				$cdata=$this->get_itemsetting_info(trim($str,','));
					$i=2;
					foreach ($cdata as $k => $v) {
						$q=0;
						foreach ($v as $kk => $v) {
							if($kk=='state'){
								$v=$v==1?'是':'否';
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}else{
							$objActSheet->setCellValue($abc[$q].$i,$v);
							}
							$q++;	
						}
						$i++;
					}
				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
					mk_dir($path);
					$file_name = build_order_no().'.xlsx';
					$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
		}	
	}
	/**
	 *
	 *
	 *获取课程的信息
	 **/
	function get_itemsetting_info($str){
		$this->CI->db->select($str);
		return $this->CI->db->get(self::T_SET_SCORE)->result_array();
	}

	//书籍导出模板
	function books_tochaneltenplate($data){
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$abc=$this->bc();
		$i=0;
		foreach ($data as $key => $value) {
				$objActSheet->setCellValue($abc[$i].'1',$value);
			$i++;
		}
				$objValidation = $objActSheet->getCell("D1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'状态(是否启用),是,否"');
				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
				mk_dir($path);
				$file_name = build_order_no().'.xlsx';
				$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
	}
	/**
	 *导出书籍
	 **/
	function do_export_books(){
		$data=CF('exporttemplate','',CONFIG_PATH);
		if(!empty($data['books'])){
				$objExcel = new PHPExcel();  
				$objWriter = new Excel2007($objExcel);
				$objProps = $objExcel->getProperties ();
				$objExcel->setActiveSheetIndex( 0 );
				$objActSheet = $objExcel->getActiveSheet ();
				$abc=$this->bc();
					$i=0;
					$str='';
				foreach ($data['books'] as $key => $value) {
						$objActSheet->setCellValue($abc[$i].'1',$value);
						$str.=$key.',';
					$i++;
				}
				$cdata=$this->get_books_info(trim($str,','));
					$i=2;
					foreach ($cdata as $k => $v) {
						$q=0;
						foreach ($v as $kk => $v) {
							if($kk=='state'){
								$v=$v==1?'是':'否';
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}else{
							$objActSheet->setCellValue($abc[$q].$i,$v);
							}
							$q++;	
						}
						$i++;
					}
				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
					mk_dir($path);
					$file_name = build_order_no().'.xlsx';
					$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
		}	
	}
	/**
	 *
	 *
	 *获取课程的信息
	 **/
	function get_books_info($str){
		$this->CI->db->select($str);
		return $this->CI->db->get(self::T_BOOKS)->result_array();
	}
	###################老师导出########################
	//老师导出模板
	function teacher_tochaneltenplate($data){
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$abc=$this->bc();
		$i=0;
		foreach ($data as $key => $value) {
				$objActSheet->setCellValue($abc[$i].'1',$value);
			$i++;
		}
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
		       -> setFormula1('"'.'性别,男,女"');
		       $objValidation = $objActSheet->getCell("L1")->getDataValidation(); //这一句为要设置数据有效性的单元格
				$objValidation -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
		       -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
		       -> setAllowBlank(false)
		       -> setShowInputMessage(true)
		       -> setShowErrorMessage(true)
		       -> setShowDropDown(true)
		       -> setErrorTitle('输入的值有误')
		       -> setError('您输入的值不在下拉框列表内.')
		       -> setPromptTitle('设备类型')
		       -> setFormula1('"'.'状态(是否启用),是,否"');
				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
				mk_dir($path);
				$file_name = build_order_no().'.xlsx';
				$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
	}
	/**
	 *导出老师
	 **/
	function do_export_teacher(){
		$data=CF('exporttemplate','',CONFIG_PATH);
		if(!empty($data['teacher'])){
				$objExcel = new PHPExcel();  
				$objWriter = new Excel2007($objExcel);
				$objProps = $objExcel->getProperties ();
				$objExcel->setActiveSheetIndex( 0 );
				$objActSheet = $objExcel->getActiveSheet ();
				$abc=$this->bc();
					$i=0;
					$str='';
				foreach ($data['teacher'] as $key => $value) {
						$objActSheet->setCellValue($abc[$i].'1',$value);
						$str.=$key.',';
					$i++;
				}
				$cdata=$this->get_teacher_info(trim($str,','));
					$i=2;
					foreach ($cdata as $k => $v) {
						$q=0;
						foreach ($v as $kk => $v) {
							if($kk=='state'){
								$v=$v==1?'是':'否';
								$objActSheet->setCellValue($abc[$q].$i,$v);
							}elseif($kk=='sex'){
								if($v==1){
									$v='男';
									$objActSheet->setCellValue($abc[$q].$i,$v);
								}elseif($v==2){
									$v='女';
									$objActSheet->setCellValue($abc[$q].$i,$v);
								}else{
									$v='';
									$objActSheet->setCellValue($abc[$q].$i,$v);
								}
							}else{
							$objActSheet->setCellValue($abc[$q].$i,$v);
							}
							$q++;	
						}
						$i++;
					}
				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
					mk_dir($path);
					$file_name = build_order_no().'.xlsx';
					$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
		}	
	}
	/**
	 *
	 *
	 *获取课程的信息
	 **/
	function get_teacher_info($str){
		$this->CI->db->select($str);
		return $this->CI->db->get(self::T_TEACHER)->result_array();
	}


	/**
	 * [电费导入模板]
	 * @return [type] [description]
	 */
	function electric_tochaneltenplate($data,$edata){
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$abc=$this->bc();
			$i=0;
		foreach ($data as $key => $value) {
				$objActSheet->setCellValue($abc[$i].'1',$value);
			$i++;
		}

		$i=2;
			foreach ($edata as $k => $v) {
					$objActSheet->setCellValue('A'.$i,$v['id']);
					$objActSheet->setCellValue('B'.$i,$v['cname']);
					$objActSheet->setCellValue('C'.$i,$v['bname']);
					$objActSheet->setCellValue('D'.$i,$v['floor']);
					$objActSheet->setCellValue('E'.$i,$v['name']);
					$objActSheet->setCellValue('F'.$i,$v['enname']);
				$i++;
			}

				$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
				mk_dir($path);
				$file_name = build_order_no().'.xlsx';
				$objWriter->save ($path.$file_name );
				$data = @file_get_contents($path.$file_name);
				return $data;
	}
	/**
	 * [do_export_by 导出北邮报表格式]
	 * @return [type] [description]
	 */
	function do_export_by($data,$body_data){
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$abc=$this->bc();
		$i=0;
		foreach ($data as $key => $value) {
				$objActSheet->setCellValue($abc[$i].'1',$value);
			$i++;
		}
		foreach ($body_data as $k => $v) {
			$i=0;
			foreach ($v as $key => $value) {
					$hang=$k+2;
					$objActSheet->setCellValue($abc[$i].$hang,$value);
				$i++;
			}
		}
		$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
		mk_dir($path);
		$file_name = build_order_no().'.xlsx';
		$objWriter->save ($path.$file_name );
		$data = @file_get_contents($path.$file_name);
		return $data;
	}
	/**
	 *导出已经教书成功的书籍
	 **/
	function do_export_book_fee($where){
		$tou=$this->_get_book_tou();
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$abc=$this->bc();
			$i=0;
			$str='';
		foreach ($tou as $key => $value) {
				$objActSheet->setCellValue($abc[$i].'1',$value);
				$str.=$key.',';
			$i++;
		}
		$excel_data=array();
		//获取已经交成功的数据
		$fee_user_info=$this->_get_books_fee_info();
		if(!empty($fee_user_info)){
			foreach ($fee_user_info as $key => $value) {
				//获取学生基本信息\
				$student_info=$this->CI->db->get_where('student','userid = '.$value['userid'])->row_array();
				$excel_data[$key]['studentid']=!empty($student_info['studentid'])?$student_info['studentid']:'';
				$excel_data[$key]['name']=!empty($student_info['name'])?$student_info['name']:'';
				$excel_data[$key]['enname']=!empty($student_info['enname'])?$student_info['enname']:'';
				$excel_data[$key]['email']=!empty($student_info['email'])?$student_info['email']:'';

				$excel_data[$key]['majorid']=!empty($student_info['majorid'])?$student_info['majorid']:'';
				if(!empty($student_info['squadid'])){
					$squ_info=$this->CI->db->get_where('squad','id = '.$student_info['squadid'])->row_array();
				}
				$excel_data[$key]['nowterm']=!empty($squ_info['nowterm'])?$squ_info['nowterm']:'';
				$excel_data[$key]['squadid']=!empty($student_info['squadid'])?$student_info['squadid']:'';
				$excel_data[$key]['passport']=!empty($student_info['passport'])?$student_info['passport']:'';
				$excel_data[$key]['mobile']=!empty($student_info['mobile'])?$student_info['mobile']:'';
				$excel_data[$key]['tel']=!empty($student_info['tel'])?$student_info['tel']:'';
				$excel_data[$key]['nationality']=!empty($student_info['nationality'])?$student_info['nationality']:'';
				$excel_data[$key]['sex']=!empty($student_info['sex'])?$student_info['sex']:'';
				$book_fee_info=$this->CI->db->get_where('books_fee','userid = '.$value['userid'].' AND paystate = 1')->result_array();
				$str='';
				$price=0;
				if(!empty($book_fee_info)){
					foreach ($book_fee_info as $key => $value) {
						$str.=$value['book_ids'].',';
						$price+=$value['paid_in'];
					}
				}
				$str=trim($str,',');
				$excel_data[$key]['books']=$str;
				$excel_data[$key]['price']=$price;
			}
		}
		if(!empty($where)&&$excel_data){
			foreach ($excel_data as $k => $v) {
				if(!empty($where['majorid'])){
					if($v['majorid']!=$where['majorid']){
						unset($excel_data[$k]);
					}
				}
				if(!empty($where['nowterm'])){
					if($v['nowterm']!=$where['nowterm']){
						unset($excel_data[$k]);
					}
				}
				if(!empty($where['squadid'])){
					if($v['squadid']!=$where['squadid']){
						unset($excel_data[$k]);
					}
				}
			}
		}
			$nationality=CF('public','',CACHE_PATH);

		foreach ($excel_data as $key => $value) {
			if($value['majorid']){
				$major_info=$this->CI->db->get_where('major','id = '.$value['majorid'])->row_array();
				$excel_data[$key]['majorid']=!empty($major_info['name'])?$major_info['name']:'';
			}
			if($value['nowterm']){
				$excel_data[$key]['nowterm']='第'.$value['nowterm'].'学期';
			}
			if($value['squadid']){
				$major_info=$this->CI->db->get_where('squad','id = '.$value['squadid'])->row_array();
				$excel_data[$key]['squadid']=!empty($major_info['name'])?$major_info['name']:'';
			}
			if($value['nationality']){
				$excel_data[$key]['nationality']=$nationality['global_country_cn'][$value['nationality']];
			}
			if($value['sex']){
				$excel_data[$key]['sex']=$value['sex']==1?'男':'女';
			}
			if($value['books']){
				$book_id=explode(',', $value['books']);
				$strs='';
				foreach ($book_id as $ks => $val) {
					$book_info=$this->CI->db->get_where('books','id = '.$val)->row_array();
					$strs.=$book_info['name'].',';
				}
				$excel_data[$key]['books']=trim($strs,',');
			}
		}
		// var_dump($excel_data);exit;
		foreach ($excel_data as $k => $v) {
			$i=0;
			$hang=$k+2;
			foreach ($v as $key => $value) {
					$objActSheet->setCellValue($abc[$i].$hang,$value);
				$i++;
			}
		}
		$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
		mk_dir($path);
		$file_name = build_order_no().'.xlsx';
		$objWriter->save ($path.$file_name );
		$data = @file_get_contents($path.$file_name);
		return $data;
	}
	/**
	 * [_get_books_fee_info 教书费成功的信息]
	 * @return [type] [description]
	 */
	function _get_books_fee_info(){
		$this->CI->db->where('paystate',1);
		$this->CI->db->group_by('userid');
		return $this->CI->db->get('books_fee')->result_array();
	}
	/**
	 * [do_budget_type 按类型导出]
	 * @return [type] [description]
	 */
	function do_budget_type($where){
		$tou=$this->_get_book_tou_budget($where);
		$objExcel = new PHPExcel();  
		$objWriter = new Excel2007($objExcel);
		$objProps = $objExcel->getProperties ();
		$objExcel->setActiveSheetIndex( 0 );
		$objActSheet = $objExcel->getActiveSheet ();
		$abc=$this->bc();
		$i=0;
		$str='';
		foreach ($tou as $key => $value) {
				$objActSheet->setCellValue($abc[$i].'1',$value);
				$str.=$key.',';
			$i++;
		}
		$excel_data=array();
		//获取这段时间所有的学生
		$user_info=$this->get_budget_user($where);
		

		if(!empty($user_info)){
			$nationality=CF('public','',CACHE_PATH);
			foreach ($user_info as $k => $v) {
				//学费	
				$_tuition=array();
				$budget_info=$this->get_budget_info_tuition($where,$v['userid']);
				if(!empty($budget_info)){
					foreach ($budget_info as $key_bud => $value_bud) {
						
						$aaa=$this->CI->db->get_where('tuition_info','budgetid = '.$value_bud['id'])->row_array();
						if($aaa!=null){
							$_tuition[]=$aaa;
						}
					}
					$or_info=$this->CI->db->get_where('apply_order_info','budget_id = '.$value_bud['id'])->row_array();
					if(!empty($or_info)){
						$_tuition[]=$this->CI->db->get_where('tuition_info','order_id = '.$or_info['budget_id'])->row_array();
					}

				}
				
						$info_user=$this->CI->db->get_where('student_info','id = '.$v['userid'])->row_array();
				foreach ($tou as $key => $value) {
					if($key=='id'){
						$excel_data[$k]['id']=$v['userid'];
					}
					if($key=='starttime'){
						$excel_data[$k]['starttime']=$where['starttime'];
					}
					if($key=='endtime'){
						$excel_data[$k]['endtime']=$where['endtime'];
					}
					if($key=='name'){
						$excel_data[$k]['name']=!empty($info_user['enname'])?$info_user['enname']:'';
					}
					if($key=='nationality'){
						$excel_data[$k]['nationality']=$nationality['global_country_cn'][$info_user['nationality']];
					}
					if($key=='paytype'){
						$budget_acc=$this->CI->db->where('paytype = '.$where['paytype'].' AND budget_type = 1 AND paytime > '.strtotime($where['starttime']).' AND paytime < '.strtotime($where['endtime']).' AND paystate = 1 AND userid = '.$v['userid'].' AND type <> 16')->get('budget')->result_array();
						$ta_acc=0;
						if(!empty($budget_acc)){
							foreach ($budget_acc as $key_app => $value_app) {
								$ta_acc+=$value_app['paid_in'];
							}
						}

					
						$excel_data[$k]['paytype']=$ta_acc;
					}
					if($key=='app'){
						$budget_app=$this->CI->db->where('paytype = '.$where['paytype'].' AND budget_type = 1 AND type = 1 AND  paytime > '.strtotime($where['starttime']).' AND paytime < '.strtotime($where['endtime']).' AND paystate = 1 AND userid = '.$v['userid'].' AND type <> 16')->get('budget')->result_array();
						// var_dump($budget_app);exit;
						$ta_app=0;
						if(!empty($budget_app)){
							foreach ($budget_app as $key_app => $value_app) {
								$ta_app+=$value_app['paid_in'];
							}
						}
						$excel_data[$k][$key]=$ta_app;
					}
					if(strstr($key,'degree_')){
						$deg_id=explode('degree_', $key);

						$major_info=$this->CI->db->get_where('major','degree = '.$deg_id[1])->result_array();
						$main=$this->CI->db->get_where('student','userid = '.$v['userid'])->row_array();
						if(!empty($main)){
							$major_id=!empty($main['majorid'])?$main['majorid']:$main['major'];
						}else{
							$app_info=$this->CI->db->get_where('apply_info','state = 7')->row_array();
							$major_id=$app_info['courseid'];
						}

						$ta_tui=0;
						if(!empty($major_info)&&!empty($_tuition)){
							foreach ($_tuition as $key_tui => $value_tui) {
								foreach ($major_info as $key_ma => $value_ma) {
									if($major_id==$value_ma['id']){
										$ta_tui+=$value_tui['tuition'];
									}
								}
							}

							
							$excel_data[$k][$key]=$ta_tui;
						}else{
							$excel_data[$k][$key]=$ta_tui;
						}
						
						
					}
					if($key=='acc'){
						$budget_acc=$this->CI->db->where('paytype = '.$where['paytype'].' AND budget_type = 1 AND type = 4 AND  paytime > '.strtotime($where['starttime']).' AND paytime < '.strtotime($where['endtime']).' AND paystate = 1 AND userid = '.$v['userid'].' AND type <> 16')->get('budget')->result_array();
						$ta_acc=0;
						if(!empty($budget_acc)){
							foreach ($budget_acc as $key_app => $value_app) {
								$ta_acc+=$value_app['paid_in'];
							}
						}
						$budget_ac=$this->CI->db->where('paytype = '.$where['paytype'].' AND budget_type = 1 AND type = 10 AND  paytime > '.strtotime($where['starttime']).' AND paytime < '.strtotime($where['endtime']).' AND paystate = 1 AND userid = '.$v['userid'].' AND type <> 16')->get('budget')->result_array();
						if(!empty($budget_ac)){
							foreach ($budget_ac as $key_app => $value_app) {
								$ta_acc+=$value_app['paid_in'];
							}
						}
						$excel_data[$k]['acc']=$ta_acc;
					}
					if($key=='yajin'){
						$budget_bao=$this->CI->db->where('paytype = '.$where['paytype'].' AND budget_type = 1 AND type = 5 AND  paytime > '.strtotime($where['starttime']).' AND paytime < '.strtotime($where['endtime']).' AND paystate = 1 AND userid = '.$v['userid'].' AND type <> 16')->get('budget')->result_array();
						$ta_acc=0;
						if(!empty($budget_bao)){
							foreach ($budget_bao as $key_app => $value_app) {
								$ta_acc+=$value_app['paid_in'];
							}
						}
						$excel_data[$k]['yajin']=$ta_acc;
					}
					if($key=='baoxian'){
						$budget_bao=$this->CI->db->where('paytype = '.$where['paytype'].' AND budget_type = 1 AND type = 9 AND  paytime > '.strtotime($where['starttime']).' AND paytime < '.strtotime($where['endtime']).' AND paystate = 1 AND userid = '.$v['userid'].' AND type <> 16')->get('budget')->result_array();
						$ta_acc=0;
						if(!empty($budget_bao)){
							foreach ($budget_bao as $key_app => $value_app) {
								$ta_acc+=$value_app['paid_in'];
							}
						}
						$excel_data[$k]['baoxian']=$ta_acc;
					}
					if($key=='shufei'){
						$budget_shufei=$this->CI->db->where('paytype = '.$where['paytype'].' AND budget_type = 1 AND type = 8 AND  paytime > '.strtotime($where['starttime']).' AND paytime < '.strtotime($where['endtime']).' AND paystate = 1 AND userid = '.$v['userid'].' AND type <> 16')->get('budget')->result_array();
						$ta_acc=0;
						if(!empty($budget_shufei)){
							foreach ($budget_shufei as $key_app => $value_app) {
								$ta_acc+=$value_app['paid_in'];
							}
						}
						$excel_data[$k]['shufei']=$ta_acc;
					}
					if($key=='chuangpin'){
						$budget_shufei=$this->CI->db->where('paytype = '.$where['paytype'].' AND budget_type = 1 AND type = 13 AND  paytime > '.strtotime($where['starttime']).' AND paytime < '.strtotime($where['endtime']).' AND paystate = 1 AND userid = '.$v['userid'].' AND type <> 16')->get('budget')->result_array();
						$ta_acc=0;
						if(!empty($budget_shufei)){
							foreach ($budget_shufei as $key_app => $value_app) {
								$ta_acc+=$value_app['paid_in'];
							}
						}
						$excel_data[$k]['chuangpin']=$ta_acc;
					}
					if($key=='dianfei'){
						$budget_acc=$this->CI->db->where('paytype = '.$where['paytype'].' AND budget_type = 1 AND type = 14 AND  paytime > '.strtotime($where['starttime']).' AND paytime < '.strtotime($where['endtime']).' AND paystate = 1 AND userid = '.$v['userid'].' AND type <> 16')->get('budget')->result_array();
						$ta_acc=0;
						if(!empty($budget_acc)){
							foreach ($budget_acc as $key_app => $value_app) {
								$ta_acc+=$value_app['paid_in'];
							}
						}
						$budget_ac=$this->CI->db->where('paytype = '.$where['paytype'].' AND budget_type = 1 AND type = 7 AND  paytime > '.strtotime($where['starttime']).' AND paytime < '.strtotime($where['endtime']).' AND paystate = 1 AND userid = '.$v['userid'].' AND type <> 16')->get('budget')->result_array();
						if(!empty($budget_ac)){
							foreach ($budget_ac as $key_app => $value_app) {
								$ta_acc+=$value_app['paid_in'];
							}
						}
						$excel_data[$k]['dianfei']=$ta_acc;
					}
					if($key=='qita'){
						$excel_data[$k]['qita']='';
					}
					if($key=='remark'){
						$budget_acc=$this->CI->db->where('paytype = '.$where['paytype'].' AND budget_type = 1 AND paytime > '.strtotime($where['starttime']).' AND paytime < '.strtotime($where['endtime']).' AND paystate = 1 AND userid = '.$v['userid'].' AND type <> 16')->get('budget')->result_array();
						$ta_acc='';
						if(!empty($budget_acc)){
							foreach ($budget_acc as $key_app => $value_app) {
								$ta_acc.=$value_app['remark'];
							}
						}
						$excel_data[$k]['remark']=$ta_acc;
					}
				}
			}
		}

		// var_dump($excel_data);exit;
		foreach ($excel_data as $k => $v) {
			$i=0;
			$hang=$k+2;
			foreach ($v as $key => $value) {
					$objActSheet->setCellValue($abc[$i].$hang,$value);
				$i++;
			}
		}
		$path = JJ_ROOT.'uploads/work/'.date('Ym').'/'.date('d').'/';
		mk_dir($path);
		$file_name = build_order_no().'.xlsx';
		$objWriter->save ($path.$file_name );
		$data = @file_get_contents($path.$file_name);
		return $data;
	}
	/**
	 * [get_budget_user 获取规定时间内的学生]
	 * @return [type] [description]
	 */
	function get_budget_user($where){
		$this->CI->db->where('paytime > '.strtotime($where['starttime']));
		$this->CI->db->where('paytime < '.strtotime($where['endtime']));
		$this->CI->db->where('paystate = 1');
		$this->CI->db->where('budget_type = 1');
		$this->CI->db->where('paytype = '.$where['paytype']);
		$this->CI->db->group_by('userid');
		return $this->CI->db->get('budget')->result_array();
	}
	/**s
	 * [get_budget_user 获取规定时间内的学生]
	 * @return [type] [description]
	 */
	function get_budget_info_tuition($where,$userid){
		$this->CI->db->where('paytime > '.strtotime($where['starttime']));
		$this->CI->db->where('paytime < '.strtotime($where['endtime']));
		$this->CI->db->where('paystate = 1');
		$this->CI->db->where('budget_type = 1');
		$this->CI->db->where('paytype = '.$where['paytype']);
		$this->CI->db->where('userid = '.$userid);
		$this->CI->db->where('type = 6');
		return $this->CI->db->get('budget')->result_array();
	}
	/**
	 * [_get_book_tou_budget 获取头部的信息]
	 * @return [type] [description]
	 */
	function _get_book_tou_budget($where){
		$data['id']='序号';
		$data['starttime']='支付时间';
		$data['endtime']='支付截止时间';
		$data['name']='姓名';
		$data['nationality']='国籍';
		//类型
		$config_paytype = array(
		1 => 'paypal',
		2 => 'payease',
		3 => '汇款',
		4 => '现金',
		5 => '刷卡',
		6=>'奖学金支付',
		7=>'申请减免'
		);
		$data['paytype']=$config_paytype[$where['paytype']].'金额';
		$data['app']='申请费';
		$degree_info=$this->CI->db->get_where('degree_info','state = 1 AND id <> 4')->result_array();
		if(!empty($degree_info)){
			foreach ($degree_info as $key => $value) {
				$data['degree_'.$value['id']]=$value['title'].'学费';
			}
		}

		$data['acc']='住宿费';
		$data['yajin']='押金';
		$data['baoxian']='保险费';
		$data['shufei']='书费';
		$data['chuangpin']='床品费';
		$data['dianfei']='电费';
		$data['degree_4']='外国留学生短期团组经费';
		$data['qita']='其他';
		$data['remark']='备注';
		return $data;

	}

	/**
	 * [_get_book_tou 获取头部]
	 * @return [type] [description]
	 */
	function _get_book_tou(){
		return array(
			'studentid'=>'学号',
			'name'=>'中文名',
			'enname'=>'英文名',
			'email'=>'邮箱',
			'majorid'=>'专业',
			'nowterm'=>'学期',
			'squadid'=>'班级',
			'passport'=>'护照号',
			'mobile'=>'手机号',
			'tel'=>'电话',
			'nationality'=>'国籍',
			'sex'=>'性别',
			'books'=>'书籍',
			'price'=>'总价',
			);
	}
function bc(){
	return array(
		  0 => 'A' ,
		  1 => 'B' ,
		  2 => 'C' ,
		  3 => 'D' ,
		  4 => 'E' ,
		  5 => 'F' ,
		  6 => 'G' ,
		  7 => 'H' ,
		  8 => 'I' ,
		  9 => 'J' ,
		  10 => 'K' ,
		  11 => 'L' ,
		  12 => 'M' ,
		  13 => 'N' ,
		  14 => 'O' ,
		  15 => 'P' ,
		  16 => 'Q' ,
		  17 => 'R' ,
		  18 => 'S' ,
		  19 => 'T' ,
		  20 => 'U' ,
		  21 => 'V' ,
		  22 => 'W' ,
		  23 => 'X' ,
		  24 => 'Y' ,
		  25 => 'Z',
		  26 => 'AA' ,
		  27 => 'AB' ,
		  28 => 'AC' ,
		  29 => 'AD' ,
		  30 => 'AE' ,
		  31 => 'AF' ,
		  32 => 'AG' ,
		  33 => 'AH' ,
		  34 => 'AI' ,
		  35 => 'AJ' ,
		  36 => 'AK' ,
		  37 => 'AL' ,
		  38 => 'AM' ,
		  39 => 'AN' ,
		  40 => 'AO' ,
		  41 => 'AP' ,
		  42 => 'AQ' ,
		  43 => 'AR' ,
		  44 => 'AS' ,
		  45 => 'AT' ,
		  46 => 'AU' ,
		  47 => 'AV' ,
		  48 => 'AW' ,
		  49 => 'AX' ,
		  50 => 'AY' ,
		  51 => 'AZ',
		  51 => 'BA' ,
		  52 => 'BB' ,
		  53 => 'BC' ,
		  54 => 'BD' ,
		  55 => 'BE' ,
		  56 => 'BF' ,
		  57 => 'BG' ,
		  58 => 'BH' ,
		  59 => 'BI' ,
		  60 => 'BJ' ,
		  61 => 'BK' ,
		  62 => 'BL' ,
		  63 => 'BM' ,
		  64 => 'BN' ,
		  65 => 'BO' ,
		  66 => 'BP' ,
		  67 => 'BQ' ,
		  68 => 'BR' ,
		  69 => 'BS' ,
		  70 => 'BT' ,
		  71 => 'BU' ,
		  72 => 'BV' ,
		  73 => 'BW' ,
		  74 => 'BX' ,
		  75 => 'BY' ,
		  76 => 'BZ',
		  77 => 'CA',
		  78 => 'CB',
		  ) ;
}

	
}