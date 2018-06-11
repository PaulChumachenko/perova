<div id="search" class="input-group">
	<span class="input-group-btn categories">
	<button type="button" class="btn" onclick="window.location.href='/autoparts'">Поиск по авто</button>
	
						
					</span>
					<input type="text" id="artnum" name="artnum" name="search" value="" placeholder="Поиск по номеру" class="form-control input-lg">

					<span class="input-group-btn">

					<button type="button" class="btn" value="Поиск" id="search-button" class="tinpbut" onclick="tdm_search_bubmit()"><i class="fa fa-search"></i></button>
					</span>
</div>
<script type="text/javascript">
$('#search a').click(function(){
	$("#selected_category").val($(this).attr('id'));
	$('#change_category').html('<span class="category-name">' + $(this).html() + '&nbsp;</span>&nbsp;<span class="fa fa fa-angle-down caretalt"></span>');
});
</script>

<script type="text/javascript">
function tdm_search_bubmit(){
	var str='';
	str = $('#artnum').val();
	str = str.replace(/[^a-zA-Z0-9.-]+/g, '');
	url = '/autoparts/search/'+str+'/';
	location = url;
}
$('#artnum').keypress(function (e){
  if (e.which == 13) {
    tdm_search_bubmit();
    return false;
  }
});
</script>