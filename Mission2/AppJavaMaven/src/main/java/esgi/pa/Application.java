package esgi.pa;

import esgi.pa.model.CompteClient;
import esgi.pa.model.Evenement;

import esgi.pa.service.DataGenerator;
import esgi.pa.service.ReportService;
import esgi.pa.service.StatisticsService;

import java.util.List;
import java.util.Map;
import java.util.Scanner;

public class Application {
    private static DataGenerator dataGenerator = new DataGenerator();
    private static List<CompteClient> clients;
    private static List<Evenement> evenements;
    private static StatisticsService statisticsService;

    public static void main(String[] args) {
        System.out.println("Génération des données...");
        clients = dataGenerator.generateClients(50);
        evenements = dataGenerator.generateEvenements(30);
        statisticsService = new StatisticsService(clients);
        statisticsService.setEvenements(evenements);

        Scanner scanner = new Scanner(System.in);
        boolean running = true;

        while (running) {
            displayMenu();
            int choice = getUserChoice(scanner);

            switch (choice) {
                case 1:
                    displayAllStatistics();
                    break;
                case 2:
                    displayClientList();
                    break;
                case 3:
                    displayClientDetails(scanner);
                    break;
                case 4:
                    displayClientTypeDistribution();
                    break;
                case 5:
                    displayEventList();
                    break;
                case 6:
                    displayEventDetails(scanner);
                    break;
                case 7:
                    generatePdfReport();
                    break;
                case 0:
                    running = false;
                    break;
                default:
                    System.out.println("Option invalide. Veuillez réessayer.");
            }
        }

        scanner.close();
        System.out.println("Application terminée.");
    }

    private static void displayMenu() {
        System.out.println("\n===== MENU PRINCIPAL =====");
        System.out.println("1. Afficher toutes les statistiques");
        System.out.println("2. Afficher la liste des clients");
        System.out.println("3. Afficher les détails d'un client");
        System.out.println("4. Afficher la distribution des types de clients");
        System.out.println("5. Afficher la liste des événements");
        System.out.println("6. Afficher les détails d'un événement");
        System.out.println("7. Générer un rapport PDF");
        System.out.println("0. Quitter");
        System.out.print("Votre choix: ");
    }

    private static int getUserChoice(Scanner scanner) {
        try {
            return Integer.parseInt(scanner.nextLine());
        } catch (NumberFormatException e) {
            return -1;
        }
    }

    private static void displayAllStatistics() {
        System.out.println("\n===== STATISTIQUES GÉNÉRALES =====");
        Map<String, Object> stats = statisticsService.generateAllStatistics();

        System.out.println("Nombre de clients: " + stats.get("clientCount"));
        System.out.println("Nombre total d'abonnements: " + stats.get("totalAbonnements"));
        System.out.println("Nombre total de devis: " + stats.get("totalDevis"));
        System.out.println("Nombre total de factures: " + stats.get("totalFactures"));

        System.out.println("\n----- Statistiques financières -----");
        System.out.printf("Valeur totale des abonnements: %.2f€\n", stats.get("totalAbonnementValue"));
        System.out.printf("Valeur moyenne des abonnements: %.2f€\n", stats.get("avgAbonnementValue"));
        System.out.printf("Valeur totale des devis: %.2f€\n", stats.get("totalDevisValue"));
        System.out.printf("Valeur moyenne des devis: %.2f€\n", stats.get("avgDevisValue"));
        System.out.printf("Valeur totale des factures: %.2f€\n", stats.get("totalFactureValue"));
        System.out.printf("Valeur moyenne des factures: %.2f€\n", stats.get("avgFactureValue"));

        System.out.println("\n----- Statistiques avancées -----");
        System.out.printf("Durée moyenne des abonnements: %.1f jours\n", stats.get("avgAbonnementDuration"));
        System.out.printf("Taux de conversion devis/facture: %.2f%%\n",
                ((Double)stats.get("conversionRateDevisToFacture")) * 100);

        // Ajout des statistiques d'événements si disponibles
        if (evenements != null && !evenements.isEmpty()) {
            Map<String, Object> eventStats = statisticsService.generateEvenementsStatistics();
            System.out.println("\n----- Statistiques des événements -----");
            System.out.println("Nombre total d'événements: " + eventStats.get("totalEvenements"));
            System.out.printf("Moyenne de participants par événement: %.1f\n", eventStats.get("avgParticipants"));
            System.out.printf("Coût moyen des événements: %.2f€\n", eventStats.get("avgCout"));
        }
    }

