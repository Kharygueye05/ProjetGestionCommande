package brasil.burger.entity;

public class Burger extends Produit {
    private double prix;

    public Burger() {}

    public Burger(int id, String nom, String image, boolean archive, double prix) {
        super(id, nom, image, archive);
        this.prix = prix;
    }

    public double getPrix() {
        return prix;
    }

    public void setPrix(double prix) {
        this.prix = prix;
    }

    @Override
    public String toString() {
        return "Burger{" +
                "id=" + getId() +
                ", nom='" + getNom() + '\'' +
                ", prix=" + prix + " FCFA" +
                ", archive=" + isArchive() +
                '}';
    }
}
