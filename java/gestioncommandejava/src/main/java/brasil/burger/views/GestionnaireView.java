package brasil.burger.views;

import brasil.burger.entity.*;
import brasil.burger.services.MenuService;

import java.util.List;
import java.util.Scanner;

public class GestionnaireView {
    private static Scanner scanner = new Scanner(System.in);

    private GestionnaireView() {
    }

    public static int menu() {
        System.out.println("\n=== BRASIL BURGER - GESTION ===");
        System.out.println("1. Gestion Burgers");
        System.out.println("2. Gestion Compléments");
        System.out.println("3. Gestion Menus");
        System.out.println("4. Quitter");
        System.out.print("Faites votre choix: ");
        int choix = scanner.nextInt();
        scanner.nextLine();
        return choix;
    }

    public static int menuBurger() {
        System.out.println("\n=== GESTION BURGERS ===");
        System.out.println("1. Créer un burger");
        System.out.println("2. Lister les burgers");
        System.out.println("3. Modifier un burger");
        System.out.println("4. Archiver un burger");
        System.out.println("5. Retour");
        System.out.print("Faites votre choix: ");
        int choix = scanner.nextInt();
        scanner.nextLine();
        return choix;
    }

    public static int menuComplement() {
        System.out.println("\n=== GESTION COMPLEMENTS ===");
        System.out.println("1. Créer un complément");
        System.out.println("2. Lister les compléments");
        System.out.println("3. Modifier un complément");
        System.out.println("4. Archiver un complément");
        System.out.println("5. Retour");
        System.out.print("Faites votre choix: ");
        int choix = scanner.nextInt();
        scanner.nextLine();
        return choix;
    }

    public static int menuMenu() {
        System.out.println("\n=== GESTION MENUS ===");
        System.out.println("1. Créer un menu");
        System.out.println("2. Lister les menus");
        System.out.println("3. Modifier un menu");
        System.out.println("4. Archiver un menu");
        System.out.println("5. Retour");
        System.out.print("Faites votre choix: ");
        int choix = scanner.nextInt();
        scanner.nextLine();
        return choix;
    }

    public static Burger saisirBurger() {
        Burger b = new Burger();
        System.out.print("Nom du burger: ");
        b.setNom(scanner.nextLine());
        System.out.print("URL de l'image: ");
        b.setImage(scanner.nextLine());
        System.out.print("Prix: ");
        b.setPrix(scanner.nextDouble());
        scanner.nextLine();
        b.setArchive(false);
        return b;
    }

    public static void afficheBurgers(List<Burger> burgers) {
        if (burgers.isEmpty()) {
            System.out.println("\nAucun burger disponible");
            return;
        }
        System.out.println("\n=== LISTE DES BURGERS ===");
        burgers.forEach(System.out::println);
    }

    public static Complement saisirComplement() {
        Complement c = new Complement();
        System.out.print("Nom du complément: ");
        c.setNom(scanner.nextLine());
        System.out.print("URL de l'image: ");
        c.setImage(scanner.nextLine());
        System.out.print("Type (BOISSON/FRITE): ");
        c.setType(scanner.nextLine().toUpperCase());
        System.out.print("Prix: ");
        c.setPrix(scanner.nextDouble());
        scanner.nextLine();
        c.setArchive(false);
        return c;
    }

    public static void afficheComplements(List<Complement> complements) {
        if (complements.isEmpty()) {
            System.out.println("\nAucun complément disponible");
            return;
        }
        System.out.println("\n=== LISTE DES COMPLEMENTS ===");
        complements.forEach(System.out::println);
    }

    public static int selectionnerBurger(List<Burger> burgers) {
        afficheBurgers(burgers);
        System.out.print("\nEntrez l'ID du burger: ");
        int id = scanner.nextInt();
        scanner.nextLine();
        return id;
    }

    public static int selectionnerComplement(List<Complement> complements) {
        afficheComplements(complements);
        System.out.print("\nEntrez l'ID du complément: ");
        int id = scanner.nextInt();
        scanner.nextLine();
        return id;
    }

    public static Menu saisirMenu(List<Burger> burgers, List<Complement> boissons, List<Complement> frites) {
        Menu m = new Menu();
        System.out.print("Nom du menu: ");
        m.setNom(scanner.nextLine());
        System.out.print("URL de l'image: ");
        m.setImage(scanner.nextLine());

        System.out.println("\nSélectionnez un burger:");
        m.setIdBurger(selectionnerBurger(burgers));

        System.out.println("\nSélectionnez une boisson:");
        m.setIdComplementBoisson(selectionnerComplement(boissons));

        System.out.println("\nSélectionnez des frites:");
        m.setIdComplementFrites(selectionnerComplement(frites));

        m.setArchive(false);
        return m;
    }

