package brasil.burger.config.factory.services;
import brasil.burger.config.factory.EntityName;
import brasil.burger.config.factory.repository.RepositoryFactory;
import brasil.burger.repository.*;
import brasil.burger.services.Impl.BurgerServiceImpl;
import brasil.burger.services.Impl.ComplementServiceImpl;
import brasil.burger.services.Impl.MenuServiceImpl;
import brasil.burger.services.Impl.*;

public final class ServicesFactory {
    private ServicesFactory() {}
    
    public static Object createServices(EntityName entity) {
        switch (entity) {
            case BURGER:
                BurgerRepository burgerRepo = (BurgerRepository) RepositoryFactory.createRepository(entity);
                return BurgerServiceImpl.getInstance(burgerRepo);
            case COMPLEMENT:
                ComplementRepository complementRepo = (ComplementRepository) RepositoryFactory.createRepository(entity);
                return ComplementServiceImpl.getInstance(complementRepo);
            case MENU:
                MenuRepository menuRepo = (MenuRepository) RepositoryFactory.createRepository(EntityName.MENU);
                BurgerRepository burgerRepoForMenu = (BurgerRepository) RepositoryFactory.createRepository(EntityName.BURGER);
                ComplementRepository complementRepoForMenu = (ComplementRepository) RepositoryFactory.createRepository(EntityName.COMPLEMENT);
                return MenuServiceImpl.getInstance(menuRepo, burgerRepoForMenu, complementRepoForMenu);
            default:
                throw new IllegalArgumentException("Unknown entity: " + entity);
        }
    }
}