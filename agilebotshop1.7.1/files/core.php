<?php

    //core AgileBotShop 1.7.1

class Bot{
        
        public $AddressBot;
        public $CurrentStep;
        public $NextStep;
        public $ArrayMessage;
        public $LinkWord;
        public $Chat_Id;
        public $UserName;
        public $Start;
        public $Connect;
        public $CurrentMessageFromBot;
        public $NameAddressBot;
        public $PathBot;
        public $PathLog;
        public $PathFile;
        public $Path;
        public $Update;
        public $NextTypeAction;
        public $TypeUpdate;
        public $ModeData;
        public $ShopID;
        public $ProviderToken;
        public $Payload;
        public $Currency;
        public $PriceLabel;        
        public $ProcessingPayment;
        public $PreCheckoutQueryId;
        public $ProviderPaymentChargeId;
        
        public function sendMessage($Message, $Chat_Id, $AddressBot, $RequestHTTP){
            $Url = $AddressBot."/sendMessage";
            if($Message==false)
                $Message = ErrorMessageNoText;
            $Postfields = [
                'chat_id' => $Chat_Id,
                'parse_mode' => 'HTML',
                'text' => $Message,
                'reply_markup' => $reply_markup,
            ];
            $Request = $RequestHTTP->post($Url, $Postfields);
            return true;
        }
        
        public function sendMessageWithButtons($Message, $Chat_Id, $Buttons, $AddressBot, $RequestHTTP){
            $Url = $AddressBot."/sendMessage";
            if($Message==false)
                $Message = ErrorMessageNoText;
            $Postfields = [
                'chat_id' => $Chat_Id,
                'parse_mode' => 'HTML',
                'text' => $Message,
                'reply_markup' => $Buttons,
            ];
            $Request = $RequestHTTP->post($Url, $Postfields);
            return true;
        }
        
        public function sendImage($UrlImage, $Chat_Id, $AddressBot, $RequestHTTP){
            $Url = $AddressBot."/sendPhoto";
            $Postfields = [
                'chat_id' => $Chat_Id,
                'parse_mode' => 'HTML',
                'photo' => $UrlImage,
               
            ];
            $Request = $RequestHTTP->post($Url, $Postfields);
        }
        
        public function sendImageWithButtons($UrlImage, $Chat_Id, $Buttons, $AddressBot, $RequestHTTP){
            $Url = $AddressBot."/sendPhoto";
            $Postfields = [
                'chat_id' => $Chat_Id,
                'parse_mode' => 'HTML',
                'photo' => $UrlImage,
            //    'text' => $Message,
                'reply_markup' => $Buttons,
            ];
            $Request = $RequestHTTP->post($Url, $Postfields);
        }

        public function sendSticker($Sticker, $Emoji, $Chat_Id, $AddressBot, $RequestHTTP){
            $Url = $AddressBot."/sendSticker";
            $PostFields = [
                'chat_id' => $Chat_Id,
                'sticker' => $Sticker               
            ];
            if($Emoji)
                $PostFileds['emoji'] = $Emoji; 
            $Request = $RequestHTTP->post($Url, $PostFields);
            return $Request;
        }
        
