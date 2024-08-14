<?php
    
    define("StartMessage", "Добрый день! Это бот AgileBotShop");
    define("StandartCurrency", "RUB");
    define("StandartLabel", "Руб");
    define("MailMessage", "Почтовое сообщение отправлено.");
    define("SuccessPaymentMessage", "Платеж осуществлен.");
    define("CanceledPaymentMessage", "Повторите платеж.");
    
    //constans errors
    define("ErrorInAddition", "Проверьте поле \"Дополнения\" в шаге бота: \r\n - Не заполнено поле  \r\n - Заполнено не верно");
    define("ErrorInNextStep", "Ошибка с параметром \"NextStep\": \r\n - Пропущен сам параметр\r\n - Не заполнено значение параметра. \r\n - Название или значение параметра заполнено не верно. ");
    define("ErrorMatchInNextStep", "Ошибка с параметром \"NextStep\": \nНомер следующего шага совпадает с номером текущего (этого) шага.\r\nПроверьте и назначьте номер следующего шага правильно.");
    define("ErrorInNameDate", "Ошибка с параметром: \"NameDate\" \r\n - Пропущен сам параметр \r\n - Не заполнение значение\r\n - Заполнено не верно.");
    
    define("ErrorMessageNoText", "Ошибка: Текст сообщения для этого шага не набран в базе данных.");
    
    define("ErrorNoNumber", "Ошибка в написании значения: \r\nВеденное значение не числовое. \r\nНапишите снова число.");
    define("ErrorNoStrongText", "Ошибка в написании значения: \r\nВеденное значение должно содержать только буквы и числа. \r\nНапишите снова.");
    define("ErrorNoMail", "Ошибка в написании значения: \r\nВеденное значение не соответствует названию почтового ящика. \r\nНапишите снова почтовый ящик.");
    define("ErrorMessageNoSticker", "Ошибка в параметре: неверно указан или не написан стикер в дополнительных параметрах шага.");
    
    define("ErrorAccessByAction", "ErrorAccessByAction");
    define("ErrorAccessByObject", "ErrorAccessByObject");