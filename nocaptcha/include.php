<?
CModule::AddAutoloadClasses("nocaptcha", array("CNocaptcha" => "classes/general/nocaptcha.php"));
if (COption::GetOptionString("nocaptcha", "replace", "N") === "Y")
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/nocaptcha/classes/general/captcha.php");
}
?>
