package brasil.burger.repository;
import brasil.burger.entity.Burger;
import java.util.List;
import java.util.Optional;

public interface BurgerRepository {
    int insert(Burger burger);
    List<Burger> selectAll();
    Optional<Burger> selectById(int id);
    int update(Burger burger);
    int archive(int id);
}