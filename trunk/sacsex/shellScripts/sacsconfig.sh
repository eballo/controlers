#!/bin/bash
# Programa: sacsconfig.sh
# Autores: Giorgio y Cristina
# Fecha de creación: durante credito de sintesis
# Descripcion:
#  Script para configurar la aplicación crontab del usuario
#  Permite establecer la frecuencia con la se realizara la copia de Backups a Servidor
# 

# Comprobamos si aplicación zenity esta instalado
zenityOk=`whereis zenity | grep bin`

function validarHora (){
    # Pide una hora en formato hh:mm para validar
    # retorna: 0 -> true
    #          1 -> false
    
    horaValida=1
    if [ "$zenityOk" ];then 
        hora=`zenity --entry --text="Indica la hora en formato hh:mm" --title="Hora"`
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
                zenity --error --text="Hora erronea"
            else
                echo "Hora erronea"
            fi
        fi
    else
        if [ "$zenityOk" ];then
            zenity --error --text="Formato de hora Erroneo"
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
        dia=`zenity --list --title="Semama" --text="Indica dia de la semana " --column="Opc" --column="Dia" 0 Domingo 1 Lunes 2 Martes 3 Miércoles 4 Jueves 5 Viernes 6 Sábado`
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
                zenity --error --text="Dia erronea"
            else
                echo "Dia erronea"
            fi
        else
            diaValido=0
        fi
    else
        if [ "$zenityOk" ];then
            zenity --error --text="Formato de dia Erroneo"
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
        mes=`zenity --list --title="Mes" --text="Indica opción para el mes" --column="Opc" --column="Tiempo" 0 "Inicio del mes" 1 "Quincena del mes" 2 "Final del mes"`
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
                zenity --error --text="Opcion para mes es erroneo"
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
            zenity --error --text="Formato de mes erroneo"
        else
            echo "Formato de mes erroneo"
        fi
    fi
    return $mesValido
}

# MAIN

# Ruta de fichero para editar crontab
cron="/tmp/cron_$USER"

# Ruta del directorio Backup de cliente
dirBackup="$HOME/Backup"

# Ruta directorio destino del usuario en Servidor
# Si fuese por SCP seria poner el comando a la hora de guardar en fichero
dirServidor="$HOME/Escritorio"

seguir=false

while ! $seguir
do
    if [ "$zenityOk" ];then
        frec=`zenity --list --title="Frecuencia" --text="Indica la Frecuencia con la que desea realizar los backups" --column="Opc" --column="Tiempo" 0 "Diaria" 1 "Semanal" 2 "Mensual"`
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
            echo "# Copia de contenido Backup a Servidor a la(s) $hora todos los dias" > $cron
            echo "$m $h * * * cp -R $dirBackup $dirServidor" >> $cron
            # Guardado de contenido de fichero en crontab
            crontab $cron
            if [ $? -eq 0 ];then
                echo "###################################"
                echo " crontab configurado correctamente"
                echo "###################################"
            else
                echo "###########################################"
                echo " Error, no se ha podido configurar crontab"
                echo "###########################################"
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
                echo "$m $h * * $dia cp -R $dirBackup $dirServidor" >> $cron
                # Guardado de contenido de fichero en crontab
                crontab $cron
                if [ $? -eq 0 ];then
                    echo "###################################"
                    echo " crontab configurado correctamente"
                    echo "###################################"
                else
                    echo "###########################################"
                    echo " Error, no se ha podido configurar crontab"
                    echo "###########################################"
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
                echo "* * 1 * * cp -R $dirBackup $dirServidor" >> $cron
            elif [ "$mes" -eq 1 ]
            then
                echo "# Copia de contenido Backup a Servidor (quincena de mes)" > $cron
                echo "* * 15 * * cp -R $dirBackup $dirServidor" >> $cron
            else
                echo "# Copia de contenido Backup a Servidor (fin de mes)" > $cron
                echo "* * 28 * * cp -R $dirBackup $dirServidor" >> $cron
            fi
            # Guardado de contenido de fichero en crontab
            crontab $cron
            if [ $? -eq 0 ];then
                echo "###################################"
                echo " crontab configurado correctamente"
                echo "###################################"
            else
                echo "###########################################"
                echo " Error, no se ha podido configurar crontab"
                echo "###########################################"
            fi
        fi
    ;;
    *)
        if [ "$zenityOk" ];then
            zenity --error --title="Error" --text="Opcion cancelada o erronea, vuelva a intentarlo\nError, no se ha podido configurar crontab"
        else
            echo "Opcion cancelada o erronea, vuelva a intentarlo"
            echo "Error, no se ha podido configurar crontab"
        fi
    ;;
    esac
