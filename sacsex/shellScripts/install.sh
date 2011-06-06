# !/bin/bash
#
# Nombre: preinstall.sh
# Autores: Giorgio y Cristina
# Descripción:
#  Script de instalación Sacs-ex
#  Comprueba los requisitos previos de instalación
#  Genera el sacsex.properties
#  Configura el crontab
# Requisitos:
#  Para un buena y correcta ejecución del programa
#  Tener instalado la aplicación links
#  Tener instalado la aplicación zenity
#  Tener el script backups-sacs2.sh en el mismo directorio que este script
#

linksOk=`whereis links | grep bin`
sacsexInstall=`whereis sacsex | grep bin`

# Ruta de fichero para editar crontab
newcron="/tmp/cron_$USER"

function validarHora (){
    # Pide una hora en formato hh:mm para validar
    # retorna: 0 -> true
    #          1 -> false
    
    horaValida=1
    if [ "$zenityOk" ];then 
        hora=`zenity --entry --text="Indica la hora en formato hh:mm" --title="Hora" 2>/dev/null`
    else
        echo "Indica la hora en formato hh:mm"
        read hora
    fi
    h=`echo $hora | grep "[^0-9:]" | wc -l`
    if [ $h -eq 0 ]
    then
        hora=`date -d $hora +%H:%M 2>/dev/null`
        if [ $? -eq 0 ]
        then
            horaValida=0
        else
            if [ "$zenityOk" ];then
                zenity --error --text="Hora erronea" 2>/dev/null
            else
                echo "Hora erronea"
            fi
        fi
    else
        if [ "$zenityOk" ];then
            zenity --error --text="Formato de hora Erroneo" 2>/dev/null
        else
            echo "Formato de hora Erroneo"
        fi
    fi
    return $horaValida
}

function validarDia (){
    # Pide un dia de la semana [ 0 - 6 ] (Domingo = 0)
    # retorna: 0 -> true
    #          1 -> false
    
    diaValido=1
    if [ "$zenityOk" ];then 
        dia=`zenity --list --title="Semama" --text="Indica dia de la semana " --column="Opc" --column="Dia" 0 Domingo 1 Lunes 2 Martes 3 Miércoles 4 Jueves 5 Viernes 6 Sábado 2>/dev/null` 
    else
        echo "Indica dia de la semana [0-6]"
        echo -e " 0 Domingo\n 1 Lunes\n 2 Martes\n 3 Miércoles\n 4 Jueves\n 5 Viernes\n 6 Sábado\n"
        read dia
    fi
    d=`echo $dia | grep "^[0-6]" | wc -l`

    if [ $d -eq 1 ]
    then
        test "$dia" -gt 6 -o "$dia" -lt 0 2>/dev/null
        if [ $? -eq 0 ]
        then
            if [ "$zenityOk" ];then
                zenity --error --text="Dia erronea" 2>/dev/null
            else
                echo "Dia erronea"
            fi
        else
            diaValido=0
        fi
    else
        if [ "$zenityOk" ];then
            zenity --error --text="Formato de dia Erroneo" 2>/dev/null
        else
            echo "Formato de dia Erroneo"
        fi
    fi
    return $diaValido
}

function validarMes (){
    # Pide opcion: 0 -> para inicio de mes
    #              1 -> para final del mes
    # retorna: 0 -> true
    #          1 -> false
    
    mesValido=1
    if [ "$zenityOk" ];then
        mes=`zenity --list --title="Mes" --text="Indica opción para el mes" --column="Opc" --column="Tiempo" 0 "Inicio del mes" 1 "Quincena del mes" 2 "Final del mes" 2>/dev/null`
    else
        echo "Indica opción para el mes"
        echo -e " 0 Inicio del mes\n 1 Quincena del mes\n 2 Final del mes\n"
        read mes
    fi
    m=`echo $mes | grep "^[0-2]" | wc -l`

    if [ $m -eq 1 ]
    then
        test "$mes" -gt 2 -o "$mes" -lt 0 2>/dev/null
        if [ $? -eq 0 ]
        then
            if [ "$zenityOk" ];then
                zenity --error --text="Opcion para mes es erroneo" 2>/dev/null
            else
                echo "Opcion para mes es erroneo"
            fi
        else
            mesValido=0
            if [ "$mes" == 0 ];then
                mes=1
            elif [ "$mes" == 1 ];then
                mes=15
            else
                mes=28
            fi
        fi
    else
        if [ "$zenityOk" ];then
            zenity --error --text="Formato de mes erroneo" 2>/dev/null
        else
            echo "Formato de mes erroneo"
        fi
    fi
    return $mesValido
}

