<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022-2022 marcoschwepp
 * @author Marco Schweppe <marco.schweppe@gmx.de>
 * @version 1.0.0
 */

namespace marcoschwepp\Cookie;

final class CookieUtility
{
    public static function normalizeDomain(string $domain): ?string
    {
        $domainLength = \mb_strlen($domain);

        if (3 > $domainLength || 253 < $domainLength) {
            return '';
        }

        if (\mb_substr($domain, -1) === '.') {
            return '';
        }

        $domain = \ltrim(\ltrim($domain, 'https://'), 'http://');

        if (!\filter_var('http://' . $domain, \FILTER_VALIDATE_URL)) {
            return '';
        }

        return \sprintf('.%s', \ltrim($domain, '.'));
    }
}