        public function startPayment($Chat_Id, $Payment, $RequestHTTP){
            $Url = $this->AddressBot."/sendInvoice";
            $PostFields = [ 
                "chat_id" => $Chat_Id,
                "title" => $Payment['Title'],
                "description" => $Payment['Description'],
                "payload" => $Payment['Payload'],
                "provider_token" => $Payment['ProviderToken'],
              //  "start_parameter" => "val",
                "currency" => $Payment['Currency'],
                "prices" => $Payment['Prices'] 
            ];    
            
            $JSONPost = json_encode($PostFields, JSON_UNESCAPED_UNICODE);
           // $JSONPost = json_encode($PostFields);
            
            /*$File = fopen("./logs/log2.log","a+");
            $Message =  "\r\n\r\n"."Время: ".date("H:i:s d:m:Y").PHP_EOL.
                    "JSON: ".$JSONPost.PHP_EOL;
            fwrite($File, $Message);
            fclose($File);*/ 
            
            $Request = $RequestHTTP->postJSON($Url, $JSONPost);        
            return $Request;                
        }
        public function answerPreChekoutQuery($PreCheckoutQueryId, $RequestHTTP){
            $Url = $this->AddressBot."/answerPreChekoutQuery";
            $PostFields = [
                "pre_chekout_query_id" => $PreCheckoutQueryId,
                "ok" => true 
            ];
            $Request = $RequestHTTP->post($Url, $PostFields);
            return $Request;            
        }
        public function messageSuccessedPayment($Message, $Chat_Id, $Buttons, $RequestHTTP){
            $Url = $this->AddressBot."/sendMessage";
            $PostFields = [
                "chat_id" => $Chat_Id,
                "parse_mode" => 'HTML',
                "text" => $Message,
                "reply_markup" => $Buttons
            ];
            $Request = $RequestHTTP->post($Url, $PostFields);
            return $Request;               
        }
        public function maessageCanceledPayment($Message, $Chat_Id, $Buttons, $RequestHTTP){
            $Url = $this->AddressBot."/sendMessage";
            $PostFields = [
                "chat_id" => $Chat_Id,
                "parse_mode" => 'HTML',
                "text" => $Message,
                "reply_markup" => $Buttons        
            ];
            $Request = $RequestHTTP->post($Url, $PostFields);
            return $Request; 
        }
}

    class RequestHTTP {

        public function post($Url, $Postfields) {
            $curlobj = curl_init();
            $curlobj_post = [
                CURLOPT_URL => $Url,
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_POSTFIELDS => $Postfields
                ];
            curl_setopt_array($curlobj, $curlobj_post);
            return curl_exec($curlobj);
        }
        
        public function postJSON($Url, $JSONPost) {
            $curlobj = curl_init();
            $curlobj_post = [
                CURLOPT_URL => $Url,
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_POSTFIELDS => $JSONPost,
                CURLOPT_HTTPHEADER =>[
                    'Content-Type:application/json',
                    'Content-Length:'.strlen($JSONPost)]
                ];
            curl_setopt_array($curlobj, $curlobj_post);
            return curl_exec($curlobj);
        }
    }

    class Analytica{

        public $CurrentStep;
        public $LinkWord;
        public $NextStep;
        public $CurrentAction;
        
        public function findActionOnLinkWord($LinkWord, $Bot, $ModelBot){
            $FindingAction = ($ModelBot->Connect->query("SELECT TypeAction FROM StepsChat WHERE Step=". $Bot->CurrentStep." And LinkWord=".$LinkWord))->fetch_assoc();
            if(is_array($FindingAction)){
                return $FindingAction['TypeAction'];
            }else{
                return false;    
            }
        }
        
        public function checkLinkWord($ModelBot, $Bot, $Data){
            $TableStepsChat = $ModelBot->Connect->query("SELECT * FROM StepsChat WHERE Step=".$Bot->CurrentStep);
            $CheckLinkWord = false;
            foreach ($TableStepsChat as $Item){
                if($Data->lower(trim($Item['LinkWord'])) == $Bot->LinkWord){
                    $this->NextStep = $Item['NextStep'];                             
                    $CheckLinkWord = true;
                }                
            }          
            return $CheckLinkWord;
        }
        
        public function checkCommand($Bot, $ModelBot, $Data){
        //    $ModelBot->Connect->set_charset("utf8");
            $TableStepsChat = $ModelBot->Connect->query("SELECT * FROM StepsChat");
            $CheckLinkWord = false;
            foreach ($TableStepsChat as $Item){
                if(trim($Item['LinkWord']) == $Bot->LinkWord){
                    $this->NextStep = $Item['NextStep'];                             
                    $CheckLinkWord = true;
                }                
            }          
            return $CheckLinkWord;
        }
        
        public function checkLinkWordOther($Bot, $ModelBot, $Data){
            $TableStepsChat = $ModelBot->Connect->query("SELECT * FROM StepsChat WHERE Step=".$Bot->CurrentStep);
            $CheckLinkWord = false;
            foreach ($TableStepsChat as $Item){
                if ($Data->lower(trim($Item['LinkWord'])) == "@GeneralOther"){                        
                    $this->NextStep = $Item['NextStep'];                             
                    $CheckLinkWord = true;
                } 
            }  
            return $CheckLinkWord;
        }
        
        
        public function checkLinkWordGeneralOther($Bot, $ModelBot, $Data){
            $TableStepsChat = $ModelBot->Connect->query("SELECT * FROM StepsChat16 ");
            $CheckLinkWord = false;
            foreach($TableStepsChat as $Item){
               if( trim($Item['LinkWord']) == "@GeneralOther"){
                   $this->NextStep = $Item['NextStep'];                             
                   $CheckLinkWord = true;
               };
            }   
            return $CheckLinkWord;
        }
        
    public function findStep($ArrayOther, $Step){
        $Find = false;
        foreach ($ArrayOther as $Other){
            if ($Step == $Other)
                $Find = true;
        }
        return $Find;
    }
    
    public function defineNextStep($Bot, $ModelBot, $Data){
       $ArrayOther;
       $ArrayOtherFromTable = $ModelBot->Connect->query("SELECT * FROM StepsChat WHERE Step=".$Bot->CurrentStep);
       foreach ($ArrayOtherFromTable as $Item ){
           $ArrayOther[] = $Item['NextStep'];
       }
       
       $MaxStepFromTable = $ModelBot->Connect->query("SELECT Max(Step) As 'Max' FROM StepsBot");
       $MaxStep = $MaxStepFromTable->fetch_assoc()['Max'];
       $Step = $Bot->CurrentStep + 1;
            
       if($Step == $MaxStep){
           $StepForMaxStepTable = $ModelBot->Connect->query("SELECT NextStep FROM StepsChat WHERE Step=".$Bot->CurrentStep);
           $this->NextStep = ($StepForMaxStepTable->fetch_assoc())['NextStep'];
       }else{
           $Find = false;
           while ($Find == false){
                if($this->findStep($ArrayOther, $Step)){
                   $Step = $Step + 1;    
                }else{
                    $Find = true;
                }
           }
           $this->NextStep = $Step;
       }
       return true;
    }
    public function defineLastStep($Bot, $ModelBot, $Data){
        $ArrayOther;
       $ArrayOtherFromTable = $ModelBot->Connect->query("SELECT * FROM StepsChat WHERE LinkWord LIKE '%Other%' OR LinkWord LIKE '%GeneralOther%' ");
       foreach ($ArrayOtherFromTable as $Item ){
           $ArrayOther[] = $Item['NextStep'];
       }
       $MinStepFromTable = $ModelBot->Connect->query("SELECT Min(Step) As 'Min' FROM StepsBot");
       $MinStep = ($MinStepFromTable->fetch_assoc())['Min'];
       $Step = $Bot->CurrentStep - 1;
       if ($Step === 1){
           $this->NextStep = 1;  
       }elseif($Step == $MinStep){
           $StepForMinStepTable = $ModelBot->Connect->query("SELECT NextStep FROM StepsChat WHERE LinkWord LIKE '%MaxStep%'");
           $this->NextStep = ($StepForMinStepTable->fetch_assoc())['NextStep'];
       }else{
           $Find = false;
           while ($Find == false){
                if($this->findStep($ArrayOther, $Step)){
                   $Step = $Step + 1;    
                }else{
                    $Find = true;
                }
            }
           $this->NextStep = $Step;
       }
       return true;
    }
}


