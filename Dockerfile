FROM yiisoftware/yii2-php:8.3-fpm-nginx

# Change document root for nginx
RUN sed -i -e 's|/app/web|/app/web|g' /etc/nginx/conf.d/default.conf
RUN apt-get update
RUN apt install -y software-properties-common
RUN apt install -y libldap2-dev && docker-php-ext-install ldap 
RUN echo "0 3 * * * /app/yii weather/sync"  >> /etc/cron.d/sikk-cronjob
RUN chmod 0644 /etc/cron.d/sikk-cronjob
RUN crontab /etc/cron.d/sikk-cronjob
RUN service cron start