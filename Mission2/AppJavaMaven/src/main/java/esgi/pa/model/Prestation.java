package esgi.pa.model;

public class Prestation {
    private String nom;
    private String type;  // "Salle", "Matériel", "Personnel", etc.
    private String description;
    private double cout;
    private int utilisations;  // Nombre de fois que cette prestation a été utilisée

    public Prestation(String nom, String type, String description, double cout) {
        this.nom = nom;
        this.type = type;
        this.description = description;
        this.cout = cout;
        this.utilisations = 0;
    }

    // Getters et setters
    public String getNom() { return nom; }
    public void setNom(String nom) { this.nom = nom; }

    public String getType() { return type; }
    public void setType(String type) { this.type = type; }

    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }

    public double getCout() { return cout; }
    public void setCout(double cout) { this.cout = cout; }

    public int getUtilisations() { return utilisations; }
    public void setUtilisations(int utilisations) { this.utilisations = utilisations; }
    public void incrementUtilisations() { this.utilisations++; }
}