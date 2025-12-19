fetch("../backend/espaces.php")
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById("espaces-body");

        data.forEach(espace => {
            const row = document.createElement("tr");

            row.innerHTML = `
                <td>${espace.nom}</td>
                <td>${espace.type_espace}</td>
                <td>${espace.capacite}</td>
                <td>${espace.localisation}</td>
                <td>${espace.statut}</td>
            `;

            tbody.appendChild(row);
        });
    })
    .catch(error => console.error(error));