    public static void afficheMenus(List<Menu> menus) {
        if (menus.isEmpty()) {
            System.out.println("\nAucun menu disponible");
            return;
        }
        System.out.println("\n=== LISTE DES MENUS ===");
        menus.forEach(System.out::println);
    }

    public static int selectionnerMenu(List<Menu> menus) {
        afficheMenus(menus);
        System.out.print("\nEntrez l'ID du menu: ");
        int id = scanner.nextInt();
        scanner.nextLine();
        return id;
    }

    public static int saisirId() {
        System.out.print("Entrez l'ID: ");
        int id = scanner.nextInt();
        scanner.nextLine();
        return id;
    }

    public static Burger modifierBurger(Burger burger) {
        System.out.println("\n=== MODIFICATION DU BURGER ===");
        System.out.println("Burger actuel: " + burger);

        System.out.print("Nouveau nom: ");
        String nom = scanner.nextLine();
        if (!nom.isEmpty()) {
            burger.setNom(nom);
        }

        System.out.print("Nouvelle URL image: ");
        String image = scanner.nextLine();
        if (!image.isEmpty()) {
            burger.setImage(image);
        }

        System.out.print("Nouveau prix: ");
        String prixStr = scanner.nextLine();
        if (!prixStr.isEmpty()) {
            burger.setPrix(Double.parseDouble(prixStr));
        }

        return burger;
    }

    public static Complement modifierComplement(Complement complement) {
        System.out.println("\n=== MODIFICATION DU COMPLÉMENT ===");
        System.out.println("Complément actuel: " + complement);

        System.out.print("Nouveau nom: ");
        String nom = scanner.nextLine();
        if (!nom.isEmpty()) {
            complement.setNom(nom);
        }

        System.out.print("Nouvelle URL image: ");
        String image = scanner.nextLine();
        if (!image.isEmpty()) {
            complement.setImage(image);
        }

        System.out.print("Nouveau type: ");
        String type = scanner.nextLine().toUpperCase();
        if (!type.isEmpty()) {
            complement.setType(type);
        }

        System.out.print("Nouveau prix: ");
        String prixStr = scanner.nextLine();
        if (!prixStr.isEmpty()) {
            complement.setPrix(Double.parseDouble(prixStr));
        }

        return complement;
    }

    public static Menu modifierMenu(Menu menu, List<Burger> burgers, List<Complement> boissons,
            List<Complement> frites) {
        System.out.println("\n=== MODIFICATION DU MENU ===");
        System.out.println("Menu actuel: " + menu);

        System.out.print("Nouveau nom: ");
        String nom = scanner.nextLine();
        if (!nom.isEmpty()) {
            menu.setNom(nom);
        }

        System.out.print("Nouvelle URL image: ");
        String image = scanner.nextLine();
        if (!image.isEmpty()) {
            menu.setImage(image);
        }

        System.out.print("Modifier le burger ? (o/n): ");
        String modifBurger = scanner.nextLine();
        if (modifBurger.equalsIgnoreCase("o")) {
            System.out.println("\nSélectionnez un nouveau burger:");
            menu.setIdBurger(selectionnerBurger(burgers));
        }

        System.out.print("Modifier la boisson ? (o/n): ");
        String modifBoisson = scanner.nextLine();
        if (modifBoisson.equalsIgnoreCase("o")) {
            System.out.println("\nSélectionnez une nouvelle boisson:");
            menu.setIdComplementBoisson(selectionnerComplement(boissons));
        }

        System.out.print("Modifier les frites ? (o/n): ");
        String modifFrites = scanner.nextLine();
        if (modifFrites.equalsIgnoreCase("o")) {
            System.out.println("\nSélectionnez de nouvelles frites:");
            menu.setIdComplementFrites(selectionnerComplement(frites));
        }

        return menu;
    }

    public static void afficheMenusAvecDetails(List<Menu> menus, MenuService menuService) {
        if (menus.isEmpty()) {
            System.out.println("\nAucun menu disponible");
            return;
        }
        System.out.println("\n=== LISTE DES MENUS ===");
        for (Menu menu : menus) {
            double prix = menuService.calculateMenuPrice(menu.getId());
            System.out.println(menu + " | Prix total: " + prix + " FCFA");
        }
    }

}