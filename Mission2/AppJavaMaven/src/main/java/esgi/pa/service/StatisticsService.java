package esgi.pa.service;

import esgi.pa.model.*;

import java.time.temporal.ChronoUnit;
import java.util.*;
import java.util.stream.Collectors;

public class StatisticsService {
    private List<CompteClient> clients;

    // Constructeur
    public StatisticsService(List<CompteClient> clients) {
        this.clients = clients;
    }

    // Génération de statistiques
    public Map<String, Object> generateAllStatistics() {
        Map<String, Object> statistics = new HashMap<>();

        // Statistiques générales
        statistics.put("clientCount", clients.size());
        statistics.put("totalAbonnements", clients.stream().mapToInt(c -> c.getAbonnements().size()).sum());
        statistics.put("totalDevis", clients.stream().mapToInt(c -> c.getDevis().size()).sum());
        statistics.put("totalFactures", clients.stream().mapToInt(c -> c.getFactures().size()).sum());

        // Statistiques financières
        DoubleSummaryStatistics abonnementStats = clients.stream()
                .flatMap(c -> c.getAbonnements().stream())
                .collect(Collectors.summarizingDouble(Document::getMontant));

        DoubleSummaryStatistics devisStats = clients.stream()
                .flatMap(c -> c.getDevis().stream())
                .collect(Collectors.summarizingDouble(Document::getMontant));

        DoubleSummaryStatistics factureStats = clients.stream()
                .flatMap(c -> c.getFactures().stream())
                .collect(Collectors.summarizingDouble(Document::getMontant));

        statistics.put("totalAbonnementValue", abonnementStats.getSum());
        statistics.put("avgAbonnementValue", abonnementStats.getAverage());
        statistics.put("totalDevisValue", devisStats.getSum());
        statistics.put("avgDevisValue", devisStats.getAverage());
        statistics.put("totalFactureValue", factureStats.getSum());
        statistics.put("avgFactureValue", factureStats.getAverage());

        // Distribution par type de client
        Map<String, Long> clientTypeDistribution = clients.stream()
                .collect(Collectors.groupingBy(CompteClient::getType, Collectors.counting()));
        statistics.put("clientTypeDistribution", clientTypeDistribution);

        // Statistiques avancées
        statistics.put("avgAbonnementDuration", calculateAvgAbonnementDuration());
        statistics.put("conversionRateDevisToFacture", calculateConversionRate());

        return statistics;
    }

    // Durée moyenne des abonnements
    private double calculateAvgAbonnementDuration() {
        return clients.stream()
                .flatMap(c -> c.getAbonnements().stream())
                .mapToLong(a -> ChronoUnit.DAYS.between(a.getDateDebut(), a.getDateFin()))
                .average()
                .orElse(0.0);
    }

    // Taux de conversion devis/facture
    private double calculateConversionRate() {
        int totalDevis = clients.stream().mapToInt(c -> c.getDevis().size()).sum();
        if (totalDevis == 0) return 0.0;

        int totalFactures = clients.stream().mapToInt(c -> c.getFactures().size()).sum();
        return (double) totalFactures / totalDevis;
    }

    // Recherche de clients
    public List<CompteClient> searchClients(String keyword) {
        if (keyword == null || keyword.trim().isEmpty()) {
            return new ArrayList<>(clients);
        }

        String searchTerm = keyword.toLowerCase().trim();
        return clients.stream()
                .filter(client -> client.getNom().toLowerCase().contains(searchTerm) ||
                        client.getType().toLowerCase().contains(searchTerm))
                .collect(Collectors.toList());
    }

    // Statistiques par client
    public Map<String, Double> getClientValueStatistics() {
        Map<String, Double> valueStats = new HashMap<>();

        // Total revenue per client
        clients.forEach(client -> {
            double totalAbonnements = client.getAbonnements().stream()
                    .mapToDouble(Document::getMontant).sum();
            double totalFactures = client.getFactures().stream()
                    .mapToDouble(Document::getMontant).sum();

            valueStats.put(client.getNom() + "_abonnements", totalAbonnements);
            valueStats.put(client.getNom() + "_factures", totalFactures);
            valueStats.put(client.getNom() + "_total", totalAbonnements + totalFactures);
        });

        return valueStats;
    }

    // evenement statistics

    private List<Evenement> evenements;

    public void setEvenements(List<Evenement> evenements) {
        this.evenements = evenements;
    }

    public Map<String, Object> generateEvenementsStatistics() {
        Map<String, Object> statistics = new HashMap<>();

        // Nombre total d'événements
        statistics.put("totalEvenements", evenements.size());

        // Distribution par type d'événement
        Map<String, Long> typeDistribution = evenements.stream()
                .collect(Collectors.groupingBy(Evenement::getType, Collectors.counting()));
        statistics.put("typeEvenementDistribution", typeDistribution);

        // Moyenne de participants par événement
        double avgParticipants = evenements.stream()
                .mapToInt(Evenement::getNombreParticipants)
                .average()
                .orElse(0);
        statistics.put("avgParticipants", avgParticipants);

        // Fréquence par mois
        Map<String, Long> frequenceParMois = evenements.stream()
                .collect(Collectors.groupingBy(
                        e -> e.getDateDebut().getMonth().toString(),
                        Collectors.counting()
                ));
        statistics.put("frequenceParMois", frequenceParMois);

        // Top 5 des événements les plus demandés (par nombre de participants)
        List<Evenement> topEvenements = evenements.stream()
                .sorted(Comparator.comparingInt(Evenement::getNombreParticipants).reversed())
                .limit(5)
                .collect(Collectors.toList());
        statistics.put("topEvenements", topEvenements);

        // Coût moyen des événements
        double avgCout = evenements.stream()
                .mapToDouble(Evenement::getCout)
                .average()
                .orElse(0);
        statistics.put("avgCout", avgCout);

        return statistics;
    }
}