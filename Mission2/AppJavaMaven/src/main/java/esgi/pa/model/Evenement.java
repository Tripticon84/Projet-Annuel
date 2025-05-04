package esgi.pa.model;

import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;

public class Evenement {
    private String nom;
    private String type;
    private String description;
    private LocalDate dateDebut;
    private LocalDate dateFin;
    private int nombreParticipants;
    private double cout;
    private List<Reservation> reservations = new ArrayList<>();
    private List<Prestation> prestations = new ArrayList<>();

    public Evenement(String nom, String type, String description, LocalDate dateDebut,
                     LocalDate dateFin, int nombreParticipants, double cout) {
        this.nom = nom;
        this.type = type;
        this.description = description;
        this.dateDebut = dateDebut;
        this.dateFin = dateFin;
        this.nombreParticipants = nombreParticipants;
        this.cout = cout;
    }

    // Getters et setters
    public String getNom() { return nom; }
    public void setNom(String nom) { this.nom = nom; }

    public String getType() { return type; }
    public void setType(String type) { this.type = type; }

    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }

    public LocalDate getDateDebut() { return dateDebut; }
    public void setDateDebut(LocalDate dateDebut) { this.dateDebut = dateDebut; }

    public LocalDate getDateFin() { return dateFin; }
    public void setDateFin(LocalDate dateFin) { this.dateFin = dateFin; }

    public int getNombreParticipants() { return nombreParticipants; }
    public void setNombreParticipants(int nombreParticipants) { this.nombreParticipants = nombreParticipants; }

    public double getCout() { return cout; }
    public void setCout(double cout) { this.cout = cout; }

    public List<Reservation> getReservations() { return reservations; }
    public void setReservations(List<Reservation> reservations) { this.reservations = reservations; }
    public void addReservation(Reservation reservation) { this.reservations.add(reservation); }

    public List<Prestation> getPrestations() { return prestations; }
    public void setPrestations(List<Prestation> prestations) { this.prestations = prestations; }
    public void addPrestation(Prestation prestation) { this.prestations.add(prestation); }
}