if [ ! "$linksOk" ]
then
	echo -e "Error: Es imprescindible tener instalado el paquete de links, para realizar las conexiones con el servidor. \n Para obtenerlo, utilice la orden:\n\n sudo apt-get install links"
	exit 1
else
	zenity 2>"/tmp/sacs.zenityck" 
	cat /tmp/sacs.zenityck | grep "open display" 2>/dev/null
	res=$?
	if [ $res -eq 1 ]
	then
		zenityOk=`whereis zenity | grep bin`
	fi
	home=`echo ~`
	if [ ! -d "$home/.sacsexBckps" ]
	then
		mkdir "$home/.sacsexBckps"
	fi
	SACSEXHOME="$home/.sacsexBckps"
	chmod 700 $SACSEXHOME
	if [ -f $SACSEXHOME/sacsex.properties ]
	then
		if [ "$zenityOk" ]
		then
			zenity --warning --title="SACS-EX Ya Instalado" --text="La aplicacion fue instalada con anterioridad desde esta cuenta de usuario linux.\n\n Si desea reconfigurar la misma, ejecute la opcion reconfigure.sh." 2>/dev/null
		else
			echo -e "La aplicacion fue instalada con anterioridad desde esta cuenta de usuario linux.\n\n Si desea reconfigurar la misma, ejecute la opcion reconfigure.sh."					
		fi
		exit 1
	fi
	propiertiesFile="$SACSEXHOME/sacsex.properties"
	# Pedimos los datos del servidor
	if [ "$zenityOk" ] && [ $res -eq 1 ]
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
			read -s password
		fi 
		# Conexión con el servicio para convertir pass a md5
		pass=`links -dump "http://$SVR_CONN/sacsex/services/service.md5convert.php?text=$password" 2>/dev/null`
		pwd5md=`echo $pass | cut -d'/' -f2`

        # Conexión con el servicio para autenticar y proceder a instalar
		busca=`links -dump "http://$SVR_CONN/sacsex/services/service.auth.php?user=$loginName&pass=$pwd5md&install=true" 2>/dev/null`

		# Recojo el id y si se puede o no proceder a la instalacion 
		id=`echo $busca | cut -f2 -d":"`
		installOk=`echo $busca | cut -f1 -d":"`

		# Si todo es correcto Genero el fichero de propiedades:
		if [ "$installOk" == '2' ]
		then
			home=`echo ~`
			if [ ! -d "$home/.sacsexBckps" ]
			then
				mkdir "$home/.sacsexBckps"
			fi
			SACSEXHOME="$home/.sacsexBckps"
			if [ -f $SACSEXHOME/sacsex.properties ]
			then
				if [ "$zenityOk" ]
				then
					zenity --warning --title="SACS-EX Ya Instalado" --text="La aplicacion fue instalada con anterioridad desde esta cuenta de usuario linux.\n\n Si desea reconfigurar la misma, ejecute la opcion reconfigure.sh." 2>/dev/null
				else
					echo -e "La aplicacion fue instalada con anterioridad desde esta cuenta de usuario linux.\n\n Si desea reconfigurar la misma, ejecute la opcion reconfigure.sh."					
				fi
				exit 1
			fi
			propiertiesFile="$SACSEXHOME/sacsex.properties"
			user=$loginName
			pass=$pwd5md
			
			
			# Conexión con el servicio para notificar la instalación a la base de datos
			instalado=`links -dump "http://$SVR_CONN/sacsex/services/service.install.php?user=$user&pass=$pass" 2>/dev/null`
			if [ "$instalado" -eq 0 ] 
			then
				seguir=false

				while ! $seguir
				do
					if [ "$zenityOk" ];then
						frec=`zenity --list --title="Frecuencia" --text="Indica la Frecuencia con la que desea realizar los backups" --column="Opc" --column="Tiempo" 0 "Diaria" 1 "Semanal" 2 "Mensual" 2>/dev/null`
					else
						echo -e "\nIndica la Frecuencia con la que desea realizar los backups"
						echo -e " (0) Diaria\n (1) Semanal \n (2) Mensual\n"
						read frec
					fi
					
					test "$frec" -gt 2 -o "$frec" -lt 0 2>/dev/null
					if [ $? -eq 0 ]
					then
						echo "Introduce un valor numerico y dentro de los valores del menu"
						seguir=false
					else
						seguir=true
					fi
				done

				case "$frec" in
				0)
					if validarHora
					then
						h=`echo $hora | cut -d: -f1`
						m=`echo $hora | cut -d: -f2`
						
						# Guardado de orden crontab en fichero
						crontab -l > $newcron
						echo "$m $h * * * sacsex" >> $newcron
						# Guardado de contenido de fichero en crontab
						crontab $newcron
						if [ $? -eq 0 ];then
							echo " crontab configurado correctamente"
							ok=0
						else
							echo " Error, no se ha podido configurar crontab"
							error=1
						fi
					fi
				;;
				1)
					if validarDia
					then
						if validarHora
						then
							h=`echo $hora | cut -d: -f1`
							m=`echo $hora | cut -d: -f2`
							crontab -l > $newcron
							# Guardado de orden crontab en fichero
							echo "$m $h * * $dia sacsex" >> $newcron
							# Guardado de contenido de fichero en crontab
							crontab $newcron 2>/dev/null
							if [ $? -eq 0 ];then
								echo " crontab configurado correctamente"
								ok=0
							else
								echo " Error, no se ha podido configurar crontab"
								error=1
							fi
						fi
					fi
				;;
				2)
					if validarMes
					then
						# Guardado de orden crontab en fichero
						crontab -l > $newcron
						if [ "$mes" -eq 0 ]
						then
							echo "* * 1 * * sacsex" >> $newcron
						elif [ "$mes" -eq 1 ]
						then
							echo "* * 15 * * sacsex" >> $newcron
						else
							echo "* * 28 * * sacsex" >> $newcron
						fi
						
						# Guardado de contenido de fichero en crontab
						crontab $newcron 2>/dev/null
						if [ $? -eq 0 ];then
							echo " crontab configurado correctamente"
							ok=0
						else
							echo " Error, no se ha podido configurar crontab"
							error=1
						fi
					fi
				;;
				*)
					if [ "$zenityOk" ];then
						zenity --error --title="Error" --text="Opcion cancelada o erronea, vuelva a intentarlo\nError, no se ha podido configurar crontab" 2>/dev/null
					else
						echo "Opcion cancelada o erronea, vuelva a intentarlo"
						echo "Error, no se ha podido configurar crontab"
						error=1
					fi
				;;
				esac
				
				if [ $ok ]
				then
					touch $SACSEXHOME/sacsex.properties
					echo "SACS_SVR_IP=$SVR_CONN" >$propiertiesFile
					echo "SACS_USER=$user" >> $propiertiesFile
					echo "SACS_PASS=$pass" >> $propiertiesFile
					echo "SACS_USER_HOME=$home" >> $propiertiesFile
  
                    #Desplegando binario sacsex
                    if [ ! "$sacsexInstall" ]
                    then
                        if [ "$zenityOk" ];then
						    zenity --warning --title="Atencion Se necesita a root" --text="Para proceder con la instalación sera necesario ejecutar con root un comando, porfavor introduzca la password de root." 2>/dev/null
					    else
						    echo -e "Para proceder con la instalación sera necesario ejecutar con root un comando, porfavor introduzca la password de root."
						    error=1
					    fi

                        sudo cp data/sacsex /bin
                        moveBin=$?
                        sudo chmod 555 /bin/sacsex
                        changePerm=$?
                        
                        if [ $moveBin -a $changePerm ]
                        then
                            if [ "$zenityOk" ]
					        then
						        zenity --info --title="SACS-EX Instalado" --text="La aplicacion ha sido instalada correctamente.\n Para gestionar sus directorios, \n puede hacerlo desde la pagina web." 2>/dev/null
					        else
						        echo -e "La aplicacion ha sido instalada correctamente.\n Para gestionar sus directorios, \n puede hacerlo desde la pagina web."
					        fi
                        else
                            if [ "$zenityOk" ]
					        then
						        zenity --info --title="SACS-EX No Instalado" --text="La aplicacion ha tenido problemas para ser instalada correctamente." 2>/dev/null
					        else
						        echo -e "La aplicacion ha tenido problemas para ser instalada correctamente."
					        fi
                            res=`links -dump "http://$SVR_CONN/sacsex/services/service.uninstall.php?user=$user&pass=$pwd5md"`
                        fi
                    else
						if [ "$zenityOk" ]
						then
							zenity --info --title="SACS-EX Instalado" --text="La aplicacion ha sido instalada correctamente.\n Para gestionar sus directorios, \n puede hacerlo desde la pagina web." 2>/dev/null
						else
							echo -e "La aplicacion ha sido instalada correctamente.\n Para gestionar sus directorios, \n puede hacerlo desde la pagina web."
						fi
                    fi
	
				else
					res=`links -dump "http://$SVR_CONN/sacsex/services/service.uninstall.php?user=$user&pass=$pwd5md"`
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


