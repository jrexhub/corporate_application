/*======================================================
個人情報の取扱いについての同意
======================================================*/
$(function(){
	$('#onCheck').click(function(){
		var pos = $("#agreement").offset().top;

		if (!$("[id=privacy]").prop("checked")) {
			alert("「個人情報の取扱いについて」の内容をご確認のうえ、\n「同意する」にチェックをお願いいたします");
			$('body,html').animate({scrollTop: pos}, 500, 'swing');
			$('#agreement label').css('background-color', '#ffe6e7');
			return false;
		} else {
			return true;
		}
	});
});

/*======================================================
郵便番号から住所を表示
======================================================*/
$(function() {
	$('.pcode1, .pcode2').change(function(e) {
		if ($('.pcode1').val() == '' || $('.pcode2').val() == '') {
			return false;
		}

		$('span#loading img').show();

		//住所情報を取得
		setTimeout(function(){
			$('span#loading img').hide();
			AjaxZip2.zip2addr('pcode1', 'pref', 'city', 'pcode2');
		}, 200);
	});
});
