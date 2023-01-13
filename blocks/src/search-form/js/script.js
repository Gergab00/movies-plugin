/**
 * 
1. Crea un formulario de búsqueda en tu plantilla de WordPress, utilizando el input de tipo "text" y el botón de tipo "submit". Este formulario debe tener una acción que apunte a un archivo de tu plugin o tema que maneje la búsqueda.

2. En el archivo de tu plugin o tema, agrega una función que maneje la búsqueda y devuelva los resultados en formato json. Esta función debe ser accesible a través de una url específica que se utilizará en el siguiente paso.

3. Utiliza jQuery para detectar cuando se presiona el botón de búsqueda y enviar la búsqueda mediante una llamada ajax a la url específica de la función de búsqueda. En el callback de la llamada ajax, maneja los resultados devueltos y actualiza la página con los resultados.

4. Agrega una funcion escucha en javascript que detecte cuando se escribe en el input de búsqueda y llame a la funcion de busqueda ajax

5. Asegúrate de incluir el archivo de jQuery en tu plantilla de WordPress y de envolver tu código javascript en una función de inicialización para asegurarte de que se ejecuta después de que se haya cargado la página.

6. Finalmente, asegúrate de que tu función de búsqueda está protegida contra inyección SQL y que solo está disponible para usuarios autorizados.
 */
import jQuery from 'jquery';

(function( $ ) {
	'use strict';
    $('#search-submit').on('click', function(e) {
        e.preventDefault();

        var search_query = $('#search-input').val();

        $.ajax({
            url: search_form_script.ajax_url,
            type: 'get',
            data: {
                action: 'search_handler',
                s: search_query
            },
            beforeSend: function(){
                $('.message').show().removeClass(['d-none']);
                $('#search-submit').prop('disabled', true);
            },
            success: function(data) {
                console.table(data);
                $('#searchModal').modal('show');
                $("#search-input").val("");
                $('#search-submit').prop('disabled', false);
                $('.message').show().addClass(['d-none']);
                var output = '';
                $.each(data.data, function(key, val) {
                    output += '<div class="col-md-3">';
                    var card = '<div class="card my-4">' +
                    '<img src="' + val.image + '" class="card-img-top" alt="' + val.name + '">' +
                    '<div class="card-img-overlay d-flex align-items-end"><div>' +
                    '<h5 class="card-title bg-dark bg-opacity-50">' + val.name + '</h5>' +
                    '<a href="' + val.url + '" class="btn btn-primary">'+ val.btntext+'</a>' +
                    '</div></div>' +
                    '</div>';
                    output += card;
                    output += '</div>';
                    });

                $('#search-results').html(output);
            },
            error: function(response) {
                console.log(response);
                $('#searchModal').modal('hide');
            }
        });
    });
})( jQuery );