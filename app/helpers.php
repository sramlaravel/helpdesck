<?php

use App\Models\User;
use Carbon\Carbon;

use PHPMailer\PHPMailer\PHPMailer;

use App\Models\Setting;


use App\Models\customizeerror;
use App\Models\Customcssjs;
use App\Models\Apptitle;
use App\Models\Customer;
use App\Models\EmailLog;
use App\Models\EmailTemplate;



if (!function_exists('setting'))   {

    function setting($key){

        return  Setting::where('key', '=',  $key)->first()->value ?? '' ;
    }
}

if (!function_exists('settingpages'))   {
function settingpages($errorname){
	return  customizeerror::where('errorname', '=',  $errorname)->first()->errorvalue ?? '' ;
}
}
    if (!function_exists('customcssjs'))   {
function customcssjs($name){
	return Customcssjs::where('name', '=', $name)->first()->value ?? '';
}
    }
        if (!function_exists('getLanguages'))   {
function getLanguages()
{

	$scanned_directory = array_diff(scandir( resource_path('lang') ), array('..', '.'));

	return $scanned_directory;
}

        }




function slug($string)
{
    return Illuminate\Support\Str::slug($string);
}




function shortCodeReplacer($shortCode, $replace_with, $template_string)
{
    return str_replace($shortCode, $replace_with, $template_string);
}


function verificationCode($length)
{
    if ($length == 0) return 0;
    $min = pow(10, $length - 1);
    $max = 0;
    while ($length > 0 && $length--) {
        $max = ($max * 10) + 9;
    }
    return random_int($min, $max);
}

function getNumber($length = 8)
{
    $characters = '1234567890';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}



//moveable



function makeDirectory($path)
{
    if (file_exists($path)) return true;
    return mkdir($path, 0755, true);
}


function removeFile($path)
{
    return file_exists($path) && is_file($path) ? @unlink($path) : false;
}


function activeTemplate($asset = false)
{
    $general = GeneralSetting::first(['active_template']);
    $template = $general->active_template;
    $sess = session()->get('template');
    if (trim($sess)) {
        $template = $sess;
    }
    if ($asset) return 'assets/templates/' . $template . '/';
    return 'templates.' . $template . '.';
}

function activeTemplateName()
{
    $general = GeneralSetting::first(['active_template']);
    $template = $general->active_template;
    $sess = session()->get('template');
    if (trim($sess)) {
        $template = $sess;
    }
    return $template;
}


function loadReCaptcha()
{
    $reCaptcha = Extension::where('act', 'google-recaptcha2')->where('status', 1)->first();
    return $reCaptcha ? $reCaptcha->generateScript() : '';
}


function loadAnalytics()
{
    $analytics = Extension::where('act', 'google-analytics')->where('status', 1)->first();
    return $analytics ? $analytics->generateScript() : '';
}

function loadTawkto()
{
    $tawkto = Extension::where('act', 'tawk-chat')->where('status', 1)->first();
    return $tawkto ? $tawkto->generateScript() : '';
}


function loadFbComment()
{
    $comment = Extension::where('act', 'fb-comment')->where('status',1)->first();
    return  $comment ? $comment->generateScript() : '';
}







function getAmount($amount, $length = 2)
{
    $amount = round($amount, $length);
    return $amount + 0;
}

function showAmount($amount, $decimal = 2, $separate = true, $exceptZeros = false){
    $separator = '';
    if($separate){
        $separator = ',';
    }
    $printAmount = number_format($amount, $decimal, '.', $separator);
    if($exceptZeros){
    $exp = explode('.', $printAmount);
        if($exp[1]*1 == 0){
            $printAmount = $exp[0];
        }
    }
    return $printAmount;
}


function removeElement($array, $value)
{
    return array_diff($array, (is_array($value) ? $value : array($value)));
}

function cryptoQR($wallet)
{

    return "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$wallet&choe=UTF-8";
}

//moveable
function curlContent($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

//moveable
function curlPostContent($url, $arr = null)
{
    if ($arr) {
        $params = http_build_query($arr);
    } else {
        $params = '';
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


function inputTitle($text)
{
    return ucfirst(preg_replace("/[^A-Za-z0-9 ]/", ' ', $text));
}


function titleToKey($text)
{
    return strtolower(str_replace(' ', '_', $text));
}



//moveable
function getIpInfo()
{
    $ip = $_SERVER["REMOTE_ADDR"];

    //Deep detect ip
    if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)){
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)){
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }


    $xml = @simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . $ip);


    $country = @$xml->geoplugin_countryName;
    $city = @$xml->geoplugin_city;
    $area = @$xml->geoplugin_areaCode;
    $code = @$xml->geoplugin_countryCode;
    $long = @$xml->geoplugin_longitude;
    $lat = @$xml->geoplugin_latitude;

    $data['country'] = $country;
    $data['city'] = $city;
    $data['area'] = $area;
    $data['code'] = $code;
    $data['long'] = $long;
    $data['lat'] = $lat;
    $data['ip'] = request()->ip();
    $data['time'] = date('d-m-Y h:i:s A');


    return $data;
}

