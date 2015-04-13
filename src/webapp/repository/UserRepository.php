<?php

namespace tdt4237\webapp\repository;

use PDO;
use tdt4237\webapp\models\Age;
use tdt4237\webapp\models\Email;
use tdt4237\webapp\models\User;

class UserRepository
{
    const INSERT_QUERY = "INSERT INTO users(user, pass, email, age, bio, isadmin) VALUES('%s', '%s', '%s' , '%s' , '%s', '%s')";
    const UPDATE_QUERY = "UPDATE users SET email='%s', age='%s', bio='%s', isadmin='%s' WHERE id='%s'";
    const FIND_BY_NAME = "SELECT * FROM users WHERE user='%s'";

    /**
     * @var PDO
     */
    private $pdo;

    function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    function makeUserFromRow($row)
    {
        $user = new User($row['user'], $row['pass']);
        $user->setId($row['id']);
        $user->setBio($row['bio']);
        $user->setIsAdmin($row['isadmin']);

        if (!empty($row['email'])) {
            $user->setEmail(new Email($row['email']));
        }

        if (!empty($row['age'])) {
            $user->setAge(new Age($row['age']));
        }

        return $user;
    }

    /**
     * Find user in db by username.
     *
     * @param string $username
     * @return mixed User or null if not found.
     */
    public function findByUser($username)
    {
        $query = sprintf(self::FIND_BY_NAME, $username);
        $result = $this->pdo->query($query, PDO::FETCH_ASSOC);
        $row = $result->fetch();

        if ($row == false) {
            return null;
        }

        return $this->makeUserFromRow($row);
    }

    public function deleteByUsername($username)
    {
        $query = "DELETE FROM users WHERE user='$username' ";
        return $this->pdo->exec($query);
    }

    public function all()
    {
        $query = "SELECT * FROM users";
        $results = $this->pdo->query($query);

        return array_map([$this, 'makeUserFromRow'], $results->fetchAll());
    }

    /**
     * Insert or update a user object to db.
     */
    function save(User $user)
    {
        if ($user->getId() === null) {
            return $this->saveNewUser($user);
        }

        $this->saveExistingUser($user);
    }

    public function saveNewUser(User $user)
    {
        $query = sprintf(
            self::INSERT_QUERY,
            $user->getUserName(),
            $user->getPasswordHash(),
            $user->getEmail(),
            $user->getAge(),
            $user->getBio(),
            $user->isAdmin()
        );

        return $this->pdo->exec($query);
    }

    public function saveExistingUser(User $user)
    {
        $query = sprintf(
            self::UPDATE_QUERY,
            $user->getEmail(),
            $user->getAge(),
            $user->getBio(),
            $user->isAdmin(),
            $user->getId()
        );

        return $this->pdo->exec($query);
    }
}
