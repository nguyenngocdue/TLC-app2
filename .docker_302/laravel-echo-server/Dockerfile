FROM node:12.13-alpine

WORKDIR /var/www/websocket

COPY laravel-echo-server.json /var/www/websocket/laravel-echo-server.json
COPY ./configs/certs /path/ssl/certs/

RUN npm install -g laravel-echo-server

# EXPOSE 6001

CMD ["laravel-echo-server", "start"]
