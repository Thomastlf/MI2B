const envoyer = document.getElementById("envoyer");
const champEmail = document.getElementById("email");
const champMdp = document.getElementById("mdp");

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
    }
};
