.PHONY: test
test: composer.json composer.lock
	vendor/bin/phpunit --configuration=phpunit.xml

.PHONY: code-coverage
code-coverage: composer.json composer.lock
	vendor/bin/phpunit --configuration=phpunit.xml --coverage-html .build
	open .build/index.html -a "google chrome"
