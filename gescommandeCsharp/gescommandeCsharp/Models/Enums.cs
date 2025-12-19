namespace BrasilBurger.Models
{
    public enum TypeCommande
    {
        sur_place,
        a_emporter,
        livraison
    }

    public enum EtatCommande
    {
        reçue,
        preparation,
        terminee,
        annulee
    }

    public enum ModePaiement
    {
        wave,
        om
    }
}