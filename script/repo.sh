set -e

DONE=/tmp/.repo_done
RPM_EPEL=ftp://ftp.kddilabs.jp/Linux/distributions/fedora/epel/6/i386/epel-release-6-8.noarch.rpm
GPG_EPEL=ftp://ftp.kddilabs.jp/Linux/distributions/fedora/epel/RPM-GPG-KEY-EPEL-6
RPM_REMI=http://rpms.famillecollet.com/enterprise/remi-release-6.rpm
GPG_REMI=http://rpms.famillecollet.com/RPM-GPG-KEY-remi
RPM_RPMF=http://pkgs.repoforge.org/rpmforge-release/rpmforge-release-0.5.3-1.el6.rf.i686.rpm
GPG_RPMF=http://apt.sw.be/RPM-GPG-KEY.dag.txt

if [ ! -f ${DONE} ]; then
  echo "include_only=.jp" | tee -a /etc/yum/pluginconf.d/fastestmirror.conf
  yum -q -y install wget

  for gpg in ${GPG_EPEL} ${GPG_REMI} ${GPG_RPMF}
  do
    rpm --import $gpg
  done

  cd /tmp
  wget -q ${RPM_EPEL}
  wget -q ${RPM_REMI}
  wget -q ${RPM_RPMF}
  for rpm in *.rpm
  do
    rpm -Uvh $rpm
  done

  for repo in remi.repo epel.repo rpmforge.repo
  do
    cp -p /etc/yum.repos.d/$repo /etc/yum.repos.d/$repo.old
    cat /etc/yum.repos.d/$repo.old| sed 's/^enabled.*$/enabled = 1/' < /etc/yum.repos.d/$repo
  done
  touch ${DONE}
fi
