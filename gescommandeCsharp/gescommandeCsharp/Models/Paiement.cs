using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace BrasilBurger.Models
{
    [Table("paiement")]
    public class Paiement
    {
        [Key]
        [Column("id")]
        public int Id { get; set; }

        [Required]
        [Column("date_paiement")]
        public DateTime DatePaiement { get; set; } = DateTime.Now;

        [Required]
        [Column("montant", TypeName = "decimal(10,2)")]
        public decimal Montant { get; set; }

        [Required]
        [Column("mode_paiement")]
        public ModePaiement ModePaiement { get; set; }

        [Column("commande_id")]
        [ForeignKey("Commande")]
        public int CommandeId { get; set; }

        public Commande Commande { get; set; } = null!;
    }
}