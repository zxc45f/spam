<?php
error_reporting(0);
ob_start();
header("Content-Type: application/json; charset=UTF-8");
ob_start();
date_default_timezone_set('Asia/Baghdad');
$API_KEY = "6772110101:AAH9smtgnBN3qbNP4tTdHIsWBicF7Q85HKk" ;
define('API_KEY',$API_KEY);
define("tryreyrtyrtbot", explode(":", $API_KEY)[0]);


function bot($method, $datas = []) {
    $url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
    $options = [
        'http' => [
            'method'  => 'POST',
            'content' => http_build_query($datas),
            'header'  => 'Content-Type: application/x-www-form-urlencoded\r\n',
        ],
    ];
    $context  = stream_context_create($options);
    $res = file_get_contents($url, false, $context);

    if ($res === FALSE) {
        return json_encode(['error' => 'Request failed']);
    } else {
        return json_decode($res);
    }
}


#


$update = json_decode(file_get_contents('php://input'));
$FileName = "Emails" ;
$h = json_decode(file_get_contents($FileName), 1);
if ($update->message) {
    $message = $update->message;
    $message_id = $message->message_id;
    $username = $message->from->username;
    $chat_id = $message->chat->id;
    $title = isset($message->chat->title) ? $message->chat->title : '';
    $text = isset($message->text) ? $message->text : '';
    $user = $message->from->username;
    $name = $message->from->first_name;
    $from_id = $message->from->id;
}

if ($update->callback_query) {
    $callback_query = $update->callback_query;
    $data = $callback_query->data;
    $message = $callback_query->message;
    $chat_id = $message->chat->id;
    $title = isset($message->chat->title) ? $message->chat->title : '';
    $message_id = $message->message_id;
    $name = $message->chat->first_name;
    $user = $message->chat->username;
    $from_id = $callback_query->from->id;
}
function X($data, $filename) {
    $jsonString = json_encode($data, JSON_PRETTY_PRINT);
    if (file_put_contents($filename, $jsonString) !== false) {
        return true; 
    } else {
        return false; 
    }
}


if ($text == "/start") {
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "
        *
        اهلا بك في بوت ارسال الرسائل الإلكترونية التلقائي 
        *
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "أضف بريدات", 'callback_data' => "addmail"], ['text' => "حذف البريدات", 'callback_data' => "delmail"]],
                [['text' => "عرض البريدات المضافه", 'callback_data' => "viewmails"]],
                [['text' => "ارسال رساله", 'callback_data' => "sendms"]],
            ]
        ])
    ]);
    unset($h["mode"]);
X($h, $FileName);
    return false ;
}


if($data == "backi") {
	bot('editMessagetext', [
        'chat_id' => $chat_id,
        'message_id' => $message_id, 
        'text' => "
        *
        اهلا بك في بوت ارسال الرسائل الإلكترونية التلقائي 
        *
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "أضف بريدات", 'callback_data' => "addmail"], ['text' => "حذف البريدات", 'callback_data' => "delmail"]],
                [['text' => "عرض البريدات المضافه", 'callback_data' => "viewmails"]],
                [['text' => "ارسال رساله", 'callback_data' => "sendms"]],
            ]
        ])
    ]);
    unset($h["mode"]);
X($h, $FileName);
    return false ;
	} 
	
if($data == "addmail") {
	bot('editMessagetext', [
        'chat_id' => $chat_id,
        'message_id' => $message_id, 
        'text' => "
        *
ارسل الان الميل مثلا ✔️
help@instagram.com
        *
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "رجوع", 'callback_data' => "backi"]],
            ]
        ])
    ]);
    $h["mode"] = $data;
X($h, $FileName);
return false ;
	}
	
	if(preg_match("/delt_/" , $text)) {
		$ma = explode("delt_", $text)[1];
		$ma = ("[". $h["mails"][$ma]. "]") ;
		if(!preg_match("/@/",$ma)) {
			bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "
ماكو بريد حب🚶‍♂️
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "رجوع", 'callback_data' => "backi"]],
            ]
        ])
    ]);
    return false ;
			} 
			
		bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "
        الايميل تم حذفه بنجاح ✅
الاميل : $ma
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "رجوع", 'callback_data' => "backi"]],
            ]
        ])
    ]);
    unset($h["mails"][explode("delt_", $text)[1]]) ;
