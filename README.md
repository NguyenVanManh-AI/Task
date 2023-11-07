```bash
$ git clone git@github.com:NguyenVanManh-AI/Task.git
```

Run API Laravel  
```bash
$ cd BE  
$ composer i  
$ npm i 
$ copy .env.example .env
$ rmdir /s /q public\storage
$ php artisan config:clear
$ php artisan route:clear
$ php artisan optimize:clear
$ php artisan route:cache
$ php artisan optimize
$ php artisan key:generate
$ php artisan jwt:secret
$ php artisan cache:clear
$ php artisan storage:link
$ php artisan migrate:fresh --seed 
$ php artisan serve 
```

Run Vuejs 
```bash
$ cd FE  
$ npm i 
$ npm run serve
```

http://localhost:8080

