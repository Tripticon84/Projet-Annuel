package esgi.pa.service;

import esgi.pa.model.CompteClient;
import esgi.pa.model.Evenement;
import esgi.pa.model.Prestation;
import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.pdmodel.PDPage;
import org.apache.pdfbox.pdmodel.PDPageContentStream;
import org.apache.pdfbox.pdmodel.common.PDRectangle;
import org.apache.pdfbox.pdmodel.font.PDType1Font;
import org.apache.pdfbox.pdmodel.graphics.image.PDImageXObject;
import org.jfree.chart.ChartFactory;
import org.jfree.chart.ChartUtils;
import org.jfree.chart.JFreeChart;
import org.jfree.chart.plot.PlotOrientation;
import org.jfree.data.category.DefaultCategoryDataset;
import org.jfree.data.general.DefaultPieDataset;
import org.jfree.chart.axis.CategoryAxis;
import org.jfree.chart.axis.CategoryLabelPositions;
import org.jfree.chart.plot.CategoryPlot;
import org.jfree.chart.ui.RectangleInsets;



import java.awt.*;
import java.io.File;
import java.io.IOException;
import java.util.*;
import java.util.List;
import java.util.stream.Collectors;
import java.awt.Color;
import java.awt.Font;

public class ReportService {
    private final List<CompteClient> clients;
    private final List<Evenement> evenements;
    private final StatisticsService statisticsService;

    public ReportService(List<CompteClient> clients, List<Evenement> evenements, StatisticsService statisticsService) {
        this.clients = clients;
        this.evenements = evenements;
        this.statisticsService = statisticsService;
    }

    public void generateStatisticsReport(String outputPath) throws IOException {
        try (PDDocument document = new PDDocument()) {
            // Page 1: Statistiques clients
            PDPage clientPage = new PDPage(PDRectangle.A4);
            document.addPage(clientPage);
            generateClientStatisticsPage(document, clientPage);

            // Page 2: Statistiques événements
            PDPage eventPage = new PDPage(PDRectangle.A4);
            document.addPage(eventPage);
            generateEventStatisticsPage(document, eventPage);

            // Page 3: Statistiques prestations
            PDPage prestationPage = new PDPage(PDRectangle.A4);
            document.addPage(prestationPage);
            generatePrestationStatisticsPage(document, prestationPage);

            document.save(outputPath);
        }
    }

