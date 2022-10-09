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

	public function __construct(
		string $name,
		string $value = '',
		int $expires = 0,
		string $path = '/',
		?string $domain = null,
		bool $secure = false,
		bool $httpOnly = false,
		array $options = []
	) {
		$this->name = $name;
		$this->value = $value;
		$this->expires = $expires;
		$this->path = $path;
		$this->domain = $domain;
		$this->secure = $secure;
		$this->httpOnly = $httpOnly;
		$this->options = $options;
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

	public function isExpired(): bool {
		return $this->expires < time();
	}

	public function getRemainingTime(): int {
		return $this->expires - \time();
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

	/**
	 * @return self
	 */
	public static function load(string $name) {
		if (!\array_key_exists($name, $_COOKIE)) {
			return null;
		}

		//return $_COOKIE[$name];

		return null;
	}

	public function delete(): void {
		if (!\array_key_exists($this->name, $_COOKIE)) {
			return;
		}

		unset($_COOKIE[$this->name]);
	}

	public function save(): self {
		setcookie(
			$this->name,
			$this->value,
			$this->expires,
			$this->path,
			$this->domain,
			$this->secure,
			$this->httpOnly,
		);

		return self::get($this->name);
	}
}
