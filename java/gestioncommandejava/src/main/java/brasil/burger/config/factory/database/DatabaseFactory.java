package brasil.burger.config.factory.database;
import brasil.burger.config.database.*;
import brasil.burger.config.factory.SgbdName;

public final class DatabaseFactory {
    private static final SgbdName sgbdName = SgbdName.POSTGRESQL;
    
    private DatabaseFactory() {}
    
    public static Database getInstance() {
        return DatabaseImpl.getInstance(EntityManager.persistenceUnit(sgbdName));
    }
}