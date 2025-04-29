<?php

// Inclure l'en-tête
require_once 'includes/head.php';
require_once 'includes/header.php';

if (!isset($_SESSION['collaborateur_id'])) {
    header('Location: /login.php');
    exit;
}
?>

<div class="container mt-4" data-collaborateur-id="<?php echo $_SESSION['collaborateur_id']; ?>">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h1 class="card-title">Catalogue de services</h1>
                    <p class="card-text">Découvrez les prestations disponibles et réservez directement en ligne.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Liste des services -->
        <div class="col-md-12">
            <div class="row">
                <div class="col-12 mb-3">
                    <input type="text" class="form-control" id="searchInput" placeholder="Rechercher une activité ou un événement...">
                </div>
            </div>
            <div class="row row-cols-1 row-cols-md-3 g-4" id="services-grid">
                <div id="loading" class="col-12">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Ajouter avant le reste du code JavaScript
const collaborateurId = document.querySelector('[data-collaborateur-id]')?.dataset?.collaborateurId;
if (!collaborateurId) {
    window.location.href = '/login.php';
}

document.addEventListener('DOMContentLoaded', function() {
    loadAvailableServices();

    // Ajout de l'event listener pour la recherche
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', filterServices);
});

// Chargement des services
async function loadAvailableServices() {
    try {
        const loadingElement = document.getElementById('loading');
        if (loadingElement) loadingElement.style.display = 'block';

        const [activitiesResponse, eventsResponse, registrationsResponse] = await Promise.all([
            fetch('/api/activity/getAll.php'),
            fetch('/api/event/getAll.php'),
            fetch(`/api/employee/registrations.php?collaborateur_id=${collaborateurId}`)
        ]);

        // Log les réponses brutes
        console.log('Activities Response:', await activitiesResponse.clone().text());
        console.log('Events Response:', await eventsResponse.clone().text());
        console.log('Registrations Response:', await registrationsResponse.clone().text());

        // Vérifier chaque réponse
        if (!activitiesResponse.ok) throw new Error('Erreur lors du chargement des activités');
        if (!eventsResponse.ok) throw new Error('Erreur lors du chargement des événements');
        if (!registrationsResponse.ok) throw new Error('Erreur lors du chargement des inscriptions');

        const [activities, events, registrations] = await Promise.all([
            activitiesResponse.json(),
            eventsResponse.json(),
            registrationsResponse.json()
        ]);

        // Filtrer uniquement les services à venir
        const now = new Date();
        const upcomingActivities = activities.filter(activity => new Date(activity.date) >= now);
        const upcomingEvents = events.filter(event => new Date(event.date) >= now);

        window.allServices = [
            ...formatActivities(upcomingActivities, registrations),
            ...formatEvents(upcomingEvents, registrations)
        ];

        // Log les services formatés
        console.log('Formatted Services:', window.allServices);

        if (loadingElement) loadingElement.style.display = 'none';
        
        // Vérifier si des services sont disponibles
        if (window.allServices.length === 0) {
            console.warn('Aucun service n\'a été chargé');
        }
        
        displayServices(window.allServices);
    } catch (error) {
        console.error('Erreur détaillée:', error);
        showErrorMessage(error.message);
        if (document.getElementById('loading')) {
            document.getElementById('loading').style.display = 'none';
        }
    }
}

// Formatage des données
function formatActivities(activities, registrations) {
    if (!Array.isArray(activities) || !Array.isArray(registrations)) {
        console.error('Format invalide:', { activities, registrations });
        return [];
    }
    
    return activities.map(activity => {
        console.log('Formatting activity:', activity);
        // Debug les inscriptions pour cette activité
        console.log('Checking registrations for activity:', activity.id, registrations.filter(r => 
            r.type === 'activite' && parseInt(r.service_id) === parseInt(activity.id)
        ));
        
        const isRegistered = registrations.some(r => 
            r.type === 'activite' && 
            parseInt(r.service_id) === parseInt(activity.id)
        );
        
        console.log(`Activity ${activity.id} isRegistered:`, isRegistered); // Debug
        
        return {
            ...activity,
            id: parseInt(activity.id),
            serviceType: 'activite',
            displayType: getDisplayType(activity.type),
            formattedDate: new Date(activity.date).toLocaleDateString('fr-FR'),
            isRegistered: isRegistered
        };
    });
}

function formatEvents(events, registrations) {
    if (!Array.isArray(events) || !Array.isArray(registrations)) {
        console.error('Format invalide:', { events, registrations });
        return [];
    }
    
    return events.map(event => {
        console.log('Formatting event:', event);
        const isRegistered = registrations.some(r => 
            r.type === 'event' && 
            parseInt(r.service_id) === parseInt(event.evenement_id)
        );
        
        console.log(`Event ${event.evenement_id} isRegistered:`, isRegistered); // Debug
        
        return {
            id: parseInt(event.evenement_id),
            nom: event.nom,
            type: event.type,
            date: event.date,
            lieu: event.lieu,
            serviceType: 'event',
            displayType: getDisplayType(event.type),
            formattedDate: new Date(event.date).toLocaleDateString('fr-FR'),
            isRegistered: isRegistered
        };
    });
}

