<h1>sobot is very simple library for create telegram bots</h1>

This library have very short document. read telegram methods for <a href="https://core.telegram.org/methods">bot api</a> first
<h2>Installtion</h2>

```
composer require hiradsajde/sobot
```

```
git clone https://github.com/hiradsajde/sobot
composer install # if need use composer
```

clone method usage need autoload file for include sobot\telegram\bot

<h2>Hello World Project</h2>

```
<?php
require 'vendor/autoload.php'; // autoload file
$bot = new sobot\telegram\bot('telegram token');
$bot->setWebhook('url');
if($bot->isUpdate()){
  $bot->getUpdate();
  $bot->sendMessage('hello world');
}
```

<h2>Library Syntax</h2>

```
  $bot->method(main_argument , [
      'any property' => 'value'
  ]);
```
for example we can use all of there for sendMessage

```
  $bot->sendMessage('hello world');
```

```
  $bot->sendMessage([
    'text' => 'hello world',
  ]);
```

```
  $bot->sendMessage('hello world' , [
    'parse_mode' => 'html',
  ]);
```

<h2>What Main Argument?</h2>

you can optionally use main argument in first parameter. for example sendMessage main argument is text.

```
  $bot->sendMessage('hello');
```

```
  $bot->sendMessage([
    'text' => 'hello'
  ]);
```
you can see more methods main argument in next title
<h2>Main Arguments list</h2>

| Method     | Argument |
| ---      | ---       |
| setWebhook     | url |
| sendMessage | text |
| editMessageText     | text |
| copyMessage     | chat_id |
| sendPhoto     | photo |

<a href="https://github.com/hiradsajde/sobot/blob/d32fa964d222eb021efd005688ff1b293bf0b05e/sobot/telegram/bot.php#L138">more main arguments in this link</a>
<h2>Default parameters</h2>
that's very nice option in this library is default parameters. for example we need to sendMessage sender in code. we do not have to repeat chat_id in code. we can jut use default property. for example

```
  $bot->default = [
    'sendMessage' => [
      'text' => 'blablabla',
    ],
  ];
  $bot->sendMessage();
```

you can use this structure for set default properties

```
  $bot->default = [
    'method1' => [
      'x' => 'y',
    ],
    'method2' => [
      'x' => 'y',
    ],
    ...
  ];
```
and we recive blablabla with out errors :) 
but why we aren't said default chat_id is $bot->chat_id, and message was sent? we have lot of default parameters
sobot merge <a href="https://github.com/hiradsajde/sobot/blob/d32fa964d222eb021efd005688ff1b293bf0b05e/sobot/telegram/bot.php#L136">getDefault method</a> to your default property and gave you truest code with very less coding

<h2>Sobot Default Property</h2>
see one example of usage properties

```
  echo $bot->chat_id;
```
you can see sobot properties for manage telegram json request <a href="https://github.com/hiradsajde/sobot/blob/d32fa964d222eb021efd005688ff1b293bf0b05e/sobot/telegram/bot.php#L58">here</a>

<h2>Manage Keyboards</h2>

if you need to manage your keyboards with sobot... we have 2 methods for this. 

```
  $bot->keyboard([
    [['text' => 'blablabla']],
  ]);
```

```
  $bot->inline_keyboard([
    [['text' => 'blablabla' , 'callback_data' => 'value]],
  ]);
```

open source  project for learn sobot : 
<ol>
  <li><a href="https://github.com/hiradsajde/wp-telegram"> wordpress post reader bot for telegram </a></li>
</ol> 
