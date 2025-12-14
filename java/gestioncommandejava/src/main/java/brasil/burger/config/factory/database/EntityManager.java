package brasil.burger.config.factory.database;
import brasil.burger.config.factory.SgbdName;
import java.util.Map;
import java.util.HashMap;

public final class EntityManager {
    private EntityManager() {}
    
    public static Map<String, String> persistenceUnit(SgbdName sgbdName) {
        switch (sgbdName) {
            case POSTGRESQL:
                return PersistenceUnitPostgresql();
            default:
                throw new IllegalArgumentException("UNKNOWN SGBD: " + sgbdName);
        }
    }
    
    private static final Map<String, String> PersistenceUnitPostgresql() {
        Map<String, String> config = new HashMap<>();
        config.put("driver", "org.postgresql.Driver");
        // URL JDBC corrigée pour Neon Postgres
        config.put("url", "jdbc:postgresql://ep-crimson-moon-a4zu2xk0-pooler.us-east-1.aws.neon.tech:5432/BrasilBurger?sslmode=require");
        config.put("user", "neondb_owner");
        config.put("pwd", "npg_AMPyBdwh04qr");
        return config;
    }
}

