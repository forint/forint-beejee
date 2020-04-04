Task Manager
========================

Welcome to the Task Manager

Installation 
--------------

Run composer install

You can create clear mysql database with file: db_only_structure.sql

Or you can import entries with sample data with file: rozetka_task.sql

You will also have to set up virtual host:

copy and modify data from virtual_host.conf file and modify it to fit your needs

required options are ServerName and DocumentRoot


Utilization 
--------------

Login page: http://you-host-name/auth/login

To sign in into administrator panel you can use default credentials: 

admin_username: admin
admin_password: 123

Or you can change this data in /config.yml and log in with your username and password.
