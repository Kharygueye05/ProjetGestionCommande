package brasil.burger.entity;

public abstract class Produit {
    private int id;
    private String nom;
    private String image;
    private boolean archive;

    public Produit() {}

    public Produit(int id, String nom, String image, boolean archive) {
        this.id = id;
        this.nom = nom;
        this.image = image;
        this.archive = archive;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public String getImage() {
        return image;
    }

    public void setImage(String image) {
        this.image = image;
    }

    public boolean isArchive() {
        return archive;
    }

    public void setArchive(boolean archive) {
        this.archive = archive;
    }

    @Override
    public String toString() {
        return "Produit{" +
                "id=" + id +
                ", nom='" + nom + '\'' +
                ", image='" + image + '\'' +
                ", archive=" + archive +
                '}';
    }
}

