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
 * @covers \marcoschwepp\Cookie\CookieUtility
 */
final class CookieUtilityTest extends TestCase
{
	public function testNormalizeDomain(): void
	{
		$domain1 = marcoschwepp\Cookie\CookieUtility::normalizeDomain('www.google.de');
		$domain2 = marcoschwepp\Cookie\CookieUtility::normalizeDomain('www.google');
		$domain3 = marcoschwepp\Cookie\CookieUtility::normalizeDomain('google.de');
		$domain4 = marcoschwepp\Cookie\CookieUtility::normalizeDomain('domain-@test.@de');
		$domain5 = marcoschwepp\Cookie\CookieUtility::normalizeDomain('xn--fsqu00a.xn--0zwm56d');
		$domain6 = marcoschwepp\Cookie\CookieUtility::normalizeDomain('..');
		$domain7 = marcoschwepp\Cookie\CookieUtility::normalizeDomain('.');
		$domain8 = marcoschwepp\Cookie\CookieUtility::normalizeDomain('-_-');
		$domain9 = marcoschwepp\Cookie\CookieUtility::normalizeDomain('---');
		$domain10 = marcoschwepp\Cookie\CookieUtility::normalizeDomain('');
		$domain11 = marcoschwepp\Cookie\CookieUtility::normalizeDomain('www.test.de.');

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
		self::assertNull($domain11);
	}
}
