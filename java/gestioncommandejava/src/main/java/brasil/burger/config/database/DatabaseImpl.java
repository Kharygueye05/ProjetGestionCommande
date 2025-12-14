package brasil.burger.config.database;
import java.sql.*;
import java.util.*;

public class DatabaseImpl implements Database {
    private Connection connection;
    private static DatabaseImpl instance = null;

    private DatabaseImpl(String driver, String url, String user, String pwd) {
        this.connection = openConnection(driver, url, user, pwd);
    }

    private DatabaseImpl(Map<String, String> config) {
        String driver = config.get("driver");
        String url = config.get("url");
        String user = config.get("user");
        String pwd = config.get("pwd");
        
        // AJOUT DE LOGS POUR DEBUG
        System.out.println("=== TENTATIVE DE CONNEXION ===");
        System.out.println("Driver: " + driver);
        System.out.println("URL: " + url);
        System.out.println("User: " + user);
        
        this.connection = openConnection(driver, url, user, pwd);
        
        if (this.connection != null) {
            System.out.println("✅ Connexion réussie!");
        } else {
            System.out.println("❌ Échec de la connexion!");
        }
    }

    public static DatabaseImpl getInstance(Map<String, String> config) {
        if (instance == null) {
            instance = new DatabaseImpl(config);
        }
        return instance;
    }

    public static DatabaseImpl getInstance(String driver, String url, String user, String pwd) {
        if (instance == null) {
            instance = new DatabaseImpl(driver, url, user, pwd);
        }
        return instance;
    }

    @Override
    public Connection getConnection() {
        return this.connection;
    }

    @Override
    public boolean isConnected() {
        return this.getConnection() != null;
    }

    @Override
    public void closeConnection() {
        if (isConnected()) {
            try {
                this.connection.close();
                System.out.println("Connexion fermée");
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }
    }

    private Connection openConnection(String driver, String url, String user, String pwd) {
        try {
            System.out.println("Chargement du driver: " + driver);
            Class.forName(driver);
            
            System.out.println("Connexion à: " + url);
            Connection conn = DriverManager.getConnection(url, user, pwd);
            
            System.out.println("✅ Driver chargé et connexion établie!");
            return conn;
            
        } catch (ClassNotFoundException e) {
            System.err.println("❌ ERREUR: Driver PostgreSQL non trouvé!");
            System.err.println("Vérifiez que postgresql est dans pom.xml");
            e.printStackTrace();
        } catch (SQLException e) {
            System.err.println("❌ ERREUR SQL lors de la connexion:");
            System.err.println("Message: " + e.getMessage());
            System.err.println("SQLState: " + e.getSQLState());
            System.err.println("ErrorCode: " + e.getErrorCode());
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public <T> List<T> fetchAll(PreparedStatement ps, Convert<T> convert) throws SQLException {
        List<T> data = new ArrayList<>();
        ResultSet rs = ps.executeQuery();
        while (rs.next()) {
            data.add(convert.toEntity(rs));
        }
        return data;
    }

    @Override
    public <T> Optional<T> fetch(PreparedStatement ps, Convert<T> convert) throws SQLException {
        ResultSet rs = ps.executeQuery();
        T data = null;
        if (rs.next()) {
            data = convert.toEntity(rs);
        }
        return Optional.ofNullable(data);
    }
}