X($h, $FileName);
		} 
		
	if($data == "viewmails") {
		foreach($h["mails"] as $m) {
			$v=array_search($m,$h["mails"] ); 
			$sm = $sm."[$m] | [/delt_$v] \n";
			} 
			if(!$h["mails"]) {
				bot('editMessagetext', [
        'chat_id' => $chat_id,
        'message_id' => $message_id, 
        'text' => "
        *
فكر مضايف بريدات من قبل👊🏻
        *
   
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
            [['text' => "أضف بريدات", 'callback_data' => "addmail"]], 
                [['text' => "رجوع", 'callback_data' => "backi"]],
            ]
        ])
    ]);
    return false ;
				} 
				
	bot('editMessagetext', [
        'chat_id' => $chat_id,
        'message_id' => $message_id, 
        'text' => "
        *
جميع بريدات ضايفها😮‍💨
        *
        $sm
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "رجوع", 'callback_data' => "backi"]],
            ]
        ])
    ]);
    $h["mode"] = $data;
X($h, $FileName);
return false ;
	}
	
	if($data == "delmail") {
		if(!$h["mails"]) {
				bot('editMessagetext', [
        'chat_id' => $chat_id,
        'message_id' => $message_id, 
        'text' => "
        *
فكر مضايف بريدات من قبل👊🏻
        *
   
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
            [['text' => "أضف بريدات", 'callback_data' => "addmail"]], 
                [['text' => "رجوع", 'callback_data' => "backi"]],
            ]
        ])
    ]);
    return false ;
				} 
		$email= count($h["mails"]) ;
		bot('editMessagetext', [
        'chat_id' => $chat_id,
        'message_id' => $message_id, 
        'text' => "
        ❗ هل انت متأكد من حذف العدد ($email) من القائمه؟ 
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "نعم بالتأكيد ✅", 'callback_data' => "yesDel"]],
                [['text' => "رجوع", 'callback_data' => "backi"]],
            ]
        ])
    ]);
    $h["mode"] = $data;
X($h, $FileName);
return false ;
		} 
		
		if($data == "yesDel") {
			if($h["mode"] != "delmail" ) {
				bot('editMessagetext', [
        'chat_id' => $chat_id,
        'message_id' => $message_id, 
        'text' => "
تم بالنجاح تاكيد وحذف ($email) ايميلات من القائمه ✔️
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                
                [['text' => "رجوع", 'callback_data' => "backi"]],
            ]
        ])
    ]);
				return false ;
				} 
				$email= count($h["mails"]) ;
				bot('editMessagetext', [
        'chat_id' => $chat_id,
        'message_id' => $message_id, 
        'text' => "
تم بالنجاح تاكيد وحذف ($email) ايميلات من القائمه ✔️
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                
                [['text' => "رجوع", 'callback_data' => "backi"]],
            ]
        ])
    ]);
    $h["mode"] = $data;
    unset($h["mails"]) ;
X($h, $FileName);
return false ;
				} 
		
		
		
		
	if($h["mode"] == "addmail"){
		if(preg_match("/@/",$text)) { 
			$email= count($h["mails"])+1;
			if(in_array($text, $h["mails"])) {
				bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "
        
⛔ يبدو ان الايميل [$text] تم اضافته الي القائمه من قبل
        
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "رجوع", 'callback_data' => "backi"]],
            ]
        ])
    ]);
    
    return false ;
				} 
			bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "
        *
~ تم الحفظ والاضافه ✅
الاميل  : *[$text] 
*~ عدد الاميلات الحالي : $email*
        
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
        'reply_to_message_id' => $message_id,
    ]);
    $h["mails"][] = $text ;
			
				
		unset($h["mode"]);
X($h, $FileName);

		} else {
				$c=bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "
        *
       تأكد من ارسال الاميل بشكل صحيح ✔️
       تم الغاء العمليه بنجاح
        *
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
        'reply_to_message_id' => $message_id,
    ]);
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "
        *
        اهلا بك في بوت ارسال الرسائل الإلكترونية التلقائي 
        *
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
        'reply_to_message_id' => $c->result->message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "أضف بريدات", 'callback_data' => "addmail"], ['text' => "حذف البريدات", 'callback_data' => "delmail"]],
                [['text' => "عرض البريدات المضافه", 'callback_data' => "viewmails"]],
                [['text' => "ارسال رساله", 'callback_data' => "sendms"]],
            ]
        ])
    ]);
    unset($h["mode"]);
