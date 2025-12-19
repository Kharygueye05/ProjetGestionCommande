using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace BrasilBurger.Models
{
    [Table("commande")]
    public class Commande
    {
        [Key]
        [Column("id")]
        public int Id { get; set; }

        [Required]
        [Column("date_commande")]
        public DateTime DateCommande { get; set; } = DateTime.Now;

        [Required]
        [Column("type_commande")]
        public TypeCommande TypeCommande { get; set; }

        [Required]
        [Column("etat")]
        public EtatCommande Etat { get; set; }

        [Required]
        [Column("montant", TypeName = "decimal(10,2)")]
        public decimal Montant { get; set; }

        [Required]
        [Column("client_id")]
        [ForeignKey("Client")]
        public int ClientId { get; set; }

        [Column("gestionnaire_id")]
        [ForeignKey("Gestionnaire")]
        public int? GestionnaireId { get; set; }

        [Column("livreur_id")]
        [ForeignKey("Livreur")]
        public int? LivreurId { get; set; }

        [Column("zone_id")]
        [ForeignKey("Zone")]
        public int? ZoneId { get; set; }

        public Client Client { get; set; } = null!;
        public Gestionnaire? Gestionnaire { get; set; }
        public Livreur? Livreur { get; set; }
        public Zone? Zone { get; set; }
        
        public ICollection<LigneCommande> LignesCommande { get; set; } = new List<LigneCommande>();
        public Paiement? Paiement { get; set; }
    }
}