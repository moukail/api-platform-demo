FROM php:8.1-fpm-alpine3.16

RUN apk --update --no-cache add bash wget gcc g++ make autoconf rabbitmq-c-dev rabbitmq-c libsodium-dev openssh rsync git
RUN docker-php-ext-install -j$(nproc) pdo_mysql

RUN pecl install amqp xdebug redis libsodium && docker-php-ext-enable amqp xdebug redis opcache
RUN git config --global user.email "moukafih@live.nl" && git config --global user.name "Ismail Moukafih"

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && sed -i "s|;date.timezone =.*|date.timezone = ${TIMEZONE}|" /usr/local/etc/php/php.ini \
    && sed -i "s|memory_limit =.*|memory_limit = -1|" /usr/local/etc/php/php.ini

RUN echo $'zend_extension=xdebug.so \n\
xdebug.mode=debug,profile,coverage,develop \n\
xdebug.start_with_request=trigger \n\
xdebug.discover_client_host=true \n\
xdebug.client_host=host.docker.internal \n\
xdebug.idekey=PHPSTORM \n\
xdebug.file_link_format="phpstorm://open?file=%f&line=%l" '\
> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer
# Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony5/bin/symfony /usr/bin/symfony

### Codeception
RUN wget http://codeception.com/codecept.phar -O /usr/bin/codecept && chmod a+x /usr/bin/codecept

RUN addgroup _www && adduser -S -H --ingroup _www _www

WORKDIR /var/www/backend

ADD install.sh /home/
RUN chmod +x /home/install.sh
#CMD bash /home/install.sh

ADD docker-init.sh /home/
RUN chmod +x /home/docker-init.sh
CMD bash /home/docker-init.sh
