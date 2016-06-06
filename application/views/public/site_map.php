<div class="bottom-link">
      <ul>
		<?php 
			if(!empty($head_nav)){
				foreach($head_nav as $lk => $lv){
		?>
			<?php if($lv['identify'] != '/'){?>
				<?php if($lv['programaid'] == 2){?>
				<li>
				  <dl>
					<dt><a href="/<?=$puri?>/course?programaid=0"><?=lang('nav_'.$lv['programaid'])?></a></dt>
					<?php 
						$p_z = $this->db->select ( 'degree' )->group_by ( 'degree' )->get_where ( 'major', 'id > 0' )->result_array ();
						
							if (! empty ( $p_z )) {
								foreach ( $p_z as $p_k => $p_v ) {
									echo '<dd><a href="/'.$puri.'/course/feature?programaid='.$p_v['degree'].'">'.lang('degree_'.$p_v['degree']).'</a></dd>';
								}
							}
							
							
					?>
				  </dl>
				</li>
			
			<?php }else if($lv['programaid'] == 67){?>
				
					<li>
					  <dl>
						<dt><a href="javascript:;" class="bg-sanjiao"><?=lang('nav_67')?></a></dt>
						<dd><a href="/<?=$puri?>/accommodation_introduce"><?=lang('nav_92')?></a></dd>
						<dd><a href="/<?=$puri?>/accommodation_book"><?=lang('nav_94')?> </a></dd>
					  </dl>
					</li>
			<?php }else if($lv['programaid'] == 21){?>
					<li>
					  <dl>
						<dt><a href="/<?=$puri?>/teacher" class="bg-sanjiao"><?=lang('nav_21')?></a></dt>
						<dd><a href="/<?=$puri?>/teacher"><?=lang('nav_103')?></a></dd>
						
					  </dl>
					</li>
			
			<?php }else{?>
				<?php if($lv['isshow'] == 1 && !empty($lv['childs'])){?>
				<li>
					<dl>
					<dt><a href="<?=!empty($lv['identify'])?'/'.$puri.'/'.$lv['identify'].'?programaid='.$lv['programaid']:''?>" class="bg-sanjiao"><?=lang('nav_'.$lv['programaid'])?></a></dt>
						<?php foreach($lv['childs'] as $ck => $cv){
								if(!$cv['isshow']){
									continue;
								}
						?>
						
						  
						  <dd><a href="<?=!empty($cv['identify'])?'/'.$puri.'/'.$cv['identify'].'?programaid='.$cv['programaid']:''?>"><?=$cv['isshow'] == 1 ?lang('nav_'.$cv['programaid']):''?></a></dd>
						
						<?php }?>
					</dl>
				</li>
				
				<?php }else if($lv['isshow'] == 1){?>
					<?php if($lv['programaid'] == 69){?>
					 <li><dl><dt><a href="<?=!empty($lv['identify'])?'/'.$puri.'/'.$lv['identify']:''?>"><?=lang('nav_'.$lv['programaid'])?></a></dt>
					 <dd><a href="/<?=$puri?>/cost"><?=lang('nav_69')?></a></dd>
					 </dl>
				</li>
					<?php }else{?>
					 <li><dl><dt><a href="<?=!empty($lv['identify'])?'/'.$puri.'/'.$lv['identify']:''?>"><?=lang('nav_'.$lv['programaid'])?></a></dt></dl>
				</li>
					<?php }?>
				 
				<?php }?>
			
			<?php }?>
		
			<?php }?>
		
		<?php }}?>
        
       
      </ul>
    </div>