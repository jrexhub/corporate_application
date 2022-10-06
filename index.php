<?php
session_start();
session_regenerate_id(true);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-52M2THQ');</script>
<!-- End Google Tag Manager -->
<script>
var obj = {
	startday: new Date('2021/01/12 01:59:59'),
	endday: new Date('2020/01/13 23:59:59'),
	today: new Date(),
	//elm_head: $('body > .header'),
	url: 'https://www.j-rex.co.jp/'
};
if (obj.startday < obj.today && obj.today < obj.endday) {
	location.href = obj.url;
};
</script>

<meta charset="utf-8">
<title>お問合せ | ジェイレックス・コーポレーション株式会社[J-REX]</title>
<meta name="description" content="ジェイレックス・コーポレーション株式会社へのお問合せ窓口です。弊社の事業、サービス、採用に関する質問をはじめ、ご意見ご感想など、お気軽にご記入ください。">
<meta name="keyword" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- CSS -->
<link href="assets/lib/slick/slick.css"  rel="stylesheet">
<link href="assets/css/common.css" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

<!-- Script -->
<?php
include('./php/formClass.php');
//include('./php/dbClass.php');

// echo '<pre>';
// print_r($formObj);
// echo '</pre>';
?>
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-52M2THQ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<!-- [ wrapper ] -->
<div id="app" class="wrapper" v-cloak>

<!-- [ header ] -->
<header class="header">
	<div class="header__cont">
		<h1 class="header__logo"><img src="assets/image/logo.png" alt="ジェイレックス・コーポレーション株式会社"></h1>
		<p class="header__text">お問合せ</p>
	</div>
</header><!-- [ /header ] -->

<!-- [ main ] -->
<main class="main" role="main">
<div class="main__cont">
<?php if ($formObj->contentStatus === 'confirm'): ?>
	<form action="#submit" method="post" name="formConfirm">
	<input type="hidden" name="_onSend" value="1">
	<input type="hidden" name="token" value="<?php echo $formObj->escape($formObj->get_harf_token()); ?>">
	<input type="hidden" name="postData" value="<?php echo $formObj->escape($formObj->postData); ?>">
		<div class="main__box">
			<h2>内容をご確認の上、送信してください</h2>
			<table class="tableElemChk">
				<caption>ご入力いただいた内容</caption>
				<tr>
					<th scope="row"><span class="required">お名前</span></th>
					<td><?php echo $formObj->escape($formObj->params['seimei']); ?></td>
				</tr>
				<tr>
					<th scope="row"><span class="required">お名前(カナ)</span></th>
					<td><?php echo $formObj->escape($formObj->params['seimei_kana']); ?></td>
				</tr>
				<tr>
					<th scope="row" class="cover"><span>郵便番号</span></th>
					<td><?php echo $formObj->escape($formObj->params['pcode_str']); ?></td>
				</tr>
				<tr>
					<th scope="row"><span>都道府県</span></th>
					<td><?php echo $formObj->escape($formObj->params['pref_str']); ?></td>
				</tr>
				<tr>
					<th scope="row"><span>市区町村・番地</span></th>
					<td><?php echo $formObj->escape($formObj->params['city']); ?></td>
				</tr>
				<tr>
					<th scope="row"><span>建物名・部屋番号</span></th>
					<td><?php echo $formObj->escape($formObj->params['building']); ?></td>
				</tr>
				<tr>
					<th scope="row"><span class="required">電話番号</span></th>
					<td><?php echo $formObj->escape($formObj->params['tel']); ?></td>
				</tr>
				<tr>
					<th scope="row"><span class="required">メールアドレス</span></th>
					<td><?php echo $formObj->escape($formObj->params['mail']); ?></td>
				</tr>
				<tr>
					<th scope="row"><span class="required">お問合せ種別</span></th>
					<td><?php echo $formObj->escape($formObj->params['kind']); ?></td>
				</tr>
				<tr>
					<th scope="row"><span class="required">お問合せ内容</span></th>
					<td><?php echo $formObj->nltobr($formObj->escape($formObj->params['comment'])); ?></td>
				</tr>
				<tr>
					<td colspan="2" class="button__col"><a class="btn" href="<?php echo $_SERVER['PHP_SELF']; ?>?status=back#top">内容の修正</a><button class="btn" type='submit' name='onSend' value='send'>送信</button></td>
				</tr>
			</table>
		</div>
	</form>
