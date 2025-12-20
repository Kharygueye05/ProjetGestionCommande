using Microsoft.EntityFrameworkCore;
using BrasilBurger.Models;

namespace BrasilBurger.Data
{
    public class BrasilBurgerDbContext : DbContext
    {
        public BrasilBurgerDbContext(DbContextOptions<BrasilBurgerDbContext> options) 
            : base(options)
        {
        }

        public DbSet<User> Users { get; set; } = null!;
        public DbSet<Client> Clients { get; set; } = null!;
        public DbSet<Gestionnaire> Gestionnaires { get; set; } = null!;
        public DbSet<Livreur> Livreurs { get; set; } = null!;
        public DbSet<Burger> Burgers { get; set; } = null!;
        public DbSet<Menu> Menus { get; set; } = null!;
        public DbSet<Complement> Complements { get; set; } = null!;
        public DbSet<Zone> Zones { get; set; } = null!;
        public DbSet<Commande> Commandes { get; set; } = null!;
        public DbSet<LigneCommande> LignesCommande { get; set; } = null!;
        public DbSet<Paiement> Paiements { get; set; } = null!;

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            base.OnModelCreating(modelBuilder);

            modelBuilder.HasPostgresEnum<TypeCommande>("type_commande");
            modelBuilder.HasPostgresEnum<EtatCommande>("etat_commande");
            modelBuilder.HasPostgresEnum<ModePaiement>("mode_paiement");

            modelBuilder.Entity<User>()
                .ToTable("users")
                .HasKey(u => u.Id);

            modelBuilder.Entity<Client>()
                .ToTable("client")
                .HasOne(c => c.User)
                .WithOne()
                .HasForeignKey<Client>(c => c.Id)
                .OnDelete(DeleteBehavior.Cascade);

            modelBuilder.Entity<Gestionnaire>()
                .ToTable("gestionnaire")
                .HasOne(g => g.User)
                .WithOne()
                .HasForeignKey<Gestionnaire>(g => g.Id)
                .OnDelete(DeleteBehavior.Cascade);

            modelBuilder.Entity<Livreur>()
                .ToTable("livreur")
                .HasOne(l => l.User)
                .WithOne()
                .HasForeignKey<Livreur>(l => l.Id)
                .OnDelete(DeleteBehavior.Cascade);

            modelBuilder.Entity<Burger>()
                .ToTable("burger")
                .HasKey(b => b.Id);

            modelBuilder.Entity<Burger>()
                .Property(b => b.Prix)
                .HasColumnType("decimal(10,2)");

            modelBuilder.Entity<Complement>()
                .ToTable("complement")
                .HasKey(c => c.Id);

            modelBuilder.Entity<Complement>()
                .Property(c => c.Prix)
                .HasColumnType("decimal(10,2)");

            modelBuilder.Entity<Menu>()
                .ToTable("menu")
                .HasKey(m => m.Id);

            modelBuilder.Entity<Menu>()
                .HasOne(m => m.Burger)
                .WithMany(b => b.Menus)
                .HasForeignKey(m => m.IdBurger)
                .OnDelete(DeleteBehavior.SetNull);

            modelBuilder.Entity<Menu>()
                .HasOne(m => m.ComplementBoisson)
                .WithMany(c => c.MenusBoisson)
                .HasForeignKey(m => m.IdComplementBoisson)
                .OnDelete(DeleteBehavior.SetNull);

            modelBuilder.Entity<Menu>()
                .HasOne(m => m.ComplementFrites)
                .WithMany(c => c.MenusFrites)
                .HasForeignKey(m => m.IdComplementFrites)
                .OnDelete(DeleteBehavior.SetNull);

            modelBuilder.Entity<Zone>()
                .ToTable("zone")
                .HasKey(z => z.Id);

            modelBuilder.Entity<Zone>()
                .Property(z => z.PrixLivraison)
                .HasColumnType("decimal(10,2)");

            modelBuilder.Entity<Commande>()
                .ToTable("commande")
                .HasKey(c => c.Id);

            modelBuilder.Entity<Commande>()
                .Property(c => c.TypeCommande)
                .HasColumnName("type_commande")
                .HasConversion<string>();

            modelBuilder.Entity<Commande>()
                .Property(c => c.Etat)
                .HasColumnName("etat")
                .HasConversion<string>();

            modelBuilder.Entity<Commande>()
                .Property(c => c.Montant)
                .HasColumnType("decimal(10,2)");

            modelBuilder.Entity<LigneCommande>()
                .ToTable("ligne_commande")
                .HasKey(lc => lc.Id);

            modelBuilder.Entity<LigneCommande>()
                .Property(lc => lc.PrixUnitaire)
                .HasColumnType("decimal(10,2)");

            modelBuilder.Entity<LigneCommande>()
                .HasOne(lc => lc.Commande)
                .WithMany(c => c.LignesCommande)
                .HasForeignKey(lc => lc.CommandeId)
                .OnDelete(DeleteBehavior.Cascade);

            modelBuilder.Entity<Paiement>()
                .ToTable("paiement")
                .HasKey(p => p.Id);

            modelBuilder.Entity<Paiement>()
                .Property(p => p.ModePaiement)
                .HasColumnName("mode_paiement")
                .HasConversion<string>();

            modelBuilder.Entity<Paiement>()
                .Property(p => p.Montant)
                .HasColumnType("decimal(10,2)");

            modelBuilder.Entity<Paiement>()
                .HasOne(p => p.Commande)
                .WithOne(c => c.Paiement)
                .HasForeignKey<Paiement>(p => p.CommandeId)
                .OnDelete(DeleteBehavior.Cascade);
        }
    }
}