//moveable
function osBrowser(){
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $osPlatform = "Unknown OS Platform";
    $osArray = array(
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    );
    foreach ($osArray as $regex => $value) {
        if (preg_match($regex, $userAgent)) {
            $osPlatform = $value;
        }
    }
    $browser = "Unknown Browser";
    $browserArray = array(
        '/msie/i' => 'Internet Explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/netscape/i' => 'Netscape',
        '/maxthon/i' => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i' => 'Handheld Browser'
    );
    foreach ($browserArray as $regex => $value) {
        if (preg_match($regex, $userAgent)) {
            $browser = $value;
        }
    }

    $data['os_platform'] = $osPlatform;
    $data['browser'] = $browser;

    return $data;
}



//moveable



function notify($user, $type, $shortCodes = null)
{

    sendEmail($user, $type, $shortCodes);
    sendSms($user, $type, $shortCodes);
}



function sendSms($user, $type, $shortCodes = [])
{
    $general = GeneralSetting::first();
    $smsTemplate = SmsTemplate::where('act', $type)->where('sms_status', 1)->first();
    $gateway = $general->sms_config->name;
    $sendSms = new SendSms;
    if ($general->sn == 1 && $smsTemplate) {
        $template = $smsTemplate->sms_body;
        foreach ($shortCodes as $code => $value) {
            $template = shortCodeReplacer('{{' . $code . '}}', $value, $template);
        }
        $message = shortCodeReplacer("{{message}}", $template, $general->sms_api);
        $message = shortCodeReplacer("{{name}}", $user->username, $message);
        $sendSms->$gateway($user->mobile,$general->sitename,$message,$general->sms_config);
    }
}
function sendSmtpMail( $view ,$receiver_email, $receiver_name, $subject, $message, $general)
{

    $mail = new PHPMailer(true);

    try {

            //Server settings
            $mail->isSMTP();
        $mail->Host       = setting('mail_host')  ;
        $mail->SMTPAuth   = true;
        $mail->Username   = setting('MAIL_USERNAME');
        $mail->Password   = setting('MAIL_PASSWORD');
        if (setting('mail_encryption') == 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }else{
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }
        $mail->Port       = setting('mail_port');
        $mail->CharSet = 'UTF-8';


        //Recipients

        $mail->setFrom(  setting('mail_from_address')  , $general->title);
        $mail->addAddress($receiver_email, $receiver_name);
        $mail->addReplyTo(setting('mail_from_address'), $general->title);
        // Content
        $mail->isHTML(true);


        $mail->Subject = $subject;
        $mail->Body = $view;
        $mail->send();
    } catch (Exception $e) {
        throw new Exception($e);
    }
}
function sendEmail($email,$template, $data)
{
    $template = EmailTemplate::where('code',  $template)->first();

    //dd ($this->template);

    $general = Apptitle::first();
    $data['title'] = $general->title;
    $body = $template->body;
    $subject = $template->subject;
    foreach($data as $key => $value){
        $subject = str_replace('{{'.$key.'}}' , $data[$key] , $subject);
        $subject = str_replace('{{ '.$key.' }}' , $data[$key] , $subject);

        $body = str_replace('{{'.$key.'}}' , $data[$key] , $body);
        $body = str_replace('{{ '.$key.' }}' , $data[$key] , $body);
    }

    $data['emailBody']  =   $body;



    $config= [
             'mail_driver' => setting('mail_driver'),
             'mail_host' => setting('mail_host') ,
            'mail_port'=>setting('mail_host') ,
              'mail_from_address'=>setting('mail_from_address') ,
              'mail_from_name'=>setting('mail_from_name') ,
             'mail_encryption'=>setting('mail_encryption') ,
              'mail_username'=>setting('mail_username') ,
             'mail_password'=>setting('mail_password') ,
    ];

    // $userexits1 = Customer::where('email', $email)->count();

    // if($userexits == 1) {
    //     $user = Customer::where('email', $email)->first();
    //     $id = $user->id;
    // }
    //     else
    //         $id=0;

            $userexits = user::where('email', $email)->count();
            if($userexits == 1) {
                $user = user::where('email', $email)->first();
                $id = $user->id;
            }
                else
                    $id=0;



            $view= view('admin.email.template', $data);


    $emailLog = new EmailLog();
    $emailLog->user_id = $id;
    $emailLog->mail_sender = setting('mail_host');
    $emailLog->email_from = $data['title'] . ' ' . setting('mail_from_address');
    $emailLog->email_to =$email ;
    $emailLog->subject = $template->subject;
    $emailLog->message =  $data['emailBody'];
    $emailLog->save();


     if ($id==0  ) {
        sendSmtpMail($view , $email,'Guest', $subject, $data['emailBody'], $general);
    } else  {

         sendSmtpMail($view , $email, $user->name, $subject, $data['emailBody'], $general);

    }


    // function sendPhpMail($receiver_email, $receiver_name, $subject, $message, $general)
    // {
    //     $headers = "From: $general->sitename <$general->email_from> \r\n";
    //     $headers .= "Reply-To: $general->sitename <$general->email_from> \r\n";
    //     $headers .= "MIME-Version: 1.0\r\n";
    //     $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    //     @mail($receiver_email, $subject, $message , $headers);
    // }









    if (!function_exists('diffForHumans'))   {


    function diffForHumans($date)
    {
        $lang = session()->get('lang');
        Carbon::setlocale($lang);
        return Carbon::parse($date)->diffForHumans();
    }}
    if (!function_exists('showDateTime'))   {

    function showDateTime($date, $format = 'Y-m-d h:i A')
    {
        $lang = session()->get('lang');
        Carbon::setlocale($lang);
        return Carbon::parse($date)->translatedFormat($format);
    }
    }
}







