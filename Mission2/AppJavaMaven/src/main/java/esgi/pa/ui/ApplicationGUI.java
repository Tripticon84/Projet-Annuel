package esgi.pa.ui;

import esgi.pa.model.CompteClient;
import esgi.pa.model.Evenement;
import esgi.pa.model.Prestation;
import esgi.pa.model.Reservation;
import esgi.pa.service.DataGenerator;
import esgi.pa.service.ReportService;
import esgi.pa.service.StatisticsService;

import javax.swing.*;
import javax.swing.border.EmptyBorder;
import javax.swing.table.DefaultTableModel;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.io.File;
import java.text.DecimalFormat;
import java.util.List;
import java.util.Map;

public class ApplicationGUI extends JFrame {
    private DataGenerator dataGenerator;
    private List<CompteClient> clients;
    private List<Evenement> evenements;
    private StatisticsService statisticsService;

    private JPanel mainPanel;
    private JPanel contentPanel;
    private final DecimalFormat decimalFormat = new DecimalFormat("#,##0.00€");

    public ApplicationGUI() {
        // Initialize data
        dataGenerator = new DataGenerator();
        clients = dataGenerator.generateClients(50);
        evenements = dataGenerator.generateEvenements(30);
        statisticsService = new StatisticsService(clients);
        statisticsService.setEvenements(evenements);

        // Setup UI
        setTitle("Gestionnaire d'Événements et Clients");
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setSize(900, 600);
        setLocationRelativeTo(null);

        // Create main panel with BorderLayout
        mainPanel = new JPanel(new BorderLayout());

        // Create sidebar with buttons
        JPanel sidebarPanel = createSidebarPanel();
        mainPanel.add(sidebarPanel, BorderLayout.WEST);

        // Create content panel
        contentPanel = new JPanel(new BorderLayout());
        contentPanel.setBorder(new EmptyBorder(10, 10, 10, 10));
        mainPanel.add(contentPanel, BorderLayout.CENTER);

        // Set main panel as content pane
        setContentPane(mainPanel);

        // Display welcome screen initially
        displayWelcomeScreen();
    }

    private JPanel createSidebarPanel() {
        JPanel panel = new JPanel();
        panel.setLayout(new BoxLayout(panel, BoxLayout.Y_AXIS));
        panel.setBorder(BorderFactory.createEmptyBorder(10, 10, 10, 10));
        panel.setBackground(new Color(230, 230, 230));
        panel.setPreferredSize(new Dimension(200, getHeight()));

        addSidebarButton(panel, "Statistiques générales", e -> displayAllStatistics());
        addSidebarButton(panel, "Liste des clients", e -> displayClientList());
        addSidebarButton(panel, "Détails d'un client", e -> promptForClientDetails());
        addSidebarButton(panel, "Distribution des clients", e -> displayClientTypeDistribution());
        addSidebarButton(panel, "Liste des événements", e -> displayEventList());
        addSidebarButton(panel, "Détails d'un événement", e -> promptForEventDetails());
        addSidebarButton(panel, "Générer rapport PDF", e -> generatePdfReport());

        // Add spacing at the bottom
        panel.add(Box.createVerticalGlue());

        return panel;
    }

    private void addSidebarButton(JPanel panel, String text, java.awt.event.ActionListener listener) {
        JButton button = new JButton(text);
        button.setAlignmentX(Component.CENTER_ALIGNMENT);
        button.setMaximumSize(new Dimension(180, 40));
        button.setPreferredSize(new Dimension(180, 40));
        button.addActionListener(listener);
        panel.add(button);
        panel.add(Box.createRigidArea(new Dimension(0, 10)));
    }

