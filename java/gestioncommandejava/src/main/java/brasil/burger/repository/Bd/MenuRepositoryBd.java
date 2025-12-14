package brasil.burger.repository.Bd;
import brasil.burger.config.database.Database;
import brasil.burger.entity.Menu;
import brasil.burger.repository.MenuRepository;
import java.sql.*;
import java.util.*;

public class MenuRepositoryBd implements MenuRepository {
    private static MenuRepositoryBd instance = null;
    private Database database;

    private MenuRepositoryBd(Database database) {
        this.database = database;
    }

    public static MenuRepositoryBd getInstance(Database database) {
        if (instance == null) {
            instance = new MenuRepositoryBd(database);
        }
        return instance;
    }

    @Override
    public int insert(Menu menu) {
        Connection conn = database.getConnection();
        try {
            PreparedStatement ps = conn.prepareStatement(
                "INSERT INTO menu (nom, image, archive, id_burger, id_complement_boisson, id_complement_frites) VALUES (?, ?, ?, ?, ?, ?)"
            );
            ps.setString(1, menu.getNom());
            ps.setString(2, menu.getImage());
            ps.setBoolean(3, menu.isArchive());
            ps.setInt(4, menu.getIdBurger());
            ps.setInt(5, menu.getIdComplementBoisson());
            ps.setInt(6, menu.getIdComplementFrites());
            return ps.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return 0;
    }

    @Override
    public List<Menu> selectAll() {
        Connection conn = database.getConnection();
        try {
            PreparedStatement ps = conn.prepareStatement(
                "SELECT id, nom, image, archive, id_burger, id_complement_boisson, id_complement_frites FROM menu WHERE archive = false"
            );
            return database.fetchAll(ps, this::toEntity);
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return Collections.emptyList();
    }

    @Override
    public Optional<Menu> selectById(int id) {
        Connection conn = database.getConnection();
        try {
            PreparedStatement ps = conn.prepareStatement(
                "SELECT id, nom, image, archive, id_burger, id_complement_boisson, id_complement_frites FROM menu WHERE id = ?"
            );
            ps.setInt(1, id);
            return database.fetch(ps, this::toEntity);
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return Optional.empty();
    }

    @Override
    public int update(Menu menu) {
        Connection conn = database.getConnection();
        try {
            PreparedStatement ps = conn.prepareStatement(
                "UPDATE menu SET nom = ?, image = ?, id_burger = ?, id_complement_boisson = ?, id_complement_frites = ? WHERE id = ?"
            );
            ps.setString(1, menu.getNom());
            ps.setString(2, menu.getImage());
            ps.setInt(3, menu.getIdBurger());
            ps.setInt(4, menu.getIdComplementBoisson());
            ps.setInt(5, menu.getIdComplementFrites());
            ps.setInt(6, menu.getId());
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
                "UPDATE menu SET archive = true WHERE id = ?"
            );
            ps.setInt(1, id);
            return ps.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return 0;
    }

    private Menu toEntity(ResultSet rs) throws SQLException {
        Menu m = new Menu();
        m.setId(rs.getInt("id"));
        m.setNom(rs.getString("nom"));
        m.setImage(rs.getString("image"));
        m.setArchive(rs.getBoolean("archive"));
        m.setIdBurger(rs.getInt("id_burger"));
        m.setIdComplementBoisson(rs.getInt("id_complement_boisson"));
        m.setIdComplementFrites(rs.getInt("id_complement_frites"));
        return m;
    }
}