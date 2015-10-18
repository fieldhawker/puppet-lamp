set -e
DONE=/tmp/.yum_done

if [ ! -f ${DONE} ]; then
  yum -q clean all
  yum -q -y groupinstall "Development Tools"
  yum -q -y install --enablerepo=epel libyaml libyaml-devel readline-devel
  yum -q -y install --enablerepo=epel ncurses-devel gdbm-devel tcl-devel
  yum -q -y install --enablerepo=epel openssl-devel db4-devel libffi-devel
  touch  ${DONE}
fi
