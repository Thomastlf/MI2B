document.addEventListener("DOMContentLoaded", () => {
    const filtrePays = document.getElementById("filtre-pays");
    const filtreCat = document.getElementById("filtre-categorie");
    const filtreAllergene = document.getElementById("filtre-allergene");
    const triPrix = document.getElementById("tri-prix");
    
    const productGrid = document.getElementById("product-grid");
    const promoBannerContainer = document.getElementById("promo-banner-container");

    // Fonction asynchrone qui s'active à chaque changement
    function actualiserMenu() {
        let pays = filtrePays.value;
        let cat = filtreCat.value;
        let allergene = filtreAllergene.value;
        let tri = triPrix.value;

        const paysAvecMenuComplet = ["France", "Italie", "Japon"];

        // Si le pays sélectionné est dans notre liste, on affiche la promo
        if (paysAvecMenuComplet.includes(pays)) {
            promoBannerContainer.innerHTML = `
                <div class="menu-promo-banner">
                    <h3>🎁 Pack Destination : ${pays}</h3>
                    <p>Commandez le menu complet (Entrée + Plat + Dessert) et profitez de <strong>-10% de remise immédiate !</strong></p>
                    <button type="submit" name="pack_menu" value="${pays}" class="btn-pack">
                        Ajouter le Pack Menu (1 pers.)
                    </button>
                </div>
            `;
        } else {
            // Sinon (Maroc, Suisse, etc.), on la cache
            promoBannerContainer.innerHTML = ""; 
        }


        let url = "api_menu.php?pays=" + pays + "&categorie=" + cat + "&allergene=" + allergene + "&tri=" + tri;

        // Appel Fetch
        window.fetch(new Request(url))
        .then((response) => {
            if(!response) {
                console.log("Erreur de promesse");
            } else {
                return response.json();
            }
        })
        .then((data) => {
            // On vide la grille
            productGrid.innerHTML = "";

            if(data.length === 0) {
                productGrid.innerHTML = "<p style='text-align:center; width:100%;'>Aucun plat ne correspond à vos critères de recherche ✈️.</p>";
                return;
            }

            // On la remplit
            for (let plat of data) {
                let htmlPlat = `
                    <div class="product-card">
                        <img src="${plat.img}" alt="${plat.nom}">
                        <div class="product-info">
                            <h3>
                                ${plat.nom}
                                <div class="info-container">
                                    <span class="info-icon">i</span>
                                    <div class="info-tooltip">
                                        <strong>Ingrédients :</strong><br>
                                        ${plat.ingredients.join(', ')}<br>
                                        <span class="allergenes-list">⚠️ Allergènes : ${plat.allergenes.join(', ')}</span>
                                    </div>
                                </div>
                            </h3>
                            <p class="country-label">${plat.pays}</p>
                            <span class="price">${parseFloat(plat.prix).toFixed(2)}€</span>
                            
                            <div class="quantity-selector">
                                <label>Quantité :</label>
                                <input type="number" name="qte[${plat.nom}]" value="0" min="0">
                            </div>
                        </div>
                    </div>
                `;
                productGrid.innerHTML += htmlPlat;
            }
        })
        .catch((error) => {
            console.error("Erreur Fetch :", error);
        });
    }

    filtrePays.addEventListener("change", actualiserMenu);
    filtreCat.addEventListener("change", actualiserMenu);
    filtreAllergene.addEventListener("change", actualiserMenu);
    triPrix.addEventListener("change", actualiserMenu);

    actualiserMenu();
});