<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sdyinc_print{

	private $CI;
	const T_PRINT_TEMPLATE='print_template';
	const T_STUDENT='student';

	function __construct() {
		$this->CI = & get_instance ();
	}


	/**
	 * html 打印
	 * @param      $mbid
	 * @param      $data
	 * @param bool $ishow
	 *
	 * @return mixed
	 */
	function do_print($mbid,$data,$ishow=false,$url=false) {
		//获取字段
		$fields=$this->get_template($mbid);
		//获取该模板信息
		$info=$this->get_template_info($mbid);
		$img=$this->get_template_img($mbid);
		$width=$this->get_px($info['dpi'],$info['width']);

		$height=$this->get_px($info['dpi'],$info['height']);
		// var_dump($info['multiple']);
		// var_dump($width);exit;
			$str='<form method="post" action="'.$url.'"><div id="print_bg" style="width: '.$width*1.3.'px; height: '.$height*1.3.'px; z-index: 1; border: 1px solid #ffffff; padding: 0px; position: relative; margin: 0px;">';
			$type=$this->get_template_type($mbid);
			if($type==1){
				$str.='<img width="'.$width*1.3.'" height="'.$height*1.3.'" src="'.$img.'" />';

			}	

			foreach ($fields as $k => $v) {
				$fields=explode('_', @$v[0]);
				if(!empty($fields[2])){
					$fields[1].='_'.$fields[2];
				}
				if(!empty($fields[3])){
					$fields[1].='_'.$fields[2].'_'.$fields[3];
				}
				$name=$this->get_field_val(@$v[0],$data);
				if(empty($name)){
					$name='请输入'.$v[1];
				}
				$str.='<div class="tiao" id="'.$v[0].'" style="width: '.@($v[2]).'px; height: '.@($v[3]).'px; border: 0px none #ffffff; padding: 0px; margin: 0px auto; position: absolute; top: '.@(($v[5]/$info['multiple']-9)*1.3).'px; left: '.@(($v[4]/$info['multiple']-9)*1.3 ).'px; word-break: break-all; text-align: left;"><textarea name="'.$fields[1].'" style="font-size:12px;overflow:hidden; resize:none;width: '.@($v[2]/$info['multiple']).'px; height: '.@($v[3]).'px;background:transparent;border:0px;" id="s'.$v[0].'" onblur="unselects(\''.$v[0].'\')"  onfocus="selects(\''.$v[0].'\')" > '.$name.'</textarea></div>';

			}
			$str.='</div></form>';
			return $this->CI->load->view('admin/public/sdyinc_print',array(
					'str'=>$str,
					'ishow'=>$ishow,
					'mid'=>$mbid
				),$ishow);
		
		
	}
function do_pdf_print($mbid,$data,$type='I',$xy=5.91){

		$fields=$this->get_template($mbid);
		$img=$this->get_template_img($mbid);
		//新打印提取字段
		$new_fields=$this->get_temp_fields($mbid);

		$is_img=$this->get_template_type($mbid);
		if(!empty($fields)){
			require_once $_SERVER ['DOCUMENT_ROOT'] . '/application/third_party/tcpdf/tcpdf.php';
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('CUCAS');
			$pdf->SetTitle('CUCAS');
			$pdf->SetSubject('CUCAS');
			$pdf->SetKeywords('CUCAS');
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(0);
			$pdf->SetFooterMargin(0);
			$pdf->setPrintFooter(false);
			$pdf->setPrintHeader(false);
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
				require_once(dirname(__FILE__).'/lang/eng.php');
				$pdf->setLanguageArray($l);
			}

			$pdf->SetFont('DroidSansFallback', '', 12);

			$pdf->AddPage();

			// 验证是否需要背景
			if($is_img==1 && !empty($img)){
				$bMargin = $pdf->getBreakMargin();
				$auto_page_break = $pdf->getAutoPageBreak();
				$pdf->SetAutoPageBreak(false, 0);
				$pdf->Image(JJ_ROOT.$img, 0, 0, 210, 0, '', '', '', false, 0, '', false, false, 0);
				$pdf->SetAutoPageBreak($auto_page_break, $bMargin);
				$pdf->setPageMark();
			}

			$pdf->SetFillColor(255, 255, 127);
			foreach ($new_fields as $k => $v) {
				// $value=$this->get_temp_fields_value($v);
				$xy = $this->get_new_field_val(@$v,$mbid);
				$x=$xy['x']/3.8;
				$y=$xy['y']/3.8;
				$pdf->writeHTMLCell(0,0, $y,$x, $data[$v], 0, 0, false, true, 'J', true);
			}

			$pdf->lastPage();
			return $pdf->Output('zust-eoffer.pdf', $type);
		}
	}
	function do_pdf_prints($mbid,$data,$type='I'){

		$fields=$this->get_template($mbid);
		$img=$this->get_template_img($mbid);

		$is_img=$this->get_template_type($mbid);
		if(!empty($fields)){
			require_once $_SERVER ['DOCUMENT_ROOT'] . '/application/third_party/tcpdf/tcpdf.php';
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('CUCAS');
			$pdf->SetTitle('CUCAS');
			$pdf->SetSubject('CUCAS');
			$pdf->SetKeywords('CUCAS');
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(0);
			$pdf->SetFooterMargin(0);
			$pdf->setPrintFooter(false);
			$pdf->setPrintHeader(false);
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
				require_once(dirname(__FILE__).'/lang/eng.php');
				$pdf->setLanguageArray($l);
			}

			$pdf->SetFont('DroidSansFallback', '', 9);

			$pdf->AddPage();

			// 验证是否需要背景
			if($is_img==1 && !empty($img)){
				$bMargin = $pdf->getBreakMargin();
				$auto_page_break = $pdf->getAutoPageBreak();
				$pdf->SetAutoPageBreak(false, 0);
				$pdf->Image(JJ_ROOT.$img, 0, 0, 210, 0, '', '', '', false, 0, '', false, false, 0);
				$pdf->SetAutoPageBreak($auto_page_break, $bMargin);
				$pdf->setPageMark();
			}

			$pdf->SetFillColor(255, 255, 127);
			foreach ($fields as $k => $v) {
				$name = $this->get_field_val(@$v[0], $data);
				$x = @($v[4])/4.6;
				$y = @($v[5])/4.6+($k*3-$k);
				$pdf->writeHTMLCell(0,0, $x,$y, $name, 0, 0, false, true, 'J', true);
			}

			$pdf->lastPage();
			return $pdf->Output('cucas.pdf', $type);
		}
	}
	/**
	 * 2015/2/4
	 * 新的获取字段的位置
	 */
	function get_new_field_val($name,$mbid){
		if(!empty($name)&&!empty($mbid)){
			$data=$this->CI->db->get_where(self::T_PRINT_TEMPLATE,'id = '.$mbid)->row_array();
			if(!empty($data['seat_fields'])){
				$arr=json_decode($data['seat_fields'],true);
				if(!empty($arr)){
					return $arr[$name];
				}
			}
		}
		return array();
	}
	/**
	 * 2015/2/4
	 * 新的获取字段
	 */
	function get_temp_fields($mbid){
		if(!empty($mbid)){
			$data=$this->CI->db->get_where(self::T_PRINT_TEMPLATE,'id = '.$mbid)->row_array();
			if(!empty($data['fields'])){
				return explode(',', $data['fields']);
			}
		}
		return array();
	}
	/**
	 *获取模板字段的值
	 *
	 **/
	function get_field_val($fname,$data){
		$arr=explode('_', $fname);
		if(!empty($arr[2])){
			return @$data[$arr[1].'_'.$arr[2]];
		}
		return @$data[$arr[1]];
	}
	/**
	 *获取模板图片的地址
	 *
	 **/
	function get_template_img($id){
		 $data=$this->CI->db->where ( 'id',$id )->limit ( 1 )->get ( self::T_PRINT_TEMPLATE )->row_array ();
		 return $data['img'];

	}
	/**
	 *获取模板的类型（是否打印图片）
	 *
	 **/
	function get_template_type($id){
		 $data=$this->CI->db->where ( 'id',$id )->limit ( 1 )->get ( self::T_PRINT_TEMPLATE )->row_array ();
		 return $data['type'];

	}

	/**
	 *获取模板字段
	 *
	 **/
		function get_template($id){
			$base = $this->CI->db->where ( 'id',$id )->limit ( 1 )->get ( self::T_PRINT_TEMPLATE )->row_array ();
			$base['config_lable']=trim($base['config_lable'],'||,||');
			$arr=explode('||,||', $base['config_lable']) ;
			$data=array();
			foreach ($arr as $k => $v) {
				$array=array();
				$a=explode(',', $v);
				foreach ($a as $kk => $vv) {
					$array[]=$vv;

				}
				$data[]=$array;

			}
			return $data;
	}

	/**
	 *获取模板
	 *
	 **/
		function get_template_info($id){
			$base = $this->CI->db->where ( 'id',$id )->limit ( 1 )->get ( self::T_PRINT_TEMPLATE )->row_array ();
			return $base;
	}

	//获取像素值
	function get_px($dpi,$num){
		if(!empty($dpi)&&!empty($num)){
			$x=0.353*$dpi/72;
			$px=$num/$x;
			return ceil($px);
		}
		return 0;
		
	}
	/**************************************************/

	//获取学生信息
	function get_student_info($id){
		if(!empty($id)){
			$this->CI->db->where('id',$id);
			return $this->CI->db->get(self::T_STUDENT)->row_array();
		}
		return false;
	}
	/**
	 * [get_new_field_val 2014/2/4]
	 * @return [type] [description]
	 */
	// function get_new_field_val(){

	// }
}