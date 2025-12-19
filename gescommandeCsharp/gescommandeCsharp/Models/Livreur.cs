using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace BrasilBurger.Models
{
    [Table("livreur")]
    public class Livreur
    {
        [Key]
        [Column("id")]
        [ForeignKey("User")]
        public int Id { get; set; }

        // Navigation property
        public User User { get; set; } = null!;
        
        // Collections
        public ICollection<Commande> Commandes { get; set; } = new List<Commande>();
    }
}