<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
    "PARAMETERS" => array(
        "ID" => array(
            "NAME" => GetMessage("NOCAPTCHA_WIDGET_ID"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
            "PARENT" => "BASE",
        ),
        "TABINDEX" => array(
            "NAME" => GetMessage("NOCAPTCHA_WIDGET_TABINDEX"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
            "PARENT" => "BASE",
        ),
    ),
);
?>
