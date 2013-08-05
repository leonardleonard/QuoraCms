var i=0;
var m=$('#msg');
	function flash_msg(){ 
	$.browser.msie?(i%2==0?document.getElementById("msg").style.display="none":document.getElementById("msg").style.display="block"):(i%2==0?m.animate({opacity:0},200):m.animate({opacity:1},200));
	i++;
	} 
	setInterval("flash_msg()",400); 