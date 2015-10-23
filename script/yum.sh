set -e
DONE=/tmp/.yum_done

if [ ! -f ${DONE} ]; then
  yum -q clean all
  yum -q -y groupinstall "Development Tools"
  yum -q -y install --enablerepo=epel libyaml libyaml-devel readline-devel
  yum -q -y install --enablerepo=epel ncurses-devel gdbm-devel tcl-devel
  yum -q -y install --enablerepo=epel openssl-devel db4-devel libffi-devel

  # MySQL
  yum -q -y install http://dev.mysql.com/get/mysql-community-release-el6-5.noarch.rpm
  # xdebug
  yum -q -y install php-pecl-xdebug

  touch  ${DONE}
fi
