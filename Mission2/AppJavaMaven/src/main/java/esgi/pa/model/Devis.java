package esgi.pa.model;

import java.time.LocalDate;

public class Devis extends Document {
    public Devis(String description, double montant, LocalDate date) {
        super(description, montant, date);
    }
}