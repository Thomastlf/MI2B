const bouton = document.getElementById("bouton");
const css = document.getElementById("css");

function setCookie(cookieName, cookieValue, expiration = null) {// fonction qui créer le cookie
    if(expiration == null) expiration = new Date(Date.now() + 86400000).toUTCString();
        document.cookie = cookieName + "=" + cookieValue +"; expires=" + expiration + ";";
}

function getCookie(cookieName, defaultValue = null) {// fonction qui récupère le cookie
    const cookies = document.cookie.split(";");
    let row = cookies.find((row) => row.trim().startsWith(cookieName + "="));
    if(row == null) return defaultValue;
        return row.split("=")[1];
}

function changerStatut() {//fonction qui change le mode (clair/sombre)
    if (getCookie("modeSombre")=="true") {
        css.setAttribute("href", "../css/connexion.css");
        bouton.innerHTML = "Passer en sombre";
        setCookie("modeSombre", "false");
    } else {
        css.setAttribute("href", "../css_sombre/connexion.css");
        bouton.innerHTML = "Passer en clair";
        setCookie("modeSombre", "true");
    }
}

if (getCookie("modeSombre") == null) {//On créer le cookie s'il n'existe pas déjà
    setCookie("modeSombre", "false");
}

bouton.addEventListener("click", changerStatut);