X($h, $FileName);
   }
  
 }

if($data == "sendms") {
	bot('editMessagetext', [
        'chat_id' => $chat_id,
        'message_id' => $message_id, 
        'text' => "
        *
ارسلي البريد التي ترسل له رساله :
        *
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
            'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "رجوع", 'callback_data' => "backi"]],
            ]
        ])
    ]);
    $h["mode"] = $data ;
X($h, $FileName);
return false ;
	}
	
	if($h["mode"] == "sendms"){
	if(preg_match("/@/",$text)) {
		bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "
        *
ارسل لي الموضوع :
        *
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
            'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "رجوع", 'callback_data' => "backi"]],
            ]
        ])
    ]);
    $h["to"] = $text ;
    $h["mode"] = "s2" ;
X($h, $FileName);
return false ;
		}else{
			bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "
        *
❗ بريد خاطء يرجي التأكد 
        *
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
            'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "رجوع", 'callback_data' => "backi"]],
            ]
        ])
    ]);
    return false ;
			} 
		}
		
		if($h["mode"] == "s2"){
			if($text) {
				bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "
        *
ارسل لي الرساله الإلكترونية :
        *
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
            'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "رجوع", 'callback_data' => "backi"]],
            ]
        ])
    ]);
    $h["sub"] = $text ;
    $h["mode"] = "s3" ;
X($h, $FileName);
return false ;
				} 
			}
			
			if($h["mode"] == "s3"){
			if($text) {
				bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "
        *
كم مره تريد ارسال هذه الرساله :
        *
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
            'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "رجوع", 'callback_data' => "backi"]],
            ]
        ])
    ]);
    $h["msg"] = $text ;
    $h["mode"] = "s4" ;
X($h, $FileName);
return false ;
				} 
			}
			
			if($h["mode"] == "s4"){
			if(is_numeric($text)) {
				$mail = $h["to"];
				$cou = count($h["mails"]);
				$sub = $h["sub"] ;
				$msg = $h["msg"];
				$v = rand(123456789,987654321);
				$h["codes"][$v] = $mail. "(?=?=?)". $text . "(?=?=?)". $sub. "(?=?=?)". $msg. "(?=?=?)". $text;
				bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "
سيتم ارسال الرساله الي : $mail
عدد المرات التي سيتم ارسالها من كل حساب : *$text*
عدد الحسابات المضافه : *$cou*

موضوعها : $sub

الرساله : $msg
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
            'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "الغاء العمليه 🚫", 'callback_data' => "backi"], ['text' => "تأكيد وارسال ✅", 'callback_data' => "yesme_$v"]],
            ]
        ])
    ]);
    $h["msgs"] = $text ;
    $h["mode"] = "s5" ;
X($h, $FileName);
return false ;
				} else {
					bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "
برجاء ارسال الارقام فقط 👍
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "رجوع", 'callback_data' => "backi"]],
            ]
        ])
    ]);
    return false ;
					} 
			}
			
			if(preg_match("/yesme_/", $data)) {
				$v = explode("yesme_", $data)[1];
				 
				$h = explode("(?=?=?)", $h["codes"][$v]) ;
				$mail = $h[0];
				$cou = count($h["mails"]);
				$sub = $h[2];
				$msg = $h[3];
				$msgs = $h[1];
				bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "
        *
برجاء الانتضار 🔃
*

سيتم ارسال الرساله الي : $mail
عدد المرات التي سيتم ارسالها من كل حساب : *$msgs*
عدد الحسابات المضافه : *$cou*

موضوعها : $sub

الرساله : $msg
        ",
        'parse_mode' => "markdown",
        'disable_web_page_preview' => true,
            'reply_to_message_id' => $message_id,
    ]);
    $h = json_decode(file_get_contents($FileName), 1);
    $vf = 0;
    for($i=0;$i <= count($h["mails"]) ;$i++){
    	
    $vf +=1;
    	for($i=0;$i <= $msgs;$i++){
        $to_email = $mail;
    $subject = $sub;
    $message = $msg;
    $headers = "From: ". $h["mails"][$vf];
    if (mail($to_email, $subject, $message, $headers)) {
        echo "تم إرسال الرسالة بنجاح";
    } else {
        echo "حدث خطأ أثناء محاولة إرسال الرسالة";
    }
}
} 
				} 