    private void generateClientStatisticsPage(PDDocument document, PDPage page) throws IOException {
        // Création des fichiers temporaires pour les graphiques
        File pieChartFile = File.createTempFile("client-type-distribution", ".png");
        File barChartFile = File.createTempFile("revenue-distribution", ".png");
        File loyaltyChartFile = File.createTempFile("top-loyal-clients", ".png");
        File documentChartFile = File.createTempFile("document-distribution", ".png");

        // Génération des graphiques et sauvegarde dans des fichiers temporaires
        createClientTypeDistributionChart(pieChartFile, 500, 400);
        createRevenueDistributionChart(barChartFile, 400, 300);
        createTopLoyalClientsChart(loyaltyChartFile, 400, 300);
        createDocumentDistributionChart(documentChartFile, 400, 300);

        // Ajout des graphiques au PDF
        try (PDPageContentStream contentStream = new PDPageContentStream(document, page)) {
            // Titre
            contentStream.beginText();
            contentStream.setFont(PDType1Font.HELVETICA_BOLD, 16);
            contentStream.newLineAtOffset(50, 750);
            contentStream.showText("Rapport de Statistiques Clients");
            contentStream.endText();

            // Sous-titre avec date
            contentStream.beginText();
            contentStream.setFont(PDType1Font.HELVETICA, 12);
            contentStream.newLineAtOffset(50, 730);
            contentStream.showText("Généré le " + new java.text.SimpleDateFormat("dd/MM/yyyy").format(new Date()));
            contentStream.endText();

            // Ajout des images de graphiques au PDF
            // Le camembert a plus d'espace en haut
            PDImageXObject pieChartImage = PDImageXObject.createFromFile(pieChartFile.getAbsolutePath(), document);
            contentStream.drawImage(pieChartImage, 50, 510, 300, 220);

            // Graphique de revenus à droite
            PDImageXObject barChartImage = PDImageXObject.createFromFile(barChartFile.getAbsolutePath(), document);
            contentStream.drawImage(barChartImage, 360, 550, 200, 180);

            // Graphiques du bas
            float yOffset = 250;
            PDImageXObject loyaltyChartImage = PDImageXObject.createFromFile(loyaltyChartFile.getAbsolutePath(), document);
            contentStream.drawImage(loyaltyChartImage, 50, yOffset, 250, 180);

            PDImageXObject documentChartImage = PDImageXObject.createFromFile(documentChartFile.getAbsolutePath(), document);
            contentStream.drawImage(documentChartImage, 310, yOffset, 250, 180);

            // Titres des graphiques - ajustement des positions
            // Titre du camembert
            contentStream.beginText();
            contentStream.setFont(PDType1Font.HELVETICA_BOLD, 12);
            contentStream.newLineAtOffset(100, 740);
            contentStream.showText("Distribution des Types de Clients");
            contentStream.endText();

            // Titre du graphique de revenus
            contentStream.beginText();
            contentStream.setFont(PDType1Font.HELVETICA_BOLD, 10);
            contentStream.newLineAtOffset(390, 740);
            contentStream.showText("Distribution du Chiffre d'Affaires");
            contentStream.endText();

            // Titres des graphiques du bas
            contentStream.beginText();
            contentStream.setFont(PDType1Font.HELVETICA_BOLD, 10);
            contentStream.newLineAtOffset(110, yOffset + 190);
            contentStream.showText("Top 5 des Clients les plus Fidèles");
            contentStream.endText();

            contentStream.beginText();
            contentStream.setFont(PDType1Font.HELVETICA_BOLD, 10);
            contentStream.newLineAtOffset(360, yOffset + 190);
            contentStream.showText("Distribution des Documents par Client");
            contentStream.endText();
        }

        // Nettoyage des fichiers temporaires
        pieChartFile.delete();
        barChartFile.delete();
        loyaltyChartFile.delete();
        documentChartFile.delete();
    }

    private void generateEventStatisticsPage(PDDocument document, PDPage page) throws IOException {
        // Création des fichiers temporaires pour les graphiques
        File typeEventChartFile = File.createTempFile("event-type-distribution", ".png");
        File freqEventChartFile = File.createTempFile("event-frequency", ".png");
        File topEventChartFile = File.createTempFile("top-events", ".png");
        File costEventChartFile = File.createTempFile("event-cost-distribution", ".png");

        // Générer les graphiques
        createEventTypeDistributionChart(typeEventChartFile, 400, 300);
        createEventFrequencyChart(freqEventChartFile, 400, 300);
        createTopEventsChart(topEventChartFile, 400, 300);
        createEventCostDistributionChart(costEventChartFile, 400, 300);

        // Ajouter les graphiques au PDF
        try (PDPageContentStream contentStream = new PDPageContentStream(document, page)) {
            // Titre
            contentStream.beginText();
            contentStream.setFont(PDType1Font.HELVETICA_BOLD, 16);
            contentStream.newLineAtOffset(50, 750);
            contentStream.showText("Statistiques des Événements");
            contentStream.endText();

            // Sous-titre avec date
            contentStream.beginText();
            contentStream.setFont(PDType1Font.HELVETICA, 12);
            contentStream.newLineAtOffset(50, 730);
            contentStream.showText("Généré le " + new java.text.SimpleDateFormat("dd/MM/yyyy").format(new Date()));
            contentStream.endText();

            // Positionnement des graphiques
            float yTopCharts = 520;
            float yBottomCharts = 220;

            // Type d'événement (haut gauche)
            PDImageXObject typeChart = PDImageXObject.createFromFile(typeEventChartFile.getAbsolutePath(), document);
            contentStream.drawImage(typeChart, 50, yTopCharts, 250, 180);

            // Fréquence (haut droit)
            PDImageXObject freqChart = PDImageXObject.createFromFile(freqEventChartFile.getAbsolutePath(), document);
            contentStream.drawImage(freqChart, 310, yTopCharts, 250, 180);

            // Top événements (bas gauche)
            PDImageXObject topChart = PDImageXObject.createFromFile(topEventChartFile.getAbsolutePath(), document);
            contentStream.drawImage(topChart, 50, yBottomCharts, 250, 180);

            // Distribution des coûts (bas droit)
            PDImageXObject costChart = PDImageXObject.createFromFile(costEventChartFile.getAbsolutePath(), document);
            contentStream.drawImage(costChart, 310, yBottomCharts, 250, 180);

            // Titres des graphiques
            addChartTitle(contentStream, "Distribution par Type d'Événement", 100, yTopCharts + 190);
            addChartTitle(contentStream, "Fréquence des Événements par Mois", 360, yTopCharts + 190);
            addChartTitle(contentStream, "Top 5 des Événements les plus Demandés", 100, yBottomCharts + 190);
            addChartTitle(contentStream, "Distribution des Coûts d'Événements", 360, yBottomCharts + 190);
        }

        // Nettoyage des fichiers temporaires
        typeEventChartFile.delete();
        freqEventChartFile.delete();
        topEventChartFile.delete();
        costEventChartFile.delete();
    }

