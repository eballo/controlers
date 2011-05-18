# !/bin/bash

SVR_HOST='192.168.0.79'

## Comprobar si samba-client esta instalado. Si no lo esta, warning de que no lo esta e instrucciones de instalacion?

login=`zenity --entry --title="SACS-EX Login" --text="Introduce tu login"`
password=`zenity --entry --title="SACS-EX Login" --text="$login, introduce tu contraseña"`

### validar usuario
####
####

## Si todo es correcto:

home=`echo ~`
mkdir "$home/.sacsexBckps"
SACSEXHOME="$home/.sacsexBckps"
touch $SACSEXHOME/sacsex.properties
propiertiesFile="$SACSEXHOME/sacsex.properties"

# Recojo la IP del host del usuario
IP=`ifconfig | grep "inet " | cut -d":" -f2 | cut -d" " -f1 | grep -v "^127"`

if [ -z "$IP" ]
then
    echo "no hay IP assignada"    
fi

USER=$login
PASS=$password

echo "USER=$USER" > $propiertiesFile
echo "PASS=$PASS" >> $propiertiesFile
echo "IP_HOST=$IP" >> $propiertiesFile
echo "USER_HOME=$home" >> $propiertiesFile

cat $propiertiesFile
