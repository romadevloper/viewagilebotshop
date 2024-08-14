<?php

    //Product: AgileBotShop 1.7.1 Beta
    //Key: 00000000000061890001

/*$MessageLog =  "\r\n\r\n"."Тип: "."Проверка".PHP_EOL.
                "Время: ".date("H:i:s d:m:Y").PHP_EOL;
$Log = fopen("./logs/log.log", "a+");
fwrite($Log, $MessageLog);
fclose($Log); */

try{

include_once "configbot.php";
include_once "core.php";
include_once "shop.php";
include_once "logs.php";
include_once "vars.php";


$Bot = new Bot();
$Data = new Data();
$Analytica = new Analytica();
$RequestHTTP = new RequestHTTP();
$ModelBot = new ModelBot($Server, $User, $Password, $DataBase);
$BotLog = new BotLog($PathBotLog);

$SourceUpdate = file_get_contents("php://input");
$Update = json_decode($SourceUpdate, true);

if (is_array($Update)){
    if(isset($Update['callback_query'])){
        $Bot->Chat_Id = $Update['callback_query']['message']['chat']['id'];
        $Bot->Message = $Data->lower($Update['callback_query']['data']);
        $Bot->TypeUpdate = "Chat";
        $BotLog->recordGetingUpdate("Button", $Update['callback_query']['data'], $SourceUpdate, $ModeUserData);
    }elseif(isset($Update['message'])){
        $Bot->Chat_Id = $Update['message']['from']['id'];
        $Bot->Message = $Data->lower($Update['message']['text']);
        $Bot->TypeUpdate = "Chat";
        $BotLog->recordGetingUpdate("Message", $Update['message']['text'], $SourceUpdate, $ModeUserData);
    }elseif(isset($Update['id']) && isset($Update['invoice_payload']) && !isset($Update['shipping_address'])){
        $Bot->Chat_Id = $Update['from']['id'];
        $Bot->PreCheckoutQueryId = $Update['Id'];
        $Bot->TypeUpdate = "Payment";
        $Bot->ProcessingPayment = "PreCheckoutQuery";
        $BotLog->recordGetingUpdate("PreCheckoutQuery", $Update['id'], $SourceUpdate, $ModeUserData);
    }elseif(isset($Update['provider_payment_charge_id'])){
        $Bot->ProviderPaymentChargeId = $Update['provider_payment_charge_id']; 
        $Bot->TypeUpdate = "Payment"; 
        $Bot->ProcessingPayment = "SuccessfulPayment";
        $BotLog->recordGetingUpdate("SuccessfulPayment", $Update['provider_payment_charge_id'], $SourceUpdate, $ModeUserData);
    } 
}


$Bot->LinkWord = $Data->lower($Bot->Message);
$Bot->AddressBot = $AddressBot;
$Bot->Connect = $ModelBot->Connect;
$Bot->NameAddressBot = $AddressBot;
$Bot->ArrayMessage = $Update;
$Bot->PathBot = $PathBot;
$Bot->PathLog = $PathLog;
$Bot->PathFile = $PathFile;
$Bot->Path = $PathBot;
$Bot->Update = $Update;
$Bot->ModeData = $ModeUserData;
$ModelBot->ArrayMessage = $Update;
$Bot->IDShop = $IDShop;
$Bot->ProviderToken = $ProviderToken;
$Bot->Payload = $StandartPayload;
$Bot->PriceLabel = $PriceLabel;

if($Bot->LinkWord == "/start"){
    $Bot->CurrentStep = 1;
    $Bot->Start = true;
    $ModelBot->CurrentStep = 1;
}else{
        if ($ModelBot->getCurrentStep($Bot)){
        $Bot->CurrentStep = $ModelBot->CurrentStep;
    }else{
        $Bot->CurrentStep = 1;
        $Bot->Start = true;
        $ModelBot->CurrentStep = 1;
    }
}

$ModelBot->ArrayCurrentStep = $ModelBot->getArrayCurrentStep($Bot->CurrentStep);
$LinkWord = $Bot->LinkWord;
$ModeBot = $ModelBot->getModeBot($Bot->Chat_Id);

}catch(Error $E){ 
    $Message =  "\r\n\r\n"."Тип: "."Ошибка".PHP_EOL.
                "Время: ".date("H:i:s d:m:Y").PHP_EOL.
               "Текст: ".$E->getMessage().PHP_EOL.
               "Файл: ".$E->getFile().PHP_EOL.
               "Линия: ".$E->getLine().PHP_EOL;
    $LogError = fopen("./logs/error.log", "a+");
    fwrite($LogError, $Message);
    fclose($LogError);
}catch(Exception $E){ 
    $Message =  "\r\n\r\n"."Тип: "."Исключение".PHP_EOL.
                "Время: ".date("H:i:s d:m:Y").PHP_EOL.
               "Текст: ".$E->getMessage().PHP_EOL.
               "Файл: ".$E->getFile().PHP_EOL.
               "Линия: ".$E->getLine().PHP_EOL;
    $LogError = fopen("./logs/error.log", "a+");
    fwrite($LogError, $Message);
    fclose($LogError);
}

function processingChatStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog){
    $Chat_Id = $Bot->Chat_Id;
    $ModeBot = $ModelBot->getModeBot($Bot->Chat_Id);
    $Addition = $ModelBot->ArrayCurrentStep['Addition'];
    $Addition = json_decode($Addition, true);
    
    $BotLog->recordProcessingChatStep($Bot->CurrentStep, $Bot->NextStep);
    
    if(isset($Bot->Update['callback_query'])){
        $FirstName =  $Bot->Update['callback_query']['from']['first_name'];
        $LastName =  $Bot->Update['callback_query']['from']['last_name'];
        $ModelBot->saveChatStep($Bot, $Data, $FirstName, $LastName, $Bot->ModeData);    
    }elseif(isset($Bot->Update['message'])){
        $FirstName =  $Bot->Update['message']['from']['first_name'];
        $LastName =  $Bot->Update['message']['from']['last_name'];
        $ModelBot->saveChatStep($Bot, $Data, $FirstName, $LastName, $Bot->ModeData);
    }
    
    if($Bot->TypeUpdate == "Chat"){
        if($Bot->CurrentStep === 1 && $Bot->Start == true ){
            $Bot->NextStep = 1;
            nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
        }elseif(strpos($Bot->LinkWord,"/") === 0 || strpos($Bot->LinkWord,"/") > 0 ){
            nextStepCommand($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);  
        }elseif($Bot->LinkWord == "меню" || $Bot->LinkWord == "м"){
            $Bot->NextStep = 2;
            nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
        }elseif($Bot->LinkWord == "команды" ){
             $Bot->NextStep = 3;
            nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
        }elseif($Bot->LinkWord == "далее" || $Bot->LinkWord == "д"){
            $Analytica->defineNextStep($Bot,$ModelBot,  $Data);
            $Bot->NextStep = $Analytica->NextStep;
            nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
        }elseif($Bot->LinkWord == "о" || $Bot->LinkWord == "о боте"){
            if($Analytica->checkLinkWord($ModelBot, $Bot, $Data)){
                $Bot->NextStep = $Analytica->NextStep;
                nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
            }else{
                $Bot->NextStep = 1;
                nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
            }
        }elseif($Bot->LinkWord == "назад" || $Bot->LinkWord == "н"){
            $Analytica->defineLastStep( $Bot, $ModelBot, $Data);
            $Bot->NextStep = $Analytica->NextStep;
            nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
        }elseif($Bot->LinkWord == "режим кнопки" || $Bot->LinkWord == "бот кнопки" || $Bot->LinkWord == '/buttons'){
            $ModeBot = "buttons";
            $Chat_Id = $Bot->Chat_Id;
            $ModelBot->setModeBot("buttons", $Bot);
            $Message = "Режим 'Кнопки' включен.";
            $Buttons = $ModelBot->getStandartButtons();
            $Bot->SendMessageWithButtons($Message, $Chat_Id, $Buttons, $Bot->NameAddressBot, $RequestHTTP);
        }elseif($Bot->LinkWord == "режим текст" || $Bot->LinkWord == "бот текст" ){
            $ModeBot = "text";
            $Chat_Id = $Bot->Chat_Id;
            $ModelBot->setModeBot("text", $Bot);
            $Message = "Режим 'Текст' включен.";
            $Bot->SendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
        }else{
            $Analytica->checkLinkWord($ModelBot, $Bot, $Data);
            
            switch($ModelBot->ArrayCurrentStep['TypeAction']){
                case "MessageWithSelect":
                    if($Analytica->checkLinkWord($ModelBot, $Bot, $Data)){
                        $Bot->NextStep = $Analytica->NextStep;
                        nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
                    }elseif($Analytica->checkLinkWordOther($ModelBot, $Bot, $Data)){
                        $Bot->NextStep = $Analytica->NextStep;
                        nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
                    }else{
                        $Analytica->checkLinkWordGeneralOther($Bot, $ModelBot, $Data);
                        $Bot->NextStep = $Analytica->NextStep;
                        nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
                    }
                    break;
                case "MessageAndNextStep":
                    if($Analytica->checkLinkWord($ModelBot, $Bot, $Data)){
                        $Bot->NextStep = $Analytica->NextStep;
                        nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
                    }
                break;
                case "MessageWithSelectHowGetData":
                    if($Analytica->checkLinkWord($ModelBot, $Bot, $Data)){
                        if(isset($Addition['NameData'])){
                            $Result = $ModelBot->saveDataInTable($Addition['NameData'], $Bot->Message, $Bot, $ModelBot); 
                        }else{
                            $NameData = "NameData".$Bot->NextStep;
                            $ModelBot->saveDataInTable($NameData, $Bot->Message, $Bot, $ModelBot); 
                        }
                        if(isset($Addition['NextStep'])){
                            $Bot->NextStep = $Addition['NextStep'];
                            nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
                        }else{
                            $Bot->NextStep = 2;
                            nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
                        }
                    }
                break;
                case "GetData":
                    if(isset($Addition)){
                         if(isset($Addition['TypeData'])){
                             switch($Addition['TypeData']){
                                case "Number":
                                    if(!$Data->checkNum($Bot->Message)){
                                        $Message = ErrorNoNumber; 
                                        $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                                        return;
                                    }
                                break;
                                case "StrongText":
                                    if(!$Data->checkStrongText($Bot->Message)){
                                        $Message = ErrorNoStrongText; 
                                        $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                                        return;
                                    }
                                break;
                                case "Mail":
                                    if(!$Data->checkMail(trim($Bot->Message))){
                                        $Message = ErrorNoMail; 
                                        $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                                        return;
                                    }
                                break;
                             }
                         }
                        
                        $Bot->NextStep = $Addition['NextStep'];
                        nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog); 
                    }else{
                        if(ReportError==true){
                            $Message = ErrorInAddition;
                            $Bot->sendMessage($Message, $Chat_Id_AdminBot, $Bot->NameAddressBot, $RequestHTTP);
                        }
                    } 
                    break;
                case "CheckForm":
                    if($Analytica->checkLinkWord($ModelBot, $Bot, $Data)){
                        $Bot->NextStep = $Analytica->NextStep;
                        nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
                    }elseif($Analytica->checkLinkWordOther($ModelBot, $Bot, $Data)){
                        $Bot->NextStep = $Analytica->NextStep;
                        nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
                    }else{
                        $Analytica->checkLinkWordGeneralOther($Bot, $ModelBot, $Data);
                        $Bot->NextStep = $Analytica->NextStep;
                        nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
                    }
                break;
                case "SendImageWithButtons":
                    if($Analytica->checkLinkWord($ModelBot, $Bot, $Data)){
                        $Bot->NextStep = $Analytica->NextStep;
                        nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
                    }   
                break;
                case "SendMail":
                        $Bot->NextStep = $Bot->CurrentStep;
                        nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
                break;
            }
        }
    }
    
    if($Bot->TypeUpdate == "Payment"){
        switch($Bot->ProcessingPayment){
            case "PreCheckoutQuery":
                $Bot->answerPreCheckoutQuery($Bot->PreChekoutQueryId, $RequestHTTP);
                break;
            case "SuccessfulPayment":
                $Message = SuccessPaymentMessage;
                $Buttons = '{"inline_keyboard":[ [{
                                        "text":"Меню",
                                        "callback_data":"Меню"}]
                                        ]
                        }';
                $Bot->messageSuccessfulPayment($Message, $Chat_Id, $Buttons, $RequestHTTP);
                break;
            case "CanceledPayment": {
                $Message = CanceledPaymentMessage;
                $Buttons = '{"inline_keyboard":[ [{
                                        "text":"Меню",
                                        "callback_data":"Меню"}]
                                        ] 
                    }';
                $Bot->messageCanceledPayment($Message, $Chat_Id, $Buttons, $RequestHTTP);
                break;
            }
        }
    }
}

