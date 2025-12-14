package brasil.burger.services.Impl;
import brasil.burger.entity.Complement;
import brasil.burger.repository.ComplementRepository;
import brasil.burger.services.ComplementService;
import java.util.List;
import java.util.Optional;

public class ComplementServiceImpl implements ComplementService {
    private ComplementRepository complementRepository;
    private static ComplementServiceImpl instance = null;

    private ComplementServiceImpl(ComplementRepository complementRepository) {
        this.complementRepository = complementRepository;
    }

    public static ComplementServiceImpl getInstance(ComplementRepository complementRepository) {
        if (instance == null) {
            instance = new ComplementServiceImpl(complementRepository);
        }
        return instance;
    }

    @Override
    public boolean createComplement(Complement complement) {
        return this.complementRepository.insert(complement) != 0;
    }

    @Override
    public List<Complement> getAllComplements() {
        return this.complementRepository.selectAll();
    }

    @Override
    public Optional<Complement> getComplementById(int id) {
        return this.complementRepository.selectById(id);
    }

    @Override
    public boolean updateComplement(Complement complement) {
        return this.complementRepository.update(complement) != 0;
    }

    @Override
    public boolean archiveComplement(int id) {
        return this.complementRepository.archive(id) != 0;
    }
}