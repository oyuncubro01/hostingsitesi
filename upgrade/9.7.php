<?php

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$config['version_id'] = "9.8";
$config['allow_share'] = "1";
$config['auth_domain'] = "1";

$tableSchema = array();

$tableSchema[] = "ALTER TABLE `" . PREFIX . "_vote` ADD `grouplevel` VARCHAR(250) NOT NULL DEFAULT 'all'";

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


$fdir = opendir( ENGINE_DIR . '/cache/system/' );
while ( $file = readdir( $fdir ) ) {
	if( $file != '.' and $file != '..' and $file != '.htaccess' ) {
		@unlink( ENGINE_DIR . '/cache/system/' . $file );
		
	}
}

@unlink(ENGINE_DIR.'/data/snap.db');

clear_cache();

if ($db->error_count) {

	$error_info = "Toplam sorgu sayısı: <b>".$db->query_num."</b> Başarısız sorgu sayısı: <b>".$db->error_count."</b>. Belki bu sorguları daha önce gerçekleştirdiniz.<br /><br /><div class=\"quote\"><b>Çalıştırılamayan sorgular:</b><br /><br />"; 

	foreach ($db->query_list as $value) {

		$error_info .= $value['query']."<br /><br />";

	}

	$error_info .= "</div>";

} else $error_info = "";

msgbox("info","Bilgilendirme", "Veritabanı sürüm yükseltme <b>9.7</b> versiyonundan <b>9.8</b> versiyonuna başarıyla gerçekleştirildi.<br /><br />{$error_info}<br />Güncelleme işlemine devam etmek için ileriye tıklayın.");
?>