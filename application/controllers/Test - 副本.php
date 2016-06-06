<?php
defined('BASEPATH') or exit ('No direct script access allowed');

/**
 * Created by CUCAS TEAM.
 * User: junjie Zhang
 * E-Mail: zhangjunjie@cucas.cn
 * Date: 15-6-29
 * Time: 上午8:42
 */

class Test extends CUCAS_Ext {
    function __construct()
    {
        parent::__construct();
    }

    function index(){
        for($i =1;$i <= 35; $i++){
            echo build_order_no().'<br>';
        }
    }

    function doin(){
		
        ini_set('memory_limit','3072m');
        require_once APPPATH.'libraries/PHPExcel.php';
        $PHPExcel = new PHPExcel();
		set_time_limit(0);
        $time = time();

        $arr = array(
            1000 => array('major_id'=>147,'tuition'=>9000,'registration_fee'=>400,'squadid' => 1,'faculty'=>'建筑工程学院','studenttype'=>'本科生'),
            1001 => array('major_id'=>147,'tuition'=>9000,'registration_fee'=>400,'squadid' => 2,'faculty'=>'建筑工程学院','studenttype'=>'本科生'),
            1002 => array('major_id'=>147,'tuition'=>9000,'registration_fee'=>400,'squadid' => 3,'faculty'=>'建筑工程学院','studenttype'=>'本科生'),
            1003 => array('major_id'=>147,'tuition'=>9000,'registration_fee'=>400,'squadid' => 4,'faculty'=>'建筑工程学院','studenttype'=>'本科生'),
            1004 => array('major_id'=>147,'tuition'=>9000,'registration_fee'=>400,'squadid' => 5,'faculty'=>'建筑工程学院','studenttype'=>'本科生'),
            1005 => array('major_id'=>147,'tuition'=>9000,'registration_fee'=>400,'squadid' => 6,'faculty'=>'建筑工程学院','studenttype'=>'本科生'),
            1006 => array('major_id'=>150,'tuition'=>8000,'registration_fee'=>400,'squadid' => 7,'faculty'=>'经济与管理学院','studenttype'=>'本科生'),
            1007 => array('major_id'=>150,'tuition'=>8000,'registration_fee'=>400,'squadid' => 8,'faculty'=>'经济与管理学院','studenttype'=>'本科生'),
            1008 => array('major_id'=>151,'tuition'=>8000,'registration_fee'=>400,'squadid' => 9,'faculty'=>'经济与管理学院','studenttype'=>'本科生'),
            1009 => array('major_id'=>152,'tuition'=>9000,'registration_fee'=>400,'squadid' => 10,'faculty'=>'理学院','studenttype'=>'本科生'),
            1010 => array('major_id'=>158,'tuition'=>7500,'registration_fee'=>400,'squadid' => 11,'faculty'=>'人文与国际教育学院','studenttype'=>'本科生'),
            1011 => array('major_id'=>158,'tuition'=>7500,'registration_fee'=>400,'squadid' => 12,'faculty'=>'人文与国际教育学院','studenttype'=>'本科生'),
            1012 => array('major_id'=>158,'tuition'=>7500,'registration_fee'=>400,'squadid' => 13,'faculty'=>'人文与国际教育学院','studenttype'=>'本科生'),
            1013 => array('major_id'=>158,'tuition'=>7500,'registration_fee'=>400,'squadid' => 14,'faculty'=>'人文与国际教育学院','studenttype'=>'本科生'),
            1014 => array('major_id'=>32,'tuition'=>7500,'registration_fee'=>400,'squadid' => 15,'faculty'=>'服装学院/艺术设计学院','studenttype'=>'进修生'),
            1015 => array('major_id'=>152,'tuition'=>9000,'registration_fee'=>400,'squadid' => 50,'faculty'=>'理学院','studenttype'=>'进修生'),
            1016 => array('major_id'=>253,'tuition'=>12500,'registration_fee'=>400,'squadid' => 17,'faculty'=>'生物与化学工程学院/轻工学院','studenttype'=>'进修生'),
            1017 => array('major_id'=>270,'tuition'=>4500,'registration_fee'=>400,'squadid' => 40,'faculty'=>'语言文学学院/中德学院','studenttype'=>'进修生'),
            1018 => array('major_id'=>152,'tuition'=>9000,'registration_fee'=>400,'squadid' => 50,'faculty'=>'理学院','studenttype'=>'进修生'),
            1019 => array('major_id'=>253,'tuition'=>12500,'registration_fee'=>400,'squadid' => 20,'faculty'=>'生物与化学工程学院/轻工学院','studenttype'=>'进修生'),
            1020 => array('major_id'=>270,'tuition'=>9000,'registration_fee'=>400,'squadid' => 40,'faculty'=>'语言文学学院/中德学院','studenttype'=>'进修生'),
            1021 => array('major_id'=>148,'tuition'=>9000,'registration_fee'=>400,'squadid' => 22,'faculty'=>'信息与电子工程学院','studenttype'=>'本科生'),
            1022 => array('major_id'=>148,'tuition'=>9000,'registration_fee'=>400,'squadid' => 23,'faculty'=>'信息与电子工程学院','studenttype'=>'本科生'),
            1023 => array('major_id'=>148,'tuition'=>9000,'registration_fee'=>400,'squadid' => 24,'faculty'=>'信息与电子工程学院','studenttype'=>'本科生'),
            1024 => array('major_id'=>148,'tuition'=>9000,'registration_fee'=>400,'squadid' => 25,'faculty'=>'信息与电子工程学院','studenttype'=>'本科生'),
            1025 => array('major_id'=>148,'tuition'=>9000,'registration_fee'=>400,'squadid' => 26,'faculty'=>'信息与电子工程学院','studenttype'=>'本科生'),
            1026 => array('major_id'=>148,'tuition'=>9000,'registration_fee'=>400,'squadid' => 27,'faculty'=>'信息与电子工程学院','studenttype'=>'本科生'),
            1027 => array('major_id'=>148,'tuition'=>9000,'registration_fee'=>400,'squadid' => 28,'faculty'=>'信息与电子工程学院','studenttype'=>'本科生'),
            1028 => array('major_id'=>148,'tuition'=>9000,'registration_fee'=>400,'squadid' => 29,'faculty'=>'信息与电子工程学院','studenttype'=>'本科生'),
            1029 => array('major_id'=>149,'tuition'=>9000,'registration_fee'=>400,'squadid' => 30,'faculty'=>'信息与电子工程学院','studenttype'=>'本科生'),
            1030 => array('major_id'=>11,'tuition'=>7500,'registration_fee'=>400,'squadid' => 31,'faculty'=>'信息与电子工程学院','studenttype'=>'本科生'),
            1031 => array('major_id'=>264,'tuition'=>4500,'registration_fee'=>400,'squadid' => 18,'faculty'=>'人文与国际教育学院','studenttype'=>'进修生'),
            1032 => array('major_id'=>150,'tuition'=>8000,'registration_fee'=>400,'squadid' => 36,'faculty'=>'经济与管理学院','studenttype'=>'本科生'),
            1033 => array('major_id'=>150,'tuition'=>8000,'registration_fee'=>400,'squadid' => 35,'faculty'=>'经济与管理学院','studenttype'=>'本科生'),
            1034 => array('major_id'=>151,'tuition'=>8000,'registration_fee'=>400,'squadid' => 37,'faculty'=>'经济与管理学院','studenttype'=>'本科生'),
            1035 => array('major_id'=>151,'tuition'=>8000,'registration_fee'=>400,'squadid' => 38,'faculty'=>'经济与管理学院','studenttype'=>'本科生'),
            1036 => array('major_id'=>150,'tuition'=>8000,'registration_fee'=>400,'squadid' => 39,'faculty'=>'经济与管理学院','studenttype'=>'本科生'),
            1037 => array('major_id'=>274,'tuition'=>8000,'registration_fee'=>400,'squadid' => 41,'faculty'=>'经济与管理学院','studenttype'=>'联合培养'),
            1038 => array('major_id'=>151,'tuition'=>8000,'registration_fee'=>400,'squadid' => 42,'faculty'=>'经济与管理学院','studenttype'=>'本科生'),
            1039 => array('major_id'=>151,'tuition'=>8000,'registration_fee'=>400,'squadid' => 43,'faculty'=>'经济与管理学院','studenttype'=>'本科生'),
            1040 => array('major_id'=>147,'tuition'=>8000,'registration_fee'=>400,'squadid' => 45,'faculty'=>'建筑工程学院','studenttype'=>'本科生'),
            1041 => array('major_id'=>147,'tuition'=>8000,'registration_fee'=>400,'squadid' => 44,'faculty'=>'建筑工程学院','studenttype'=>'本科生'),
            1042 => array('major_id'=>158,'tuition'=>8000,'registration_fee'=>400,'squadid' => 46,'faculty'=>'人文与国际教育学院','studenttype'=>'本科生'),
            1043 => array('major_id'=>158,'tuition'=>8000,'registration_fee'=>400,'squadid' => 47,'faculty'=>'人文与国际教育学院','studenttype'=>'本科生'),
            1044 => array('major_id'=>150,'tuition'=>8000,'registration_fee'=>400,'squadid' => 49,'faculty'=>'经济与管理学院','studenttype'=>'本科生'),
            1045 => array('major_id'=>150,'tuition'=>8000,'registration_fee'=>400,'squadid' => 48,'faculty'=>'经济与管理学院','studenttype'=>'本科生'),
            1046 => array('major_id'=>40,'tuition'=>8000,'registration_fee'=>400,'squadid' => 51,'faculty'=>'经济与管理学院','studenttype'=>'本科生'),
            1047 => array('major_id'=>22,'tuition'=>8000,'registration_fee'=>400,'squadid' => 52,'faculty'=>'生物与化学工程学院/轻工学院','studenttype'=>'本科生'),
            1048 => array('major_id'=>14,'tuition'=>8000,'registration_fee'=>400,'squadid' => 53,'faculty'=>'信息与电子工程学院','studenttype'=>'本科生'),
            1049 => array('major_id'=>12,'tuition'=>8000,'registration_fee'=>400,'squadid' => 54,'faculty'=>'信息与电子工程学院','studenttype'=>'本科生'),
            1050 => array('major_id'=>40,'tuition'=>8000,'registration_fee'=>400,'squadid' => 55,'faculty'=>'经济与管理学院','studenttype'=>'本科生'),
            1051 => array('major_id'=>1,'tuition'=>8000,'registration_fee'=>400,'squadid' => 56,'faculty'=>'机械与汽车工程学院','studenttype'=>'本科生'),
            1052 => array('major_id'=>38,'tuition'=>8000,'registration_fee'=>400,'squadid' => 57,'faculty'=>'经济管理学院','studenttype'=>'本科生'),
            1053 => array('major_id'=>20,'tuition'=>8000,'registration_fee'=>400,'squadid' => 58,'faculty'=>'建筑工程学院','studenttype'=>'本科生'),
            1054 => array('major_id'=>20,'tuition'=>8000,'registration_fee'=>400,'squadid' => 60,'faculty'=>'建筑工程学院','studenttype'=>'本科生'),
            1055 => array('major_id'=>17,'tuition'=>8000,'registration_fee'=>400,'squadid' => 61,'faculty'=>'建筑工程学院','studenttype'=>'本科生'),
        );

        foreach($arr as $file => $item){
            echo $filePath = BASEPATH.'../xlsx/'.$file.'.xlsx';
            echo '<br>';
            /**默认用excel2007读取excel，若格式不对，则用之前的版本进行读取*/
            $PHPReader = new PHPExcel_Reader_Excel2007();
            if (!$PHPReader->canRead($filePath)) {
                $PHPReader = new PHPExcel_Reader_Excel5();
                if (!$PHPReader->canRead($filePath)) {
                    echo 'no Excel';
                    return;
                }
            }

            $PHPExcel = $PHPReader->load($filePath);
            $currentSheet = $PHPExcel->getSheet(0); /* * 读取excel文件中的第一个工作表 */
            echo '导入：'.$allRow = $currentSheet->getHighestRow(); /* * 取得一共有多少行 */
            echo ' 人<br>';
            PHPExcel_Cell::columnIndexFromString(); //字母列转换为数字列 如:AA变为27
            for ($currentRow = 2; $currentRow <= $allRow; $currentRow ++) {
                $data = array();
                $data['enname'] = trim($currentSheet->getCellByColumnAndRow(5, $currentRow)->getValue());
                $data['sex'] = trim($currentSheet->getCellByColumnAndRow(8, $currentRow)->getValue()) == '男' ? 1 : 2;
                $data['email'] = trim($currentSheet->getCellByColumnAndRow(17, $currentRow)->getValue());
                $data['password'] = "e10adc3949ba59abbe56e057f20";
                $data['mobile'] = trim($currentSheet->getCellByColumnAndRow(23, $currentRow)->getValue());
                $data['tel'] = trim($currentSheet->getCellByColumnAndRow(35, $currentRow)->getValue());
                $data['nationality'] = trim($currentSheet->getCellByColumnAndRow(0, $currentRow)->getValue());
                $data['interest'] = 0;
                $data['inquiries'] = 0;
                $data['passport'] = trim($currentSheet->getCellByColumnAndRow(2, $currentRow)->getValue());
                $birthday = trim($currentSheet->getCellByColumnAndRow(9, $currentRow)->getValue());
                $data['birthday'] = strtotime(mb_strcut($birthday,0,4).'-'.mb_strcut($birthday,4,2).'-'.mb_strcut($birthday,6));
                $data['registertime'] = $time;
                $data['registerip'] = "127.0.0.1";
                $data['lasttime'] = $time;
                $data['state'] = 1;
                $data['isactive'] = 1;
                $data['address'] = "";
                $data['isenrol'] = 1;
                $data['studentid'] = trim($currentSheet->getCellByColumnAndRow(4, $currentRow)->getValue());
                $remark = trim($currentSheet->getCellByColumnAndRow(29, $currentRow)->getValue());
				$data['remark'] = !empty($remark)?$remark:'';
				
                if(empty($data['enname']) || empty($data['passport']))
                    continue;

                // 验证重复
                $is = $this->db->where("passport = '".$data['passport']."'")->get('student_info')->row();

                if(empty($is)){
                    $this->db->insert('student_info',$data);
                    $user_id = $this->db->insert_id();

                    $data_s = array();
                    $data_s['number'] = build_order_no();
                    $data_s['userid'] = $user_id;
                    $data_s['courseid'] = $item['major_id'];
                    $data_s['tuition'] = $item['tuition'];
                    $data_s['applytime'] = $time;
                    $data_s['registration_fee'] = $item['registration_fee'];
                    $data_s['danwei'] = 2;
                    $data_s['paystate'] = 1;
                    $data_s['paytime'] = $time;
                    $data_s['paytype'] = 4;
                    $data_s['isstart'] = 1;
                    $data_s['isinformation'] = 1;
                    $data_s['isatt'] = 1;
                    $data_s['issubmit'] = 1;
                    $data_s['issubmittime'] = $time;
                    $data_s['lasttime'] = $time;
                    $data_s['state'] = 8;
                    $data_s['addressconfirm'] = 1;
                    $data_s['address_ctime'] = $time;
                    $data_s['opening'] = $time;
                    $data_s['remark'] = !empty($remark)?$remark:'批量导入';
                    $data_s['pagesend_status'] = 1;
                    $data_s['e_offer_status'] = 1;
                    $data_s['confirm_admission'] = "-1";

                    $this->db->insert('apply_info',$data_s);
                    $apply_id = $this->db->insert_id();

                    $data_i = array();
                    $data_i['studentid'] = $data['studentid'];
                    $data_i['enname'] = $data['enname'];
                    $data_i['email'] = $data['email'];
                    $data_i['majorid'] = $item['major_id'];
                    $data_i['squadid'] = $item['squadid'];
                    $data_i['major'] = $item['major_id'];
                    $data_i['userid'] = $user_id;
                    $data_i['passport'] = $data['passport'];
                    $data_i['isclass'] = 1;
                    $visaendtime = trim($currentSheet->getCellByColumnAndRow(21, $currentRow)->getValue());
					
                    if(!empty($visaendtime)){
                        $visaendtime = explode('.',$visaendtime);
                        $visaendtime = $visaendtime[0].'-'.(strlen($visaendtime[1]) >= 2 ? $visaendtime[1] : '0'.$visaendtime[1]).'-'.(strlen($visaendtime[2]) >= 2 ? $visaendtime[2] : '0'.$visaendtime[2]);
                    }
                    $data_i['visaendtime'] = ''; // 签证到期时间
                    $data_i['mobile'] = $data['mobile'];
                    $data_i['tel'] = $data['tel'];
                    $data_i['nationality'] = $data['nationality'];
                    $data_i['state'] = 1;
                    $data_i['acc_state'] = 1;
                    $data_i['faculty'] = $item['faculty'];
                    $data_i['sex'] = $data['sex'];
                    $data_i['profession'] = '学生';
                    $data_i['remark'] = !empty($remark)?$remark:'批量导入';
                    $data_i['birthday'] = $birthday;
                    $data_i['applytime'] = $time;
                    $data_i['studenttype'] = $item['studenttype'];
                    $data_i['degreeid'] = 1;
                    $data_i['isshort'] = 0;
                    $data_i['paperstype'] = '护照';
                    $data_i['putupstate'] = 1;
                    $data_i['createtime'] = $time;
                    $data_i['applyid'] = $apply_id;

                    $this->db->insert('student',$data_i);
                    $s_user_id = $this->db->insert_id();

                    $data_v = array();
                    $data_v['studentid']=$s_user_id;
                    $data_v['visatype']="护照";
                    $data_v['visanumber']=$data['passport'];
                    $data_v['visatime']=$visaendtime;
                    $data_v['issuetime']=$visaendtime;
                    $data_v['nowaddress']=trim($currentSheet->getCellByColumnAndRow(18, $currentRow)->getValue());;

                    $this->db->insert('student_visa',$data_v);
                }
            }
        }
    }
	
