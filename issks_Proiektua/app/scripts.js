function editElement(el) {
    document.getElementById('editModal').style.display = 'block';
    document.getElementById('izena_original').value = el.izena;
    document.getElementById('modal_izena').value = el.izena;
    document.getElementById('modal_mota').value = el.mota;
    document.getElementById('modal_bizitza').value = el.bizitza;
    document.getElementById('modal_erasoa').value = el.erasoa;
    document.getElementById('modal_defentsa').value = el.defentsa;
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

window.onclick = function(e) {
    if (e.target == document.getElementById('editModal')) {
        closeEditModal();
    }
}
