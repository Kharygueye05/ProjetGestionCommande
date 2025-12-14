package brasil.burger;
import brasil.burger.config.factory.EntityName;
import brasil.burger.config.factory.services.ServicesFactory;
import brasil.burger.services.*;
import brasil.burger.views.GestionnaireView;
import brasil.burger.entity.*;

public class Main {
    public static void main(String[] args) {
        BurgerService burgerService = (BurgerService) ServicesFactory.createServices(EntityName.BURGER);
        ComplementService complementService = (ComplementService) ServicesFactory.createServices(EntityName.COMPLEMENT);
        MenuService menuService = (MenuService) ServicesFactory.createServices(EntityName.MENU);
        
        int choix;
        do {
            choix = GestionnaireView.menu();
            switch (choix) {
                case 1:
                    int choixBurger;
                    do {
                        choixBurger = GestionnaireView.menuBurger();
                        switch (choixBurger) {
                            case 1:
                                Burger nouveauBurger = GestionnaireView.saisirBurger();
                                if (burgerService.createBurger(nouveauBurger)) {
                                    System.out.println("Burger créé avec succès!");
                                } else {
                                    System.out.println("Erreur lors de la création du burger");
                                }
                                break;
                            case 2:
                                System.out.println("");
                                break;
                            case 3:
                                System.out.println("");
                                break;
                            case 4:
                                System.out.println("");
                                break;
                            case 5:
                                System.out.println("Retour au menu principal");
                                break;
                            default:
                                System.out.println("Choix incorrect");
                                break;
                        }
                    } while (choixBurger != 5);
                    break;
                case 2:
                    
                    System.out.println("");
                    break;
                case 3:
                    
                    System.out.println("");
                    break;
                case 4:
                    System.out.println("");
                    break;
                default:
                    System.out.println("");
                    break;
            }
        } while (choix != 4);
    }
}
