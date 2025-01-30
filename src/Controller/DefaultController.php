<?php
declare (strict_types = 1);
namespace MyApp\Controller;

use MyApp\Entity\Currency;
use MyApp\Entity\Product;
use MyApp\Entity\Type;
use MyApp\Model\CurrencyModel;
use MyApp\Model\ProductModel;
use MyApp\Model\TypeModel;
use MyApp\Model\UserModel;
use MyApp\Service\DependencyContainer;
use Twig\Environment;

class DefaultController
{
    private $twig;
    private $typeModel;
    private $productModel;
    private $userModel;
    private $currencyModel;

    public function __construct(Environment $twig, DependencyContainer $dependencyContainer)
    {
        $this->twig = $twig;
        $this->typeModel = $dependencyContainer->get('TypeModel');
        $this->productModel = $dependencyContainer->get('ProductModel');
        $this->userModel = $dependencyContainer->get('UserModel');
        $this->currencyModel = $dependencyContainer->get('CurrencyModel');
    }

    public function home()
    {
        echo $this->twig->render('defaultController/home.html.twig', []);
    }

    public function error404()
    {
        echo $this->twig->render('defaultController/error404.html.twig', []);
    }

    public function error500()
    {
        echo $this->twig->render('defaultController/error500.html.twig', []);
    }
    public function contact()
    {
        echo $this->twig->render('defaultController/contact.html.twig', []);
    }
    public function legals()
    {
        echo $this->twig->render('defaultController/legals.html.twig', []);
    }

    public function products()
    {
        $products = $this->productModel->getAllProducts();
        echo $this->twig->render('defaultController/products.html.twig', ['products' => $products]);
    }

    public function users()
    {
        $users = $this->userModel->getAllUsers();
        echo $this->twig->render('defaultController/users.html.twig', ['users' => $users]);
    }

    public function types()
    {
        $types = $this->typeModel->getAllTypes();
        echo $this->twig->render('defaultController/types.html.twig', ['types' => $types]);
    }

    public function updateType()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $label = filter_input(INPUT_POST, 'label', FILTER_SANITIZE_STRING);
            if (!empty($_POST['label'])) {
                $type = new Type(intVal($id), $label);
                $success = $this->typeModel->updateType($type);
                if ($success) {
                    header('Location: index.php?page=types');
                }
            }
        } else {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        }
        $type = $this->typeModel->getOneType(intVal($id));
        echo $this->twig->render('defaultController/updateType.html.twig', ['type' => $type]);
    }

    public function updateProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
            if (!empty($_POST['name']) && !empty($_POST['price']) && !empty($_POST['description'])) {
                $product = new Product(intVal($id), $name, floatVal($price), $description);
                $success = $this->productModel->updateProduct($product);
                if ($success) {
                    header('Location: index.php?page=products');
                }
            }
        } else {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        }
        $product = $this->productModel->getOneProduct(intVal($id));
        echo $product->getname();
        echo $this->twig->render('defaultController/updateProduct.html.twig', ['product' => $product]);
    }

    public function addType()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $label = filter_input(INPUT_POST, 'label', FILTER_SANITIZE_STRING);
            if (!empty($_POST['label'])) {
                $type = new Type(null, $label);
                $success = $this->typeModel->createType($type);
                if ($success) {
                    header('Location: index.php?page=types');
                }
            }
        }
        echo $this->twig->render('defaultController/addType.html.twig', []);
    }

    public function addProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $label = filter_input(INPUT_POST, 'label', FILTER_SANITIZE_STRING);
            if (!empty($_POST['name']) && !empty($_POST['price']) && !empty($_POST['description'])) {
                $product = new Product(null, $label);
                $success = $this->productModel->createProduct($product);
                if ($success) {
                    header('Location: index.php?page=products');
                }
            }
        }
        echo $this->twig->render('defaultController/addProduct.html.twig', []);
    }

    public function deleteType()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $this->typeModel->deleteType(intVal($id));
        header('Location: index.php?page=types');
    }

    public function addCurrency()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currency = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            if (!empty($_POST['name'])) {
                $currency = new Currency(null, $currency);
                $success = $this->currencyModel->addCurrency($currency);
                if ($success) {
                    header('Location: index.php?page=currencys');
                }
            }
        }
        echo $this->twig->render('defaultController/addCurrency.html.twig', []);
    }

    public function updateCurrency()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            if (!empty($_POST['name'])) {
                $currency = new Currency(intVal($id), $name);
                $success = $this->currencyModel->updateCurrency($currency);
                if ($success) {
                    header('Location: index.php?page=currencys');
                }
            }
        } else {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        }
        $currency = $this->currencyModel->getOneCurrency(intVal($id));
        echo $this->twig->render('defaultController/updateCurrency.html.twig', ['currency' => $currency]);
    }

    public function currencys()
    {
        $currencys = $this->currencyModel->getAllCurrency();
        echo $this->twig->render('defaultController/currencys.html.twig', ['currencys' => $currencys]);
    }

    public function deleteCurrency()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $this->currencyModel->deleteCurrency(intVal($id));
        header('Location: index.php?page=currencys');
    }
}
