console.log('Script de jQuery ejecutándose');

$(document).ready(function() {
    $('#nivel').change(function() {
        console.log('Evento de cambio activado');
        var nivelSeleccionado = $(this).val();
        console.log('Nivel seleccionado:', nivelSeleccionado);

        // Llamada AJAX para obtener paralelos y materias según el nivel seleccionado
        $.ajax({
            url: 'matriculacion.php',
            method: 'POST',
            data: {
                nivel: nivelSeleccionado,
                obtenerParalelos: true,
                obtenerMaterias: true,
                vincular: true
            },
            success: function(response) {
                // Añadir puntos de comprobación
                console.log('Respuesta de AJAX:', response);

                // Intentar parsear la respuesta JSON
                try {
                    var data = JSON.parse(response);

                    // Llenar el select de paralelos
                    var paraleloSelect = $('#paralelo');
                    paraleloSelect.empty();
                    // Añadir opción predeterminada
                    paraleloSelect.append('<option value="" selected disabled>Selecciona un opción</option>');
                    $.each(data.paralelos, function(index, value) {
                        paraleloSelect.append('<option value="' + value.id_paralelo + '">' + value.nombre_paralelo + '</option>');
                    });

                    // Llenar el select de materias
                    var materiaSelect = $('#materia');
                    materiaSelect.empty();
                    // Añadir opción predeterminada
                    materiaSelect.append('<option value="" selected disabled>Selecciona un opción</option>');
                    $.each(data.materias, function(index, value) {
                        materiaSelect.append('<option value="' + value.id_materia + '">' + value.nombre_materia + '</option>');
                    });
                } catch (error) {
                    console.error('Error al parsear la respuesta JSON:', error);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la llamada AJAX:', status, error);
            }
        });
    });
});
