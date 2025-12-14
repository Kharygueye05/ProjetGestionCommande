package brasil.burger.repository.Bd;
import brasil.burger.config.database.Database;
import brasil.burger.entity.Burger;
import brasil.burger.repository.BurgerRepository;
import java.sql.*;
import java.util.*;

public class BurgerRepositoryBd implements BurgerRepository {
    private static BurgerRepositoryBd instance = null;
    private Database database;

    private BurgerRepositoryBd(Database database) {
        this.database = database;
    }

    public static BurgerRepositoryBd getInstance(Database database) {
        if (instance == null) {
            instance = new BurgerRepositoryBd(database);
        }
        return instance;
    }

    @Override
    public int insert(Burger burger) {
        Connection conn = database.getConnection();
        try {
            PreparedStatement ps = conn.prepareStatement(
                "INSERT INTO burger (nom, image, archive, prix) VALUES (?, ?, ?, ?)"
            );
            ps.setString(1, burger.getNom());
            ps.setString(2, burger.getImage());
            ps.setBoolean(3, burger.isArchive());
            ps.setDouble(4, burger.getPrix());
            return ps.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return 0;
    }

    @Override
    public List<Burger> selectAll() {
        Connection conn = database.getConnection();
        try {
            PreparedStatement ps = conn.prepareStatement(
                "SELECT id, nom, image, archive, prix FROM burger WHERE archive = false"
            );
            return database.fetchAll(ps, this::toEntity);
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return Collections.emptyList();
    }

    @Override
    public Optional<Burger> selectById(int id) {
        Connection conn = database.getConnection();
        try {
            PreparedStatement ps = conn.prepareStatement(
                "SELECT id, nom, image, archive, prix FROM burger WHERE id = ?"
            );
            ps.setInt(1, id);
            return database.fetch(ps, this::toEntity);
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return Optional.empty();
    }

    @Override
    public int update(Burger burger) {
        Connection conn = database.getConnection();
        try {
            PreparedStatement ps = conn.prepareStatement(
                "UPDATE burger SET nom = ?, image = ?, prix = ? WHERE id = ?"
            );
            ps.setString(1, burger.getNom());
            ps.setString(2, burger.getImage());
            ps.setDouble(3, burger.getPrix());
            ps.setInt(4, burger.getId());
            return ps.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return 0;
    }

    @Override
    public int archive(int id) {
        Connection conn = database.getConnection();
        try {
            PreparedStatement ps = conn.prepareStatement(
                "UPDATE burger SET archive = true WHERE id = ?"
            );
            ps.setInt(1, id);
            return ps.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return 0;
    }

    private Burger toEntity(ResultSet rs) throws SQLException {
        Burger b = new Burger();
        b.setId(rs.getInt("id"));
        b.setNom(rs.getString("nom"));
        b.setImage(rs.getString("image"));
        b.setArchive(rs.getBoolean("archive"));
        b.setPrix(rs.getDouble("prix"));
        return b;
    }
}
