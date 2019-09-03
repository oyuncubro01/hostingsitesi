<?php
/*
=====================================================
 DataLife Engine - by SoftNews Media Group 
-----------------------------------------------------
 http://dle-news.ru/  |  Copyright (c) 2004-2014
=====================================================
 DLE.NET.TR  |  Copyright (c) 2014
-----------------------------------------------------
 http://dle.net.tr  -  info@dle.net.tr
=====================================================
*/

session_start();

@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );

define('DATALIFEENGINE', true);
define('ROOT_DIR', dirname (__FILE__));
define('ENGINE_DIR', ROOT_DIR.'/engine');

$distr_charset = "utf-8";
$db_charset = "utf8";

require_once(ROOT_DIR.'/language/Turkish/adminpanel.lng');
require_once(ENGINE_DIR.'/inc/include/functions.inc.php');


$skin_header = <<<HTML
<!doctype html>
<html>
<head>
  <meta charset="{$distr_charset}">
  <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
  <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
  <title>DataLife Engine - Kurulum</title>
  <link href="engine/skins/stylesheets/application.css" media="screen" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="engine/skins/javascripts/application.js"></script>
</head>
<body>
<script language="javascript" type="text/javascript">
<!--
var dle_act_lang   = ["Evet", "Hayır", "Giriş", "İptal", "Resim yükleme ve sunucudaki dosyalar"];
var cal_language   = {en:{months:['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'],dayOfWeek:["Pa", "Pt", "Sa", "Ça", "Pe", "Cu", "Ct"]}};
//-->
</script>
<nav class="navbar navbar-default navbar-inverse navbar-static-top" role="navigation">
  <div class="navbar-header">
    <a class="navbar-brand" href=""><img src="engine/skins/images/logo.png" />Yükleme Sihirbazı</a>
  </div>
</nav>
<div class="container">
  <div class="col-md-8 col-md-offset-2">
    <div class="padded">
	    <div style="margin-top: 50px;">
<!--MAIN area-->
HTML;


$skin_footer = <<<HTML
	 <!--MAIN area-->
    </div>
  </div>
</div>
</div>

</body>
</html>
HTML;

function msgbox($type, $title, $text, $back=FALSE){
global $lang, $skin_header, $skin_footer;

  if ($back) $back = "onclick=\"history.go(-1); return false;\""; else $back = "";

  echo $skin_header;

echo <<<HTML
<form action="install.php" method="get">
<div class="box">
  <div class="box-header">
    <div class="title">{$title}</div>
  </div>
  <div class="box-content">
	<div class="row box-section">
		{$text}
	</div>
	<div class="row box-section">	
		<input {$back} class="btn btn-green" type=submit value="Devam">
	</div>
	
  </div>
</div>
</form>
HTML;

  echo $skin_footer;

  exit();
}

