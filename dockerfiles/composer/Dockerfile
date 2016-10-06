FROM php:7.0.11-alpine

MAINTAINER herloct <herloct@gmail.com>

RUN apk update --no-cache \
    && apk add --no-cache \
        git \
        su-exec

RUN mkdir -p /project /composer

ENV COMPOSER_HOME /composer

RUN cd /tmp \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === 'e115a8dc7871f15d853148a7fbac7da27d6c0030b848d9b3dc09e2a0388afed865e6a3d6b3c0fad45c48e2b5fc1196ae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && rm -rf /var/cache/apk/* /var/tmp/* /tmp/*

ENV PATH $COMPOSER_HOME/vendor/bin:$PATH

VOLUME ["/project", "/composer"]
WORKDIR /project

COPY entrypoint.sh /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh", "composer"]
CMD ["--version"]
