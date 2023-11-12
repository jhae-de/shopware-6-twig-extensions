PROJECT_NAME = JHAE Twig Extensions

.DEFAULT_GOAL = help
TARGET_DESCRIPTION_INDENTATION = 20

.PHONY: help
help: ## Display this help
	@printf "\n\033[1;30m$(shell echo $(PROJECT_NAME)) Makefile\033[0m\n\n\033[33mUsage:\033[0m\n  make [target]\n\n\033[33mTargets:\033[0m\n"
	@awk 'BEGIN {FS = ":.*?## "} /^[0-9a-zA-Z_-]+:.*?## / {printf "  \033[32m%-$(TARGET_DESCRIPTION_INDENTATION)s\033[0m %s\n", $$1, $$2}' $(firstword $(MAKEFILE_LIST))

.PHONY: test
test: ## Run PHPUnit
	@composer run-script test

.PHONY: test-coverage
test-coverage: ## Run PHPUnit with code coverage as text output
	@composer run-script test:coverage

.PHONY: test-coverage-html
test-coverage-html: ## Run PHPUnit with code coverage as html output
	@composer run-script test:coverage-html
