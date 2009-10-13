//manage search box text
$(document).ready(function(){	

	//search term input toggle
	$("#search_term").focus(function(){
		if($(this).attr("value") == "Search for business or service here...") $(this).attr("value", "");
	});
	$("#search_term").blur(function(){
		if($(this).attr("value") == "") $(this).attr("value", "Search for business or service here...");
	});
	
	//search location input toggle
	$("#search_location").focus(function(){
		if($(this).attr("value") == "City, State or Zip") $(this).attr("value", "");
	});
	$("#search_location").blur(function(){
		if($(this).attr("value") == "") $(this).attr("value", "City, State or Zip");
	});
	
	
});