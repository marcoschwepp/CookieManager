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

        self::assertEmpty($newCookie->getValue());
    }
}