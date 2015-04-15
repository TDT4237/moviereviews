<?php

namespace tdt4237\webapp\repository;

use PDO;
use tdt4237\webapp\models\Age;
use tdt4237\webapp\models\Email;
use tdt4237\webapp\models\User;

/**
 * This class handles the conversion from PHP objects into database rows.
 */
class UserRepository
{
    const INSERT_QUERY = "INSERT INTO users(user, pass, email, age, bio, isadmin) VALUES('%s', '%s', '%s' , '%s' , '%s', '%s')";
    const UPDATE_QUERY = "UPDATE users SET email='%s', age='%s', bio='%s', isadmin='%s' WHERE id='%s'";
    const FIND_BY_NAME = "SELECT * FROM users WHERE user='%s'";
    const DELETE_BY_NAME = "DELETE FROM users WHERE user='%s'";
    const SELECT_ALL = "SELECT * FROM users";

    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function makeUserFromRow($row)
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

    public function findByUser($username)
    {
        $query = sprintf(self::FIND_BY_NAME, $username);
        $result = $this->pdo->query($query, PDO::FETCH_ASSOC);
        $row = $result->fetch();

        return $this->makeUserFromRow($row);
    }

    public function deleteByUsername($username)
    {
        return $this->pdo->exec(
            sprintf(self::DELETE_BY_NAME, $username)
        );
    }

    public function all()
    {
        $rows = $this->pdo->query(self::SELECT_ALL);

        return array_map([$this, 'makeUserFromRow'], $rows->fetchAll());
    }

    /**
     * Insert or update a user object to db.
     */
    public function save(User $user)
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
