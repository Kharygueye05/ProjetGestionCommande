package brasil.burger.repository;
import brasil.burger.entity.Menu;
import java.util.List;
import java.util.Optional;

public interface MenuRepository {
    int insert(Menu menu);
    List<Menu> selectAll();
    Optional<Menu> selectById(int id);
    int update(Menu menu);
    int archive(int id);
}