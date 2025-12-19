using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace BrasilBurger.Models
{
    [Table("burger")]
    public class Burger
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

        [Required]
        [Column("prix", TypeName = "decimal(10,2)")]
        public decimal Prix { get; set; }

        public ICollection<Menu> Menus { get; set; } = new List<Menu>();
    }
}