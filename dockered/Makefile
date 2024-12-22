.PHONY: help build up down logs shell test clean

help: ## Show this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Build containers
	docker-compose build

up: ## Start containers
	docker-compose up -d

down: ## Stop containers
	docker-compose down

logs: ## View container logs
	docker-compose logs -f

shell: ## Enter app container as root
	docker-compose exec -u root app sh

test: ## Run tests
	docker-compose exec app php artisan test

clean: ## Remove containers, volumes, and images
	docker-compose down -v --rmi all

install: ## Install Laravel dependencies
	docker-compose exec app composer install
	docker-compose exec app npm install

migrate: ## Run database migrations
	docker-compose exec app php artisan migrate

cache: ## Clear Laravel cache
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan route:clear
	docker-compose exec app php artisan view:clear