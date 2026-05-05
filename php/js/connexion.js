const bouton = document.getElementById("bouton");
const css = document.getElementById("css");
let modeSombre = false;

//fonction qui change le mdoe
function changerStatut() {
    if (modeSombre) {
        css.setAttribute("href", "../css/connexion.css");
        bouton.innerHTML = "Passer en sombre";
        modeSombre = false;
    } else {
        css.setAttribute("href", "../css/connexion_sombre.css");
        bouton.innerHTML = "Passer en clair";
        modeSombre = true;
    }
}
bouton.addEventListener("click", changerStatut);
