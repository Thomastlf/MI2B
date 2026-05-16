document.addEventListener("DOMContentLoaded", () => {

    // 1. Sécuriser le bouton CONFIRMER (Validation de livraison)
    const formsLivraison = document.querySelectorAll('.form-livraison');
    
    for(let form of formsLivraison) {
        form.addEventListener('submit', (e) => {
            // Demande une confirmation au livreur
            let confirmation = confirm("Avez-vous bien remis cette commande au passager ? \n\nCette action est définitive.");
            
            // S'il clique sur "Annuler", on bloque l'envoi au serveur PHP
            if(!confirmation) {
                e.preventDefault();
            }
        });
    }

    // 2. Sécuriser le bouton ABANDONNER
    const formsAbandon = document.querySelectorAll('.form-abandon');
    
    for(let form of formsAbandon) {
        form.addEventListener('submit', (e) => {
            let confirmation = confirm("Êtes-vous sûr de vouloir abandonner cette course ? \n\nElle sera remise à la disposition des autres livreurs.");
            
            if(!confirmation) {
                e.preventDefault();
            }
        });
    }
});