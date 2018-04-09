<?php
namespace Ijdb;

class IjdbRoutes implements \Ninja\Routes{
    private $authorsTable;
    private $jokesTable;
    private $authentication;
    private $categoriesTable;
    private $jokeCategoriesTable;

    public function __construct(){
        include __DIR__ . '/../../includes/DatabaseConnection.php';

        $this->jokesTable = new \Ninja\DatabaseTable($pdo, 'joke', 'id', '\Ijdb\Entity\Joke',
                                                                                        [&$this->authorsTable, &$this->jokeCategoriesTable]);
        $this->authorsTable = new \Ninja\DatabaseTable($pdo, 'author', 'id',  '\Ijdb\Entity\Author', [&$this->jokesTable]);
        $this->authentication = new \Ninja\Authentication($this->authorsTable, 'email', 'password');
        $this->categoriesTable = new \Ninja\DatabaseTable($pdo, 'category', 'id', '\Ijdb\Entity\Category',
                                                                                            [&$this->jokesTable, &$this->jokeCategoriesTable]);
        $this->jokeCategoriesTable =  new \Ninja\DatabaseTable($pdo, 'joke_category', 'categoryId');
    }

    public function getRoutes(): array {
        $jokeController = new \Ijdb\Controllers\Joke($this->jokesTable, $this->authorsTable,
                                                                                    $this->categoriesTable, $this->authentication);
        $authorController = new \Ijdb\Controllers\Register($this->authorsTable);
        $loginController = new \Ijdb\Controllers\Login($this->authentication);
        $categoryController = new \Ijdb\Controllers\Category($this->categoriesTable);

        $routes = [
			'login/permissionserror' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'permissionsError',
				],
			],
            'author/permissions' => [
                'GET' => [
                    'controller' => $authorController,
                    'action' => 'permissions',
                ],
                'POST' => [
                    'controller' => $authorController,
                    'action' => 'savePermissions',
                ],
                'login' => true,
                'permissions' =>  \Ijdb\Entity\Author::EDIT_USER_ACCESS,
            ],
            'author/list' => [
                'GET' => [
                    'controller' => $authorController,
                    'action' => 'list',
                ],
                'login' => true,
                'permissions' =>  \Ijdb\Entity\Author::EDIT_USER_ACCESS,
            ],
            'category/delete' => [
                'POST' => [
                    'controller' => $categoryController,
                    'action' => 'delete',
                ],
                'login' => true,
                'permissions' => \Ijdb\Entity\Author::REMOVE_CATEGORIES,
            ],
            'category/list' => [
                'GET' => [
                    'controller' => $categoryController,
                    'action' => 'list'
                ],
                'login' => true,
                'permissions' => \Ijdb\Entity\Author::EDIT_CATEGORIES,
            ],
            'category/edit' => [
                'POST' => [
                    'controller' => $categoryController,
                    'action' => 'saveEdit',
                ],
                'GET' => [
                    'controller' => $categoryController,
                    'action' => 'edit',
                ],
                'login' => true,
                'permissions' => \Ijdb\Entity\Author::EDIT_CATEGORIES,
            ],
            'logout' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'logout',
                ],
            ],
            'login' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'loginForm',
                ],
                'POST' => [
                    'controller' => $loginController,
                    'action' => 'processLogin',
                ],
            ],
            'login/success' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'success',
                ],
                'login' => true,
            ],
            'login/error' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'error',
                ],
            ],
            'author/register' => [
                'GET' => [
                    'controller' => $authorController,
                    'action' => 'registrationForm',
                ],
                'POST' => [
                    'controller' => $authorController,
                    'action' => 'registerUser',
                ],
            ],
            'author/success' => [
                'GET' => [
                    'controller' => $authorController,
                    'action' => 'success',
                ],
                'login' => true,
            ],
            'joke/edit' =>[
                'POST'=>[
                    'controller' => $jokeController,
                    'action' => 'saveEdit',
                ],
                'GET' => [
                    'controller' => $jokeController,
                    'action' => 'edit',
                ],
                'login' => true,
            ],
            'joke/list' =>[
                'GET'=>[
                    'controller' => $jokeController,
                    'action' => 'list',
                ]
            ],
            'joke/delete' =>[
                'POST'=>[
                    'controller' => $jokeController,
                    'action' => 'delete',
                ],
                'login' => true,
            ],
            'index.php' =>[
                'GET'=>[
                    'controller' => $jokeController,
                    'action' => 'home',
                ]
            ],
            '' =>[
                'GET'=>[
                    'controller' => $jokeController,
                    'action' => 'home',
                ]
            ],
        ];

        return $routes;
    }

    public function getAuthentication(): \Ninja\Authentication {
        return $this->authentication;
    }

    public function checkPermission($permission): bool {
        $user = $this->authentication->getUser();
        if ($user && $user->hasPermission($permission)) {
            return true;
        } else {
            return false;
        }
    }
}
