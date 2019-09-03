<?php

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$config['version_id'] = "7.3";
$config['flv_watermark'] = "0";
$config['allow_add_tags'] = "0";
$config['allow_fixed'] = "1";
$config['max_file_size'] = "4096";
$config['max_file_count'] = "0";


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

if ($db->error_count) $error_info = "Toplam sorgu sayısı: <b>".$db->query_num."</b> Başarısız sorgu sayısı: <b>".$db->error_count."</b>. Belki bu sorguları daha önce gerçekleştirdiniz."; else $error_info = "";

msgbox("info","Bilgilendirme", "Veritabanı sürüm yükseltme <b>7.2</b> versiyonundan <b>7.3</b> versiyonuna başarıyla gerçekleştirildi.<br />{$error_info}<br />Güncelleme işlemine devam etmek için ileriye tıklayın.");
?>