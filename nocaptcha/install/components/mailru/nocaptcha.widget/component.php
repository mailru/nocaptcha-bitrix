<?

CModule::IncludeModule("nocaptcha");

if (isset($arParams["ID"]))
{
    $arResult["ID"] = $arParams["ID"];
    CNocaptcha::GetInstance()->AddContainerId($arResult["ID"]);
}
else
{
    $arResult["ID"] = CNocaptcha::GetInstance()->GenerateContainerId();
}
$this->IncludeComponentTemplate();
?>
