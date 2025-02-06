<?php
declare (strict_types = 1);
namespace MyApp\Controller;

use MyApp\Entity\Currency;
use MyApp\Entity\EconomicZone;
use MyApp\Entity\Type;
use MyApp\Model\CurrencyModel;
use MyApp\Model\EconomicZoneModel;
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
    private $EconomicZoneModel;

    public function __construct(Environment $twig, DependencyContainer $dependencyContainer)
    {
        $this->twig = $twig;
        $this->typeModel = $dependencyContainer->get('TypeModel');
        $this->productModel = $dependencyContainer->get('ProductModel');
        $this->userModel = $dependencyContainer->get('UserModel');
        $this->currencyModel = $dependencyContainer->get('CurrencyModel');
        $this->EconomicZoneModel = $dependencyContainer->get('EconomicZoneModel');
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

    public function deleteType()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $this->typeModel->deleteType(intVal($id));
        header('Location: index.php?page=types');
    }

    // EconomicZone

    public function economiczones()
    {
        $economiczones = $this->EconomicZoneModel->getAllEconomicZones();
        echo $this->twig->render('defaultController/economiczones.html.twig', ['economiczones' => $economiczones]);
    }

    public function updateEconomicZone()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            if (!empty($_POST['name'])) {
                $economiczone = new EconomicZone(intVal($id), $name);
                $success = $this->EconomicZoneModel->updateEconomicZone($economiczone);
                if ($success) {
                    header('Location: index.php?page=economiczones');
                }
            }
        } else {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        }
        $economiczone = $this->EconomicZoneModel->getOneEconomicZone(intVal($id));
        echo $this->twig->render('defaultController/updateEconomicZone.html.twig', ['economiczone' => $economiczone]);
    }

    public function addEconomicZone()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            if (!empty($_POST['name'])) {
                $economiczone = new EconomicZone(null, $name);
                $success = $this->EconomicZoneModel->addEconomicZone($economiczone);
                if ($success) {
                    header('Location: index.php?page=economiczones');
                }
            }
        }
        echo $this->twig->render('defaultController/addEconomicZone.html.twig', []);
    }

    public function deleteEconomicZone()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $this->EconomicZoneModel->deleteEconomicZone(intVal($id));
        header('Location: index.php?page=economiczones');
    }

    // Currency

    public function addProduct()
    {
        $types = $this->typeModel->getAllTypes();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
            $stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);
            $idType = filter_input(INPUT_POST, 'idType', FILTER_SANITIZE_NUMBER_INT);
            if (!empty($_POST['name']) && !empty($_POST['price']) && !empty($_POST['description']) && !empty($_POST['stock']) && !empty($idType)) {
                $type = $this->typeModel->getOneType(intVal($idType));
                if ($type == null) {
                    $_SESSION['message'] = 'Erreur sur le type.';
                } else {

                    $product = new Product(null, $name, floatVal($price), $description, intVal($stock), $type);

                    $success = $this->productModel->createProduct($product);
                    if ($success) {
                        header('Location: index.php?page=products');
                    }
                }
            }
        } else {
            echo $this->twig->render('defaultController/addProduct.html.twig', ['types' => $types]);
        }

    }

    public function addCurrency()
    {

        $economiczones = $this->EconomicZoneModel->getAllEconomicZones();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $idEconomicZone = filter_input(INPUT_POST, 'idEconomicZone', FILTER_SANITIZE_NUMBER_INT);

            if (!empty($_POST['name']) && !empty($idEconomicZone)) {
                $economiczone = $this->EconomicZoneModel->getOneEconomicZone(intVal($idEconomicZone));

                if ($economiczone == null) {
                    $_SESSION['message'] = 'Erreur sur le type.';
                } else {

                    $currency = new Currency(null, $name, $economiczone);

                    $success = $this->currencyModel->addCurrency($currency);
                    if ($success) {
                        header('Location: index.php?page=currencys');

                    }
                }
            }
        } else {
            echo $this->twig->render('defaultController/addCurrency.html.twig', ['economiczones' => $economiczones]);
        }

    }

    public function updateCurrency()
    {
        $economiczones = $this->EconomicZoneModel->getAllEconomicZones();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $idEconomicZone = filter_input(INPUT_POST, 'idEconomicZone',
                FILTER_SANITIZE_NUMBER_INT);

            if (!empty($id) && !empty($name) && !empty($idEconomicZone)) {
                $currency = $this->currencyModel->getOneCurrency(intVal($id));
                if ($currency == null) {
                    $_SESSION['message'] = 'Erreur sur la monnaie.';
                } else {
                    $economiczone = $this->EconomicZoneModel->getOneEconomicZone(intVal($idEconomicZone));
                    if ($economiczone == null) {
                        $_SESSION['message'] = 'Erreur sur la zone eco.';
                    } else {

                        $currency = new Currency(intVal($id), $name, $economiczone);
                        $success = $this->currencyModel->updateCurrency($currency);
                        if ($success) {
                            header('Location: index.php?page=currencys');
                        } else {
                            $_SESSION['message'] = 'Erreur sur la modification.';
                            header('Location: index.php?page=currencys');
                        }
                    }
                }
            } else {
                $_SESSION['message'] = 'Veuillez saisir toutes les données.';
            }
        } else {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            $currency = $this->currencyModel->getOneCurrency(intVal($id));
            if ($currency == null) {
                $_SESSION['message'] = 'Erreur sur la monnaie.';
                header('Location: index.php?page=currencys');
            }
        }
        echo $this->twig->render('defaultController/updateCurrency.html.twig',
            ['currency' => $currency, 'economiczones' => $economiczones]);
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