class ModelBot{

    public $Connect;
    public $CurrentStep;
    public $ArrayCurrentStep;
    public $DataForm;

    function __construct($Server, $User, $Password, $DataBase){
        $this->Connect = new MySQLi($Server, $User, $Password, $DataBase);
        $this->Connect->set_charset("utf8");
    }

    public function getCurrentStep($Bot){
        $CurrentStep = ($this->Connect->query("SELECT Step FROM TableSteps WHERE Chat_Id = ".$Bot->Chat_Id. " ORDER BY Id DESC"))->fetch_assoc();
        if(is_array($CurrentStep)){
            $this->CurrentStep = $CurrentStep['Step'];
            return $this->CurrentStep;
        }else{
            return false;
        }
    }

    public function getArrayCurrentStep(){
        $ResultRequest = $this->Connect->query("SELECT * FROM StepsBot WHERE Step =".$this->CurrentStep); 
        if (isset($ResultRequest)){
            //var_dump($ResultRequest);
            $this->ArrayCurrentStep = $ResultRequest->fetch_assoc();
            return $this->ArrayCurrentStep;
        }else{
            return false;
        }
    }

    public function getArrayNextStep($NextStep){
        $ResultRequest = $this->Connect->query("SELECT * FROM StepsBot WHERE Step =".$NextStep); 
        if (is_object($ResultRequest)){
            $this->ArrayNextStep = $ResultRequest->fetch_assoc();
            return true; 
        }else{
            return false;
        }
    }
    
