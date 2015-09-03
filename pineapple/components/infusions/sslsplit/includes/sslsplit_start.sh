#!/bin/sh

MODULEPATH="$(dirname $0)/"
MYTIME=`date +%s`

killall sslsplit

# Forward IP packets not meant for the local machine to its standard/default gateway
echo '1' > /proc/sys/net/ipv4/ip_forward
iptables -X
iptables -F
iptables -t nat -F
iptables -P INPUT ACCEPT
iptables -P FORWARD ACCEPT
iptables -P OUTPUT ACCEPT

# Execute specific redirections
sh ${MODULEPATH}rules/iptables

iptables -t nat -A POSTROUTING -j MASQUERADE

# Start sslsplit
## ${MODULEPATH}dep/sslsplit -D -l connections.log -S ${MODULEPATH}log -k ${MODULEPATH}cert/certificate.key -c ${MODULEPATH}cert/certificate.crt ssl 0.0.0.0 8443 tcp 0.0.0.0 8080
${MODULEPATH}dep/sslsplit -D -l connections.log -L ${MYPATH}log/output_${MYTIME}.log -k ${MODULEPATH}cert/certificate.key -c ${MODULEPATH}cert/certificate.crt ssl 0.0.0.0 8443 tcp 0.0.0.0 8080