<?php declare(strict_types=1);

namespace marcoschwepp\Cookie;

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

   public function __construct(string $name)
   {
        $this->name = $name;
        $this->value = '';
        $this->expires = 0;
        $this->path = '/';
        $this->domain = null;
        $this->secure = false;
        $this->httpOnly = true;
        $this->options = [];
    }

    public static function fromString(string $name): self {
        return new self($name);
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        if ('' === $name) {
            return;
        }

		$this->name = $name;
    }

    public function getValue(): string {
        return $this->value;
    }

    public function setValue(string $value): void {
        $this->value = $value;
    }

    public function getExpires(): int {
        return $this->expires;
    }

    public function setExpires(int $expires): void {
        $this->expires = $expires;
    }

    public function getPath(): string {
        return $this->path;
    }

    public function setPath(string $path): void {
        $this->path = $path;
    }

    public function getDomain(): string {
        return $this->domain;
    }

    public function setDomain(string $domain): void {
        $this->domain = $domain;
    }

    public function getSecure(): bool {
        return $this->secure;
    }

    public function setSecure(bool $secure): void {
        $this->secure = $secure;
    }

    public function getHttpOnly(): bool {
        return $this->httpOnly;
    }

    public function setHttpOnly(bool $httpOnly): void {
        $this->httpOnly = $httpOnly;
    }

    public function getOptions(): array {
        return $this->options;
    }

    public function setOptions(array $options): void {
        $this->options = $options;
    }

    public function getCookie(string $name): mixed {
        if (!\array_key_exists($name, $_COOKIE)) {
            return null;
        }

        return $_COOKIE[$name];
    }

    public function deleteCookie(string $name): bool {
        if (isset($_COOKIE[$name])) {
            unset($_COOKIE[$name]);

            return true;
        }

        return false;
    }

    public function saveCookie(Cookie $cookie): bool {

    }

    public function cookieExists(string $name): bool {
        return isset($_COOKIE[$name]);
    }
}