function GetRandInt($max){

	if(function_exists('openssl_random_pseudo_bytes') && (version_compare(PHP_VERSION, '5.3.4') >= 0 || strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')) {
	     do{
	         $result = floor($max*(hexdec(bin2hex(openssl_random_pseudo_bytes(4)))/0xffffffff));
	     }while($result == $max);
	} else {

		$result = mt_rand( 0, $max );
	}

    return $result;
}

function generate_auth_key() {

    $arr = array('a','b','c','d','e','f',
                 'g','h','i','j','k','l',
                 'm','n','o','p','r','s',
                 't','u','v','x','y','z',
                 'A','B','C','D','E','F',
                 'G','H','I','J','K','L',
                 'M','N','O','P','R','S',
                 'T','U','V','X','Y','Z',
                 '1','2','3','4','5','6',
                 '7','8','9','0','.',',',
                 '(',')','[',']','!','?',
                 '&','^','%','@','*',' ',
                 '<','>','/','|','+','-',
                 '{','}','`','~','#',';',
                 '/','|','=',':','`');

    $key = "";
    for($i = 0; $i < 64; $i++)
    {
      $index = GetRandInt(count($arr))-1;
      $key .= $arr[$index];
    }
    return $key;
}

extract($_REQUEST, EXTR_SKIP);

if($_REQUEST['action'] == "function_check")
{

if ( !$_SESSION['dle_install'] ) msgbox( "", "Hata", "Sistem kurulumunu baştan yapmak için ana sayfaya dönün ve kurulumu yeniden başlatın: <br /><br /><a href=\"http://{$_SERVER['HTTP_HOST']}/install.php\">http://{$_SERVER['HTTP_HOST']}/install.php</a>" );

  echo $skin_header;

echo <<<HTML
<form method="get" action="">
<input type=hidden name="action" value="chmod_check">
<div class="box">
  <div class="box-header">
    <div class="title">Yüklü PHP Bileşenleri</div>
  </div>
  <div class="box-content">
	<div class="row box-section">
<table class="table table-normal table-bordered">
HTML;

echo"<tr>
<td width=\"250\">Minimum Gereksinimler</td>
<td colspan=2>Mevcut Değer</td>
</tr>";
 
$status = phpversion() < '5.2' ? '<font color=red><b>Hayır</b></font>' : '<font color=green><b>Evet</b></font>';

   echo"<tr>
         <td>PHP 5.2 veya üstü</td>
         <td colspan=2>$status</td>
         </tr>";

$status = function_exists('mysqli_connect') ? '<font color=green><b>Evet</b></font>' : '<font color=red><b>Hayır</b></font>';;

   echo"<tr>
         <td>MySQLi Desteği</td>
         <td colspan=2>$status</td>
         </tr>";


$status = extension_loaded('zlib') ? '<font color=green><b>Evet</b></font>' : '<font color=red><b>Hayır</b></font>';

   echo"<tr>
         <td>Zlib Sıkıştırma Desteği</td>
         <td colspan=2>$status</td>
         </tr>";

$status = extension_loaded('xml') ? '<font color=green><b>Evet</b></font>' : '<font color=red><b>Hayır</b></font>';

   echo"<tr>
         <td>XML Desteği</td>
         <td colspan=2>$status</td>
         </tr>";

$status = function_exists('iconv') ? '<font color=green><b>Evet</b></font>' : '<font color=red><b>Hayır</b></font>';;

   echo"<tr>
         <td>iconv Desteği</td>
         <td colspan=2>$status</td>
         </tr>";

   echo"<tr>
         <td colspan=3><br />Bu değerlerden herhangi biri kırmızı olarak görünüyorsa, durumu düzeltmek için şu adımları takip edin. Durumlarda betiğin minimum gereksinimleri, sistem içinde yanlış çalışmaya neden olabilir.<br /><br /></td>
         </tr>";

echo"<tr>
<td>Önerilen Ayarlar</td>
<td width=\"200\">Tavsiye Edilen Değer</td>
<td>Mevcut Değer</td>
</tr>";

$status = ini_get('safe_mode') ? '<font color=red><b>Aktif</b></font>' : '<font color=green><b>Pasif</b></font>';;

   echo"<tr>
         <td>Safe Mode</td>
         <td>Pasif</td>
         <td>$status</td>
         </tr>";

$status = ini_get('file_uploads') ? '<font color=green><b>Aktif</b></font>' : '<font color=red><b>Pasif</b></font>';;

   echo"<tr>
         <td>Dosya Yükleme</td>
         <td>Aktif</td>
         <td>$status</td>
         </tr>";

$status = ini_get('output_buffering') ? '<font color=red><b>Aktif</b></font>' : '<font color=green><b>Pasif</b></font>';;

   echo"<tr>
         <td>Çıktı Tamponlama</td>
         <td>Pasif</td>
         <td>$status</td>
         </tr>";

$status = ini_get('magic_quotes_runtime') ? '<font color=red><b>Aktif</b></font>' : '<font color=green><b>Pasif</b></font>';;

   echo"<tr>
         <td>Magic Quotes Runtime</td>
         <td>Pasif</td>
         <td>$status</td>
         </tr>";

$status = ini_get('register_globals') ? '<font color=red><b>Aktif</b></font>' : '<font color=green><b>Pasif</b></font>';;

   echo"<tr>
         <td>Register Globals</td>
         <td>Pasif</td>
         <td>$status</td>
         </tr>";

$status = ini_get('session.auto_start') ? '<font color=red><b>Aktif</b></font>' : '<font color=green><b>Pasif</b></font>';;

   echo"<tr>
         <td>Session auto start</td>
         <td>Pasif</td>
         <td>$status</td>
         </tr>";

   echo"<tr>
         <td colspan=3 class=\"note lagre\"><br />Bu ayarlar, tam uyumluluk için tavsiye edilen ayarlardır, ancak ayarlar uyuşmuyor olsa bile scripti çalıştırabilirsiniz.<br /><br /></td>
         </tr>";

echo <<<HTML
</table>
	</div>
	<div class="row box-section">	
		<input class="btn btn-green" type=submit value="Devam">
	</div>
	
  </div>
</div></form>
HTML;

} 
// ********************************************************************************
// Проверка прав на запись
// ********************************************************************************
elseif($_REQUEST['action'] == "chmod_check")
{

if ( !$_SESSION['dle_install'] ) msgbox( "", "Hata", "Sistem kurulumunu baştan yapmak için ana sayfaya dönün ve kurulumu yeniden başlatın: <br /><br /><a href=\"http://{$_SERVER['HTTP_HOST']}/install.php\">http://{$_SERVER['HTTP_HOST']}/install.php</a>" );

  echo $skin_header;

echo <<<HTML
<form method="get" action="">
<input type=hidden name="action" value="doconfig">
<div class="box">
  <div class="box-header">
    <div class="title">Önemli Sistem Dosyaları Denetimi</div>
  </div>
  <div class="box-content">
	<div class="row box-section">
<table class="table table-normal table-bordered">
HTML;

echo"<thead><tr>
<td>Klasör/Dosya</td>
<td width=\"100\">CHMOD</td>
<td width=\"100\">Durum</td></tr></thead><tbody>";
 
$important_files = array(
'./backup/',
'./engine/data/',
'./engine/cache/',
'./engine/cache/system/',
'./uploads/',
'./uploads/files/',
'./uploads/fotos/',
'./uploads/posts/',
'./uploads/posts/thumbs/',
'./uploads/thumbs/',
'./templates/',
'./templates/Default/',
);


$chmod_errors = 0;
$not_found_errors = 0;
    foreach($important_files as $file){

        if(!file_exists($file)){
            $file_status = "<font color=red>bulunamadı!</font>";
            $not_found_errors ++;
        }
        elseif(is_writable($file)){
            $file_status = "<font color=green>yazılabilir</font>";
        }
        else{
            @chmod($file, 0777);
            if(is_writable($file)){
                $file_status = "<font color=green>yazılabilir</font>";
            }else{
                @chmod("$file", 0755);
                if(is_writable($file)){
                    $file_status = "<font color=green>yazılabilir</font>";
                }else{
                    $file_status = "<font color=red>yazılamaz</font>";
                    $chmod_errors ++;
                }
            }
        }
        $chmod_value = @decoct(@fileperms($file)) % 1000;

    echo"<tr>
         <td>$file</td>
         <td>$chmod_value</td>
         <td>$file_status</td>
         </tr>";
    }
if($chmod_errors == 0 and $not_found_errors == 0){
$status_report = 'Dosya doğrulaması başarıyla tamamlandı. Kuruluma devam edilebilir!!';
}else{
if($chmod_errors > 0){
$status_report = "<font color=red>Dikkat!!!</font><br /><br />Kontrol esnasında bazı hatalar tespit edildi: <b>$chmod_errors</b>. Dosyaya erişim engelli.<br />Herhangi bir FTP programı aracılığıyla CHMOD 666 olan dosyaları ve klasörleri CHMOD 777 olarak değiştiriniz.<br /><br />Değişiklik yapılana dek kuruluma devam etmeniz <font color=red><b>Kesinlikle Önerilmez</b></font>.<br />";
}
if($not_found_errors > 0){
$status_report .= "<font color=red>Dikkat!!!</font><br />Kontrol esnasında bazı hatalar tespit edildi: <b>$not_found_errors</b>. Dosya Bulunamadı!<br /><br />Değişiklik yapılana dek kuruluma devam etmeniz <font color=red><b>Önerilmez</b></font>.<br />";
}
}

echo"<tr><td height=\"25\" colspan=3>&nbsp;&nbsp;Doğrulama Durumu</td></tr><tr><td style=\"padding: 5px\" colspan=3>$status_report</td></tr>";    

echo <<<HTML
</tbody></table>
	</div>
	<div class="row box-section">	
		<input class="btn btn-green" type=submit value="Devam">
	</div>
	
  </div>
</div></form>
HTML;

} elseif($_REQUEST['action'] == "doconfig")
{

if ( !$_SESSION['dle_install'] ) msgbox( "", "Hata", "Sistem kurulumunu baştan yapmak için ana sayfaya dönün ve kurulumu yeniden başlatın: <br /><br /><a href=\"http://{$_SERVER['HTTP_HOST']}/install.php\">http://{$_SERVER['HTTP_HOST']}/install.php</a>" );


$url  = preg_replace( "'/install.php'", "", $_SERVER['HTTP_REFERER']);
$url  = preg_replace( "'\?(.*)'", "", $url);
if(substr("$url", -1) == "/"){ $url = substr($url, 0, -1); }

  echo $skin_header;

  echo <<<HTML
<form method="post" action="">
<input type=hidden name="action" value="doinstall">
<div class="box">
  <div class="box-header">
    <div class="title">Sistem Yapılandırması</div>
  </div>
  <div class="box-content">
	<div class="row box-section">
<table width="100%">
HTML;


echo'<tr>
<td width="175" style="padding: 5px;">Site URL\'i:
<td style="padding: 5px;"><input name=url value="'.$url.'/" size=38 type=text><br><span class="note large">Belirtilen dosya yolu sonuna, <font color="red">/</font> eklemeniz zorunludur </span></tr>
<tr><td colspan="3" height="40">&nbsp;&nbsp;<b>MySQL Sunucusu Erişim Bilgileri</b><td></tr>
<tr><td style="padding: 5px;">MySQL Sunucusu:<td style="padding: 5px;"><input type=text size="28" name="dbhost" value="localhost"></tr>
<tr><td style="padding: 5px;">Veritabanı Adı:<td style="padding: 5px;"><input type=text size="28" name="dbname"></tr>
<tr><td style="padding: 5px;">Kullanıcı Adı:<td style="padding: 5px;"><input type=text size="28" name="dbuser"></tr>
<tr><td style="padding: 5px;">Şifre:<td style="padding: 5px;"><input type=text size="28" name="dbpasswd"></tr>
<tr><td style="padding: 5px;">Tablo Öneki:<td style="padding: 5px;"><input type=text size="28" name="dbprefix" value="dle"> <span class="note large">Ne olduğunu bilmiyorsanız, lütfen bu ayarı değiştirmeyiniz</span></tr>
<tr><td colspan="3"  height="40">&nbsp;&nbsp;<b>Yönetim Paneli İçin Giriş Bilgileri</b><td></tr>
<tr><td style="padding: 5px;">Yönetici Adı:<td style="padding: 5px;"><input type=text size="28" name="reg_username" ></tr>
<tr><td style="padding: 5px;">Şifre:<td style="padding: 5px;"><input type=password size="28" name="reg_password1"> <span class="note large">Şifrenizi <b>unutmayın</b>!</span></tr>
<tr><td style="padding: 5px;">Şifreyi Tekrar Girin:<td style="padding: 5px;"><input type=password size="28" name="reg_password2"></tr>
<tr><td style="padding: 5px;">E-mail:<td style="padding: 5px;"><input type=text size="28" name="reg_email"></tr>
<tr><td colspan="3"  height="40">&nbsp;&nbsp;<b>Ek Ayarlar</b><td></tr>
<tr><td style="padding: 5px;">Rewrite Desteği:
<td style="padding: 5px;">
<select class=rating name="alt_url"><option value="yes">Evet</option><option value="no">Hayır</option></select>&nbsp;&nbsp;<span class="note large">SEO Desteğini aktifleştirmek veya pasifleştirmek için <b>.htaccess</b> dosyası ana dizinde olmalıdır, sunucunuz <i><b>rewrite</b></i> özelliğini desteklemiyorsa Hayır olarak seçim yapın.</span>
</tr>';

echo <<<HTML
</table>
	</div>
	<div class="row box-section">	
		<input class="btn btn-green" type=submit value="Devam">
	</div>
	
  </div>
</div></form>
HTML;

}
// ********************************************************************************
// Do Install
// ********************************************************************************
elseif($_REQUEST['action'] == "doinstall")
{

	if ( !$_SESSION['dle_install'] ) msgbox( "", "Hata", "Sistem kurulumunu baştan yapmak için ana sayfaya dönün ve kurulumu yeniden başlatın: <br /><br /><a href=\"http://{$_SERVER['HTTP_HOST']}/install.php\">http://{$_SERVER['HTTP_HOST']}/install.php</a>" );

    if(!$reg_username or !$reg_email or !$reg_password1 or !$url or $reg_password1 != $reg_password2){ msgbox("error", "Hata!!!" ,"Gerekli alanları doldurun!", "history.go(-1)"); }
	if (preg_match("/[\||\'|\<|\>|\"|\!|\$|\@|\&\~\*\+]/", $reg_username))
	{	
		msgbox("error", "Hata!!!" ,"Kayıt için bu yönetici adı kabul edilemez!!", "history.go(-1)");		 
	}

    $reg_password = md5(md5($reg_password1));

	$url = htmlspecialchars( $url, ENT_QUOTES, 'ISO-8859-1');
	$reg_email = htmlspecialchars( $reg_email, ENT_QUOTES, 'ISO-8859-1');
	$alt_url = intval( $alt_url );
	$url = str_replace( "$", "&#036;", $url );
	$reg_email = str_replace( "$", "&#036;", $reg_email );
	$timezone = date_default_timezone_get();


$config = <<<HTML
<?PHP

//System Configurations

\$config = array (

'version_id' => "10.3",

'home_title' => "DataLife Engine",

'http_home_url' => "{$url}",

'charset' => "{$distr_charset}",

'admin_mail' => "{$reg_email}",

'description' => "Demo DataLife Engine Sayfası",

'keywords' => "DataLife, Engine, CMS, PHP Engine",

'date_adjust' => "{$timezone}",

'site_offline' => "0",

'allow_alt_url' => "{$alt_url}",

'langs' => "Turkish",

'skin' => "Default",

'allow_gzip' => "0",

'allow_admin_wysiwyg' => "0",

'allow_static_wysiwyg' => "0",

'news_number' => "10",

'smilies' => "wink,winked,smile,am,belay,feel,fellow,laughing,lol,love,no,recourse,request,sad,tongue,wassat,crying,what,bully,angry",

'timestamp_active' => "j-m-Y, H:i",

'news_sort' => "date",

'news_msort' => "DESC",

'hide_full_link' => "0",

'allow_site_wysiwyg' => "0",

'allow_comments' => "1",

'comm_nummers' => "30",

'comm_msort' => "ASC",

'flood_time' => "30",

'auto_wrap' => "80",

'timestamp_comment' => "j F Y H:i",

'allow_comments_wysiwyg' => "0",

'allow_registration' => "1",

'allow_cache' => "0",

'allow_votes' => "1",

'allow_topnews' => "1",

'allow_read_count' => "1",

'allow_calendar' => "1",

'allow_archives' => "1",

'files_allow' => "1",

'files_count' => "1",

'reg_group' => "4",

'registration_type' => "0",

'allow_sec_code' => "1",

'allow_skin_change' => "1",

'max_users' => "0",

'max_users_day' => "0",

'max_up_size' => "200",

'max_image_days' => "2",

'allow_watermark' => "1",

'max_watermark' => "150",

'max_image' => "200",

'jpeg_quality' => "85",

'files_antileech' => "1",

'allow_banner' => "1",

'log_hash' => "0",

'show_sub_cats' => "1",

'tag_img_width' => "0",

'mail_metod' => "php",

'smtp_host' => "localhost",

'smtp_port' => "25",

'smtp_user' => "",

'smtp_pass' => "",

'mail_bcc' => "0",

'speedbar' => "1",

'extra_login' => "0",

'image_align' => "left",

'ip_control' => "1",

'cache_count' => "0",

'related_news' => "1",

'no_date' => "1",

'mail_news' => "1",

'mail_comments' => "1",

'admin_path' => "admin.php",

'rss_informer' => "1",

'allow_cmod' => "0",

'max_up_side' => "0",

'files_force' => "1",

'short_rating' => "1",

'full_search' => "0",

'allow_multi_category' => "1",

'short_title' => "Demo Site",

'allow_rss' => "1",

'rss_mtype' => "0",

'rss_number' => "10",

'rss_format' => "1",

'comments_maxlen' => "3000",

'offline_reason' => "Sitedeki tüm çalışmalar tamamlanınca açılacaktır, Şuanda güncelleme yapılmaktadır.<br /><br />Rahatsızlık verdiğimiz için özür dileriz.",

'catalog_sort' => "date",

'catalog_msort' => "DESC",

'related_number' => "5",

'seo_type' => "2",

'max_moderation' => "0",

'allow_quick_wysiwyg' => "0",

'sec_addnews' => "2",

'mail_pm' => "1",

'allow_change_sort' => "1",

'registration_rules' => "1",

'allow_tags' => "1",

'allow_add_tags' => "1",

'allow_fixed' => "1",

'max_file_count' => "0",

'allow_smartphone' => "0",

'allow_smart_images' => "0",

'allow_smart_video' => "0",

'allow_search_print' => "1",

'allow_search_link' => "1",

'allow_smart_format' => "1",

'thumb_dimming' => "0",

'thumb_gallery' => "1",

'max_comments_days' => "0",

'allow_combine' => "1",

'allow_subscribe' => "1",

'parse_links' => "0",

't_seite' => "0",

'comments_minlen' => "10",

'js_min' => "0",

'outlinetype' => "0",

'fast_search' => "1",

'login_log' => "5",

'allow_recaptcha' => "0",

'recaptcha_public_key' => "6LfoOroSAAAAAEg7PViyas0nRqCN9nIztKxWcDp_",

'recaptcha_private_key' => "6LfoOroSAAAAAMgMr_BTRMZy20PFir0iGT2OQYZJ",

'recaptcha_theme' => "clean",

'search_number' => "10",

'news_navigation' => "1",

'mail_additional' => "",

'smtp_mail' => "",

'seo_control' => "0",

'news_restricted' => "0",

'comments_restricted' => "0",

'auth_metod' => "0",

'comments_ajax' => "0",

'create_catalog' => "0",

'mobile_news' => "10",

'reg_question' => "0",

'smtp_helo' => "HELO",

'news_future' => "0",

'cache_type' => "0",

'memcache_server' => "localhost:11211",

'allow_comments_cache' => "1",

'reg_multi_ip' => "1",

'top_number' => "10",

'tags_number' => "40",

'mail_title' => "",

'o_seite' => "0",

'online_status' => "1",

'avatar_size' => "100",

'allow_share' => "1",

'auth_domain' => "1",

'start_site' => "1",

'clear_cache' => "0",

'use_admin_mail' => "0",

'allow_complaint_mail' => "0",

'spam_api_key' => "",

'create_metatags' => '1',

'admin_allowed_ip' => '',

'related_only_cats' => '0',

'allow_links' => '1',

'comments_lazyload' => '0',

'category_separator' => '/',

'speedbar_separator' => '&raquo;',

'adminlog_maxdays' => '30',

'allow_social' => '0',

'medium_image' => '450',

'key' => '',

);

?>
HTML;

$dbhost = str_replace ('"', '\"', str_replace ("$", "\\$", $dbhost) );
$dbname = str_replace ('"', '\"', str_replace ("$", "\\$", $dbname) );
$dbuser = str_replace ('"', '\"', str_replace ("$", "\\$", $dbuser) );
$dbpasswd = str_replace ('"', '\"', str_replace ("$", "\\$", $dbpasswd) );
$dbprefix = str_replace ('"', '\"', str_replace ("$", "\\$", $dbprefix) );
$auth_key = generate_auth_key();

$dbconfig = <<<HTML
<?PHP

define ("DBHOST", "{$dbhost}"); 

define ("DBNAME", "{$dbname}");

define ("DBUSER", "{$dbuser}");

define ("DBPASS", "{$dbpasswd}");  

define ("PREFIX", "{$dbprefix}"); 

define ("USERPREFIX", "{$dbprefix}");

define ("COLLATE", "{$db_charset}"); 

define('SECURE_AUTH_KEY', '{$auth_key}');

\$db = new db;
 
?>
HTML;


$video_config = <<<HTML
<?PHP

//Videoplayers Configurations

\$video_config = array (

'width' => "425",

'height' => "325",

'play' => "false",

'progressBarColor' => "0xFFFFFF",

'flv_watermark' => "1",

'tube_related' => "0",

'tube_dle' => "0",

'startframe' => "0",

'progressBarColor' => "0xFFFFFF",

'preview' => "1",

'buffer' => "3",

'autohide' => "0",

'flv_watermark_pos' => "left",

'flv_watermark_al' => "1",

'youtube_q' => "hd720",

'play' => "0",

'fullsizeview' => "2",

'audio_width' => "300",

'use_html5' => '0',

);

?>
HTML;


$social_config = <<<HTML
<?PHP

//Social Configurations

\$social_config = array (

'vk' => '0',

'vkid' => '',

'vksecret' => '',

'od' => '0',

'odid' => '',

'odpublic' => '',

'odsecret' => '',

'fc' => '0',

'fcid' => '',

'fcsecret' => '',

'google' => '0',

'googleid' => '',

'googlesecret' => '',

'mailru' => '0',

'mailruid' => '',

'mailrusecret' => '',

'yandex' => '0',

'yandexid' => '',

'yandexsecret' => '',

);

?>
HTML;

$con_file = fopen("engine/data/config.php", "w+") or die("Üzgünüz, ama dosya oluşturamazsınız <b>.engine/data/config.php</b>.<br />CHMOD Ayarlarını kontrol edin!");
fwrite($con_file, $config);
fclose($con_file);
@chmod("engine/data/config.php", 0666);

$con_file = fopen("engine/data/dbconfig.php", "w+") or die("Üzgünüz, ama dosya oluşturamazsınız <b>.engine/data/dbconfig.php</b>.<br />CHMOD Ayarlarını kontrol edin!");
fwrite($con_file, $dbconfig);
fclose($con_file);
@chmod("engine/data/dbconfig.php", 0666);

$con_file = fopen("engine/data/videoconfig.php", "w+") or die("Üzgünüz, ama dosya oluşturamazsınız <b>.engine/data/videoconfig.php</b>.<br />CHMOD Ayarlarını kontrol edin!");
fwrite($con_file, $video_config);
fclose($con_file);
@chmod("engine/data/videoconfig.php", 0666);

$con_file = fopen("engine/data/socialconfig.php", "w+") or die("Üzgünüz, ama dosya oluşturamazsınız <b>.engine/data/socialconfig.php</b>.<br />CHMOD Ayarlarını kontrol edin!");
fwrite($con_file, $social_config);
fclose($con_file);
@chmod("engine/data/socialconfig.php", 0666);

$con_file = fopen("engine/data/wordfilter.db.php", "w+") or die("Üzgünüz, ama dosya oluşturamazsınız <b>.engine/data/wordfilter.db.php</b>.<br />CHMOD Ayarlarını kontrol edin!");
fwrite($con_file, '');
fclose($con_file);
@chmod("engine/data/wordfilter.db.php", 0666);

$con_file = fopen("engine/data/xfields.txt", "w+") or die("Üzgünüz, ama dosya oluşturamazsınız <b>.engine/data/xfields.txt</b>.<br />CHMOD Ayarlarını kontrol edin!");
fwrite($con_file, '');
fclose($con_file);
@chmod("engine/data/xfields.txt", 0666);

$con_file = fopen("engine/data/xprofile.txt", "w+") or die("Üzgünüz, ama dosya oluşturamazsınız <b>.engine/data/xprofile.txt</b>.<br />CHMOD Ayarlarını kontrol edin!");
fwrite($con_file, '');
fclose($con_file);
@chmod("engine/data/xprofile.txt", 0666);

@unlink(ENGINE_DIR.'/cache/system/usergroup.php');
@unlink(ENGINE_DIR.'/cache/system/vote.php');
@unlink(ENGINE_DIR.'/cache/system/banners.php');
@unlink(ENGINE_DIR.'/cache/system/category.php');
@unlink(ENGINE_DIR.'/cache/system/banned.php');
@unlink(ENGINE_DIR.'/cache/system/cron.php');
@unlink(ENGINE_DIR.'/cache/system/informers.php');
@unlink(ENGINE_DIR.'/data/snap.db');

define ("PREFIX", $dbprefix);
define ("COLLATE", $db_charset);

$tableSchema = array();

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_category";

$tableSchema[] = "CREATE TABLE " . PREFIX . "_category (
  `id` mediumint(8) NOT NULL auto_increment,
  `parentid` mediumint(8) NOT NULL default '0',
  `posi` mediumint(8) NOT NULL default '1',
  `name` varchar(50) NOT NULL default '',
  `alt_name` varchar(50) NOT NULL default '',
  `icon` varchar(200) NOT NULL default '',
  `skin` varchar(50) NOT NULL default '',
  `descr` varchar(200) NOT NULL default '',
  `keywords` text NOT NULL,
  `news_sort` varchar(10) NOT NULL default '',
  `news_msort` varchar(4) NOT NULL default '',
  `news_number` smallint(5) NOT NULL default '0',
  `short_tpl` varchar(40) NOT NULL default '',
  `full_tpl` varchar(40) NOT NULL default '',
  `metatitle` varchar(255) NOT NULL default '',
  `show_sub` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
  ) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_comments";

$tableSchema[] = "CREATE TABLE " . PREFIX . "_comments (
  `id` int(10) unsigned NOT NULL auto_increment,
  `post_id` int(11) NOT NULL default '0',
  `user_id` mediumint(8) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `autor` varchar(40) NOT NULL default '',
  `email` varchar(40) NOT NULL default '',
  `text` text NOT NULL,
  `ip` varchar(16) NOT NULL default '',
  `is_register` tinyint(1) NOT NULL default '0',
  `approve` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `post_id` (`post_id`),
  KEY `approve` (`approve`),
  FULLTEXT KEY `text` (`text`)
  ) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_email";

