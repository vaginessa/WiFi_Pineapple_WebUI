#!/bin/sh

MODULEPATH="$(dirname $0)/"

killall sslsplit

rm -rf ${MODULEPATH}connections.log

iptables -F
iptables -X
iptables -t nat -F
iptables -t nat -X
iptables -t mangle -F
iptables -t mangle -X
iptables -P INPUT ACCEPT
iptables -P FORWARD ACCEPT
iptables -P OUTPUT ACCEPT