    private void addChartTitle(PDPageContentStream contentStream, String title, float x, float y) throws IOException {
        contentStream.beginText();
        contentStream.setFont(PDType1Font.HELVETICA_BOLD, 10);
        contentStream.newLineAtOffset(x, y);
        contentStream.showText(title);
        contentStream.endText();
    }

    private void createClientTypeDistributionChart(File outputFile, int width, int height) throws IOException {
        Map<String, Long> distribution = clients.stream()
                .collect(Collectors.groupingBy(CompteClient::getType, Collectors.counting()));

        DefaultPieDataset<String> dataset = new DefaultPieDataset<>();
        distribution.forEach(dataset::setValue);

        JFreeChart chart = ChartFactory.createPieChart(
                "Distribution des Types de Clients",
                dataset, true, true, false
        );

        chart.getTitle().setFont(new Font("SansSerif", Font.BOLD, 12));
        ChartUtils.saveChartAsPNG(outputFile, chart, width, height);
    }

    private void createRevenueDistributionChart(File outputFile, int width, int height) throws IOException {
        double[] ranges = {0, 1000, 5000, 10000, 50000, Double.MAX_VALUE};
        String[] rangeLabels = {"0-1K€", "1K-5K€", "5K-10K€", "10K-50K€", "50K€+"};

        Map<String, Long> distribution = new HashMap<>();
        for (String label : rangeLabels) {
            distribution.put(label, 0L);
        }

        for (CompteClient client : clients) {
            double ca = client.getChiffreAffaires();
            for (int i = 0; i < ranges.length - 1; i++) {
                if (ca >= ranges[i] && ca < ranges[i + 1]) {
                    String label = rangeLabels[i];
                    distribution.put(label, distribution.get(label) + 1);
                    break;
                }
            }
        }

        DefaultCategoryDataset dataset = new DefaultCategoryDataset();
        for (String label : rangeLabels) {
            dataset.addValue(distribution.get(label), "Nombre de clients", label);
        }

        JFreeChart chart = ChartFactory.createBarChart(
                "Distribution par Chiffre d'Affaires", "Plage de CA", "Nombre de clients",
                dataset, PlotOrientation.VERTICAL, false, true, false
        );

        chart.getTitle().setFont(new Font("SansSerif", Font.BOLD, 12));
        ChartUtils.saveChartAsPNG(outputFile, chart, width, height);
    }

    private void createTopLoyalClientsChart(File outputFile, int width, int height) throws IOException {
        // Calculate client loyalty score based on subscriptions, duration and invoices
        List<Map.Entry<CompteClient, Double>> clientsByLoyalty = clients.stream()
                .map(client -> new AbstractMap.SimpleEntry<>(client, calculateLoyaltyScore(client)))
                .sorted(Map.Entry.<CompteClient, Double>comparingByValue().reversed())
                .limit(5)
                .collect(Collectors.toList());

        DefaultCategoryDataset dataset = new DefaultCategoryDataset();
        for (Map.Entry<CompteClient, Double> entry : clientsByLoyalty) {
            String clientName = entry.getKey().getNom().length() > 15
                    ? entry.getKey().getNom().substring(0, 12) + "..."
                    : entry.getKey().getNom();
            dataset.addValue(entry.getValue(), "Score de fidélité", clientName);
        }

        JFreeChart chart = ChartFactory.createBarChart(
                "Top 5 des Clients les plus Fidèles", "Client", "Score de fidélité",
                dataset, PlotOrientation.VERTICAL, false, true, false
        );

        chart.getTitle().setFont(new Font("SansSerif", Font.BOLD, 12));
        ChartUtils.saveChartAsPNG(outputFile, chart, width, height);
    }

