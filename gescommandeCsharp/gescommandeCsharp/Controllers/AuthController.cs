using BrasilBurger.Data;
using BrasilBurger.Models;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace BrasilBurger.Controllers
{
    public class AuthController : Controller
    {
        private readonly BrasilBurgerDbContext _context;
        private readonly ILogger<AuthController> _logger;

        public AuthController(BrasilBurgerDbContext context, ILogger<AuthController> logger)
        {
            _context = context;
            _logger = logger;
        }

        [HttpGet]
        public IActionResult Login(string? returnUrl = null)
        {
            ViewBag.ReturnUrl = returnUrl;
            return View();
        }

        [HttpPost]
        public async Task<IActionResult> Login(string telephone, string password, string? returnUrl = null)
        {
            try
            {
                var client = await _context.Clients
                    .Include(c => c.User)
                    .FirstOrDefaultAsync(c => c.User.Telephone == telephone);

                if (client == null)
                {
                    ViewBag.Error = "Numéro de téléphone ou mot de passe incorrect";
                    ViewBag.ReturnUrl = returnUrl;
                    return View();
                }

                if (client.Password != password)
                {
                    ViewBag.Error = "Numéro de téléphone ou mot de passe incorrect";
                    ViewBag.ReturnUrl = returnUrl;
                    return View();
                }

                HttpContext.Session.SetInt32("ClientId", client.Id);
                HttpContext.Session.SetString("ClientNom", client.User.Nom);
                HttpContext.Session.SetString("ClientPrenom", client.User.Prenom);
                HttpContext.Session.SetString("ClientTelephone", client.User.Telephone);

                if (!string.IsNullOrEmpty(returnUrl) && Url.IsLocalUrl(returnUrl))
                {
                    return Redirect(returnUrl);
                }

                return RedirectToAction("Index", "Catalogue");
            }
            catch (Exception ex)
            {
                _logger.LogError(ex, "Erreur lors de la connexion");
                ViewBag.Error = "Une erreur s'est produite. Veuillez réessayer.";
                ViewBag.ReturnUrl = returnUrl;
                return View();
            }
        }

        [HttpGet]
        public IActionResult Register(string? returnUrl = null)
        {
            ViewBag.ReturnUrl = returnUrl;
            return View();
        }

        [HttpPost]
        public async Task<IActionResult> Register(string nom, string prenom, string telephone, string adresse, string password, string confirmPassword, string? returnUrl = null)
        {
            try
            {
                if (password != confirmPassword)
                {
                    ViewBag.Error = "Les mots de passe ne correspondent pas";
                    ViewBag.ReturnUrl = returnUrl;
                    return View();
                }

                var existingUser = await _context.Users
                    .FirstOrDefaultAsync(u => u.Telephone == telephone);

                if (existingUser != null)
                {
                    ViewBag.Error = "Ce numéro de téléphone est déjà utilisé";
                    ViewBag.ReturnUrl = returnUrl;
                    return View();
                }

                var user = new User
                {
                    Nom = nom,
                    Prenom = prenom,
                    Telephone = telephone
                };

                _context.Users.Add(user);
                await _context.SaveChangesAsync();

                var client = new Client
                {
                    Id = user.Id,
                    Adresse = adresse,
                    Password = password 
                };

                _context.Clients.Add(client);
                await _context.SaveChangesAsync();

                HttpContext.Session.SetInt32("ClientId", client.Id);
                HttpContext.Session.SetString("ClientNom", user.Nom);
                HttpContext.Session.SetString("ClientPrenom", user.Prenom);
                HttpContext.Session.SetString("ClientTelephone", user.Telephone);

                if (!string.IsNullOrEmpty(returnUrl) && Url.IsLocalUrl(returnUrl))
                {
                    return Redirect(returnUrl);
                }

                return RedirectToAction("Index", "Catalogue");
            }
            catch (Exception ex)
            {
                _logger.LogError(ex, "Erreur lors de l'inscription");
                ViewBag.Error = "Une erreur s'est produite. Veuillez réessayer.";
                ViewBag.ReturnUrl = returnUrl;
                return View();
            }
        }

        public IActionResult Logout()
        {
            HttpContext.Session.Clear();
            return RedirectToAction("Index", "Catalogue");
        }
    }
}