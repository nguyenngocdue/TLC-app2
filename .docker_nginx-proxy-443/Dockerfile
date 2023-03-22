FROM nginx

RUN apt-get update
RUN apt-get install nano mc tree apt-utils -y

COPY ./configs/nginx.conf /etc/nginx/nginx.conf
COPY ./configs/certs/ssl-cert-snakeoil.pem /etc/ssl/certs/
COPY ./configs/certs/ssl-cert-snakeoil.key /etc/ssl/private/

#this to create the folder
WORKDIR /etc/nginx/sites-enabled
COPY ./sites-enabled/* /etc/nginx/sites-enabled/

#No need as it will load when nginx started
#RUN service reload nginx