// scripts.js - Todo el JavaScript externo - VERSIÓN CORREGIDA

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado - Inicializando eventos');
    
    // Modal functionality
    const editModal = document.getElementById('editModal');
    const closeModal = document.getElementById('closeModal');
    const cancelEdit = document.getElementById('cancelEdit');
    
    // Event listeners for edit buttons - CORREGIDO
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-edit')) {
            console.log('Botón editar clickeado');
            const elementData = e.target.getAttribute('data-element');
            if (elementData) {
                try {
                    const element = JSON.parse(elementData);
                    editElement(element);
                } catch (error) {
                    console.error('Error parsing element data:', error);
                }
            }
        }
    });
    
    // Close modal events
    if (closeModal) {
        closeModal.addEventListener('click', closeEditModal);
    }
    
    if (cancelEdit) {
        cancelEdit.addEventListener('click', closeEditModal);
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === editModal) {
            closeEditModal();
        }
    });
    
    console.log('Eventos inicializados correctamente');
});

function editElement(el) {
    console.log('Editando elemento:', el);
    const modal = document.getElementById('editModal');
    if (!modal) {
        console.error('Modal no encontrado');
        return;
    }
    
    document.getElementById('izena_original').value = el.izena;
    document.getElementById('modal_izena').value = el.izena;
    document.getElementById('modal_mota').value = el.mota;
    document.getElementById('modal_bizitza').value = el.bizitza;
    document.getElementById('modal_erasoa').value = el.erasoa;
    document.getElementById('modal_defentsa').value = el.defentsa;
    
    modal.style.display = 'block';
    console.log('Modal mostrado');
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    if (modal) {
        modal.style.display = 'none';
        console.log('Modal cerrado');
    }
}
