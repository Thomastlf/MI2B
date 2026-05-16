document.addEventListener("DOMContentLoaded", () => {
    
    const textarea = document.getElementById("commentaire");
    const compteur = document.getElementById("compteurCommentaire");
    const form = document.getElementById("form-notation");

    // 1. Gestion du compteur de caractères en direct
    if (textarea != null && compteur != null) {
        const limiteMax = textarea.getAttribute("maxlength");
        
        // Initialisation à l'ouverture
        compteur.innerHTML = `(${textarea.value.length}/${limiteMax})`;

        // Mise à jour à chaque frappe
        textarea.addEventListener("input", () => {
            let nbCaracteres = textarea.value.length;
            compteur.innerHTML = `(${nbCaracteres}/${limiteMax})`;
        });
    }

    // 2. Validation du formulaire (Côté Client)
    if (form != null) {
        form.addEventListener("submit", (e) => {
            // On vérifie si au moins un bouton radio (étoile) est coché
            const etoileCochee = document.querySelector('input[name="star"]:checked');
            
            if (etoileCochee == null) {
                // Si aucune étoile n'est cochée, on bloque l'envoi vers le serveur PHP
                e.preventDefault();
                alert("Veuillez sélectionner une note avec les étoiles avant d'envoyer votre avis ! ⭐");
            }
        });
    }
});