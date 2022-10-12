<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022-2022 marcoschwepp
 * @author Marco Schweppe <marco.schweppe@gmx.de>
 * @version 1.0.0
 */

namespace marcoschwepp\Cookie\Test;

use marcoschwepp\Cookie\Cookie;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \marcoschwepp\Cookie\Cookie
 */
final class CookieTest extends TestCase
{
    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::arbitrary()
     */
    public function testDefaults(string $name): void
    {
        $newCookie = new \marcoschwepp\Cookie\Cookie($name);

        self::assertSame($newCookie->getName(), $name);
        self::assertSame($newCookie->getPath(), '/');
        self::assertSame($newCookie->getDomain(), '');
        self::assertFalse($newCookie->isSecure());
        self::assertFalse($newCookie->isHttpOnly());
        self::assertEmpty($newCookie->getValue());
        self::assertInstanceOf(\DateTimeImmutable::class, $newCookie->getExpiresAt());
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::arbitrary()
     */
    public function testConstructFromOptions(string $name): void
    {
        $options = [
            'name' => $name,
        ];

        $newCookieFromOptions = \marcoschwepp\Cookie\Cookie::constructFromOptions($options);

        self::assertSame($newCookieFromOptions->getName(), $name);
        self::assertSame($newCookieFromOptions->getPath(), '/');
        self::assertFalse($newCookieFromOptions->isSecure());
        self::assertEmpty($newCookieFromOptions->getDomain());
        self::assertFalse($newCookieFromOptions->isHttpOnly());
        self::assertEmpty($newCookieFromOptions->getValue());
        self::assertInstanceOf(\DateTimeImmutable::class, $newCookieFromOptions->getExpiresAt());
    }

    /**
     * @dataProvider \marcoschwepp\Cookie\Test\DataProvider::cookie()
     */
    public function testGettersAndSetters(
        string $name,
        string $value,
        \DateTimeImmutable $expiresAt,
        string $path,
        string $domain,
        bool $secure,
        bool $httpOnly,
        string $expectedDomain
    ): void {
        $options = [
            'name' => $name,
            'value' => $value,
            'expiresAt' => $expiresAt,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httpOnly' => $httpOnly,
        ];

        $cookie = \marcoschwepp\Cookie\Cookie::constructFromOptions($options);

        self::assertSame($cookie->getName(), $name);
        self::assertSame($cookie->getValue(), $value);
        self::assertSame($cookie->getExpiresAt(), $expiresAt);
        self::assertInstanceOf(\DateTimeImmutable::class, $cookie->getExpiresAt());
        self::assertSame($cookie->getPath(), $path);
        self::assertSame($cookie->getDomain(), $expectedDomain);
        self::assertSame($cookie->isSecure(), $secure);
        self::assertSame($cookie->isHttpOnly(), $httpOnly);
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::uuid()
     */
    public function testCanSaveCookie(string $name): void
    {
        $options = [
            'name' => $name,
            'domain' => 'google.com',
        ];

        $cookie = \marcoschwepp\Cookie\Cookie::constructFromOptions($options);

        $result = $cookie->save();

        self::assertNull($result);
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::arbitrary()
     */
    public function testDeleteWhenCookieWasNotSaved(string $name): void
    {
        $options = [
            'name' => $name,
        ];

        $cookie = \marcoschwepp\Cookie\Cookie::constructFromOptions($options);
        $cookie->delete();

        self::assertArrayNotHasKey($name, $_COOKIE);
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::arbitrary()
     */
    public function testSetCookieValue(string $name): void
    {
        $faker = Faker\Factory::create();

        $options = [
            'name' => $name,
        ];
        $value = $faker->text(20);

        $cookie = \marcoschwepp\Cookie\Cookie::constructFromOptions($options);
        $cookie->setValue($value);

        self::assertSame($value, $cookie->getValue());
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::arbitrary()
     */
    public function testSetAndGetExpiresAt(string $name): void
    {
        $options = [
            'name' => $name,
        ];

        $faker = Faker\Factory::create();

        $expires = new DateTimeImmutable($faker->date('Y-m-d H:i:s', '+1 year'));

        $cookie = \marcoschwepp\Cookie\Cookie::constructFromOptions($options);

        $cookie->expiresAt($expires);

        self::assertSame($cookie->getExpiresAt(), $expires);
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::arbitrary()
     */
    public function testSetExpiresIn(string $name): void
    {
        $options = [
            'name' => $name,
        ];
        $faker = Faker\Factory::create();
        $cookie = \marcoschwepp\Cookie\Cookie::constructFromOptions($options);
        $seconds = $faker->numberBetween(1000, 999999999);
        $cookie->expiresIn($seconds);
        $expectedDate = new \DateTimeImmutable(\sprintf('+ %s seconds', $seconds));

        self::assertSame($expectedDate->format('Y-m-d H:i:s'), $cookie->getExpiresAt()->format('Y-m-d H:i:s'));
    }

    /**
     * @dataProvider expiredDataProvider
     */
    public function testCookieIsExpired(string $modifier, bool $expected): void
    {
        $faker = \Faker\Factory::create();
        $options = [
            'name' => $faker->word(),
        ];
        $cookie = \marcoschwepp\Cookie\Cookie::constructFromOptions($options);

        $cookie->expiresAt(new \DateTimeImmutable($modifier));

        self::assertSame($expected, $cookie->isExpired());
    }

    public function expiredDataProvider(): array
    {
        $faker = \Faker\Factory::create();
        $days = $faker->numberBetween(2, 999);
        $minutes = $faker->numberBetween(1, 9999);

        return [
            [\sprintf('- %s days', $days), true],
            [\sprintf('+ %s days', $days), false],
            ['- 1 day', true],
            ['+ 1 day', false],
            [\sprintf('- %s minutes', $minutes), true],
            [\sprintf('+ %s minutes', $minutes), false],
        ];
    }
}
