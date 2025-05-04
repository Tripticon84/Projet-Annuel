package esgi.pa.model;

import java.time.LocalDate;

public class Facture extends Document {
    private String numero;

    public Facture(String description, double montant, LocalDate date, String numero) {
        super(description, montant, date);
        this.numero = numero;
    }

    public String getNumero() {
        return numero;
    }

    public void setNumero(String numero) {
        this.numero = numero;
    }
}