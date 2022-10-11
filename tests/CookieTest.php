<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022-2022 marcoschwepp
 * @author Marco Schweppe <marco.schweppe@gmx.de>
 * @version 1.0.0
 */

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \marcoschwepp\Cookie\Cookie
 */
final class CookieTest extends TestCase
{
    public function testDefaults(): void
    {
        $newCookie = new marcoschwepp\Cookie\Cookie('testCookie');

        self::assertSame($newCookie->getName(), 'testCookie');
        self::assertSame($newCookie->getPath(), '/');

        self::assertFalse($newCookie->isSecure());
        self::assertFalse($newCookie->isHttpOnly());

        self::assertEmpty($newCookie->getValue());

        self::assertInstanceOf(\DateTimeImmutable::class, $newCookie->getExpiresAt());
    }

    public function testConstructFromOptions(): void
    {
        $options = [
            'name' => 'testCookie',
        ];

        $newCookieFromOptions = marcoschwepp\Cookie\Cookie::constructFromOptions($options);

        self::assertSame($newCookieFromOptions->getName(), 'testCookie');
        self::assertSame($newCookieFromOptions->getPath(), '/');

        self::assertFalse($newCookieFromOptions->isSecure());
        self::assertFalse($newCookieFromOptions->isHttpOnly());

        self::assertEmpty($newCookieFromOptions->getValue());

        self::assertInstanceOf(\DateTimeImmutable::class, $newCookieFromOptions->getExpiresAt());
    }

    public function testGettersAndSetters(): void
    {
        $options = [
            'name' => 'Test-Cookie',
            'value' => 'Test-Value',
            'expiresAt' => new \DateTimeImmutable(),
            'path' => '/',
            'domain' => 'local.de',
            'secure' => true,
            'httpOnly' => true,
        ];

        $cookie = marcoschwepp\Cookie\Cookie::constructFromOptions($options);

        self::assertSame($cookie->getName(), 'Test-Cookie');
        self::assertSame($cookie->getValue(), 'Test-Value');
        self::assertSame($cookie->getPath(), '/');
        self::assertSame($cookie->getDomain(), '.local.de');

        self::assertTrue($cookie->isSecure());
        self::assertTrue($cookie->isHttpOnly());

        self::assertInstanceOf(\DateTimeImmutable::class, $cookie->getExpiresAt());
    }
}
