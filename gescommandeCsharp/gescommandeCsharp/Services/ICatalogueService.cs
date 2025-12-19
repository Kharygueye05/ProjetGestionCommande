using BrasilBurger.Models;

namespace BrasilBurger.Services
{
    public interface ICatalogueService
    {
        IEnumerable<Burger> GetAllBurgers();
        IEnumerable<Menu> GetAllMenus();
        IEnumerable<Complement> GetAllComplements();
        Burger? GetBurgerById(int id);
        Menu? GetMenuById(int id);
    }
}