    private void displayWelcomeScreen() {
        contentPanel.removeAll();

        JLabel titleLabel = new JLabel("Bienvenue dans l'application de gestion", JLabel.CENTER);
        titleLabel.setFont(new Font("Arial", Font.BOLD, 24));

        JLabel subtitleLabel = new JLabel("Utilisez les boutons à gauche pour naviguer", JLabel.CENTER);
        subtitleLabel.setFont(new Font("Arial", Font.PLAIN, 16));

        JPanel centerPanel = new JPanel();
        centerPanel.setLayout(new BoxLayout(centerPanel, BoxLayout.Y_AXIS));
        centerPanel.add(Box.createVerticalGlue());
        centerPanel.add(titleLabel);
        centerPanel.add(Box.createRigidArea(new Dimension(0, 20)));
        centerPanel.add(subtitleLabel);
        centerPanel.add(Box.createVerticalGlue());

        contentPanel.add(centerPanel, BorderLayout.CENTER);
        contentPanel.revalidate();
        contentPanel.repaint();
    }

    private void displayAllStatistics() {
        contentPanel.removeAll();

        Map<String, Object> stats = statisticsService.generateAllStatistics();
        Map<String, Object> eventStats = statisticsService.generateEvenementsStatistics();

        // Create panel with GridBagLayout for better control
        JPanel statsPanel = new JPanel(new GridBagLayout());
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(5, 5, 5, 5);
        gbc.anchor = GridBagConstraints.WEST;

        // Add section title
        JLabel titleLabel = new JLabel("Statistiques Générales", JLabel.LEFT);
        titleLabel.setFont(new Font("Arial", Font.BOLD, 18));
        gbc.gridx = 0;
        gbc.gridy = 0;
        gbc.gridwidth = 2;
        statsPanel.add(titleLabel, gbc);

        // Reset gridwidth
        gbc.gridwidth = 1;

        // Client statistics
        addStatRow(statsPanel, gbc, 1, "Nombre de clients:", stats.get("clientCount").toString());
        addStatRow(statsPanel, gbc, 2, "Total abonnements:", stats.get("totalAbonnements").toString());
        addStatRow(statsPanel, gbc, 3, "Total devis:", stats.get("totalDevis").toString());
        addStatRow(statsPanel, gbc, 4, "Total factures:", stats.get("totalFactures").toString());

        // Financial statistics
        JLabel financeTitle = new JLabel("Statistiques Financières", JLabel.LEFT);
        financeTitle.setFont(new Font("Arial", Font.BOLD, 18));
        gbc.gridx = 0;
        gbc.gridy = 5;
        gbc.gridwidth = 2;
        statsPanel.add(financeTitle, gbc);
        gbc.gridwidth = 1;

        addStatRow(statsPanel, gbc, 6, "Valeur abonnements:", decimalFormat.format(stats.get("totalAbonnementValue")));
        addStatRow(statsPanel, gbc, 7, "Moyenne abonnements:", decimalFormat.format(stats.get("avgAbonnementValue")));
        addStatRow(statsPanel, gbc, 8, "Valeur devis:", decimalFormat.format(stats.get("totalDevisValue")));
        addStatRow(statsPanel, gbc, 9, "Valeur factures:", decimalFormat.format(stats.get("totalFactureValue")));

        // Event statistics
        JLabel eventTitle = new JLabel("Statistiques Événements", JLabel.LEFT);
        eventTitle.setFont(new Font("Arial", Font.BOLD, 18));
        gbc.gridx = 0;
        gbc.gridy = 10;
        gbc.gridwidth = 2;
        statsPanel.add(eventTitle, gbc);
        gbc.gridwidth = 1;

        addStatRow(statsPanel, gbc, 11, "Nombre d'événements:", eventStats.get("totalEvenements").toString());
        addStatRow(statsPanel, gbc, 12, "Moy. participants:", String.format("%.1f", eventStats.get("avgParticipants")));
        addStatRow(statsPanel, gbc, 13, "Coût moyen:", decimalFormat.format(eventStats.get("avgCout")));

        // Wrap in scroll pane
        JScrollPane scrollPane = new JScrollPane(statsPanel);
        contentPanel.add(scrollPane, BorderLayout.CENTER);
        contentPanel.revalidate();
        contentPanel.repaint();
    }

    private void addStatRow(JPanel panel, GridBagConstraints gbc, int row, String label, String value) {
        gbc.gridx = 0;
        gbc.gridy = row;
        panel.add(new JLabel(label), gbc);

        gbc.gridx = 1;
        panel.add(new JLabel(value, JLabel.RIGHT), gbc);
    }

