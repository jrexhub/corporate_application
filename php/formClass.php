<?php
// echo '<pre>';
// print_r($_POST);
// echo '</pre>';

// echo "phpスクリプトの読み込み！";
// exit;


class formClass
{
	//プロパティ定義
	public $params;
	public $postData;
	public $contentStatus = 'default';

	protected $mailTo = 'info@j-rex.co.jp';
	// protected $mailTo = 'h.okada@j-rex.co.jp';
	// protected $mailBcc = 'jrex.okada@gmail.com';//jrex-it@j-rex.co.jp
	protected $mailToBisinessMaster = 'y.nakamura@j-rex.co.jp';//中村部長
	protected $mailToRentMaster = 't.negishi@j-rex.co.jp';//根岸部長
	protected $mailSubject = '【J-REX WEBサイト】お問合わせがありました';
	protected $mailCharset = 'UTF-8';
	protected $inputPage = 'index.php';
	protected $thankPage = 'thanks.html';

	//public $pref_arr = array();
	public $month_arr = array(
		'1', '2', '3', '4', '5', '6', '7', '8', '9', '10','11', '12',
	);
	public $day_arr = array(
		'1', '2', '3', '4', '5', '6', '7', '8', '9', '10',
		'11', '12', '13', '14', '15', '16', '17', '18', '19', '20',
		'21', '22', '23', '24', '25', '26', '27', '28', '29', '30',
		'31',
	);
	public $time_arr = array(
		'1', '2', '3', '4', '5', '6', '7', '8', '9', '10',
		'11', '12', '13', '14', '15', '16', '17', '18', '19',
	);

	public $pref_arr = array(
		'' => '選択してください',
		1 => '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県', '茨城県', '栃木県', '群馬県',
		'埼玉県', '千葉県', '東京都', '神奈川県', '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県',
		'岐阜県', '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県',
		'鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県', '香川県', '愛媛県', '高知県', '福岡県',
		'佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県',
	);
	public $ip_arr = array(
		// 'JREX' => '152.165.118.182',//ジェイレックス
		'arteria' => '43.233.78.220',
		'jscgateway' => '106.186.220.132',
		'i114-188-5-184.s41.a040.ap.plala.or.jp' => '114.188.5.184',
		'fwd-jscgateway02.j-scube.com' => '61.204.27.84',
		'118-86-146-55.kakt.j-cnet.jp' => '118.86.146.55',
		'fwd-jscgateway01.j-scube.com' => '106.186.220.132',
		'LOCAL' => '::1',//ローカルホスト
	);

