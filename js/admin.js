const serv = "http://localhost:8000/";

async function bloquer(email, bouton) {
    try {
        const response = await fetch(serv + "php/maj_admin.php?" + "email="+email);
        if (response.ok) {
            const nouveauStatut = await response.text();
            if (nouveauStatut == 'Bloque') {
                bouton.innerHTML = '🚫';
            } else if (nouveauStatut == 'Actif') {
                bouton.innerHTML = '✅';
            }
        } else {
            console.error("La requête n'a pas abouti : " + response.status);
        }
    } catch (e) {
        console.error("Erreur avec fetch", e);
    }
}
