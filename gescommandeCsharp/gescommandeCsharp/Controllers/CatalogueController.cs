using BrasilBurger.Services;
using Microsoft.AspNetCore.Mvc;

namespace BrasilBurger.Controllers
{
    public class CatalogueController : Controller
    {
        private readonly ICatalogueService _catalogueService;
        private readonly ILogger<CatalogueController> _logger;

        public CatalogueController(ICatalogueService catalogueService, ILogger<CatalogueController> logger)
        {
            _catalogueService = catalogueService;
            _logger = logger;
        }

        [HttpGet]
        public IActionResult Index(string? filtre)
        {
            try
            {
                ViewBag.FiltreActuel = filtre ?? "tous";

                switch (filtre?.ToLower())
                {
                    case "burgers":
                        ViewBag.Burgers = _catalogueService.GetAllBurgers();
                        ViewBag.Menus = new List<BrasilBurger.Models.Menu>();
                        ViewBag.Complements = new List<BrasilBurger.Models.Complement>();
                        break;

                    case "menus":
                        ViewBag.Burgers = new List<BrasilBurger.Models.Burger>();
                        ViewBag.Menus = _catalogueService.GetAllMenus();
                        ViewBag.Complements = new List<BrasilBurger.Models.Complement>();
                        break;

                    case "complements":
                        ViewBag.Burgers = new List<BrasilBurger.Models.Burger>();
                        ViewBag.Menus = new List<BrasilBurger.Models.Menu>();
                        ViewBag.Complements = _catalogueService.GetAllComplements();
                        break;

                    default: 
                        ViewBag.Burgers = _catalogueService.GetAllBurgers();
                        ViewBag.Menus = _catalogueService.GetAllMenus();
                        ViewBag.Complements = _catalogueService.GetAllComplements();
                        break;
                }

                return View();
            }
            catch (Exception ex)
            {
                _logger.LogError(ex, "Erreur lors de l'affichage du catalogue.");
                return View("Error");
            }
        }

        [HttpGet]
        public IActionResult DetailsBurger(int id)
        {
            try
            {
                var burger = _catalogueService.GetBurgerById(id);
                if (burger == null)
                {
                    return NotFound();
                }

                ViewBag.Complements = _catalogueService.GetAllComplements();

                return View(burger);
            }
            catch (Exception ex)
            {
                _logger.LogError(ex, $"Erreur lors de l'affichage des détails du burger {id}.");
                return View("Error");
            }
        }

        [HttpGet]
        public IActionResult DetailsMenu(int id)
        {
            try
            {
                var menu = _catalogueService.GetMenuById(id);
                if (menu == null)
                {
                    return NotFound();
                }

                return View(menu);
            }
            catch (Exception ex)
            {
                _logger.LogError(ex, $"Erreur lors de l'affichage des détails du menu {id}.");
                return View("Error");
            }
        }
    }
}