	function __construct() {

		//環境変数
		/*$env = $_SERVER['WEB_APP_ENV'];
		switch ($env) {
			case 'localhost':
				define('ROOT_DIR', 'C:/xampp/htdocs/');
				break;
			case 'stage':
				define('ROOT_DIR', '/var/www/');
				break;
			case 'production':
				define('ROOT_DIR', '/var/www/');
				break;
			default:
			break;
		}*/

		// 特定IPアドレスを拒否
		if (in_array($_SERVER["REMOTE_ADDR"], $this->ip_arr)){
			// echo $_SERVER["REMOTE_ADDR"] . "は一致しましたので非表示にします";
			//$disp = 'style="display: none;"';
header('Location: https://www.j-rex.co.jp/?ref=contact_ip_error');
exit();
		} else {
			// echo $_SERVER["REMOTE_ADDR"] . "不一致でした";
		}
		//print_r($this->ip_arr);

		//確認画面からの画面遷移時
		if (isset($_GET['status']) && $_GET['status'] === 'back') {
			//受け取った値をセッションへ代入
			$this->params = $_SESSION['params'];
			//フラグ設定
			$this->contentStatus = 'default';
		}
		//送信処理
		elseif (isset($_POST['_onSend'])) {

			// echo "送信！";
			// exit;

			// 文字コードの定義
			mb_language('Japanese');
			mb_internal_encoding('utf-8');

			//トークンのチェック
			if ($this->check_token($_POST["token"]) == false) {
				// セッション破棄
				$this->all_session_destroy();
				// ページ遷移
				$this->location_to($this->get_indexpage_url());
				exit;
			}

			//POSTデータをアンシリアライズ
			$this->postData = unserialize(htmlspecialchars_decode($_POST['postData'], ENT_QUOTES));
			//本番環境のみ重複する改行コードを修正
			$this->postData['comment'] = str_replace(array("\r\n", "\r"), "\n", $this->postData['comment']);

			// echo $this->postData["comment"];
			// echo "<pre>";
			// print_r($this->postData["comment"]);
			// echo "</pre>";
			// exit;

			//メール本文の作成(ジェイレックスへ)
			$mail_body = "コーポレートサイトよりお問合せがありました\n\n";
			$mail_body .= "お名前　　　　　: {$this->postData['sei']}{$this->postData['mei']}\n";
			$mail_body .= "お名前（カナ）　: {$this->postData['sei_kana']}{$this->postData['mei_kana']}\n";
			$mail_body .= "郵便番号　　　　: {$this->postData['pcode_str']}\n";
			$mail_body .= "都道府県　　　　: {$this->postData['pref_str']}\n";
			$mail_body .= "市区町村・番地　: {$this->postData['city']}\n";
			$mail_body .= "建物名・部屋番号: {$this->postData['building']}\n";
			$mail_body .= "電話番号　　　　: {$this->postData['tel']}\n";
			$mail_body .= "メールアドレス　: {$this->postData['mail']}\n";
			$mail_body .= "お問合せ種別　　: {$this->postData['kind']}\n";
			$mail_body .= "お問合せ内容　　: {$this->postData['comment']}\n";
			$mail_body .= "\n";
			$mail_body .= "==================================================\n";
			$mail_body .= "date:" . date("Y/m/d(D)H:i:s", time()) . "\n";
			$mail_body .= "ip:" . getenv('REMOTE_ADDR') . "\n";
			$mail_body .= "host:" . getHostByAddr(getenv('REMOTE_ADDR')) . "\n";
			//環境依存文字(㎡)を変換
			// $mail_body = $this->change_heibei($mail_body);
			//送信設定
			$mail_to = $this->mailTo;//
			// $mail_head = "From:{$this->postData['mail']}\n";
			$mail_head = 'From: J-REX メール送信 <info@j-rex.co.jp>' . "\n";
			$mail_head .= "MIME-Version: 1.0 \n";
			// $mail_head .= "Bcc:$this->mailBcc\n";
			$mail_head .= "Content-Type: text/plain;charset=JIS\n";
			$mail_head .= "X-mailer: PHP/" . phpversion() . "\n";
			$mail_head .= "Content-Transfer-Encoding: 7bit\n";
			$mail_head .= "Return-Path: info@j-rex.co.jp\n";
			// $mail_head .= "Return-Path: root@b3.valueserver.jp\n";
			// $mail_head .= "Sender: info@j-rex.co.jp\n";
			$mail_head .= "Reply-To: info@j-rex.co.jp\n";
			// $mail_head .= "Organization: J-REX Corporation.\n";
			// $mail_head .= "X-Sender: info@j-rex.co.jp\n";
			$mail_head .= "X-Priority: 3\n";

			$mail_subject = $this->mailSubject;
			// $mail_subject = mb_encode_mimeheader($this->mailSubject, "ISO-2022-JP");
			// $mail_subject = "=?iso-2022-jp?B?".base64_encode(mb_convert_encoding("{$this->mailSubject}","JIS","UTF-8"))."?=";

			//$文字コード = mb_detect_encoding($mail_body);
			// $mail_body = mb_convert_encoding($mail_body, "JIS", $this->mailCharset);
			$mail_body = mb_convert_encoding($mail_body, 'ISO-2022-JP-ms', 'UTF-8');
			// $mail_subject = mb_convert_encoding($mail_subject, "JIS", $this->mailCharset);

			// mb_language("Japanese");

			$params = '-f ' . 'info@j-rex.co.jp';

			//送信先の追加
			if ($this->postData['kind'] == "事業、サービスについて") {
				$mail_to .= "," . $this->mailToBisinessMaster;
			} elseif ($this->postData['kind'] == "居住中の物件について") {
				$mail_to .= "," . $this->mailToRentMaster;
			}

			//メッセージ送信(to jrex)
			$this->send_message($mail_to, $mail_subject, $mail_body, $mail_head, $params);




/*
			//メール本文の作成(ユーザへ)
			$user_mail_body = "{$this->postData['sei']}{$this->postData['mei']} 様\n\n";
			$user_mail_body .= "この度はジェイレックス・コーポレーション株式会社へお問合せ頂きありがとうございます。\nお問合せ情報は下記の内容で送信されました。後ほど、担当者より連絡差し上げます。\n\n";
			$user_mail_body .= "お名前　　　　　: {$this->postData['sei']}{$this->postData['mei']}\n";
			$user_mail_body .= "お名前（カナ）　: {$this->postData['sei_kana']}{$this->postData['mei_kana']}\n";
			$user_mail_body .= "郵便番号　　　　: {$this->postData['pcode_str']}\n";
			$user_mail_body .= "都道府県　　　　: {$this->postData['pref_str']}\n";
			$user_mail_body .= "市区町村・番地　: {$this->postData['city']}\n";
			$user_mail_body .= "建物名・部屋番号: {$this->postData['building']}\n";
			$user_mail_body .= "電話番号　　　　: {$this->postData['tel']}\n";
			$user_mail_body .= "メールアドレス　: {$this->postData['mail']}\n";
			$user_mail_body .= "お問合せ種別　　: {$this->postData['kind']}\n";
			$user_mail_body .= "お問合せ内容　　: {$this->postData['comment']}\n";
			$user_mail_body .= "\n";
			$user_mail_body .= "※本メールは「ジェイレックス・コーポレーション株式会社」サイト内フォーム\nよりお問合わせいただいた方への返信メールです。本メールに心当たりのない方\nはお手数ですが、info@j-rex.co.jpまでご連絡下さいますようお願い致します。\nその他、本メールはご返信できませんのでご注意ください。\n\n";
			$user_mail_body .= "==================================================\n";
			$user_mail_body .= "date:" . date("Y/m/d(D)H:i:s", time()) . "\n";
			// $user_mail_body .= "host:".getHostByAddr(getenv('REMOTE_ADDR'))."\n\n";
			//環境依存文字(㎡)を変換
			// $user_mail_body = $this->change_heibei($user_mail_body);
			//送信設定
			$user_mail_to = $this->postData['mail'];//ユーザの入力したメールアドレス
			// $user_mail_head = "From:" . mb_encode_mimeheader("ジェイレックスコーポレーション") . "\n";//ジェイレックスの受信メールアドレスを表示
			$user_mail_head = 'From: J-REX <info@j-rex.co.jp>' . "\n";
			$user_mail_head .= "MIME-Version: 1.0 \n";
			// $user_mail_head .= "Bcc:$this->mailBcc\n";
			// $user_mail_head .= "Content-Type: text/plain;charset=JIS\n";
			$user_mail_head .= "Content-Type: text/plain;charset=JIS\n";
			$user_mail_head .= "X-mailer: PHP/" . phpversion() . "\n";
			$user_mail_head .= "Content-Transfer-Encoding: 7bit\n";
			$user_mail_head .= "Return-Path: info@j-rex.co.jp\n";
			// $user_mail_head .= "Return-Path: root@b3.valueserver.jp\n";
			// $user_mail_head .= "Sender: info@j-rex.co.jp\n";
			$user_mail_head .= "Reply-To: info@j-rex.co.jp\n";
			// $user_mail_head .= "Organization: J-REX Corporation.\n";
			// $user_mail_head .= "X-Sender: info@j-rex.co.jp\n";
			$user_mail_head .= "X-Priority: 3\n";


			$user_mail_subject = "【ジェイレックスコーポレーション WEBサイト】お問合せありがとうございます";
			// $user_mail_subject = "=?iso-2022-jp?B?".base64_encode(mb_convert_encoding("【ジェイレックスコーポレーション WEBサイト】お問合せありがとうございます","JIS","UTF-8"))."?=";

			$user_params = '-f ' . 'info@j-rex.co.jp';

			//$文字コード = mb_detect_encoding($user_mail_body);
			// $user_mail_body = mb_convert_encoding($user_mail_body, "JIS", $this->mailCharset);
			$user_mail_body = mb_convert_encoding($user_mail_body, 'ISO-2022-JP-ms', 'UTF-8');
			// $user_mail_subject = mb_convert_encoding($user_mail_subject, "JIS", $this->mailCharset);
			//メッセージ送信(to user)
			$this->send_message($user_mail_to, $user_mail_subject, $user_mail_body, $user_mail_head, $user_params);*/



			//セッション破棄
			$this->all_session_destroy();
			//ページ遷移
			$this->location_to($this->get_thankpage_url());

		//確認処理
		} elseif (isset($_POST['_onConfirm'])) {

			//受け取った値をオブジェクトへ代入
			foreach ($_POST as $key => $val) {
				$this->params[$key] = $val;
			}

			//受け取った値をセッションへ代入
			$_SESSION['params'] = $this->params;

			//バリデートを実行
			$errors = $this->validate();

			//バリデーション失敗時(フォーム再度表示)
			if (count($errors) > 0) {
				//エラー箇所
				$this->params['errors'] = $errors;
			//バリデーション成功(確認画面表示)
			} else {
				//フラグ設定
				$this->contentStatus = 'confirm';

				//お名前
				$this->params['seimei'] = $this->params['sei'] . $this->params['mei'];
				//お名前(カナ)
				$this->params['seimei_kana'] = $this->params['sei_kana'] . $this->params['mei_kana'];

				//種別
				//@ksort($this->params['kind']);
				//$this->params['kind_str'] = @implode(" / ", @$this->params['kind']);
				//郵便番号
				$this->params['pcode_str'] = ($this->params['pcode1'] . $this->params['pcode2']) ? '〒' . $this->params['pcode1'] . ' - ' . $this->params['pcode2'] : '';
				//都道府県
				$this->params['pref_str'] = ($this->params['pref']) ? $this->pref_arr[$this->params['pref']] : '';


				//送信用データの作成
				$this->postData = htmlspecialchars(serialize($this->params), ENT_QUOTES);
			}
		} else {
			//初期値の設定
			$this->params['kind'] = 'その他';
		}
	}

