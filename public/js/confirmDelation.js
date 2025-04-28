document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar todos los botones de eliminar
    const deleteButtons = document.querySelectorAll('.delete-favorite');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Prevenir el envío inmediato
            
            const form = this.closest('form'); // Encontrar el formulario más cercano
            
            Swal.fire({
                title: '¿Eliminar de favoritos?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Enviar el formulario si se confirma
                }
            });
        });
    });
});