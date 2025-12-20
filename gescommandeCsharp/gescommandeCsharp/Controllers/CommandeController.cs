using BrasilBurger.Data;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using BrasilBurger.Models;
using System.Linq;

namespace BrasilBurger.Controllers
{
    public class CommandeController : Controller
    {
        private readonly BrasilBurgerDbContext _context;
        private readonly ILogger<CommandeController> _logger;

        public CommandeController(BrasilBurgerDbContext context, ILogger<CommandeController> logger)
        {
            _context = context;
            _logger = logger;
        }

        [HttpGet]
        public async Task<IActionResult> Checkout()
        {
            var clientId = HttpContext.Session.GetInt32("ClientId");
            if (!clientId.HasValue)
            {
                return RedirectToAction("Login", "Auth", new { returnUrl = Url.Action("Checkout") });
            }

            var zones = await _context.Zones.ToListAsync();
            ViewBag.Zones = zones;

            return View();
        }

        [HttpPost]
        public IActionResult SaveCartToSession([FromBody] List<CartItemData> cart)
        {
            try
            {
                if (cart == null || cart.Count == 0)
                {
                    return Json(new { success = false, message = "Le panier est vide" });
                }

                var cartJson = System.Text.Json.JsonSerializer.Serialize(cart);
                HttpContext.Session.SetString("Cart", cartJson);

                return Json(new { success = true });
            }
            catch (Exception ex)
            {
                _logger.LogError(ex, "Erreur lors de la sauvegarde du panier en session");
                return Json(new { success = false, message = "Erreur lors de la sauvegarde du panier" });
            }
        }

        [HttpPost]
        public async Task<IActionResult> ProcessCheckout(string paymentMethod, int? zoneId, decimal deliveryFee)
        {
            try
            {
                var clientId = HttpContext.Session.GetInt32("ClientId");
                if (!clientId.HasValue)
                {
                    TempData["Error"] = "Vous devez être connecté pour passer une commande.";
                    return RedirectToAction("Login", "Auth", new { returnUrl = Url.Action("Checkout") });
                }

                var cartJson = HttpContext.Session.GetString("Cart");
                if (string.IsNullOrEmpty(cartJson))
                {
                    TempData["Error"] = "Votre panier est vide. Veuillez ajouter des articles au panier.";
                    return RedirectToAction("Index", "Panier");
                }

                List<CartItemData>? cart = null;
                try
                {
                    cart = System.Text.Json.JsonSerializer.Deserialize<List<CartItemData>>(cartJson);
                }
                catch (Exception ex)
                {
                    _logger.LogError(ex, "Erreur lors de la désérialisation du panier");
                    TempData["Error"] = "Erreur lors de la lecture du panier. Veuillez réessayer.";
                    return RedirectToAction("Index", "Panier");
                }

                if (cart == null || cart.Count == 0)
                {
                    TempData["Error"] = "Votre panier est vide.";
                    return RedirectToAction("Index", "Panier");
                }

                foreach (var item in cart)
                {
                    if (item == null)
                    {
                        TempData["Error"] = "Le panier contient des données invalides.";
                        return RedirectToAction("Index", "Panier");
                    }
                }

                decimal montantTotal = 0;
                foreach (var item in cart)
                {
                    if (item.Total > 0)
                    {
                        montantTotal += item.Total;
                    }
                }

                if (zoneId.HasValue && deliveryFee > 0)
                {
                    montantTotal += deliveryFee;
                }

                var firstItem = cart[0];
                if (firstItem == null || string.IsNullOrEmpty(firstItem.CommandType))
                {
                    TempData["Error"] = "Type de commande manquant dans le panier.";
                    return RedirectToAction("Index", "Panier");
                }

                TypeCommande typeCommande = TypeCommande.sur_place;
                if (firstItem.CommandType == "a_emporter")
                {
                    typeCommande = TypeCommande.a_emporter;
                }
                else if (firstItem.CommandType == "livraison")
                {
                    typeCommande = TypeCommande.livraison;
                }

                var commande = new Commande
                {
                    DateCommande = DateTime.Now,
                    TypeCommande = typeCommande,
                    Etat = EtatCommande.reçue,
                    Montant = montantTotal,
                    ClientId = clientId.Value,
                    ZoneId = zoneId
                };

                _context.Commandes.Add(commande);
                await _context.SaveChangesAsync();

                foreach (var item in cart)
                {
                    if (item == null) continue;

                    if (item.Type == "burger")
                    {
                        decimal prixUnitaire = item.Price;
                        if (item.Complements != null && item.Complements.Any())
                        {
                            prixUnitaire += item.Complements.Where(c => c != null).Sum(c => c.Price);
                        }

                        var ligneCommande = new LigneCommande
                        {
                            Quantite = item.Quantity > 0 ? item.Quantity : 1,
                            PrixUnitaire = prixUnitaire > 0 ? prixUnitaire : item.Price,
                            CommandeId = commande.Id,
                            ProduitId = item.Id > 0 ? item.Id : 0
                        };

                        _context.LignesCommande.Add(ligneCommande);
                    }
                    else if (item.Type == "menu")
                    {
                        var ligneCommande = new LigneCommande
                        {
                            Quantite = item.Quantity > 0 ? item.Quantity : 1,
                            PrixUnitaire = item.Price > 0 ? item.Price : 0,
                            CommandeId = commande.Id,
                            ProduitId = item.Id > 0 ? item.Id : 0
                        };

                        _context.LignesCommande.Add(ligneCommande);
                    }
                }

                await _context.SaveChangesAsync();

                if (string.IsNullOrEmpty(paymentMethod))
                {
                    paymentMethod = "wave";
                }

                var paiement = new Paiement
                {
                    DatePaiement = DateTime.Now,
                    Montant = montantTotal,
                    ModePaiement = paymentMethod == "wave" ? ModePaiement.wave : ModePaiement.om,
                    CommandeId = commande.Id
                };

                _context.Paiements.Add(paiement);
                await _context.SaveChangesAsync();

                HttpContext.Session.Remove("Cart");

                TempData["Success"] = $"Commande #{commande.Id} passée avec succès !";
                return RedirectToAction("MesCommandes");
            }
            catch (Exception ex)
            {
                _logger.LogError(ex, "Erreur lors du traitement de la commande: {Message}", ex.Message);
                TempData["Error"] = $"Erreur lors du traitement de la commande: {ex.Message}";
                return RedirectToAction("Checkout");
            }
        }

        [HttpGet]
        public async Task<IActionResult> MesCommandes()
        {
            var clientId = HttpContext.Session.GetInt32("ClientId");
            if (!clientId.HasValue)
            {
                return RedirectToAction("Login", "Auth", new { returnUrl = Url.Action("MesCommandes") });
            }

            var commandes = await _context.Commandes
                .Where(c => c.ClientId == clientId.Value)
                .OrderByDescending(c => c.DateCommande)
                .ToListAsync();

            return View(commandes);
        }
    }
}