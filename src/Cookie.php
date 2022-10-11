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

    public function __construct(
        string $name,
        string $value = '',
        int $expires = 0,
        string $path = '/',
        ?string $domain = '',
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

        return new self($options['name'], $options['value'], $options['expires'], $options['path'], CookieUtility::normalizeDomain($options['domain']), $options['secure'], $options['httpOnly']);
    }

    public function getName(): string
    {
        return $this->name;
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
        $this->domain = CookieUtility::normalizeDomain($domain);
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

    public static function load(string $name): ?self
    {
        if (!\array_key_exists($name, $_COOKIE)) {
            return null;
        }

        $newCookie = $_COOKIE[$name];

        return new self($name, $newCookie['value'], $newCookie['expires'], $newCookie['path'], CookieUtility::normalizeDomain($newCookie['domain']), $newCookie['secure'], $newCookie['httpOnly']);
    }

    public function delete(): void
    {
        if (!\array_key_exists($this->name, $_COOKIE)) {
            return;
        }

        unset($_COOKIE[$this->name]);
    }

    public function save(): ?self
    {
        $cookieIsSet = \setcookie(
            $this->name,
            $this->value,
            $this->expires,
            $this->path,
            $this->domain,
            $this->secure,
            $this->httpOnly,
        );

        if (!$cookieIsSet) {
            return null;
        }

        return self::load($this->name);
    }
}