	//関数定義
	private function validate() {
		//エラー情報配列
		$errors = array();

		//お問合せ項目//
		/*if ($this->is_empty(@$_POST['kind']) == true) {
			$errors['kind'] = 'お問合せ内容を選択してください';
		}*/
		//法人名//
		// if ($this->is_empty($_POST['company']) == true) {
		// 	$errors['company'] =  '法人名を入力してください';
		// }
		//お名前//
		// if ($this->is_empty($_POST['name']) == true) {
		// 	$errors['name'] =  'お名前を入力してください';
		// }
		//ふりがな//
		// if ($this->is_empty($_POST['kana']) == true) {
		// 	$errors['kana'] =  'ふりがなを入力してください';
		// }
		//お名前//
		if ($this->is_empty($_POST['sei'] . $_POST['mei']) == true) {
			$errors['seimei'] =  'お名前を入力してください';
		}
		//お名前(カナ)//
		if ($this->is_empty($_POST['sei_kana'] . $_POST['mei_kana']) == true) {
			$errors['seimei_kana'] =  'お名前(カナ)を入力してください';
		}
		//電話番号//
		if ($this->is_empty($_POST['tel']) == true) {
			$errors['tel'] = '電話番号を入力してください';
		} elseif ($this->is_num($_POST['tel']) == false) {
			$errors['tel'] = '電話番号の内容に誤りがあります';
		}
		//メール//
		if ($this->is_empty($_POST['mail']) == true) {
			$errors['mail'] = 'メールアドレスを入力してください';
		} elseif ($this->is_mail($_POST['mail']) == false) {
			$errors['mail'] = 'メールアドレスの内容に誤りがあります';
		}
		//郵便番号//
		/*if ($this->is_empty($_POST['pcode1']) == true || $this->is_empty($_POST['pcode2']) == true) {
			$errors['pcode'] = '郵便番号を入力してください';
		} elseif ($this->is_num($_POST['tel']) == false) {
			$errors['pcode'] = '郵便番号の内容に誤りがあります';
		}*/
		//都道府県//
		/*if ($this->is_empty($_POST['pref']) == true) {
			$errors['pref'] =  '都道府県を選択してください';
		}*/
		//市区郡 町名・番地//
		/*if ($this->is_empty($_POST['address']) == true) {
			$errors['address'] =  '市区郡 町名・番地を入力してください';
		}*/
		//お問合せ内容//
		if ($this->is_empty($_POST['comment']) == true) {
			$errors['comment'] =  'お問合せ内容を入力してください';
		}

		return $errors;
	}

