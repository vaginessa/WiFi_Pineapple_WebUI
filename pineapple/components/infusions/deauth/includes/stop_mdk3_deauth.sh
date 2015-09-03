#!/bin/sh

MYPATH="$(dirname $0)/"
LOG=${MYPATH}log
TMPBLACKLIST=${MYPATH}rules/blacklist.tmp
TMPWHITELIST=${MYPATH}rules/whitelist.tmp

echo -e "Stopping WiFi Deauth [mdk3]..." >> ${LOG}

killall -9 start_mdk3_deauth.sh
killall -9 mdk3

rm ${TMPBLACKLIST}
rm ${TMPWHITELIST}
rm ${LOG}