$tableSchema[] = "CREATE TABLE " . PREFIX . "_email (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(10) NOT NULL default '',
  `template` text NOT NULL,
  PRIMARY KEY  (`id`)
  ) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";


$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_flood";

$tableSchema[] = "CREATE TABLE  " . PREFIX . "_flood (
  `f_id` int(11) unsigned NOT NULL auto_increment,
  `ip` varchar(40) NOT NULL default '',
  `id` varchar(20) NOT NULL default '',
  `flag` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`f_id`),
  KEY `ip` (`ip`),
  KEY `id` (`id`),
  KEY `flag` (`flag`)
  ) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_images";

$tableSchema[] = "CREATE TABLE " . PREFIX . "_images (
  `id` int(10) unsigned NOT NULL auto_increment,
  `images` text NOT NULL,
  `news_id` int(10) NOT NULL default '0',
  `author` varchar(40) NOT NULL default '',
  `date` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `author` (`author`),
  KEY `news_id` (`news_id`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_logs";

$tableSchema[] = "CREATE TABLE " . PREFIX . "_logs (
  `id` int(10) unsigned NOT NULL auto_increment,
  `news_id` int(10) NOT NULL default '0',
  `member` varchar(40) NOT NULL default '',
  `ip` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `news_id` (`news_id`),
  KEY `member` (`member`),
  KEY `ip` (`ip`)
  ) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_vote";