    private void displayClientList() {
        contentPanel.removeAll();

        String[] columns = {"ID", "Nom", "Type", "Chiffre d'affaires"};
        Object[][] data = new Object[clients.size()][4];

        for (int i = 0; i < clients.size(); i++) {
            CompteClient client = clients.get(i);
            data[i][0] = i + 1;
            data[i][1] = client.getNom();
            data[i][2] = client.getType();
            data[i][3] = decimalFormat.format(client.getChiffreAffaires());
        }

        DefaultTableModel model = new DefaultTableModel(data, columns) {
            @Override
            public boolean isCellEditable(int row, int column) {
                return false;
            }
        };

        JTable table = new JTable(model);
        table.setRowHeight(25);
        table.getTableHeader().setReorderingAllowed(false);

        JScrollPane scrollPane = new JScrollPane(table);

        JPanel topPanel = new JPanel(new BorderLayout());
        JLabel titleLabel = new JLabel("Liste des Clients", JLabel.LEFT);
        titleLabel.setFont(new Font("Arial", Font.BOLD, 18));
        topPanel.add(titleLabel, BorderLayout.WEST);

        contentPanel.add(topPanel, BorderLayout.NORTH);
        contentPanel.add(scrollPane, BorderLayout.CENTER);
        contentPanel.revalidate();
        contentPanel.repaint();
    }

    private void promptForClientDetails() {
        if (clients.isEmpty()) {
            JOptionPane.showMessageDialog(this, "Aucun client disponible", "Erreur", JOptionPane.ERROR_MESSAGE);
            return;
        }

        String[] clientIds = new String[clients.size()];
        for (int i = 0; i < clients.size(); i++) {
            CompteClient client = clients.get(i);
            clientIds[i] = "ID: " + (i + 1) + " - " + client.getNom() + " (" + client.getType() + ")";
        }

        String selectedClient = (String) JOptionPane.showInputDialog(
                this,
                "Sélectionnez un client:",
                "Détails Client",
                JOptionPane.QUESTION_MESSAGE,
                null,
                clientIds,
                clientIds[0]
        );

        if (selectedClient != null) {
            int index = Integer.parseInt(selectedClient.substring(4, selectedClient.indexOf(" -"))) - 1;
            displayClientDetails(clients.get(index));
        }
    }

