package brasil.burger.repository;
import brasil.burger.entity.Complement;
import java.util.List;
import java.util.Optional;

public interface ComplementRepository {
    int insert(Complement complement);
    List<Complement> selectAll();
    Optional<Complement> selectById(int id);
    int update(Complement complement);
    int archive(int id);
    List<Complement> selectByType(String type);
    
}