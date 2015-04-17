<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

IncludeModuleLangFile(__FILE__);

$arComponentDescription = array(
	"NAME" => GetMessage("NOCAPTCHA_WIDGET_NAME"),
	"DESCRIPTION" => GetMessage("NOCAPTCHA_WIDGET_DESCRIPTION"),
	"ICON" => "/images/nocaptcha.gif",
	"PATH" => array(
		"ID" => "utility",
	),
);
?>
