<?php
//создаем класс
class User {
    // Создаем закрытый свойство класса для работы с бдыв
    private $db;
    // Конструктор для создания объектов классаыв
    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // Метод для получения списка аккаунтов (с пагинацией)ыв
    public function getAccounts($limit, $offset) {
        $stmt = $this->db->prepare("SELECT * FROM accounts LIMIT :limit OFFSET :offset");
        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Метод для добавления аккаунта
    public function addUser($first_name, $last_name, $email, $company, $position, $phones) {
        $stmt = $this->db->prepare("INSERT INTO accounts (first_name, last_name, email, company_name, position, phone1, phone2, phone3)
                                    VALUES (:first_name, :last_name, :email, :company, :position, :phone1, :phone2, :phone3)");
        return $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':company' => $company,
            ':position' => $position,
            //Чтобы избежать ошибки Undefined array key проверяем на null
            ':phone1' => $phones[0] ?? null,
            ':phone2' => $phones[1] ?? null,
            ':phone3' => $phones[2] ?? null
        ]);
    }

    // Метод для удаления аккаунта
    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM accounts WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    // Метод для обновления аккаунта
    public function updateUser($id, $first_name, $last_name, $email, $company, $position, $phones) {
        $stmt = $this->db->prepare("UPDATE accounts SET 
            first_name = :first_name, 
            last_name = :last_name, 
            email = :email, 
            company_name = :company, 
            position = :position, 
            phone1 = :phone1, 
            phone2 = :phone2, 
            phone3 = :phone3
            WHERE id = :id");
    
        return $stmt->execute([
            ':id'        => $id,
            ':first_name'=> $first_name,
            ':last_name' => $last_name,
            ':email'     => $email,
            ':company'   => $company,
            ':position'  => $position,
             //Чтобы избежать ошибки Undefined array key проверяем на null
            ':phone1'    => $phones[0] ?? null,
            ':phone2'    => $phones[1] ?? null,
            ':phone3'    => $phones[2] ?? null
        ]);
    }
}
