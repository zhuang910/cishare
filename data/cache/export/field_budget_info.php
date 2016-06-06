<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * Created by CUCAS TEAM.
 * User: JunJie
 * E-Mail:zhangjunjie@cucas.cn
 * Date: 15-1-14
 * Time: 下午2:30
 */

return array(

	array('field'=>'insurance_info.id','name'=>'序号'),
	array('field'=>'student_info.enname','name'=>'姓名（护照名）'),
	array('field'=>'student_info.passport','name'=>'护照号码'),
	array('field'=>'student_info.sex','name'=>'性别'),
	array('field'=>'student_info.birthday','name'=>'出生日期'),
	array('field'=>'student_info.nationality','name'=>'国籍'),
	array('field'=>'insurance_info.paid_in','name'=>'保险费'),
	array('field'=>'insurance_info.deadline','name'=>'保险期限'),
	array('field'=>'insurance_info.effect_time','name'=>'保险生效日期'),
	// array('field'=>'budget.payable','name'=>'应收费用')
	);