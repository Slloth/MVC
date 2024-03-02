# Light MVC
Light MVC est un framework php de type MVC très léger *from scratch*
# Implémentation
* Le Routeur
* Les Controlleurs
* Les Models
* Les Repositories
* Les Views
* Les Variables d'environnement
* L'Autoloadeur
* {AbstractControlleur, AbstractModel, AbstractRepository, Db}
## Connecter sa base de données
Copier le fichier [.env](.env) en .env.local et modifiez le

Les base de données compatible:
* mysql
* mariadb
## Création d'un Controller
### Controller
Créer un class suivis de Controller ***MonController.php*** dans le dossier [App/Controllers](App/Controllers).

Ajouter le namespace `namespace App\Controllers;`.

Puis la faire etendre d'[AbstractController](Core/Controller/AbstractController.php).

Enfin créer l'action par defaut index
````php
    public function index(){
        $title = "Mon titre de page";
        // dans compact le string doit être du même nom qu'une variable
        $this->render("nomDeLaVueDansLeDossierDuController",compact("title",...string));
    }
````
### Views
Ajouter le dans le dossier [Views](App/Views) un dossier du nom du controller dont la première lettre est en  minuscule ***monController***.

Et un fichier pour du même nom que l'action du controller ***index.php***.
## Création d'un Model
### Model
Créer une class du nom de la table ***table.php*** dans le dossier [App/Models](App/Models).

Ajouter le namespace `namespace App\Models;`. 

Puis la faire étendre d'[AbstractModel](Core/Model/AbstractModel.php).

Enfin créer autant d'Attributs **Protected** dans votre class que de colonne dans votre table, puis créer les Getters & Setters et pour finir le constructeur.
````php
    public function __construct(){
        $this->table = "nomDeLaTable";
    }
````
### Repository
Créer un class du nom de la class suivis de Repository ***tableRepository.php*** dans le dossier [App/Repositories](App/Repositories).

Ajouter le namespace `namespace App\Repositories;`.

Puis la faire étendre d'[AbstractRepository](Core/Repository/AbstractRepository.php).

Enfin créer le constructeur.
````php
    public function __construct(){
        parent::__construct(new Table());
    }
````
#### Utiliser un Repository
Aller dans votre controller et instanciez votre Repository.

Exemples:
````php
$repo = new ArticleRepository();
/**
* @var Article $article
*/
$article = $repo->find(1);
````
````php
$repo = new ArticleRepository();
/**
* @var Article[] $articles
*/
$articles = $repo->findAll();
````
**! Noubliez pas d'utilisez la PHPDoc pour typer la variable du même type que le model associé aider votre IDE**

Votre repository dispose par défaut de 4 methodes:
- findAll
- find
- findBy
- findOneBy

Vous trouverez plus de détails via la PHPDoc.