$tableSchema[] = "CREATE TABLE " . PREFIX . "_vote (
  `id` mediumint(8) NOT NULL auto_increment,
  `category` text NOT NULL,
  `vote_num` mediumint(8) NOT NULL default '0',
  `date` varchar(25) NOT NULL default '0',
  `title` varchar(200) NOT NULL default '',
  `body` text NOT NULL,
  `approve` tinyint(1) NOT NULL default '1',
  `start` varchar(15) NOT NULL default '',
  `end` varchar(15) NOT NULL default '',
  `grouplevel` varchar(250) NOT NULL default 'all',
  PRIMARY KEY  (`id`),
  KEY `approve` (`approve`)
  ) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_vote_result";

$tableSchema[] = "CREATE TABLE " . PREFIX . "_vote_result (
  `id` int(10) NOT NULL auto_increment,
  `ip` varchar(16) NOT NULL default '',
  `name` varchar(40) NOT NULL default '',
  `vote_id` mediumint(8) NOT NULL default '0',
  `answer` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `answer` (`answer`),
  KEY `vote_id` (`vote_id`),
  KEY `ip` (`ip`),
  KEY `name` (`name`)
  ) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_lostdb";

$tableSchema[] = "CREATE TABLE " . PREFIX . "_lostdb (
  `id` mediumint(8) NOT NULL auto_increment,
  `lostname` mediumint(8) NOT NULL default '0',
  `lostid` varchar( 40 ) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `lostid` (`lostid`)
  ) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_pm";

