const mdp = document.getElementById("mdp");
const bouton2 = document.getElementById("bouton2");
let cache = true;

function changerStatut() {//fonction qui change le mode (affiché/caché)
    if (cache) {
        mdp.setAttribute("type", "text");
        cache=false;
    } else {
        mdp.setAttribute("type", "password");
        cache = true;
    }
}

bouton2.addEventListener("click", changerStatut);
