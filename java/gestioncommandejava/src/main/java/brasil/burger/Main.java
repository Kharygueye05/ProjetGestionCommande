package brasil.burger;
import brasil.burger.config.factory.EntityName;
import brasil.burger.config.factory.services.ServicesFactory;
import brasil.burger.services.*;
import brasil.burger.views.GestionnaireView;
import brasil.burger.entity.*;
import java.util.List;
import java.util.Optional;

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
                                    System.out.println("\nBurger créé avec succès!");
                                } else {
                                    System.out.println("\nErreur lors de la création du burger");
                                }
                                break;
                            case 2:
                                
                                GestionnaireView.afficheBurgers(burgerService.getAllBurgers());
                                break;
                            case 3:
                                List<Burger> burgersAModifier = burgerService.getAllBurgers();
                                if (burgersAModifier.isEmpty()) {
                                    System.out.println("\nAucun burger à modifier");
                                } else {
                                    int idBurgerModif = GestionnaireView.selectionnerBurger(burgersAModifier);
                                    Optional<Burger> burgerOpt = burgerService.getBurgerById(idBurgerModif);
                                    if (burgerOpt.isPresent()) {
                                        Burger burgerModifie = GestionnaireView.modifierBurger(burgerOpt.get());
                                        if (burgerService.updateBurger(burgerModifie)) {
                                            System.out.println("\nBurger modifié avec succès!");
                                        } else {
                                            System.out.println("\nErreur lors de la modification");
                                        }
                                    } else {
                                        System.out.println("\nBurger introuvable");
                                    }
                                }
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
                    System.out.println("Au revoir!");
                    break;
                default:
                    System.out.println("Choix incorrect");
                    break;
            }
        } while (choix != 4);
    }
}