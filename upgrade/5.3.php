<?php

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}


$config['version_id'] = "5.5";
$config['no_date'] = "1";
$config['related_news'] = "0";
$config['key'] = "";

unset ($config['cron']);

$handler = fopen(ENGINE_DIR.'/data/config.php', "w") or die("Üzgünüz, Dosyaya yazma yetkisi yok <b>.engine/data/config.php</b>.<br />CHMOD değerini kontol edin!");
fwrite($handler, "<?PHP \n\n//System Configurations\n\n\$config = array (\n\n");
foreach($config as $name => $value)
{
fwrite($handler, "'{$name}' => \"{$value}\",\n\n");
}
fwrite($handler, ");\n\n?>");
fclose($handler);

$tableSchema = array();

$tableSchema[] = "ALTER TABLE `" . PREFIX . "_pm` CHANGE `user` `user` MEDIUMINT( 8 ) NOT NULL";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_static` ADD `tpl` VARCHAR( 40 ) NOT NULL DEFAULT ''";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_category` ADD `short_tpl` VARCHAR( 40 ) NOT NULL DEFAULT ''";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_category` ADD `full_tpl` VARCHAR( 40 ) NOT NULL DEFAULT ''";

  foreach($tableSchema as $table) {
     $db->query ($table);
   }

@unlink(ENGINE_DIR.'/cache/system/category.php');
@unlink(ENGINE_DIR.'/cache/system/cron.php');

clear_cache();

msgbox("info","Bilgilendirme", "Veritabanı sürüm yükseltme <b>5.3</b> versiyonundan <b>5.5</b> versiyonuna başarıyla gerçekleştirildi.<br />Güncelleme işlemine devam etmek için ileriye tıklayın.");
?>