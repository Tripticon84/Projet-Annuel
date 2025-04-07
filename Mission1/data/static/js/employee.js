// Initialisation du calendrier
document.addEventListener('DOMContentLoaded', async function() {
    if (typeof collaborateurId === 'undefined' || collaborateurId === null) {
        console.error('Collaborateur ID not available');
        alert('Veuillez vous connecter pour accéder à votre planning.');
        return;
    }

    await initializeCalendar();
    await loadActivities();
    initializeFilters();
});

async function initializeCalendar() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: await getEmployeeActivities(),
        locale: 'fr'
    });

    calendar.render();
}

function getCollaborateurId() {
    // Check first for the global variable set in the page
    if (typeof collaborateurId !== 'undefined' && collaborateurId) {
        return collaborateurId;
    }

    // Try multiple sources for the ID
    const sources = {
        bodyData: document.body.dataset.collaborateurId,
        containerData: document.querySelector('.container')?.dataset?.collaborateurId,
        elementData: document.querySelector('[data-collaborateur-id]')?.dataset?.collaborateurId,
        urlParam: new URLSearchParams(window.location.search).get('collaborateur_id'),
        hiddenInput: document.querySelector('input[name="collaborateur_id"]')?.value
    };

    const id = sources.bodyData || sources.containerData || sources.elementData || sources.urlParam || sources.hiddenInput;
    
    if (!id) {
        console.error('Collaborateur ID not found. Redirecting to login...');
        window.location.href = '/login.php';
        return null;
    }
    
    return id;
}

async function getEmployeeActivities() {
    try {
        const collaborateurId = getCollaborateurId();
        
        if (!collaborateurId) {
            throw new Error('Collaborateur ID manquant - Veuillez vous connecter.');
        }

        const [activities, events] = await Promise.all([
            fetch(`/api/employee/getActivity.php?collaborateur_id=${collaborateurId}`).then(r => r.json()),
            fetch(`/api/employee/getEvent.php?collaborateur_id=${collaborateurId}`).then(r => r.json())
        ]);

        // Format the dates properly for FullCalendar
        const formattedActivities = activities.map(activity => ({
            title: activity.nom,
            type: activity.type,
            date: activity.date,
            devis: activity.is_devis,
            prestataire: activity.id_prestataire,
            lieu: activity.id_lieu
        }));

        const formattedEvents = events.map(event => ({
            title: event.nom,
            date: event.date,
            lieu: event.lieu,
            type: event.type,
            satatut: event.statut,
            id_association: event.id_association
        }));
        return [...formattedActivities, ...formattedEvents];
    } catch (error) {
        console.error('Erreur détaillée:', {
            message: error.message,
            stack: error.stack,
            collaborateurId: getCollaborateurId()
        });
        return [];
    }
}

async function loadActivities() {
    const activitiesList = document.getElementById('activities-list');
    if (!activitiesList) return;

    const activities = await getEmployeeActivities();
    activities.forEach(activity => {
        const activityElement = createActivityElement(activity);
        activitiesList.appendChild(activityElement);
    });
}

function createActivityElement(item) {
    const element = document.createElement('div');
    element.className = `list-group-item list-group-item-action activity-item ${item.type}`;
    
    const date = new Date(item.start);
    const typeLabel = item.itemType === 'activity' ? 'Activité' : 'Événement';
    
    element.innerHTML = `
        <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">${item.title}</h5>
            <small>${date.toLocaleDateString()}</small>
        </div>
        <p class="mb-1">
            <span class="badge bg-${item.itemType === 'activity' ? 'primary' : 'success'}">${typeLabel}</span>
            ${date.toLocaleTimeString()}
        </p>
    `;
    return element;
}

function initializeFilters() {
    const filters = document.querySelectorAll('.filter-activity');
    filters.forEach(filter => {
        filter.addEventListener('change', function() {
            updateActivityVisibility();
        });
    });
}

function updateActivityVisibility() {
    const selectedTypes = Array.from(document.querySelectorAll('.filter-activity:checked'))
        .map(checkbox => checkbox.value);
    
    document.querySelectorAll('.activity-item').forEach(item => {
        const type = Array.from(item.classList)
            .find(className => ['webinar', 'conference', 'workshop', 'medical', 'sport'].includes(className));
        
        item.style.display = selectedTypes.includes(type) ? 'block' : 'none';
    });
}

async function registerForService(type, id) {
    try {
        const collaborateurId = getCollaborateurId();
        
        // Use the correct field name based on type
        const requestData = {
            type,
            collaborateur_id: collaborateurId
        };
        
        // Add the correct ID field based on type
        if (type === 'event') {
            requestData.id_evenement = id;
        } else if (type === 'activity') {
            requestData.id_activite = id;
        }

        const response = await fetch('/api/employee/register.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(requestData)
        });

        const data = await response.json();
        if (!response.ok) throw new Error(data.message);
        
        // Rafraîchir le calendrier si on est sur la page calendrier
        const calendar = document.querySelector('#calendar');
        if (calendar) {
            await initializeCalendar();
        }
        
        return data;
    } catch (error) {
        console.error('Erreur d\'inscription:', error);
        throw error;
    }
}
