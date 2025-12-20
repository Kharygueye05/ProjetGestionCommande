using Microsoft.AspNetCore.Mvc;

namespace BrasilBurger.Controllers
{
    public class PanierController : Controller
    {
        private readonly ILogger<PanierController> _logger;

        public PanierController(ILogger<PanierController> logger)
        {
            _logger = logger;
        }

        [HttpGet]
        public IActionResult Index()
        {
            return View();
        }
    }
}