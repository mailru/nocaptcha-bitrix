<?

IncludeModuleLangFile(__FILE__);

$PathInstall = str_replace("\\", "/", __FILE__);
$PathInstall = substr($PathInstall, 0, strlen($PathInstall) - strlen("/index.php"));
include($PathInstall."/version.php");
define(BX_NOCAPTCHA_VERSION, $arModuleVersion["VERSION"]);
define(BX_NOCAPTCHA_VERSION_DATE, $arModuleVersion["VERSION_DATE"]);

if(class_exists("nocaptcha"))
{
    return;
}

class nocaptcha extends CModule
{
    var $MODULE_ID = "nocaptcha";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;

    function __construct()
    {
        $this->MODULE_VERSION = BX_NOCAPTCHA_VERSION;
        $this->MODULE_VERSION_DATE = BX_NOCAPTCHA_VERSION_DATE;
        $this->MODULE_NAME = GetMessage("NOCAPTCHA_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("NOCAPTCHA_DESCRIPTION");
    }

	function InstallFiles()
	{
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/components",
                     $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
	}

	function UnInstallFiles()
	{
        DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/components",
                       $_SERVER["DOCUMENT_ROOT"]."/bitrix/components");
	}

    function DoInstall()
    {
        $this->InstallFiles();
        RegisterModuleDependences("main", "OnPageStart", $this->MODULE_ID);
        RegisterModule($this->MODULE_ID);
        //$APPLICATION->IncludeAdminFile(GetMessage("NOCAPTCHA_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/step.php");
    }

    function DoUninstall()
    {
        $this->UnInstallFiles();
        UnRegisterModuleDependences("main", "OnPageStart", $this->MODULE_ID);
        UnRegisterModule($this->MODULE_ID);
        //$APPLICATION->IncludeAdminFile("install module", $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/unstep.php");
    }
}
?>
