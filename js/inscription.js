const envoyer = document.getElementById("envoyer");
const champNom = document.getElementById("nom");
const chamPrenom = document.getElementById("prenom");
const champEmail = document.getElementById("email");
const champAdresse = document.getElementById("adresse");
const champNumero = document.getElementById("numero");
const champMdp = document.getElementById("mdp");
const erreur_js = document.getElementById("erreur_js");

envoyer.onsubmit = function (e) {//evenement qui se déclenche lorsque le formulaire est envoyé et qui bloque l'envoie du formulaire vers le serveur si les champs sont invalides
    let nom=champNom.value;
    let prenom=chamPrenom.value;
    let email=champEmail.value;
    let adresse=champAdresse.value;
    let numero=champNumero.value;
    let mdp=champMdp.value;

    let contient_arobase=false;
    let contient_point=false;
    let contient_nombre_prenom=false;
    let contient_nombre_nom=false;
    let que_des_nombres=true;

    for(let i=0;i<nom.length;i++){
        if(!isNaN(nom[i]) && nom[i]!=" " && nom[i]!="-"){
            contient_nombre_nom=true;
        }
    }
    for(let i=0;i<prenom.length;i++){
        if(!isNaN(prenom[i]) && prenom[i]!=" " && prenom[i]!="-"){
            contient_nombre_prenom=true;
        }
    }

    for(let i=0;i<numero.length;i++){
        if(isNaN(numero[i])){
            que_des_nombres=false;
        }
    }

    for(let i=0;i<email.length;i++){
        if(email[i]=="@"){
            contient_arobase=true;
        }
        if(email[i]=="."){
            contient_point=true;
        }
    }
    if(contient_nombre_nom || nom.length==0){//nom
        e.preventDefault();
        erreur_js.innerHTML = "Format invalide : le nom ne peut pas contenir de chiffres et ne doit pas être vide.";
    }
    else if(contient_nombre_prenom || prenom.length==0){//prenom
        e.preventDefault();
        erreur_js.innerHTML = "Format invalide : le prénom ne peut pas contenir de chiffres et ne doit pas être vide.";
    }
    else if (contient_arobase == false || contient_point == false) {//email
        e.preventDefault();
        erreur_js.innerHTML = "Format invalide : l'email doit contenir un '@' et un '.'.";
    }
    else if(adresse.length==0){//adresse
        e.preventDefault();
        erreur_js.innerHTML = "Format invalide : l'adresse ne doit pas être vide.";
    }
    else if (!que_des_nombres || numero.length!=10){//numéro de tel
        e.preventDefault();
        erreur_js.innerHTML = "Format invalide : le numéro de téléphone doit contenir 10 chiffres.";
    }
    else if (mdp.length<8){//mdp
        e.preventDefault();
        erreur_js.innerHTML = "Format invalide : le mot de passe doit avoir une longueur d'au moins 8 caractères.";
    }
    else{//correct
        erreur_js.innerHTML = "";
    }
};
