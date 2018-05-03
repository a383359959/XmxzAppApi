$(function(){
	
	$('form').submit(function(){
		
		var username = $('input[name="username"]').val();
		var password = $('input[name="password"]').val();
		if(username == ''){
			alert('用户名不能为空！');
			return false;
		}else if(password == ''){
			alert('密码不能为空！');
			return false;
		}
		
	});
	
});
