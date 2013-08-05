/*	****************************************************************
	*
	*	qcsBox
	*
	****************************************************************
	*
	*	Author:
	*		QQ:452605524
	*		http://www.quoracms.com
	*
	****************************************************************
	*
	*	How to use(So easy!!!):
	*
	*		qcsAlert:
	*
	*			$('#id').click(function(){
	*				$(this).qcsAlert( {
	*					'title':'' , 'message':'' , 'button':''
	*				} , callback_function });
	*
	*		qcsConfirm:
	*
	*			$('#id').click(function(){
	*				$(this).qcsConfirm( {
	*					'title':'' , 'message':'' , 'button1':'' , 'button2':''
	*				} , callback_function });
	*
	**************************************************************** */
(function($){$.fn.qcsAlert=function(opciones,callback){if(!opciones||typeof opciones!='object'){opciones={}};if(!opciones['title']){opciones['title']='提示'};if(!opciones['message']){opciones['message']='This is the QCS Alert Dialog Box'};if(!opciones['button']){opciones['button']='OK'};if(!callback){callback=null};$('body').append($('<div>').attr('id','iOSdialogBoxLockScreen')).append($('<div>').attr('id','iOSdialogBoxWindow').append($('<div>').attr('id','iOSdialogBoxWindowTitle').html(opciones['title'])).append($('<div>').attr('id','iOSdialogBoxWindowMessage').html(opciones['message'])).append($('<div>').attr('id','iOSdialogBoxWindowButtons').append($('<div>').attr('class','iOSdialogBoxButton').css({'position':'absolute','left':'-20px'}).append($('<div>').html(opciones['button']).click(function(e){$('#iOSdialogBoxLockScreen').remove();$('#iOSdialogBoxWindow').remove();if(callback!=null){callback(1)}})))));var altura=$('.iOSdialogBoxButton div').height();altura/=2;altura*=-1;altura+='px';$('.iOSdialogBoxButton div').css('margin-top',altura);$('.iOSdialogBoxButton').css('margin-left','128px')};$.fn.qcsConfirm=function(opciones,callback){if(!opciones||typeof opciones!='object'){opciones={}};if(!opciones['title']){opciones['title']='确认'};if(!opciones['message']){opciones['message']='This is the QCS Confirm Dialog Box'};if(!opciones['button1']){opciones['button1']='OK'};if(!opciones['button2']){opciones['button2']='Cancel'};if(!callback){callback=null};$('body').append($('<div>').attr('id','iOSdialogBoxLockScreen')).append($('<div>').attr('id','iOSdialogBoxWindow').append($('<div>').attr('id','iOSdialogBoxWindowTitle').html(opciones['title'])).append($('<div>').attr('id','iOSdialogBoxWindowMessage').html(opciones['message'])).append($('<div>').attr('id','iOSdialogBoxWindowButtons').append($('<div>').attr('class','iOSdialogBoxButton').css('float','left').append($('<div>').html(opciones['button1']).click(function(e){$('#iOSdialogBoxLockScreen').remove();$('#iOSdialogBoxWindow').remove();if(callback!=null){callback(true)}}))).append($('<div>').attr('class','iOSdialogBoxButton').css('float','right').append($('<div>').html(opciones['button2']).click(function(e){$('#iOSdialogBoxLockScreen').remove();$('#iOSdialogBoxWindow').remove();if(callback!=null){callback(false)}})))));var altura=$('.iOSdialogBoxButton div').height();altura/=2;altura*=-1;altura+='px';$('.iOSdialogBoxButton div').css('margin-top',altura)}})(jQuery);