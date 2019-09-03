<?php

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}


$config['version_id'] = "6.7";
$config['key'] = "";
$config['comments_maxlen'] = "3000";
$config['offline_reason'] = "Sitedeki tüm çalışmalar tamamlanınca açılacaktır, Şuanda güncelleme yapılmaktadır.<br /><br />Rahatsızlık verdiğimiz için özür dileriz.";


$handler = fopen(ENGINE_DIR.'/data/config.php', "w") or die("Üzgünüz, Dosyaya yazma yetkisi yok <b>.engine/data/config.php</b>.<br />CHMOD değerini kontol edin!");
fwrite($handler, "<?PHP \n\n//System Configurations\n\n\$config = array (\n\n");
foreach($config as $name => $value)
{
	fwrite($handler, "'{$name}' => \"{$value}\",\n\n");
}
fwrite($handler, ");\n\n?>");
fclose($handler);

@unlink(ENGINE_DIR.'/cache/system/usergroup.php');
@unlink(ENGINE_DIR.'/cache/system/vote.php');
@unlink(ENGINE_DIR.'/cache/system/banners.php');
@unlink(ENGINE_DIR.'/cache/system/category.php');
@unlink(ENGINE_DIR.'/cache/system/banned.php');
@unlink(ENGINE_DIR.'/cache/system/cron.php');
@unlink(ENGINE_DIR.'/data/snap.db');

clear_cache();

msgbox("info","Bilgilendirme", "Veritabanı sürüm yükseltme <b>6.5</b> versiyonundan <b>6.7</b> versiyonuna başarıyla gerçekleştirildi.<br />Güncelleme işlemine devam etmek için ileriye tıklayın.");
?>