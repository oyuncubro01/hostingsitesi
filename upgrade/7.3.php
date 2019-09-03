<?php
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$config['version_id'] = "7.5";
$config['allow_smartphone'] = "0";
$config['allow_smart_images'] = "0";

$tableSchema = array();

$tableSchema[] = "ALTER TABLE `" . PREFIX . "_static` ADD `date` VARCHAR( 15 ) NOT NULL DEFAULT ''";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_usergroups` ADD `max_signature` SMALLINT( 6 ) NOT NULL DEFAULT '1'";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_usergroups` ADD `max_info` SMALLINT( 6 ) NOT NULL DEFAULT '1'";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_static_files` ADD `onserver` VARCHAR( 255 ) NOT NULL DEFAULT ''";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_static_files` ADD `dcount` SMALLINT( 5 ) NOT NULL DEFAULT '0'";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_static_files` ADD INDEX ( `onserver` )";
$tableSchema[] = "UPDATE " . PREFIX . "_usergroups SET max_signature='500'";
$tableSchema[] = "UPDATE " . PREFIX . "_usergroups SET max_info='1000'";

foreach($tableSchema as $table) {
	$db->query ($table);
}


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

msgbox("info","Bilgilendirme", "Veritabanı sürüm yükseltme <b>7.3</b> versiyonundan <b>7.5</b> versiyonuna başarıyla gerçekleştirildi.<br /><br />{$error_info}<br />Güncelleme işlemine devam etmek için ileriye tıklayın.");
?>