	private function is_space($value) {
		if (empty($value) || preg_match("/^[　 ]+$/", $value)) {
			return true;
		}
	}
	private function is_empty($arr) {
		if (empty($arr)) {
			return true;
		} elseif ($this->is_space($arr)) {
			return true;
		}
	}
	private function is_num($value) {
		if (preg_match("/^[0-9\-\+]+$/", $value)) {
			return true;
		} else {
			return false;
		}
	}
	private function is_mail($email) {
		if (preg_match('/^[a-zA-Z0-9_\.\-]+?@[A-Za-z0-9_\.\-]+$/', $email)) {
			return true;
		}
	}
	private function is_sameness($mail, $mail_check) {
		if ($mail == $mail_check) {
			return true;
		}
	}
	public function escape($value) {
		return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	}
	public function nltobr($value) {
		return nl2br($value);
	}
	public function change_heibei($value) {
		return str_replace("㎡", "m2", $value);
	}
	public function get_select_element($name, $posted_val = NULL, $data_arr, $id = NULL, $class = NULL) {
		$html_tag = "<select name=\"{$name}\" id=\"{$id}\" class=\"{$class}\">\n";

		foreach ($data_arr as $key => $val) {
			if ($key == $posted_val) {
				$html_tag .= "<option value=\"{$key}\" selected=\"selected\">{$val}</option>\n";
			} else {
				$html_tag .= "<option value=\"{$key}\">{$val}</option>\n";
			}
		}

		$html_tag .= "</select>\n";
		return $html_tag;
	}
	protected function get_indexpage_url() {
		return $this->indexPage;
	}
	protected function get_thankpage_url() {
		return $this->thankPage;
	}

