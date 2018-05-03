!function(){
	
	var CloudStrong = function(){
		this.leftMenu = function(controller,action){
			$('.left li').each(function(){
				var name = $(this).find('.item').attr('controller');
				if(name == controller){
					$(this).addClass('focus');
					$(this).find('.close_arrow').attr('class','open_arrow');
					$(this).find('.menu').slideDown();
					$(this).find('.menu a').each(function(){
						var taction = $(this).attr('action');
						var inc = action.slice(0,taction.length);
						if(taction == action || taction == inc){
							$(this).addClass('focus');
						}
					});
				}
			});
			$('.left').on('click','.item',function(){
				var obj = $(this).parent();
				var len = obj.find('div').length;
				var cls = obj.find('.close_arrow').length;
				
				if(len > 1){
					$('.left li .menu').slideUp();
					$('.left li').removeClass('focus');
					$('.left li .open_arrow').attr('class','close_arrow');
					if(cls > 0){
						obj.find('.close_arrow').attr('class','open_arrow');
						obj.find('.menu').slideDown();
						obj.addClass('focus');
					}else{
						obj.find('.open_arrow').attr('class','close_arrow');
						obj.find('.menu').slideUp();
						obj.removeClass('focus');
					}
				}
			});
		};
		
	}
	
	window.CloudStrong = CloudStrong;
	
}();