$tableSchema[] = "CREATE TABLE " . PREFIX . "_pm (
  `id` int(11) unsigned NOT NULL auto_increment,
  `subj` varchar(255) NOT NULL default '',
  `text` text NOT NULL,
  `user` MEDIUMINT(8) NOT NULL default '0',
  `user_from` varchar(40) NOT NULL default '',
  `date` int(11) unsigned NOT NULL default '0',
  `pm_read` TINYINT(1) NOT NULL default '0',
  `folder` varchar(10) NOT NULL default '',
  `reply` tinyint(1) NOT NULL default '0',
  `sendid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `folder` (`folder`),
  KEY `user` (`user`),
  KEY `user_from` (`user_from`),
  KEY `pm_read` (`pm_read`)
  ) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_post";

$tableSchema[] = "CREATE TABLE " . PREFIX . "_post (
  `id` int(11) NOT NULL auto_increment,
  `autor` varchar(40) NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `short_story` text NOT NULL,
  `full_story` text NOT NULL,
  `xfields` text NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `descr` varchar(200) NOT NULL default '',
  `keywords` text NOT NULL,
  `category` varchar(200) NOT NULL default '0',
  `alt_name` varchar(200) NOT NULL default '',
  `comm_num` mediumint(8) unsigned NOT NULL default '0',
  `allow_comm` tinyint(1) NOT NULL default '1',
  `allow_main` tinyint(1) unsigned NOT NULL default '1',
  `approve` tinyint(1) NOT NULL default '0',
  `fixed` tinyint(1) NOT NULL default '0',
  `allow_br` tinyint(1) NOT NULL default '1',
  `symbol` varchar(3) NOT NULL default '',
  `tags` VARCHAR(255) NOT NULL default '',
  `metatitle` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `autor` (`autor`),
  KEY `alt_name` (`alt_name`),
  KEY `category` (`category`),
  KEY `approve` (`approve`),
  KEY `allow_main` (`allow_main`),
  KEY `date` (`date`),
  KEY `symbol` (`symbol`),
  KEY `comm_num` (`comm_num`),
  KEY `tags` (`tags`),
  KEY `fixed` (`fixed`),
  FULLTEXT KEY `short_story` (`short_story`,`full_story`,`xfields`,`title`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_post_extras";

$tableSchema[] = "CREATE TABLE " . PREFIX . "_post_extras (
  `eid` int(11) NOT NULL AUTO_INCREMENT,
  `news_id` int(11) NOT NULL DEFAULT '0',
  `news_read` int(11) NOT NULL DEFAULT '0',
  `allow_rate` tinyint(1) NOT NULL DEFAULT '1',
  `rating` mediumint(8) NOT NULL DEFAULT '0',
  `vote_num` mediumint(8) NOT NULL DEFAULT '0',
  `votes` tinyint(1) NOT NULL DEFAULT '0',
  `view_edit` tinyint(1) NOT NULL DEFAULT '0',
  `disable_index` tinyint(1) NOT NULL DEFAULT '0',
  `related_ids` varchar(255) NOT NULL DEFAULT '',
  `access` varchar(150) NOT NULL DEFAULT '',
  `editdate` int(11) NOT NULL DEFAULT '0',
  `editor` varchar(40) NOT NULL DEFAULT '',
  `reason` varchar(255) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`eid`),
  UNIQUE KEY `news_id` (`news_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_static";

