const envoyer = document.getElementById("envoyer");
const champEmail = document.getElementById("email");
const champMdp = document.getElementById("mdp");
const erreur_js = document.getElementById("erreur_js");

envoyer.onsubmit = function (e) {//evenement qui se déclenche lorsque le formulaire est envoyé et qui bloque l'envoie du formulaire vers le serveur si les champs sont invalides
    let email=champEmail.value;
    let mdp=champMdp.value;
    let contient_arobase=false;
    let contient_point=false;
    for(let i=0;i<email.length;i++){
        if(email[i]=="@"){
            contient_arobase=true;
        }
        if(email[i]=="."){
            contient_point=true;
        }
    }
    if (contient_arobase == false || contient_point == false) {
        e.preventDefault();//bloque l'envoie du formulaire
        erreur_js.innerHTML = "Format invalide : l'email doit contenir un '@' et un '.'.";
    }
    else if (mdp.length<8){
        e.preventDefault();//bloque l'envoie du formulaire
        erreur_js.innerHTML = "Format invalide : le mot de passe doit avoir une longueur d'au moins 8 caractères.";
    }
    else{
        erreur_js.innerHTML = "";
    }
};
