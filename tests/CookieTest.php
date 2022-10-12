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

        $result = @$cookie->save();

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

		$expires = new DateTimeImmutable('now');
		$expires = $expires->modify('+1 day');

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
		$cookie = \marcoschwepp\Cookie\Cookie::constructFromOptions($options);
		$cookie->expiresIn(86400); // use data provider for that?
		$formatedDate = new \DateTimeImmutable(sprintf('+ %s seconds', 86400));
		$formatedDate = $formatedDate->format('Y-m-d H:i:s');

		self::assertSame($formatedDate, $cookie->getExpiresAt()->format('Y-m-d H:i:s'));
	}

	/**
	 * @dataProvider \Ergebnis\DataProvider\StringProvider::arbitrary()
	 */
	public function testCookieIsExpired(string $name): void
	{
		$options = [
			'name' => $name,
		];
		$expiredCookie = \marcoschwepp\Cookie\Cookie::constructFromOptions($options);
		$validCookie = \marcoschwepp\Cookie\Cookie::constructFromOptions($options);

		$expiredCookie->expiresAt(new \DateTimeImmutable(sprintf('- %s day', 1)));
		$validCookie->expiresAt(new \DateTimeImmutable(sprintf('+ %s day', 1)));

		self::assertTrue($expiredCookie->isExpired());
		self::assertFalse($validCookie->isExpired());
	}
}
