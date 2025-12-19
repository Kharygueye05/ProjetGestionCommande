using Microsoft.AspNetCore.Mvc;
using BrasilBurger.Services;
using BrasilBurger.Models;

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
                        ViewBag.Menus = new List<Menu>();
                        ViewBag.Complements = new List<Complement>();
                        break;

                    case "menus":
                        ViewBag.Burgers = new List<Burger>();
                        ViewBag.Menus = _catalogueService.GetAllMenus();
                        ViewBag.Complements = new List<Complement>();
                        break;

                    case "complements":
                        ViewBag.Burgers = new List<Burger>();
                        ViewBag.Menus = new List<Menu>();
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