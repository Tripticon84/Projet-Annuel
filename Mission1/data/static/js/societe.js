// Charger les informations de la société
function loadCompanyInfo(societyId) {
  fetch(`/api/company/getOne.php?societe_id=${societyId}`, {
    method: "GET",
    headers: {
      Authorization: "Bearer " + getToken(),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      companyData = data;
      document.getElementById(
        "company-name"
      ).textContent = `Tableau de bord - ${data.nom}`;

      const companyInfoDiv = document.getElementById("company-info");
      companyInfoDiv.innerHTML = `
                <h4>${data.nom}</h4>
                <p><strong>Contact:</strong> ${data.contact_person}</p>
                <p><strong>Email:</strong> ${data.email}</p>
                <p><strong>Téléphone:</strong> ${data.telephone}</p>
                <p><strong>Adresse:</strong> ${data.adresse}</p>
                <p><strong>SIRET:</strong> ${data.siret}</p>
                <p><strong>Date de création:</strong> ${new Date(
                  data.date_creation
                ).toLocaleDateString("fr-FR")}</p>
            `;
    })
    .catch((error) => {
      console.error(
        "Erreur lors du chargement des informations de la société:",
        error
      );
      document.getElementById(
        "company-info"
      ).innerHTML = `<div class="alert alert-danger">Erreur lors du chargement des informations</div>`;
    });
}

// Charger les contrats avec filtres optionnels
function loadContracts(societyId, filters = {}) {
  // Construire l'URL avec les filtres
  let url = `/api/company/getContract.php?societe_id=${societyId}`;

  // Ajouter les filtres s'ils sont définis
  if (filters.status) url += `&statut=${filters.status}`;
  if (filters.startDate) url += `&date_debut=${filters.startDate}`;
  if (filters.endDate) url += `&date_fin=${filters.endDate}`;

  fetch(url, {
    method: "GET",
    headers: {
      Authorization: "Bearer " + getToken(),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      const tableBody = document.getElementById("contracts-table");

      if (data.length === 0) {
        tableBody.innerHTML =
          '<tr><td colspan="6" class="text-center">Aucun contrat trouvé</td></tr>';
        return;
      }

      tableBody.innerHTML = "";
      data.forEach((contract) => {
        tableBody.innerHTML += `
                    <tr>
                        <td>${contract.devis_id}</td>
                        <td>${new Date(contract.stard_date).toLocaleDateString(
                          "fr-FR"
                        )}</td>
                        <td>${new Date(contract.end_date).toLocaleDateString(
                          "fr-FR"
                        )}</td>
                        <td><span class="badge bg-${getStatusBadge(
                          contract.statut
                        )}">${contract.statut}</span></td>
                        <td>${contract.montant} €</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="viewContract(${
                              contract.devis_id
                            })">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                `;
      });
    })
    .catch((error) => {
      console.error("Erreur lors du chargement des contrats:", error);
      document.getElementById(
        "contracts-table"
      ).innerHTML = `<tr><td colspan="6" class="text-center text-danger">Erreur lors du chargement des contrats</td></tr>`;
    });
}

// Fonction pour appliquer les filtres de contrats
function applyContractFilters(societyId) {
  const filters = {
    status: document.getElementById("statusFilter").value,
    startDate: document.getElementById("dateStartFilter").value,
    endDate: document.getElementById("dateEndFilter").value,
  };

  loadContracts(societyId, filters);
}

// Fonction pour réinitialiser les filtres de contrats
function resetContractFilters(societyId) {
  // Réinitialiser le formulaire
  document.getElementById("contractFilterForm").reset();

  // Recharger tous les contrats sans filtre
  loadContracts(societyId);
}

// Fonction pour appliquer les filtres
function applyContractFilters() {
  const status = document.getElementById("statusFilter").value;
  const startDate = document.getElementById("dateStartFilter").value;
  const endDate = document.getElementById("dateEndFilter").value;

  // Construire l'URL avec les filtres
  let url = `/api/company/getContract.php?societe_id=${societyId}`;
  if (status) url += `&statut=${status}`;
  if (startDate) url += `&date_debut=${startDate}`;
  if (endDate) url += `&date_fin=${endDate}`;

  fetch(url, {
    method: "GET",
    headers: {
      Authorization: "Bearer " + getToken(),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      // Même logique que loadAllContracts pour afficher les résultats
      const tableBody = document.getElementById("contracts-table");

      if (data.length === 0) {
        tableBody.innerHTML =
          '<tr><td colspan="6" class="text-center">Aucun contrat trouvé avec ces critères</td></tr>';
        return;
      }

      // Afficher les résultats filtrés (même code que dans loadAllContracts)
      tableBody.innerHTML = "";
      data.forEach((contract) => {
        tableBody.innerHTML += `
                  <tr>
                      <td>${contract.devis_id}</td>
                      <td>${new Date(contract.stard_date).toLocaleDateString(
                        "fr-FR"
                      )}</td>
                      <td>${new Date(contract.end_date).toLocaleDateString(
                        "fr-FR"
                      )}</td>
                      <td><span class="badge bg-${getStatusBadge(
                        contract.statut
                      )}">${contract.statut}</span></td>
                      <td>${contract.montant} €</td>
                      <td>
                          <button class="btn btn-sm btn-info" onclick="viewContractDetails(${
                            contract.devis_id
                          })">
                              <i class="fas fa-eye"></i>
                          </button>
                      </td>
                  </tr>
              `;
      });
    })
    .catch((error) => {
      console.error("Erreur lors de l'application des filtres:", error);
      document.getElementById(
        "contracts-table"
      ).innerHTML = `<tr><td colspan="6" class="text-center text-danger">Erreur lors de l'application des filtres</td></tr>`;
    });
}

// Fonction pour voir les détails d'un contrat
function viewContractDetails(id) {
  fetch(`/api/company/getContractDetails.php?contract_id=${id}`, {
    method: "GET",
    headers: {
      Authorization: "Bearer " + getToken(),
    },
  })
    .then((response) => response.json())
    .then((contract) => {
      const detailsContainer = document.getElementById("contract-details");
      detailsContainer.innerHTML = `
              <div class="row mb-3">
                  <div class="col-md-6">
                      <p><strong>ID:</strong> ${contract.devis_id}</p>
                      <p><strong>Date de début:</strong> ${new Date(
                        contract.stard_date
                      ).toLocaleDateString("fr-FR")}</p>
                      <p><strong>Date de fin:</strong> ${new Date(
                        contract.end_date
                      ).toLocaleDateString("fr-FR")}</p>
                  </div>
                  <div class="col-md-6">
                      <p><strong>Statut:</strong> <span class="badge bg-${getStatusBadge(
                        contract.statut
                      )}">${contract.statut}</span></p>
                      <p><strong>Montant:</strong> ${contract.montant} €</p>
                  </div>
              </div>
              <div class="row">
                  <div class="col-12">
                      <p><strong>Description:</strong></p>
                      <p>${
                        contract.description || "Aucune description disponible"
                      }</p>
                  </div>
              </div>
          `;

      // Stocker l'ID pour l'édition
      document.getElementById("editContract").setAttribute("data-id", id);

      // Afficher le modal
      const modal = new bootstrap.Modal(
        document.getElementById("viewContractModal")
      );
      modal.show();
    })
    .catch((error) => {
      console.error("Erreur lors du chargement des détails du contrat:", error);
      alert("Erreur lors du chargement des détails du contrat");
    });
}

// Charger les devis
function loadEstimates(societyId) {
  fetch(`/api/company/getEstimate.php?societe_id=${societyId}`, {
    method: "GET",
    headers: {
      Authorization: "Bearer " + getToken(),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      const tableBody = document.getElementById("estimates-table");

      if (data.length === 0) {
        tableBody.innerHTML =
          '<tr><td colspan="6" class="text-center">Aucun devis trouvé</td></tr>';
        return;
      }

      tableBody.innerHTML = "";
      data.forEach((estimate) => {
        tableBody.innerHTML += `
                    <tr>
                        <td>${estimate.devis_id}</td>
                        <td>${new Date(estimate.stard_date).toLocaleDateString(
                          "fr-FR"
                        )}</td>
                        <td>${new Date(estimate.end_date).toLocaleDateString(
                          "fr-FR"
                        )}</td>
                        <td><span class="badge bg-${getStatusBadge(
                          estimate.statut
                        )}">${estimate.statut}</span></td>
                        <td>${estimate.montant} €</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="viewEstimate(${
                              estimate.devis_id
                            })">
                                <i class="fas fa-eye"></i>
                            </button>
                            ${
                              estimate.statut === "En attente"
                                ? `<button class="btn btn-sm btn-success" onclick="approveEstimate(${estimate.devis_id})">
                                    <i class="fas fa-check"></i>
                                 </button>`
                                : ""
                            }
                        </td>
                    </tr>
                `;
      });
    })
    .catch((error) => {
      console.error("Erreur lors du chargement des devis:", error);
      document.getElementById(
        "estimates-table"
      ).innerHTML = `<tr><td colspan="6" class="text-center text-danger">Erreur lors du chargement des devis</td></tr>`;
    });
}

// Charger les autres frais
function loadOtherCosts(societyId) {
  fetch(`/api/company/getOtherCosts.php?societe_id=${societyId}`, {
    method: "GET",
    headers: {
      Authorization: "Bearer " + getToken(),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      const tableBody = document.getElementById("costs-table");

      if (data.length === 0) {
        tableBody.innerHTML =
          '<tr><td colspan="5" class="text-center">Aucun frais supplémentaire trouvé</td></tr>';
        return;
      }

      tableBody.innerHTML = "";
      data.forEach((cost) => {
        tableBody.innerHTML += `
                    <tr>
                        <td>${cost.other_cost_id}</td>
                        <td>${cost.name}</td>
                        <td>${cost.price} €</td>
                        <td>${cost.facture_id}</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="viewCost(${cost.other_cost_id})">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                `;
      });
    })
    .catch((error) => {
      console.error("Erreur lors du chargement des frais:", error);
      document.getElementById(
        "costs-table"
      ).innerHTML = `<tr><td colspan="5" class="text-center text-danger">Erreur lors du chargement des frais</td></tr>`;
    });
}

// Charger les collaborateurs
function loadEmployees(societyId) {
  fetch(`/api/company/getAllEmployee.php?societe_id=${societyId}`, {
    method: "GET",
    headers: {
      Authorization: "Bearer " + getToken(),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      const tableBody = document.getElementById("employees-table");

      if (data.length === 0) {
        tableBody.innerHTML =
          '<tr><td colspan="8" class="text-center">Aucun collaborateur trouvé</td></tr>';
        return;
      }

      tableBody.innerHTML = "";
      data.forEach((employee) => {
        tableBody.innerHTML += `
                    <tr>
                        <td>${employee.collaborateur_id}</td>
                        <td>${employee.nom}</td>
                        <td>${employee.prenom}</td>
                        <td>${employee.username}</td>
                        <td>${employee.role}</td>
                        <td>${employee.email}</td>
                        <td>${employee.telephone}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="editEmployee(${employee.collaborateur_id}, '${employee.nom}', '${employee.prenom}', '${employee.username}', '${employee.role}', '${employee.email}', '${employee.telephone}')">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                `;
      });
    })
    .catch((error) => {
      console.error("Erreur lors du chargement des collaborateurs:", error);
      document.getElementById(
        "employees-table"
      ).innerHTML = `<tr><td colspan="8" class="text-center text-danger">Erreur lors du chargement des collaborateurs</td></tr>`;
    });
}

// Fonction pour charger les 5 dernières factures
function loadRecentInvoices(societyId) {
  fetch(`/api/company/getInvoices.php?societe_id=${societyId}&limit=5`, {
    method: "GET",
    headers: {
      Authorization: "Bearer " + getToken(),
    },
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Erreur lors du chargement des factures");
      }
      return response.json();
    })
    .then((data) => {
      const tableBody = document.getElementById("invoices-table");
      if (data.length === 0) {
        tableBody.innerHTML =
          '<tr><td colspan="8" class="text-center">Aucune facture trouvée</td></tr>';
        return;
      }

      let html = "";
      data.forEach((invoice) => {
        const statusClass = getStatusBadge(invoice.statut);
        html += `
                <tr>
                    <td>${invoice.facture_id}</td>
                    <td>${new Date(invoice.date_emission).toLocaleDateString(
                      "fr-FR"
                    )}</td>
                    <td>${new Date(invoice.date_echeance).toLocaleDateString(
                      "fr-FR"
                    )}</td>
                    <td>${invoice.montant.toLocaleString("fr-FR", {
                      style: "currency",
                      currency: "EUR",
                    })} €</td>
                    <td>${invoice.montant_tva.toLocaleString("fr-FR", {
                      style: "currency",
                      currency: "EUR",
                    })} €</td>
                    <td>${invoice.montant_ht.toLocaleString("fr-FR", {
                      style: "currency",
                      currency: "EUR",
                    })} €</td>
                    <td><span class="badge bg-${statusClass}">${
          invoice.statut
        }</span></td>
                    <td>
                        <a href="/frontOffice/societe/facture-details.php?id=${
                          invoice.facture_id
                        }" class="btn btn-sm btn-info" title="Voir les détails">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>`;
      });
      tableBody.innerHTML = html;
    })
    .catch((error) => {
      console.error("Erreur:", error);
      document.getElementById("invoices-table").innerHTML =
        '<tr><td colspan="8" class="text-center text-danger">Erreur lors du chargement des factures</td></tr>';
    });
}

// Ajouter un employé
function addEmployee(societyId) {
  const formData = {
    nom: document.getElementById("nom").value,
    prenom: document.getElementById("prenom").value,
    username: document.getElementById("username").value,
    role: document.getElementById("role").value,
    email: document.getElementById("email").value,
    telephone: document.getElementById("telephone").value,
    password: document.getElementById("password").value,
    id_societe: societyId,
  };

  fetch("/api/employee/create.php", {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      Authorization: "Bearer " + getToken(),
    },
    body: JSON.stringify(formData),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.id) {
        // Succès
        const modal = bootstrap.Modal.getInstance(
          document.getElementById("addEmployeeModal")
        );
        modal.hide();

        // Réinitialiser le formulaire
        document.getElementById("addEmployeeForm").reset();

        // Recharger la liste des employés
        loadEmployees(societyId);

        // Afficher un message de succès
        alert("Collaborateur ajouté avec succès!");
      } else {
        // Erreur
        alert(`Erreur: ${data.message || "Une erreur est survenue"}`);
      }
    })
    .catch((error) => {
      console.error("Erreur lors de l'ajout du collaborateur:", error);
      alert("Une erreur est survenue lors de l'ajout du collaborateur");
    });
}

// Ouvrir le modal de modification d'un employé
function editEmployee(id, nom, prenom, username, role, email, telephone) {
  document.getElementById("edit_employee_id").value = id;
  document.getElementById("edit_nom").value = nom;
  document.getElementById("edit_prenom").value = prenom;
  document.getElementById("edit_username").value = username;
  document.getElementById("edit_role").value = role;
  document.getElementById("edit_email").value = email;
  document.getElementById("edit_telephone").value = telephone;
  document.getElementById("edit_password").value = "";

  const modal = new bootstrap.Modal(
    document.getElementById("editEmployeeModal")
  );
  modal.show();
}

// Mettre à jour un employé
function updateEmployee() {
  const formData = {
    id: document.getElementById("edit_employee_id").value,
    nom: document.getElementById("edit_nom").value,
    prenom: document.getElementById("edit_prenom").value,
    username: document.getElementById("edit_username").value,
    role: document.getElementById("edit_role").value,
    email: document.getElementById("edit_email").value,
    telephone: document.getElementById("edit_telephone").value,
  };

  // Ajouter le mot de passe uniquement s'il a été renseigné
  const password = document.getElementById("edit_password").value;
  if (password) {
    formData.password = password;
  }

  fetch("/api/employee/modify.php", {
    method: "PATCH",
    headers: {
      "Content-Type": "application/json",
      Authorization: "Bearer " + getToken(),
    },
    body: JSON.stringify(formData),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Succès
        const modal = bootstrap.Modal.getInstance(
          document.getElementById("editEmployeeModal")
        );
        modal.hide();

        // Recharger la liste des employés
        loadEmployees(societyId);

        // Afficher un message de succès
        alert("Collaborateur modifié avec succès!");
      } else {
        // Erreur
        alert(`Erreur: ${data.message || "Une erreur est survenue"}`);
      }
    })
    .catch((error) => {
      console.error("Erreur lors de la modification du collaborateur:", error);
      alert("Une erreur est survenue lors de la modification du collaborateur");
    });
}

// Utilitaire pour obtenir la classe de badge en fonction du statut
function getStatusBadge(status) {
  switch (status) {
    case "accepté":
    case "Payee":
      return "success";
    case "envoyé":
    case "Attente":
      return "warning";
    case "refusé":
    case "Annulé":
      return "danger";
    case "brouillon":
      return "secondary";
    default:
      return "secondary";
  }
}

// Fonctions d'affichage des détails (à implémenter si nécessaire)
function viewContract(id) {
  alert(`Affichage du contrat ${id} (fonctionnalité à implémenter)`);
}

function viewEstimate(id) {
  alert(`Affichage du devis ${id} (fonctionnalité à implémenter)`);
}

function viewCost(id) {
  alert(`Affichage du frais ${id} (fonctionnalité à implémenter)`);
}

function approveEstimate(id) {
  alert(`Approbation du devis ${id} (fonctionnalité à implémenter)`);
}

// Fonction pour charger les devis avec filtres optionnels
function loadEstimates(societyId, filters = {}) {
  // Construire l'URL avec les filtres
  let url = `/api/company/getEstimate.php?societe_id=${societyId}&is_contract=0`;

  // Ajouter les filtres s'ils sont définis
  if (filters.status) url += `&statut=${filters.status}`;
  if (filters.startDate) url += `&date_debut=${filters.startDate}`;
  if (filters.endDate) url += `&date_fin=${filters.endDate}`;

  fetch(url, {
    method: "GET",
    headers: {
      Authorization: "Bearer " + getToken(),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      const tableBody = document.getElementById("estimates-table");

      if (data.length === 0) {
        tableBody.innerHTML =
          '<tr><td colspan="8" class="text-center">Aucun devis trouvé</td></tr>';
        return;
      }

      tableBody.innerHTML = "";
      data.forEach((estimate) => {
        tableBody.innerHTML += `
          <tr>
            <td>${estimate.devis_id}</td>
            <td>${new Date(estimate.date_debut).toLocaleDateString("fr-FR")}</td>
            <td>${new Date(estimate.date_fin).toLocaleDateString("fr-FR")}</td>
            <td><span class="badge bg-${getStatusBadge(estimate.statut)}">${
          estimate.statut
        }</span></td>
            <td>${estimate.montant} €</td>
            <td>${estimate.montant_ht} €</td>
            <td>${estimate.montant_tva} €</td>
            <td>
              <button class="btn btn-sm btn-info" onclick="viewEstimateDetails(${
                estimate.devis_id
              })">
                <i class="fas fa-eye"></i>
              </button>
              ${
                estimate.statut === "envoyé"
                  ? `<button class="btn btn-sm btn-success" onclick="approveEstimate(${estimate.devis_id})">
                      <i class="fas fa-check"></i>
                     </button>
                     <button class="btn btn-sm btn-danger" onclick="rejectEstimate(${estimate.devis_id})">
                      <i class="fas fa-times"></i>
                     </button>`
                  : ""
              }
              ${
                estimate.statut === "brouillon"
                  ? `<button class="btn btn-sm btn-warning" onclick="editEstimateDetails(${estimate.devis_id})">
                      <i class="fas fa-edit"></i>
                     </button>`
                  : ""
              }
              ${
                estimate.fichier
                  ? `<a href="/uploads/devis/${estimate.fichier}" class="btn btn-sm btn-secondary" target="_blank">
                      <i class="fas fa-file-pdf"></i>
                     </a>`
                  : ""
              }
            </td>
          </tr>
        `;
      });
    })
    .catch((error) => {
      console.error("Erreur lors du chargement des devis:", error);
      document.getElementById(
        "estimates-table"
      ).innerHTML = `<tr><td colspan="8" class="text-center text-danger">Erreur lors du chargement des devis</td></tr>`;
    });
}

// Fonction pour appliquer les filtres aux devis
function applyEstimateFilters(societyId) {
  const filters = {
    status: document.getElementById("statusFilter").value,
    startDate: document.getElementById("dateStartFilter").value,
    endDate: document.getElementById("dateEndFilter").value,
  };

  loadEstimates(societyId, filters);
}

// Fonction pour réinitialiser les filtres des devis
function resetEstimateFilters(societyId) {
  // Réinitialiser le formulaire
  document.getElementById("estimateFilterForm").reset();

  // Recharger tous les devis sans filtre
  loadEstimates(societyId);
}

// Fonction pour voir les détails d'un devis
function viewEstimateDetails(id) {
  fetch(`/api/company/getEstimateDetails.php?devis_id=${id}`, {
    method: "GET",
    headers: {
      Authorization: "Bearer " + getToken(),
    },
  })
    .then((response) => response.json())
    .then((estimate) => {
      const detailsContainer = document.getElementById("estimate-details");

      let fichierLink = estimate.fichier
        ? `<a href="/uploads/devis/${estimate.fichier}" target="_blank" class="btn btn-sm btn-primary">
            <i class="fas fa-file-pdf"></i> Voir le PDF
           </a>`
        : '<span class="text-muted">Aucun fichier attaché</span>';

      detailsContainer.innerHTML = `
        <div class="row mb-3">
          <div class="col-md-6">
            <p><strong>ID:</strong> ${estimate.devis_id}</p>
            <p><strong>Date de début:</strong> ${new Date(estimate.date_debut).toLocaleDateString("fr-FR")}</p>
            <p><strong>Date de fin:</strong> ${new Date(estimate.date_fin).toLocaleDateString("fr-FR")}</p>
          </div>
          <div class="col-md-6">
            <p><strong>Statut:</strong> <span class="badge bg-${getStatusBadge(estimate.statut)}">${estimate.statut}</span></p>
            <p><strong>Montant TTC:</strong> ${estimate.montant} €</p>
            <p><strong>Montant HT:</strong> ${estimate.montant_ht} €</p>
            <p><strong>Montant TVA:</strong> ${estimate.montant_tva} €</p>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-12">
            <p><strong>Fichier:</strong> ${fichierLink}</p>
          </div>
        </div>
      `;

      // Stocker l'ID pour l'édition et la conversion
      document.getElementById("editEstimate").setAttribute("data-id", id);
      document.getElementById("convertToContract").setAttribute("data-id", id);

      // Afficher ou masquer le bouton de conversion selon le statut
      document.getElementById("convertToContract").style.display =
        estimate.statut === "accepté" ? "inline-block" : "none";

      // Afficher ou masquer le bouton d'édition selon le statut
      document.getElementById("editEstimate").style.display =
        estimate.statut === "brouillon" ? "inline-block" : "none";

      // Afficher le modal
      const modal = new bootstrap.Modal(document.getElementById("viewEstimateModal"));
      modal.show();
    })
    .catch((error) => {
      console.error("Erreur lors du chargement des détails du devis:", error);
      alert("Erreur lors du chargement des détails du devis");
    });
}

// Fonction pour ajouter un nouveau devis
function addNewEstimate() {
  // Créer un FormData pour gérer le fichier PDF
  const formData = new FormData();
  formData.append("societe_id", societyId);
  formData.append("date_debut", document.getElementById("start_date").value);
  formData.append("date_fin", document.getElementById("end_date").value);
  formData.append("montant", document.getElementById("montant").value);
  formData.append("montant_ht", document.getElementById("montant_ht").value);
  formData.append("montant_tva", document.getElementById("montant_tva").value);
  formData.append("statut", document.getElementById("statut").value);
  formData.append("is_contract", document.getElementById("is_contract").checked ? 1 : 0);

  // Ajouter le fichier s'il existe
  const fichierInput = document.getElementById("fichier");
  if (fichierInput.files.length > 0) {
    formData.append("fichier", fichierInput.files[0]);
  }

  fetch("/api/company/addEstimate.php", {
    method: "POST",
    headers: {
      Authorization: "Bearer " + getToken(),
    },
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Fermer le modal
        const modal = bootstrap.Modal.getInstance(document.getElementById("addEstimateModal"));
        modal.hide();

        // Réinitialiser le formulaire
        document.getElementById("addEstimateForm").reset();

        // Recharger les devis
        loadEstimates(societyId);

        // Afficher un message de succès
        alert("Devis ajouté avec succès!");
      } else {
        alert(`Erreur: ${data.message || "Une erreur est survenue"}`);
      }
    })
    .catch((error) => {
      console.error("Erreur lors de l'ajout du devis:", error);
      alert("Une erreur est survenue lors de l'ajout du devis");
    });
}

// Fonction pour éditer un devis
function editEstimateDetails(id) {
  fetch(`/api/company/getEstimateDetails.php?devis_id=${id}`, {
    method: "GET",
    headers: {
      Authorization: "Bearer " + getToken(),
    },
  })
    .then((response) => response.json())
    .then((estimate) => {
      // Pré-remplir le formulaire d'ajout
      document.getElementById("start_date").value = estimate.date_debut;
      document.getElementById("end_date").value = estimate.date_fin;
      document.getElementById("montant").value = estimate.montant;
      document.getElementById("montant_ht").value = estimate.montant_ht;
      document.getElementById("montant_tva").value = estimate.montant_tva;
      document.getElementById("statut").value = estimate.statut;
      document.getElementById("is_contract").checked = estimate.is_contract === 1;

      // Changer le titre et le bouton d'action du modal
      document.getElementById("addEstimateModalLabel").textContent = "Modifier le devis";
      document.getElementById("saveEstimate").textContent = "Mettre à jour";

      // Stocker l'ID pour la modification
      document.getElementById("saveEstimate").setAttribute("data-id", id);
      document.getElementById("saveEstimate").setAttribute("data-action", "edit");

      // Afficher le modal
      const modal = new bootstrap.Modal(document.getElementById("addEstimateModal"));
      modal.show();

      // Fermer le modal de détails s'il est ouvert
      const detailsModal = bootstrap.Modal.getInstance(document.getElementById("viewEstimateModal"));
      if (detailsModal) {
        detailsModal.hide();
      }
    })
    .catch((error) => {
      console.error("Erreur lors du chargement des détails du devis:", error);
      alert("Erreur lors du chargement des détails du devis");
    });
}

// Fonction pour approuver un devis
function approveEstimate(id) {
  if (confirm("Êtes-vous sûr de vouloir accepter ce devis ?")) {
    fetch(`/api/company/updateEstimateStatus.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: "Bearer " + getToken(),
      },
      body: JSON.stringify({
        devis_id: id,
        statut: "accepté"
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Recharger les devis
          loadEstimates(societyId);
          alert("Devis accepté avec succès!");
        } else {
          alert(`Erreur: ${data.message || "Une erreur est survenue"}`);
        }
      })
      .catch((error) => {
        console.error("Erreur lors de l'acceptation du devis:", error);
        alert("Une erreur est survenue lors de l'acceptation du devis");
      });
  }
}

// Fonction pour refuser un devis
function rejectEstimate(id) {
  if (confirm("Êtes-vous sûr de vouloir refuser ce devis ?")) {
    fetch(`/api/company/updateEstimateStatus.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: "Bearer " + getToken(),
      },
      body: JSON.stringify({
        devis_id: id,
        statut: "refusé"
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Recharger les devis
          loadEstimates(societyId);
          alert("Devis refusé avec succès!");
        } else {
          alert(`Erreur: ${data.message || "Une erreur est survenue"}`);
        }
      })
      .catch((error) => {
        console.error("Erreur lors du refus du devis:", error);
        alert("Une erreur est survenue lors du refus du devis");
      });
  }
}

// Fonction pour convertir un devis en contrat
function convertEstimateToContract(id) {
  if (confirm("Êtes-vous sûr de vouloir convertir ce devis en contrat ?")) {
    fetch(`/api/company/convertToContract.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: "Bearer " + getToken(),
      },
      body: JSON.stringify({
        devis_id: id
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Fermer le modal
          const modal = bootstrap.Modal.getInstance(document.getElementById("viewEstimateModal"));
          modal.hide();

          // Recharger les devis
          loadEstimates(societyId);
          alert("Devis converti en contrat avec succès!");
        } else {
          alert(`Erreur: ${data.message || "Une erreur est survenue"}`);
        }
      })
      .catch((error) => {
        console.error("Erreur lors de la conversion du devis en contrat:", error);
        alert("Une erreur est survenue lors de la conversion du devis en contrat");
      });
  }
}
