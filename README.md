#Welcome to Community App APIs

###Intro
Purpose of this APIs are provide interface to application to get Questions their Answer & data of reactions of users on them.

###Technology & Tools
PHP 8.0.14
MySQL 5.7.33
Laravel 8.12.

####instructions
1. `composer install`
2. copy content from .env.example to .env and update the configirations (specially for mysql)
3. `php artisan key:generate` - to generate app key
4. `php artisan migrate` - to create necessary table
5. `php artisan db:seed` - to create dummy data
6. `php artisan serve` - to run app


#####Important notes
- Please use Insomnia for test APIs 
- You can find .insomnia_api.json in root directory


> Incase you need help or want to give suggestions, please contact me on vishal@tarkar.com