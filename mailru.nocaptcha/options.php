<?
if (!$USER->IsAdmin())
    return;

define(BX_NOCAPTCHA_MODULE_ID, "mailru.nocaptcha");

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".BX_NOCAPTCHA_MODULE_ID."/default_option.php");

$options = array(
    array("public_key", GetMessage("NOCAPTCHA_PUBLIC_KEY"), $nocaptcha_default_option["public_key"],
          array("text", 40, 64, false)),
    array("private_key", GetMessage("NOCAPTCHA_PRIVATE_KEY"), $nocaptcha_default_option["private_key"],
          array("text", 40, 64, true)),
    array("ru_langs", GetMessage("NOCAPTCHA_RU_LANGS"), $nocaptcha_default_option["ru_langs"],
          array("text", 40, 20, false)),
    array("replace", GetMessage("NOCAPTCHA_REPLACE"), $nocaptcha_default_option["replace"],
          array("checkbox")),
);

$tabs = array(
	array("DIV" => "edit1",
          "TAB" => GetMessage("MAIN_TAB_SET"),
          "ICON" => "ib_settings",
          "TITLE" => GetMessage("MAIN_TAB_TITLE_SET")),
);

$tabControl = new CAdminTabControl("tabControl", $tabs);

if ($REQUEST_METHOD == "POST" && strlen($Update . $Apply . $RestoreDefaults) > 0
        && check_bitrix_sessid())
{
    if (strlen($RestoreDefaults) > 0)
        COption::RemoveOption(BX_NOCAPTCHA_MODULE_ID);
    else
    {
        foreach ($options as $option)
        {
            $name = $option[0];
            $val = $_REQUEST[$name];
            if ($option[2][0] == "checkbox" && $val != "Y")
                $val = "N";
            COption::SetOptionString(BX_NOCAPTCHA_MODULE_ID, $name, $val, $caption);
        }
    }
    if (strlen($Update) > 0 && strlen($_REQUEST["back_url_settings"]) > 0)
		LocalRedirect($_REQUEST["back_url_settings"]);
	else
		LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . urlencode($mid) . "&lang=" . urlencode(LANGUAGE_ID) . "&back_url_settings=" . urlencode($_REQUEST["back_url_settings"]) . "&" . $tabControl->ActiveTabParam() . "&mid_menu=1");
}

$tabControl->Begin();
?>
<form method="post" action="<?=$APPLICATION->GetCurPage() . "?mid=" . urlencode($mid) . "&lang=" . urlencode(LANGUAGE_ID)?>">
    <?
    $tabControl->BeginNextTab();

    foreach ($options as $option):
        $name = $option[0];
        $caption = $option[1];
        $val = COption::GetOptionString(BX_NOCAPTCHA_MODULE_ID, $name, $option[2]);
        $type = $option[3];
    ?>
    <tr>
        <td width="40%">
            <label for="<?=$name?>"><?=htmlspecialcharsbx($caption)?>:</label>
        </td>
        <td width="60%">
            <? if ($type[0] == "checkbox"): ?>
                <input type="checkbox" id="<?=htmlspecialcharsbx($name)?>" name="<?=htmlspecialcharsbx($name)?>" value="Y"<? if ($val == "Y") echo " checked"; ?>>
            <? elseif ($type[0] == "text"): ?>
                <input type="text" size="<?=$type[1]?>" maxlength="<?=$type[2]?>" value="<?=htmlspecialcharsbx($val)?>" name="<?=htmlspecialcharsbx($name)?>"<? if ($type[3]) echo ' autocomplete="off"'; ?>>
            <? elseif ($type[0] == "textarea"): ?>
				<textarea rows="<?=$type[1]?>" cols="<?=$type[2]?>" name="<?=htmlspecialcharsbx($name)?>"><?=htmlspecialcharsbx($val)?></textarea>
            <? endif ?>
        </td>
    </tr>
    <? endforeach ?>
    <?$tabControl->Buttons();?>
	<input type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
	<input type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
	<? if (strlen($_REQUEST["back_url_settings"]) > 0): ?>
		<input type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
		<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
	<? endif ?>
	<input type="submit" name="RestoreDefaults" title="<?=GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" onclick="return confirm('<?=AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?=GetMessage("MAIN_RESTORE_DEFAULTS")?>">
	<?=bitrix_sessid_post();?>
    <?$tabControl->End();?>
</form>
