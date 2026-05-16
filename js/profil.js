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
    const adresse2=document.getElementById("adresse2").value;
    const code2=document.getElementById("code2").value;
    const numero2=document.getElementById("numero2").value;

    const params="nom="+nom2+"&prenom="+prenom2+"&adresse="+adresse2+"&code="+code2+"&numero="+numero2;//préparation des paramètres pour l'envoyer en get
    try{
        const response=await fetch(serv + "php/maj_profil.php?" + params);
        if(response.ok){
            document.getElementById("nom").innerHTML=nom2;
            document.getElementById("prenom").innerHTML=prenom2;
            document.getElementById("adresse").innerHTML=adresse2;
            document.getElementById("code").innerHTML=code2;
            document.getElementById("numero").innerHTML=numero2;

            // Cacher le formulaire
            document.getElementById("formulaire").style.display = "none";
        }
        else{
            console.error("La requête n'a pas abouti : " + response.status + " " + response.statusText);
        }
    }
    catch(e){
        console.error("Erreur avec fetch");
    }
}