<?php
class Email
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllEmails()
    {
        $stmt = $this->pdo->query("SELECT * FROM emails");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEmailById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM emails WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getEmailByUserId($id){
        $stmt = $this->pdo->prepare("SELECT * FROM emails WHERE userId = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createEmail($data)
    {
        try {
            $sql = "INSERT INTO emails (userId, recipient, subject, body) VALUES (:userId, :recipient, :subject, :body)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':userId' => $data['userId'],
                ':recipient' => $data['recipient'],
                ':subject' => $data['subject'],
                ':body' => $data['body']
            ]);
        } catch (PDOException $e) {
            return ['error' => 'Database error: ' . $e->getMessage()];
        }

    }

    public function updateEmail($id, $name, $email)
    {
        $stmt = $this->pdo->prepare("UPDATE emails SET name = ?, email = ? WHERE id = ?");
        return $stmt->execute([$name, $email, $id]);
    }

    public function deleteEmail($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM emails WHERE id = ?");
        return $stmt->execute([$id]);
    }
}