    private double calculateLoyaltyScore(CompteClient client) {
        // Loyalty score based on subscriptions, duration and invoices
        double subscriptionFactor = client.getAbonnements().size() * 10;

        long totalDurationDays = client.getAbonnements().stream()
                .mapToLong(abonnement -> {
                    try {
                        // Use LocalDate objects directly without parsing
                        java.time.LocalDate debut = abonnement.getDateDebut();
                        java.time.LocalDate fin = abonnement.getDateFin();
                        return java.time.temporal.ChronoUnit.DAYS.between(debut, fin);
                    } catch (Exception e) {
                        return 0L;
                    }
                })
                .sum();

        double durationFactor = totalDurationDays / 30.0; // Convert to months
        double invoiceFactor = client.getFactures().size() * 5;

        return subscriptionFactor + durationFactor + invoiceFactor;
    }

    private void createDocumentDistributionChart(File outputFile, int width, int height) throws IOException {
        // Calculate average documents per client
        double avgAbonnements = clients.stream().mapToInt(c -> c.getAbonnements().size()).average().orElse(0);
        double avgDevis = clients.stream().mapToInt(c -> c.getDevis().size()).average().orElse(0);
        double avgFactures = clients.stream().mapToInt(c -> c.getFactures().size()).average().orElse(0);

        DefaultCategoryDataset dataset = new DefaultCategoryDataset();
        dataset.addValue(avgAbonnements, "Moyenne par client", "Abonnements");
        dataset.addValue(avgDevis, "Moyenne par client", "Devis");
        dataset.addValue(avgFactures, "Moyenne par client", "Factures");

        JFreeChart chart = ChartFactory.createBarChart(
                "Documents Moyens par Client", "Type de document", "Nombre moyen",
                dataset, PlotOrientation.VERTICAL, false, true, false
        );

        chart.getTitle().setFont(new Font("SansSerif", Font.BOLD, 12));
        ChartUtils.saveChartAsPNG(outputFile, chart, width, height);
    }

    private void createEventTypeDistributionChart(File outputFile, int width, int height) throws IOException {
        Map<String, Object> stats = statisticsService.generateEvenementsStatistics();

        @SuppressWarnings("unchecked")
        Map<String, Long> distribution = (Map<String, Long>) stats.get("typeEvenementDistribution");

        DefaultPieDataset<String> dataset = new DefaultPieDataset<>();
        distribution.forEach(dataset::setValue);

        JFreeChart chart = ChartFactory.createPieChart(
                "Distribution par Type d'Événement",
                dataset, true, true, false
        );

        chart.getTitle().setFont(new Font("SansSerif", Font.BOLD, 12));
        ChartUtils.saveChartAsPNG(outputFile, chart, width, height);
    }

    private void createEventFrequencyChart(File outputFile, int width, int height) throws IOException {
        Map<String, Object> stats = statisticsService.generateEvenementsStatistics();

        @SuppressWarnings("unchecked")
        Map<String, Long> frequenceParMois = (Map<String, Long>) stats.get("frequenceParMois");

        // Define months in chronological order using the actual format from your data
        List<String> monthsInOrder = Arrays.asList(
                "JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE",
                "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER"
        );

        DefaultCategoryDataset dataset = new DefaultCategoryDataset();

        // Add data in chronological order
        for (String month : monthsInOrder) {
            Long count = frequenceParMois.getOrDefault(month, 0L);
            dataset.addValue(count, "Fréquence", month);
        }

        JFreeChart chart = ChartFactory.createBarChart(
                "Fréquence par Mois", "Mois", "Nombre d'événements",
                dataset, PlotOrientation.VERTICAL, false, true, false
        );

        // Improve chart appearance
        chart.getTitle().setFont(new Font("SansSerif", Font.BOLD, 12));

        // Configure X-axis for better visibility
        CategoryPlot plot = chart.getCategoryPlot();
        CategoryAxis domainAxis = plot.getDomainAxis();
        domainAxis.setCategoryLabelPositions(CategoryLabelPositions.createUpRotationLabelPositions(Math.PI / 6.0));

        // Increase bottom margin for label space
        chart.setPadding(new org.jfree.chart.ui.RectangleInsets(5, 5, 25, 5));


        ChartUtils.saveChartAsPNG(outputFile, chart, width, height);
    }

