$(function(){
    $('#testbd').click(function(e){
        $.ajax({
            url: $('base').attr('href') + 'testbd.php',
            type: 'POST',
            data: 'servidor_bd=' + $('#servidor_bd').val() + '&usuario_bd=' + $('#usuario_bd').val() + '&clave_bd=' + $('#clave_bd').val() + '&bd='+ $('#bd').val(),
            success: function(data) {
                $('#statusbd').html(data)
            }
        });
    });
    
    $('#delete').click(function(e){
        e.preventDefault();
        $.ajax({
            url: $('base').attr('href') + 'eliminar.php',
            success: function(data) {
                $('#deletestatus').html(data)
            }
        });
    });
});