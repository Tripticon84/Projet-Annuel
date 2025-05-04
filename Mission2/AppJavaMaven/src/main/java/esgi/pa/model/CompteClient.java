package esgi.pa.model;

import java.util.ArrayList;
import java.util.List;

public class CompteClient {
    private String nom;
    private String type;
    private double chiffreAffaires;
    private List<Abonnement> abonnements;
    private List<Devis> devis;
    private List<Facture> factures;

    public CompteClient(String nom, String type, double chiffreAffaires) {
        this.nom = nom;
        this.type = type;
        this.chiffreAffaires = chiffreAffaires;
        this.abonnements = new ArrayList<>();
        this.devis = new ArrayList<>();
        this.factures = new ArrayList<>();
    }

    // Getters and Setters
    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public double getChiffreAffaires() {
        return chiffreAffaires;
    }

    public void setChiffreAffaires(double chiffreAffaires) {
        this.chiffreAffaires = chiffreAffaires;
    }

    public List<Abonnement> getAbonnements() {
        return abonnements;
    }

    public List<Devis> getDevis() {
        return devis;
    }

    public List<Facture> getFactures() {
        return factures;
    }

    // Methods to add items to lists
    public void addAbonnement(Abonnement abonnement) {
        this.abonnements.add(abonnement);
    }

    public void addDevis(Devis devis) {
        this.devis.add(devis);
    }

    public void addFacture(Facture facture) {
        this.factures.add(facture);
    }
}