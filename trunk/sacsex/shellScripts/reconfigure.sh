#! /bin/bash

# reconfigure.sh


# OJO: Controlar el tamaño de los tamaños maxima de subida ##########################################
#   Modificar en uninstall.sh el elif del properties file 


# Ruta de fichero para editar crontab
cron="/tmp/cron_$USER"

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

linksOk=`whereis links | grep bin`

if [ ! "$linksOk" ]
then
	echo -e "Error: Es imprescindible tener instalado el paquete de links, para realizar las conexiones con el servidor. \n Para obtenerlo, utilice la orden:\n\n sudo apt-get install links"
	exit 1
else
    zenity 2>"/tmp/sacs.zenityck" 
    cat /tmp/sacs.zenityck | grep "open display"
    res=$?
    if [ $res -eq 1 ]
    then
        zenityOk=`whereis zenity | grep bin`
    fi

    seguirRe=false
    while ! $seguirRe
    do
        echo -e "Reconfiguracion \nIndica opcion a reconfigurar \n (0) Cambiar password \n (1) Reconfigurar crontab"
        read opc

        test "$opc" -gt 1 -o "$opc" -lt 0 2>/dev/null
        if [ $? -eq 0 ]
        then
            echo -e "Introduce un valor numerico y dentro de los valores del menu\n"
            seguirRe=false
        else
            seguirRe=true
        fi
    done

    if [ "$opc" == 0 ]
    then
        echo "Introduce contraseña nueva: "
        read -s passnew
        echo "Vuelve a introducir la contraseña"
        read -s passnewRep
        if [ "$passnew" != "$passnewRep" ]
        then
            echo "Error: la contraseña no es identica"
        else
            home=`echo ~`
            if [ ! -d "$home/.sacsexBckps" ]
            then
                echo -e "Error: no se encuentra la ruta $home/.sacsexBckps \nNo ha sido instalado el cliente de sacsex"
            else
                SACSEXHOME="$home/.sacsexBckps"
                if [ ! -f $SACSEXHOME/sacsex.properties ]
                then
                    echo -e "Error: no se encuentra el fichero sacsex.properties \nNo se ha podido cambiar el password"
                else
                    propiertiesFile="$SACSEXHOME/sacsex.properties"
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
                        elif [ `echo $elem | grep "SACS_USER_HOME=" | wc -l` -eq 1 ]
                        then
                            home=`echo $elem | cut -d'=' -f2`
                        fi
                    done
                    
                    # Conexión con el servicio para convertir passnew a md5
                    passnew=`links -dump "http://$SVR_CONN/sacsex/services/service.md5convert.php?text=$passnew" 2>/dev/null`
                    pwd5mdnew=`echo $passnew | cut -d'/' -f2`
                    
                    # Conexión con el servicio para validar usuario y notificar a la BD el nuevo password
                    cambio=`links -dump "http://$SVR_CONN/sacsex/services/service.changepassword.php?user=$user&pass=$pwd5md&passnew=$pwd5mdnew" 2>/dev/null`
                    cambioOk=`echo $cambio | cut -d':' -f1`
                    
                    if [ "$cambioOk" -eq 0 ]
                    then
                        pass=$pwd5mdnew
                        echo "SACS_SVR_IP=$SVR_CONN" >$propiertiesFile
                        echo "SACS_USER=$user" >> $propiertiesFile
                        echo "SACS_PASS=$pass" >> $propiertiesFile
                        echo "SACS_USER_HOME=$home" >> $propiertiesFile
                        echo "Cambio de password correctamente"
                    else
                        echo "No se han realizado cambios en el password"
                    fi
                fi
            fi
        fi

    elif [ "$opc" == 1 ]
    then
        okRe=false
        while ! $okRe
        do
            echo -e "Se borrara la configuracion de crontab ya establecida \nEstas seguro de reconfigurar crontab? \n (0) Si \n (1) No"
            read conf
            if [ "$conf" -eq 0 ]
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
                                
                # Copia de configuracion crontab establecido a un fichero por si ocurre algun error
                cron_old="/tmp/cron_old_$USER"
                echo $cron >> $cron_old

                case "$frec" in
                0)
                    if validarHora
                    then
                        h=`echo $hora | cut -d: -f1`
                        m=`echo $hora | cut -d: -f2`
                        
                        # Guardado de orden crontab en fichero
                        echo "# Copia de contenido Backup a Servidor a la(s) $hora todos los dias" > $cron
                        echo "$m $h * * * sacsex" >> $cron
                        # Guardado de contenido de fichero en crontab
                        crontab $cron
                        if [ $? -eq 0 ];then
                            echo " crontab reconfigurado correctamente"
                            ok=0
                        else
                            crontab $cron_old
                            echo " Error, no se ha podido reconfigurar crontab"
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
                            
                            # Guardado de orden crontab en fichero
                            echo "# Copia de contenido Backup a Servidor a la(s) $hora en dia de la semana ($dia)" > $cron
                            echo "$m $h * * $dia sacsex" >> $cron
                            # Guardado de contenido de fichero en crontab
                            crontab $cron
                            if [ $? -eq 0 ];then
                                echo " crontab reconfigurado correctamente"
                                ok=0
                            else
                                crontab $cron_old
                                echo " Error, no se ha podido reconfigurar crontab"
                                error=1
                            fi
                        fi
                    fi
                ;;
                2)
                    if validarMes
                    then
                        # Guardado de orden crontab en fichero
                        if [ "$mes" -eq 0 ]
                        then
                            echo "# Copia de contenido Backup a Servidor (inicio de mes)" > $cron
                            echo "* * 1 * * sacsex" >> $cron
                        elif [ "$mes" -eq 1 ]
                        then
                            echo "# Copia de contenido Backup a Servidor (quincena de mes)" > $cron
                            echo "* * 15 * * sacsex" >> $cron
                        else
                            echo "# Copia de contenido Backup a Servidor (fin de mes)" > $cron
                            echo "* * 28 * * sacsex" >> $cron
                        fi
                        # Guardado de contenido de fichero en crontab
                        crontab $cron
                        if [ $? -eq 0 ];then
                            echo " crontab reconfigurado correctamente"
                            ok=0
                        else
                            crontab $cron_old
                            echo " Error, no se ha podido reconfigurar crontab"
                            error=1
                        fi
                    fi
                ;;
                *)
                    if [ "$zenityOk" ];then
                        zenity --error --title="Error" --text="Opcion cancelada o erronea, vuelva a intentarlo\nError, no se ha podido reconfigurar crontab" 2>/dev/null
                    else
                        echo "Opcion cancelada o erronea, vuelva a intentarlo"
                        echo "Error, no se ha podido reconfigurar crontab"
                        error=1
                    fi
                ;;
                esac
                okRe=true
                
            elif [ "$conf" -eq 1 ]
            then
                okRe=true
            else
                echo -e "Opcion erronea, vuelva a intertarlo\n"
            fi
        done

    else
        echo "Error: no se permiten strings"
    fi
fi






