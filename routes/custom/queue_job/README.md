- composer require predis/predis
- setting .env
	+ REDIS_CLIENT=predis
	+ QUEUE_CONNECTION=redis
- php artisan queue:work