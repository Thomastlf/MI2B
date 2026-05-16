document.addEventListener("DOMContentLoaded", () => {
    const totalInitial = parseFloat(document.getElementById("total-initial").value);
    const btnValider = document.getElementById("btn-valider-modif");
    const msgDiff = document.getElementById("message-diff");
    const inputDiff = document.getElementById("montant-supplementaire");
    const nouveauTotalDisplay = document.getElementById("nouveau-total-display");

    function updateCart() {
        let nouveauTotal = 0;
        
        document.querySelectorAll('.qte-input').forEach(input => {
            let qte = parseInt(input.value) || 0;
            let prix = parseFloat(input.dataset.prix);
            nouveauTotal += qte * prix;
        });
        
        nouveauTotalDisplay.innerText = nouveauTotal.toFixed(2) + " €";
        
        let diff = nouveauTotal - totalInitial;
        
        if (diff > 0) {
            msgDiff.innerHTML = "Supplément à payer : <strong>" + diff.toFixed(2) + " €</strong>";
            msgDiff.style.color = "#FF1493";
            btnValider.innerText = "Payer la différence (" + diff.toFixed(2) + " €) 💳";
            inputDiff.value = diff.toFixed(2);
        } else {
            msgDiff.innerHTML = "La commande est moins chère ou identique. <br><em>Aucun remboursement ne sera effectué.</em>";
            msgDiff.style.color = "#00FFFF";
            btnValider.innerText = "Valider les modifications ✅";
            inputDiff.value = 0;
        }
    }

    document.querySelectorAll('.btn-plus').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            let input = document.getElementById(btn.dataset.target);
            input.value = parseInt(input.value) + 1;
            updateCart();
        });
    });

    document.querySelectorAll('.btn-moins').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            let input = document.getElementById(btn.dataset.target);
            if (parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;
                updateCart();
            }
        });
    });

    document.querySelectorAll('.qte-input').forEach(input => {
        input.addEventListener('input', updateCart);
    });

    updateCart();
});
