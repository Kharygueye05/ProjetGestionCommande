using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace BrasilBurger.Models
{
    [Table("menu")]
    public class Menu
    {
        [Key]
        [Column("id")]
        public int Id { get; set; }

        [Required]
        [StringLength(100)]
        [Column("nom")]
        public string Nom { get; set; } = string.Empty;

        [Column("image")]
        [StringLength(255)]
        public string? Image { get; set; }

        [Column("description")]
        public string? Description { get; set; }

        [Column("archive")]
        public bool Archive { get; set; } = false;

        [Column("id_burger")]
        [ForeignKey("Burger")]
        public int? IdBurger { get; set; }

        [Column("id_complement_boisson")]
        [ForeignKey("ComplementBoisson")]
        public int? IdComplementBoisson { get; set; }

        [Column("id_complement_frites")]
        [ForeignKey("ComplementFrites")]
        public int? IdComplementFrites { get; set; }

        // Navigation properties
        public Burger? Burger { get; set; }
        public Complement? ComplementBoisson { get; set; }
        public Complement? ComplementFrites { get; set; }

        // Propriété calculée pour le prix du menu
        [NotMapped]
        public decimal Prix
        {
            get
            {
                decimal total = 0;
                if (Burger != null)
                {
                    total += Burger.Prix;
                }
                if (ComplementBoisson != null)
                {
                    total += ComplementBoisson.Prix;
                }
                if (ComplementFrites != null)
                {
                    total += ComplementFrites.Prix;
                }
                return total;
            }
        }
    }
}