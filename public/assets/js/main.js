'use strict';

$(document).ready(function(){
    const url='http://secretaligner.com.devel/todo/save';
    var nombre=$('#nombre').val();
    var fechatope=$('#fechatope').val();
    $('#estado').change(function(){
       var estado=$(this).val(); 
    });
    
        //Anular la función submit que tiene por defecto el formulario para llamar por ajax
        /*
        $('#formulario_guardar_tarea').submit(function(e){
            e.preventDefault();
        });
        */
        //Recogemos el click sobre el botón guardar del formulario
        $('#guardartarea').click(function(){
            $.ajax({
                url:url,
                type:'POST',
                data:{nombre:nombre,estado:estado,fechatope:fechatope}
            }).success(function(mensaje){
                console.log(mensaje);
            });
        });
});

