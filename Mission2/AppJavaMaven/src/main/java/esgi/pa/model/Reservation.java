package esgi.pa.model;

import java.time.LocalDate;

public class Reservation {
    private String lieu;
    private LocalDate date;
    private int dureeHeures;
    private double montant;

    public Reservation(String lieu, LocalDate date, int dureeHeures, double montant) {
        this.lieu = lieu;
        this.date = date;
        this.dureeHeures = dureeHeures;
        this.montant = montant;
    }

    // Getters et setters
    public String getLieu() { return lieu; }
    public void setLieu(String lieu) { this.lieu = lieu; }

    public LocalDate getDate() { return date; }
    public void setDate(LocalDate date) { this.date = date; }

    public int getDureeHeures() { return dureeHeures; }
    public void setDureeHeures(int dureeHeures) { this.dureeHeures = dureeHeures; }

    public double getMontant() { return montant; }
    public void setMontant(double montant) { this.montant = montant; }
}