<?php else: ?>
    <p class="main__lead">下記「個人情報の取り扱いについて」の内容をご確認、同意いただき入力へお進みください<br>お問合せフォームからのセールス等はご遠慮ください</p>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>#submit" method="post" name="formInput" onSubmit="return privacyCheck(document.getElementById('privacy')); return false;">
	<input type="hidden" name="_onConfirm" value="1">
        <div class="main__box">
            <h2>個人情報の取扱いについて</h2>
            <div class="main__privacydoc">
                <h3>■このフォームでお預かりする個人情報の取扱いについて</h3>
                <h4>1. 事業者の氏名又は名称</h4>
                <p>ジェイレックス・コーポレーション株式会社</p>
                <h4>2. 個人情報保護管理者（若しくはその代理人）の氏名又は職名、所属及び連絡先</h4>
                <p>管理者名：中村安利<br>所属部署：管理部 部長<br>連絡先：TEL：03-3345-6012</p>
                <h4>3. 個人情報の利用目的</h4>
                <p>・お問い合わせ対応（本人への連絡を含む）のため</p>
                <h4>4. 個人情報の開示等の請求</h4>
                <p>ご本人様は、当社に対してご自身の個人情報の利用目的の通知、開示、内容の訂正、追加又は削除、利用の停止、消去及び第三者への提供の停止に関して、下記の当社問合わせ窓口に申し出ることができます。その際、当社はお客様ご本人を確認させていただいたうえで、合理的な期間内に対応いたします。</p>
                <p>【お問合せ窓口】<br>〒160-0023 東京都新宿区西新宿1-23-7 新宿ファーストウエスト16F<br>ジェイレックス・コーポレーション株式会社 お客様相談室<br>TEL：0120-288-665　FAX：03-3345-6011<br>※土・日曜日、祝日、年末年始、ゴールデンウィーク期間は翌営業日以降の対応とさせていただきます。</p>
                <h4>5. 個人情報を提供されることの任意性について</h4>
                <p>ご本人様が当社に個人情報を提供されるかどうかは任意によるものです。ただし、必要な項目をいただけない場合、適切な対応ができない場合があります。</p>
                <div class="agreement" id="agreement">
    				<label for="privacy"><input type="checkbox" name="privacy" id="privacy" value="1" <?php if (@$formObj->params['privacy']) { echo "checked=\"checked\"";} ?>> 同意する</label>
    			</div>
            </div>
        </div>

        <div class="main__box">
            <?php if (@$formObj->params['errors']) { ?>
    		<div class="error-messages" id="submit">
    			<p>以下の項目に誤りがございます。再度ご入力をお願いいたします。</p>
    			<ul>
    				<?php foreach (@$formObj->params['errors'] as $key => $val) { ?>
    					<li>■<?php echo $formObj->escape(@$formObj->params['errors'][$key]); ?></li>
    				<?php } ?>
    			</ul>
    		</div>
    		<?php } ?>

            <h2>必要事項をご入力ください</h2>
            <table id="top" class="tableElemIpt">
				<caption>お問合せフォーム</caption>
    			<tr class="<?php if (@$formObj->params['errors']['seimei']) { echo "error"; } ?>">
    				<th scope="row"><span class="required">お名前</span></th>
    				<td><input type="text" name="sei" placeholder="例：新宿" value="<?php echo @$formObj->params['sei']; ?>">&nbsp;&nbsp;<input type="text" name="mei" placeholder="例：太郎" value="<?php echo @$formObj->params['mei']; ?>">
                        <?php if (@$formObj->params['errors']['seimei']) { echo "<p class=\"error-txt\">" . $formObj->escape($formObj->params['errors']['seimei']) . "</p>";} ?></td>
    			</tr>
                <tr class="<?php if (@$formObj->params['errors']['seimei_kana']) { echo "error"; } ?>">
    				<th scope="row"><span class="required">お名前(カナ)</span></th>
    				<td><input type="text" name="sei_kana" placeholder="例：シンジュク" value="<?php echo @$formObj->params['sei_kana']; ?>">&nbsp;&nbsp;<input type="text" name="mei_kana" placeholder="例：タロウ" value="<?php echo @$formObj->params['mei_kana']; ?>">
                        <?php if (@$formObj->params['errors']['seimei_kana']) { echo "<p class=\"error-txt\">" . $formObj->escape($formObj->params['errors']['seimei_kana']) . "</p>";} ?></td>
    			</tr>
    			<tr>
    				<th scope="row" class="cover"><span>郵便番号</span></th>
    				<td><span id="loading"><img src="assets/image/loading.gif"></span><input type="text" name="pcode1" class="pcode1 mode-disabled" placeholder="例：160" value="<?php echo @$formObj->params['pcode1']; ?>" maxlength="3"> - <input type="text" name="pcode2" class="pcode2 mode-disabled" placeholder="例：0023" value="<?php echo @$formObj->params['pcode2']; ?>" maxlength="4"> (半角数字)</td>
    			</tr>
    			<tr>
    				<th scope="row"><span>都道府県</span></th>
    				<td><?php echo $formObj->get_select_element('pref', @$formObj->params['pref'], $formObj->pref_arr, 'pref', 'selectElementA01'); ?></td>
    			</tr>
    			<tr>
    				<th scope="row"><span>市区町村・番地</span></th>
    				<td><input type="text" id="city" name="city" placeholder="例：新宿区西新宿1-23-7" value="<?php echo @$formObj->params['city']; ?>"></td>
    			</tr>
    			<tr>
    				<th scope="row"><span>建物名・部屋番号</span></th>
    				<td><input type="text" name="building" placeholder="例：新宿ファーストウエスト16F" value="<?php echo @$formObj->params['building']; ?>"></td>
    			</tr>
    			<tr class="<?php if (@$formObj->params['errors']['tel']) { echo "error"; } ?>">
    				<th scope="row"><span class="required">電話番号</span></th>
    				<td><input type="text" name="tel" class="mode-disabled" placeholder="例：03-3345-6012" value="<?php echo @$formObj->params['tel']; ?>"> (半角数字)<?php if (@$formObj->params['errors']['tel']) { echo "<p class=\"error-txt\">" . $formObj->escape($formObj->params['errors']['tel']) . "</p>";} ?></td>
    			</tr>
    			<tr class="<?php if (@$formObj->params['errors']['mail']) { echo "error"; } ?>">
    				<th scope="row"><span class="required">メールアドレス</span></th>
    				<td><input type="text" name="mail" class="mode-disabled" placeholder="例：example@j-rex.co.jp" value="<?php echo @$formObj->params['mail']; ?>"> (半角英数字)<?php if (@$formObj->params['errors']['mail']) { echo "<p class=\"error-txt\">" . $formObj->escape($formObj->params['errors']['mail']) . "</p>";} ?></td>
    			</tr>
                <tr class="<?php if (@$formObj->params['errors']['kind']) { echo "error"; } ?>">
    				<th scope="row"><span class="required">お問合せ種別</span></th>
    				<td><label for="kind_business"><input id="kind_business" type="radio" name="kind" value="事業、サービスについて" <?php if (@$formObj->params['kind'] == '事業、サービスについて') { echo "checked=\"checked\"";} ?>> 事業、サービスについて</label> <label for="kind_article"><input id="kind_article" type="radio" name="kind" value="居住中の物件について" <?php if (@$formObj->params['kind'] == '居住中の物件について') { echo "checked=\"checked\"";} ?>> 居住中の物件について</label>
					<br>
                    <label for="kind_recruit"><input id="kind_recruit" type="radio" name="kind" value="採用について" <?php if (@$formObj->params['kind'] == '採用について') { echo "checked=\"checked\"";} ?>> 採用について</label>
                    <label for="kind_other"><input id="kind_other" type="radio" name="kind" value="その他" <?php if (@$formObj->params['kind'] == 'その他') { echo "checked=\"checked\"";} ?>> その他</label>
                        <?php if (@$formObj->params['errors']['driver']) { echo "<p class=\"error-txt\"><i class=\"fa fa-exclamation-circle fa-lg fa-fw\" aria-hidden=\"true\"></i>" . $formObj->escape($formObj->params['errors']['driver']) . "</p>";} ?></td>
    			</tr>
    			<tr class="<?php if (@$formObj->params['errors']['comment']) { echo "error"; } ?>">
    				<th scope="row"><span class="required">お問合せ内容</span></th>
    				<td><textarea name="comment" placeholder="例：遊休土地の活用方法について、いくつか検討している。マンション経営での資産運用について相談したい。"><?php echo @$formObj->params['comment']; ?></textarea><?php if (@$formObj->params['errors']['comment']) { echo "<p class=\"error-txt\">" . $formObj->escape($formObj->params['errors']['comment']) . "</p>";} ?></td>
    			</tr>
    			<tr>
    				<td colspan="2" class="button__col"><button class="btn" type='submit' id='onCheck' name='onCheck' value='check'>入力内容の確認</button></td>
    			</tr>
    		</table>

        </div>

	</form>
