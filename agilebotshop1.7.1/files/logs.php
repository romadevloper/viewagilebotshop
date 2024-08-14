<?php
 
class BotLog{

    public $FileName;

    function __construct($FileName){
        $this->FileName = $FileName; 
    }

    public function write($Data){
        $File = fopen($this->FileName, "a+");
        fwrite($File, $Data);
        fclose($File);
    }
    /*public function close(){
        return fclose($this->File);
    }*/
    public function recordGetingUpdate($TypeStep, $Step, $SourceUpdate, $ModeUserData){
        if($ModeUserData){
            $JSONUpdate = $SourceUpdate;
        }else{
            $JSONUpdate = "";
        }
        $Message =  "\r\n\r\n"."Время: ".date("H:i:s d:m:Y").PHP_EOL.
                    "Шаг пользователя".PHP_EOL.
                    "Тип: ".$TypeStep.PHP_EOL.
                    "Значение: ".$Step.PHP_EOL.
                    "(Это сообщение или название кнопки)".PHP_EOL.
                    "JSON Update: ". $JSONUpdate."\r\n";
                    
        $File = fopen($this->FileName, "a+");
        fwrite($File, $Message);
        fclose($File);
    } 
    public function recordProcessingChatStep($CurrentStep, $NextStep){
        if(!$NextStep)
            $NextStep = "";
        $Message =  "\r\n"."Обработка шага пользователя:".PHP_EOL.
                    "Время: ".date("H:i:s d:m:Y").PHP_EOL.
                    "Текущий шаг: ".$CurrentStep.PHP_EOL;
                    //"Следующий шаг: ".$NextStep.PHP_EOL."\n";
        $File = fopen($this->FileName, "a+");
        fwrite($File, $Message);
        fclose($File);
    }
    public function recordNextBotStep($NextStep, $TypeAction){
        $Message =  "\r\n"."Шаг бота".PHP_EOL.
                    "Время: ".date("H:i:s d:m:Y").PHP_EOL.
                    "Номер шага: ".$NextStep.PHP_EOL.
                    "Тип шага: ".$TypeAction."\r\n";
        $File = fopen($this->FileName, "a+");
        fwrite($File, $Message);
        fclose($File);
    } 

}

class ErrorLog{

    public $File;

    function __construct($FileName){
        $this->File = fopen($FileName, "a+");
    }

    //public function read(){
    //   return fread($this->File);
    //}

    public function write($Data){
        return fwrite($this->File, $Data);
    }
    public function close(){
        return fclose($this->File);
    }
    public function handler($ErrCode, $ErrMessage, $File, $Line){
        $Message = "Ошибка".PHP_EOL.
                   "Время: ".date("H:i:s d:m:Y").PHP_EOL.
                   "Код: ".$ErrCode.PHP_EOL.
                   "Сообщение: ".$rrMessage.PHP_EOL.
                   "Файл: ".$File.PHP_EOL.
                   "В строке: ".$Line.PHP_EOL.PHP_EOL;
        $this->write($Message);
    }
}