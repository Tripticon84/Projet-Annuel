package esgi.pa.service;

import esgi.pa.model.*;

import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;
import java.util.Random;

public class DataGenerator {
    private final Random random = new Random();

    private final String[] clientTypes = {
            "PME", "Grand Compte", "Particulier", "Gouvernement",
            "Auto-entrepreneur", "TPE", "Association", "Organisme public",
            "Multinationale", "Start-up", "Établissement scolaire",
            "Collectivité locale", "Commerçant", "Artisan", "Profession libérale",
            "ONG", "Fondation", "Centre de recherche", "Entreprise sociale",
            "Coopérative", "Industrie", "Commerce de détail", "Restaurant",
            "Hôtel", "Cabinet conseil", "Société de services", "Clinique médicale",
            "Établissement culturel", "Institution financière", "Organisation internationale"
    };

    private final String[] abonnementTypes = {"Basic", "Premium", "Enterprise", "Ultimate"};
    private final String[] devisDescriptions = {"Prestation service", "Installation", "Maintenance", "Formation"};
    private final String[] factureDescriptions = {"Service mensuel", "Produits", "Consultation", "Support"};

    public List<CompteClient> generateClients(int count) {
        List<CompteClient> clients = new ArrayList<>();

        for (int i = 0; i < count; i++) {
            CompteClient client = generateClient("Client " + (i + 1));
            generateDocumentsForClient(client);
            clients.add(client);
        }

        return clients;
    }

    private CompteClient generateClient(String nom) {
        String type = clientTypes[random.nextInt(clientTypes.length)];
        double chiffreAffaires = 10000 + random.nextDouble() * 990000; // Entre 10K et 1M
        return new CompteClient(nom, type, chiffreAffaires);
    }

    private void generateDocumentsForClient(CompteClient client) {
        // Générer entre 1 et 5 abonnements
        int abonnementCount = 1 + random.nextInt(5);
        for (int i = 0; i < abonnementCount; i++) {
            client.addAbonnement(generateAbonnement());
        }

        // Générer entre 2 et 10 devis
        int devisCount = 2 + random.nextInt(9);
        for (int i = 0; i < devisCount; i++) {
            client.addDevis(generateDevis());
        }

        // Générer entre 3 et 15 factures
        int factureCount = 3 + random.nextInt(13);
        for (int i = 0; i < factureCount; i++) {
            client.addFacture(generateFacture());
        }
    }

    private Abonnement generateAbonnement() {
        String type = abonnementTypes[random.nextInt(abonnementTypes.length)];
        double montant = 50 + random.nextDouble() * 950; // Entre 50 et 1000
        LocalDate dateDebut = LocalDate.now().minusMonths(random.nextInt(12));
        LocalDate dateFin = dateDebut.plusMonths(3 + random.nextInt(21)); // Entre 3 et 24 mois
        return new Abonnement(type, montant, dateDebut, dateFin);
    }

    private Devis generateDevis() {
        String description = devisDescriptions[random.nextInt(devisDescriptions.length)];
        double montant = 100 + random.nextDouble() * 4900; // Entre 100 et 5000
        LocalDate date = LocalDate.now().minusMonths(random.nextInt(6));
        return new Devis(description, montant, date);
    }

    private Facture generateFacture() {
        String description = factureDescriptions[random.nextInt(factureDescriptions.length)];
        double montant = 50 + random.nextDouble() * 2950; // Entre 50 et 3000
        LocalDate date = LocalDate.now().minusMonths(random.nextInt(12));
        String numero = "F" + (10000 + random.nextInt(90000)); // F suivi de 5 chiffres
        return new Facture(description, montant, date, numero);
    }

    //*********************partie evenement******************************


