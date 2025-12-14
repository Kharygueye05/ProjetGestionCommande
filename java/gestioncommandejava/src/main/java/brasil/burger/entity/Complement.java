package brasil.burger.entity;

public class Complement extends Produit {
    private double prix;
    private String type;

    public Complement() {}

    public Complement(int id, String nom, String image, boolean archive, double prix, String type) {
        super(id, nom, image, archive);
        this.prix = prix;
        this.type = type;
    }

    public double getPrix() {
        return prix;
    }

    public void setPrix(double prix) {
        this.prix = prix;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    @Override
    public String toString() {
        return "Complement{" +
                "id=" + getId() +
                ", nom='" + getNom() + '\'' +
                ", type='" + type + '\'' +
                ", prix=" + prix + " FCFA" +
                ", archive=" + isArchive() +
                '}';
    }
}

