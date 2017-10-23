$(document).ready(function() {
	$("#goin").fancybox({padding:25,margin:0});
	$(".img-fancy").fancybox();

    $('.fancy').fancybox({
        padding: 0,
        helpers: {
            overlay: {
                locked: false
            }
        }
    });
	
	
	$('#show-per-page').click(function(){
		location.replace('?per_page='+this.value);
	})
  /*treeview--------------------------------------------------------------------------------------*/
	/*$("#treeview").treeview({
	  animated: 'fast',
		collapsed: true,
    unique: true,
    //persist: 'location',
		persist: 'cookie'
	});*/
  /*end treeview----------------------------------------------------------------------------------*/

  /*jCarousel-------------------------------------------------------------------------------------*/

  /* TODO legacy

  $('.jcarousel-skin-tango').jcarousel({
    scroll: 1,
    animation: 600,
    //buttonNextHTML: '<div>еще...</div>',
    //buttonPrevHTML: '<div></div>',
    wrap: 'last'
    //wrap: 'circular',
    //auto: 1
  });
  */

  /*end jCarousel---------------------------------------------------------------------------------*/
/*
 $('.hitarea').each(function(){
	$(this).bind('click', function(){
	

		
		
		if($(this).hasClass('opr')){
		
			$(this).parent().find('ul').toggle();
			$(this).removeClass('opr');
			
		}
		else{
		$('.opr').removeClass('opr');
			$(this).addClass('opr');
				$('.expandable ul').css('display', 'none');
				$(this).parent().find('ul').toggle();
		}
		
	
	});
 
 });
  
*/
    
	
  /*$("#news-message").fancybox();
  $("#news-message").trigger("click");*/
 

    /*function setCookie(name, value) {
		document.cookie = name + "=" + value;
	}
	function getCookie(name) {
		var r = document.cookie.match("(^|;) ?" + name + "=([^;]*)(;|$)");
		if (r) 
			return r[2];
		else 
			return "";
	}
	function deleteCookie(name) {
		var date = new Date();
		date.setTime(date.getTime() - 1);
		document.cookie = name += "=; expires=" + date.toGMTString();
	}
	
  	if(!getCookie('info_view') && !getCookie('visit_time')){
  		$("#news-message").fancybox();
  		$("#news-message").trigger("click");
  	}
  	
	if (!getCookie("visit_time")){
		setCookie("visit_time", $.now()/1000);
	}
	
	var now = $.now()/1000;
	
	if(now - getCookie('visit_time') > 60){
		deleteCookie('info_view');
		deleteCookie('visit_time');
	}

	if (!getCookie("info_view")){
		if (now - getCookie('visit_time') > 60){
			$("#news-message").fancybox();
	        
  			$("#news-message").trigger("click");
  			
			setCookie("info_view", "Yes");
			
			return false;
		}
	}*/
  });
