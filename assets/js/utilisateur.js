document.getElementById("utilisateurForm").addEventListener("submit", function(e){
    e.preventDefault();

    const data = {
        nom: this.nom.value,
        prenom: this.prenom.value,
        email: this.email.value,
        mot_de_passe: this.mot_de_passe.value,
        date_naiss: this.date_naiss.value,
        role: this.role.value,
        statut: this.statut.value
    };

    fetch("../Back-end/ajouter_utilisateur.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(resp => {
        document.getElementById("message").innerText = resp.message;
        if(resp.success){
            this.reset();
        }
    })
    .catch(err => console.error(err));
});
