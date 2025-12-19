using BrasilBurger.Data;
using BrasilBurger.Models;
using Microsoft.EntityFrameworkCore;

namespace BrasilBurger.Services
{
    public class CatalogueService : ICatalogueService
    {
        private readonly BrasilBurgerDbContext _context;
        private readonly ILogger<CatalogueService> _logger;

        public CatalogueService(BrasilBurgerDbContext context, ILogger<CatalogueService> logger)
        {
            _context = context;
            _logger = logger;
        }

        public IEnumerable<Burger> GetAllBurgers()
        {
            try
            {
                return _context.Burgers
                    .Where(b => !b.Archive)
                    .OrderBy(b => b.Nom)
                    .ToList();
            }
            catch (Exception ex)
            {
                _logger.LogError(ex, "Erreur lors de la récupération des burgers.");
                throw;
            }
        }

        public IEnumerable<Menu> GetAllMenus()
        {
            try
            {
                return _context.Menus
                    .Include(m => m.Burger)
                    .Include(m => m.ComplementBoisson)
                    .Include(m => m.ComplementFrites)
                    .Where(m => !m.Archive)
                    .OrderBy(m => m.Nom)
                    .ToList();
            }
            catch (Exception ex)
            {
                _logger.LogError(ex, "Erreur lors de la récupération des menus.");
                throw;
            }
        }

        public IEnumerable<Complement> GetAllComplements()
        {
            try
            {
                return _context.Complements
                    .Where(c => !c.Archive)
                    .OrderBy(c => c.Type)
                    .ThenBy(c => c.Nom)
                    .ToList();
            }
            catch (Exception ex)
            {
                _logger.LogError(ex, "Erreur lors de la récupération des compléments.");
                throw;
            }
        }

        public Burger? GetBurgerById(int id)
        {
            try
            {
                return _context.Burgers
                    .FirstOrDefault(b => b.Id == id && !b.Archive);
            }
            catch (Exception ex)
            {
                _logger.LogError(ex, $"Erreur lors de la récupération du burger avec l'ID {id}.");
                throw;
            }
        }

        public Menu? GetMenuById(int id)
        {
            try
            {
                return _context.Menus
                    .Include(m => m.Burger)
                    .Include(m => m.ComplementBoisson)
                    .Include(m => m.ComplementFrites)
                    .FirstOrDefault(m => m.Id == id && !m.Archive);
            }
            catch (Exception ex)
            {
                _logger.LogError(ex, $"Erreur lors de la récupération du menu avec l'ID {id}.");
                throw;
            }
        }
    }
}