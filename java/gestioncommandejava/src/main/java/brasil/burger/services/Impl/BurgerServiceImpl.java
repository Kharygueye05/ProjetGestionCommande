package brasil.burger.services.Impl;
import brasil.burger.entity.Burger;
import brasil.burger.repository.BurgerRepository;
import brasil.burger.services.BurgerService;
import java.util.List;
import java.util.Optional;

public class BurgerServiceImpl implements BurgerService {
    private BurgerRepository burgerRepository;
    private static BurgerServiceImpl instance = null;

    private BurgerServiceImpl(BurgerRepository burgerRepository) {
        this.burgerRepository = burgerRepository;
    }

    public static BurgerServiceImpl getInstance(BurgerRepository burgerRepository) {
        if (instance == null) {
            instance = new BurgerServiceImpl(burgerRepository);
        }
        return instance;
    }

    @Override
    public boolean createBurger(Burger burger) {
        return this.burgerRepository.insert(burger) != 0;
    }

    @Override
    public List<Burger> getAllBurgers() {
        return this.burgerRepository.selectAll();
    }

    @Override
    public Optional<Burger> getBurgerById(int id) {
        return this.burgerRepository.selectById(id);
    }

    @Override
    public boolean updateBurger(Burger burger) {
        return this.burgerRepository.update(burger) != 0;
    }

    @Override
    public boolean archiveBurger(int id) {
        return this.burgerRepository.archive(id) != 0;
    }
}