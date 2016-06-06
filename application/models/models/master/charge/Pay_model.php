<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Pay_Model extends CI_Model {
	const T_MAJOR='major';
	const T_STUDENT='student';
	const T_MAJOR_TUITION='major_tuition';
	const T_ACC='accommodation_info';
	const T_TUITION_INFO='tuition_info';
	const T_ELECTRIC_INFO='electric_info';
	const T_QUA_INFO='quarterage_info';
	const T_ACC_PLE_INFO='acc_pledge_info';//住宿押金表
	const T_APP='apply_info';
	const T_SCH_INFO = 'scholarship_info';
	const T_CAMPUS = 'school_accommodation_campus'; // 院校校区表
	const T_BULIDING = 'school_accommodation_buliding'; // 院校校区内容表
	const T_PRICES = 'school_accommodation_prices'; // 院校校区宿舍价格表
	const T_PICTURES = 'school_accommodation_picture'; // 院校校区宿舍图片表
	const T_STUDENT_REBUILD='student_rebuild';//重修费用表
	const T_STU_BAR_CARD='student_barter_card';//换证费表
	const T_TUITION_DETAIL='tuition_info_detail';//学费详情表
	const T_CRE='credentials';//凭据表
	const T_MAJOR_COURSE='major_course';
	const T_COURSE_BOOKS='course_books';
	const T_BOOKS='books';
	const T_BOOKS_FEE='books_fee';//书费表
	const T_ORDERBY='apply_order_info';//所有订单表
	const T_INSURANCE_INFO='insurance_info';//保险费表
	const T_SQUAD='squad';
	const T_J_X_J_APP='applyscholarship_info';
	const T_STUDENT_INFO='student_info';
	const T_DEPOSIT_INFO='deposit_info';//申请押金表




	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	/**
	 *
	 *获取该条件的学生
	 **/
	function get_where_student($key,$value){
		$this->db->select('student_info.*');
		$this->db->like('student_info.'.$key,$value);
		$this->db->join(self::T_APP ,self::T_APP.'.userid='.self::T_STUDENT_INFO.'.id');
		$this->db->where('apply_info.state >=7');
		return $this->db->get(self::T_STUDENT_INFO)->result_array();
	}
	/**
	 * [get_tuition 获取实收的学费  如果是新生默认收取第一学期的学费]
	 * @return [type] [description]
	 */
	function get_tuition($userid){
		if(!empty($userid)){
			//判断是否为新生
			$udata=$this->db->get(self::T_STUDENT,'userid = '.$userid)->row_array();
			if(!empty($udata)&&!empty($userid['squadid'])){
				//老学生
			}else{
				//获取第一学期的学费
				$this->db->where('majorid = '.$udata['major'].' AND term=1');
				$num=$this->db->get(self::T_MAJOR_TUITION)->row_array();
				if(!empty($num['tuition'])){
					return $num['tuition'];
				}
			}
		}
		return 0;
	}
	/**
	 * [get_book 获取书费
	 * @param  [type] $userid [description]
	 * @return [type]         [description]
	 */
	function get_book($userid){

		// if(!empty($userid)){
		// 	//获取学生信息
		// 	$udata=$this->db->get(self::T_STUDENT,'userid = '.$userid)->row_array();
		// 	if(!empty($udata)&&!empty($userid['major'])){
		// 		//获取当前专业的所有书籍
		// 		$this->db->where('majorid = '.$udata['major'].' AND term=1');
		// 		$num=$this->db->get(self::T_MAJOR_TUITION)->row_array();
		// 		if(!empty($num['tuition'])){
		// 			return $num['tuition'];
		// 		}
		// 	}
		// }
		return 0;
	}
	/**
	 * [get_book 获取住宿费 根据住宿订单表里开始入住时间
	 * @param  [type] $userid [description]
	 * @return [type]         [description]
	 */
	function get_acc($userid){
		if(!empty($userid)){
			//判断是否为新生
			$this->db->where('userid = '.$userid);
			$udata=$this->db->get(self::T_ACC)->row_array();
			//获取当前房间的价格
			$now_prices=$this->get_now_room_prices($udata['roomid']);
			if(!empty($udata)){
				return $now_prices*$udata['accendtime'];	
			}
		}
		return 0;
	}
	//获取当前房间的价格
	function get_now_room_prices($rid){
		if(!empty($rid)){
			$this->db->select('prices');
			$this->db->where('id',$rid);
			$data=$this->db->get(self::T_PRICES)->row_array();
			if(!empty($data['prices'])){
				return $data['prices'];
			}
			return 0;
		}
	}
	/**
	 * [insert_tuition 插入学费表]
	 * @param  [type] $userid  [description]
	 * @param  [type] $tuition [description]
	 * @return [type]          [description]
	 */
	function insert_tuition($userid,$tuition,$term=1){
		if(!empty($userid)&&!empty($tuition)){
			$user_info=$this->db->get(self::T_STUDENT,'userid = '.$userid)->row_array();
			$max_cucasid = build_order_no ();
			$data['nowterm']=$term;
			$data['userid']=$userid;
			$data['ordernumber']= $max_cucasid;
			$data['tuition']=$tuition;
			$data['danwei']='rmb';
			$data['adminid']=$_SESSION['master_user_info']->id;
			$data['name']=$user_info['name'];
			$data['email']=$user_info['email'];
			$data['nationality']=$user_info['nationality'];
			$data['mobile']=$user_info['mobile'];
			$data['tel']=$user_info['tel'];
			$data['passport']=$user_info['passport'];
			$data['paystate']=1;
			$data['paytime']=time();
			$data['createtime']=time();
			$this->db->insert(self::T_TUITION_INFO,$data);
			$id=$this->db->insert_id();
			if(!empty($id)){
				$this->insert_scholarship_order($id,$userid,6,$tuition);
			}
		}
	}
	/**
	 * [insert_scholarship_order 讲学支付插入订单表]
	 * @return [type] [description]
	 */
	function insert_scholarship_order($id,$userid,$ordertype,$money){
		if(!empty($userid)&&!empty($ordertype)){
			$arr['createtime']=time();
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['ordertype']=$ordertype;
			$arr['ordermondey']=$money;
			$arr['userid']=$userid;
			$arr['applyid']=$id;
			$arr['lasttime']=time();
			$arr['paytime']=time();
			$arr['paystate']=1;
			$arr['is_scholarship']=1;
			$this->db->insert(self::T_ORDERBY,$arr);
			}
		
	}
	/**
	 * [insert_tuition 插入学费表]
	 * @param  [type] $userid  [description]
	 * @param  [type] $tuition [description]
	 * @return [type]          [description]p
	 */
	function type_insert_tuition($data){
		//插入学费表  返回id再插入订单表
		$tuition_id=$this->type_insert_tuition_one($data);
		if(!empty($tuition_id)){
			//插入订单表
			$orderid=$this->insert_order($data,$tuition_id,6);
			//如果是凭据用户  则插入凭据表
			if($data['isproof']==1){
				$creid=$this->insert_tuition_credentials($data,$orderid);
			}
			
			
			if(!empty($orderid)){
				return $orderid;
			}
		}
		return 0;
	}
	/**
	 * [insert_tuition_order 插入学费所有订单表]
	 * @return [type] [description]
	 */
	function insert_order($data,$id,$ordertype){
		if(!empty($data)){
			$arr['createtime']=time();
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['ordertype']=$ordertype;
			$arr['userid']=$data['userid'];
			$arr['applyid']=$id;
			$arr['ordermondey']=$data['paid_in'];
			$arr['lasttime']=time();
			if($data['isproof']==1){
				$arr['paytype']=3;
			}
			$arr['paytime']=time();
			$arr['paystate']=1;
			$this->db->insert(self::T_ORDERBY,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [type_insert_tuition_one 插入学费表返回id]
	 * @return [type] [description]
	 */
	function type_insert_tuition_one($data){
		if(!empty($data)){
			$user_info=$this->db->get(self::T_STUDENT,'userid = '.$data['userid'])->row_array();
			//先查询有没有该生该学期的记录
			$is_youmeiyou=$this->check_is_tuition($data);
			
			$max_cucasid = build_order_no ();
			$arr['nowterm']=$data['term'];
			$arr['userid']=$data['userid'];
			$arr['tuition']=$data['paid_in'];
			$arr['danwei']='rmb';
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$arr['name']=$user_info['name'];
			$arr['email']=$user_info['email'];
			$arr['nationality']=$user_info['nationality'];
			$arr['mobile']=$user_info['mobile'];
			$arr['tel']=$user_info['tel'];
			$arr['passport']=$user_info['passport'];
			$arr['paystate']=1;
			$arr['paytime']=time();
			$arr['createtime']=time();
			$arr['isproof']=$data['isproof'];
			$arr['remark']=$data['remark'];
			if(!empty($is_youmeiyou)){
				$this->db->update(self::T_TUITION_INFO,$arr,'id = '.$is_youmeiyou);
				$id= $is_youmeiyou;
			}else{
				$arr['number']= $max_cucasid;

				$this->db->insert(self::T_TUITION_INFO,$arr);
				$id= $this->db->insert_id();
			}
			
			if(!empty($data['rebuild_ids'])||!empty($data['barter_card_ids'])||!empty($data['abatement'])){
				//插入补缴重修费&&换证费&&续读生补缴额度
				$this->insert_tuition_detail($data,$id);
			}
			return $id;
		}
	}
	/**
	 * [check_is_tuition 检查有没有生成这条订单]
	 * @return [type] [description]
	 */
	function check_is_tuition($data){
		if(!empty($data)){
			$this->db->where('nowterm',$data['term']);
			$this->db->where('userid',$data['userid']);
			$this->db->where('paystate',0);
			$data=$this->db->get(self::T_TUITION_INFO)->row_array();
			return $data['id'];
		}
	}
	/**
	 * [insert_tuition_detail 插入学费详细]
	 * @return [type] [description]
	 */
	function insert_tuition_detail($data,$tuitionid){
		if(!empty($data)){
			if(!empty($data['rebuild_ids'])){
				$arr['rebuild_ids']=$data['rebuild_ids'];
				$arr['rebuild_money']=$data['rebuild'];
			}
			if(!empty($data['abatement'])){
					$arr['abatement']=$data['abatement'];
				}
			if(!empty($data['barter_card_ids'])){
				$arr['barter_card_ids']=$data['barter_card_ids'];
				$arr['barter_card_money']=$data['barter_card'];
			}
			if(!empty($data['apply_discount'])){
					$arr['apply_discount']=$data['apply_discount'];
				}
			if(!empty($data['scholarship_discount'])){
					$arr['scholarship_discount']=$data['scholarship_discount'];
				}
			if(!empty($data['entry_fee'])){
					$arr['entry_fee']=$data['entry_fee'];
				}
			if(!empty($data['pledge_fee'])){
					$arr['pledge_fee']=$data['pledge_fee'];
				}
			$arr['tuitionid']=$tuitionid;
			$this->db->insert(self::T_TUITION_DETAIL,$arr);
		}

	}
	/**
	 * [insert_electric 插入电费表]
	 * @return [type] [description]
	 */
	function insert_electric($userid,$electric){
		if(!empty($userid)&&!empty($electric)){
			$udata=$this->db->get(self::T_ACC,'userid = '.$userid)->row_array();
			$max_cucasid = build_order_no ();
			$data['userid']=$userid;
			$data['ordernumber']= $max_cucasid;
			$data['campusid']=$udata['campid'];
			$data['buildingid']=$udata['buildingid'];
			$data['floor']=$udata['floor'];
			$data['roomid']=$udata['roomid'];
			$data['pay']=$electric;
			$data['state']=1;
			$data['paytime']=time();
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_ELECTRIC_INFO,$data);
			$id=$this->db->insert_id();
			if(!empty($id)){
				$this->insert_scholarship_order($id,$userid,7,$electric);
			}
			//更新到一直跑着的住宿订单表里的电费字段
			//获取原先的电费金钱
			$old_electric=$this->db->select('electric_money')->where('userid',$userid)->get(self::T_ACC)->row_array();
			$new_electric['electric_money']=$electric+$old_electric['electric_money'];
			$this->db->where('userid',$userid)->update(self::T_ACC,$new_electric);
		}
	}
	/**
	 * [insert_electric 插入电费表]
	 * @return [type] [description]
	 */
	function type_insert_electric($data){
		//先插入电费缴费记录表
		$ele_id=$this->type_insert_electric_one($data);
		if(!empty($ele_id)){
			//所有订单表
			$orderid=$this->insert_order($data,$tuition_id,7);
			//插入凭据表
			if($data['isproof'==1]){
				$creid=$this->insert_electric_credentials($data,$orderid);
			}
			if(!empty($orderid)){
				return $orderid;
			}		
		}
		return 0;
	}
	/**
	 * [type_insert_electric_one 插入电费缴费记录表]
	 * @return [type] [description]
	 */
	function type_insert_electric_one($data){	
		if(!empty($data)){
			$udata=$this->db->get(self::T_ACC,'userid = '.$data['userid'])->row_array();
			$max_cucasid = build_order_no ();
			$arr['userid']=$data['userid'];
			$arr['ordernumber']= $max_cucasid;
			$arr['campusid']=$udata['campid'];
			$arr['buildingid']=$udata['buildingid'];
			$arr['floor']=$udata['floor'];
			$arr['roomid']=$udata['roomid'];
			$arr['pay']=$data['electric'];
			$arr['state']=1;
			$arr['paytime']=time();
			$arr['createtime']=time();
			if($data['isproof']==1){
				$arr['isproof']=$data['isproof'];
				$arr['proof_path']=$data['file'];
			}
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_ELECTRIC_INFO,$arr);
			$ele_id=$this->db->insert_id();
			//更新到一直跑着的住宿订单表里的电费字段
			//获取原先的电费金钱
			$old_electric=$this->db->select('electric_money')->where('userid = '.$data['userid'].' AND acc_state = 6')->get(self::T_ACC)->row_array();
			$new_electric['electric_money']=$data['electric']+$old_electric['electric_money'];
			$this->db->where('userid',$data['userid'])->update(self::T_ACC,$new_electric);
			return $ele_id;
		}
	}
	/**
	 * [insert_electric_credentials 电费插入所有订单表]
	 * @return [type] [description]
	 */	
	function insert_electric_credentials($data,$ele_id){
		if(!empty($data)&&!empty($ele_id)){
			$arr['orderid']=$ele_id;
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$data['userid'];
			$arr['amount']=$data['electric'];
			$arr['item']=7;
			$arr['currency']='rmb';
			$arr['way']=3;
			$arr['state']=1;
			$arr['remark']=$data['remark'];
			$arr['createtime']=time();
			$arr['ordertype']=7;
			if($data['isproof']==1){
				$arr['number']=$data['proof_number'];
				$arr['file']=$data['file'];
			}
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_CRE,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [insert_credentials 学费插入所有的订单表]
	 * @return [type] [description]
	 */
	function insert_tuition_credentials($data,$tuitionid){
		if(!empty($data)&&!empty($tuitionid)){
			$arr['orderid']=$tuitionid;
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$data['userid'];
			$arr['amount']=$data['paid_in'];
			$arr['item']=6;
			$arr['currency']='rmb';
			$arr['way']=3;
			$arr['state']=1;
			$arr['remark']=$data['remark'];
			$arr['createtime']=time();
			$arr['ordertype']=6;
			if($data['isproof']==1){
				$arr['number']=$data['proof_number'];
				$arr['file']=$data['file'];
			}
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_CRE,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [insert_acc 插入住宿费表]
	 * @return [type] [description]
	 */
	function insert_acc($userid,$acc){
		if(!empty($userid)&&!empty($acc)){
			$udata=$this->db->get(self::T_ACC,'userid = '.$userid)->row_array();
			$max_cucasid = build_order_no ();
			$data['userid']=$userid;
			$data['ordernumber']= $max_cucasid;
			$data['campusid']=$udata['campid'];
			$data['buildingid']=$udata['buildingid'];
			$data['floor']=$udata['floor'];
			$data['roomid']=$udata['roomid'];
			$data['pay']=$acc;
			$data['state']=1;
			$data['paytime']=time();
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_QUA_INFO,$data);
			$id=$this->db->insert_id();
			if(!empty($id)){
				$this->insert_scholarship_order($id,$userid,4,$acc);
			}
			//更新到一直跑着的住宿订单表里的电费字段
			//获取原先的电费金钱
			$old_acc=$this->db->select('acc_money')->where('userid',$userid)->get(self::T_ACC)->row_array();
			$new_acc['acc_money']=$acc+$old_acc['acc_money'];
			$this->db->where('userid',$userid)->update(self::T_ACC,$new_acc);
		}	
	}
	/**
	 * [insert_acc 插入住宿费表]
	 * @return [type] [description]
	 */
	function insert_book($userid,$book,$book_ids){
		if(!empty($userid)){
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$userid;
			$arr['book_ids']=$book_ids;
			// $arr['payable']=$data['book_money'];
			$arr['pay']=$book;
			$arr['paytime']=time();
			$arr['createtime']=time();
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_BOOKS_FEE,$arr);
			$id= $this->db->insert_id();
			if(!empty($id)){
				$this->insert_scholarship_order($id,$userid,8,$book);
			}
		}
	}
	/**
	 * [insert_acc 插入住宿费表]
	 * @return [type] [description]
	 */
	function insert_insurance($userid,$insurance){
		if(!empty($userid)){
			$user_info=$this->db->get(self::T_STUDENT,'userid = '.$userid)->row_array();
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$userid;
			// $arr['insurance_ids']=$data['insurance_ids'];
			// $arr['payable']=$data['insurance_money'];
			$arr['premium']=$insurance;
			$arr['paytime']=time();
			$arr['createtime']=time();
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$arr['majorid']=$user_info['major'];
			$arr['squadid']=$user_info['squadid'];
			$arr['term']=1;
			$squad_info=$this->db->get(self::T_SQUAD,'id = '.$user_info['squadid'])->row_array();
			$arr['effect_time']=$squad_info['classtime'];
			$this->db->insert(self::T_INSURANCE_INFO,$arr);
			$id= $this->db->insert_id();
			if(!empty($id)){
				$this->insert_scholarship_order($id,$userid,9,$insurance);
			}
		}
	}
	/**
	 * [insert_acc 按类型插入住宿费表]
	 * @return [type] [description]
	 */
	function type_insert_acc($data){
		//插入住宿表
		$acc_id=$this->insert_acc_fee($data);
		if(!empty($acc_id)){
			//插入所有订单表
			$orderid=$this->insert_order($data,$acc_id,4);
			if($data['isproof']==1){
					$id=$this->insert_acc_credentials($data,$orderid);
			}
			return $orderid;
		}	
		return 0;
	}
	/**
	 * [insert_acc_fee 插入住宿费表  返回插入id]
	 * @return [type] [description]
	 */
	function insert_acc_fee($data){
		if(!empty($data)){
			$udata=$this->db->get(self::T_ACC,'userid = '.$data['userid'])->row_array();
			$max_cucasid = build_order_no ();
			$arr['userid']=$data['userid'];
			$arr['ordernumber']= $max_cucasid;
			$arr['campusid']=$udata['campid'];
			$arr['buildingid']=$udata['buildingid'];
			$arr['floor']=$udata['floor'];
			$arr['roomid']=$udata['roomid'];
			$arr['pay']=$data['paid_in'];
			$arr['state']=1;
			$arr['paytime']=time();
			$arr['createtime']=time();
			$arr['day_fee']=$data['day'];
			$arr['adminid']=$_SESSION['master_user_info']->id;
			if($data['isproof']==1){
				$arr['proof_number']=$data['proof_number'];
				$arr['proof_path']=$data['file'];
			}
			$this->db->insert(self::T_QUA_INFO,$arr);
			$acc_id=$this->db->insert_id();
			//更新到一直跑着的住宿订单表里的电费字段
			//获取原先的电费金钱
			$old_acc=$this->db->select('acc_money')->where('userid',$data['userid'])->get(self::T_ACC)->row_array();
			$new_acc['acc_money']=$data['paid_in']+$old_acc['acc_money'];
			$this->db->where('userid',$data['userid'])->update(self::T_ACC,$new_acc);
			return $acc_id;
		}
	}

	function insert_acc_credentials($data,$acc_id){
		if(!empty($data)&&!empty($acc_id)){
			$arr['orderid']=$acc_id;
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$data['userid'];
			$arr['amount']=$data['acc'];
			$arr['item']=4;
			$arr['currency']='rmb';
			$arr['way']=3;
			$arr['state']=1;
			$arr['remark']=$data['remark'];
			$arr['createtime']=time();
			$arr['ordertype']=4;
			if($data['isproof']==1){
				$arr['number']=$data['proof_number'];
				$arr['file']=$data['file'];
			}
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_CRE,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [insert_acc 插入住宿费押金表]
	 * @return [type] [description]
	 */
	function insert_acc_pledge($userid,$acc_pledge){
		if(!empty($userid)&&!empty($acc_pledge)){
			$udata=$this->db->get(self::T_ACC,'userid = '.$userid)->row_array();
			$max_cucasid = build_order_no ();
			$data['userid']=$userid;
			$data['ordernumber']= $max_cucasid;
			$data['campusid']=$udata['campid'];
			$data['buildingid']=$udata['buildingid'];
			$data['floor']=$udata['floor'];
			$data['roomid']=$udata['roomid'];
			$data['pay']=$acc_pledge;
			$data['state']=1;
			$data['paytime']=time();
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_ACC_PLE_INFO,$data);
			$id=$this->db->insert_id();
			if(!empty($id)){
				$this->insert_scholarship_order($id,$userid,10,$acc_pledge);
			}
			//更新到一直跑着的住宿订单表里的电费字段
			//获取原先的电费金钱
			$old_acc_pledge=$this->db->select('acc_pledge_money')->where('userid',$userid)->get(self::T_ACC)->row_array();
			$new_acc_pledge['acc_pledge_money']=$acc_pledge+$old_acc_pledge['acc_pledge_money'];
			$this->db->where('userid',$userid)->update(self::T_ACC,$new_acc_pledge);
		}	
	}
	/**
	 * [get_scholarship 判断是不是奖学金用户]
	 * @return [type] [description]
	 */
	function get_scholarship_user($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			$this->db->where('state > 7');
			$this->db->where('isscholar',1);
			$this->db->where('scholorshipid <> ""');
			$data=$this->db->get(self::T_APP)->row_array();
			if(!empty($data)){
				return $data;
			}
		}
		return array();
	}
	/**
	 * [get_scholarship_info 获取奖学金的信息]
	 * @return [type] [description]
	 */
	function get_scholarship_info($is_scholarship=array()){
		if(!empty($is_scholarship)){
			$this->db->where('id',$is_scholarship['scholorshipid']);
			return $this->db->get(self::T_SCH_INFO)->row_array();
		}
		return array();
	}
	/**
	 * [get_major_info 获取该学生所在的专业信息]
	 * @return [type] [description]
	 */
	function get_major_info($userid){
		if(!empty($userid)){
			//用户信息
			$user_info=$this->db->get(self::T_STUDENT,'userid = '.$userid)->row_array();
			//专业信息
			$this->db->where('id ',$user_info['majorid']);
			$major_info=$this->db->get(self::T_MAJOR)->row_array();
			if(!empty($major_info)){
				return $major_info;
			}
		}
		return array();
	}
	/**
	 * [get_term_tuition 获取当前学期的学费]
	 * @return [type] [description]
	 */
	function get_term_tuition($majorid,$term){
		if(!empty($majorid)&&!empty($term)){
			$this->db->where('majorid = '.$majorid.' AND term = '.$term);
			$info=$this->db->get(self::T_MAJOR_TUITION)->row_array();
			if(!empty($info)){
				return $info;
			}
		}
		return 0;
	}
	/**
	 * [is_tuition 判断该学期的学费交没交]
	 * @return boolean [description]
	 */
	function is_tuition($majorid,$term,$userid){
		if(!empty($majorid)&&!empty($term)){
			$this->db->select('count(*) as num');
			$this->db->where('nowterm = '.$term.' AND userid = '.$userid);
			$this->db->where('paystate',1);
			$info=$this->db->get(self::T_TUITION_INFO)->row_array();
			if(!empty($info['num'])){
				return $info['num'];
			}
		}
		return 0;
	}
	/**
	 * [get_typeacc_info 获取该学生的订房信息]
	 * @param  [type] $userid [description]
	 * @return [type]         [description]
	 */
	function get_typeacc_info($userid){
		if(!empty($userid)){
			$this->db->select('accommodation_info.*,school_accommodation_campus.name as cname,school_accommodation_buliding.name as bname,school_accommodation_prices.name as rname,school_accommodation_prices.prices as dayprices');
			$this->db->join(self::T_PRICES ,self::T_PRICES.'.id='.self::T_ACC.'.roomid');
			$this->db->join(self::T_BULIDING ,self::T_BULIDING.'.id='.self::T_ACC.'.buildingid');
			$this->db->join(self::T_CAMPUS ,self::T_CAMPUS.'.id='.self::T_ACC.'.campid');
			$this->db->where('accommodation_info.userid',$userid);
			$this->db->where('accommodation_info.acc_state',6);
			$udata=$this->db->get(self::T_ACC)->row_array();
			return $udata;
		}
		return array();
		
	}
	/**
	 * 查看在学的学生信息
	 */
	function get_student_info($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			return $this->db->get(self::T_STUDENT)->row_array();
		}
	}
	//查找该学生在缴费学期下有没有重修费用
	function get_rebuild($term,$userid){
		if(!empty($term)&&!empty($userid)){
			$this->db->where('term',$term);
			$this->db->where('userid',$userid);
			return $this->db->get(self::T_STUDENT_REBUILD)->result_array();
		}
	}
	//查找该学生在缴费学期下有没有换证的费用
	
	function get_barter_card($term,$userid){
		if(!empty($term)&&!empty($userid)){
			$this->db->where('term',$term);
			$this->db->where('userid',$userid);
			return $this->db->get(self::T_STU_BAR_CARD)->result_array();
		}
	}
	/**
	 * [get_major_course 获取该专业的所有课程]
	 * @return [type] [description]
	 */
	function get_major_course($mid){
		if(!empty($mid)){
			$this->db->where('majorid',$mid);
			return $this->db->get(self::T_MAJOR_COURSE)->result_array();
		}
	}
	/**
	 * [get_course_book 获取该课程的书籍]
	 * @return [type] [description]
	 */
	function get_course_book($courseid){
		if(!empty($courseid)){
			$this->db->where('courseid',$courseid);
			return $this->db->get(self::T_COURSE_BOOKS)->result_array();
		}
	}
	/**
	 * [get_book_info 获取书籍信息]
	 * @return [type] [description]
	 */
	function get_book_info($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			return $this->db->get(self::T_BOOKS)->row_array();
		}
	}
	/**
	 * [type_insert_book_fee 按类型交书费]
	 * @return [type] [description]
	 */
	function type_insert_book_fee($data){
		if(!empty($data)){
			//先插入书费表
			$book_feeid=$this->insert_book_fee($data);
			if(!empty($book_feeid)){
				//插入所有订单表
				$orderid=$this->insert_order($data,$book_feeid,8);
				if($data['isproof']==1){
					$id=$this->insert_book_credentials($data,$orderid);
				}
				//插入凭据表
				return $orderid;
			}
		}
		return 0;
	}
	/**
	 * [insert_book_fee 插入书费表]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	function insert_book_fee($data){
		if(!empty($data)){
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$data['userid'];
			$arr['book_ids']=$data['book_ids'];
			$arr['payable']=$data['book_money'];
			$arr['pay']=$data['paid_in'];
			$arr['paytime']=time();
			$arr['remark']=$data['remark'];
			$arr['createtime']=time();
			$arr['adminid']=$_SESSION['master_user_info']->id;
			if($data['isproof']==1){
				$arr['proof_number']=$data['proof_number'];
				$arr['isproof']=$data['isproof'];
				$arr['proof_path']=$data['file'];
			}
			$this->db->insert(self::T_BOOKS_FEE,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [insert_book_credentials 书费插入所有订单表]
	 * @return [type] [description]
	 */
	function insert_book_credentials($data,$book_feeid){
		if(!empty($data)&&!empty($book_feeid)){
			$arr['orderid']=$book_feeid;
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$data['userid'];
			$arr['amount']=$data['paid_in'];
			$arr['item']=8;
			$arr['currency']='rmb';
			$arr['state']=1;
			$arr['remark']=$data['remark'];
			$arr['createtime']=time();
			$arr['ordertype']=8;
			if($data['isproof']==1){
				$arr['number']=$data['proof_number'];
				$arr['file']=$data['file'];
			}
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_CRE,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [type_insert_insurance_fee 插入保险费]
	 * @return [type] [description]
	 */
	function type_insert_insurance_fee($data){
		if(!empty($data)){
			//先插入书费表
			$insurance_feeid=$this->insert_insurance_fee($data);
			if(!empty($insurance_feeid)){
				//插入所有订单表
				$orderid=$this->insert_order($data,$insurance_feeid,9);
				if($data['isproof']==1){
					$id=$this->insert_insurance_credentials($data,$orderid);
				}
				//插入凭据表
				return $orderid;
			}
		}
		return 0;
	}
	/**
	 * [insert_insurance_fee 插入保险费表]
	 * @return [type] [description]
	 */
	function insert_insurance_fee($data){
		if(!empty($data)){
			$user_info=$this->db->get(self::T_STUDENT,'userid = '.$data['userid'])->row_array();
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$data['userid'];
			$arr['studentid']=$user_info['id'];
			$arr['payable']=$data['payable'];
			$arr['premium']=$data['paid_in'];
			$arr['effect_time']=$data['effect_time'];
			$arr['majorid']=$user_info['major'];
			$arr['squadid']=$user_info['squadid'];
			$arr['paytime']=time();
			$arr['remark']=$data['remark'];
			$arr['createtime']=time();
			$arr['adminid']=$_SESSION['master_user_info']->id;
			if($data['isproof']==1){
				$arr['proof_number']=$data['proof_number'];
				$arr['isproof']=$data['isproof'];
				$arr['proof_path']=$data['file'];
			}
			$this->db->insert(self::T_INSURANCE_INFO,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * 插入保险费凭据表
	 */
	function insert_insurance_credentials($data,$orderid){
		if(!empty($data)&&!empty($orderid)){
			$arr['orderid']=$orderid;
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$data['userid'];
			$arr['amount']=$data['paid_in'];
			$arr['item']=9;
			$arr['currency']='rmb';
			$arr['state']=1;
			$arr['remark']=$data['remark'];
			$arr['createtime']=time();
			$arr['ordertype']=9;
			if($data['isproof']==1){
				$arr['number']=$data['proof_number'];
				$arr['file']=$data['file'];
			}
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_CRE,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [type_insert_acc_pledge 按类别交住宿押金]
	 * @return [type] [description]
	 */
	function type_insert_acc_pledge($data){
		if(!empty($data)){
			//先插入押金表
			$acc_pledge_feeid=$this->insert_acc_pledge_fee($data);
			if(!empty($acc_pledge_feeid)){
				//插入所有订单表
				$orderid=$this->insert_order($data,$acc_pledge_feeid,10);
				if($data['isproof']==1){
					$id=$this->insert_acc_pledge_credentials($data,$orderid);
				}
				//插入凭据表
				return $orderid;
			}
		}
	}
	/**
	 * [insert_acc_pledge_fee 插入住宿押金表]
	 * @return [type] [description]
	 */
	function insert_acc_pledge_fee($data){
		if(!empty($data)){
			$udata=$this->db->get(self::T_ACC,'userid = '.$data['userid'])->row_array();
			$max_cucasid = build_order_no ();
			$arr['userid']=$data['userid'];
			$arr['ordernumber']= $max_cucasid;
			$arr['campusid']=$udata['campid'];
			$arr['buildingid']=$udata['buildingid'];
			$arr['floor']=$udata['floor'];
			$arr['roomid']=$udata['roomid'];
			$arr['payable']=$data['payable'];
			$arr['pay']=$data['paid_in'];
			$arr['state']=1;
			$arr['paytime']=time();
			$arr['createtime']=time();
			$arr['adminid']=$_SESSION['master_user_info']->id;
			if($data['isproof']==1){
				$arr['isproof']=$data['isproof'];
				$arr['proof_number']=$data['proof_number'];
				$arr['proof_path']=$data['file'];
			}
			$this->db->insert(self::T_ACC_PLE_INFO,$arr);
			$acc_id=$this->db->insert_id();
			//更新到一直跑着的住宿订单表里的电费字段
			//获取原先的电费金钱
			$old_acc=$this->db->select('acc_pledge_money')->where('userid',$data['userid'])->get(self::T_ACC)->row_array();
			$new_acc['acc_pledge_money']=$data['paid_in']+$old_acc['acc_pledge_money'];
			$this->db->where('userid',$data['userid'])->update(self::T_ACC,$new_acc);
			return $acc_id;
		}
	}
	/**
	 * [insert_acc_pledge_credentials 插入住宿押金平局表]
	 * @return [type] [description]
	 */
	function insert_acc_pledge_credentials($data,$orderid){
		if(!empty($data)&&!empty($orderid)){
			$arr['orderid']=$orderid;
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$data['userid'];
			$arr['amount']=$data['paid_in'];
			$arr['item']=10;
			$arr['currency']='rmb';
			$arr['state']=1;
			$arr['remark']=$data['remark'];
			$arr['createtime']=time();
			$arr['ordertype']=10;
			if($data['isproof']==1){
				$arr['number']=$data['proof_number'];
				$arr['file']=$data['file'];
			}
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_CRE,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [get_user_jiaoguo 获取交没交过奖学金]
	 * @return [type] [description]
	 */
	function get_user_jiaoguo($userid){
		if(!empty($userid)){
			$this->db->select('is_scholarship');
			$this->db->where('userid',$userid);
			$this->db->where('acc_state',6);
			$data=$this->db->get(self::T_ACC)->row_array();
			if(!empty($data)){
				return $data['is_scholarship'];
			}
		}
		return 0;
	}
	/**
	 * [check_is_jiangxuejin 查看用户是否奖学金用户]
	 * @return [type] [description]
	 */
	function check_is_jiangxuejin($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			$this->db->where('state >=',7);
			return $this->db->get(self::T_APP)->row_array();
		}
	}
	/**
	 * [check_is_jiangxuejin_tg 查看奖学金是否通过]
	 * @return [type] [description]
	 */
	function check_is_jiangxuejin_tg($userid,$scholarshipid){
		if(!empty($userid)&&!empty($scholarshipid)){
			$this->db->where('userid',$userid);
			$this->db->where('scholarshipid',$scholarshipid);
			return $this->db->get(self::T_J_X_J_APP)->row_array();
		}
	}
	/**
	 * [get_jiangxuejin_info查看奖学金的信息]
	 * @return [type] [description]
	 */
	function get_jiangxuejin_info($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			return $this->db->get(self::T_SCH_INFO)->row_array();
		}
	}
	/**
	 * [get_apply_tuition_discount 获取申请的时候有没有学费折扣]
	 * @return [type] [description]
	 */
	function get_apply_tuition_discount($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			$this->db->where('is_tuition_discount',0);
			$this->db->where('state >=',7);
			return $this->db->get(self::T_APP)->row_array();
		}
	}
	/**
	 * [get_major_info_one 获取专业的一条信息]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function get_major_info_one($id){
		if(!empty($id)){
			//专业信息
			$this->db->where('id ',$id);
			$major_info=$this->db->get(self::T_MAJOR)->row_array();
			return $major_info;
		}
		return array();
	}
	/**
	 * [get_major_info_one 获取专业的一条信息]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function get_apply_info_one($userid){
		if(!empty($userid)){
			//专业信息
			$this->db->where('userid ',$userid);
			$this->db->where('state >=',7);
			$major_info=$this->db->get(self::T_APP)->row_array();
			return $major_info;
		}
		return array();
	}
	/**
	 * 插入表数组
	 */
	function table_array(){
		return array(
			'tuition'=>array(
					'tuition'=>'tuition_info',
					'detail'=>'tuition_info_detail'
				),
			'acc'=>'quarterage_info',
			'electric'=>'electric_info',
			'book'=>'books_fee',
			'insurance'=>'insurance_info',
			'acc_pledge'=>'acc_pledge_info'
			);
	}
	/**
	 * [unify_pay 收费统一接口]
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	function unify_pay ($data=array()){
		if(!empty($data)){
			$table=$this->table_array();
			//获取学生
			$user_info=$this->db->get(self::T_STUDENT,'userid = '.$data['userid'])->row_array();

			//插入学费表
			if($data['type']=='tuition'){
				$type=6;
				//先查询有没有该生该学期的记录
				$is_youmeiyou=$this->check_is_tuition($data);
				
				$max_cucasid = build_order_no ();
				$arr['nowterm']=$data['term'];
				$arr['userid']=$data['userid'];
				$arr['tuition']=$data['paid_in'];
				$arr['danwei']='rmb';
				$arr['adminid']=$_SESSION['master_user_info']->id;
				$arr['name']=$user_info['name'];
				$arr['email']=$user_info['email'];
				$arr['nationality']=$user_info['nationality'];
				$arr['mobile']=$user_info['mobile'];
				$arr['tel']=$user_info['tel'];
				$arr['passport']=$user_info['passport'];
				$arr['paystate']=1;
				$arr['paytime']=time();
				$arr['createtime']=time();
				$arr['isproof']=$data['isproof'];
				$arr['remark']=$data['remark'];
				if(!empty($is_youmeiyou)){
					$this->db->update(self::T_TUITION_INFO,$arr,'id = '.$is_youmeiyou);
					$applyid= $is_youmeiyou;
				}else{
					$arr['number']= $max_cucasid;

					$this->db->insert(self::T_TUITION_INFO,$arr);
					$applyid= $this->db->insert_id();
				}
				
				if(!empty($data['pledge_fee'])&&!empty($data['entry_fee'])&&!empty($data['rebuild_ids'])||!empty($data['barter_card_ids'])||!empty($data['abatement'])||!empty($data['apply_discount'])||!empty($data['scholarship_discount'])){
					$apply_info=$this->get_apply_info_one($data['userid']);
					//插入补缴重修费&&换证费&&续读生补缴额度
					$this->insert_tuition_detail($data,$applyid);
					if(!empty($data['apply_discount'])){
						//更新该申请已经打过折
						$apply['is_tuition_discount']=1;
						$this->db->update(self::T_APP,$apply,'id = '.$apply_info['id']);
					}
					if(!empty($data['pledge_fee'])){
						//更新该申请已经打过折
						$apply['is_deposit']=1;
						$this->db->update(self::T_APP,$apply,'id = '.$apply_info['id']);
					}
					if(!empty($data['entry_fee'])){
						//更新该申请已经打过折
						$apply['is_registration']=1;
						$this->db->update(self::T_APP,$apply,'id = '.$apply_info['id']);
					}
				}

			}
			/*****************/ //结束交学费
			//查看交电费
			if($data['type']=='electric'){
				$type=7;
				$udata=$this->db->get(self::T_ACC,'userid = '.$data['userid'])->row_array();
				$max_cucasid = build_order_no ();
				$arr['userid']=$data['userid'];
				$arr['ordernumber']= $max_cucasid;
				$arr['campusid']=$udata['campid'];
				$arr['buildingid']=$udata['buildingid'];
				$arr['floor']=$udata['floor'];
				$arr['roomid']=$udata['roomid'];
				$arr['pay']=$data['paid_in'];
				$arr['state']=1;
				$arr['paytime']=time();
				$arr['createtime']=time();
				if($data['isproof']==1){
					$arr['isproof']=$data['isproof'];
					$arr['proof_path']=$data['file'];
				}
				$arr['adminid']=$_SESSION['master_user_info']->id;
				$this->db->insert(self::T_ELECTRIC_INFO,$arr);
				$applyid=$this->db->insert_id();
				//更新到一直跑着的住宿订单表里的电费字段
				//获取原先的电费金钱
				$old_electric=$this->db->select('electric_money')->where('userid = '.$data['userid'].' AND acc_state = 6')->get(self::T_ACC)->row_array();
				$new_electric['electric_money']=$data['paid_in']+$old_electric['electric_money'];
				$this->db->where('userid',$data['userid'])->update(self::T_ACC,$new_electric);
				
				
			}
			/*****************/ //结束交电费
			//交住宿费
			if($data['type']=='acc'){
				$type=4;
				$udata=$this->db->get(self::T_ACC,'userid = '.$data['userid'])->row_array();
				$max_cucasid = build_order_no ();
				$arr['userid']=$data['userid'];
				$arr['ordernumber']= $max_cucasid;
				$arr['campusid']=$udata['campid'];
				$arr['buildingid']=$udata['buildingid'];
				$arr['floor']=$udata['floor'];
				$arr['roomid']=$udata['roomid'];
				$arr['pay']=$data['paid_in'];
				$arr['state']=1;
				$arr['paytime']=time();
				$arr['createtime']=time();
				$arr['day_fee']=$data['day'];
				$arr['adminid']=$_SESSION['master_user_info']->id;
				if($data['isproof']==1){
					$arr['proof_number']=$data['proof_number'];
					$arr['proof_path']=$data['file'];
				}
				$this->db->insert(self::T_QUA_INFO,$arr);
				$applyid=$this->db->insert_id();
				//更新到一直跑着的住宿订单表里的电费字段
				//获取原先的电费金钱
				$old_acc=$this->db->select('acc_money')->where('userid',$data['userid'])->get(self::T_ACC)->row_array();
				$new_acc['acc_money']=$data['paid_in']+$old_acc['acc_money'];
				$this->db->where('userid',$data['userid'])->update(self::T_ACC,$new_acc);
			}
			/****///结束交保险费
			if($data['type']=='insurance'){
				$type=9;
				$user_info=$this->db->get(self::T_STUDENT,'userid = '.$data['userid'])->row_array();
				$max_cucasid = build_order_no ();
				$arr['ordernumber']= $max_cucasid;
				$arr['userid']=$data['userid'];
				$arr['studentid']=$user_info['id'];
				$arr['payable']=$data['payable'];
				$arr['premium']=$data['paid_in'];
				$arr['effect_time']=$data['effect_time'];
				$arr['majorid']=$user_info['major'];
				$arr['squadid']=$user_info['squadid'];
				$arr['paytime']=time();
				$arr['remark']=$data['remark'];
				$arr['createtime']=time();
				$arr['adminid']=$_SESSION['master_user_info']->id;
				if($data['isproof']==1){
					$arr['proof_number']=$data['proof_number'];
					$arr['isproof']=$data['isproof'];
					$arr['proof_path']=$data['file'];
				}
				$this->db->insert(self::T_INSURANCE_INFO,$arr);
				$applyid= $this->db->insert_id();
			}
			/****///结束交保险费
			if($data['type']=='book'){
				$type=8;
				$max_cucasid = build_order_no ();
				$arr['ordernumber']= $max_cucasid;
				$arr['userid']=$data['userid'];
				$arr['book_ids']=$data['book_ids'];
				$arr['payable']=$data['book_money'];
				$arr['pay']=$data['paid_in'];
				$arr['paytime']=time();
				$arr['remark']=$data['remark'];
				$arr['createtime']=time();
				$arr['adminid']=$_SESSION['master_user_info']->id;
				if($data['isproof']==1){
					$arr['proof_number']=$data['proof_number'];
					$arr['isproof']=$data['isproof'];
					$arr['proof_path']=$data['file'];
				}
				$this->db->insert(self::T_BOOKS_FEE,$arr);
				$applyid= $this->db->insert_id();
			}
			//返回applyid插入所有订单表里
			$orderid=$this->insert_order($data,$applyid,$type);
			//如果是凭据用户  则插入凭据表
			if($data['isproof']==1){
				$creid=$this->insert_credentials($data,$orderid,$type);
			}
			
		}
	}

	/**
	 * [insert_credentials 插入凭据表]
	 * @return [type] [description]
	 */
	function insert_credentials($data,$tuitionid,$type){
		if(!empty($data)&&!empty($tuitionid)){
			$arr['orderid']=$tuitionid;
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$data['userid'];
			$arr['amount']=$data['paid_in'];
			$arr['item']=$type;
			$arr['currency']='rmb';
			$arr['way']=3;
			$arr['state']=1;
			$arr['remark']=$data['remark'];
			$arr['createtime']=time();
			$arr['ordertype']=$type;
			if($data['isproof']==1){
				$arr['number']=$data['proof_number'];
				$arr['file']=$data['file'];
			}
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_CRE,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [get_deposit_info 查看申请的时候交没交押金]
	 * @return [type] [description]
	 */
	function get_deposit_info($aid,$userid){
		if(!empty($aid)&&!empty($userid)){
			$this->db->where('applyid',$aid);
			$this->db->where('userid',$userid);
			return $this->db->get(self::T_DEPOSIT_INFO)->row_array();
		}
	}
}	