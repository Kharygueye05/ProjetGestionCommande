namespace BrasilBurger.Models
{
    public class CommandeData
    {
        public List<CartItemData> Cart { get; set; } = new();
        public string PaymentMethod { get; set; } = string.Empty;
        public int? ZoneId { get; set; }
        public decimal DeliveryFee { get; set; }
        public decimal Total { get; set; }
    }

    public class CartItemData
    {
        public string Type { get; set; } = string.Empty;
        public int Id { get; set; }
        public string Name { get; set; } = string.Empty;
        public string? Image { get; set; }
        public decimal Price { get; set; }
        public string CommandType { get; set; } = string.Empty;
        public int Quantity { get; set; }
        public decimal Total { get; set; }
        public List<ComplementData>? Complements { get; set; }
    }

    public class ComplementData
    {
        public int Id { get; set; }
        public decimal Price { get; set; }
        public string? Name { get; set; }
    }
}