FROM alpine:3.11
ARG PHP_VERSION=7.4
ARG USER_ID=1001
ARG GROUP_ID=1001
ENV LARASED_HOME=/var/laravel-sed
ENV LARAVEL_APP_DIR=/var/app
ENV APK_DEL="curl"

WORKDIR $LARASED_HOME

ADD . .

ADD https://dl.bintray.com/php-alpine/key/php-alpine.rsa.pub /etc/apk/keys/php-alpine.rsa.pub
RUN apk --update add ca-certificates && \
    echo "https://dl.bintray.com/php-alpine/v3.11/php-${PHP_VERSION}" >> /etc/apk/repositories

RUN addgroup $USER_ID && \
    adduser -G $USER_ID -s /bin/bash -D $USER_ID --home "/home/$USER_ID"

## install php and some extensions
RUN apk add \
    php \
    php-bz2 \
    php-dom \
    php-json \
    php-phar \
    php-mbstring \
    php-iconv \
    php-openssl \
    php-curl \
    php-session \
    php-sodium \
    php-zip \
    php-zlib && \
    ln -s /usr/bin/php7 /usr/bin/php
#
#RUN apk update && apk add bash < doesnt work
RUN apk add --update bash curl git && rm -rf /var/cache/apk/*

RUN curl -s -o composer-setup.php https://getcomposer.org/installer \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php \
    && curl -SsLo styleci.phar https://github.com/StyleCI/CLI/releases/download/v0.6.2/styleci.phar \
    && chmod +x styleci.phar \
    && mv styleci.phar /usr/local/bin/styleci \
    && curl -L https://cs.symfony.com/download/php-cs-fixer-v2.phar -o php-cs-fixer \
    && mv php-cs-fixer /usr/local/bin/php-cs-fixer

WORKDIR $LARASED_HOME

RUN composer install

RUN apk del ${APK_DEL} && \
    rm -fR /var/cache/apk/*

RUN mkdir -p $LARAVEL_APP_DIR && \
    chown -R $USER_ID:$USER_ID $LARAVEL_APP_DIR

RUN chmod g+rw $LARASED_HOME && \
    chown -R $USER_ID:$USER_ID $LARASED_HOME

USER $USER_ID

VOLUME $LARASED_HOME/scripts
VOLUME $LARAVEL_APP_DIR

RUN ["chmod", "+x", "/var/laravel-sed/scripts/entrypoint.sh"]

ENTRYPOINT ["/var/laravel-sed/scripts/entrypoint.sh"]
CMD ["config-edit", "--help"]
