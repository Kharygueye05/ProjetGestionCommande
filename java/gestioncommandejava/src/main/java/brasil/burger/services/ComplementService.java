package brasil.burger.services;
import brasil.burger.entity.Complement;
import java.util.List;
import java.util.Optional;

public interface ComplementService {
    boolean createComplement(Complement complement);
    List<Complement> getAllComplements();
    Optional<Complement> getComplementById(int id);
    boolean updateComplement(Complement complement);
    boolean archiveComplement(int id);
}