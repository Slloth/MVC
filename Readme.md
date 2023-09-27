# MVC perso
## Ceci est un MVC Basique pour un usage simple

### Controller
charger un model dans un controller
```php
$this->loadModel("monArticle");
$articles = $this->monArticle->getAll();
```
### Model
Créer un Model
```php
class MonModel extends AbstractModel{
    public function __construct()
    {
        parent::__construct();
        $this->setTable("monModel");
        $this->getConnection();
    }
}
```