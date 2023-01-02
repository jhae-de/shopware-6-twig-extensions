MAKEFILE := $(firstword $(MAKEFILE_LIST))

.PHONY: help
help: ## Display help
	@printf "JHAE Twig Extensions Makefile\n\n\033[33mUsage:\033[0m\n  make [target]\n\n\033[33mTargets:\033[0m\n"
	@awk 'BEGIN {FS = ":.*?## "} /^[0-9a-zA-Z_-]+:.*?## / {printf "  \033[32m%-22s\033[0m %s\n", $$1, $$2}' $(MAKEFILE)

.PHONY: test
test: ## Run PHPUnit
	@composer run-script test

.PHONY: test-coverage
test-coverage: ## Run PHPUnit with code coverage as text output
	@composer run-script test:coverage

.PHONY: test-coverage-html
test-coverage-html: ## Run PHPUnit with code coverage as html output
	@composer run-script test:coverage-html
