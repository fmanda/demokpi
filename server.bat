@ECHO OFF
ECHO Starting server.
cd server
cd public
php -S 127.0.0.1:8000
PAUSE