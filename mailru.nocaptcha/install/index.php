<?

IncludeModuleLangFile(__FILE__);

$PathInstall = str_replace("\\", "/", __FILE__);
$PathInstall = substr($PathInstall, 0, strlen($PathInstall) - strlen("/index.php"));
include($PathInstall."/version.php");
define(BX_NOCAPTCHA_VERSION, $arModuleVersion["VERSION"]);
define(BX_NOCAPTCHA_VERSION_DATE, $arModuleVersion["VERSION_DATE"]);

if(class_exists("mailru_nocaptcha"))
{
    return;
}

class mailru_nocaptcha extends CModule
{
    var $MODULE_ID = "mailru.nocaptcha";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $PARTNER_NAME;
    var $PARTNER_URI;

    function __construct()
    {
        $this->MODULE_VERSION = BX_NOCAPTCHA_VERSION;
        $this->MODULE_VERSION_DATE = BX_NOCAPTCHA_VERSION_DATE;
        $this->MODULE_NAME = GetMessage("NOCAPTCHA_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("NOCAPTCHA_DESCRIPTION");
        $this->PARTNER_NAME = "Mail.Ru Group";
        $this->PARTNER_URI = "http://corp.mail.ru";
    }

	function InstallFiles()
	{
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/components",
                     $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
	}

	function UnInstallFiles()
	{
        DeleteDirFilesEx("bitrix/components/mailru/nocaptcha.widget");
        DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/components",
                       $_SERVER["DOCUMENT_ROOT"]."/bitrix/components");
	}

    function DoInstall()
    {
        $this->InstallFiles();
        RegisterModuleDependences("main", "OnPageStart", $this->MODULE_ID);
        RegisterModule($this->MODULE_ID);
    }

    function DoUninstall()
    {
        $this->UnInstallFiles();
        UnRegisterModuleDependences("main", "OnPageStart", $this->MODULE_ID);
        UnRegisterModule($this->MODULE_ID);
    }
}
?>