    private static void displayClientList() {
        System.out.println("\n===== LISTE DES CLIENTS =====");
        System.out.printf("%-5s %-30s %-20s %-15s\n", "ID", "Nom", "Type", "Chiffre d'affaires");
        System.out.println("-".repeat(70));

        for (int i = 0; i < clients.size(); i++) {
            CompteClient client = clients.get(i);
            System.out.printf("%-5d %-30s %-20s %.2f€\n",
                    i+1, client.getNom(), client.getType(), client.getChiffreAffaires());
        }
    }

    private static void displayClientDetails(Scanner scanner) {
        displayClientList();
        System.out.print("\nEntrez l'ID du client à afficher: ");
        int id = getUserChoice(scanner);

        if (id <= 0 || id > clients.size()) {
            System.out.println("ID client invalide.");
            return;
        }

        CompteClient client = clients.get(id-1);
        System.out.println("\n===== DÉTAILS DU CLIENT =====");
        System.out.println("Nom: " + client.getNom());
        System.out.println("Type: " + client.getType());
        System.out.printf("Chiffre d'affaires: %.2f€\n", client.getChiffreAffaires());

        System.out.println("\nAbonnements (" + client.getAbonnements().size() + "):");
        client.getAbonnements().forEach(a ->
                System.out.printf("  - %s, %.2f€ (du %s au %s)\n",
                        a.getDescription(), a.getMontant(), a.getDateDebut(), a.getDateFin()));

        System.out.println("\nDevis (" + client.getDevis().size() + "):");
        client.getDevis().forEach(d ->
                System.out.printf("  - %s, %.2f€ (%s)\n",
                        d.getDescription(), d.getMontant(), d.getDate()));

        System.out.println("\nFactures (" + client.getFactures().size() + "):");
        client.getFactures().forEach(f ->
                System.out.printf("  - %s (N°%s), %.2f€ (%s)\n",
                        f.getDescription(), f.getNumero(), f.getMontant(), f.getDate()));
    }

    private static void displayClientTypeDistribution() {
        System.out.println("\n===== DISTRIBUTION DES TYPES DE CLIENTS =====");
        Map<String, Object> stats = statisticsService.generateAllStatistics();

        @SuppressWarnings("unchecked")
        Map<String, Long> distribution = (Map<String, Long>) stats.get("clientTypeDistribution");

        distribution.forEach((type, count) -> {
            double percentage = (count * 100.0) / clients.size();
            System.out.printf("%-30s : %3d clients (%.1f%%)\n", type, count, percentage);
        });
    }

    private static void displayEventList() {
        System.out.println("\n===== LISTE DES ÉVÉNEMENTS =====");
        System.out.printf("%-5s %-30s %-20s %-15s %-15s\n", "ID", "Nom", "Type", "Date début", "Participants");
        System.out.println("-".repeat(85));

        for (int i = 0; i < evenements.size(); i++) {
            Evenement event = evenements.get(i);
            System.out.printf("%-5d %-30s %-20s %-15s %-15d\n",
                    i+1, event.getNom(), event.getType(), event.getDateDebut(), event.getNombreParticipants());
        }
    }

    private static void displayEventDetails(Scanner scanner) {
        displayEventList();
        System.out.print("\nEntrez l'ID de l'événement à afficher: ");
        int id = getUserChoice(scanner);

        if (id <= 0 || id > evenements.size()) {
            System.out.println("ID événement invalide.");
            return;
        }

        Evenement event = evenements.get(id-1);
        System.out.println("\n===== DÉTAILS DE L'ÉVÉNEMENT =====");
        System.out.println("Nom: " + event.getNom());
        System.out.println("Type: " + event.getType());
        System.out.println("Description: " + event.getDescription());
        System.out.println("Date début: " + event.getDateDebut());
        System.out.println("Date fin: " + event.getDateFin());
        System.out.println("Nombre de participants: " + event.getNombreParticipants());
        System.out.printf("Coût total: %.2f€\n", event.getCout());

        System.out.println("\nRéservations (" + event.getReservations().size() + "):");
        event.getReservations().forEach(r ->
                System.out.printf("  - %s, %.2f€ (le %s, %d heures)\n",
                        r.getLieu(), r.getMontant(), r.getDate(), r.getDureeHeures()));

        System.out.println("\nPrestations (" + event.getPrestations().size() + "):");
        event.getPrestations().forEach(p ->
                System.out.printf("  - %s (%s), %.2f€\n",
                        p.getNom(), p.getType(), p.getCout()));
    }

    private static void generatePdfReport() {
        System.out.println("\nGénération du rapport PDF...");
        ReportService reportService = new ReportService(clients, evenements, statisticsService);

        try {
            String outputPath = "rapport_statistiques.pdf";
            reportService.generateStatisticsReport(outputPath);
            System.out.println("Rapport PDF généré avec succès: " + outputPath);
        } catch (Exception e) {
            System.err.println("Erreur lors de la génération du rapport: " + e.getMessage());
        }
    }
}