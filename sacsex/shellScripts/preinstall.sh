# !/bin/bash
#Script que comprueba los requisitos previos de instalacion y genera 
# el sacsex.properties
SVR_IP='192.168.0.79'


zenityOk=`find / -name "zenity" 2>/dev/null | grep bin | wc -l `

## Comprobar si samba-client esta instalado. Si no lo esta, warning de que no lo esta e instrucciones de instalacion?
smb=`find / -name "smbmount" 2>/dev/null | wc -l` 

if [ $smb -eq 0 ]
then
    if [ $zenityOk -eq 1 ]
    then
        zenity --title="Instalación de SACS-EX" --text="Error: Es necesario tener instalado el paquete de smbmount\n Para ello, puede utilizar la orden:\n\n apt-get install smbfs"
    else
        echo -e "Error: Es necesario tener instalado el paquete de smbmount\n Para ello, puede utilizar la orden apt-get install smbfs"
    fi
    exit 1
else
    if [ $zenityOk -eq 1 ]
    then
        loginName=`zenity --entry --title="SACS-EX Login" --text="Introduce tu login"`
        password=`zenity --entry --title="SACS-EX Login" --text="$loginName, introduce tu contraseña"`
    else
        echo "Introduce tu login"
        read loginName
        echo "$loginName, introduce tu contraseña"
        read password
    fi

    ### validar usuario
    ####
    ###conexion con auth.php mediante lynx para validar usuario $SVR_HOST/sacsex/services/service.auth.php?user=$login&password=$passwd

    ## Si todo es correcto:
    # Posible problema al ejecutarse desde root, para obtener el /home. Buscar soluciones
    
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

    USER=$loginName
    PASS=$password

    echo "USER=$USER" > $propiertiesFile
    echo "PASS=$PASS" >> $propiertiesFile
    echo "HOST_IP=$IP" >> $propiertiesFile
    echo "SVR_IP=$SVR_IP" >> $propiertiesFile
    echo "USER_HOME=$home" >> $propiertiesFile

    cat $propiertiesFile
fi
