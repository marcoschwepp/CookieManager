<?php

class DataProvider
{
	public static function domain(): array {
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

	public static function cookie(): array {
		$faker = Faker\Factory::create();

		$domain = $faker->randomElement(self::domain());
		$path = $faker->randomElement(['/', '/test', '/test/test', 'x', 'x/y', 'x/y/', 'x/y/z/']);

		return [
			[$faker->text(), $faker->text(), new \DateTimeImmutable($faker->time()), $path, $domain[0], $faker->boolean(), $faker->boolean(), $domain[1]],
			[$faker->text(), $faker->text(), new \DateTimeImmutable($faker->time()), $path, $domain[0], $faker->boolean(), $faker->boolean(), $domain[1]],
			[$faker->text(), $faker->text(), new \DateTimeImmutable($faker->time()), $path, $domain[0], $faker->boolean(), $faker->boolean(), $domain[1]],
			[$faker->text(), $faker->text(), new \DateTimeImmutable($faker->time()), $path, $domain[0], $faker->boolean(), $faker->boolean(), $domain[1]],
		];
	}
}
