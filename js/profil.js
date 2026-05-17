const serv = "http://localhost:8000/";
let cache=true
function afficherFormulaire() {
    if(cache){
        document.getElementById("formulaire").style.display = "block";
        cache=false;
    }
    else{
        document.getElementById("formulaire").style.display = "None";
        cache=true;
    }
}

async function validerModif() {
    //formulaire 
    const nom2=document.getElementById("nom2").value;
    const prenom2=document.getElementById("prenom2").value;
    //const email2=document.getElementById("email2").value;
    const adresse2=document.getElementById("adresse2").value;
    const code2=document.getElementById("code2").value;
    const numero2=document.getElementById("numero2").value;
    const date2=document.getElementById("date2").value;
    const genre2=document.getElementById("genre2").value;

    /*let contient_arobase=false;
    let contient_point=false;*/
    let contient_nombre_prenom=false;
    let contient_nombre_nom=false;
    let que_des_nombres=true;

    let correct=true;
    for(let i=0;i<nom2.length;i++){
        if(!isNaN(nom2[i]) && nom2[i]!=" " && nom2[i]!="-"){
            contient_nombre_nom=true;
        }
    }
    for(let i=0;i<prenom2.length;i++){
        if(!isNaN(prenom2[i]) && prenom2[i]!=" " && prenom2[i]!="-"){
            contient_nombre_prenom=true;
        }
    }

    for(let i=0;i<numero2.length;i++){
        if(isNaN(numero2[i])){
            que_des_nombres=false;
        }
    }

    /*for(let i=0;i<email2.length;i++){
        if(email2[i]=="@"){
            contient_arobase=true;
        }
        if(email2[i]=="."){
            contient_point=true;
        }
    }*/
    
    const erreur_js = document.getElementById("erreur_js");
    if(contient_nombre_nom){//nom
        erreur_js.innerHTML = "Format invalide : le nom ne peut pas contenir de chiffres.";
        correct=false;
    }
    else if(contient_nombre_prenom){//prenom
        correct=false;
        erreur_js.innerHTML = "Format invalide : le prénom ne peut pas contenir de chiffres.";
    }
    /*else if ((contient_arobase == false || contient_point == false)&&email2.length!=0) {//email
        correct=false;
        erreur_js.innerHTML = "Format invalide : l'email doit contenir un '@' et un '.'.";
    }*/
    else if ((!que_des_nombres || numero2.length!=10)&&numero2.length!=0){//numéro de tel
        correct=false;
        erreur_js.innerHTML = "Format invalide : le numéro de téléphone doit contenir 10 chiffres.";
    }
    else if(new Date(date2)>new Date(Date.now())){//date
        correct=false;
        erreur_js.innerHTML = "La date de naissance ne peut pas être dans le futur.";
    }
    if (correct){
        const params="nom="+nom2+"&prenom="+prenom2+"&adresse="+adresse2+"&code="+code2+"&numero="+numero2+"&date="+date2+"&genre="+genre2;//préparation des paramètres pour l'envoyer en get
        try{
            const response=await fetch(serv + "php/maj_profil.php?" + params);
            if(response.ok){
                /*if(await response.text()=="utilise"){
                    erreur_js.innerHTML = "Cet email est déjà utilisé par un autre compte.";
                }
                else{*/
                    erreur_js.innerHTML = "";
                    if(nom2){
                    document.getElementById("nom").innerHTML=nom2;
                    }
                    if(prenom2){document.getElementById("prenom").innerHTML=prenom2;}
                    /*if(email2){document.getElementById("email").innerHTML=email2;}*/
                    if(adresse2){document.getElementById("adresse").innerHTML=adresse2;}
                    if(code2){document.getElementById("code").innerHTML=code2;}
                    if(numero2){document.getElementById("numero").innerHTML=numero2;}
                    if(date2){document.getElementById("date").innerHTML=date2;}
                    if(genre2){document.getElementById("genre").innerHTML=genre2;}

                    // Cacher le formulaire
                    document.getElementById("formulaire").style.display = "none";
                //}
            }
            else{
                console.error("La requête n'a pas abouti : " + response.status + " " + response.statusText);
            }
        }
        catch(e){
            console.error("Erreur avec fetch");
            }
    }
}
