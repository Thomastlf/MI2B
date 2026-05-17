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
    if (getCookie("theme")=="true") {
        css.removeAttribute("href");
        bouton.innerHTML = "Passer en mode malvoyant";
        setCookie("theme", "false");
    } else {
        css.setAttribute("href", "../css/theme.css");
        bouton.innerHTML = "Passer en mode par défaut";
        setCookie("theme", "true");
    }
}

if (getCookie("theme") == null) {//On créer le cookie s'il n'existe pas déjà
    setCookie("theme", "false");
}

bouton.addEventListener("click", changerStatut);