    public function getModeBot($Chat_Id){
        $Query = "SELECT ModeBot FROM TableMode WHERE Chat_Id=".$Chat_Id." ORDER BY Id DESC LIMIT 1";
        $ResultQuery = $this->Connect->query($Query);
        if($ResultQuery)
            $Buttons = ($ResultQuery->fetch_assoc())['ModeBot'];
        if($ResultQuery === false)
            $Buttons = "buttons";
        if(($ResultQuery->fetch_assoc())['ModeBot'] == NULL)
            $Buttons = "buttons";
        return $Buttons;
    }
    
    public function setModeBot($Mode, $Bot){
        $Query = "INSERT INTO TableMode SET ModeBot='$Mode', Chat_Id=".$Bot->Chat_Id;
        return $ResultQuery = $this->Connect->query($Query);
    }
    
    public function getButtons($Step, $Bot){
        $Query = "SELECT DISTINCT LinkWord FROM StepsChat WHERE Step=$Step";
        $this->Connect->set_charset("utf8");
        $ResultQuery = $this->Connect->query($Query);
        $Buttons = '{"inline_keyboard":[';
        $Button = "";
        $i = 1;
        $j = 1;
        $NumRows = $ResultQuery->num_rows;
        if ($NumRows>=1){
            while ($j <= $NumRows){
                $LinkWord = ($ResultQuery->fetch_assoc())['LinkWord'];
                if ($i > 1)
                    $Buttons = $Buttons.",";
                $Button = '[{"text":"'.$LinkWord.'", "callback_data": "'.$LinkWord.'"}]';
                $Buttons = $Buttons.$Button;
                $i++;
                $j++;
            }
        
        $Buttons = $Buttons.']}';
        }else{ 
            $Buttons = false;
        }
        return $Buttons;
    }
    