    private void createTopEventsChart(File outputFile, int width, int height) throws IOException {
        Map<String, Object> stats = statisticsService.generateEvenementsStatistics();

        @SuppressWarnings("unchecked")
        List<Evenement> topEvents = (List<Evenement>) stats.get("topEvenements");

        DefaultCategoryDataset dataset = new DefaultCategoryDataset();

        // Add data for top events
        for (Evenement event : topEvents) {
            dataset.addValue(event.getNombreParticipants(), "Participants", event.getNom());
        }

        JFreeChart chart = ChartFactory.createBarChart(
                "Top 5 des Événements", "Événement", "Nombre de participants",
                dataset, PlotOrientation.VERTICAL, false, true, false
        );

        // Significantly increase bottom margin for labels
        chart.setPadding(new RectangleInsets(10, 10, 100, 20));

        CategoryPlot plot = chart.getCategoryPlot();
        plot.setInsets(new RectangleInsets(5, 10, 10, 10));

        CategoryAxis domainAxis = plot.getDomainAxis();

        // Use vertical labels with larger font
        domainAxis.setCategoryLabelPositions(CategoryLabelPositions.UP_90);
        domainAxis.setTickLabelFont(new Font("SansSerif", Font.PLAIN, 12));

        // Ensure maximum space for labels
        domainAxis.setMaximumCategoryLabelWidthRatio(0.5f);

        // Save with larger dimensions - fixed by adding the chart parameter
        ChartUtils.saveChartAsPNG(outputFile, chart, Math.max(width, 800), Math.max(height, 600));
    }

    private void generatePrestationStatisticsPage(PDDocument document, PDPage page) throws IOException {
        // Création des fichiers temporaires pour les graphiques
        File typeDistributionChartFile = File.createTempFile("prestation-type-distribution", ".png");
        File costDistributionChartFile = File.createTempFile("prestation-cost-distribution", ".png");
        File eventAssociationChartFile = File.createTempFile("prestation-event-association", ".png");
        File topPrestationsChartFile = File.createTempFile("top-prestations", ".png");

        // Générer les graphiques
        createPrestationTypeDistributionChart(typeDistributionChartFile, 400, 300);
        createPrestationCostDistributionChart(costDistributionChartFile, 400, 300);
        createPrestationEventAssociationChart(eventAssociationChartFile, 400, 300);
        createTopPrestationsChart(topPrestationsChartFile, 400, 300);

        // Ajouter les graphiques au PDF
        try (PDPageContentStream contentStream = new PDPageContentStream(document, page)) {
            // Titre
            contentStream.beginText();
            contentStream.setFont(PDType1Font.HELVETICA_BOLD, 16);
            contentStream.newLineAtOffset(50, 750);
            contentStream.showText("Statistiques des Prestations");
            contentStream.endText();

            // Sous-titre avec date
            contentStream.beginText();
            contentStream.setFont(PDType1Font.HELVETICA, 12);
            contentStream.newLineAtOffset(50, 730);
            contentStream.showText("Généré le " + new java.text.SimpleDateFormat("dd/MM/yyyy").format(new Date()));
            contentStream.endText();

            // Positionnement des graphiques
            float yTopCharts = 520;
            float yBottomCharts = 220;

            // Type de prestation (haut gauche)
            PDImageXObject typeChart = PDImageXObject.createFromFile(typeDistributionChartFile.getAbsolutePath(), document);
            contentStream.drawImage(typeChart, 50, yTopCharts, 250, 180);

            // Distribution des coûts (haut droit)
            PDImageXObject costChart = PDImageXObject.createFromFile(costDistributionChartFile.getAbsolutePath(), document);
            contentStream.drawImage(costChart, 310, yTopCharts, 250, 180);

            // Événements par type de prestation (bas gauche)
            PDImageXObject eventAssociationChart = PDImageXObject.createFromFile(eventAssociationChartFile.getAbsolutePath(), document);
            contentStream.drawImage(eventAssociationChart, 50, yBottomCharts, 250, 180);

            // Top prestations (bas droit)
            PDImageXObject topChart = PDImageXObject.createFromFile(topPrestationsChartFile.getAbsolutePath(), document);
            contentStream.drawImage(topChart, 310, yBottomCharts, 250, 180);

            // Titres des graphiques
            addChartTitle(contentStream, "Distribution par Type de Prestation", 100, yTopCharts + 190);
            addChartTitle(contentStream, "Distribution par Coût des Prestations", 360, yTopCharts + 190);
            addChartTitle(contentStream, "Événements par Type de Prestation", 100, yBottomCharts + 190);
            addChartTitle(contentStream, "Top 5 des Prestations les plus Fréquentes", 360, yBottomCharts + 190);
        }

        // Nettoyage des fichiers temporaires
        typeDistributionChartFile.delete();
        costDistributionChartFile.delete();
        eventAssociationChartFile.delete();
        topPrestationsChartFile.delete();
    }