	//メール送信
	protected function send_message($mail_to, $mail_subject, $mail_body, $mail_head, $params = "") {
		return mb_send_mail($mail_to, $mail_subject, $mail_body, $mail_head, $params);
	}
	//ページ遷移
	protected function location_to($url) {
		/*header("Location: {$url}");
		exit();*/

		echo "<script type=\"text/JavaScript\">location.replace(\"{$url}\")</script>";
		exit();
	}

	//セッション変数を全て解除
	protected function all_session_destroy() {
		$_SESSION = array();

		// セッションを切断するにはセッションクッキーも削除する。
		// セッション情報だけでなくセッションを破壊する。
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}

		session_destroy();//セッションを破壊

		return true;
	}

	//ワンタイムトークンの生成
	public function create_token() {
		$ipad = getenv('REMOTE_ADDR');
		$time = time();
		$rand = mt_rand();

		//値をハッシュ化
		$ipad = hash('sha256', $ipad);
		$time = hash('md5', $time);
		$rand = hash('md5', $rand);

		$no_token = $ipad . $time . $rand;

		//トークン生成
		$token = hash('sha256', $no_token);

		return $token;
	}

	//トークンの半券を取得
	public function get_harf_token() {

		$original_token = $this->create_token();

		//フォームに埋め込む半券
		$harf = substr($original_token, 0, 10);

		//オリジナルのトークンと片割れをSESSIONに保存
		$_SESSION['harf_token'] = substr($original_token, 10);
		$_SESSION['original_token'] = $original_token;
		return $harf;
	}

	//トークンの照合
	public function check_token($harf_token) {

		/*echo $_SESSION['original_token'];
		echo "<br>".$harf_token;*/

		//照合用のトークン取得
		$ch_token = $_SESSION['original_token'];

		//所持していた半券とformから送信された半券を結合
		$token = $harf_token . $_SESSION['harf_token'];

		//照合
		if(strcmp($ch_token, $token) === 0) {
			return true;
		}

		return false;
	}
}

//インスタンスの作成
$formObj = new formClass();
