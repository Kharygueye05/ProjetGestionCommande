using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace BrasilBurger.Models
{
    [Table("zone")]
    public class Zone
    {
        [Key]
        [Column("id")]
        public int Id { get; set; }

        [Required]
        [StringLength(100)]
        [Column("nom_zone")]
        public string NomZone { get; set; } = string.Empty;

        [Required]
        [Column("prix_livraison", TypeName = "decimal(10,2)")]
        public decimal PrixLivraison { get; set; }

        [Column("quartiers")]
        public string? Quartiers { get; set; }

        public ICollection<Commande> Commandes { get; set; } = new List<Commande>();
    }
}