	//导入 新数据
	
	function doin_new(){
		
        ini_set('memory_limit','3072m');
        require_once APPPATH.'libraries/PHPExcel.php';
        $PHPExcel = new PHPExcel();
		set_time_limit(0);
        $time = time();

        $arr = array(
            10000001,10000002,10000003,10000004,10000005,10000006,10000007,10000008,10000009,100000010,100000011,100000012,100000013,100000014,100000015,100000016,100000017,100000018,100000019,100000020
        );
		 
        foreach($arr as $file => $item){
			
            echo $filePath = BASEPATH.'../xlsx/'.$item.'.xls';
			
            echo '<br>';
            /**默认用excel2007读取excel，若格式不对，则用之前的版本进行读取*/
            $PHPReader = new PHPExcel_Reader_Excel2007();
            if (!$PHPReader->canRead($filePath)) {
                $PHPReader = new PHPExcel_Reader_Excel5();
                if (!$PHPReader->canRead($filePath)) {
                    echo 'no Excel';
                    return;
                }
            }

            $PHPExcel = $PHPReader->load($filePath);
            $currentSheet = $PHPExcel->getSheet(0); /* * 读取excel文件中的第一个工作表 */
            echo '导入：'.$allRow = $currentSheet->getHighestRow(); /* * 取得一共有多少行 */
            echo ' 人<br>';
            PHPExcel_Cell::columnIndexFromString(); //字母列转换为数字列 如:AA变为27
            for ($currentRow = 2; $currentRow <= $allRow; $currentRow ++) {
                $data = array();
				
                $data['enname'] = trim($currentSheet->getCellByColumnAndRow(0, $currentRow)->getValue());
                $data['sex'] = trim($currentSheet->getCellByColumnAndRow(3, $currentRow)->getValue()) == '男' ? 1 : 2;
                $data['email'] = trim($currentSheet->getCellByColumnAndRow(18, $currentRow)->getValue());
                $data['password'] = "e10adc3949ba59abbe56e057f20";
                $data['mobile'] = trim($currentSheet->getCellByColumnAndRow(16, $currentRow)->getValue());
                $data['tel'] = trim($currentSheet->getCellByColumnAndRow(16, $currentRow)->getValue());
                $data['nationality'] = (int)trim($currentSheet->getCellByColumnAndRow(2, $currentRow)->getValue());
                $data['interest'] = 0;
                $data['inquiries'] = 0;
                $data['passport'] = trim($currentSheet->getCellByColumnAndRow(6, $currentRow)->getValue());
                $birthday = trim($currentSheet->getCellByColumnAndRow(4, $currentRow)->getValue());
				if(!empty($birthday)){
					$b = explode('.',$birthday);
					if(!empty($birthday) && is_array($b) && count($b) == 3){
						
						$data['birthday'] = mktime(0,0,0,(int)$b[1],(int)$b[2],(int)$b[0]);
						
					}
					
				}
				
                
                $data['registertime'] = $time;
                $data['registerip'] = "127.0.0.1";
                $data['lasttime'] = $time;
                $data['state'] = 1;
                $data['isactive'] = 1;
                $data['address'] = "";
                $data['isenrol'] = 1;
               // $data['studentid'] = trim($currentSheet->getCellByColumnAndRow(4, $currentRow)->getValue());
				 $xuexiqixian = trim($currentSheet->getCellByColumnAndRow(13, $currentRow)->getValue());
				 $data['ordernumber_202'] = trim($currentSheet->getCellByColumnAndRow(14, $currentRow)->getValue());
				 $data['remark'] = trim($currentSheet->getCellByColumnAndRow(15, $currentRow)->getValue());
				 $data['dutyteacher'] = trim($currentSheet->getCellByColumnAndRow(19, $currentRow)->getValue());
				 $data['studenttype'] = trim($currentSheet->getCellByColumnAndRow(10, $currentRow)->getValue());
				 if(!empty($xuexiqixian)){
					 $x = explode('-',$xuexiqixian);
					
					 $data['studystarttime'] = strtotime(str_replace(".","-",$x[0]));
					 $data['studyendtime'] = strtotime(str_replace(".","-",$x[1]));
				 }
				
                if(empty($data['enname']) || empty($data['passport'])){
					
					continue;
				}
                    

                // 验证重复
                $is = $this->db->where("passport = '".$data['passport']."'")->get('student_info')->row();
				
                if(empty($is)){
                    $this->db->insert('student_info',$data);
                    $user_id = $this->db->insert_id();
					$majorid = trim($currentSheet->getCellByColumnAndRow(9, $currentRow)->getValue());
					$major_info = $this->db->get_where('major','id = '.$majorid)->row();
					
                    $data_s = array();
                    $data_s['number'] = build_order_no();
                    $data_s['userid'] = $user_id;
                    $data_s['courseid'] = $majorid;
                    $data_s['tuition'] = !empty($major_info->tuition)?$major_info->tuition:0;
                    $data_s['applytime'] = $time;
                    $data_s['registration_fee'] = !empty($major_info->registration_fee)?$major_info->registration_fee:0;
                    $data_s['danwei'] = 2;
                    $data_s['paystate'] = 1;
                    $data_s['paytime'] = $time;
                    $data_s['paytype'] = 4;
                    $data_s['isstart'] = 1;
                    $data_s['isinformation'] = 1;
                    $data_s['isatt'] = 1;
                    $data_s['issubmit'] = 1;
                    $data_s['issubmittime'] = $time;
                    $data_s['lasttime'] = $time;
                    $data_s['state'] = 8;
                    $data_s['addressconfirm'] = 1;
                    $data_s['address_ctime'] = $time;
                    $data_s['opening'] = $time;
                    $data_s['remark'] = "批量导入";
                    $data_s['pagesend_status'] = 1;
                    $data_s['e_offer_status'] = 1;
                    $data_s['confirm_admission'] = "-1";

                    $this->db->insert('apply_info',$data_s);
					
                    $apply_id = $this->db->insert_id();
					

                }
            }
        }
    }
	
	
	function doin_fees(){
        ini_set('memory_limit','3072m');
        require_once APPPATH.'libraries/PHPExcel.php';
        $PHPExcel = new PHPExcel();

        $time = time();

        $arr = array(
			100001,100002,100003,100004,100005,100006,100007,100008,100009,1000010,1000011,1000012,1000013
        );

        foreach($arr as $file => $item){
            echo $filePath = BASEPATH.'../xlsx/'.$item.'.xlsx';
            echo '<br>';
            /**默认用excel2007读取excel，若格式不对，则用之前的版本进行读取*/
            $PHPReader = new PHPExcel_Reader_Excel2007();
            if (!$PHPReader->canRead($filePath)) {
                $PHPReader = new PHPExcel_Reader_Excel5();
                if (!$PHPReader->canRead($filePath)) {
                    echo 'no Excel';
                    return;
                }
            }

            $PHPExcel = $PHPReader->load($filePath);
            $currentSheet = $PHPExcel->getSheet(0); /* * 读取excel文件中的第一个工作表 */
            echo '导入：'.$allRow = $currentSheet->getHighestRow(); /* * 取得一共有多少行 */
            echo ' 人<br>';
            PHPExcel_Cell::columnIndexFromString(); //字母列转换为数字列 如:AA变为27
            for ($currentRow = 2; $currentRow <= $allRow; $currentRow ++) {
                $data = array();
                $data['xuhao'] = trim($currentSheet->getCellByColumnAndRow(0, $currentRow)->getValue());
                $data['term'] = trim($currentSheet->getCellByColumnAndRow(1, $currentRow)->getValue());
                $bddate = trim($currentSheet->getCellByColumnAndRow(2, $currentRow)->getValue());
				if(!empty($bddate)){
					$data['bddate']=gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($bddate)); 
				}else{
					$data['bddate'] = '';
				}
                $data['passport'] = trim($currentSheet->getCellByColumnAndRow(3, $currentRow)->getValue());
                $data['tuition'] = trim($currentSheet->getCellByColumnAndRow(4, $currentRow)->getValue());
                $data['bxf'] = trim($currentSheet->getCellByColumnAndRow(5, $currentRow)->getValue());
                $data['remark'] = trim($currentSheet->getCellByColumnAndRow(6, $currentRow)->getValue());
                $data['name'] = trim($currentSheet->getCellByColumnAndRow(7, $currentRow)->getValue());
                $data['baseshu'] = trim($currentSheet->getCellByColumnAndRow(8, $currentRow)->getValue());
                $data['xuhaobiao'] = $item;
				if(!empty($data['passport'])){
					$this->db->insert('export_fees',$data);
				}
				
				$id = $this->db->insert_id();
				echo $id.'<br />';
                
                }
            }
        
    }
	
	
	/**
	* 更换 判断 新生老生
	*
	*/
	function isnew(){
		$data = $this->db->get_where('export_fees','id > 0')->result_array();
		foreach($data as $k => $v){
			if(!empty($v['bxf'])){
				if($v['bxf'] == 300 || $v['bxf'] == 600){
					$this->db->update('export_fees',array('isnew' => 2),'id = '.$v['id']);
					echo $v['id'].'<br />';
				}
			}
		}
		
	}
	
	
	/**
	* 导入数据
	*/
	function export_fees(){
		$data = $this->db->get_where('export_fees','id > 0')->result_array();
		
		foreach($data as $key => $val){
			// 先通过 护照 去找 是否 存在 该用户 并且 查一下 所在 专业 报到时间
			$user = $this->db->select('id,majorid,enroltime,userid')->get_where('student',"passport = '{$val['passport']}'")->row();
			
			if(empty($user)){
				$this->db->update('export_fees',array('expremark' => '在学表中找不到此用户 护照号：'.$val['passport']),'id = '.$val['id']);
				echo '<font color="#FF00FF">找不到的用户，护照号：'.$val['passport'].' </font>';
				echo '<font color="#FF00FF">姓名'.$val['name'].'<br /></font>';
				continue;
			}
			
			//能 查到 数据 首先 更新 报到时间 
			/*if(!empty($val['bddate'])){
				$date = str_replace('-','',$val['bddate']);
				$this->db->update('student',array('enroltime' => $date),'id = '.$user->id);
				echo '<font color="blue">更新了报到时间的用户的护照号：'.$val['passport'].'<br /></font>';
			}
			*/
			//查专业的 学期数 和 应交的 学费金额
		/*	$major_info = $this->db->select('*')->get_where('major_tuition','majorid = '.$user->majorid)->result_array();
			//学期 和 学费的数组
			foreach($major_info as $k_term => $v_tuition){
				$term_tuition[$v_tuition['term']] = $v_tuition['tuition'];
			}
			
			*/
			//查看 数据中的 当前 学期 和 钱数
			if(empty($val['term'])){
				$this->db->update('export_fees',array('expremark' => '数据中没有学期，无法进行'),'id = '.$val['id']);
				echo '<font color="#FF00FF">找不到学期的用户护照号：'.$val['passport'].'<br /></font>';
				continue;
				
			}
			
			if($val['term'] == 1){
				// 学费 为免费的话 则 实缴应缴都为 0 备注 奖学金用户并且是 奖学金类型
				if($val['tuition'] == '免费'){
					$data_t_jiangxuejin_budget = array(
						'userid' => $user->userid,
						'budget_type' => 1,
						'type' => 6,
						'term' => 1,
						'payable' => 0,
						'paid_in' => 0,
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 6,
						'createtime' => time(),
						'lasttime' => time(),
						'remark' => !empty($val['remark'])?$val['remark']:'',
						'is_get_scholarship_money' => 3,
						'adminid' => 10
						
					);
					
					//插入到 收支表
					$this->db->insert('budget',$data_t_jiangxuejin_budget);
					
					//返回一个id
					$return_budget_id_1 = $this->db->insert_id();
					//组织 学费表
					$data_t_1_tuition = array(
						'budgetid' => $return_budget_id_1,
						'nowterm' => 1,
						'userid' => $user->userid,
						'tuition' => 0,
						'danwei' => 'rmb',
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 6,
						'createtime' => time(),
						'lasttime' => time(),
						'adminid' => 10
					);
					//插入到 学费表
					$this->db->insert('tuition_info',$data_t_1_tuition);
					
					
				}else if($val['tuition'] <= $val['baseshu']){
					$data_t_jiangxuejin_budget = array(
						'userid' => $user->userid,
						'budget_type' => 1,
						'type' => 6,
						'term' => 1,
						'payable' => $val['baseshu'],
						'paid_in' => $val['tuition'],
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 4,
						'createtime' => time(),
						'lasttime' => time(),
						'remark' => !empty($val['remark'])?$val['remark']:'',
						'is_get_scholarship_money' => 3,
						'adminid' => 10
						
					);
					
					//插入到 收支表
					$this->db->insert('budget',$data_t_jiangxuejin_budget);
					
					//返回一个id
					$return_budget_id_1 = $this->db->insert_id();
					//组织 学费表
					$data_t_1_tuition = array(
						'budgetid' => $return_budget_id_1,
						'nowterm' => 1,
						'userid' => $user->userid,
						'tuition' => $val['tuition'],
						'danwei' => 'rmb',
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 4,
						'createtime' => time(),
						'lasttime' => time(),
						'adminid' => 10
					);
					//插入到 学费表
					$this->db->insert('tuition_info',$data_t_1_tuition);
				}else if($val['tuition'] > $val['baseshu']){
					$data_t_jiangxuejin_budget = array(
						'userid' => $user->userid,
						'budget_type' => 1,
						'type' => 6,
						'term' => 1,
						'payable' => $val['baseshu'],
						'paid_in' => $val['baseshu'],
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 4,
						'createtime' => time(),
						'lasttime' => time(),
						'remark' => !empty($val['remark'])?$val['remark']:'',
						'is_get_scholarship_money' => 3,
						'adminid' => 10
						
					);
					
					//插入到 收支表
					$this->db->insert('budget',$data_t_jiangxuejin_budget);
					
					//返回一个id
					$return_budget_id_1 = $this->db->insert_id();
					//组织 学费表
					$data_t_1_tuition = array(
						'budgetid' => $return_budget_id_1,
						'nowterm' => 1,
						'userid' => $user->userid,
						'tuition' => $val['baseshu'],
						'danwei' => 'rmb',
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 4,
						'createtime' => time(),
						'lasttime' => time(),
						'adminid' => 10
					);
					//插入到 学费表
					$this->db->insert('tuition_info',$data_t_1_tuition);
					
					
					$data_t_jiangxuejin_budget_2 = array(
						'userid' => $user->userid,
						'budget_type' => 1,
						'type' => 6,
						'term' => 2,
						'payable' => $val['baseshu'],
						'paid_in' => $val['tuition'] - $val['baseshu'],
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 4,
						'createtime' => time(),
						'lasttime' => time(),
						'remark' => !empty($val['remark'])?$val['remark']:'',
						'is_get_scholarship_money' => 3,
						'adminid' => 10
						
					);
					
					//插入到 收支表
					$this->db->insert('budget',$data_t_jiangxuejin_budget_2);
					
					//返回一个id
					$return_budget_id_2 = $this->db->insert_id();
					//组织 学费表
					$data_t_1_tuition_2 = array(
						'budgetid' => $return_budget_id_2,
						'nowterm' => 2,
						'userid' => $user->userid,
						'tuition' => $val['tuition'] - $val['baseshu'],
						'danwei' => 'rmb',
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 4,
						'createtime' => time(),
						'lasttime' => time(),
						'adminid' => 10
					);
					//插入到 学费表
					$this->db->insert('tuition_info',$data_t_1_tuition_2);
				}
			}else if($val['term'] > 1){
				
				if($val['tuition'] <= $val['baseshu']){
					$base = $val['term'] - 1;
					for($i = 1; $i <= $base;$i ++){
						$payable = $val['baseshu'];
						$paid_in = $val['baseshu'];
						if($i == $base){
							$paid_in = $val['tuition'];
						}
						
						$data_t_jiangxuejin_budget = array(
							'userid' => $user->userid,
							'budget_type' => 1,
							'type' => 6,
							'term' => $i,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_jiangxuejin_budget);
						
						//返回一个id
						$return_budget_id_1 = $this->db->insert_id();
						//组织 学费表
						$data_t_1_tuition = array(
							'budgetid' => $return_budget_id_1,
							'nowterm' => $i,
							'userid' => $user->userid,
							'tuition' => $paid_in,
							'danwei' => 'rmb',
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'adminid' => 10
						);
						//插入到 学费表
						$this->db->insert('tuition_info',$data_t_1_tuition);
					}
				}else if($val['tuition'] > $val['baseshu'] && $val['tuition'] <= $val['baseshu']*2){
					$base = $val['term'];
					for($i = 1; $i <= $base;$i ++){
						$payable = $val['baseshu'];
						$paid_in = $val['baseshu'];
						if($i == $base){
							$paid_in = $val['tuition'] - $val['baseshu'];
						}
						
						$data_t_jiangxuejin_budget = array(
							'userid' => $user->userid,
							'budget_type' => 1,
							'type' => 6,
							'term' => $i,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_jiangxuejin_budget);
						
						//返回一个id
						$return_budget_id_1 = $this->db->insert_id();
						//组织 学费表
						$data_t_1_tuition = array(
							'budgetid' => $return_budget_id_1,
							'nowterm' => $i,
							'userid' => $user->userid,
							'tuition' => $paid_in,
							'danwei' => 'rmb',
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'adminid' => 10
						);
						//插入到 学费表
						$this->db->insert('tuition_info',$data_t_1_tuition);
					}
					
				}else if($val['tuition'] > $val['baseshu']*2 && $val['tuition'] <= $val['baseshu']*3){
					$base = $val['term'] + 1;
					for($i = 1; $i <= $base;$i ++){
						$payable = $val['baseshu'];
						$paid_in = $val['baseshu'];
						if($i == $base){
							$paid_in = $val['tuition'] - $val['baseshu']*2;
						}
						
						$data_t_jiangxuejin_budget = array(
							'userid' => $user->userid,
							'budget_type' => 1,
							'type' => 6,
							'term' => $i,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_jiangxuejin_budget);
						
						//返回一个id
						$return_budget_id_1 = $this->db->insert_id();
						//组织 学费表
						$data_t_1_tuition = array(
							'budgetid' => $return_budget_id_1,
							'nowterm' => $i,
							'userid' => $user->userid,
							'tuition' => $paid_in,
							'danwei' => 'rmb',
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'adminid' => 10
						);
						//插入到 学费表
						$this->db->insert('tuition_info',$data_t_1_tuition);
					}
				}else if($val['tuition'] > $val['baseshu']*3 && $val['tuition'] <= $val['baseshu']*4){
					$base = $val['term'] + 2;
					for($i = 1; $i <= $base;$i ++){
						$payable = $val['baseshu'];
						$paid_in = $val['baseshu'];
						if($i == $base){
							$paid_in = $val['tuition'] - $val['baseshu']*3;
						}
						
						$data_t_jiangxuejin_budget = array(
							'userid' => $user->userid,
							'budget_type' => 1,
							'type' => 6,
							'term' => $i,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_jiangxuejin_budget);
						
						//返回一个id
						$return_budget_id_1 = $this->db->insert_id();
						//组织 学费表
						$data_t_1_tuition = array(
							'budgetid' => $return_budget_id_1,
							'nowterm' => $i,
							'userid' => $user->userid,
							'tuition' => $paid_in,
							'danwei' => 'rmb',
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'adminid' => 10
						);
						//插入到 学费表
						$this->db->insert('tuition_info',$data_t_1_tuition);
					}
				}else if($val['tuition'] == '免费'){
					$base = $val['term'];
					for($i = 1; $i <= $base;$i ++){
						$data_t_jiangxuejin_budget = array(
						'userid' => $user->userid,
						'budget_type' => 1,
						'type' => 6,
						'term' => $i,
						'payable' => 0,
						'paid_in' => 0,
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 6,
						'createtime' => time(),
						'lasttime' => time(),
						'remark' => !empty($val['remark'])?$val['remark']:'',
						'is_get_scholarship_money' => 3,
						'adminid' => 10
						
					);
					
					//插入到 收支表
					$this->db->insert('budget',$data_t_jiangxuejin_budget);
					
					//返回一个id
					$return_budget_id_1 = $this->db->insert_id();
					//组织 学费表
					$data_t_1_tuition = array(
						'budgetid' => $return_budget_id_1,
						'nowterm' => $i,
						'userid' => $user->userid,
						'tuition' => 0,
						'danwei' => 'rmb',
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 6,
						'createtime' => time(),
						'lasttime' => time(),
						'adminid' => 10
					);
					//插入到 学费表
					$this->db->insert('tuition_info',$data_t_1_tuition);
						
						
					}
					
					
					
				}else if(empty($val['tuition'])){
					$base = $val['term'] - 2;
					$payable = $val['baseshu'];
					$paid_in = $val['baseshu'];
					if($base > 0){
						for($i = 1; $i <= $base;$i ++){
							$data_t_jiangxuejin_budget = array(
							'userid' => $user->userid,
							'budget_type' => 1,
							'type' => 6,
							'term' => $i,
							'payable' => $payable,
							'paid_in' => $payable,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_jiangxuejin_budget);
						
						//返回一个id
						$return_budget_id_1 = $this->db->insert_id();
						//组织 学费表
						$data_t_1_tuition = array(
							'budgetid' => $return_budget_id_1,
							'nowterm' => $i,
							'userid' => $user->userid,
							'tuition' => $payable,
							'danwei' => 'rmb',
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'adminid' => 10
						);
						//插入到 学费表
						$this->db->insert('tuition_info',$data_t_1_tuition);
							
							
						}
					}
					
					
					
				}
				
				
				
				
			}
			
			/*
			die;
			
			if(empty($val['tuition']) || $val['tuition'] == '免费'){
				$data_t_1_budget = array(
						'userid' => $user->id,
						'budget_type' => 1,
						'type' => 6,
						'term' => $val['term'],
						'payable' => $term_tuition[$val['term']],
						'paid_in' => 0,
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 4,
						'createtime' => time(),
						'lasttime' => time(),
						'remark' => !empty($val['remark'])?$val['remark']:'',
						'is_get_scholarship_money' => 3,
						'adminid' => 10
						
					);
					
					//插入到 收支表
					$this->db->insert('budget',$data_t_1_budget);
					//返回一个id
					$return_budget_id_1 = $this->db->insert_id();
					//组织 学费表
					$data_t_1_tuition = array(
						'budgetid' => $return_budget_id_1,
						'nowterm' => 1,
						'userid' => $user->id,
						'tuition' => 0,
						'danwei' => 'rmb',
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 4,
						'createtime' => time(),
						'lasttime' => time(),
						'adminid' => 10
					);
					//插入到 学费表
					$this->db->insert('tuition_info',$data_t_1_tuition);
					
					continue;
				
			}
			
			
			
			//如果当前学期是 第一学期 教多了 则 划入到 第二学期里
			if($val['term'] == 1){
				if($val['tuition'] > $term_tuition[$val['term']]){
					//差值 放到 第二学期里
					$t_1_2 = $val['tuition'] - $term_tuition[$val['term']];
					//组织 两个数据 第一个是 第一 学期的数据 第二个是 第二学期的数据
					
					$data_t_1_budget = array(
						'userid' => $user->id,
						'budget_type' => 1,
						'type' => 6,
						'term' => 1,
						'payable' => $term_tuition[$val['term']],
						'paid_in' => $term_tuition[$val['term']],
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 4,
						'createtime' => time(),
						'lasttime' => time(),
						'remark' => !empty($val['remark'])?$val['remark']:'',
						'is_get_scholarship_money' => 3,
						'adminid' => 10
						
					);
					
					//插入到 收支表
					$this->db->insert('budget',$data_t_1_budget);
					//返回一个id
					$return_budget_id_1 = $this->db->insert_id();
					//组织 学费表
					$data_t_1_tuition = array(
						'budgetid' => $return_budget_id_1,
						'nowterm' => 1,
						'userid' => $user->id,
						'tuition' => $term_tuition[$val['term']],
						'danwei' => 'rmb',
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 4,
						'createtime' => time(),
						'lasttime' => time(),
						'adminid' => 10
					);
					//插入到 学费表
					$this->db->insert('tuition_info',$data_t_1_tuition);
					
					//组织 第二个数据
					$data_t_2_budget = array(
						'userid' => $user->id,
						'budget_type' => 1,
						'type' => 6,
						'term' => 2,
						'payable' => $term_tuition[$val['term']],
						'paid_in' => $t_1_2,
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 4,
						'createtime' => time(),
						'lasttime' => time(),
						'remark' => !empty($val['remark'])?$val['remark']:'',
						'is_get_scholarship_money' => 3,
						'adminid' => 10
						
					);
					
					//插入到 收支表
					$this->db->insert('budget',$data_t_2_budget);
					//返回一个id
					$return_budget_id_2 = $this->db->insert_id();
					//组织 学费表
					$data_t_2_tuition = array(
						'budgetid' => $return_budget_id_2,
						'nowterm' => 2,
						'userid' => $user->id,
						'tuition' => $t_1_2,
						'danwei' => 'rmb',
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 4,
						'createtime' => time(),
						'lasttime' => time(),
						'adminid' => 10
					);
					//插入到 学费表
					$this->db->insert('tuition_info',$data_t_2_tuition);
				}else{
					$data_t_1_budget = array(
						'userid' => $user->id,
						'budget_type' => 1,
						'type' => 6,
						'term' => 1,
						'payable' => $term_tuition[$val['term']],
						'paid_in' => $val['tuition'],
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 4,
						'createtime' => time(),
						'lasttime' => time(),
						'remark' => !empty($val['remark'])?$val['remark']:'',
						'is_get_scholarship_money' => 3,
						'adminid' => 10
						
					);
					
					//插入到 收支表
					$this->db->insert('budget',$data_t_1_budget);
					//返回一个id
					$return_budget_id_1 = $this->db->insert_id();
					//组织 学费表
					$data_t_1_tuition = array(
						'budgetid' => $return_budget_id_1,
						'nowterm' => 1,
						'userid' => $user->id,
						'tuition' => $val['tuition'],
						'danwei' => 'rmb',
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 4,
						'createtime' => time(),
						'lasttime' => time(),
						'adminid' => 10
					);
					//插入到 学费表
					$this->db->insert('tuition_info',$data_t_1_tuition);
				}
			}else{
				
				//  学期 大于 第一个 学期 
				//如果费用 小于或等于 当前学费  就直接 按 本学期 插入  若果是 大于 怎先向前推一个 学期 看余额 状况 再向后 推
				if($val['tuition'] < $term_tuition[$val['term']] || $val['tuition'] = $term_tuition[$val['term']]){
					$data_t_1_budget = array(
						'userid' => $user->id,
						'budget_type' => 1,
						'type' => 6,
						'term' => $val['term'],
						'payable' => $term_tuition[$val['term']],
						'paid_in' => $val['tuition'],
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 4,
						'createtime' => time(),
						'lasttime' => time(),
						'remark' => !empty($val['remark'])?$val['remark']:'',
						'is_get_scholarship_money' => 3,
						'adminid' => 10
						
					);
					
					//插入到 收支表
					$this->db->insert('budget',$data_t_1_budget);
					//返回一个id
					$return_budget_id_1 = $this->db->insert_id();
					//组织 学费表
					$data_t_1_tuition = array(
						'budgetid' => $return_budget_id_1,
						'nowterm' => $val['term'],
						'userid' => $user->id,
						'tuition' => $val['tuition'],
						'danwei' => 'rmb',
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 4,
						'createtime' => time(),
						'lasttime' => time(),
						'adminid' => 10
					);
					//插入到 学费表
					$this->db->insert('tuition_info',$data_t_1_tuition);
					
				}else{
					$i = 2;
					// 计算 差值  向前推一个 学期
					$t_1_2 = $val['tuition'] - $term_tuition[$val['term']];
					
					if($t_1_2 - $term_tuition[$val['term'] - 1] > 0){
						$i = 3;
						$t_1_2_3 = $t_1_2 - $term_tuition[$val['term'] - 1];
					}
					
				
						//组织 两个数据 第一个是 当前 学期  第二个是 前一个学期
					
					$data_t_1_budget = array(
						'userid' => $user->id,
						'budget_type' => 1,
						'type' => 6,
						'term' => $val['term'],
						'payable' => $term_tuition[$val['term']],
						'paid_in' => $term_tuition[$val['term']],
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 4,
						'createtime' => time(),
						'lasttime' => time(),
						'remark' => !empty($val['remark'])?$val['remark']:'',
						'is_get_scholarship_money' => 3,
						'adminid' => 10
						
					);
					
					//插入到 收支表
					$this->db->insert('budget',$data_t_1_budget);
					//返回一个id
					$return_budget_id_1 = $this->db->insert_id();
					//组织 学费表
					$data_t_1_tuition = array(
						'budgetid' => $return_budget_id_1,
						'nowterm' => $val['term'],
						'userid' => $user->id,
						'tuition' => $term_tuition[$val['term']],
						'danwei' => 'rmb',
						'paystate' => 1,
						'paytime' => time(),
						'paytype' => 4,
						'createtime' => time(),
						'lasttime' => time(),
						'adminid' => 10
					);
					//插入到 学费表
					$this->db->insert('tuition_info',$data_t_1_tuition);
					if($i == 2){
						//组织 第二个数据
						$data_t_2_budget = array(
							'userid' => $user->id,
							'budget_type' => 1,
							'type' => 6,
							'term' => $val['term'] - 1,
							'payable' => $term_tuition[$val['term']],
							'paid_in' => $t_1_2,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_2_budget);
						//返回一个id
						$return_budget_id_2 = $this->db->insert_id();
						//组织 学费表
						$data_t_2_tuition = array(
							'budgetid' => $return_budget_id_2,
							'nowterm' => $val['term'] - 1,
							'userid' => $user->id,
							'tuition' => $t_1_2,
							'danwei' => 'rmb',
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'adminid' => 10
						);
						//插入到 学费表
						$this->db->insert('tuition_info',$data_t_2_tuition);
					}else{
						//组织 第三个数据
						$data_t_3_budget = array(
							'userid' => $user->id,
							'budget_type' => 1,
							'type' => 6,
							'term' => $val['term'] + 1,
							'payable' => $term_tuition[$val['term']],
							'paid_in' => $t_1_2_3,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_3_budget);
						//返回一个id
						$return_budget_id_3 = $this->db->insert_id();
						//组织 学费表
						$data_t_3_tuition = array(
							'budgetid' => $return_budget_id_3,
							'nowterm' => $val['term'] + 1,
							'userid' => $user->id,
							'tuition' => $t_1_2_3,
							'danwei' => 'rmb',
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'adminid' => 10
						);
						//插入到 学费表
						$this->db->insert('tuition_info',$data_t_3_tuition);
					}
					
					
				}
				
			}*/
			
		}
		
	}
	
	/*
	*
	*保险费
	*/
	function export_fees_infrance(){
		$data = $this->db->get_where('export_fees','id > 0')->result_array();
		foreach($data as $key => $val){
			// 先通过 护照 去找 是否 存在 该用户 并且 查一下 所在 专业 报到时间
			$user = $this->db->select('id,majorid,userid')->get_where('student',"passport = '{$val['passport']}'")->row();
			if(empty($user)){
				continue;
			}
			
			if($val['term'] == 1){
				//都是 400 元
				if(empty($val['bxf'])){
					continue;
				}else if($val['bxf'] == '免费'){
					//免费
					//组织 第二个数据
						$data_t_2_budget = array(
							'userid' => $user->userid,
							'budget_type' => 1,
							'type' => 9,
							'term' => 1,
							'payable' => 0,
							'paid_in' => 0,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 6,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_2_budget);
						//返回一个id
						$return_budget_id_2 = $this->db->insert_id();
						//保险费表
						$data_infrance = array(
							'budgetid' => $return_budget_id_2,
							'payable' => 0,
							'paid_in' => 0,
							'deadline' => 1,
							'student_type' => 1,
							'userid' =>  $user->userid,
							'paystate' => 1,
							'paytime' => time(),
							'createtime' => time(),	
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'adminid' => 10,
							'term' => 1
							
						);
						
						$this->db->insert('insurance_info',$data_infrance);
				}else if($val['bxf'] == 400){
					//组织 第二个数据
						$data_t_2_budget = array(
							'userid' => $user->userid,
							'budget_type' => 1,
							'type' => 9,
							'term' => 1,
							'payable' => 400,
							'paid_in' => 400,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_2_budget);
						//返回一个id
						$return_budget_id_2 = $this->db->insert_id();
						//保险费表
						$data_infrance = array(
							'budgetid' => $return_budget_id_2,
							'payable' => 400,
							'paid_in' => 400,
							'deadline' => 1,
							'student_type' => 1,
							'userid' =>  $user->userid,
							'paystate' => 1,
							'paytime' => time(),
							'createtime' => time(),	
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'adminid' => 10,
							'term' => 1						
							
						);
						
						$this->db->insert('insurance_info',$data_infrance);
					
				}else if($val['bxf'] > 400 && $val['bxf'] <= 800){
					for($i = 1;$i<=2;$i++){
						$payable = 400;
						$paid_in = 400;
						if($i == 2){
							$paid_in = $val['bxf'] - 400;
						}
						//组织 第二个数据
						$data_t_2_budget = array(
							'userid' => $user->userid,
							'budget_type' => 1,
							'type' => 9,
							'term' => $i,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_2_budget);
						//返回一个id
						$return_budget_id_2 = $this->db->insert_id();
						//保险费表
						$data_infrance = array(
							'budgetid' => $return_budget_id_2,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'deadline' => 1,
							'student_type' => 1,
							'userid' =>  $user->userid,
							'paystate' => 1,
							'paytime' => time(),
							'createtime' => time(),	
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'adminid' => 10,
							'term' => $i						
							
						);
						
						$this->db->insert('insurance_info',$data_infrance);
					}
				}
			}else if($val['term'] == 2){
				if(empty($val['bxf'])){
					continue;
				}else if($val['bxf'] == '免费'){
					for($i = 1;$i<=2;$i++){
						//免费
					//组织 第二个数据
						$data_t_2_budget = array(
							'userid' => $user->userid,
							'budget_type' => 1,
							'type' => 9,
							'term' => $i,
							'payable' => 0,
							'paid_in' => 0,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 6,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_2_budget);
						//返回一个id
						$return_budget_id_2 = $this->db->insert_id();
						//保险费表
						$data_infrance = array(
							'budgetid' => $return_budget_id_2,
							'payable' => 0,
							'paid_in' => 0,
							'deadline' => 1,
							'student_type' => 1,
							'userid' =>  $user->userid,
							'paystate' => 1,
							'paytime' => time(),
							'createtime' => time(),	
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'adminid' => 10,
							'term' => $i
							
						);
						
						$this->db->insert('insurance_info',$data_infrance);
					}
				}else if($val['bxf'] <= 400){
					//免费
					//组织 第二个数据
						$data_t_2_budget = array(
							'userid' => $user->userid,
							'budget_type' => 1,
							'type' => 9,
							'term' => 1,
							'payable' => 400,
							'paid_in' => $val['bxf'],
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_2_budget);
						//返回一个id
						$return_budget_id_2 = $this->db->insert_id();
						//保险费表
						$data_infrance = array(
							'budgetid' => $return_budget_id_2,
							'payable' => 400,
							'paid_in' => $val['bxf'],
							'deadline' => 1,
							'student_type' => 1,
							'userid' =>  $user->userid,
							'paystate' => 1,
							'paytime' => time(),
							'createtime' => time(),	
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'adminid' => 10,
							'term' => 1
							
						);
						
						$this->db->insert('insurance_info',$data_infrance);
				}else if($val['bxf'] > 400 && $val['bxf'] <= 800){
					for($i = 1;$i<=2;$i++){
						$payable = 400;
						$paid_in = 400;
						if($i == 2){
							$paid_in = $val['bxf'] - 400;
						}
						//组织 第二个数据
						$data_t_2_budget = array(
							'userid' => $user->userid,
							'budget_type' => 1,
							'type' => 9,
							'term' => $i,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_2_budget);
						//返回一个id
						$return_budget_id_2 = $this->db->insert_id();
						//保险费表
						$data_infrance = array(
							'budgetid' => $return_budget_id_2,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'deadline' => 1,
							'student_type' => 1,
							'userid' =>  $user->userid,
							'paystate' => 1,
							'paytime' => time(),
							'createtime' => time(),	
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'adminid' => 10,
							'term' => $i						
							
						);
						
						$this->db->insert('insurance_info',$data_infrance);
					}
				}else if($val['bxf'] > 800 && $val['bxf'] <= 1200){
					for($i = 1;$i<=3;$i++){
						$payable = 400;
						$paid_in = 400;
						if($i == 3){
							$paid_in = $val['bxf'] - 800;
						}
						//组织 第二个数据
						$data_t_2_budget = array(
							'userid' => $user->userid,
							'budget_type' => 1,
							'type' => 9,
							'term' => $i,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_2_budget);
						//返回一个id
						$return_budget_id_2 = $this->db->insert_id();
						//保险费表
						$data_infrance = array(
							'budgetid' => $return_budget_id_2,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'deadline' => 1,
							'student_type' => 1,
							'userid' =>  $user->userid,
							'paystate' => 1,
							'paytime' => time(),
							'createtime' => time(),	
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'adminid' => 10,
							'term' => $i						
							
						);
						
						$this->db->insert('insurance_info',$data_infrance);
					}
				}
			}else{
				// 学期 大于 2 缴费 基数 都是 300 老生
				if(empty($val['bxf'])){
					$base = $val['term'] - 2;
					for($i = 1;$i <= $base;$i++){
						$payable = 300;
						$paid_in = 300;
						
						//组织 第二个数据
						$data_t_2_budget = array(
							'userid' => $user->userid,
							'budget_type' => 1,
							'type' => 9,
							'term' => $i,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_2_budget);
						//返回一个id
						$return_budget_id_2 = $this->db->insert_id();
						//保险费表
						$data_infrance = array(
							'budgetid' => $return_budget_id_2,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'deadline' => 1,
							'student_type' => 2,
							'userid' =>  $user->userid,
							'paystate' => 1,
							'paytime' => time(),
							'createtime' => time(),	
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'adminid' => 10,
							'term' => $i						
							
						);
						
						$this->db->insert('insurance_info',$data_infrance);
					}
				}else if($val['bxf'] == '免费'){
					$base = $val['term'];
					for($i = 1;$i <= $base;$i++){
						$payable = 0;
						$paid_in = 0;
						
						//组织 第二个数据
						$data_t_2_budget = array(
							'userid' => $user->userid,
							'budget_type' => 1,
							'type' => 9,
							'term' => $i,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 6,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_2_budget);
						//返回一个id
						$return_budget_id_2 = $this->db->insert_id();
						//保险费表
						$data_infrance = array(
							'budgetid' => $return_budget_id_2,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'deadline' => 1,
							'student_type' => 2,
							'userid' =>  $user->userid,
							'paystate' => 1,
							'paytime' => time(),
							'createtime' => time(),	
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'adminid' => 10,
							'term' => $i						
							
						);
						
						$this->db->insert('insurance_info',$data_infrance);
					}
					
					
				}else if($val['bxf'] <= 300){
					
					$base = $val['term'] -1;
					for($i = 1;$i <= $base;$i++){
						$payable = 300;
						$paid_in = 300;
						if($base == $i){
							$paid_in = $val['bxf'];
						}
						//组织 第二个数据
						$data_t_2_budget = array(
							'userid' => $user->userid,
							'budget_type' => 1,
							'type' => 9,
							'term' => $i,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_2_budget);
						//返回一个id
						$return_budget_id_2 = $this->db->insert_id();
						//保险费表
						$data_infrance = array(
							'budgetid' => $return_budget_id_2,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'deadline' => 1,
							'student_type' => 2,
							'userid' =>  $user->userid,
							'paystate' => 1,
							'paytime' => time(),
							'createtime' => time(),	
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'adminid' => 10,
							'term' => $i						
							
						);
						
						$this->db->insert('insurance_info',$data_infrance);
					}
					
					
				}else if($val['bxf'] > 300 && $val['bxf'] <= 600){
					
					$base = $val['term'];
					for($i = 1;$i <= $base;$i++){
						$payable = 300;
						$paid_in = 300;
						if($base == $i){
							$paid_in = $val['bxf'] - 300;
						}
						//组织 第二个数据
						$data_t_2_budget = array(
							'userid' => $user->userid,
							'budget_type' => 1,
							'type' => 9,
							'term' => $i,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_2_budget);
						//返回一个id
						$return_budget_id_2 = $this->db->insert_id();
						//保险费表
						$data_infrance = array(
							'budgetid' => $return_budget_id_2,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'deadline' => 1,
							'student_type' => 2,
							'userid' =>  $user->userid,
							'paystate' => 1,
							'paytime' => time(),
							'createtime' => time(),	
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'adminid' => 10,
							'term' => $i						
							
						);
						
						$this->db->insert('insurance_info',$data_infrance);
					}
					
					
				}else if($val['bxf'] > 600 && $val['bxf'] <= 900){
					
					$base = $val['term'] +1;
					for($i = 1;$i <= $base;$i++){
						$payable = 300;
						$paid_in = 300;
						if($base == $i){
							$paid_in = $val['bxf'] - 600;
						}
						//组织 第二个数据
						$data_t_2_budget = array(
							'userid' => $user->userid,
							'budget_type' => 1,
							'type' => 9,
							'term' => $i,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_2_budget);
						//返回一个id
						$return_budget_id_2 = $this->db->insert_id();
						//保险费表
						$data_infrance = array(
							'budgetid' => $return_budget_id_2,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'deadline' => 1,
							'student_type' => 2,
							'userid' =>  $user->userid,
							'paystate' => 1,
							'paytime' => time(),
							'createtime' => time(),	
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'adminid' => 10,
							'term' => $i						
							
						);
						
						$this->db->insert('insurance_info',$data_infrance);
					}
					
					
				}else if($val['bxf'] > 900 && $val['bxf'] <= 1200){
					
					$base = $val['term'] +2;
					for($i = 1;$i <= $base;$i++){
						$payable = 300;
						$paid_in = 300;
						if($base == $i){
							$paid_in = $val['bxf'] - 900;
						}
						//组织 第二个数据
						$data_t_2_budget = array(
							'userid' => $user->userid,
							'budget_type' => 1,
							'type' => 9,
							'term' => $i,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_2_budget);
						//返回一个id
						$return_budget_id_2 = $this->db->insert_id();
						//保险费表
						$data_infrance = array(
							'budgetid' => $return_budget_id_2,
							'payable' => $payable,
							'paid_in' => $paid_in,
							'deadline' => 1,
							'student_type' => 2,
							'userid' =>  $user->userid,
							'paystate' => 1,
							'paytime' => time(),
							'createtime' => time(),	
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'adminid' => 10,
							'term' => $i						
							
						);
						
						$this->db->insert('insurance_info',$data_infrance);
					}
					
					
				}
				
				
				
				
				
			}
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			// 免费 和 空 按半年 计算
			//if($val['isnew'] == 1){
			//	$base_data = 400;
		//	}else{
		//		$base_data = 300;
		//	}
		
		
		
		
		
		
		
		
		/*
			
			if(empty($val['bxf']) || $val['bxf'] == '免费'){
				//组织 第二个数据
						$data_t_2_budget = array(
							'userid' => $user->id,
							'budget_type' => 1,
							'type' => 9,
							'term' => !empty($val['term'])?$val['term']:1,
							'payable' => $base_data,
							'paid_in' => 0,
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_2_budget);
						//返回一个id
						$return_budget_id_2 = $this->db->insert_id();
						//保险费表
						$data_infrance = array(
							'budgetid' => $return_budget_id_2,
							'payable' => $base_data,
							'paid_in' => 0,
							'deadline' => 1,
							'student_type' => $val['isnew'],
							'userid' =>  $user->id,
							'paystate' => 1,
							'paytime' => time(),
							'createtime' => time(),	
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'adminid' => 10						
							
						);
						
						$this->db->insert('insurance_info',$data_infrance);
			}else{
				if($val['bxf'] % $base_data != 0){
					$r = !empty($val->remark)?$val->remark.',':'';
					$r.='数据不正确 ，无法导入';
					$this->db->update('export_fees',array('expremark' => $r),'id = '.$val['id']);
					
					continue;
				}
				
				//计算年数
				$year = $val['bxf'] / $base_data;
				if($year == 0){
					$year = 1;
				}
				
				$data_t_2_budget = array(
							'userid' => $user->id,
							'budget_type' => 1,
							'type' => 9,
							'term' => !empty($val['term'])?$val['term']:1,
							'payable' => $year*$base_data,
							'paid_in' => $val['bxf'],
							'paystate' => 1,
							'paytime' => time(),
							'paytype' => 4,
							'createtime' => time(),
							'lasttime' => time(),
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'is_get_scholarship_money' => 3,
							'adminid' => 10
							
						);
						
						//插入到 收支表
						$this->db->insert('budget',$data_t_2_budget);
						//返回一个id
						$return_budget_id_2 = $this->db->insert_id();
						//保险费表
						$data_infrance = array(
							'budgetid' => $return_budget_id_2,
							'payable' => $year*$base_data,
							'paid_in' => $val['bxf'],
							'deadline' => $year,
							'student_type' => $val['isnew'],
							'userid' =>  $user->id,
							'paystate' => 1,
							'paytime' => time(),
							'createtime' => time(),	
							'remark' => !empty($val['remark'])?$val['remark']:'',
							'adminid' => 10						
							
						);
						
						$this->db->insert('insurance_info',$data_infrance);
			}*/
		}
		
	}
}