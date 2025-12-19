using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace BrasilBurger.Models
{
    [Table("users")]
    public class User
    {
        [Key]
        [Column("id")]
        public int Id { get; set; }

        [Required]
        [StringLength(100)]
        [Column("nom")]
        public string Nom { get; set; } = string.Empty;

        [Required]
        [StringLength(100)]
        [Column("prenom")]
        public string Prenom { get; set; } = string.Empty;

        [Required]
        [StringLength(20)]
        [Column("telephone")]
        public string Telephone { get; set; } = string.Empty;
    }
}