    private void createEventCostDistributionChart(File outputFile, int width, int height) throws IOException {
        double[] ranges = {0, 1000, 3000, 5000, 7000, Double.MAX_VALUE};
        String[] rangeLabels = {"0-1K€", "1K-3K€", "3K-5K€", "5K-7K€", ">7K€"};

        Map<String, Long> distribution = new HashMap<>();
        for (String label : rangeLabels) {
            distribution.put(label, 0L);
        }

        for (Evenement event : evenements) {
            double cout = event.getCout();
            for (int i = 0; i < ranges.length - 1; i++) {
                if (cout >= ranges[i] && cout < ranges[i + 1]) {
                    String label = rangeLabels[i];
                    distribution.put(label, distribution.get(label) + 1);
                    break;
                }
            }
        }

        DefaultCategoryDataset dataset = new DefaultCategoryDataset();
        for (String label : rangeLabels) {
            dataset.addValue(distribution.get(label), "Nombre d'événements", label);
        }

        JFreeChart chart = ChartFactory.createBarChart(
                "Distribution par Coût", "Plage de coût", "Nombre d'événements",
                dataset, PlotOrientation.VERTICAL, false, true, false
        );

        chart.getTitle().setFont(new Font("SansSerif", Font.BOLD, 12));
        ChartUtils.saveChartAsPNG(outputFile, chart, width, height);
    }

    //prestation

    private void createPrestationTypeDistributionChart(File outputFile, int width, int height) throws IOException {
        // Group prestations by type
        Map<String, Long> typeDistribution = evenements.stream()
                .flatMap(e -> e.getPrestations().stream())
                .collect(Collectors.groupingBy(Prestation::getType, Collectors.counting()));

        DefaultPieDataset<String> dataset = new DefaultPieDataset<>();
        typeDistribution.forEach(dataset::setValue);

        JFreeChart chart = ChartFactory.createPieChart(
                "Distribution par Type de Prestation",
                dataset, true, true, false
        );

        chart.getTitle().setFont(new Font("SansSerif", Font.BOLD, 12));
        ChartUtils.saveChartAsPNG(outputFile, chart, width, height);
    }