    private void displayClientDetails(CompteClient client) {
        contentPanel.removeAll();

        JPanel detailsPanel = new JPanel();
        detailsPanel.setLayout(new BoxLayout(detailsPanel, BoxLayout.Y_AXIS));
        detailsPanel.setBorder(new EmptyBorder(10, 10, 10, 10));

        // Header info
        JPanel headerPanel = new JPanel(new BorderLayout());
        JLabel nameLabel = new JLabel(client.getNom(), JLabel.LEFT);
        nameLabel.setFont(new Font("Arial", Font.BOLD, 20));
        headerPanel.add(nameLabel, BorderLayout.NORTH);

        JPanel infoPanel = new JPanel(new GridLayout(2, 2, 10, 5));
        infoPanel.add(new JLabel("Type: " + client.getType()));
        infoPanel.add(new JLabel("Chiffre d'affaires: " + decimalFormat.format(client.getChiffreAffaires())));
        headerPanel.add(infoPanel, BorderLayout.CENTER);

        detailsPanel.add(headerPanel);
        detailsPanel.add(Box.createRigidArea(new Dimension(0, 20)));

        // Abonnements section
        detailsPanel.add(createSectionTitle("Abonnements (" + client.getAbonnements().size() + ")"));
        String[] abonnementColumns = {"Type", "Montant", "Date début", "Date fin"};
        Object[][] abonnementData = new Object[client.getAbonnements().size()][4];

        for (int i = 0; i < client.getAbonnements().size(); i++) {
            abonnementData[i][0] = client.getAbonnements().get(i).getDescription();
            abonnementData[i][1] = decimalFormat.format(client.getAbonnements().get(i).getMontant());
            abonnementData[i][2] = client.getAbonnements().get(i).getDateDebut();
            abonnementData[i][3] = client.getAbonnements().get(i).getDateFin();
        }

        detailsPanel.add(createTablePanel(abonnementColumns, abonnementData));
        detailsPanel.add(Box.createRigidArea(new Dimension(0, 20)));

        // Devis section
        detailsPanel.add(createSectionTitle("Devis (" + client.getDevis().size() + ")"));
        String[] devisColumns = {"Description", "Montant", "Date"};
        Object[][] devisData = new Object[client.getDevis().size()][3];

        for (int i = 0; i < client.getDevis().size(); i++) {
            devisData[i][0] = client.getDevis().get(i).getDescription();
            devisData[i][1] = decimalFormat.format(client.getDevis().get(i).getMontant());
            devisData[i][2] = client.getDevis().get(i).getDate();
        }

        detailsPanel.add(createTablePanel(devisColumns, devisData));
        detailsPanel.add(Box.createRigidArea(new Dimension(0, 20)));

        // Factures section
        detailsPanel.add(createSectionTitle("Factures (" + client.getFactures().size() + ")"));
        String[] factureColumns = {"N°", "Description", "Montant", "Date"};
        Object[][] factureData = new Object[client.getFactures().size()][4];

        for (int i = 0; i < client.getFactures().size(); i++) {
            factureData[i][0] = client.getFactures().get(i).getNumero();
            factureData[i][1] = client.getFactures().get(i).getDescription();
            factureData[i][2] = decimalFormat.format(client.getFactures().get(i).getMontant());
            factureData[i][3] = client.getFactures().get(i).getDate();
        }

        detailsPanel.add(createTablePanel(factureColumns, factureData));

        JScrollPane scrollPane = new JScrollPane(detailsPanel);
        contentPanel.add(scrollPane, BorderLayout.CENTER);
        contentPanel.revalidate();
        contentPanel.repaint();
    }

    private JLabel createSectionTitle(String title) {
        JLabel label = new JLabel(title, JLabel.LEFT);
        label.setFont(new Font("Arial", Font.BOLD, 16));
        label.setBorder(new EmptyBorder(10, 0, 5, 0));
        return label;
    }

    private JPanel createTablePanel(String[] columns, Object[][] data) {
        DefaultTableModel model = new DefaultTableModel(data, columns) {
            @Override
            public boolean isCellEditable(int row, int column) {
                return false;
            }
        };

        JTable table = new JTable(model);
        table.setRowHeight(25);
        table.getTableHeader().setReorderingAllowed(false);

        JScrollPane scrollPane = new JScrollPane(table);
        scrollPane.setPreferredSize(new Dimension(500, 150));

        JPanel panel = new JPanel(new BorderLayout());
        panel.add(scrollPane, BorderLayout.CENTER);
        return panel;
    }

    private void displayClientTypeDistribution() {
        contentPanel.removeAll();

        Map<String, Object> stats = statisticsService.generateAllStatistics();

        @SuppressWarnings("unchecked")
        Map<String, Long> distribution = (Map<String, Long>) stats.get("clientTypeDistribution");

        String[] columns = {"Type de client", "Nombre", "Pourcentage"};
        Object[][] data = new Object[distribution.size()][3];

        int i = 0;
        for (Map.Entry<String, Long> entry : distribution.entrySet()) {
            double percentage = (entry.getValue() * 100.0) / clients.size();
            data[i][0] = entry.getKey();
            data[i][1] = entry.getValue();
            data[i][2] = String.format("%.1f%%", percentage);
            i++;
        }

        DefaultTableModel model = new DefaultTableModel(data, columns) {
            @Override
            public boolean isCellEditable(int row, int column) {
                return false;
            }
        };

        JTable table = new JTable(model);
        table.setRowHeight(25);
        table.getTableHeader().setReorderingAllowed(false);

        JScrollPane scrollPane = new JScrollPane(table);

        JPanel topPanel = new JPanel(new BorderLayout());
        JLabel titleLabel = new JLabel("Distribution des Types de Clients", JLabel.LEFT);
        titleLabel.setFont(new Font("Arial", Font.BOLD, 18));
        topPanel.add(titleLabel, BorderLayout.WEST);

        contentPanel.add(topPanel, BorderLayout.NORTH);
        contentPanel.add(scrollPane, BorderLayout.CENTER);
        contentPanel.revalidate();
        contentPanel.repaint();
    }

