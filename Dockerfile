FROM alpine:3.11
WORKDIR /usr
ADD cli/nodejs .
RUN apk add --no-cache git nodejs nodejs-npm && \
    npm i -g --unsafe-perm yarn typescript webpack webpack-cli &&  \
    yarn
# This is the build build process. Pending

FROM alpine:3.11
ARG PHP_VERSION=7.4
ARG USER_ID=blue
ENV CLI_BIN_DIR=/var/laraseed
ENV APK_DEL="git curl"

WORKDIR $CLI_BIN_DIR

ADD https://dl.bintray.com/php-alpine/key/php-alpine.rsa.pub /etc/apk/keys/php-alpine.rsa.pub
ADD cli cli
ADD app app

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
    php-sodium \
    php-zip \
    php-zlib && \
    ln -s /usr/bin/php7 /usr/bin/php
#
#RUN apk update && apk add bash < doesnt work
RUN apk add --update bash curl git && rm -rf /var/cache/apk/*

RUN curl -s -o composer-setup.php https://getcomposer.org/installer \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

RUN cd $CLI_BIN_DIR/cli/php && \
    composer install

WORKDIR $CLI_BIN_DIR

RUN apk del ${APK_DEL} && \
    rm -fR /var/cache/apk/*

RUN chmod g+rw $CLI_BIN_DIR && \
    chown -R $USER_ID:$USER_ID $CLI_BIN_DIR

USER $USER_ID

VOLUME $CLI_BIN_DIR

RUN ["chmod", "+x", "/var/laraseed/cli/main.sh"]

ENTRYPOINT ["/var/laraseed/cli/main.sh"]
CMD ["/bin/bash"]
