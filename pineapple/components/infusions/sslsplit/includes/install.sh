#!/bin/sh

MODULEPATH="$(dirname $0)/"

opkg update
opkg install openssl-util
opkg install libevent2
opkg install libevent2-core
opkg install libevent2-extra
opkg install libevent2-openssl
opkg install libevent2-pthreads

# Generate the SSL certificate authority and key for SSLsplit to use
openssl genrsa -out ${MODULEPATH}cert/certificate.key 1024
openssl req -new -nodes -x509 -sha1 -out ${MODULEPATH}cert/certificate.crt -key ${MODULEPATH}cert/certificate.key -config ${MODULEPATH}cert/openssl.cnf -extensions v3_ca -subj '/O=SSLsplit Root CA/CN=SSLsplit Root CA/' -set_serial 0 -days 3650

# Executable
chmod +x ${MODULEPATH}rules/iptables
chmod +x ${MODULEPATH}autostart.sh
chmod +x ${MODULEPATH}dep/sslsplit
chmod +x ${MODULEPATH}sslsplit_start.sh
chmod +x ${MODULEPATH}sslsplit_stop.sh
chmod +x ${MODULEPATH}sslsplit_generate.sh

# Done !
touch ${MODULEPATH}installed
echo "done" > ${MODULEPATH}status.php