#!/bin/bash

SACSEXHOME="$HOME/.sacsexBckps"
if [ -d "$SACSEXHOME" ]
then
	linksOk=`whereis links | grep bin`
	if [ ! '$linksOk' ]
	then
		echo -e "Error: Es imprescindible tener instalado el paquete de links, para realizar las conexiones con el servidor. \n Para obtenrelo, utilice la orden:\n\n sudo apt-get install links"
		exit 1
	else
		propiertiesFile="$SACSEXHOME/sacsex.properties"
		if [ -f "$propiertiesFile" ]
		then
			for elem in `cat $propiertiesFile`
			do
				if [ `echo $elem | grep "SACS_SVR_IP=" | wc -l` -eq 1 ]
				then
					SVR_CONN=`echo $elem | cut -d'=' -f2`
				elif [ `echo $elem | grep "SACS_USER=" | wc -l` -eq 1 ]
				then
					user=`echo $elem | cut -d'=' -f2`
				elif [ `echo $elem | grep "SACS_PASS=" | wc -l` -eq 1 ]
				then
					pwd5md=`echo $elem | cut -d'=' -f2`
				fi
			done
			echo "$SVR_CONN"
			echo "$user"
			echo "$pwd5md"
			
			if [ "$SVR_CONN" != '' ] && [ "$user" != '' ] && [ "$pwd5md" != '' ]
			then
				SVR_IP=`echo $SVR_CONN | cut -d':' -f1`
				SVR_PORT=`echo $SVR_CONN | cut -d':' -f2`
			# comprobamos el acceso al host y puerto indicados en el archivo
				echo "Comprobando conexion"
				nc -z "$SVR_IP" "$SVR_PORT" 2>/dev/null
				if [ $? -eq 0 ]
				then
					SVR_CONN=${SVR_IP}:${SVR_PORT}
					busca=`links -source "http://$SVR_CONN/sacsex/services/service.auth.php?user=$user&pass=$pwd5md&install=false"`
					id=`echo $busca | cut -f2 -d" "`
					# Comprobamos el id devuelto
					if [ "$id" != "1" ]
					then
						rutas=`links -source "http://$SVR_CONN/sacsex/services/service.dirlist.php?id=$id"`
						for i in $rutas
						do
							echo $i
						done
					else
						echo "Error: Usuario '$user' no localizado en la base de datos."
					fi
				else
					if [ '$zenityOk' ]
					then
						zenity --warning --title="Fallo de conexion" --text="Error: No se ha podido acceder al puerto $SVR_PORT\n del servidor $SVR_IP.\n\n Compruebe los datos y asegurese de que dicho puerto figura abierto"
					else
						echo -e "Error: No se ha podido acceder al puerto $SVR_PORT\n del servidor $SVR_IP.\n\n Compruebe los datos y asegurese de que dicho puerto figura abierto"
					fi
				fi
			else
				echo "Error: faltan algunos parametros por completar"
			fi
		else
			echo "Error: el fichero $propiertiesFile no esta localizable"
		fi
	fi
else
	echo "Error: No se ha localizado la carpeta $SACSEXHOME"
fi
