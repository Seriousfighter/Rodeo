DOCKER DEPLOY DEVELOPMENT INSTRUCCTION:

V1: 
1) in the root folder of the app (where dockerfile is), run docker compose up -d --build
2) run docker compose exec -it rodeo-app /bin/sh
3) once inside the conteiner do: 
    - php artisan key:generate
    - php artisan migrate
    - php storage:link


if everithings goes well... you should get the laravel jetstream app at localhost:9001

else permision errorsd