function getDisplayType(type) {
    const typeMapping = {
        'webinar': 'Webinar',
        'conference': 'Conference',
        'workshop': 'Workshop',
        'medical': 'Medical',
        'sport': 'Sport'
    };
    return typeMapping[type.toLowerCase()] || type;
}

// Affichage des services modifié
function displayServices(services) {
    const servicesGrid = document.getElementById('services-grid');
    document.getElementById('loading')?.remove();

    if (services.length === 0) {
        servicesGrid.innerHTML = '<div class="col-12"><div class="alert alert-info">Aucun service disponible</div></div>';
        return;
    }

    servicesGrid.innerHTML = services.map(service => `
        <div class="col">
            <div class="card h-100 ${service.isRegistered ? 'border-success' : ''}">
                <div class="card-body">
                    <h5 class="card-title">${service.nom}</h5>
                    <p class="card-text">
                        <span class="badge bg-${service.serviceType === 'event' ? 'success' : 'primary'}">
                            ${service.displayType}
                        </span>
                        <br>
                        <small class="text-muted">Date: ${service.formattedDate}</small>
                    </p>
                    ${service.isRegistered ? `
                        <button class="btn btn-danger" 
                                onclick="unregisterFrom('${service.serviceType}', ${service.id})">
                            Se désinscrire
                        </button>
                    ` : `
                        <button class="btn btn-primary" 
                                onclick="registerFor('${service.serviceType}', ${service.id})">
                            S'inscrire
                        </button>
                    `}
                </div>
            </div>
        </div>
    `).join('');
}

// Fonction de filtrage des services simplifiée
function filterServices(event) {
    const searchTerm = event.target.value.toLowerCase();
    let filteredServices = window.allServices.filter(service => 
        service.nom.toLowerCase().includes(searchTerm) || 
        service.displayType.toLowerCase().includes(searchTerm)
    );
    displayServices(filteredServices);
}

// Inscription aux services
async function registerFor(type, id) {
    try {
        if (!confirm('Voulez-vous vraiment vous inscrire à cet événement ?')) {
            return;
        }

        const collaborateurId = document.querySelector('[data-collaborateur-id]')?.dataset?.collaborateurId;
        if (!collaborateurId) {
            alert('Veuillez vous connecter pour vous inscrire.');
            return;
        }

        // Construct request data with the correct field names
        const requestData = {
            type: type,
            collaborateur_id: collaborateurId
        };

        // Add the appropriate ID field based on type
        if (type === 'event') {
            requestData.id_evenement = id;
        } else if (type === 'activite') {
            requestData.id_activite = id;
        }

        // Log pour déboguer
        console.log('Sending request with data:', requestData);

        const response = await fetch('/api/employee/register.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(requestData)
        });

        // Log la réponse pour le débogage
        const responseText = await response.clone().text();
        console.log('Register Response:', responseText);

        let data;
        try {
            data = JSON.parse(responseText);
        } catch (e) {
            throw new Error('Réponse invalide du serveur');
        }
        
        if (!response.ok) {
            throw new Error(data.error || data.message || 'Erreur lors de l\'inscription');
        }

        showSuccessMessage('Inscription réussie !');
        await loadAvailableServices();
    } catch (error) {
        showErrorMessage('Erreur : ' + error.message);
        console.error('Erreur détaillée:', error);
    }
}

// Désinscription des services
async function unregisterFrom(type, id) {
    try {
        if (!confirm('Voulez-vous vraiment vous désinscrire de cet événement ?')) {
            return;
        }

        const collaborateurId = document.querySelector('[data-collaborateur-id]')?.dataset?.collaborateurId;
        if (!collaborateurId) {
            alert('Veuillez vous connecter pour vous désinscrire.');
            return;
        }

        // Construct request data with the correct field names
        const requestData = {
            type: type,
            collaborateur_id: collaborateurId
        };

        // Add the appropriate ID field based on type
        if (type === 'event') {
            requestData.id_evenement = id;
        } else if (type === 'activite') {
            requestData.id_activite = id;
        }

        // Log pour déboguer
        console.log('Sending request with data:', requestData);

        const response = await fetch('/api/employee/unregister.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(requestData)
        });

        // Log la réponse pour le débogage
        const responseText = await response.clone().text();
        console.log('Unregister Response:', responseText);

        let data;
        try {
            data = JSON.parse(responseText);
        } catch (e) {
            throw new Error('Réponse invalide du serveur');
        }
        
        if (!response.ok) {
            throw new Error(data.error || data.message || 'Erreur lors de la désinscription');
        }

        showSuccessMessage('Désinscription réussie !');
        await loadAvailableServices();
    } catch (error) {
        showErrorMessage('Erreur : ' + error.message);
        console.error('Erreur détaillée:', error);
    }
}

function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.querySelector('.container').insertAdjacentElement('afterbegin', alertDiv);
}

function showErrorMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.querySelector('.container').insertAdjacentElement('afterbegin', alertDiv);
}
</script>

<?php include 'includes/footer.php'; ?>
