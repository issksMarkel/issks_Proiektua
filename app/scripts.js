function editPokemon(pokemon) {
    document.getElementById('editModal').style.display = 'block';
    document.getElementById('izena_original').value = pokemon.izena;
    document.getElementById('modal_izena').value = pokemon.izena;
    document.getElementById('modal_mota').value = pokemon.mota;
    document.getElementById('modal_bizitza').value = pokemon.bizitza;
    document.getElementById('modal_erasoa').value = pokemon.erasoa;
    document.getElementById('modal_defentsa').value = pokemon.defentsa;
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function confirmDelete() {
    return confirm('Ziur zaude pokemon hau ezabatu nahi duzula?');
}

window.onclick = function(e) {
    const modal = document.getElementById('editModal');
    if (modal && e.target == modal) {
        closeEditModal();
    }
}

// Event listeners para botones
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.btn-edit-pokemon');
    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const pokemonJson = this.getAttribute('data-pokemon');
            const pokemon = JSON.parse(pokemonJson);
            editPokemon(pokemon);
        });
    });

    const deleteButtons = document.querySelectorAll('.btn-delete-pokemon');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirmDelete()) {
                e.preventDefault();
            }
        });
    });

    const closeBtn = document.querySelector('.close');
    if (closeBtn) {
        closeBtn.addEventListener('click', closeEditModal);
    }

    const secondaryBtn = document.querySelector('.btn-secondary');
    if (secondaryBtn) {
        secondaryBtn.addEventListener('click', closeEditModal);
    }
});