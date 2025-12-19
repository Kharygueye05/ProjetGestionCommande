using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace BrasilBurger.Models
{
    [Table("gestionnaire")]
    public class Gestionnaire
    {
        [Key]
        [Column("id")]
        [ForeignKey("User")]
        public int Id { get; set; }

        [Required]
        [StringLength(150)]
        [Column("email")]
        public string Email { get; set; } = string.Empty;

        [Required]
        [Column("password")]
        public string Password { get; set; } = string.Empty;

        // Navigation property
        public User User { get; set; } = null!;
        
        // Collections
        public ICollection<Commande> Commandes { get; set; } = new List<Commande>();
    }
}