$tableSchema[] = "CREATE TABLE " . PREFIX . "_static (
  `id` MEDIUMINT(8) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `descr` varchar(255) NOT NULL default '',
  `template` text NOT NULL,
  `allow_br` tinyint(1) NOT NULL default '0',
  `allow_template` tinyint(1) NOT NULL default '0',
  `grouplevel` varchar(100) NOT NULL default 'all',
  `tpl` varchar(40) NOT NULL default '',
  `metadescr` varchar(200) NOT NULL default '',
  `metakeys` text NOT NULL,
  `views` mediumint(8) NOT NULL default '0',
  `template_folder` varchar(50) NOT NULL default '',
  `date` int(11) unsigned NOT NULL default '0',
  `metatitle` varchar(255) NOT NULL default '',
  `allow_count` tinyint(1) NOT NULL default '1',
  `sitemap` tinyint(1) NOT NULL default '1',
  `disable_index` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  FULLTEXT KEY `template` (`template`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_users";

$tableSchema[] = "CREATE TABLE " . PREFIX . "_users (
  `email` varchar(50) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `name` varchar(40) NOT NULL default '',
  `user_id` int(11) NOT NULL auto_increment,
  `news_num` mediumint(8) NOT NULL default '0',
  `comm_num` mediumint(8) NOT NULL default '0',
  `user_group` smallint(5) NOT NULL default '4',
  `lastdate` varchar(20) default NULL,
  `reg_date` varchar(20) default NULL,
  `banned` varchar(5) NOT NULL default '',
  `allow_mail` tinyint(1) NOT NULL default '1',
  `info` text NOT NULL,
  `signature` text NOT NULL,
  `foto` varchar(50) NOT NULL default '',
  `fullname` varchar(100) NOT NULL default '',
  `land` varchar(100) NOT NULL default '',
  `favorites` text NOT NULL,
  `pm_all` smallint(5) NOT NULL default '0',
  `pm_unread` smallint(5) NOT NULL default '0',
  `time_limit` varchar(20) NOT NULL default '',
  `xfields` text NOT NULL,
  `allowed_ip` varchar(255) NOT NULL default '',
  `hash` varchar(32) NOT NULL default '',
  `logged_ip` varchar(16) NOT NULL default '',
  `restricted` TINYINT(1) NOT NULL default '0',
  `restricted_days` SMALLINT(4) NOT NULL default '0',
  `restricted_date` VARCHAR(15) NOT NULL default '',
  `timezone` VARCHAR(100) NOT NULL default '',
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_banned";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_banned (
  `id` smallint(5) NOT NULL auto_increment,
  `users_id` mediumint(8) NOT NULL default '0',
  `descr` text NOT NULL,
  `date` varchar(15) NOT NULL default '',
  `days` smallint(4) NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`users_id`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_files";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_files (
  `id`  MEDIUMINT(8) NOT NULL auto_increment,
  `news_id` int(10) NOT NULL default '0',
  `name` varchar(250) NOT NULL default '',
  `onserver` varchar(250) NOT NULL default '',
  `author` varchar(40) NOT NULL default '',
  `date` varchar(15) NOT NULL default '',
  `dcount` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `news_id` (`news_id`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_usergroups";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_usergroups (
  `id` smallint(5) NOT NULL auto_increment,
  `group_name` varchar(32) NOT NULL default '',
  `allow_cats` text NOT NULL,
  `allow_adds` tinyint(1) NOT NULL default '1',
  `cat_add` text NOT NULL,
  `allow_admin` tinyint(1) NOT NULL default '0',
  `allow_addc` tinyint(1) NOT NULL default '0',
  `allow_editc` tinyint(1) NOT NULL default '0',
  `allow_delc` tinyint(1) NOT NULL default '0',
  `edit_allc` tinyint(1) NOT NULL default '0',
  `del_allc` tinyint(1) NOT NULL default '0',
  `moderation` tinyint(1) NOT NULL default '0',
  `allow_all_edit` tinyint(1) NOT NULL default '0',
  `allow_edit` tinyint(1) NOT NULL default '0',
  `allow_pm` tinyint(1) NOT NULL default '0',
  `max_pm` smallint(5) NOT NULL default '0',
  `max_foto` VARCHAR(10) NOT NULL default '',
  `allow_files` tinyint(1) NOT NULL default '0',
  `allow_hide` tinyint(1) NOT NULL default '1',
  `allow_short` tinyint(1) NOT NULL default '0',
  `time_limit` tinyint(1) NOT NULL default '0',
  `rid` smallint(5) NOT NULL default '0',
  `allow_fixed` tinyint(1) NOT NULL default '0',
  `allow_feed`  tinyint(1) NOT NULL default '1',
  `allow_search`  tinyint(1) NOT NULL default '1',
  `allow_poll`  tinyint(1) NOT NULL default '1',
  `allow_main`  tinyint(1) NOT NULL default '1',
  `captcha`  tinyint(1) NOT NULL default '0',
  `icon` varchar(200) NOT NULL default '',
  `allow_modc`  tinyint(1) NOT NULL default '0',
  `allow_rating` tinyint(1) NOT NULL default '1',
  `allow_offline` tinyint(1) NOT NULL default '0',
  `allow_image_upload` tinyint(1) NOT NULL default '0',
  `allow_file_upload` tinyint(1) NOT NULL default '0',
  `allow_signature` tinyint(1) NOT NULL default '0',
  `allow_url` tinyint(1) NOT NULL default '1',
  `news_sec_code` tinyint(1) NOT NULL default '1',
  `allow_image` tinyint(1) NOT NULL default '0',
  `max_signature` SMALLINT(6) NOT NULL default '0',
  `max_info` SMALLINT(6) NOT NULL default '0',
  `admin_addnews` tinyint(1) NOT NULL default '0',
  `admin_editnews` tinyint(1) NOT NULL default '0',
  `admin_comments` tinyint(1) NOT NULL default '0',
  `admin_categories` tinyint(1) NOT NULL default '0',
  `admin_editusers` tinyint(1) NOT NULL default '0',
  `admin_wordfilter` tinyint(1) NOT NULL default '0',
  `admin_xfields` tinyint(1) NOT NULL default '0',
  `admin_userfields` tinyint(1) NOT NULL default '0',
  `admin_static` tinyint(1) NOT NULL default '0',
  `admin_editvote` tinyint(1) NOT NULL default '0',
  `admin_newsletter` tinyint(1) NOT NULL default '0',
  `admin_blockip` tinyint(1) NOT NULL default '0',
  `admin_banners` tinyint(1) NOT NULL default '0',
  `admin_rss` tinyint(1) NOT NULL default '0',
  `admin_iptools` tinyint(1) NOT NULL default '0',
  `admin_rssinform` tinyint(1) NOT NULL default '0',
  `admin_googlemap` tinyint(1) NOT NULL default '0',
  `allow_html` tinyint(1) NOT NULL default '1',
  `group_prefix` text NOT NULL,
  `group_suffix` text NOT NULL,
  `allow_subscribe` tinyint(1) NOT NULL default '0',
  `allow_image_size` tinyint(1) NOT NULL default '0',
  `cat_allow_addnews` text NOT NULL,
  `flood_news` smallint(6) NOT NULL default '0',
  `max_day_news` smallint(6) NOT NULL default '0',
  `force_leech` tinyint(1) NOT NULL default '0',
  `edit_limit` smallint(6) NOT NULL default '0',
  `captcha_pm` tinyint(1) NOT NULL default '0',
  `max_pm_day` smallint(6) NOT NULL default '0',
  `max_mail_day` smallint(6) NOT NULL default '0',
  `admin_tagscloud` tinyint(1) NOT NULL default '0',
  `allow_vote` tinyint(1) NOT NULL default '0',
  `admin_complaint` tinyint(1) NOT NULL default '0',
  `news_question` tinyint(1) NOT NULL default '0',
  `comments_question` tinyint(1) NOT NULL default '0',
  `max_comment_day` smallint(6) NOT NULL default '0',
  `max_images` smallint(6) NOT NULL default '0',
  `max_files` smallint(6) NOT NULL default '0',
  `disable_news_captcha` smallint(6) NOT NULL default '0',
  `disable_comments_captcha` smallint(6) NOT NULL default '0',
  `pm_question` tinyint(1) NOT NULL default '0',
  `captcha_feedback` tinyint(1) NOT NULL default '1',
  `feedback_question` tinyint(1) NOT NULL default '0',
  `files_type` varchar(255) NOT NULL default '',
  `max_file_size` mediumint(9) NOT NULL default '0',
  `files_max_speed` smallint(6) NOT NULL default '0',
  `allow_lostpassword` tinyint(1) NOT NULL default '1',
  `spamfilter` tinyint(1) NOT NULL default '2',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_poll";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_poll (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `news_id` int(10) unsigned NOT NULL default '0',
  `title` varchar(200) NOT NULL default '',
  `frage` varchar(200) NOT NULL default '',
  `body` text NOT NULL,
  `votes` mediumint(8) NOT NULL default '0',
  `multiple` tinyint(1) NOT NULL default '0',
  `answer` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `news_id` (`news_id`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_poll_log";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_poll_log (
  `id` int(10) unsigned NOT NULL auto_increment,
  `news_id` int(10) unsigned NOT NULL default '0',
  `member` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `news_id` (`news_id`,`member`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_banners";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_banners (
  `id` smallint(5) NOT NULL auto_increment,
  `banner_tag` varchar(40) NOT NULL default '',
  `descr` varchar(200) NOT NULL default '',
  `code` text NOT NULL,
  `approve` tinyint(1) NOT NULL default '0',
  `short_place` tinyint(1) NOT NULL default '0',
  `bstick` tinyint(1) NOT NULL default '0',
  `main` tinyint(1) NOT NULL default '0',
  `category` VARCHAR(255) NOT NULL default '',
  `grouplevel` varchar(100) NOT NULL default 'all',
  `start` varchar(15) NOT NULL default '',
  `end` varchar(15) NOT NULL default '',
  `fpage` tinyint(1) NOT NULL default '0',
  `innews` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_rss";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_rss (
  `id` smallint(5) NOT NULL auto_increment,
  `url` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `allow_main` tinyint(1) NOT NULL default '0',
  `allow_rating` tinyint(1) NOT NULL default '0',
  `allow_comm` tinyint(1) NOT NULL default '0',
  `text_type` tinyint(1) NOT NULL default '0',
  `date` tinyint(1) NOT NULL default '0',
  `search` text NOT NULL,
  `max_news` tinyint(3) NOT NULL default '0',
  `cookie` text NOT NULL,
  `category` smallint(5) NOT NULL default '0',
  `lastdate` INT(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_views";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_views (
  `id` int(11) NOT NULL auto_increment,
  `news_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";


$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_rssinform";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_rssinform (
  `id` smallint(5) NOT NULL auto_increment,
  `tag` varchar(40) NOT NULL default '',
  `descr` varchar(255) NOT NULL default '',
  `category` varchar(200) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `template` varchar(40) NOT NULL default '',
  `news_max` smallint(5) NOT NULL default '0',
  `tmax` smallint(5) NOT NULL default '0',
  `dmax` smallint(5) NOT NULL default '0',
  `approve` tinyint(1) NOT NULL default '1',
  `rss_date_format` VARCHAR(20) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_notice";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_notice (
  `id` mediumint(8) NOT NULL auto_increment,
  `user_id` mediumint(8) NOT NULL default '0',
  `notice` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_static_files";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_static_files (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `static_id` mediumint(8) NOT NULL default '0',
  `author` varchar(40) NOT NULL default '',
  `date` varchar(50) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `onserver` varchar(255) NOT NULL default '',
  `dcount` smallint(5) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `static_id` (`static_id`),
  KEY `onserver` (`onserver`),
  KEY `author` (`author`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_tags";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_tags (
  `id` INT(11) NOT NULL auto_increment,
  `news_id` INT(11) NOT NULL default '0',
  `tag` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `news_id` (`news_id`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_post_log";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_post_log (
  `id` INT(11) NOT NULL auto_increment,
  `news_id` INT(11) NOT NULL default '0',
  `expires` varchar(15) NOT NULL default '',
  `action` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `news_id` (`news_id`),
  KEY `expires` (`expires`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_admin_sections";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_admin_sections (
  `id` mediumint(8) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `descr` varchar(255) NOT NULL default '',
  `icon` varchar(255) NOT NULL default '',
  `allow_groups` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_subscribe";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_subscribe (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `name` varchar(40) NOT NULL default '',
  `email`  varchar(50) NOT NULL default '',
  `news_id` int(11) NOT NULL default '0',
  `hash` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `news_id` (`news_id`),
  KEY `user_id` (`user_id`) 
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_sendlog";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_sendlog (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(40) NOT NULL DEFAULT '',
  `date` INT(11) unsigned NOT NULL DEFAULT '0',
  `flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `date` (`date`),
  KEY `flag` (`flag`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_login_log";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_login_log (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL DEFAULT '',
  `count` smallint(6) NOT NULL DEFAULT '0',
  `date` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip` (`ip`),
  KEY `date` (`date`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_mail_log";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_mail_log (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `mail` varchar(50) NOT NULL DEFAULT '',
  `hash` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_complaint";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_complaint (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL DEFAULT '0',
  `c_id` int(11) NOT NULL DEFAULT '0',
  `n_id` int(11) NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  `from` varchar(40) NOT NULL DEFAULT '',
  `to` varchar(255) NOT NULL DEFAULT '',
  `date` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `c_id` (`c_id`),
  KEY `p_id` (`p_id`),
  KEY `n_id` (`n_id`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_ignore_list";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_ignore_list (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL default '0',
  `user_from` VARCHAR(40) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `user_from` (`user_from`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_admin_logs";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_admin_logs (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `date` int(11) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(16) NOT NULL DEFAULT '',
  `action` int(11) NOT NULL DEFAULT '0',
  `extras` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `date` (`date`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_question";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_question (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL DEFAULT '',
  `answer` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_read_log";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_read_log (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `news_id` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_spam_log";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_spam_log (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL DEFAULT '',
  `is_spammer` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(50) NOT NULL DEFAULT '',
  `date` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`),
  KEY `is_spammer` (`is_spammer`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_links";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_links (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `only_one` tinyint(1) NOT NULL DEFAULT '0',
  `replacearea` tinyint(1) NOT NULL DEFAULT '1',
  `rcount` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_social_login";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_social_login (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` varchar(40) NOT NULL DEFAULT '',
  `uid` int(11) NOT NULL DEFAULT '0',
  `password` varchar(32) NOT NULL DEFAULT '',
  `provider` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "INSERT INTO " . PREFIX . "_rssinform VALUES (1, 'dle', 'Yandex Haberleri', '0', 'http://news.yandex.com.tr/index.rss', 'informer', 3, 0, 200, 1, 'j F Y H:i')";

$tableSchema[] = "INSERT INTO " . PREFIX . "_usergroups VALUES (1, 'Yönetici', 'all', 1, 'all', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 50, 101, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 0, '{THEME}/images/icon_1.gif', 0, 1, 1, 1, 1, 1, 1, 0, 1,500,1000,1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,1,'<b><span style=\"color:red\">','</span></b>',1,1,'all', 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 'zip,rar,exe,doc,pdf,swf', 4096, 0, 0, 2)";
$tableSchema[] = "INSERT INTO " . PREFIX . "_usergroups VALUES (2, 'Genel Editör', 'all', 1, 'all', 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 50, 101, 1, 1, 1, 0, 2, 1, 1, 1, 1, 1, 0, '{THEME}/images/icon_2.gif', 0, 1, 0, 1, 1, 1, 1, 0, 1,500,1000,1, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,1,'','',1,1,'all', 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 'zip,rar,exe,doc,pdf,swf', 4096, 0, 1, 2)";
$tableSchema[] = "INSERT INTO " . PREFIX . "_usergroups VALUES (3, 'Editör', 'all', 1, 'all', 1, 1, 1, 1, 0, 0, 1, 0, 1, 1, 50, 101, 1, 1, 1, 0, 3, 0, 1, 1, 1, 1, 0, '{THEME}/images/icon_3.gif', 0, 1, 0, 1, 1, 1, 1, 0, 1,500,1000,1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,1,'','',1,1,'all', 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 'zip,rar,exe,doc,pdf,swf', 4096, 0, 1, 2)";
$tableSchema[] = "INSERT INTO " . PREFIX . "_usergroups VALUES (4, 'Üye', 'all', 1, 'all', 0, 1, 1, 1, 0, 0, 0, 0, 0, 1, 20, 101, 1, 1, 1, 0, 4, 0, 1, 1, 1, 1, 0, '{THEME}/images/icon_4.gif', 0, 1, 0, 1, 0, 1, 1, 1, 0,500,1000,0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,1,'','',1,0,'all', 0, 0, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 'zip,rar,exe,doc,pdf,swf', 4096, 0, 1, 2)";
$tableSchema[] = "INSERT INTO " . PREFIX . "_usergroups VALUES (5, 'Ziyaretçi', 'all', 0, 'all', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 1, 0, 5, 0, 1, 1, 1, 0, 1, '{THEME}/images/icon_5.gif', 0, 1, 0, 0, 0, 0, 1, 1, 0,1,1,0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0,'','',0,0,'all', 0, 0, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, '', 0, 0, 0, 2)";

$tableSchema[] = "INSERT INTO " . PREFIX . "_rss VALUES (1, 'http://dle-news.ru/rss.xml', 'DataLife Engine Resmi Sitesi', 1, 1, 1, 1, 1, '<td align=\"left\" valign=\"top\" class=\"news\">{get}<br /><br /></td>', 5, '', 1, 0)";

$tableSchema[] = "INSERT INTO " . PREFIX . "_email values (1, 'reg_mail', '{%username%},\r\n\r\nBu mesaj $url sitesinden gönderildi\r\n\r\nBu e-posta yı sitemizde kaydınız olduğu için alıyorsunuz. Sitede kayıtlı olmadığınızı düşünüyorsanız bu mesajı görmezden gelerek silebilirsiniz. Bir daha böyle bir mesaj almayacaksınız.\r\n\r\n------------------------------------------------\r\nSitedeki Kullanıcı Adı ve Şifreniz:\r\n------------------------------------------------\r\n\r\nKullanıcı Adı: {%username%}\r\nŞifre: {%password%}\r\n\r\n------------------------------------------------\r\nAktifleştirme Bilgisi\r\n------------------------------------------------\r\n\r\nÖncelikle sitemize kayıt olduğunuz için teşekkür ediyoruz.\r\nKaydınızı onaylamak için gerçek bir mail hesabı kullandığınız onaylandı. Bu işlem istnemeyen (spam) gönderileri engellemek içindir.\r\n\r\nKaydınızı onaylamak için aşağıdaki linke tıklayın :\r\n\r\n{%validationlink%}\r\n\r\nEğer link çalışmazsa hesabınız silinmiş demektir.Bunun için site yönetimi ile iletişime geçmelisiniz.\r\n\r\nSaygılarla,\r\n\r\n$url Yönetimi.')";
$tableSchema[] = "INSERT INTO " . PREFIX . "_email values (2, 'feed_mail', '{%username_to%},\r\n\r\n{%username_from%} tarafından $url sitesinden gelen mesaj.\r\n\r\n------------------------------------------------\r\nMetin Mesajları\r\n------------------------------------------------\r\n\r\n{%text%}\r\n\r\nGönderenin IP Adresi: {%ip%}\r\n\r\n------------------------------------------------\r\nHatırlatırız, site bu mesajın içeriğinden ve gönderenden sorumlu değildir.\r\n\r\nSaygılarımızla,\r\n\r\n$url Yönetimi')";
$tableSchema[] = "INSERT INTO " . PREFIX . "_email values (3, 'lost_mail', 'Sayın {%username%},\r\n\r\nYeni bir şifre oluşturmak için talepte bulundunuz, Ancak şifreler sistemimizde kodlanmış olarak saklanmaktadır. Şifrenizi unuttuysanız $url adresini ziyaret ederek yönetimle irtibata geçebilir veya aşağıdaki bağlantıyı ziyaret edebilirsiniz: \r\n\r\n{%lostlink%}\r\n\r\nEğer bir şifre talebinde bulunmadıysanız bu mesajı silin, şifreniz değiştirilmeyecek ve güvenli bir yerde başkalarının ulaşamayacağı şekilde saklanmaya devam edecektir.\r\n\r\nGönderenin IP Adresi: {%ip%}\r\n\r\nSaygılarımızla,\r\n\r\n$url Yönetimi')";
$tableSchema[] = "INSERT INTO " . PREFIX . "_email values (4, 'new_news', 'Sayın Yönetici,\r\n\r\nSiteye yeni makale eklendi ve onay bekliyor. Takip ve işlemler için $url adresini ziyaret ediniz.\r\n\r\n------------------------------------------------\r\nMakale Özeti\r\n------------------------------------------------\r\n\r\nYazan: {%username%}\r\nMakale Başlığı: {%title%}\r\nKategorisi: {%category%}\r\nTarih: {%date%}\r\n\r\nSaygılarımızla,\r\n\r\n$url Yönetimi')";
$tableSchema[] = "INSERT INTO " . PREFIX . "_email values (5, 'comments', 'Sayın {%username_to%},\r\n\r\nSiteye yeni yorum eklendi. $url adresinden hangi makaleye yorum eklendiğini takip edebilirsiniz. Bu mesaj bilgilendirmek amaçlı göndeirlmiştir.\r\n\r\n------------------------------------------------\r\nMakaleye Yapılan Yorumun Özeti\r\n------------------------------------------------\r\n\r\nYazan: {%username%}\r\nTarih: {%date%}\r\nMakale Adresi: {%link%}\r\n\r\n------------------------------------------------\r\nYorum Metni\r\n------------------------------------------------\r\n\r\n{%text%}\r\n\r\n------------------------------------------------\r\n\r\nBu makaleye yorum eklendiğinde bildirim almak istemiyorsanız, lütfen bağlantıyı takip edin: {%unsubscribe%}\r\n\r\nSaygılarımızla,\r\n\r\n$url Yönetimi')";
$tableSchema[] = "INSERT INTO " . PREFIX . "_email values (6, 'pm', 'Sayın {%username%},\r\n\r\n$url Sitesinden size kişisel mesaj gönderildi.\r\n\r\n------------------------------------------------\r\nMesaj Bilgisi\r\n------------------------------------------------\r\n\r\nGönderen: {%fromusername%}\r\nTarih: {%date%}\r\nBaşlık: {%title%}\r\n\r\n------------------------------------------------\r\nMesaj Metni\r\n------------------------------------------------\r\n\r\n{%text%}\r\n\r\nSaygılarımızla,\r\n\r\n$url Yönetimi')";

$tableSchema[] = "INSERT INTO " . PREFIX . "_category (name, alt_name, keywords) values ('Bilgilendirme', 'main', '')";
$tableSchema[] = "INSERT INTO " . PREFIX . "_banners (banner_tag, descr, code, approve, short_place, bstick, main, category) values ('header', 'Üst Banner', '<div align=\"center\"><a href=\"http://dle.net.tr/\" target=\"_blank\"><img src=\"{$url}templates/Default/images/banner_dnt.png\" style=\"border: none;\" alt=\"\" /></a></div>', 1, 0, 0, 0, 0)";

$add_time = time();
$thistime = date ("Y-m-d H:i:s", $add_time);

$tableSchema[] = "INSERT INTO " . PREFIX . "_static (`name`, `descr`, `template`, `allow_br`, `allow_template`, `grouplevel`, `tpl`, `metadescr`, `metakeys`, `views`, `template_folder`, `date`) VALUES ('dle-rules-page', 'Site Kuralları', '<b>Genel Site Kuralları:</b><br /><br />Site Kuralları<br /><br /><div align=\"center\">{ACCEPT-DECLINE}</div>', 1, 1, 'all', '', 'Genel Kurallar', 'Genel Kurallar', 0, '', '{$add_time}')";
$tableSchema[] = "INSERT INTO " . PREFIX . "_users (name, password, email, reg_date, lastdate, user_group, news_num, info, signature, favorites, xfields) values ('$reg_username', '$reg_password', '$reg_email', '$add_time', '$add_time', '1', '4', '', '', '', '')";
$tableSchema[] = "INSERT INTO " . PREFIX . "_vote (category, vote_num, date, title, body) VALUES ('all', '0', '$thistime', 'Sitemizi değerlendirin', 'Çok güzel bir site<br />İyi bir site<br />İyi ...ama...<br />İyi olabilir<br />Hoşlanmadım')";

$title = "Hoş geldiniz";
$short_story = "<div align=\"center\"><img src=\"".$url."uploads/boxsmall.jpg\" alt=\"\" /></div>DataLife Engine demo sayfasına hoş geldiniz.DataLife Engine çok kullanıcılı bir haber motorudur, bir çok özelliğe sahiptir.Sistem öncelikli olarak haber ve blog siteleri oluşturmak için tasarlanmıştır.Ancak hemen hemen her amaç için kullanabileceğiniz ayarlamalara izin verir.Sistem için mevcut tasarımınızı kolayca entegre edebilirsiniz, şablon oluşturma işleminde hiçbir kısıtlama yoktur.DataLife Engine\'in temel özelliği ise minimum miktarda sunucuya yük bindirmesidir, eğer siteniz büyük bir kitleye hitap ediyorsa, sistem kaynaklarını az kullandığı için sitenize erişim sıkıntısı çekmezsiniz<br />Sistem arama motorları için optimize edilmiştir.Tüm fonksiyonel özellikleri hakkında bilgiyi bu <a href=\"http://dle.net.tr/\" target=\"_blank\">sayfadan</a> okuyabilirsiniz.<br /><br />Sistem hakkında her türlü tartışma katılıp, teknik desteği <a href=\"http://forum.dle.net.tr/\" target=\"_blank\">buradan</a> almak mümkündür.";
$full_story = "";

$tableSchema[] = "INSERT INTO " . PREFIX . "_post (id, date, autor, short_story, full_story, xfields, title, keywords, category, alt_name, allow_comm, approve, allow_main, tags) values ('1', '$thistime', '$reg_username', '$short_story', '$full_story', '', '$title', '', '1', 'post1', '1', '1', '1', 'dle, haber')";
$tableSchema[] = "INSERT INTO " . PREFIX . "_post_extras (news_id, user_id) values ('1', '1')";
$tableSchema[] = "INSERT INTO " . PREFIX . "_tags (news_id, tag) values ('1', 'dle'), ('1', 'haber')";

include ENGINE_DIR.'/classes/mysql.php';
include ENGINE_DIR.'/data/dbconfig.php';

      foreach($tableSchema as $table) {

        $db->query($table);

      }

  echo $skin_header;


echo <<<HTML
<form action="index.php" method="get">
<div class="box">
	<div class="box-header">
		<div class="title">{$title}</div>
	</div>
	<div class="box-content">
	<div class="row box-section">
Tebrikler, DataLife Engine sunucunuza başarıyla yüklendi. Artık <a class="status-info" href="index.php">sitenizin anasayfasına</a> bakabilir ve özellikleri inceleyebilirsiniz. Veya buradan DataLife Engine <a class="status-info" href="admin.php">kontol paneline</a> girebilir ve sistem ayarlarını yönetebilirsiniz.
<br><br><font color="red">Dikkat: Kurulum dosyası bir veritabanı yapısı ve bir yönetici hesabı oluşturmaktadır. Başarılı bir kurulumdan sonra yeniden çalıştırılması kayıplara neden olabilmektedir. Güvenliğiniz amaçlı <b>install.php</b> dosyasını hemen sunucunuzdan silin!</font><br><br>
Keyfini çıkarın<br><br>
SoftNews Media Group<br><br>
	</div>
	<div class="row box-section">	
		<input class="btn btn-green" type=submit value="Bitir">
	</div>
	
	</div>
</div>

HTML;
 
}
else {

	if (@file_exists(ENGINE_DIR.'/data/config.php')) { 

		msgbox( "", "Kurulum işlemi otomatik olarak kilitlendi", "Dikkat! sistem, sunucuda DataLife Engine'ın Kurulu oluğunu tespit etti. Eğer kuruluma devam etmek istiyorsanız, FTP' den <b>/engine/data/config.php</b>, dizinine ulaşarak <b>config.php</b> dosyasını silmeniz gerekmektedir." );

		die ();
	}

$_SESSION['dle_install'] = 1;

// ********************************************************************************
// Приветствие
// ********************************************************************************

  echo $skin_header;

echo <<<HTML
<form method="get" action="">
<input type="hidden" name="action" value="function_check">
<div class="box">
	<div class="box-header">
		<div class="title">DataLife Engine Yükleme Sihirbazı</div>
	</div>
	<div class="box-content">
	<div class="row box-section">
	DataLife Engine kurulum sihirbazına hoş geldiniz. Bu sihirbaz sadece birkaç dakika içinde sistemi yüklemenize yardımcı olacaktır. Ancak, sistemi kurmadan önce scriptin ve dosyaların, çalışması hakkında gerekli dökümanları okumanızı şiddetle öneririz.<br><br>
	Kurulumdan önce, tüm sistem dosyalarının sunucuya yüklenmiş olduğundan, ayrıca dosya ve klasörler için gerekli erişim haklarını vermiş olduğunuza emin olun.<br><br>
	Sistem SEO desteklidir, kullanabilmeniz için <b>modrewrite</b> modülünün sunucuda yüklü olması gerekir, Eğer modül yüklü değil ise, SEO fonksiyonunu devre dışı bırakmak için <b>.htaccess</b> dosyasını ana dizinden silin.<br><br>
	<font color="red">Dikkat: Kurulum dosyası bir veritabanı yapısı ve bir yönetici hesabı oluşturmaktadır. Başarılı bir kurulumdan sonra yeniden çalıştırılması kayıplara neden olabilmektedir. Güvenliğiniz amaçlı kurulumdan sonra <b>install.php</b> dosyasını sunucunuzdan silin!</font></font><br><br>
	Şimdi işin keyfini çıkarın,<br><br>
	SoftNews Media Group
	</div>
	<div class="row box-section">	
		<input class="btn btn-green" type=submit value="Kurulumu Başlat">
	</div>
	
	</div>
</div>
</form>
HTML;
}


echo $skin_footer;
?>