try{

function nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog){
    try{
        $ModelBot->getArrayNextStep($Bot->NextStep);
        $Chat_Id = $Bot->Chat_Id;
        $ModeBot = $ModelBot->getModeBot($Bot->Chat_Id); 
        $Message = $ModelBot->ArrayNextStep['Message'];
        $Addition = $ModelBot->ArrayNextStep['Addition'];
        $Bot->NextTypeAction = $ModelBot->ArrayNextStep['TypeAction'];
        $OnCalculate = true;
        
        if ($Addition != ""){
            $Addition = json_decode($Addition, true);
        }
        
        switch ($Data->standart($ModelBot->ArrayNextStep['TypeAction'])){
                case "Message":   
                    $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                    break;
                case "MessageWithSelect":
                    if(isset($Addition['Mode']) && $Addition['Mode'] == "Text"){
                        $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                        break;
                    }
                    if($ModeBot == "buttons"){
                            $Buttons = $ModelBot->getButtons($Bot->NextStep, $Bot);
                            if($Buttons === false)
                                $Buttons = $ModelBot->getStandartButtons();
                            $Bot->sendMessageWithButtons($Message, $Chat_Id, $Buttons, $Bot->NameAddressBot, $RequestHTTP);
                            break;
                    }
                    $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                    break;
                case "MessageAndNextStep":
                    if(isset($Addition)){
                        if(isset($Addition['NextStep'])){
                            if($Addition['NextStep'] == $Bot->NextStep){
                                throw new Exception(ErrorMatchInNextStep);
                            }
                            $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                            $Bot->NextStep = $Addition['NextStep'];
                            nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog); 
                        }else{
                            if(ReportError == true){
                                $Message = ErrorInNextStep;
                                $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                            }
                        }
                    }else{
                        if(ReportError == true){
                            $Message = ErrorInAddition;
                            $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                        }
                    }
                break;
                case "MessageWithSelectHowGetData":
                    if($ModeBot == "buttons"){
                        $Buttons = $ModelBot->getButtons($Bot->NextStep, $Bot);
                        if($Buttons === false)
                            $Buttons = $ModelBot->getStandartButtons();
                        $Bot->sendMessageWithButtons($Message, $Chat_Id, $Buttons, $Bot->NameAddressBot, $RequestHTTP);
                    }
                    break; 
                case "GetData":    
                        $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                    break;
                case "SaveData":  
                    if(isset($Addition)){
                            if(count($Addition)==1 || count($Addition)==2){
                                if(isset($Addition['NameData'])){
                                    $Result = $ModelBot->saveDataInTable($Addition['NameData'], $Bot->Message, $Bot, $ModelBot); 
                                }else{
                                    if(ReportError == true){
                                        $Message = ErrorInNameData;
                                        $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                                    }
                                }
                            }
                            if(isset($Addition['NextStep'])){
                                if($Addition['NextStep'] == $Bot->CurrentStep){
                                    throw new Exception(ErrorMatchInNextStep);
                                }
                                $Bot->NextStep = $Addition['NextStep'];
                                nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog); 
                            }else{
                                if(ReportError == true){
                                    $Message = ErrorInNextStep;
                                    $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                                }
                            }
                        }else{
                            if(ReportError == true){
                                $Message = ErrorInAddition;
                                $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                            }
                        }
                    break;
                    case "SendInChat":
                        $ArrayNameData = array();
                        if(isset($Addition['ChatId']))
                            $Chat_Id = $Addition['ChatId']; 
                        if(is_array($Addition) && isset($Addition['ArrayNameData'])){
                            foreach ($Addition['ArrayNameData'] as $Item){
                                $ArrayNameData[] = $Data->standart($Item);
                            }    
                            $ModelBot->DataForm = $ModelBot->getValuesData($ArrayNameData, $Chat_Id, $Bot, $ModelBot);
                        }else{
                            $ModelBot->DataForm = "";
                        }
                        $HeaderMessage = $ModelBot->ArrayNextStep['Message']."\r\n\r\n";
                        $HeaderMessage = $HeaderMessage."Chat Id: $Chat_Id"."\r\n";
                        $DataMessage = "";
                        if($ModelBot->DataForm != ""){
                            foreach ($ModelBot->DataForm as $Item){
                                $DataMessage = $DataMessage.$Item['NameData'].": ".$Item['ValueData']."\r\n";
                            }
                        }
                        $DataMessage = $DataMessage."\n";
                        $Message = $HeaderMessage.$DataMessage;
                        $Chat_Id_Addition = $Addition['ChatId'];
                        $Bot->sendMessage($Message, $Chat_Id_Addition, $Bot->NameAddressBot, $RequestHTTP);
                        if(isset($Addition['NextStep']) && is_numeric($Addition['NextStep'])){
                            if($Addition['NextStep'] == $Bot->CurrentStep){
                               throw new Exception (ErrorMatchInNextStep);
                            }
                            $Bot->NextStep = $Addition['NextStep'];
                            nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog); 
                        }else{
                            if(ReportError == true){
                                $Message = ErrorInNextStep;
                                $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                            }
                        }
                    break;
                case "CheckForm":
                     if(isset($Addition)){
                        $ArrayNameData;
                        $DataMessage = "";
                        if(is_array($Addition) && isset($Addition['ArrayNameData'])){
                            foreach ($Addition['ArrayNameData'] as $Item){
                               $ArrayNameData[] = $Data->standart($Item);
                            } 
                            $ModelBot->DataForm = $ModelBot->getValuesData($ArrayNameData, $Chat_Id, $Bot, $ModelBot);
                            foreach ($ModelBot->DataForm as $Item){
                                $DataMessage = $DataMessage."\r\n".$Item['NameData'].": ".$Item['ValueData']; 
                            }
                        }
                        $HeaderMessage = $ModelBot->ArrayNextStep['Message'];
                        $Question = $Addition['Question'];
                        $Message = $HeaderMessage."\r\n".$DataMessage."\r\n\r\n".$Question;
                        if($ModeBot == "buttons"){
                            $Buttons = $ModelBot->getButtons($Bot->NextStep, $Bot);
                            if($Buttons === false)
                                $Buttons = $ModelBot->getStandartButtons();
                            $Bot->sendMessageWithButtons($Message, $Chat_Id, $Buttons, $Bot->NameAddressBot, $RequestHTTP);
                           return;
                        }
                        $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP); 
                     }else{
                        if(ReportError == true){
                            $Message = ErrorInAddition;
                            $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                        }
                    }
                    break;
                case "SaveFile":
                        $ResultRecord = "\r\n";
                        $Message = $ModelBot->ArrayNextStep['Message'];
                        $Determinator = "\r\n";
                        $DeterminatorDataItem = "\r\n";
                        $DataTable = "";
                        if(isset($Addition['ArrayNameData'])){            
                            $DataTable = $Determinator;
                            foreach ($Addition['ArrayNameData'] as $Item){
                                $ArrayNameData[] = $Data->standart($Item);
                                }  
                                
                            $ModelBot->DataForm = $ModelBot->getValuesData($ArrayNameData, $Chat_Id, $Bot, $ModelBot);
                            foreach ($ModelBot->DataForm as $DataItem)    {
                                $DataTable = $DataTable.$DataItem['NameData'].": ". $DataItem['ValueData'];
                                $DataTable = $DataTable.$DeterminatorDataItem;
                            }  
                        }
                        $DataTable = $DataTable.$Determinator;
                        $Date = $Data->getTimeAndDate()."\r\n";
                        $ResultRecord = $Date."\r\nChat Id: $Chat_Id\r\n".$Message.$DataTable."\r\n";
                        $File = new File();
                        $File->save($ResultRecord,$Bot->PathFile."/".trim($Addition['File']));
                        if(isset($Addition['NextStep']) && is_numeric($Addition['NextStep'])){
                            if($Addition['NextStep'] == $Bot->CurrentStep){
                                throw new Exception(ErrorMatchInNextStep);
                            }
                            $Bot->NextStep = $Addition['NextStep'];
                            nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog); 
                        }else{
                            if(ReportError == true){
                                $Message = ErrorInNextStep;
                                $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                            } 
                        }
                    break;
                case "SendMail":                    
                    $Message = $ModelBot->ArrayNextStep['Message'];
                    $DataTable = "";
                    if(isset($Addition['ChatId']))
                        $Chat_Id = $Addition['ChatId'];
                    if(isset($Addition['ArrayNameData'])){            
                            $DataTable = $Determinator;
                            foreach ($Addition['ArrayNameData'] as $Item){
                                $ArrayNameData[] = $Data->standart($Item);
                                }   
                            $ModelBot->DataForm = $ModelBot->getValuesData($ArrayNameData, $Chat_Id, $Bot, $ModelBot);
                            foreach ($ModelBot->DataForm as $DataItem)    {
                                $DataTable = $DataTable."\r\n".$DataItem['NameData'].": ". $DataItem['ValueData'];
                            }            
                        }
                    $Message ="\r\n".$Message."\r\n".$DataTable."\r\n\r\n".$Addition["Footer"];
                    $To = $Addition["To"];
                    $SubObject = $Addition["SubObject"];
                    $ResultMail = mail($To, $SubObject, $Message);
                    if($ResultMail){
                        $Bot->SendMessage(MailMessage, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                    }else{
                        $Bot->SendMessage("Ошибка в работе почтовой функции.", $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                    }
                    if(isset($Addition['NextStep']) && is_numeric($Addition['NextStep'])){
                            if($Addition['NextStep'] == $Bot->NextStep){
                               throw new Exception(ErrorMatchInNextStep);
                            }
                            $Bot->NextStep = $Addition['NextStep'];
                            nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog); 
                    }else{
                            if(ReportError == true){
                                $Message = ErrorInNextStep;
                                $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                            }
                        }
                break;
                case "Calculate":
                    if($OnCalculate){
                        $ArrayNameData = array();
                        if(is_array($Addition) && isset($Addition['ArrayNameData'])){
                            foreach ($Addition['ArrayNameData'] as $Item){
                                        $ArrayNameData[] = $Data->filter($Item);
                                }    
                        }
                        $ModelBot->DataForm = $ModelBot->getValuesData($ArrayNameData, $Chat_Id, $Bot, $ModelBot);
                        foreach ($ModelBot->DataForm as $Item){
                            $Expression = $Item['NameData']."=".$Item['ValueData'].";";
                            eval($Expression);
                        }
                        $MainExpression ='$ResultCalculate='.$Addition['Formula'].";";  
                        eval($MainExpression);
                        $ModelBot->saveDataInTable("Calculate", $ResultCalculate, $Bot, $ModelBot); 
                        $Message = $Addition['Message']." $ResultCalculate";
                        $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                        if(isset($Addition['NextStep']) && is_numeric($Addition['NextStep'])){
                                if($Addition['NextStep'] == $Bot->CurrentStep){
                                    throw new Exception(ErrorMatchInNextStep);
                                }
                                $Bot->NextStep = $Addition['NextStep'];
                                nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog); 
                        }else{
                                if(ReportError == true){
                                    $Message = ErrorInNextStep;
                                    $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                                }
                        }
                    } 
                break;
                case "SendImageWithButtons":
                    $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                    $Url = $Bot->PathBot.$Addition['File'];
                    if($ModeBot == "buttons"){ 
                        $Buttons = $ModelBot->getButtons($Bot->NextStep, $Bot);
                        if($Buttons === false)
                            $Buttons = $ModelBot->getStandartButtons(); 
                        $Bot->sendImageWithButtons($Url, $Chat_Id, $Buttons, $Bot->NameAddressBot, $RequestHTTP);       
                        break;
                    }
                break;
                case "SendImage":
                    $Url = $Bot->PathBot.$Addition['File'];
                    $Bot->sendImage($Url, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                break;
                case "SendSticker":
                    if($Addition["Sticker"]){
                        $Sticker = $Addition["Sticker"];
                    }else{
                        $Message = ErrorMessageNoSticker;
                        $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                    }
                    if($Addition["Emoji"]){
                        $Emoji = $Addition["Emoji"];
                    }else{
                        $Emoji = false;
                    }
                    $Result = $Bot->sendSticker($Sticker, $Emoji, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                break; 
                case "ShowProduct":
                    $Payment = array();   
                    $Payment['Title'] = $Addition["Product"];
                    $Payment['Description'] = $Addition['Description'];
                    $Payment['Payload'] = $Bot->Payload;
                    $Payment['ProviderToken'] = $Bot->ProviderToken;
                    if(isset($Addition['Currency'])){
                        $Payment['Currency'] = $Addition['Currency'];
                    }else{ 
                        $Payment['Currency'] = StandartCurrency;
                    }
                    $Prices = new Prices();
                    if(isset($Addition['Label'])){
                        $Prices->label = $Addition['Label'];
                    }else{
                        $Prices->label = StandartLabel;
                    }                    
                    $Prices->amount = $Addition['Amount'];                     
                    $Payment['Prices'] = array($Prices);                                                        
                    $ResultStartPayment = $Bot->startPayment($Chat_Id, $Payment, $RequestHTTP);
                    
                    $MessageLog = "\r\n\r\n".$ResultStartPayment."\r\n";
                    $File = fopen("log2.log","a+");
                    fwrite($File, $MessageLog);
                    fclose($File);
                break;
                
                default:                           
                    $Bot->sendMessage($Message."Ошибка: Тип шага указан неверно в базе данных бота.", $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
                break;
            }    
            
            $ModelBot->saveNextStepBot($Bot, $Data, $Bot->ModeData);
        
    }catch(Exception $e){
        if(ReportError == true){
            $Message = $e->getMessage();
            $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
        }
    }
}

