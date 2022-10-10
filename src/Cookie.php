<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022-2022 marcoschwepp
 * @author Marco Schweppe <marco.schweppe@gmx.de>
 * @version 1.0.0
 */

namespace marcoschwepp\Cookie;

use Symfony\Component\OptionsResolver\OptionsResolver;

final class Cookie
{
    private string $name;

    private string $value;

    private int $expires;

    private string $path;

    private ?string $domain;

    private bool $secure;

    private bool $httpOnly;

    private array $options;

    public function __construct(
        string $name,
        string $value = '',
        int $expires = 0,
        string $path = '/',
        ?string $domain = null,
        bool $secure = false,
        bool $httpOnly = false
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->expires = $expires;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
    }

    public static function constructFromOptions(
        array $options
    ): self {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'name' => '',
            'value' => '',
            'expires' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httpOnly' => false,
        ]);

        $options = $resolver->resolve($options);

        return new self($options['name'], $options['value'], $options['expires'], $options['path'], self::normalizeDomain($options['domain']), $options['secure'], $options['httpOnly']);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        if ('' === $name) {
            return;
        }

        $this->name = $name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getExpires(): int
    {
        return $this->expires;
    }

    public function setExpires(int $expires): void
    {
        $this->expires = $expires;
    }

    public function isExpired(): bool
    {
        return \time() > $this->expires;
    }

    public function getRemainingTime(): int
    {
        return $this->expires - \time();
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): void
    {
        $this->domain = self::normalizeDomain($domain);
    }

    public function isSecure(): bool
    {
        return $this->secure;
    }

    public function setSecure(bool $secure): void
    {
        $this->secure = $secure;
    }

    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    public function setHttpOnly(bool $httpOnly): void
    {
        $this->httpOnly = $httpOnly;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    public static function load(string $name): ?self
    {
        if (!\array_key_exists($name, $_COOKIE)) {
            return NULL;
        }

        return self::load($name);
    }

    public function delete(): void
    {
        if (!\array_key_exists($this->name, $_COOKIE)) {
            return;
        }

        unset($_COOKIE[$this->name]);
    }

    public function save(): self
    {
        \setcookie(
            $this->name,
            $this->value,
            $this->expires,
            $this->path,
            $this->domain ?? '',
            $this->secure,
            $this->httpOnly,
        );

        return self::load($this->name);
    }

    public static function normalizeDomain(string $domain): ?string
	{
        $domain_len = \mb_strlen($domain);

        // check for domain length to not continue if not necessary
        if (3 > $domain_len || 253 < $domain_len) {
            return null;
        }

        // a dot as last character is not valid as a domain
        if (\mb_substr($domain, -1) === '') {
            return null;
        }

        // remove unnecessary http or https
        if (\mb_stripos($domain, 'http://') === 0) {
            $domain = \mb_substr($domain, 7);
        } elseif (\mb_stripos($domain, 'https://') === 0) {
            $domain = \mb_substr($domain, 8);
        }

        // validate url with http tag to be able to use the filter
        if (!\filter_var('http://' . $domain, \FILTER_VALIDATE_URL)) {
            return null;
        }

        // if dot is not first character, prepend a dot for maximum compatibility
        if ('.' !== $domain[0]) {
            $domain = '.' . $domain;
        }

        return $domain;
    }
}
