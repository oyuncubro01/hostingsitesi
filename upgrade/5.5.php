<?php

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}


$config['version_id'] = "5.7";
$config['mail_news'] = "0";
$config['mail_comments'] = "0";
$config['key'] = "";

$handler = fopen(ENGINE_DIR.'/data/config.php', "w") or die("Üzgünüz, Dosyaya yazma yetkisi yok <b>.engine/data/config.php</b>.<br />CHMOD değerini kontol edin!");
fwrite($handler, "<?PHP \n\n//System Configurations\n\n\$config = array (\n\n");
foreach($config as $name => $value)
{
	fwrite($handler, "'{$name}' => \"{$value}\",\n\n");
}
fwrite($handler, ");\n\n?>");
fclose($handler);

$config_dbhost = DBHOST;
$config_dbname = DBNAME;
$config_dbuser = DBUSER;
$config_dbpasswd = DBPASS;
$config_dbprefix = PREFIX;

$dbconfig = <<<HTML
<?PHP

define ("DBHOST", "{$config_dbhost}"); 

define ("DBNAME", "{$config_dbname}");

define ("DBUSER", "{$config_dbuser}");

define ("DBPASS", "{$config_dbpasswd}");  

define ("PREFIX", "{$config_dbprefix}"); 

define ("USERPREFIX", "{$config_dbprefix}"); 

\$db = new db;

?>
HTML;

$con_file = fopen(ENGINE_DIR.'/data/dbconfig.php', "w") or die("Üzgünüz, Dosyaya yazma yetkisi yok <b>.engine/data/dbconfig.php</b>.<br />CHMOD değerini kontol edin!");
fwrite($con_file, $dbconfig);
fclose($con_file);

$tableSchema = array();

$tableSchema[] = "ALTER TABLE `" . PREFIX . "_vote` CHANGE `category` `category` VARCHAR( 200 ) NOT NULL DEFAULT ''";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_pm` ADD `reply` TINYINT( 1 ) NOT NULL DEFAULT '0'";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_static` ADD `metadescr` VARCHAR( 200 ) NOT NULL DEFAULT ''";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_static` ADD `metakeys` TEXT NOT NULL DEFAULT ''";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_usergroups` ADD `icon` VARCHAR( 200 ) NOT NULL DEFAULT ''";
$tableSchema[] = "INSERT INTO " . PREFIX . "_email values (4, 'new_news', 'Sayın Yönetici,\r\n\r\nSiteye yeni makale eklendi ve onay bekliyor. Takip ve işlemler için $url adresini ziyaret ediniz.\r\n\r\n------------------------------------------------\r\nMakale Özeti\r\n------------------------------------------------\r\n\r\nYazan: {%username%}\r\nMakale Başlığı: {%title%}\r\nKategorisi: {%category%}\r\nTarih: {%date%}\r\n\r\nSaygılarımızla,\r\n\r\n$url Yönetimi')";
$tableSchema[] = "INSERT INTO " . PREFIX . "_email values (5, 'comments', 'Sayın {%username_to%},\r\n\r\nSiteye yeni yorum eklendi. $url adresinden hangi makaleye yorum eklendiğini takip edebilirsiniz. Bu mesaj bilgilendirmek amaçlı göndeirlmiştir.\r\n\r\n------------------------------------------------\r\nMakaleye Yapılan Yorumun Özeti\r\n------------------------------------------------\r\n\r\nYazan: {%username%}\r\nTarih: {%date%}\r\nMakale Adresi: {%link%}\r\n\r\n------------------------------------------------\r\nYorum Metni\r\n------------------------------------------------\r\n\r\n{%text%}\r\n\r\n------------------------------------------------\r\n\r\nBu makaleye yorum eklendiğinde bildirim almak istemiyorsanız, lütfen bağlantıyı takip edin: {%unsubscribe%}\r\n\r\nSaygılarımızla,\r\n\r\n$url Yönetimi')";

  foreach($tableSchema as $table) {
     $db->query ($table);
   }

@unlink(ENGINE_DIR.'/cache/system/category.php');
@unlink(ENGINE_DIR.'/cache/system/cron.php');

clear_cache();

msgbox("info","Bilgilendirme", "Veritabanı sürüm yükseltme <b>5.5</b> versiyonundan <b>5.7</b> versiyonuna başarıyla gerçekleştirildi.<br />Güncelleme işlemine devam etmek için ileriye tıklayın.");
?>