    public function getStandartButtons(){
        $Buttons = '{"inline_keyboard":[ [{
                                        "text":"Назад",
                                        "callback_data":"Назад"},
                                        {"text":"Далее",
                                        "callback_data":"Далее"}]
                                        ]
                    }'; 
        return $Buttons;
    }
    
    public function getNameDataChat(){
        $ArrayNameData = ($this->Connect->query("SELECT NameData FROM DataChat WHERE Step =".$this->CurrentStep))->fetch_assoc();
        return $ArrayNameData['NameData'];
    }

    public function getNameNextStep(){
        if(isset($this->ArrayNextStep))
            $this->ArrayNextStep = $this->getArrayNextStep();
        $WithSendMessage = ""; 
        //add third value in if
        if (trim($this->ArrayNextStep['WithMessage'])=="да")
            $WithSendMessage = "с отправкой сообщения"; 
        switch($this->ArrayNextStep['TypeAction']){
            case "Message":
                $NameNextStep = "Отправка сообщения";
                break;
            case "getActionSimpleSelect":
                //add number step bota
                $NameNextStep = "Выбор Да/Нет";
                break;
            case "getActionMultiSelect":
                $NameNextStep = "Выбор нескольких вариантов"; 
                break;
            case "getActionSimpleSelect": 
                $NameNextStep = "Получить данные";
                break;               
            default: 
                $NameNextStep = "Другое действие бота ".$WithSendMessage;
                break;
        }
        return $NameNextStep; 
    } 

    public function saveChatStep($Bot, $Data, $FirstName, $LastName, $Mode){
        if($Mode){
            $Query = "INSERT INTO TableSteps (Date, Who, Chat_Id, Step, TypeAction, Message, FirstName, LastName, Addition) 
                                               VALUES ('".$Data->getDate()."','Chat','".$Bot->Chat_Id."',".$Bot->CurrentStep.",'".$this->ArrayCurrentStep['TypeAction']."','".$Bot->Message."','".$FirstName."','".$LastName."','".$this->
                                               ArrayNextStep['Addition']."')"; 
        }else{
            $Query = "INSERT INTO TableSteps (Date, Who, Chat_Id, Step, TypeAction, Message, FirstName, LastName, Addition) 
                                               VALUES ('".$Data->getDate()."','Chat','".$Bot->Chat_Id."',".$Bot->CurrentStep.",'".$this->ArrayCurrentStep['TypeAction']."','".$Bot->Message."','','','".$this->
                                               ArrayNextStep['Addition']."')"; 
        }
        $ResultQuery = $this->Connect->query($Query);
    }
    
    public function saveNextStepBot($Bot, $Data, $Mode){
        if($Mode){
            $Query = "INSERT INTO TableSteps (Date, Who, Chat_Id, Step, TypeAction, Message, FirstName, LastName, Addition) 
                                               VALUES ('".$Data->getDate()."','Bot','".$Bot->Chat_Id."',".$Bot->NextStep.",'".$this->ArrayNextStep['TypeAction']."','".$Bot->Message."','".$Bot->ArrayMessage['message']['from']['first_name']."','".$Bot->ArrayMessage['message']['from']['last_name']."','".$this->
                                               ArrayNextStep['Addition']."')"; 
        }else{
            $Query = "INSERT INTO TableSteps (Date, Who, Chat_Id, Step, TypeAction, Message, FirstName, LastName, Addition) 
                                               VALUES ('".$Data->getDate()."','Bot','".$Bot->Chat_Id."',".$Bot->NextStep.",'".$this->ArrayNextStep['TypeAction']."','".$Bot->Message."','','','".$this->
                                               ArrayNextStep['Addition']."')"; 
        }
        $ResultQuery = $this->Connect->query($Query);
    }

    public function getCurrentArrayDataChat(){
        $this->CurrentArrayDataChat = ($this->Connect->query("SELECT * FROM TableData WHERE Step =".$this->CurrentStep))->fetch_assoc();
        if(isset($this->CurrentDataChat)){
            return true;
        }else{
            return false;
        }
    }
    
    //get type: array string
    public function getValuesData($ArrayNameData, $Chat_Id, $Bot, $ModelBot){
        $ValuesData;
        foreach ($ArrayNameData as $Name){
            $Query = "SELECT NameData, ValueData FROM TableData WHERE NameData='$Name' And Chat_Id=$Chat_Id ORDER BY Id DESC";
            $ValuesData[] = ($ModelBot->Connect->query($Query))->fetch_assoc();
        }
        return $ValuesData;
    }

    public function saveDataInTable($NameData, $ValueData, $Bot, $ModelBot ){
        $Step = $Bot->NextStep;
        $Date = date("H:i d:m:Y");
        if($Bot->Update['message']){
           $ValueData = $ValueData;
           $Chat_Id = $Bot->Chat_Id;
           $FirstName = $Bot->Update['message']['from']['first_name'];
           $LastName = $Bot->Update['message']['from']['last_name'];
        }elseif($Bot->Update['callback_query']){
           $Step = $this->CurrentStep;    
           $ValueData = $ValueData;
           $Chat_Id = $Bot->Chat_Id;
           $FirstName = $Bot->Update['callback_query']['message']['chat']['first_name'];
           $LastName = $Bot->Update['callback_query']['message']['chat']['last_name'];
        }
        $Query = "INSERT INTO TableData ( Step, NameData, ValueData, Chat_Id, FirstName, LastName, Date)
                                 VALUES (".$Step.",'".$NameData."','".$ValueData."',". $Chat_Id.",'".$FirstName."','".$LastName."','$Date')";      
        return $this->Connect->query($Query);
   
    }

    public function getAddition($Step){
        $Result = $this->Connect->query("SELECT Addition FROM StepsBot WHERE Step=".$Step);
        return ($Result->fetch_assoc)['Addition']; 
    }
    
    public function saveDataChat($Bot){
        if($this->getCurrentDataChat()){
            $NameDataChat = $this->CurrentDataChat['NameDataChat'];
            $ValueDataChat = $Bot->Message;  
            return $this->Connect->query("INSERT INTO TableData (UserName, Step, NameData, ValueData, Chat_Id, FirstName, LastName, Date)
                                         VALUES ('".$Bot->ArrayMessage['message']['from']['username']."',".$this->CurrentStep.",'".$NameDataChat."','".$ValueDataChat."',". $Bot->ArrayMessage['message']['from']['id'].",'".$Bot->ArrayMessage['message']['from']['first_name']."','".$Bot->ArrayMessage['message']['from']['last_name']."','".$Data->getTimeAndDate()."')");           
        }else{
            return false;
        }
    }

    public function closeDB(){
        $this->Connect->close();
    }
}

class Data{
    
    public function standart($Str){
        return preg_quote(trim($Str),"'");
    }
    public function filter($Str){
        $Pattern = "/[\'\"]/i";
        $Replacement = "";
        return preg_replace($Pattern, $Replacement, trim($Str));
    }
    public function lower($Str){
        return trim(mb_strtolower($Str, "utf8"));
    }
    public function getTimeAndDate(){
        return date("H:i:s d:m:Y");
    }
    public function getDate(){
        return date("d:m:Y");
    }
    public function checkNum($Str){
        if(is_numeric(trim($Str))){
            return true; 
        }else{
            return false;     
        }
    }
    public function checkStrongText($Str){
        $pattern = "/^[a-zA-Z0-9а-яА-Я]+$/i";
        if(preg_match($pattern, $Str)){
            return true; 
        }else{
            return false;
        }
    }
    public function checkMail($Str){
        $pattern = "/^\w+@\w+\.\w+$/i";
        if(preg_match($pattern, trim($Str))){
            return true;
        }else{
            return false;
        }
    }
    public function valid($Str, $Connect){
        return mysqli_real_escape_string($Connect, trim(mb_convert_encoding($Str, "utf-8")));
    }
    public function validLower($Str, $Connect){
        return mysqli_real_escape_string($Connect, trim(mb_strtolower($Str, "utf8")));
    }
}

class File{
    public function save($Data, $PathFile, ){
        $File = fopen($PathFile, "a+");
        fwrite($File, $Data);
        fclose($File);
    }
}