function nextStepCommand($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog){
    if($Bot->LinkWord == '/buttons'){
        $ModelBot->setModeBot("buttons", $Bot);
        $Message = "Режим 'Кнопки' включен.";
        $Buttons = $ModelBot->getStandartButtons();
        $Bot->SendMessageWithButtons($Message, $Bot->Chat_Id, $Buttons, $Bot->NameAddressBot, $RequestHTTP);
    }elseif($Bot->LinkWord == '/text'){
        $ModelBot->setModeBot("text", $Bot);
        $Message = "Режим 'Текст' включен.";
        $Buttons = $ModelBot->getStandartButtons();
        $Bot->SendMessage($Message, $Bot->Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
    }elseif($Bot->LinkWord == "/menu" ){
        $Bot->NextStep = 2;
        nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
    }elseif($Bot->LinkWord == "/help" ){ 
        $Bot->NextStep = 3;
        nextStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);
    }
    elseif($Analytica->checkCommand($Bot, $ModelBot,  $Data)){
        $Bot->NextStep = $Analytica->NextStep;
        $ModelBot->getArrayNextStep($Bot->NextStep);
        $Message = $ModelBot->ArrayNextStep['Message'];
        $Chat_Id = $Bot->Chat_Id;
        $ModeBot = $ModelBot->getModeBot($Bot->Chat_Id);
        
        if($ModeBot == "buttons"){
            $Buttons = $ModelBot->getButtons($Bot->NextStep, $Bot);
            if($Buttons === false)
            $Buttons = $ModelBot->getStandartButtons();
            $Bot->sendMessageWithButtons($Message, $Chat_Id, $Buttons, $Bot->NameAddressBot, $RequestHTTP);
        }else{
            $Bot->sendMessage($Message, $Chat_Id, $Bot->NameAddressBot, $RequestHTTP);
        }
    }else{
     //   $Bot->gotoOther($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP);
    //    $Analytica->checkLinkWordGeneralOther($Bot, $ModelBot, $Data);
     //   $Bot->NextStep = $Analytica->NextStep;
    }
   
    $ModelBot->saveNextStepBot($Bot, $Data, $Bot->ModeData);
}

processingChatStep($Bot, $ModelBot, $Analytica, $Data, $RequestHTTP, $BotLog);           
$BotLog->recordNextBotStep($Bot->NextStep, $Bot->NextTypeAction);

}catch(Error $E){
    $Message =  "\r\n\r\n"."Тип: "."Ошибка в работе функций".PHP_EOL.
                "Время: ".date("H:i:s d:m:Y").PHP_EOL.
               "Текст: ".$E->getMessage().PHP_EOL.
               "Файл: ".$E->getFile().PHP_EOL.
               "Линия: ".$E->getLine().PHP_EOL;
    $LogError = fopen("./logs/error.log", "a+");
    fwrite($LogError, $Message);
    fclose($LogError);
}catch(Exception $E){
    $Message =  "\r\n\r\n"."Тип: "."Исключение в работе функций".PHP_EOL.
                "Время: ".date("H:i:s d:m:Y").PHP_EOL.
               "Текст: ".$E->getMessage().PHP_EOL.
               "Файл: ".$E->getFile().PHP_EOL.
               "Линия: ".$E->getLine().PHP_EOL;
    $LogError = fopen("./logs/error.log", "a+");
    fwrite($LogError, $Message);
    fclose($LogError);
}
