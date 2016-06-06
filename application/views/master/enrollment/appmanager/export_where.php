
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">

					
wdjhgaskj
</div>
								<!--timestart-->
			<div class="datepicker datepicker-dropdown dropdown-menu datepicker-orient-left datepicker-orient-bottom" style="display: none; top: 1966.47px; left: 222.917px;">
				<div class="datepicker-days" style="display: block;">
				<table class=" table-condensed">
				<thead>
				<tr>
				<th class="prev" style="visibility: visible;">«</th>
				<th class="datepicker-switch" colspan="5">August 2014</th>
				<th class="next" style="visibility: visible;">»</th>
				</tr>
				<tr>
				<th class="dow">Su</th>
				<th class="dow">Mo</th>
				<th class="dow">Tu</th>
				<th class="dow">We</th>
				<th class="dow">Th</th>
				<th class="dow">Fr</th>
				<th class="dow">Sa</th>
				</tr>
				</thead>
				<tbody>
				<tr>
				<td class="old day">27</td>
				<td class="old day">28</td>
				<td class="old day">29</td>
				<td class="old day">30</td>
				<td class="old day">31</td>
				<td class="day">1</td>
				<td class="day">2</td>
				</tr>
				<tr>
				<td class="day">3</td>
				<td class="today day">4</td>
				<td class="day">5</td>
				<td class="day">6</td>
				<td class="day">7</td>
				<td class="day">8</td>
				<td class="day">9</td>
				</tr>
				<tr>
				<td class="day">10</td>
				<td class="day">11</td>
				<td class="day">12</td>
				<td class="day">13</td>
				<td class="day">14</td>
				<td class="day">15</td>
				<td class="day">16</td>
				</tr>
				<tr>
				<td class="day">17</td>
				<td class="day">18</td>
				<td class="day">19</td>
				<td class="day">20</td>
				<td class="day">21</td>
				<td class="day">22</td>
				<td class="day">23</td>
				</tr>
				<tr>
				<td class="day">24</td>
				<td class="day">25</td>
				<td class="day">26</td>
				<td class="day">27</td>
				<td class="day">28</td>
				<td class="active day">29</td>
				<td class="day">30</td>
				</tr>
				<tr>
				<td class="day">31</td>
				<td class="new day">1</td>
				<td class="new day">2</td>
				<td class="new day">3</td>
				<td class="new day">4</td>
				<td class="new day">5</td>
				<td class="new day">6</td>
				</tr>
				</tbody>
				<tfoot>
				<tr>
				<th class="today" colspan="7" style="display: none;">Today</th>
				</tr>
				<tr>
				<th class="clear" colspan="7" style="display: none;">Clear</th>
				</tr>
				</tfoot>
				</table>
				</div>
			</div>	
			<!--timeend-->	
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>	


	<!--日期插件-->
<script type="text/javascript">
	//datepicker plugin
	//link
	$(function(){
		$('.date-picker').datepicker({
		autoclose: true,
		todayHighlight: true
		})
		//show datepicker when clicking on the icon
		.next().on(ace.click_event, function(){
			$(this).prev().focus();
		});

		});
	
</script>