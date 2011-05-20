#!/bin/bash
# Valores de configuracion de frecuencia
#

function validarHora (){
    # Pide la hora formato hh:mm para validar
    
    horaValida=false
    echo "Indica la hora en formato hh:mm"
    read hora
    h=`echo $hora | grep "[^0-9:]" | wc -l`
    if [ $h -eq 0 ]
    then
        hora=`date -d $hora +%H:%M 2>/dev/null`
        if [ $? -eq 0 ]
        then
            horaValida=true
        else
            echo "Hora erronea"
        fi
    else
        echo "Formato de hora Erroneo"
    fi

}

function validarDia (){
    # Pide dia de la semana [ 0-6 ] Domingo = 6
    
    diaValido=false
    echo "Indica dia de la semana [ 0-6 ]"
    read dia
    #test 6 -ge $dia 2>/dev/null
    test "$dia" -gt 6 -o "$dia" -lt 0 2>/dev/null
    if [ $? -eq 0 ]
    then
        echo "Dia erroneo"
    else
        diaValido=true
    fi
    
}

function validarMes (){
    # Pide opcion 0 para inicio de mes, 1 para final del mes
    
    mesValido=false
    echo "Indica opcion para el mes"
    echo -e "  (0) Inicio del mes\n  (1) Final del mes"
    read mes
    
    test "$mes" -gt 1 -o "$mes" -lt 0 2>/dev/null
    if [ $? -eq 0 ]
    then
        echo "Opcion para mes es erroneo"
    else
        mesValido=true
        if [ "$mes" == 0 ]
        then
            mes=1
        else
            mes=28
        fi
    fi
    

}

# ----

seguir=false
while ! $seguir
do
    echo ""
    echo "Indica la Frecuencia con la que desea realizar los backups"
    echo -e " (0) Diaria\n (1) Semanal \n (2) Mensual\n"
    read frec

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
        validarHora
        echo $hora
    ;;
    1)
        validarDia
        if $diaValido
        then
            validarHora
            if $horaValida
            then
                echo $dia $hora
            fi
        fi
    ;;
    2)
        validarMes
        echo $mes
        mi_cron="/home/alumne/Escritorio/mi_cron.txt"
        # Tendria que escribir un comentario segun si es inicio
        # de mes o final de mes
        echo "# Realiza la copia de backups cada dia del mes"
        echo "* * $mes * * mkdir /home/alumne/Escritorio/Backup" >> $mi_cron
    ;;
    esac
    












