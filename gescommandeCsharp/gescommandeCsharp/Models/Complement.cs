using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace BrasilBurger.Models
{
    [Table("complement")]
    public class Complement
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

        [Column("archive")]
        public bool Archive { get; set; } = false;

        [Required]
        [Column("prix", TypeName = "decimal(10,2)")]
        public decimal Prix { get; set; }

        [Required]
        [StringLength(50)]
        [Column("type")]
        public string Type { get; set; } = string.Empty;

  
        public ICollection<Menu> MenusBoisson { get; set; } = new List<Menu>();
        
    
        public ICollection<Menu> MenusFrites { get; set; } = new List<Menu>();
    }
}