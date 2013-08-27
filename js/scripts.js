$(document).ready(function(){

/**
	$("ul.menu li ul.inside li").click(function() {
		$(this).parents('ul').attr('class','');
		$(this).children('a:first').attr('class','currentpage');
	})
	
	var pageName = location.pathname.substring(location.pathname.lastIndexOf("/") + 1);
	$("#"+pageName).children('a:first').attr('class','currentpage');
	**/
	var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
    
    if(dd<10){
    	dd='0'+dd;
    } 
    
    if(mm<10){
    	mm='0'+mm;
    } 
    var today = yyyy+'-'+mm+'-'+dd;
    
    $('#datepicker').val(today);
	$('#datepicker').datepicker({
    	format: 'yyyy-mm-dd'
	});
	
	$("#datepicker2").val(today);
	$('#datepicker2').datepicker({
    	format: 'yyyy-mm-dd'
	});
	
	$('#datepickerStart').datepicker({
    	format: 'yyyy-mm-dd'
	});
	
	$('#datepickerEnd').val(today);
	$('#datepickerEnd').datepicker({
    	format: 'yyyy-mm-dd'
	});

	
	$(".numbers").keyup(function(){
		var value = $(this).val();
		if (value.length > 0){		
			if (value.match(/^0.*$/)){
				$(".numbersWarning").html("<span class=\"badge badge-important warning\">Cannot begin with 0</span>");
				$(".numbers").parents('td').attr('class','control-group warning');
			} else if (!value.match(/^[1-9]{1}[0-9]*$/)){
				$(".numbersWarning").html("<span class=\"badge badge-important warning\">Only numbers allowed</span>");
				$(".numbers").parents('td').attr('class','control-group warning');
			} else {
				$(".numbersWarning").html("");
				$(".numbers").parents('td').attr('class','');
			}
		} else {
			$(".numbersWarning").html("");
				$(".numbers").parents('td').attr('class','');

		}
	});
	
	
	$(".textNumbers").keyup(function(){
		var value = $(this).val();
		if (value.trim().length > 0){		
			if (!value.trim().match(/^[A-Za-z0-9\s\-\_]+$/)){
				$(".textNumbersWarning").html("<span class=\"badge badge-important warning\">Only letters, numbers, -, _, and spaces allowed</span>");
				$(".textNumbers").parents('td').attr('class','control-group warning');
			} else {
				$(".textNumbersWarning").html("");
				$(".textNumbers").parents('td').attr('class','');
			}
		} else {
			$(".textNumbersWarning").html("");
				$(".textNumbers").parents('td').attr('class','');

		}
	});
	
	$(".textNumbers2").keyup(function(){
		var value = $(this).val();
		if (value.trim().length > 0){		
			if (!value.trim().match(/^[A-Za-z0-9\s\-\_]+$/)){
				$(".textNumbersWarning2").html("<span class=\"badge badge-important warning\">Only letters, numbers, -, _, and spaces allowed</span>");
				$(".textNumbers2").parents('td').attr('class','control-group warning');
			} else {
				$(".textNumbersWarning2").html("");
				$(".textNumbers2").parents('td').attr('class','');
			}
		} else {
			$(".textNumbersWarning2").html("");
			$(".textNumbers2").parents('td').attr('class','');

		}
	});
		$(".textNumbers3").keyup(function(){
		var value = $(this).val();
		if (value.trim().length > 0){		
			if (!value.trim().match(/^[A-Za-z0-9\s\-\_]+$/)){
				$(".textNumbersWarning3").html("<span class=\"badge badge-important warning\">Only letters, numbers, -, _, and spaces allowed</span>");
				$(".textNumbers3").parents('td').attr('class','control-group warning');
			} else {
				$(".textNumbersWarning3").html("");
				$(".textNumbers3").parents('td').attr('class','');
			}
		} else {
			$(".textNumbersWarning3").html("");
			$(".textNumbers3").parents('td').attr('class','');

		}
	});
		$(".textNumbers4").keyup(function(){
		var value = $(this).val();
		if (value.trim().length > 0){		
			if (!value.trim().match(/^[A-Za-z0-9\s\-\_]+$/)){
				$(".textNumbersWarning4").html("<span class=\"badge badge-important warning\">Only letters, numbers, -, _, and spaces allowed.</span>");
				$(".textNumbers4").parents('td').attr('class','control-group warning');
			} else {
				$(".textNumbersWarning4").html(" Seperate tags by comma e.g. tag1,tag2");
				$(".textNumbers4").parents('td').attr('class','');
			}
		} else {
			$(".textNumbersWarning4").html("");
			$(".textNumbers4").parents('td').attr('class','');

		}
	});
		$(".blogPhoto").keyup(function(){
		var value = $(this).val();
		if (value.trim().length > 0){		
			if (!value.trim().match(/^[A-Za-z0-9\s\-\_]+$/)){
				$(".textNumbersWarning4").html("<span class=\"badge badge-important warning\">Only letters, numbers, -, _, and spaces allowed.</span>");
				$(".textNumbers4").parents('td').attr('class','control-group warning');
			} else {
				$(".textNumbersWarning4").html(" Seperate tags by comma e.g. tag1,tag2");
				$(".textNumbers4").parents('td').attr('class','');
			}
		} else {
			$(".textNumbersWarning4").html("");
			$(".textNumbers4").parents('td').attr('class','');

		}
	});
	
	
	$(".username").keyup(function(){
		var value = $(this).val();
		if (value.length > 0){ //TODO: split warnings. check duplicate usernames		
			if (!value.match(/^[A-Za-z]{1}[A-Za-z0-9\-\_]{5,14}$/)){ 
				$(".usernameWarning").html("<span class=\"badge badge-important warning\">Must start with a letter. Only letters, numbers, -, and _ are allowed. Between 6-15 characters.</span>");
				$(".username").parents('td').attr('class','control-group warning');
			} else {
				$(".usernameWarning").html("");
				$(".username").parents('td').attr('class','');
			}
		} else {
			$(".usernameWarning").html("");
			$(".username").parents('td').attr('class','');
		}
	});	
	
	
	$(".password").keyup(function(){ //TODO: check simplicity
		var value = $(this).val();
		if (value.length > 0){		
			if (value.length < 6){
				$(".passwordWarning").html("<span class=\"badge badge-important warning\">Password is too short</span>");
				$(".password").parents('td').attr('class','control-group warning');
			} else if (value.length > 16){
				$(".passwordWarning").html("<span class=\"badge badge-important warning\">Password is too long</span>");
				$(".password").parents('td').attr('class','control-group warning');
			} else {
				$(".passwordWarning").html("");
				$(".password").parents('td').attr('class','');
			}
		} else {
			$(".passwordWarning").html("");
			$(".password").parents('td').attr('class','');
		}
	});	
	
	$(".password2").keyup(function(){ //TODO: check simplicity
		var value = $(this).val();
		if (value.length > 0){		
			if (value.length < 6){
				$(".passwordWarning2").html("<span class=\"badge badge-important warning\">Password is too short</span>");
				$(".password2").parents('td').attr('class','control-group warning');
			} else if (value.length > 16){
				$(".passwordWarning2").html("<span class=\"badge badge-important warning\">Password is too long</span>");
				$(".password2").parents('td').attr('class','control-group warning');
			} else {
				$(".passwordWarning2").html("");
				$(".password2").parents('td').attr('class','');
			}
		} else {
			$(".passwordWarning2").html("");
			$(".password2").parents('td').attr('class','');
		}
	});	
	
	$(".emailAddress").keyup(function(){
		var value = $(this).val();
		if (value.length > 0){		
			if (!value.trim().match(/^[A-Za-z0-9\-\_]+@[A-Za-z0-9\-\_]+(\.[A-Za-z0-9\-\_]+)+$/)){
				$(".emailAddressWarning").html("<span class=\"badge badge-important warning\">Please enter a valid email address</span>");
				$(".emailAddress").parents('td').attr('class','control-group warning');
			} else {
				$(".emailAddressWarning").html("");
				$(".emailAddress").parents('td').attr('class','');
			}
		} else {
			$(".emailAddressWarning").html("");
			$(".emailAddress").parents('td').attr('class','');
		}
	});
	
	$(".filterPhoto").change(function(){
		var parts = window.location.search.substr(1).split("&");
		var $_GET = {};
		for (var i = 0; i < parts.length; i++) {
		    var temp = parts[i].split("=");
		    $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
		}
		
		if(!$_GET.rid) {
			$_GET.rid = -1;
		}
		
		$.ajax({
			url : "GalleryFilterAjax.php?country=" + $("#filterCountry").val() + "&chapter=" + $("#filterChapter").val() + "&activity=" + $("#filterActivity").val() + "&start=" + $("#datepickerStart").val() + "&end=" + $("#datepickerEnd").val() + "&rid=" + $_GET.rid,
			success : function(response){
				$("#searchResults").html(response);
			}
		});
	});
	
	$(".filterDate").datepicker().on('changeDate', function(){
		var parts = window.location.search.substr(1).split("&");
		var $_GET = {};
		for (var i = 0; i < parts.length; i++) {
		    var temp = parts[i].split("=");
		    $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
		}
		
		if(!$_GET.rid) {
			$_GET.rid = -1;
		}
		
		$.ajax({
			url : "GalleryFilterAjax.php?country=" + $("#filterCountry").val() + "&chapter=" + $("#filterChapter").val() + "&activity=" + $("#filterActivity").val() + "&start=" + $("#datepickerStart").val() + "&end=" + $("#datepickerEnd").val() + "&rid=" + $_GET.rid,
			success : function(response){
				$("#searchResults").html(response);
			}
		});
	});
	
	$(".edit input[name='delete']").click(function(){
		return confirm("Are you sure?");
	});
	
	$("#gallery_search_id").keyup(function(){
		$.ajax({
			url : "GallerySearchAjax.php?searchValue=" + $(this).val(),
			success : function(response){
				$("#searchResults").html(response);
			}
		});
	});
});