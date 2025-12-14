package brasil.burger.services.Impl;
import brasil.burger.entity.Menu;
import brasil.burger.entity.Burger;
import brasil.burger.entity.Complement;
import brasil.burger.repository.MenuRepository;
import brasil.burger.repository.BurgerRepository;
import brasil.burger.repository.ComplementRepository;
import brasil.burger.services.MenuService;
import java.util.List;
import java.util.Optional;

public class MenuServiceImpl implements MenuService {
    private MenuRepository menuRepository;
    private BurgerRepository burgerRepository;
    private ComplementRepository complementRepository;
    private static MenuServiceImpl instance = null;

    private MenuServiceImpl(MenuRepository menuRepository, BurgerRepository burgerRepository, ComplementRepository complementRepository) {
        this.menuRepository = menuRepository;
        this.burgerRepository = burgerRepository;
        this.complementRepository = complementRepository;
    }

    public static MenuServiceImpl getInstance(MenuRepository menuRepository, BurgerRepository burgerRepository, ComplementRepository complementRepository) {
        if (instance == null) {
            instance = new MenuServiceImpl(menuRepository, burgerRepository, complementRepository);
        }
        return instance;
    }

    @Override
    public boolean createMenu(Menu menu) {
        return this.menuRepository.insert(menu) != 0;
    }

    @Override
    public List<Menu> getAllMenus() {
        return this.menuRepository.selectAll();
    }

    @Override
    public Optional<Menu> getMenuById(int id) {
        return this.menuRepository.selectById(id);
    }

    @Override
    public boolean updateMenu(Menu menu) {
        return this.menuRepository.update(menu) != 0;
    }

    @Override
    public boolean archiveMenu(int id) {
        return this.menuRepository.archive(id) != 0;
    }

    @Override
    public double calculateMenuPrice(int idMenu) {
        Optional<Menu> menu = this.menuRepository.selectById(idMenu);
        if (!menu.isPresent()) {
            return 0.0;
        }
        
        Menu m = menu.get();
        double prixBurger = burgerRepository.selectById(m.getIdBurger())
            .map(Burger::getPrix).orElse(0.0);
        double prixBoisson = complementRepository.selectById(m.getIdComplementBoisson())
            .map(Complement::getPrix).orElse(0.0);
        double prixFrites = complementRepository.selectById(m.getIdComplementFrites())
            .map(Complement::getPrix).orElse(0.0);
        
        return prixBurger + prixBoisson + prixFrites;
    }
}