    private void createPrestationCostDistributionChart(File outputFile, int width, int height) throws IOException {
        double[] ranges = {0, 500, 1000, 2000, 3000, Double.MAX_VALUE};
        String[] rangeLabels = {"0-500€", "500-1K€", "1K-2K€", "2K-3K€", ">3K€"};

        Map<String, Long> distribution = new HashMap<>();
        for (String label : rangeLabels) {
            distribution.put(label, 0L);
        }

        List<Prestation> allPrestations = evenements.stream()
                .flatMap(e -> e.getPrestations().stream())
                .collect(Collectors.toList());

        for (Prestation prestation : allPrestations) {
            double cout = prestation.getCout();
            for (int i = 0; i < ranges.length - 1; i++) {
                if (cout >= ranges[i] && cout < ranges[i + 1]) {
                    String label = rangeLabels[i];
                    distribution.put(label, distribution.get(label) + 1);
                    break;
                }
            }
        }

        DefaultCategoryDataset dataset = new DefaultCategoryDataset();
        for (String label : rangeLabels) {
            dataset.addValue(distribution.get(label), "Nombre de prestations", label);
        }

        JFreeChart chart = ChartFactory.createBarChart(
                "Distribution par Coût des Prestations", "Plage de coût", "Nombre de prestations",
                dataset, PlotOrientation.VERTICAL, false, true, false
        );

        chart.getTitle().setFont(new Font("SansSerif", Font.BOLD, 12));
        ChartUtils.saveChartAsPNG(outputFile, chart, width, height);
    }

    private void createPrestationEventAssociationChart(File outputFile, int width, int height) throws IOException {
        // Count each prestation type by number of events associated
        Map<String, Long> typeToEventCount = new HashMap<>();

        for (Evenement event : evenements) {
            Set<String> uniqueTypesInEvent = event.getPrestations().stream()
                    .map(Prestation::getType)
                    .collect(Collectors.toSet());

            for (String type : uniqueTypesInEvent) {
                typeToEventCount.put(type, typeToEventCount.getOrDefault(type, 0L) + 1);
            }
        }

        DefaultCategoryDataset dataset = new DefaultCategoryDataset();
        typeToEventCount.entrySet().stream()
                .sorted(Map.Entry.<String, Long>comparingByValue().reversed())
                .forEach(entry -> dataset.addValue(entry.getValue(), "Événements associés", entry.getKey()));

        JFreeChart chart = ChartFactory.createBarChart(
                "Événements par Type de Prestation", "Type de prestation", "Nombre d'événements",
                dataset, PlotOrientation.VERTICAL, false, true, false
        );

        chart.getTitle().setFont(new Font("SansSerif", Font.BOLD, 12));

        // Handle x-axis labels for better readability
        CategoryPlot plot = chart.getCategoryPlot();
        CategoryAxis domainAxis = plot.getDomainAxis();
        domainAxis.setCategoryLabelPositions(CategoryLabelPositions.UP_45);

        ChartUtils.saveChartAsPNG(outputFile, chart, width, height);
    }

    private void createTopPrestationsChart(File outputFile, int width, int height) throws IOException {
        // Count occurrences of each prestation by name
        Map<String, Long> prestationCounts = evenements.stream()
                .flatMap(e -> e.getPrestations().stream())
                .collect(Collectors.groupingBy(Prestation::getNom, Collectors.counting()));

        // Get top 5 prestations
        List<Map.Entry<String, Long>> topPrestations = prestationCounts.entrySet().stream()
                .sorted(Map.Entry.<String, Long>comparingByValue().reversed())
                .limit(5)
                .collect(Collectors.toList());

        DefaultCategoryDataset dataset = new DefaultCategoryDataset();
        for (Map.Entry<String, Long> entry : topPrestations) {
            dataset.addValue(entry.getValue(), "Fréquence", entry.getKey());
        }

        JFreeChart chart = ChartFactory.createBarChart(
                "Top 5 des Prestations", "Prestation", "Fréquence",
                dataset, PlotOrientation.VERTICAL, false, true, false
        );

        chart.setPadding(new RectangleInsets(10, 10, 100, 20));

        CategoryPlot plot = chart.getCategoryPlot();
        plot.setInsets(new RectangleInsets(5, 10, 10, 10));

        CategoryAxis domainAxis = plot.getDomainAxis();
        domainAxis.setCategoryLabelPositions(CategoryLabelPositions.UP_90);
        domainAxis.setTickLabelFont(new Font("SansSerif", Font.PLAIN, 12));
        domainAxis.setMaximumCategoryLabelWidthRatio(0.5f);

        ChartUtils.saveChartAsPNG(outputFile, chart, Math.max(width, 800), Math.max(height, 600));
    }
}