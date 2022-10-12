.PHONY: test
test: composer.json composer.lock
	vendor/bin/phpunit --configuration=phpunit.xml

.PHONY: code-coverage
code-coverage: composer.json composer.lock
	vendor/bin/phpunit --configuration=phpunit.xml --coverage-html .build
	open .build/index.html -a "google chrome"

.PHONY: coding-standards
coding-standards: composer.json composer.lock
	mkdir -p .build/php-cs-fixer
	vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php --diff --verbose
