-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Июл 08 2024 г., 15:15
-- Версия сервера: 5.7.27-30
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: 
--

-- --------------------------------------------------------

--
-- Структура таблицы `StepsBot`
--

CREATE TABLE `StepsBot` (
  `Id` int(11) NOT NULL,
  `Step` int(11) NOT NULL,
  `TypeAction` text NOT NULL,
  `Message` text NOT NULL,
  `HeaderMessage` text NOT NULL,
  `IdMessage` int(11) NOT NULL,
  `Addition` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `StepsBot`
--

INSERT INTO `StepsBot` (`Id`, `Step`, `TypeAction`, `Message`, `HeaderMessage`, `IdMessage`, `Addition`) VALUES
(1, 1, 'MessageWithSelect', 'Добрый день! \r\nЭто бот  модели   AgileBotPro  1.7.1!\r\n\r\n', '', 3, ''),
(2, 2, 'MessageWithSelect', '<b>Меню</b>\r\n\r\n<b>Тестовые шаги бота</b>\r\n<b>Команды</b>', '', 3, ''),
(3, 3, 'MessageWithSelect', '<b>Команды</b>\n\n<pre>/start -  старт бота</pre>\n<pre>/menu  -  меню бота</pre>\n<pre>/help  -  команды бота</pre>\n\n', '', 3, ''),
(4, 4, 'Empty', 'Шаг бота как разделитель.\nПрименяется для создания нескольких разделов (или несколько меню) внутри бота. ', '', 3, ''),
(5, 100, 'MessageWithSelect', 'Тестовые шаги бота\r\n\r\nЗаявка\r\nПисьмо\r\nСообщение с дальнейшим переходом\r\nЗаписать данное с кнопки\r\nОтправить изображение с кнопками\r\nОтправить сообщение\r\nОтправить изображение\r\nПример стикера\r\nРасчет\r\n\r\nЗаявка с отправкой заполненных данных в чат менеджеру и записью в файл.\r\n\r\nДля продолжения напишите \"Заявка\" или \"Расчет\" или нажмите соответствующую кнопку, если включен режим \"Кнопки\".', '', 3, ''),
(6, 101, 'MessageWithSelect', 'Заявка\n\nЭто заявка, для заполнения и отправки менеджеру напишите \"далее\".\n\n  \n', '', 3, ''),
(7, 102, 'GetData', 'Ваше Имя?\n\n  \n', '', 3, '{\"NextStep\":103}'),
(8, 103, 'SaveData', '\n  \n', '', 3, '{\"NameData\":\"Name\", \"NextStep\":104}'),
(9, 104, 'GetData', 'Ваш телефон?\n\n  \n', '', 3, '{\"NextStep\":105}'),
(10, 105, 'SaveData', '  \n', '', 3, '{\"NameData\":\"Telefon\", \"NextStep\":106}'),
(11, 106, 'CheckForm', 'Заявка заполнена!\n\nПроверьте заполненные данные:\n\n  \n', '', 3, '{\"ArrayNameData\":[\"Name\",\"Telefon\"], \"Question\":\"Отправить Да, Нет?\"}'),
(12, 107, 'SendInChat', 'Заполнена заявка.\nДанные:', '', 3, '{\"ArrayNameData\":[\"Name\",\"Telefon\"], \"ChatId\":1973172682, \"NextStep\":108}'),
(13, 108, 'SaveFile', 'Заполнена заявка.\nДанные:', '', 3, '{\"ArrayNameData\":[\"Name\",\"Telefon\"], \"Number1\":1, \"Number2\":0, \"File\":\"file.txt\", \"NextStep\":109}'),
(14, 109, 'MessageWithSelect', 'Спасибо! Заявка оправлена в чат менеджера бота.\n  \n', '', 3, ''),
(15, 110, 'MessageWithSelect', 'Расчет\n\nДля проведения расчета напишите или нажмите \"далее\".\n', '', 3, ''),
(16, 111, 'GetData', 'Первое число?', '', 3, '{\"NextStep\":112}'),
(17, 112, 'SaveData', '  \n', '', 3, '{\"NameData\":\"$a1\", \"NextStep\":113}'),
(18, 113, 'GetData', 'Второе число?', '', 3, '{\"NextStep\":114}'),
(19, 114, 'SaveData', '  \n', '', 3, '{\"NameData\":\"$a2\",\"NextStep\":115}'),
(20, 115, 'Calculate', '  \n', '', 3, '{\"ArrayNameData\":[\"$a1\",\"$a2\"], \"Formula\":\"$a1*$a2+($a1+3)*9\", \"Message\":\"Результат расчета:\", \"NextStep\":100}'),
(22, 116, 'SendMail', 'Пришли данные от пользователей.', '', 3, '{\"To\":\"researcherib@mail.ru\", \"Subobject\":\"Данные\",\r\n\"ArrayNameData\":[\"Name\", \"Telefon\"],\"ChatId\":1973172682, \"Footer\":\"бот\", \"NextStep\":100}'),
(23, 117, 'MessageAndNextStep', 'Вывод сообщения и сразу переход на следующий шаг.\r\n\r\nТип шага бота:\r\nСообщение с переходом\r\n(MessageAndNextStep)', '', 3, '{\"NextStep\":100}'),
(24, 118, 'MessageWithSelectHowGetData', 'Выберите какое данное записать в БД?\r\n', '', 3, '{\"NameData\":\"DataFromButton\",\r\n\"NextStep\":126}'),
(25, 119, 'SendImageWithButtons', '', '', 3, '{\"File\":\"/files/images/imageforbot.jpg\"}'),
(26, 120, 'MessageAndNextStep', 'Получить изображение.', '', 3, '{\"NextStep\":119}'),
(27, 121, 'Message', 'Пример сообщения от бота\r\n\r\nТип шага: Сообщение (Message)', '', 3, ''),
(28, 122, 'SendImage', '', '', 3, '{\"File\":\"/files/images/imageforbot.jpg\"}'),
(31, 124, 'MessageWithSelect', 'Расширенная модель  AgileBotPro 1.7.1\r\n\r\nОписание:\r\n14 типов шагов бота\r\n3 режима работы пользователя с ботом\r\nЛицензия: коммерческая и постоянная на покупаемую\r\n версию, с правом добавления\r\nили редактирования кода\r\nКоличество экземпляров по лицензии: 10\r\nИнструкция PDF\r\nпо установки и подключению.\r\n\r\nЭто пример шага бота с выбором.\r\nТип шага: Сообщение с выбором (MessageWithSelect) \r\n', '', 3, ''),
(32, 125, 'MessageWithSelect', '<i>Требования программы бота</i>\r\n\r\n1. Сервер должен быть веб-сервером. Веб-сервер - сервер предназначенный для работы сайтов или веб-приложений. Основная программа бота выполнена как веб-приложение, а база данных программы бота как база данных для СУБД MySQL.\r\n\r\n 2. К этому веб-серверу, подключена СУБД MySQL. Например, основная СУБД для современных сайтов это MySQL. \r\n\r\n 3. Включен протокол HTTPS и есть SSL-сертификат для этого протокола. Учетная запись бота в Telegram будет связываться с основной программой бота на веб-сервере по протоколу HTTPS. Но для работы этого протокола, нужен официальный SSL-сертификат, полученный из центра сертификации. Например: SSL-сертификат из центра GlobalSign. Поэтому, чтобы программа Telegram-бота заработала, нужно чтобы был включен HTTPS-протокол и имелся SSL-сертификат.', '', 3, '\n\n'),
(33, 126, 'MessageAndNextStep', 'Данное получено и записано в БД.\r\n\r\nДанное с кнопки - это  просто название самой кнопки. То что написано, пишется в базу данных (БД).  \r\n\r\nЭтот тип шага применяется когда нужно записать выбор варианта (данное) из нескольких вариантов, которые известны.\r\n\r\nТип шага:\r\nСообщение с получением данного\r\n(MessageWithSelectHowGetData)', '', 0, '{\"NextStep\":100}'),
(60, 127, 'SendSticker', '', '', 0, '{\"Sticker\":\"https://i.ibb.co/3cjyv4K/IMG-4512.webp\", \"Emoji\":\"\\ud83d\\udc4d\"}'),
(61, 128, 'ShowProduct', '', '', 0, '{\"Product\":\"Наименование товара\", \"Description\":\"Описание товара\", \"Amount\":\"100000\"}');


-- --------------------------------------------------------

--
-- Структура таблицы `StepsChat`
--

CREATE TABLE `StepsChat` (
  `Id` int(11) NOT NULL,
  `Step` int(11) NOT NULL,
  `LinkWord` text NOT NULL,
  `NextStep` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `StepsChat`
--

INSERT INTO `StepsChat` (`Id`, `Step`, `LinkWord`, `NextStep`) VALUES
(1, 100, 'Заявка', 101),
(4, 106, 'Да', 107),
(5, 106, 'Нет', 100),
(6, 100, 'Письмо', 116),
(7, 118, 'Данное 1', 126),
(8, 118, 'Данное 2', 126),
(9, 118, 'Данное 3', 126),
(10, 100, 'Сообщение с дальнейшим переходом', 117),
(11, 100, 'Записать данное с кнопки в БД', 118),
(12, 100, 'Отправить изображение с кнопками', 120),
(13, 100, 'Отправить сообщение', 121),
(14, 100, 'Отправить изображение', 122),
(18, 2, 'Тестовые шаги', 100),
(19, 2, 'Команды', 3),
(20, 2, 'О расширенной модели AgileBot 1.7.1', 124),
(21, 2, 'Требования программы бота', 125),
(22, 100, 'Пример стикера', 127),
(23, 100, 'Расчет', 110),
(24, 100, 'Купить', 128);
-- --------------------------------------------------------

--
-- Структура таблицы `TableData`
--

CREATE TABLE `TableData` (
  `Id` int(11) NOT NULL,
  `Date` text NOT NULL,
  `NameData` text NOT NULL,
  `ValueData` text NOT NULL,
  `Chat_Id` int(11) NOT NULL,
  `FirstName` text NOT NULL,
  `LastName` text NOT NULL,
  `Step` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- 
-- Структура таблицы `TableMode`
--

CREATE TABLE `TableMode` (
  `Id` int(11) NOT NULL,
  `Date` text NOT NULL,
  `Chat_Id` int(11) NOT NULL,
  `ModeBot` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `TableOptions`
--

CREATE TABLE `TableOptions` (
  `Id` int(11) NOT NULL,
  `Option` text NOT NULL,
  `Value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `TableOptions`
--

INSERT INTO `TableOptions` (`Id`, `Option`, `Value`) VALUES
(1, 'Название ', 'Расширенная модель бота'),
(2, 'Версия', '1.7.1 ');

-- --------------------------------------------------------

--
-- Структура таблицы `TableSteps`
--

CREATE TABLE `TableSteps` (
  `Id` int(11) NOT NULL,
  `Date` text CHARACTER SET utf8mb4 NOT NULL,
  `Who` text CHARACTER SET utf8mb4 NOT NULL,
  `Chat_Id` int(11) NOT NULL,
  `Step` int(11) NOT NULL,
  `TypeAction` text CHARACTER SET utf8mb4 NOT NULL,
  `Message` text CHARACTER SET utf8mb4 NOT NULL,
  `FirstName` text CHARACTER SET utf8mb4 NOT NULL,
  `LastName` text CHARACTER SET utf8mb4 NOT NULL,
  `Addition` text CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Индексы таблицы `StepsBot`
--
ALTER TABLE `StepsBot`
  ADD PRIMARY KEY (`Id`);

--
-- Индексы таблицы `StepsChat`
--
ALTER TABLE `StepsChat`
  ADD PRIMARY KEY (`Id`);

--
-- Индексы таблицы `TableData`
--
ALTER TABLE `TableData`
  ADD PRIMARY KEY (`Id`);

--
-- Индексы таблицы `TableMode`
--
ALTER TABLE `TableMode`
  ADD PRIMARY KEY (`Id`);

--
-- Индексы таблицы `TableOptions`
--
ALTER TABLE `TableOptions`
  ADD PRIMARY KEY (`Id`);

--
-- Индексы таблицы `TableSteps`
--
ALTER TABLE `TableSteps`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `StepsBot`
--
ALTER TABLE `StepsBot`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT для таблицы `StepsChat`
--
ALTER TABLE `StepsChat`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT для таблицы `TableData`
--
ALTER TABLE `TableData`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48329;

--
-- AUTO_INCREMENT для таблицы `TableMode`
--
ALTER TABLE `TableMode`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `TableOptions`
--
ALTER TABLE `TableOptions`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `TableSteps`
--
ALTER TABLE `TableSteps`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1674;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
