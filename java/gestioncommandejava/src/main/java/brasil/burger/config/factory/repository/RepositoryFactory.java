package brasil.burger.config.factory.repository;
import brasil.burger.config.database.Database;
import brasil.burger.config.factory.EntityName;
import brasil.burger.config.factory.database.DatabaseFactory;
import brasil.burger.repository.Bd.*;

public final class RepositoryFactory {
    public static Storage storage = Storage.DATABASE;
    
    private RepositoryFactory() {}
    
    public static Object createRepository(EntityName entity) {
        Database database = DatabaseFactory.getInstance();
        return createRepositoryInBd(entity, database);
    }
    
    private static Object createRepositoryInBd(EntityName entity, Database database) {
        switch (entity) {
            case BURGER:
                return BurgerRepositoryBd.getInstance(database);
            case COMPLEMENT:
                return ComplementRepositoryBd.getInstance(database);
            case MENU:
                return MenuRepositoryBd.getInstance(database);
            default:
                throw new IllegalArgumentException("Unknown entity: " + entity);
        }
    }
}