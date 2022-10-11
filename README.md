# PHP-Cookie

Easy cookie management with php

## Requirements

 * PHP >= 7.4

## Installation

## Usage

### Create Cookie

There are two methods to create a new Cookie object.

- Create a new instance of the Cookie class: 

```php
// only the name is required.
$cookie = new marcoschwepp\Cookie\Cookie('testCookie');

// optional parameters: 
$cookie->setValue('123456');
$cookie->setExpiresAt(new \DateTimeImmutable()) // e.g. timestamp now + 24h = \time() + 86400
$cookie->setPath('/');
$cookie->setDomain('.local.de');
$cookie->setSecure(false);
$cookie->setHttpOnly(false);
```

- Create from options array and static method:

```php
$options = [
    'name' => 'Test-Cookie',
    'value' => 'Test-Value',
    'expiresAt' => new \DateTimeImmutable,
    'path' => '/',
    'domain' => 'local.de',
    'secure' => true,
    'httpOnly' => true,
];

$cookie = marcoschwepp\Cookie\Cookie::constructFromOptions($options);
```

### Save, Delete, Read

```php
$cookie->save();
$cookie->delete('testCookie');
$cookie->load();
```

## Contributing

All contributions are welcome! If you wish to contribute, please create an issue first so that your feature, problem or question can be discussed.

## License

This project is licensed under the terms of the [MIT License](https://opensource.org/licenses/MIT).