    public List<Evenement> generateEvenements(int count) {
        List<Evenement> evenements = new ArrayList<>();
        List<Prestation> prestations = generatePrestations(20);

        String[] typesEvenements = {"Séminaire", "Conférence", "Formation", "Réunion", "Exposition",
                "Cocktail", "Soirée d'entreprise", "Workshop", "Lancement de produit", "Webinaire"};
        String[] descriptions = {"Événement annuel", "Session trimestrielle", "Rencontre mensuelle",
                "Présentation client", "Formation interne", "Session de networking"};
        String[] lieux = {"Paris", "Lyon", "Marseille", "Bordeaux", "Lille", "Toulouse", "Nantes", "Strasbourg"};

        for (int i = 0; i < count; i++) {
            // Génération de dates aléatoires
            LocalDate dateDebut = generateRandomDate(2023, 2024);
            LocalDate dateFin = dateDebut.plusDays(random.nextInt(5) + 1);

            // Création de l'événement
            Evenement evenement = new Evenement(
                    "Événement " + (i + 1),
                    typesEvenements[random.nextInt(typesEvenements.length)],
                    descriptions[random.nextInt(descriptions.length)],
                    dateDebut,
                    dateFin,
                    50 + random.nextInt(200),
                    1000 + random.nextDouble() * 9000
            );

            // Ajout de réservations
            int nbReservations = 1 + random.nextInt(3);
            for (int j = 0; j < nbReservations; j++) {
                Reservation reservation = new Reservation(
                        lieux[random.nextInt(lieux.length)],
                        dateDebut.minusDays(random.nextInt(30) + 1),
                        4 + random.nextInt(8),
                        500 + random.nextDouble() * 2000
                );
                evenement.addReservation(reservation);
            }

            // Ajout de prestations
            int nbPrestations = 1 + random.nextInt(5);
            for (int j = 0; j < nbPrestations; j++) {
                Prestation prestation = prestations.get(random.nextInt(prestations.size()));
                prestation.incrementUtilisations();
                evenement.addPrestation(prestation);
            }

            evenements.add(evenement);
        }

        return evenements;
    }

    public List<Prestation> generatePrestations(int count) {
        List<Prestation> prestations = new ArrayList<>();

        String[] typesPrestations = {"Salle", "Matériel audio", "Matériel vidéo", "Personnel", "Restauration", "Transport"};
        String[] nomsSalles = {"Salle Diamant", "Salle Ruby", "Salle Saphir", "Salle Émeraude", "Amphithéâtre"};
        String[] nomsMateriels = {"Vidéoprojecteur HD", "Système son premium", "Micro sans fil", "Écran tactile", "Caméra 4K"};
        String[] nomsPersonnel = {"Technicien", "Hôtesse d'accueil", "Traiteur", "Photographe", "Animateur"};

        for (int i = 0; i < count; i++) {
            String type = typesPrestations[random.nextInt(typesPrestations.length)];
            String nom;
            String description;

            switch (type) {
                case "Salle":
                    nom = nomsSalles[random.nextInt(nomsSalles.length)];
                    description = "Capacité: " + (20 + random.nextInt(100)) + " personnes";
                    break;
                case "Matériel audio":
                case "Matériel vidéo":
                    nom = nomsMateriels[random.nextInt(nomsMateriels.length)];
                    description = "Haute qualité professionnelle";
                    break;
                case "Personnel":
                    nom = nomsPersonnel[random.nextInt(nomsPersonnel.length)];
                    description = "Professionnel expérimenté";
                    break;
                default:
                    nom = "Prestation " + (i + 1);
                    description = "Description standard";
            }

            Prestation prestation = new Prestation(
                    nom,
                    type,
                    description,
                    100 + random.nextDouble() * 1000
            );

            prestations.add(prestation);
        }

        return prestations;
    }

    private LocalDate generateRandomDate(int startYear, int endYear) {
        int year = startYear + random.nextInt(endYear - startYear + 1);
        int month = 1 + random.nextInt(12);
        int day = 1 + random.nextInt(28);
        return LocalDate.of(year, month, day);
    }
}