<?php endif; ?>
</div>
</main><!-- [ /main ] -->

<!-- [ footer ] -->
<footer class="footer">
	<div class="footer__cont">
		<div class="left">
			<p class="logo"><a href="https://www.j-rex.co.jp"><img src="assets/image/logo_footer.png" alt="ジェイレックス・コーポレーション株式会社"><span class="company">ジェイレックス・コーポレーション株式会社</span></a></p>
		</div>
		<!-- <p class="right"><img src="assets/image/pmark.png" alt="プライバシーマーク"></p> -->
	</div>
	<div class="footer__belt">
		<p class="footer__copy">Copyright &copy; J-REX Corporation All rights reserved.</p>
	</div>
</footer><!-- [ /footer ] -->

</div><!-- [ /wrapper ] -->

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://jp.vuejs.org/js/vue.js"></script>
<script type="text/javascript" src="assets/lib/slick/slick.min.js"></script>
<script src="assets/lib/lazyload/jquery.lazyload.min.js" charset="utf-8"></script>
<script type="text/javascript" src="assets/js/base.min.js" charset="utf-8"></script>
<script type="text/javascript" src="assets/js/formControll.min.js" charset="utf-8"></script>
<script type="text/javascript" src="assets/lib/pcode/ajaxzip2.js"></script>
<script>AjaxZip2.JSONDATA = 'assets/lib/pcode/data';</script>
</body>
</html>
