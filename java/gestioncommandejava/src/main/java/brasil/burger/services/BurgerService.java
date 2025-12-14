package brasil.burger.services;
import brasil.burger.entity.Burger;
import java.util.List;
import java.util.Optional;

public interface BurgerService {
    boolean createBurger(Burger burger);
    List<Burger> getAllBurgers();
    Optional<Burger> getBurgerById(int id);
    boolean updateBurger(Burger burger);
    boolean archiveBurger(int id);
}