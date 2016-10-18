FROM php:7.0.12-alpine

MAINTAINER herloct <herloct@gmail.com>

RUN apk update --no-cache \
    && apk add --no-cache \
        su-exec

RUN mkdir -p /project

RUN curl -L http://codeception.com/codecept.phar > /usr/local/bin/codecept \
    && chmod +x /usr/local/bin/codecept

VOLUME ["/project"]
WORKDIR /project

COPY entrypoint.sh /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh", "phpdbg", "-qrr", "/usr/local/bin/codecept"]
CMD ["--version"]
