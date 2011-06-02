# !/bin/bash
#Script que comprueba los requisitos previos de instalacion y genera 
# el sacsex.properties

linksOk=`whereis links | grep bin`
if [ ! "$linksOk" ]
then
	echo -e "Error: Es imprescindible tener instalado el paquete de links, para realizar las conexiones con el servidor. \n Para obtenerlo, utilice la orden:\n\n sudo apt-get install links"
	exit 1
else
	zenityOk=`whereis zenity | grep bin`
	# Pedimos los datos del servidor
	if [ "$zenityOk" ]
	then
		SVR_IP=`zenity --entry --title="Datos Del Servidor" --text="Introduce la direccion IP del servidor" 2>/dev/null`
		SVR_PORT=`zenity --entry --title="Datos Del Servidor" --text="El puerto abierto para los servicios de servidor web" 2>/dev/null`
	else
		echo "Introduce la direccion IP del servidor"
		read SVR_IP
		echo "El puerto abierto para los servicios de servidor web"
		read SVR_PORT
	fi
	
    # Comprobamos el acceso al host y puertos indicados
    nc -z "$SVR_IP" "$SVR_PORT" 2>/dev/null
    if [ $? -eq 0 ]
    then
        conexion=0
    else
        conexion=1
    fi
    
    if [ "$zenityOk" ]
    then
        # Ventana de animación al conectar
        (
        echo "10" ; sleep 1
        echo "# Comprobando acceso a host $SVR_IP" ; sleep 1
        echo "20" ; sleep 1
        echo "# Comprobando puerto $SVR_PORT" ; sleep 1
        echo "50" ; sleep 1
        echo "# Comprobando conexión"; sleep 1
            if [ "$conexion" -eq 0 ]; then
                echo "75"; sleep 1
                echo "# Conexión establecida"; sleep 1
                echo "100" ;
            else
                echo "100" ; sleep 1
            fi
        ) | 
        zenity --progress --title="SACS-EX" --text="Iniciando comprobación..." --auto-close --pulsate --width="400" --height="100" --percentage=0 2>/dev/null
    else
        echo "Comprobando conexion"
    fi

	if [ "$conexion" -eq 0 ]
	then
		SVR_CONN=${SVR_IP}:${SVR_PORT}
		if [ "$zenityOk" ]
		then
		    loginName=`zenity --entry --title="SACS-EX Login" --text="Introduce tu login" 2>/dev/null`
			password=`zenity --entry --title="SACS-EX Login" --hide-text --text="$loginName, introduce tu contrasena" 2>/dev/null`
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
		id=`echo $busca | cut -f2 -d":"`
		installOk=`echo $busca | cut -f1 -d":"`
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
			sshLogin="sacs@${SVR_IP}"
			user=$loginName
			pass=$pwd5md
			
			
			instalado=`links -dump "http://$SVR_CONN/sacsex/services/service.install.php?user=$user&pass=$pass"`
			if [ $instalado -eq 0 ] 
			then
				echo "SACS_SVR_IP=$SVR_CONN" >$propiertiesFile
				echo "SACS_USER=$user" >> $propiertiesFile
				echo "SACS_PASS=$pass" >> $propiertiesFile
				echo "SACS_USER_HOME=$home" >> $propiertiesFile
				echo "SACS_LOGIN=$sshLogin" >> $propiertiesFile
				bash sacsconfig.sh
				if [ "$zenityOk" ]
				then
					zenity --info --title="SACS-EX Instalado" --text="La aplicacion ha sido instalada correctamente.\n para Gestionar sus directorios, \n puede hacerlo desde la pagina web." 2>/dev/null
				else
					echo -e "La aplicacion ha sido instalada correctamente.\n para Gestionar sus directorios, \n puede hacerlo desde la pagina web."
				fi
			else
				if [ "$zenityOk" ]
				then
					zenity --warning --title="Fallo de conexion" --text="Error: No se ha podido conectar a la base de datos.\n Asegurese que la conexión a internet sea correcta" 2>/dev/null
				else
					echo -e "Error: No se ha podido conectar a la base de datos.\n Asegurese que la conexión a internet sea correcta"
				fi
			fi
		else
			echo "Error: El usuario no existe o ya tiene instalada la aplicacion"
		fi
	else
		if [ "$zenityOk" ]
		then
			zenity --warning --title="Fallo de conexión" --text="Error: No se ha podido acceder al puerto $SVR_PORT \ndel servidor $SVR_IP. \n\nCompruebe los datos y asegurese de que dicho puerto figura abierto" 2>/dev/null
		else
            echo -e "Error: No se ha podido acceder al puerto $SVR_PORT \ndel servidor $SVR_IP. \n\nCompruebe los datos y asegurese de que dicho puerto figura abierto"
		fi
	fi
fi
