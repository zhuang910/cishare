<dl class="news_font">
          <dt><?=!empty($notice[0]['createtime'])?date('m',$notice[0]['createtime']).'-'.date('d',$notice[0]['createtime']):''?></dt>
          <dd>
            <h2><a href="<?php 
				if(!empty($notice[0]['isjump']) && $notice[0]['isjump'] == 1){
					echo $notice[0]['jumpurl'];
				}else{
					echo '/index/noticedetail?id='.$notice[0]['id'];
				}
			
			?>"><?=!empty($notice[0]['title'])?$notice[0]['title']:''?></a></h2>
          </dd>
          <dd class="font_gray"><?=!empty($notice[0]['desperation'])?$notice[0]['desperation']:''?></dd>
        </dl>
        <div class="qh clearfix">
          <div class="qh_l" onclick="prev(<?=$page?>)"></div>
          <div class="qh_r" onclick="next(<?=$page?>)"></div>
        </div>