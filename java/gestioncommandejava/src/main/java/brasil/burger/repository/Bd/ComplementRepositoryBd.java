package brasil.burger.repository.Bd;
import brasil.burger.config.database.Database;
import brasil.burger.entity.Complement;
import brasil.burger.repository.ComplementRepository;
import java.sql.*;
import java.util.*;

public class ComplementRepositoryBd implements ComplementRepository {
    private static ComplementRepositoryBd instance = null;
    private Database database;

    private ComplementRepositoryBd(Database database) {
        this.database = database;
    }

    public static ComplementRepositoryBd getInstance(Database database) {
        if (instance == null) {
            instance = new ComplementRepositoryBd(database);
        }
        return instance;
    }

    @Override
    public int insert(Complement complement) {
        Connection conn = database.getConnection();
        try {
            PreparedStatement ps = conn.prepareStatement(
                "INSERT INTO complement (nom, image, archive, prix, type) VALUES (?, ?, ?, ?, ?)"
            );
            ps.setString(1, complement.getNom());
            ps.setString(2, complement.getImage());
            ps.setBoolean(3, complement.isArchive());
            ps.setDouble(4, complement.getPrix());
            ps.setString(5, complement.getType());
            return ps.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return 0;
    }

    @Override
    public List<Complement> selectAll() {
        Connection conn = database.getConnection();
        try {
            PreparedStatement ps = conn.prepareStatement(
                "SELECT id, nom, image, archive, prix, type FROM complement WHERE archive = false"
            );
            return database.fetchAll(ps, this::toEntity);
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return Collections.emptyList();
    }

    @Override
    public Optional<Complement> selectById(int id) {
        Connection conn = database.getConnection();
        try {
            PreparedStatement ps = conn.prepareStatement(
                "SELECT id, nom, image, archive, prix, type FROM complement WHERE id = ?"
            );
            ps.setInt(1, id);
            return database.fetch(ps, this::toEntity);
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return Optional.empty();
    }

    @Override
    public int update(Complement complement) {
        Connection conn = database.getConnection();
        try {
            PreparedStatement ps = conn.prepareStatement(
                "UPDATE complement SET nom = ?, image = ?, prix = ?, type = ? WHERE id = ?"
            );
            ps.setString(1, complement.getNom());
            ps.setString(2, complement.getImage());
            ps.setDouble(3, complement.getPrix());
            ps.setString(4, complement.getType());
            ps.setInt(5, complement.getId());
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
                "UPDATE complement SET archive = true WHERE id = ?"
            );
            ps.setInt(1, id);
            return ps.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return 0;
    }

    private Complement toEntity(ResultSet rs) throws SQLException {
        Complement c = new Complement();
        c.setId(rs.getInt("id"));
        c.setNom(rs.getString("nom"));
        c.setImage(rs.getString("image"));
        c.setArchive(rs.getBoolean("archive"));
        c.setPrix(rs.getDouble("prix"));
        c.setType(rs.getString("type"));
        return c;
    }
    @Override
public List<Complement> selectByType(String type) {
    Connection conn = database.getConnection();
    try {
        PreparedStatement ps = conn.prepareStatement(
            "SELECT id, nom, image, archive, prix, type FROM complement WHERE archive = false AND type = ?"
        );
        ps.setString(1, type);
        return database.fetchAll(ps, this::toEntity);
    } catch (SQLException e) {
        e.printStackTrace();
    }
    return Collections.emptyList();
}
}
