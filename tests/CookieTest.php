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
 * @coversNothing
 */
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

    public function testNormalizeDomain(): void
    {
        $domain1 = marcoschwepp\Cookie\Cookie::normalizeDomain('www.google.de');
        $domain2 = marcoschwepp\Cookie\Cookie::normalizeDomain('www.google');
        $domain3 = marcoschwepp\Cookie\Cookie::normalizeDomain('google.de');
        $domain4 = marcoschwepp\Cookie\Cookie::normalizeDomain('domain-@test.@de');
        $domain5 = marcoschwepp\Cookie\Cookie::normalizeDomain('xn--fsqu00a.xn--0zwm56d');
        $domain6 = marcoschwepp\Cookie\Cookie::normalizeDomain('..');
        $domain7 = marcoschwepp\Cookie\Cookie::normalizeDomain('.');
        $domain8 = marcoschwepp\Cookie\Cookie::normalizeDomain('-_-');
        $domain9 = marcoschwepp\Cookie\Cookie::normalizeDomain('---');
        $domain10 = marcoschwepp\Cookie\Cookie::normalizeDomain('');

        self::assertSame($domain1, '.www.google.de');
        self::assertSame($domain2, '.www.google');
        self::assertSame($domain3, '.google.de');
        self::assertSame($domain5, '.xn--fsqu00a.xn--0zwm56d');

        self::assertNull($domain4);
        self::assertNull($domain6);
        self::assertNull($domain7);
        self::assertNull($domain8);
        self::assertNull($domain9);
        self::assertNull($domain10);
    }

    public function testCreateAndSaveCookie(): void
    {
        $options = [
            'name' => 'Test-Cookie',
            'value' => 'Test-Value',
            'expires' => \time() + 86400,
            'path' => '/',
            'domain' => 'local.de',
            'secure' => true,
            'httpOnly' => true,
        ];

        $cookie = marcoschwepp\Cookie\Cookie::constructFromOptions($options);
        $cookie->save();

        self::assertSame($cookie->getName(), 'Test-Cookie');
        self::assertSame($cookie->getValue(), 'Test-Value');
        self::assertSame($cookie->getExpires(), \time() + 86400);
        self::assertSame($cookie->getPath(), '/');
        self::assertSame($cookie->getDomain(), '.local.de');

        self::assertTrue($cookie->isSecure());
        self::assertTrue($cookie->isHttpOnly());
    }
}
