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
                                List<Burger> burgersAArchiver = burgerService.getAllBurgers();
                                if (burgersAArchiver.isEmpty()) {
                                    System.out.println("\nAucun burger à archiver");
                                } else {
                                    int idBurgerArch = GestionnaireView.selectionnerBurger(burgersAArchiver);
                                    if (burgerService.archiveBurger(idBurgerArch)) {
                                        System.out.println("\nBurger archivé avec succès!");
                                    } else {
                                        System.out.println("\n Erreur lors de l'archivage");
                                    }
                                }
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
                    int choixComplement;
                    do {
                        choixComplement = GestionnaireView.menuComplement();
                        switch (choixComplement) {
                            case 1:
                                Complement nouveauComplement = GestionnaireView.saisirComplement();
                                if (complementService.createComplement(nouveauComplement)) {
                                    System.out.println("\nComplément créé avec succès!");
                                } else {
                                    System.out.println("\nErreur lors de la création du complément");
                                }
                                break;
                            case 2:
                                GestionnaireView.afficheComplements(complementService.getAllComplements());
                                break;
                            case 3:
                                List<Complement> complementsAModifier = complementService.getAllComplements();
                                if (complementsAModifier.isEmpty()) {
                                    System.out.println("\nAucun complément à modifier");
                                } else {
                                    int idComplementModif = GestionnaireView.selectionnerComplement(complementsAModifier);
                                    Optional<Complement> complementOpt = complementService.getComplementById(idComplementModif);
                                    if (complementOpt.isPresent()) {
                                        Complement complementModifie = GestionnaireView.modifierComplement(complementOpt.get());
                                        if (complementService.updateComplement(complementModifie)) {
                                            System.out.println("\nComplément modifié avec succès!");
                                        } else {
                                            System.out.println("\nErreur lors de la modification");
                                        }
                                    } else {
                                        System.out.println("\nComplément introuvable");
                                    }
                                }
                                break;
                            case 4:
                                List<Complement> complementsAArchiver = complementService.getAllComplements();
                                if (complementsAArchiver.isEmpty()) {
                                    System.out.println("\nAucun complément à archiver");
                                } else {
                                    int idComplementArch = GestionnaireView.selectionnerComplement(complementsAArchiver);
                                    if (complementService.archiveComplement(idComplementArch)) {
                                        System.out.println("\nComplément archivé avec succès!");
                                    } else {
                                        System.out.println("\nErreur lors de l'archivage");
                                    }
                                }
                                break;
                            case 5:
                                System.out.println("Retour au menu principal");
                                break;
                            default:
                                System.out.println("Choix incorrect");
                                break;
                        }
                    } while (choixComplement != 5);
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