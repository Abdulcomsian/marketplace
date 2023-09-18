sudo a2dismod php7.4 && sudo a2enmod php8.1 && sudo update-alternatives --set php /usr/bin/php8.1 && sudo /etc/init.d/apache2 restart
for 7


sudo a2dismod php8.1 && sudo a2enmod php7.4 && sudo update-alternatives --set php /usr/bin/php7.4 && sudo /etc/init.d/apache2 restart
for 8