// validation.js - Validaciones de formulario

document.addEventListener('DOMContentLoaded', function() {
    // Registro form validation
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            if (!validatuFormularioa()) {
                e.preventDefault();
            }
        });
    }
    
    // Profile form validations
    const profileForm = document.querySelector('form[onsubmit="return validarDatosPersonales()"]');
    if (profileForm) {
        profileForm.onsubmit = validarDatosPersonales;
    }
    
    const passwordForm = document.querySelector('form[onsubmit="return validarPassword()"]');
    if (passwordForm) {
        passwordForm.onsubmit = validarPassword;
    }
});

function validatuFormularioa() {
    const nan = document.getElementById('nan').value;
    const tel = document.getElementById('telefonoa').value;
    const email = document.getElementById('email').value;
    
    if (!/^[0-9]{8}-[A-Z]$/.test(nan)) {
        alert('NAN formatu okerra (Adibidez: 12345678-Z)');
        return false;
    }
    if (!/^[0-9]{9}$/.test(tel)) {
        alert('Telefonoak 9 zenbaki izan behar ditu');
        return false;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert('Email formatu okerra');
        return false;
    }
    
    return true;
}

function validarDatosPersonales() {
    const tel = document.getElementById('telefono').value;
    const email = document.getElementById('email').value;
    
    if (!/^[0-9]{9}$/.test(tel)) {
        alert('Telefonoak 9 zenbaki izan behar ditu');
        return false;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert('Email formatu okerra');
        return false;
    }
    
    return true;
}

function validarPassword() {
    const newPass = document.getElementById('new_password').value;
    const confirmPass = document.getElementById('confirm_password').value;
    
    if (newPass.length < 6) {
        alert('Pasahitzak gutxienez 6 karaktere izan behar ditu');
        return false;
    }
    if (newPass !== confirmPass) {
        alert('Pasahitz berriak ez datoz bat');
        return false;
    }
    
    return true;
}
