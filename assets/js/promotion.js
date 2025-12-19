document.getElementById("promotionForm").addEventListener("submit", function(e){
    e.preventDefault();

    const data = { annee_academique: this.annee_academique.value };

    fetch("../Back-end/ajouter_promotion.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(resp => {
        document.getElementById("message").innerText = resp.message;
        if(resp.success) this.reset();
    })
    .catch(err => console.error(err));
});
