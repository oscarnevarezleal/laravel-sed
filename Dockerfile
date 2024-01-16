FROM composer:2.2

ENV LARASED_HOME=/var/laravel-sed
ENV LARAVEL_APP_DIR=/var/app

WORKDIR $LARASED_HOME

ADD . .
RUN composer install

RUN curl -SsLo styleci.phar https://github.com/StyleCI/CLI/releases/download/v1.5.1/styleci.phar \
    && chmod +x styleci.phar && mv styleci.phar /usr/local/bin/styleci \
    && curl -L https://cs.symfony.com/download/php-cs-fixer-v3.phar -o php-cs-fixer \
    && mv php-cs-fixer /usr/local/bin/php-cs-fixer

VOLUME $LARASED_HOME/scripts
VOLUME $LARAVEL_APP_DIR

RUN ["chmod", "+x", "/var/laravel-sed/scripts/entrypoint.sh"]

ENTRYPOINT ["/var/laravel-sed/scripts/entrypoint.sh"]
CMD ["list"]
