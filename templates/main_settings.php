<?php 
if (isset($_POST[VKPB_USER_ID_OPTION]) && isset($_POST[VKPB_CLIENT_ID_OPTION]) && isset($_POST[VKPB_ACCESS_TOKEN_OPTION])) {
	update_option(VKPB_USER_ID_OPTION, $_POST[VKPB_USER_ID_OPTION]);
	update_option(VKPB_CLIENT_ID_OPTION, $_POST[VKPB_CLIENT_ID_OPTION]);
	update_option(VKPB_ACCESS_TOKEN_OPTION, $_POST[VKPB_ACCESS_TOKEN_OPTION]);
	echo "<div class='updated'><p>Настройки сохранены</p></div>";
}
