#!/bin/bash

####################################################################
#                                                                  #
# Script de copias de seguridad para servidores linux de Sadiel.   #
#                                                                  #
# Autor : Atarman                                                  #
#                                                                  #
# Version : 1.0                                                    #
#                                                                  #
# Desc: Copia de elementos definidos en un fichero por red         #
#                                                                  #
# Opciones:                                                        #
#	 							   #
#	[ Ruta ficheros ] [ Ruta propiedades ]                     #
#	                                                           #
#	:1: Ruta fichero de elementos                              #
#	:2: Ruta fichero de propiedades                            #
#                                                                  #
# * Es necesario que el host destino tenga la clave publica        #
#   del origen, de no ser asÃ­ el script no funcionar               # 
#                                                                  #
#################################################################### 

#Constantes

CHOSTNAME=${HOSTNAME}
SACSEXHOME="$HOME/.sacsexBckps"
SUSER=sacs
#Variables globales###
diractual=`pwd`
errors=0             
numfiles=0           
numdirs=0
destDateAppend=`date +%Y%m%d%H%M%S`
errorlog="$SACSEXHOME/sacsex.err.log"
log="$SACSEXHOME/sacsex.log"


#fileElems=$1
#fileProperties=$2
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

#~ if [ $# -eq 2 ]
#~ then
	#Carga de ficheros
	#if [ -f "$fileElems" -a -f "$fileProperties" ]
	if [ -f "$propiertiesFile" ]
	then
		
		#El delimitador son los saltos de linea
		#export IFS=$'\n'
		#Cargando parÃ¡metros de configuraciÃ³n.
		#for line in `cat "$fileProperties"`
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
			elif [ `echo $elem | grep "SACS_LOGIN=" | wc -l` -eq 1 ]
			then
				sshLogin=`echo $elem | cut -d'=' -f2`
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
				id=`echo $busca | cut -f2 -d"/"`
				# Comprobamos el id devuelto
				if [ "$id" != "1" ]
				then					
					rutas=`links -dump "http://$SVR_CONN/sacsex/services/service.dirlist.php?user=$user&pass=$pwd5md"`
					rvalidas=()
					novalidas=()
					val=0
					nval=0
					for i in ${rutas}
					do 	
						if [ -d "$i" ]
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
					
					for ruta in ${rvalidas[*]}
					do
						#Creamos el comprimido de archivos
						name=`basename "$ruta"`
						echo $name
						tarname="${destDateAppend}.${name}.tar.gz"
						echo "TAR: $tarname"
					 	tar -cvzf "$tarname" "$ruta" >>${log} 2>>${errorlog}
						 if [ "$?" -eq 0 ]
						 then
							 echo -e "#  [ Comprimiendo \033[1;33m${ruta}\033[0m ]			\033[0;32m [ OK ] \033[0m"
							 log "  [ Comprimiendo${ruta} ]			 [ OK ] "
						 else 
							 echo -e "#  [ Comprimiendo \033[1;33m${ruta}\033[0m ]			\033[0;31m [ Error ] \033[0m"
							 log  "  [ Comprimiendo ${ruta} ]			 [ Error ]"
						 fi
					done
					DESTDIR="/home/sacs/bkps/$id"
					sshLogin="${SUSER}@${SVR_IP}"
					echo $DESTDIR
					#ssh $sshLogin mkdir -p $DESTDIR
					#ssh $sshLogin ln -s $DESTDIR $id/
					#compruebo si existe el directorio del usuario
					
					for ruta in ${rvalidas[*]}
					do	
						hoy=`date +%Y-%m-%d`
						#recibo el tamaño en bytes y lo transformo a Kb
						name=`basename "$ruta"`
						echo $name
						tarname="${destDateAppend}.${name}.tar.gz"
						size=`stat -c%s $tarname`
						echo $size
						((size=$size/1024))
						echo "TAR2: $tarname"
						res=`links -dump "http://$SVR_CONN/sacsex/services/service.bckpsnotify.php?user=$user&pass=$pwd5md&file=$tarname&date=$hoy&size=$size"` #Obtenemos el id del Fichero
						echo $res
						ok=`echo $res | cut -d: -f1`
						if [ "$ok" == '0' ]
						then
							IDF=`echo $res | cut -d: -f2`
							tarname=${IDF}.${tarname}
							scp "$tarname" ${sshLogin}:$DESTDIR
							if [ $? -eq 0 ]
							then
								log "$ruta Copiado"
							else
								res=`links -dump "http://$SVR_CONN/sacsex/services/service.delback.php?user=$user&pass=$pwd5md&idf=$IDF"` #Lo eliminamos de la bd
								log "No se pudo copiar al servidor el fichero $ruta"
							fi
						else
						 	log "$res"
						fi
						#Por ultimo, eliminamos el tar del origen
						rm "$tarname"
						fi
					done
				else
					echo "Error: Usuario '$user' no localizado en la base de datos." >> ${errorlog}
				fi
			else
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
