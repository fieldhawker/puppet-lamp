set -e
DONE=/tmp/.after_done

if [ ! -f ${DONE} ]; then

  /usr/bin/mysql -u myappuser -pmyapppass -D myapp < /vagrant/sql/create_user_table.sql
  /usr/bin/mysql -u myappuser -pmyapppass -D myapp < /vagrant/sql/create_address_table.sql
  /usr/bin/mysql -u myappuser -pmyapppass -D myapp_test < /vagrant/sql/create_user_table.sql
  /usr/bin/mysql -u myappuser -pmyapppass -D myapp_test < /vagrant/sql/create_address_table.sql

  cd /var/www/html
  /usr/local/bin/composer install
  
  touch  ${DONE}
fi
