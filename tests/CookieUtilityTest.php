<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022-2022 marcoschwepp
 * @author Marco Schweppe <marco.schweppe@gmx.de>
 * @version 1.0.0
 */

namespace marcoschwepp\Cookie\Test;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \marcoschwepp\Cookie\CookieUtility
 */
final class CookieUtilityTest extends TestCase
{
    /**
     * @dataProvider \marcoschwepp\Cookie\Test\DataProvider::domain()
     */
    public function testNormalizeDomain(string $input, ?string $expected): void
    {
        $normalizedDomain = \marcoschwepp\Cookie\CookieUtility::normalizeDomain($input);

        self::assertEquals($expected, $normalizedDomain);
    }
}
