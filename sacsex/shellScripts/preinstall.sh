# !/bin/bash
#Script que comprueba los requisitos previos de instalacion y genera 
# el sacsex.properties

linksOk=`whereis links | grep bin`
if [ ! "$linksOk" ]
then
	echo -e "Error: Es imprescindible tener instalado el paquete de links, para realizar las conexiones con el servidor. \n Para obtenrelo, utilice la orden:\n\n sudo apt-get install links"
	exit 1
else
	zenityOk=`whereis zenity | grep bin`
	# Pedimos los datos del servidor
	if [ "$zenityOk" ]
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
		if [ "$zenityOk" ]
		then
		    loginName=`zenity --entry --title="SACS-EX Login" --text="Introduce tu login"`
			password=`zenity --entry --title="SACS-EX Login" --text="$loginName, introduce tu contrasena"`
		else
			echo "Introduce tu login"
			read loginName
			echo "$loginName, introduce tu contraseña"
			read password
		fi 
		
		pass=`links -dump "http://$SVR_CONN/sacsex/services/service.md5convert.php?text=$password"`
		pwd5md=`echo $pass | cut -d'/' -f2`
		busca=`links -dump "http://$SVR_CONN/sacsex/services/service.auth.php?user=$loginName&pass=$pwd5md&install=true"`
		# Recojo el id y si se puede o no proceder a la instalacion 
		id=`echo $busca | cut -f2 -d"/"`
		installOk=`echo $busca | cut -f1 -d"/"`
		## Si todo es correcto Genero el fichero de propiedades:
		if [ "$installOk" -eq 2 ]
		then
			home=`echo ~`
			if [ ! -d "$home/.sacsexBckps" ]
			then
				mkdir "$home/.sacsexBckps"
			fi
			SACSEXHOME="$home/.sacsexBckps"
			if [ ! -f $SACSEXHOME/sacsex.properties ]
			then
				touch $SACSEXHOME/sacsex.properties
			fi
			propiertiesFile="$SACSEXHOME/sacsex.properties"
			
			USER=$loginName
			PASS=$pwd5md
			echo "SACS_SVR_IP=$SVR_CONN" >$propiertiesFile
			echo "SACS_USER=$USER" >> $propiertiesFile
			echo "SACS_PASS=$PASS" >> $propiertiesFile
			echo "SACS_USER_HOME=$home" >> $propiertiesFile
			
		else
			echo "Error: El usuario no existe o ya tiene instalada la aplicacion"
		fi
	else
		if [ "$zenityOk" ]
		then
			zenity --warning --title="Fallo de conexion" --text="Error: No se ha podido acceder al puerto $SVR_PORT\n del servidor $SVR_IP.\n\n Compruebe los datos y asegurese de que dicho puerto figura abierto"
		else
			echo -e "Error: No se ha podido acceder al puerto $SVR_PORT\n del servidor $SVR_IP.\n\n Compruebe los datos y asegurese de que dicho puerto figura abierto"
		fi
	fi
fi
