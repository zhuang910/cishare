<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Acc_dispose_electric extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/enrollment/';
		$this->load->model ( $this->view . 'acc_dispose_electric_model' );
		$this->update_room_cost();
	}
	/**
	 * [update_room_cost 根据房间每个学生的电费的余额更新房间的状态]
	 * @return [type] [description]
	 */
	function update_room_cost(){

		//获取所有的房间
		$room_info=$this->acc_dispose_electric_model->get_all_room();
		$charge=CF('warning_line','',CONFIG_PATH);
		//循环每个房间 查找学生更新记录
		foreach ($room_info as $k => $v) {
			$user_info=$this->acc_dispose_electric_model->get_room_user($v['id']);
			$cost_state['cost_state']=1;
			foreach ($user_info as $kk => $vv) {
				if(!empty($vv['electric_money']) && $vv['electric_money']<0){
					//更新该房间已经处于提醒状态
					$cost_state['cost_state']=3;
				}elseif (!empty($vv['electric_money']) && $vv['electric_money']<$charge['charge']) {
					$cost_state['cost_state']=2;
				}
			}
			$this->db->update('school_accommodation_prices',$cost_state,'id = '.$v['id']);
		}
	}
	/**
	 * 后台学生管理
	 */
	function index() {
		//列所有的房间   加个字段来标识当前状态 正常 已提醒 欠费
		$label_id = $this->input->get ( 'label_id' );
		$label_id = ! empty ( $label_id ) ? $label_id : '1';
		
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
			//biaoqian
			$label_id = $this->input->get ( 'label_id' );
			$label_id = ! empty ( $label_id ) ? $label_id : '1';
			$where="cost_state = {$label_id}";

            // 查询条件组合
            $condition = dateTable_where_order_limit ( $fields );

            // 排序
            $orderby = $condition['orderby'];
            if(!empty($condition['where'])) {
                $where .= ' AND ' . $condition['where'];
            }

			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->acc_dispose_electric_model->count ( $where);
			$output ['aaData'] = $this->acc_dispose_electric_model->get ( $where,$limit, $offset, $orderby);
			foreach ( $output ['aaData'] as $item ) {
				//获取校区名
				$item->columnid=$this->acc_dispose_electric_model->get_campus_name($item->columnid);
				//楼层名字
				$item->bulidingid=$this->acc_dispose_electric_model->get_buliding_name($item->bulidingid);
				$item->floor='第'.$item->floor.'层';
				$item->operation ='<a href="javascript:;"onclick="pub_alert_html(\'' . $this->zjjp . 'acc_dispose_electric/insert_electric_one?floor='.$item->floor.'&columnid='.$item->columnid.'&bulidingid='.$item->bulidingid.'&roomid=' . $item->id . '&s=1\')"  title="倒入电费"><i class="ace-icon fa fa-check green bigger-130"></i></a>';
			}
			exit ( json_encode ( $output ) );
		}
		//国籍
		$this->_view ( 'acc_dispose_electric_index', array (
				'label_id'=>$label_id
		) );
	}
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'name',
				'enname',
				'columnid',
				'bulidingid',
				'floor',
                ''
		);
	}
	
	function insert_electric_one(){
		$s = intval ( $this->input->get ( 's' ) );
		$roomid=intval ( $this->input->get ( 'roomid' ) );
		$bulidingid=intval ( $this->input->get ( 'bulidingid' ) );
		$floor=intval ( $this->input->get ( 'floor' ) );
		$campusid=intval ( $this->input->get ( 'columnid' ) );
		if (! empty ( $s )) {
			$html = $this->_view ( 'insert_electric_one', array (
				'roomid'=>$roomid,
				'bulidingid'=>$bulidingid,
				'floor'=>$floor,
				'campusid'=>$campusid
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 * [save_room_electric 单个导入房间电费]
	 * @return [type] [description]
	 */
	function save_room_electric(){
		$data=$this->input->post();
		if(!empty($data)){
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$user_info=$this->acc_dispose_electric_model->get_room_user($data['roomid']);
			$num=count($user_info);
			if(empty($num)){
				ajaxReturn('','该房间下没有住人',0);
			}
			//每个学生减去相应的电费 
			//总电费/人头=平均电费  //保留四舍五入两位小数
			$age_money=sprintf("%.2f", $data['money']/$num);  
			//电费余额提醒线
			$charge=CF('warning_line','',CONFIG_PATH);
			//加载email类
			$this->load->library ( 'sdyinc_email' );
			$MAIL = new sdyinc_email ();
			//循环每个学生减去平均的电费
			foreach ($user_info as $k => $v) {
				//计算每个人的电费
				$result=$v['electric_money']-$age_money;
				//更新该用户最新的电费余额
				$arr['electric_money']=$result;
				$this->db->update('accommodation_info',$arr,'id = '.$v['id']);
				if($result<0){
					//如果某同学已经欠费 发邮件通知
					//更新该房间已经处于提醒状态
					$cost_state['cost_state']=2;
					$this->db->update('school_accommodation_prices',$cost_state,'id = '.$v['roomid']);
					//该房间已经处于欠费状态
					$val_arr = array (
							'email' => $v['userid'],
					);
					$MAIL->dot_send_mail ( 37, $v['email'], $val_arr );
				}elseif($result<$charge['charge']){
				//如果某同学已经小于提醒线 发邮件通知
					//更新该房间已经处于提醒状态
					$cost_state['cost_state']=2;
					$this->db->update('school_accommodation_prices',$cost_state,'id = '.$v['roomid']);
					$val_arr = array (
							'email' => $v['userid'],
					);
					$MAIL->dot_send_mail ( 36, $v['email'], $val_arr );
				}
				
				
			}
			$id=$this->acc_dispose_electric_model->save($data);
			ajaxReturn('','',1);
		}	
	}
	/**
	 * [batch_insert_ele_page 批量导入弹框]
	 * @return [type] [description]
	 */
	function batch_insert_ele_page(){
		$s = intval ( $this->input->get ( 's' ) );
		if (! empty ( $s )) {
			$html = $this->_view ( 'batch_insert_ele_page', array (
				
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 *
	 *导出模板
	 **/
	function tochaneltenplate(){
		$data=$this->acc_dispose_electric_model->get_room_fields();
		$edata=$this->acc_dispose_electric_model->get_room_tochanel_data();
		$this->load->library('sdyinc_export');
		$d=$this->sdyinc_export->electric_tochaneltenplate($data,$edata);
		if(!empty($d)){
			$this->load->helper('download');
			force_download('electric'. time().'.xlsx', $d);
			return 1;
		}
	}
	/**
	 *
	 *上传major
	 **/
	function upload_excel(){
		set_time_limit(0);
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
        $str=$_SERVER ['DOCUMENT_ROOT'] . '/uploads/work/' . date ( 'Ym' ) . '/' . date ( 'd' );
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
        echo "Stored in: " . $inputFileName.'<br />';
        $this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');
		$this->load->library('PHPExcel/Writer/Excel2007');
        $objReader = IOFactory::createReader($inputFileType);
        $WorksheetInfo = $objReader->listWorksheetInfo($inputFileName);

        //读取文件最大行数、列数，偶尔会用到。
        $maxRows = $WorksheetInfo[0]['totalRows'];
        $maxColumn = $WorksheetInfo[0]['totalColumns']; 

         //设置只读，可取消类似"3.08E-05"之类自动转换的数据格式，避免写库失败
        $objReader->setReadDataOnly(true);
      
        $objPHPExcel = $objReader->load($inputFileName);
        $sheetData = $objPHPExcel->getSheet(0)->toArray(null,true,true,true);

        $zjj = $objPHPExcel->getActiveSheet();
		$zjj->getStyle('G1:G'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
		$zjj->getStyle('H1:H'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');

        //excel2003文件，可使用'$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);'
        //excel2007文件使用"getActiveSheet()"方法时会提示出错：对non-object使用了"toArray"方法。    
         $keywords = $sheetData[1];
   	     $num=count($sheetData[1]);
         $warning = '上传文件字段格式不正确，请修改后重新上传！<br />';
        $columns = array ( 'A', 'B', 'C', 'D', 'E', 'F', 'G','H','I','J' );
        $mfields=$this->acc_dispose_electric_model->get_room_fields();
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
        //电费余额提醒线
		$charge=CF('warning_line','',CONFIG_PATH);
		//加载email类
		$this->load->library ( 'sdyinc_email' );
		$MAIL = new sdyinc_email ();
        unset($sheetData[1]);
			$i=65;
			$str='';
			$one=2;
			foreach ($sheetData as $k => $v) {
				$value='';
				$roomid=$v['A'];
				$money=$v['J'];
				//$room  组合插入电费历史表的数据
				$room['roomid']=$roomid;
				$room['spend']=$v['I'];
				$room['starttime']=strtotime($zjj->getCell('G'.$k)->getFormattedValue());
				$room['endtime']=strtotime($zjj->getCell('H'.$k)->getFormattedValue());
				$room['money']=$money;
				//获取房间的信息
				$room_info=$this->acc_dispose_electric_model->get_one_room($roomid);
				$room['campusid']=$room_info['columnid'];
				$room['bulidingid']=$room_info['bulidingid'];
				$room['floor']=$room_info['floor'];
				$room['createtime']=time();
				$room['adminid']=$_SESSION['master_user_info']->id;
				if(empty($room['money'])||empty($room['starttime'])||empty($room['endtime'])||empty($room['spend'])){
					$str.=$v['E'].'房间数据不完整，该行没有插入<br>';
					continue;
				}
				/********/
					$user_info=$this->acc_dispose_electric_model->get_room_user($roomid);
					$num=count($user_info);
					if(empty($num)){
						$str.=$v['E'].'行的房间下没有住人，该行没有插入<br>';
						continue;
					}
					//每个学生减去相应的电费 
					//总电费/人头=平均电费  //保留四舍五入两位小数
					$age_money=sprintf("%.2f", $money/$num);  
					//循环每个学生减去平均的电费
					foreach ($user_info as $k => $v) {
						//计算每个人的电费
						$result=$v['electric_money']-$age_money;
						//更新该用户最新的电费余额
						$arr['electric_money']=$result;
						$this->db->update('accommodation_info',$arr,'id = '.$v['id']);
						if($result<0){
							//如果某同学已经欠费 发邮件通知
							//更新该房间已经处于提醒状态
							$cost_state['cost_state']=2;
							$this->db->update('school_accommodation_prices',$cost_state,'id = '.$v['roomid']);
							//该房间已经处于欠费状态
							$val_arr = array (
									'email' => $v['userid'],
							);
							$MAIL->dot_send_mail ( 37, $v['email'], $val_arr );
						}elseif($result<$charge['charge']){
						//如果某同学已经小于提醒线 发邮件通知
							//更新该房间已经处于提醒状态
							$cost_state['cost_state']=2;
							$this->db->update('school_accommodation_prices',$cost_state,'id = '.$v['roomid']);
							$val_arr = array (
									'email' => $v['userid'],
							);
							$MAIL->dot_send_mail ( 36, $v['email'], $val_arr );
						}
						
						
					}
				/********/
				$this->acc_dispose_electric_model->save($room);
				$one=1+$one;
			$i++;
			}
			if($str!=''){
				echo $str;
			}
	}
}