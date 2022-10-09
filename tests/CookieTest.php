<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use PHPUnit\Framework\TestCase;

final class CookieTest extends TestCase
{
    public function testDefaults(): void
    {
        $newCookie = new marcoschwepp\Cookie\Cookie('testCookie');

        self::assertSame($newCookie->getName(), 'testCookie');
        self::assertSame($newCookie->getExpires(), 0);
        self::assertSame($newCookie->getPath(), '/');
        
        self::assertFalse($newCookie->isSecure());
        self::assertFalse($newCookie->isHttpOnly());

        self::assertEmpty($newCookie->getValue());
    }

    public function testConstructFromOptions(): void
    {
        $options = [
            'name' => 'testCookie',
        ];

        $newCookieFromOptions = marcoschwepp\Cookie\Cookie::constructFromOptions($options);

        self::assertSame($newCookieFromOptions->getName(), 'testCookie');
        self::assertSame($newCookieFromOptions->getExpires(), 0);
        self::assertSame($newCookieFromOptions->getPath(), '/');
        
        self::assertFalse($newCookieFromOptions->isSecure());
        self::assertFalse($newCookieFromOptions->isHttpOnly());

        self::assertEmpty($newCookieFromOptions->getValue());
    }
}