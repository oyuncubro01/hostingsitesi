<?php

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

msgbox("info","Güncelleme Tamamlandı", "Script güncellemesi son sürüm olan <b>$version_id</b> sürümüne başarıyla gerçekleşmiştir.<br /><br /> Sunucunuzdan <b>/upgrade/</b> klasörünü silin", true);
?>