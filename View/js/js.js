var base_url = 'http://localhost/proyecto_desis/';

var voto = {
    id: null,
    nombre      : $('#nombre_apellido_id'),
    alias       : $('#alias_id'),
    rut         : $('#rut_id'),
    email       : $('#email_id'),
    region      : $('#region_id'),
    comuna      : $('#comuna_id'),
    candidato   : $('#candidato_id'),
    nosotros_web   : $('#rd_nosotros_1_id'),
    nosotros_tv    : $('#rd_nosotros_2_id'),
    nosotros_rs    : $('#rd_nosotros_3_id'),
    nosotros_amigo : $('#rd_nosotros_4_id'),
}

$(document).ready(function() {
    cargarSelectorRegion();
    votar();
    borrarAlerta();
    cargarSelectorComuna();
    soloLetras();
});

function cargarSelectorRegion(){

    voto.comuna.html('');

    $.ajax({
        method: "GET",
        url: base_url + 'Funciones/CargarSelectores.php',
    }).done(function( result ) {
        
        var obj = JSON.parse(result);
        $.each(obj.region, function (i, value) { 
            voto.region.append('<option value="'+value[0]+'">'+value[1]+'</option>');
        });
        voto.region.val('');

        $.each(obj.candidato, function (i, value) { 
            voto.candidato.append('<option value="'+value[0]+'">'+value[1]+'</option>');
        });
        voto.candidato.val('');
        
    });
}

function cargarSelectorComuna(){

    $(document).on('change', '#region_id', function(e){

        voto.comuna.html('');

        $.ajax({
            method: "POST",
            url: base_url + 'Funciones/CargarSelectorComuna.php',
            data: {id: voto.region.val(),}
        }).done(function( result ) {
            
            var obj = JSON.parse(result);
            $.each(obj, function (i, value) { 
                voto.comuna.append('<option value="'+value[0]+'">'+value[1]+'</option>');
            });
            voto.comuna.val('');
            
        });
    })

}

function votar(){
    $(document).on('click', '#votar_id', function(e) {
        e.preventDefault();
        $('#alertas').html('');
        $.ajax({
            method: "POST",
            url: base_url + 'Funciones/Guardar.php',
            data: {
                    nombre:         voto.nombre.val(),
                    alias:          voto.alias.val(),
                    rut:            voto.rut.val(),
                    email:          voto.email.val(),
                    region:         voto.region.val(),
                    comuna:         voto.comuna.val(),
                    candidato:      voto.candidato.val(),
                    nosotros_web:   voto.nosotros_web.is(':checked') ? true : false,
                    nosotros_tv:    voto.nosotros_tv.is(':checked') ? true : false,
                    nosotros_rs:    voto.nosotros_rs.is(':checked') ? true : false,
                    nosotros_amigo: voto.nosotros_amigo.is(':checked') ? true : false,
                  }
        }).done(function( result ) {
     
            var obj = JSON.parse(result);
            console.log(obj);
            if(obj.estado == true){
                console.log(obj);
                if(obj.errores.length != 0){
                    alertas(obj.errores);
                }else{
                    limpiarFormulario();
                    toastr["success"]("Voto guardado exitosamente");
                }
                
            }else{
                toastr["error"]("Error al guardar el voto");
            }
            
        });
    
    })
}

function alertas(errores){
    let alerta_txt = '<div> \n' +
                     '<ul>';

    $.each(errores, function (i, error) { 
        alerta_txt += '<li>'+error+'</li>';
    });

    alerta_txt += '</ul> \n' +
                  '</div>'; 

    toastr["error"](alerta_txt);
}

function borrarAlerta(){
    $('.btn-close').on('click', function(e){
        $('#alertas').html('');
    })
}

function limpiarFormulario(){
    voto.id = null;
    voto.nombre.val('');
    voto.alias.val('');
    voto.rut.val('');
    voto.email.val('');
    voto.region.val('');
    voto.comuna.val('');
    voto.comuna.html('');
    voto.candidato.val('');
    voto.nosotros_web.prop('checked', false);
    voto.nosotros_tv.prop('checked', false);
    voto.nosotros_rs.prop('checked', false);
    voto.nosotros_amigo.prop('checked', false);
}

function soloLetras(){
    $('#nombre_apellido_id').on('input', function (e) {
        if (!/^[ a-zA-Záéíóúüñ]*$/i.test(this.value)) {
            this.value = this.value.replace(/[^ a-zA-Záéíóúüñ]+/ig,"");
        }
    });
}

  
  