    private void displayEventList() {
        contentPanel.removeAll();

        String[] columns = {"ID", "Type", "Date", "Nombre de prestations"};
        Object[][] data = new Object[evenements.size()][4];

        for (int i = 0; i < evenements.size(); i++) {
            Evenement event = evenements.get(i);
            data[i][0] = i + 1;
            data[i][1] = event.getType();
            data[i][2] = event.getDateDebut();
            data[i][3] = event.getPrestations().size();
        }

        DefaultTableModel model = new DefaultTableModel(data, columns) {
            @Override
            public boolean isCellEditable(int row, int column) {
                return false;
            }
        };

        JTable table = new JTable(model);
        table.setRowHeight(25);
        table.getTableHeader().setReorderingAllowed(false);

        JScrollPane scrollPane = new JScrollPane(table);

        JPanel topPanel = new JPanel(new BorderLayout());
        JLabel titleLabel = new JLabel("Liste des Événements", JLabel.LEFT);
        titleLabel.setFont(new Font("Arial", Font.BOLD, 18));
        topPanel.add(titleLabel, BorderLayout.WEST);

        contentPanel.add(topPanel, BorderLayout.NORTH);
        contentPanel.add(scrollPane, BorderLayout.CENTER);
        contentPanel.revalidate();
        contentPanel.repaint();
    }

    private void promptForEventDetails() {
        if (evenements.isEmpty()) {
            JOptionPane.showMessageDialog(this, "Aucun événement disponible", "Erreur", JOptionPane.ERROR_MESSAGE);
            return;
        }

        String[] eventIds = new String[evenements.size()];
        for (int i = 0; i < evenements.size(); i++) {
            Evenement event = evenements.get(i);
            eventIds[i] = "ID: " + (i + 1) + " - " + event.getType() + " (" + event.getNom() + ")";
        }

        String selectedEvent = (String) JOptionPane.showInputDialog(
                this,
                "Sélectionnez un événement:",
                "Détails Événement",
                JOptionPane.QUESTION_MESSAGE,
                null,
                eventIds,
                eventIds[0]
        );

        if (selectedEvent != null) {
            int index = Integer.parseInt(selectedEvent.substring(4, selectedEvent.indexOf(" -"))) - 1;
            displayEventDetails(evenements.get(index));
        }
    }

