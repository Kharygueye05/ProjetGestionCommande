package brasil.burger.services;
import brasil.burger.entity.Menu;
import java.util.List;
import java.util.Optional;

public interface MenuService {
    boolean createMenu(Menu menu);
    List<Menu> getAllMenus();
    Optional<Menu> getMenuById(int id);
    boolean updateMenu(Menu menu);
    boolean archiveMenu(int id);
    double calculateMenuPrice(int idMenu);
}