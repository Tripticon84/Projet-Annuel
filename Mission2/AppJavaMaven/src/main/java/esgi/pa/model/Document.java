package esgi.pa.model;

import java.time.LocalDate;

public abstract class Document {
    private String description;
    private double montant;
    private LocalDate date;

    public Document(String description, double montant, LocalDate date) {
        this.description = description;
        this.montant = montant;
        this.date = date;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public double getMontant() {
        return montant;
    }

    public void setMontant(double montant) {
        this.montant = montant;
    }

    public LocalDate getDate() {
        return date;
    }

    public void setDate(LocalDate date) {
        this.date = date;
    }
}