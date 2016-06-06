<?php $this->load->view('student/headermy.php')?>
<?php
  $uri4 = $this->uri->segment(5);
?>

<div class="width1024">
  <div class="wap_box p30">
  
    <h1 class="clearfix"><span class="fl">
	<?php 
		if($puri == 'en'){
			if(!empty($c->enname)){
				echo $c->enname;
			}
			
			if(!empty($b->enname)){
				
				echo '->'.$b->enname;
			}
			
			if(!empty($c_b->enname)){
				
				echo '->'.$c_b->enname;
			}
		}else{
				if(!empty($c->name)){
					echo $c->name;
				}
				
				if(!empty($b->name)){
					
					echo '->'.$b->name;
				}
				
				if(!empty($c_b->name)){
					
					echo '->'.$c_b->name;
				}
			
		}
	
	?>
	</span>
    </h1>
    <div class="tab pt30">
      <?=!empty($data->info)?nl2br($data->info):''?>
    </div>
  </div>
</div>

<?php $this->load->view('student/footer.php')?>