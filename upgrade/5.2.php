<?php

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}


$config['version_id'] = "5.3";
$config['smilies'] = "wink,winked,smile,am,belay,feel,fellow,laughing,lol,love,no,recourse,request,sad,tongue,wassat,crying,what,bully,angry";
$config['extra_login'] = "0";
$config['image_align'] = "left";
$config['ip_control'] = "1";
$config['ip_control'] = "1";
$config['cache_count'] = "0";
$config['cron'] = time();
$config['key'] = "";

unset ($config['smiles_nummer']);

$handler = fopen(ENGINE_DIR.'/data/config.php', "w") or die("Üzgünüz, Dosyaya yazma yetkisi yok <b>.engine/data/config.php</b>.<br />CHMOD değerini kontol edin!");
fwrite($handler, "<?PHP \n\n//System Configurations\n\n\$config = array (\n\n");
foreach($config as $name => $value)
{
fwrite($handler, "'{$name}' => \"{$value}\",\n\n");
}
fwrite($handler, ");\n\n?>");
fclose($handler);

$tableSchema = array();

$tableSchema[] = "ALTER TABLE `" . PREFIX . "_pm` CHANGE `user` `user` SMALLINT( 8 ) NOT NULL";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_pm` ADD INDEX ( `user` )";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_pm` ADD INDEX ( `user_from` )";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_banners` CHANGE `category` `category` VARCHAR( 200 ) NOT NULL DEFAULT ''";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_users` ADD `logged_ip` VARCHAR( 16 ) NOT NULL DEFAULT ''";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_banned` ADD `ip` VARCHAR( 16 ) NOT NULL DEFAULT ''";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_views";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_views (
  `id` mediumint(8) NOT NULL auto_increment,
  `news_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci */";

  foreach($tableSchema as $table) {
     $db->query ($table);
   }

msgbox("info","Bilgilendirme", "Veritabanı sürüm yükseltme <b>5.2</b> versiyonundan <b>5.3</b> versiyonuna başarıyla gerçekleştirildi.<br />Güncelleme işlemine devam etmek için ileriye tıklayın.");
?>