    private void displayEventDetails(Evenement event) {
        contentPanel.removeAll();

        JPanel detailsPanel = new JPanel();
        detailsPanel.setLayout(new BoxLayout(detailsPanel, BoxLayout.Y_AXIS));
        detailsPanel.setBorder(new EmptyBorder(10, 10, 10, 10));

        // Header info
        JPanel headerPanel = new JPanel(new BorderLayout());
        JLabel nameLabel = new JLabel(event.getNom(), JLabel.LEFT);
        nameLabel.setFont(new Font("Arial", Font.BOLD, 20));
        headerPanel.add(nameLabel, BorderLayout.NORTH);

        JPanel infoPanel = new JPanel(new GridLayout(4, 2, 10, 5));
        infoPanel.add(new JLabel("Type: " + event.getType()));
        infoPanel.add(new JLabel("Participants: " + event.getNombreParticipants()));
        infoPanel.add(new JLabel("Date début: " + event.getDateDebut()));
        infoPanel.add(new JLabel("Date fin: " + event.getDateFin()));
        infoPanel.add(new JLabel("Coût total: " + decimalFormat.format(event.getCout())));
        headerPanel.add(infoPanel, BorderLayout.CENTER);

        detailsPanel.add(headerPanel);
        detailsPanel.add(Box.createRigidArea(new Dimension(0, 10)));

        // Description
        JLabel descLabel = new JLabel("Description:", JLabel.LEFT);
        descLabel.setFont(new Font("Arial", Font.BOLD, 14));
        detailsPanel.add(descLabel);

        JTextArea descArea = new JTextArea(event.getDescription());
        descArea.setLineWrap(true);
        descArea.setWrapStyleWord(true);
        descArea.setEditable(false);
        descArea.setBackground(null);
        detailsPanel.add(descArea);
        detailsPanel.add(Box.createRigidArea(new Dimension(0, 20)));

        // Réservations section
        detailsPanel.add(createSectionTitle("Réservations (" + event.getReservations().size() + ")"));
        String[] reservationColumns = {"Lieu", "Date", "Durée (h)", "Montant"};
        Object[][] reservationData = new Object[event.getReservations().size()][4];

        List<Reservation> reservations = event.getReservations();
        for (int i = 0; i < reservations.size(); i++) {
            Reservation reservation = reservations.get(i);
            reservationData[i][0] = reservation.getLieu();
            reservationData[i][1] = reservation.getDate();
            reservationData[i][2] = reservation.getDureeHeures();
            reservationData[i][3] = decimalFormat.format(reservation.getMontant());
        }

        detailsPanel.add(createTablePanel(reservationColumns, reservationData));
        detailsPanel.add(Box.createRigidArea(new Dimension(0, 20)));

        // Prestations section
        detailsPanel.add(createSectionTitle("Prestations (" + event.getPrestations().size() + ")"));
        String[] prestationColumns = {"Nom", "Type", "Description", "Coût"};
        Object[][] prestationData = new Object[event.getPrestations().size()][4];

        List<Prestation> prestations = event.getPrestations();
        for (int i = 0; i < prestations.size(); i++) {
            Prestation prestation = prestations.get(i);
            prestationData[i][0] = prestation.getNom();
            prestationData[i][1] = prestation.getType();
            prestationData[i][2] = prestation.getDescription();
            prestationData[i][3] = decimalFormat.format(prestation.getCout());
        }

        detailsPanel.add(createTablePanel(prestationColumns, prestationData));

        JScrollPane scrollPane = new JScrollPane(detailsPanel);
        contentPanel.add(scrollPane, BorderLayout.CENTER);
        contentPanel.revalidate();
        contentPanel.repaint();
    }

    private void generatePdfReport() {
        // Create file chooser
        JFileChooser fileChooser = new JFileChooser();
        fileChooser.setDialogTitle("Enregistrer le rapport PDF");
        fileChooser.setSelectedFile(new File("rapport_statistiques.pdf"));

        int userSelection = fileChooser.showSaveDialog(this);
        if (userSelection == JFileChooser.APPROVE_OPTION) {
            File fileToSave = fileChooser.getSelectedFile();
            String outputPath = fileToSave.getAbsolutePath();

            // Add .pdf extension if missing
            if (!outputPath.toLowerCase().endsWith(".pdf")) {
                outputPath += ".pdf";
            }

            try {
                ReportService reportService = new ReportService(clients, evenements, statisticsService);
                reportService.generateStatisticsReport(outputPath);

                // Show success message
                JOptionPane.showMessageDialog(
                        this,
                        "Rapport PDF généré avec succès:\n" + outputPath,
                        "Succès",
                        JOptionPane.INFORMATION_MESSAGE
                );

                // Try to open the PDF file
                try {
                    Desktop.getDesktop().open(new File(outputPath));
                } catch (Exception e) {
                    // Silently ignore if can't open
                }

            } catch (Exception e) {
                JOptionPane.showMessageDialog(
                        this,
                        "Erreur lors de la génération du rapport:\n" + e.getMessage(),
                        "Erreur",
                        JOptionPane.ERROR_MESSAGE
                );
            }
        }
    }

    public static void main(String[] args) {
        SwingUtilities.invokeLater(() -> {
            try {
                // Set system look and feel
                UIManager.setLookAndFeel(UIManager.getSystemLookAndFeelClassName());
            } catch (Exception e) {
                e.printStackTrace();
            }

            new ApplicationGUI().setVisible(true);
        });
    }
}