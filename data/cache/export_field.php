<?php
defined('BASEPATH') or exit ('No direct script access allowed');
/**
 * Created by CUCAS TEAM.
 * User: Junjie Zhang
 * E-Mail: zhangjunjie@cucas.cn
 * Date: 15/1/8
 * Time: 下午4:02
 */

return array(
	'insurance_info'=>array('table'=>'insurance_info','name'=>'保险费','fix'=>''),
	'course_elective'=>array('table'=>'course_elective','name'=>'选课名单','fix'=>''),
	'books_fee'=>array('table'=>'books_fee','name'=>'教材导出清单','fix'=>''),
	'student'=>array('table'=>'student','name'=>'成绩统计总表-简单','fix'=>'score'),
	'checking'=>array('table'=>'checking','name'=>'考勤汇总-按月','fix'=>'check'),
	'squad'=>array('table'=>'squad','name'=>'学生评教汇总表-班级','fix'=>'squ'),
	'evaluat_student'=>array('table'=>'evaluat_student','name'=>'学生评教汇总表-班级','fix'=>'eva'),
	'score'=>array('table'=>'score','name'=>'语言生成绩统计详细','fix'=>'sco'),
	'teacher_score'=>array('table'=>'teacher_score','name'=>'成绩统计教师输入','fix'=>'tea'),
	'budget'=>array('table'=>'budget','name'=>'学生收费信息登记表','fix'=>'bug'),
	'credentials'=>array('table'=>'credentials','name'=>'汇款明细','fix'=>'cre'),
	'accommodation_info'=>array('table'=>'accommodation_info','name'=>'住宿费退费清单','fix'=>'acc'),
	'room_electric_record'=>array('table'=>'room_electric_record','name'=>'电费核算','fix'=>''),
	'student_budget'=>array('table'=>'student_budget','name'=>'按学生导出收费信息','fix'=>'bug'),
	'student_refresh'=>array('table'=>'student_refresh','name'=>'按学生刷卡记录导出收费信息','fix'=>'bug1'),
	'fees_export'=>array('table'=>'fees_export','name'=>'费用收取表格','fix'=>'bug1'),
	'student_online'=>array('table'=>'student_online','name'=>'在学导出','fix'=>'bug1'),
);