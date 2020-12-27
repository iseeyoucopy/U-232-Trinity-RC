$(document).ready(function(){
	
	var open=Array();
	
	$("#jd-chat .jd-online_user").click(function(){
		var user_name = $.trim($(this).text());
		var id = $.trim($(this).attr("id"));
		
		if($.inArray(id,open) !== -1 )
			return
		
		open.push(id);
	
		$("#jd-chat").prepend('<div class="jd-user">\
			<div class="jd-header" id="' + id + '">' + user_name + '<span class="close-this"> X </span></div>\
			<div class="jd-body"></div>\
			<div class="jd-footer"><input placeholder="Write A Message"></div>\
		</div>');
		$.ajax({
			url:'chat.class.php',
			type:'POST',
			data:'get_all_msg=true&user=' + id ,
			success:function(data){
				$("#jd-chat").find(".jd-user:first .jd-body").append("<span class='me'> " + data + "</span>");
			}
		});
	});
	
	$("#jd-chat").delegate(".close-this","click",function(){
		removeItem = $(this).parents(".jd-header").attr("id");
		$(this).parents(".jd-user").remove();
		
		open = $.grep(open, function(value) {
		  return value != removeItem;
		});	
	});
		
	$("#jd-chat").delegate(".jd-header","click",function(){
		var box=$(this).parents(".jd-user,.jd-online");
		$(box).find(".jd-body,.jd-footer").slideToggle();
	});
	
	$("#search_chat").keyup(function(){
		var val =  $.trim($(this).val());
		$(".jd-online .jd-body").find("span").each(function(){
			if ($(this).text().search(new RegExp(val, "i")) < 0 ) 
			{
                $(this).fadeOut(); 
            } 
			else 
			{
                $(this).show();              
            }
		});
	});
	
	$("#jd-chat").delegate(".jd-user input","keyup",function(e){
		if(e.keyCode == 13 )
		{
			var box=$(this).parents(".jd-user");
			var msg=$(box).find("input").val();
			var to = $.trim($(box).find(".jd-header").attr("id"));
			$.ajax({
				url:'chat.class.php',
				type:'POST',
				data:'send=true&to=' + to + '&msg=' + msg,
				success:function(data){					
					$(box).find(".jd-body").append("<span class='me'> " + msg + "</span>");
				}
			});
		}
	});
	
	function message_cycle()
	{	
		$.ajax({
			url:'chat.class.php',
			type:'POST',
			data:'unread=true',
			dataType:'JSON',
			success:function(data){				
				$.each(data , function( index, obj ) {
					var user = index;					
					var box  = $("#jd-chat").find("div#2").parents(".jd-user");
					
					$(".jd-online").find(".light").hide();
					
					$.each(obj, function( key, value ) {
						if($.inArray(user,open) !== -1 )											
							$(box).find(".jd-body").append("<span class='other'> " + value + "</span>");						
						else						
							$(".jd-online").find("span#" + user + " .light").show();						
					});
				});				
			}
		});
	}
	
	setInterval(message_cycle,1000);
});  