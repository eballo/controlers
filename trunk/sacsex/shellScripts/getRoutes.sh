#!/bin/bash

linksOk=`whereis links | grep bin`
if [ ! '$linksOk' ]
then
	echo -e "Error: Es imprescindible tener instalado el paquete de links, para realizar las conexiones con el servidor. \n Para obtenrelo, utilice la orden:\n\n sudo apt-get install links"
	exit 1
else
	# Pedimos los datos del servidor
	zenityOk=`whereis zenity | grep bin`
	if [ '$zenityOk' ]
	then
		SVR_IP=`zenity --entry --title="Datos Del Servidor" --text="Introduce la direccion IP del servidor"`
		SVR_PORT=`zenity --entry --title="Datos Del Servidor" --text="El puerto abierto para los servicios de servidor web"`
	else
		echo "Introduce la direccion IP del servidor"
		read SVR_IP
		echo "El puerto abierto para los servicios de servidor web"
		read SVR_PORT
	fi
	# comprobamos el acceso al host y puertos indicados
	echo "Comprobando conexion"
	nc -z "$SVR_IP" "$SVR_PORT" 2>/dev/null
	if [ $? -eq 0 ]
	then
		SVR_CONN=${SVR_IP}:${SVR_PORT}
		if [ '$zenitiOk' ]
		then
		    loginName=`zenity --entry --title="SACS-EX Login" --text="Introduce tu login"`
			password=`zenity --entry --title="SACS-EX Login" --text="$loginName, introduce tu contraseña"`
		else
			echo "Introduce tu login"
			read loginName
			echo "$loginName, introduce tu contraseÃ±a"
			read password
		fi 
		
		pwd5md=`links -source "http://$SVR_CONN/sacsex/services/service.md5convert.php?text=$password"`
		echo ${pwd5md}
		busca=`links -source "http://$SVR_CONN/sacsex/services/service.auth.php?user=$loginName&pass=$pwd5md&install=false"`
		id=`echo $busca | cut -f2 -d" "`
		echo $id
		rutas=`links -source "http://$SVR_CONN/sacsex/services/service.dirlist.php?id=$id"`
		for i in $rutas
		do
			echo $i
		done
	else
		if [ '$zenityOk' ]
		then
			zenity --warning --title="Fallo de conexion" --text="Error: No se ha podido acceder al puerto $SVR_PORT\n del servidor $SVR_IP.\n\n Compruebe los datos y asegurese de que dicho puerto figura abierto"
		else
			echo -e "Error: No se ha podido acceder al puerto $SVR_PORT\n del servidor $SVR_IP.\n\n Compruebe los datos y asegurese de que dicho puerto figura abierto"
		fi
	fi
fi
