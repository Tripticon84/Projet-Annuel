package esgi.pa.model;

import java.time.LocalDate;

public class Abonnement extends Document {
    private LocalDate dateDebut;
    private LocalDate dateFin;

    public Abonnement(String type, double montant, LocalDate dateDebut, LocalDate dateFin) {
        super(type, montant, dateDebut);
        this.dateDebut = dateDebut;
        this.dateFin = dateFin;
    }

    public LocalDate getDateDebut() {
        return dateDebut;
    }

    public void setDateDebut(LocalDate dateDebut) {
        this.dateDebut = dateDebut;
    }

    public LocalDate getDateFin() {
        return dateFin;
    }

    public void setDateFin(LocalDate dateFin) {
        this.dateFin = dateFin;
    }
}