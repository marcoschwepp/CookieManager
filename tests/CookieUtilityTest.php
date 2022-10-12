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
	/** @dataProvider domainProvider */
    public function testNormalizeDomain(string $input, ?string $expected): void
    {
		$normalizedDomain = marcoschwepp\Cookie\CookieUtility::normalizeDomain($input);

		self::assertEquals($expected, $normalizedDomain);
    }

	public function domainProvider(): array
	{
		return [
			['www.google.de', '.www.google.de'],
			['www.google', '.www.google'],
			['google.de', '.google.de'],
			['domain-@test.@de', null],
			['xn--fsqu00a.xn--0zwm56d', '.xn--fsqu00a.xn--0zwm56d'],
			['..', null],
			['.', null],
			['-_-', null],
			['---', null],
			['', null],
			['www.test.de.', null],
			['ab', null],
			['https://google.com', '.google.com'],
			['https://www.google.com', '.www.google.com'],
		];
	}
}
