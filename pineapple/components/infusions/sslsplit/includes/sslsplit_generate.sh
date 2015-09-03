#!/bin/sh

MODULEPATH="$(dirname $0)/"

# Generate the SSL certificate authority and key for SSLsplit to use
openssl genrsa -out ${MODULEPATH}cert/certificate.key 1024
openssl req -new -nodes -x509 -sha1 -out ${MODULEPATH}cert/certificate.crt -key ${MODULEPATH}cert/certificate.key -config ${MODULEPATH}cert/openssl.cnf -extensions v3_ca -subj '/O=SSLsplit Root CA/CN=SSLsplit Root CA/' -set_serial 0 -days 3650