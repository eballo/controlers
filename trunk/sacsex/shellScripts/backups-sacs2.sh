#!/bin/bash

####################################################################
# Script de copias de seguridad para servidores linux              #
#                                                                  #
# Autores : Cristina y Giorgio                                     #
#                                                                  #
# Version : 1.5                                                    #
#                                                                  #
# Desc: Copia de elementos definidos en un fichero por red         #
# * Requisitos de funcionamiento:                                  #
# 	* El host destino debe tener la clave publica del cliente  		#
#  de no ser así el script no funcionará                          #
#       * El host cliente debe tener instalado el paquete links    #
#                                                                  #
#################################################################### 

#Constantes

SACSEXHOME="$HOME/.sacsexBckps"
SUSER=sacs
#Variables globales###
errors=0             
numfiles=0           
numdirs=0
destDateAppend=`date +%Y%m%d%H%M%S`
errorlog="$SACSEXHOME/sacsex.err.log"
log="$SACSEXHOME/sacsex.log"

propiertiesFile="$SACSEXHOME/sacsex.properties"

#Funciones
function log(){
	#Concatenamos los datos al fichero
	echo "[  `date +"%Y/%m/%d %H-%M-%S"`   ] - $1" >>$log
}
#Separador en el fichero de log
log " ##### SACSEX-BKP #####" 


#BANNER#
echo -e "\033[0;31m ################################################# \033[0m"
echo -e "\033[0;31m ####### \033[0m \033[0;33m SACSEX  1.0 \033[0m \033[0;34m BackUps por Red \033[0m \033[0;31m ####### \033[0m"
echo -e "\033[0;31m ################################################# \033[0m"
#######

if [ -f "$propiertiesFile" ]
then
	#Cargando parÃ¡metros de configuraciÃ³n.
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
		# elif [ `echo $elem | grep "SACS_LOGIN=" | wc -l` -eq 1 ]
#		then
#			sshLogin=`echo $elem | cut -d'=' -f2`
		fi
	done
#
	if [ "$SVR_CONN" != '' ] && [ "$user" != '' ] && [ "$pwd5md" != '' ]
	then
		#Recojo la IP y el Puerto del servidor para probar conexion
		SVR_IP=`echo $SVR_CONN | cut -d':' -f1`
		SVR_PORT=`echo $SVR_CONN | cut -d':' -f2`
		
		echo "Comprobando conexion"
		nc -z "$SVR_IP" "$SVR_PORT" 2>/dev/null
		if [ $? -eq 0 ]
		then
			SVR_CONN=${SVR_IP}:${SVR_PORT}
			busca=`links -dump "http://$SVR_CONN/sacsex/services/service.auth.php?user=$user&pass=$pwd5md&install=false"`
			id=`echo $busca | cut -f2 -d":"`
			# Comprobamos el id devuelto
			if [ "$id" != "1" ]
			then			
				DESTDIR=`echo $busca | cut -d":" -f3-`		
				rutas=`links -dump "http://$SVR_CONN/sacsex/services/service.dirlist.php?user=$user&pass=$pwd5md"`
				rvalidas=()
				novalidas=()
				val=0
				nval=0
				for i in ${rutas}
				do 	
					if [ -d "$i" -o -f "$i" ]
					then
						let numdirs=$numdirs+1
						rvalidas[$val]=$i
						((val++))
					else
						log "Error: No se localizo el directorio $i"
						((errors++))
						novalidas[$nval]="$i"
						((nval++))
					fi
				done
				for elem in ${novalidas[*]}
				do	
					res=`links -dump "http://$SVR_CONN/sacsex/services/service.invalidpath.php?dir=$elem&user=$user&pass=$pwd5md"`
					echo $res
					if [ `echo $res | cut -d'/' -f2` -ne 0 ]
					then
						log "No se ha podido eliminar $elem de la base de datos y este elemento no es valido"
					else
						log "$elem eliminado de la Base de datos al no ser valido"
					fi
				done
				
				#Creamos el comprimido de las rutas especificadas 
				tarname="${destDateAppend}.tar.gz"
				echo "TAR: $tarname"
				a=${rvalidas[*]}
				tar -cvzf "$tarname" `echo $a` >>${log} 2>>${errorlog}
				if [ "$?" -eq 0 ]
				then
				echo -e "#  [ Comprimiendo \033[1;33m${rvalidas[*]}\033[0m ]			\033[0;32m [ OK ] \033[0m"
					log "  [ Comprimiendo ]			 [ OK ] "
				else 
					echo -e "#  [ Comprimiendo \033[1;33m${rvalidas[*]}\033[0m ]			\033[0;31m [ Error ] \033[0m"
					log  "  [ Comprimiendo ]			 [ Error ]"
				fi
				
				#recibo el tamaño en bytes y lo transformo a Kb
				size=`stat -c%s $tarname`
				((size=$size/1024))
				echo $size
				#1.- Se pide confirmacion de que el espacio maximo no haya sido superado

				uok=`links -dump "http://$SVR_CONN/sacsex/services/service.uploadOk.php?user=$user&pass=$pwd5md&file=$tarname&size=$size"` #Pedimos autorizacion de subida
				ok=`echo $uok | cut -d":" -f1`
				if [ "$ok" == 0 ]
				then
					hoy=`date +%Y-%m-%d`
					#Si ok, se recoge el sshLogin y se hace el scp sobre la ruta de temporal
					sshLogin=`echo $uok | cut -d":" -f2-`
					echo $sshLogin
					scp "$tarname" ${sshLogin}
					copiado=$?
					# Si scpOk, se ejecuta el service.bkpsnotify para subirlo a la BD
					if [ $copiado -eq 0 ]
					then
						echo "OK"
						log "$ruta Copiado"					
						res=`links -dump "http://$SVR_CONN/sacsex/services/service.bckpsnotify.php?user=$user&pass=$pwd5md&file=$tarname&date=$hoy&size=$size"` 
						ok=`echo $res | cut -d: -f1`
						if [ "$ok" == 'Error' ]
						then
							log "$res"
						fi
					else
						log "Error al intentar subir los archivos a $DESTDIR"
					fi
				else
					log "$uok"
				fi
				#Por ultimo, eliminamos el tar del origen
				rm "$tarname" 2>/dev/null 
			else
				echo "Error: Usuario '$user' no localizado en la base de datos." >> ${errorlog}
			fi
		else
			zenity --warning --text="Error: No se ha podido acceder al puerto $SVR_PORT\n del servidor $SVR_IP.\n\n Compruebe los datos y asegurese de que dicho puerto figura abierto"
			echo -e "Error: No se ha podido acceder al puerto $SVR_PORT\n del servidor $SVR_IP.\n\n Compruebe los datos y asegurese de que dicho puerto figura abierto" >> ${errorlog}
		fi
	else
		echo "Error: faltan algunos parametros por completar en el archivo $propiertiesFile" >> ${errorlog}
	fi
	#
else
	echo "Rutas incorrectas"
	log "Rutas incorrectas"
fi
