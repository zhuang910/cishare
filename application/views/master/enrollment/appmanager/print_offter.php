<?php $this->load->view(MASTER.'public/js_fileupload');?>
<?php $this->load->view(MASTER.'public/js_css_datepicker');?>
<script src="/public/js/eakroko.js"></script>
<div id="pub_edit_bootbox" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">打印通知书</h3>
        <div class="modal-body">
			<form id="pub_form" class="form-horizontal" method="POST" action="<?=$zjjp?>/save_print_offter">
			<div class="control-group">
					<label class="control-label">通知书类别：</label>
					<div class="controls">
						<input type="radio" name="type" value="1" >北京外国语大学汉语短训班邀请书<br />
						<input type="radio" name="type" value="2" >北京外国语大学录取通知书
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">姓名：</label>
					<div class="controls">
						<input type="text" name="name" value="<?=!empty($result->lastname)?$result->lastname:''?> &nbsp;<?=!empty($result->firstname)?$result->firstname:''?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">性别：</label>
					<div class="controls">
						<input type="radio" name="sex" value="1" <?=!empty($result->sex) && $result->sex == 1?'checked':''?>>男
						<input type="radio" name="sex" value="2" <?=!empty($result->sex) && $result->sex == 2?'checked':''?>>女
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">国籍：</label>
					<div class="controls">
						<select name="nationality">
							<?php foreach ($nationality as $key => $value) {?>
								<option value="<?=$key?>" <?=!empty($result->nationality) && $result->nationality == $key?'selected':''?>><?=$value?></option>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">护照号：</label>
					<div class="controls">
						<input type="text" name="passport" value="<?=!empty($result->passport)?$result->passport:''?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">来华学习经费来源：</label>
					<div class="controls">
						<input type="text" name="money" value="<?=!empty($result->soursemoney)?$result->soursemoney:''?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">学号：</label>
					<div class="controls">
						<input type="text" name="idnum" value="<?=!empty($result->idnum)?$result->idnum:''?>">
					</div>
				</div>
				<div class="control-group">
					<label for="createtime" class="control-label">学习开始时间：</label>
					<div class="controls">
						<input type="text" name="opening" id="opening" value="<?=!empty($result) ? date('Y-m-d',$result->opening) : date('Y-m-d')?>" class="input-xlarge datepick">
					</div>
				</div>
				<?php 
					//计算学习截至时间
					switch ($result->program_unit) {
						case 1:
							$xxendtime = $result->opening + $result->program_length * 7 * 3600 *24; 
							break;
						case 2:
							$xxendtime = $result->opening + $result->program_length * 30 * 3600 *24; 
							break;
						case 3:
							$xxendtime = $result->opening + $result->program_length * 120 * 3600 *24; 
							break;
						case 4:
							$xxendtime = $result->opening + $result->program_length * 240 * 3600 *24; 
							break;
					}
				?>
				
				<div class="control-group">
					<label for="createtime" class="control-label">学习截止时间：</label>
					<div class="controls">
						<input type="text" name="xxendtime" id="xxendtime" value="<?=!empty($xxendtime) ? date('Y-m-d',$xxendtime) : date('Y-m-d ')?>" class="input-xlarge datepick">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">学生类型：</label>
					<div class="controls">
						<input type="radio" name="stype" value="1" >长期进修生
						<input type="radio" name="stype" value="2" >短期进修生
					</div>
				</div>
				
				<?php 
					$a = array(
							'22' => '长期进修',
							'23' => '短期进修',
							'24' => '寒暑假进修',
							'25' => '夏令营/冬令营',
							'26' => '商务汉语',
							'27' => '汉语翻译',
							'28' => 'HSK系列课程',
							'29' => '团体项目',

						);
					$language = array(
							'1' => 'Chinese',
							'2' => 'English'
						);

				?>
				<div class="control-group">
					<label class="control-label">专业：</label>
					<div class="controls">
						<select name="columnid">
							<?php foreach ($a as $aa => $aaa) {?>
								<option value="<?=$aa?>" <?=!empty($result->columnid) && $result->columnid == $aa?'selected':''?>><?=$aaa?></option>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">授课语言：</label>
					<div class="controls">
						<select name="language">
							<?php foreach ($language as $l => $ll) {?>
								<option value="<?=$l?>" <?=!empty($result->language) && $result->language == $l?'selected':''?>><?=$ll?></option>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">学费：</label>
					<div class="controls">
						<input type="text" name="tuition" value="<?=!empty($result->tuition)?$result->tuition:''?>">元人民币/学期<br />
						<input type="text" name="tuition1" value="">元人民币/学年
					</div>
				</div>
				
				<div class="control-group">
					<label for="createtime" class="control-label">报名开始时间：</label>
					<div class="controls">
						<input type="text" name="otime" id="" value="<?=date('Y-m-d')?>" class="input-xlarge datepick">
					</div>
				</div>
				<div class="control-group">
					<label for="createtime" class="control-label">报名结束时间：</label>
					<div class="controls">
						<input type="text" name="etime" id="" value="<?= date('Y-m-d')?>" class="input-xlarge datepick">
					</div>
				</div>
				<div class="control-group">
					<label for="createtime" class="control-label">落款时间：</label>
					<div class="controls">
						<input type="text" name="ltime" id="" value="<?= date('Y-m-d')?>" class="input-xlarge datepick">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">二维码：</label>
					<div class="controls">
						<input type="hidden" name="qrcode" value="<?=!empty($result->qrcode)?$result->qrcode:''?>">
						<img src="<?=!empty($result->qrcode)?$result->qrcode:''?>" width="" height="">
					</div>
				</div>
				<div class="form-actions">
					
					<input type="submit" value="确定" class="btn btn-primary">
					<button class="btn" type="button" data-dismiss="modal">取消</button>
				</div>
			</form>
		</div>
    </div>

</div>
