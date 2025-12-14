package brasil.burger.entity;

public class Menu extends Produit {
    private int idBurger;
    private int idComplementBoisson;
    private int idComplementFrites;

    public Menu() {}

    public Menu(int id, String nom, String image, boolean archive, int quantity, 
                int idBurger, int idComplementBoisson, int idComplementFrites) {
        super(id, nom, image, archive, quantity);
        this.idBurger = idBurger;
        this.idComplementBoisson = idComplementBoisson;
        this.idComplementFrites = idComplementFrites;
    }

    public int getIdBurger() {
        return idBurger;
    }

    public void setIdBurger(int idBurger) {
        this.idBurger = idBurger;
    }

    public int getIdComplementBoisson() {
        return idComplementBoisson;
    }

    public void setIdComplementBoisson(int idComplementBoisson) {
        this.idComplementBoisson = idComplementBoisson;
    }

    public int getIdComplementFrites() {
        return idComplementFrites;
    }

    public void setIdComplementFrites(int idComplementFrites) {
        this.idComplementFrites = idComplementFrites;
    }

    @Override
    public String toString() {
        return "Menu{" +
                "id=" + getId() +
                ", nom='" + getNom() + '\'' +
                ", idBurger=" + idBurger +
                ", idComplementBoisson=" + idComplementBoisson +
                ", idComplementFrites=" + idComplementFrites +
                ", quantity=" + getQuantity() +
                ", archive=" + isArchive() +
                '}';
    }
}

