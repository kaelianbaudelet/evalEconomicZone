<?php
declare (strict_types = 1);
namespace MyApp\Controller;

use MyApp\Entity\Product;
use MyApp\Model\ProductModel;
use MyApp\Model\TypeModel;
use MyApp\Service\DependencyContainer;
use Twig\Environment;

class ProductController
{
    private $twig;
    private $productModel;
    private TypeModel $typeModel;

    public function __construct(Environment $twig, DependencyContainer $dependencyContainer)
    {
        $this->twig = $twig;
        $this->productModel = $dependencyContainer->get('ProductModel');
        $this->typeModel = $dependencyContainer->get('TypeModel');

    }

    public function products()
    {
        $products = $this->productModel->getAllProducts();

        echo $this->twig->render('defaultController/products.html.twig', ['products' => $products]);
    }

    public function updateProduct()
    {
        $types = $this->typeModel->getAllTypes();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $idType = filter_input(INPUT_POST, 'idType',
                FILTER_SANITIZE_NUMBER_INT);
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
            $stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);
            if (!empty($id) && !empty($name) && !empty($description) && !empty($price)
                && !empty($stock) && !empty($idType)) {
                $product = $this->productModel->getOneProduct(intVal($id));
                if ($product == null) {
                    $_SESSION['message'] = 'Erreur sur le produit.';
                } else {
                    $type = $this->typeModel->getOneType(intVal($idType));
                    if ($type == null) {
                        $_SESSION['message'] = 'Erreur sur le type.';
                    } else {

                        $product = new Product(intVal($id), $name, floatVal($price), $description, intVal($stock), $type);
                        $success = $this->productModel->updateProduct($product);
                        if ($success) {
                            header('Location: index.php?page=list-products');
                        } else {
                            $_SESSION['message'] = 'Erreur sur la modification.';
                            header('Location: index.php?page=list-products');
                        }
                    }
                }
            } else {
                $_SESSION['message'] = 'Veuillez saisir toutes les données.';
            }
        } else {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            $product = $this->productModel->getOneProduct(intVal($id));
            if ($product == null) {
                $_SESSION['message'] = 'Erreur sur le produit.';
                header('Location: index.php?page=list-products');
            }
        }
        echo $this->twig->render('defaultController/updateProduct.html.twig',
            ['product' => $product, 'types' => $types]);
    }

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

    public function deleteProduct()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $this->productModel->deleteProduct(intVal($id));
        header('Location: index.php?page=products');
    }
}
