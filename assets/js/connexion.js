document.getElementById("loginForm").addEventListener("submit", function(e){
    e.preventDefault();

    const data = {
        email: this.email.value,
        mot_de_passe: this.mot_de_passe.value
    };

    fetch("../Back-end/connexion.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(resp => {
        const msg = document.getElementById("message");
        msg.innerText = resp.message;
        if(resp.success){
            // Redirection selon rôle
            if(resp.role === "FORMATEUR") window.location.href = "../Front-end/dashboard_formateur.html";
            else if(resp.role === "ETUDIANT") window.location.href = "../Front-end/dashboard_etudiant.html";
            else if(resp.role === "DIRECTEUR") window.location.href = "../Front-end/dashboard_directeur.html";
            else if(resp.role === "TECHNICIEN") window.location.href = "../Front-end/dashboard_technicien.html";
        }
    })
    .catch(err => console.error(err));
});
