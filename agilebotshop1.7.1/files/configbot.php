<?php

$AddressServerBot = "https://api.telegram.org/bot";
$BotToken = "";
$AddressBot = $AddressServerBot.$BotToken;

$Server = "127.0.0.1"; 
$DataBase = "";
$User = "";
$Password = "";

$IDShop = "";
$ProviderToken = "";
$StandartPayload = "StandartPayload";
$Currency = "RUB";
$PriceLabel = "руб";
$StartParameter = "";

$PathBot = "";
$PathLog = "./logs/";
$PathFile = "./files/";
$FileBotLog = "log.log";
$PathBotLog = $PathLog.$FileBotLog;

$ModeUserData = false;

define("ReportError", true);