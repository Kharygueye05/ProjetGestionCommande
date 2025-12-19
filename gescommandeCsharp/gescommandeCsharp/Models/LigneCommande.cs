using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace BrasilBurger.Models
{
    [Table("ligne_commande")]
    public class LigneCommande
    {
        [Key]
        [Column("id")]
        public int Id { get; set; }

        [Required]
        [Column("quantite")]
        public int Quantite { get; set; }

        [Required]
        [Column("prix_unitaire", TypeName = "decimal(10,2)")]
        public decimal PrixUnitaire { get; set; }

        [Required]
        [Column("commande_id")]
        [ForeignKey("Commande")]
        public int CommandeId { get; set; }

        [Required]
        [Column("produit_id")]
        [ForeignKey("Burger")]
        public int ProduitId { get; set; }

    
        public Commande Commande { get; set; } = null!;
        public Burger Burger { get; set; } = null!;
    }
}