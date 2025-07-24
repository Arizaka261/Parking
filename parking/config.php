<?php
// Configuration de la base de données
class DatabaseConfig {
    private $host = 'localhost';
    private $dbname = 'parking_management';
    private $username = 'root'; // Changez selon votre configuration
    private $password = ''; // Changez selon votre configuration
    private $charset = 'utf8mb4';
    
    public function getConnection() {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
        
        try {
            $pdo = new PDO($dsn, $this->username, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (PDOException $e) {
            error_log("Erreur de connexion à la base de données: " . $e->getMessage());
            throw new Exception("Erreur de connexion à la base de données");
        }
    }
}

// Classe pour gérer les opérations sur les clients
class ClientManager {
    private $pdo;
    
    public function __construct() {
        $db = new DatabaseConfig();
        $this->pdo = $db->getConnection();
    }
    
    // Ajouter un nouveau client
    public function addClient($data) {
        try {
            $sql = "INSERT INTO clients (name, phone, car_model, plate_number, monthly_fee, parking_spot) 
                    VALUES (:name, :phone, :car_model, :plate_number, :monthly_fee, :parking_spot)";
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                ':name' => $data['name'],
                ':phone' => $data['phone'],
                ':car_model' => $data['car_model'],
                ':plate_number' => $data['plate_number'],
                ':monthly_fee' => $data['monthly_fee'],
                ':parking_spot' => $data['parking_spot']
            ]);
            
            if ($result) {
                return $this->pdo->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout du client: " . $e->getMessage());
            return false;
        }
    }
    
    // Récupérer tous les clients
    public function getAllClients() {
        try {
            $sql = "SELECT * FROM clients ORDER BY name";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des clients: " . $e->getMessage());
            return [];
        }
    }
    
    // Récupérer un client par ID
    public function getClientById($id) {
        try {
            $sql = "SELECT * FROM clients WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du client: " . $e->getMessage());
            return null;
        }
    }
    
    // Modifier un client
    public function updateClient($id, $data) {
        try {
            $sql = "UPDATE clients 
                    SET name = :name, phone = :phone, car_model = :car_model, 
                        plate_number = :plate_number, monthly_fee = :monthly_fee, 
                        parking_spot = :parking_spot 
                    WHERE id = :id";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':phone' => $data['phone'],
                ':car_model' => $data['car_model'],
                ':plate_number' => $data['plate_number'],
                ':monthly_fee' => $data['monthly_fee'],
                ':parking_spot' => $data['parking_spot']
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la modification du client: " . $e->getMessage());
            return false;
        }
    }
    
    // Supprimer un client
    public function deleteClient($id) {
        try {
            $sql = "DELETE FROM clients WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression du client: " . $e->getMessage());
            return false;
        }
    }
    
    // Rechercher des clients
    public function searchClients($searchTerm) {
        try {
            $sql = "SELECT * FROM clients 
                    WHERE name LIKE :search OR plate_number LIKE :search 
                    ORDER BY name";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':search' => "%{$searchTerm}%"]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur lors de la recherche: " . $e->getMessage());
            return [];
        }
    }
}

// Classe pour gérer les paiements
class PaymentManager {
    private $pdo;
    
    public function __construct() {
        $db = new DatabaseConfig();
        $this->pdo = $db->getConnection();
    }
    
    // Récupérer les paiements d'un client pour une année
    public function getClientPayments($clientId, $year) {
        try {
            $sql = "SELECT * FROM payments 
                    WHERE client_id = :client_id AND year = :year 
                    ORDER BY month";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':client_id' => $clientId,
                ':year' => $year
            ]);
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des paiements: " . $e->getMessage());
            return [];
        }
    }
    
    // Basculer le statut de paiement
    public function togglePayment($clientId, $year, $month) {
        try {
            // Vérifier si le paiement existe
            $sql = "SELECT is_paid FROM payments 
                    WHERE client_id = :client_id AND year = :year AND month = :month";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':client_id' => $clientId,
                ':year' => $year,
                ':month' => $month
            ]);
            
            $payment = $stmt->fetch();
            
            if ($payment) {
                // Basculer le statut
                $newStatus = !$payment['is_paid'];
                $paymentDate = $newStatus ? 'NOW()' : 'NULL';
                
                $sql = "UPDATE payments 
                        SET is_paid = :is_paid, payment_date = {$paymentDate}
                        WHERE client_id = :client_id AND year = :year AND month = :month";
                
                $stmt = $this->pdo->prepare($sql);
                return $stmt->execute([
                    ':is_paid' => $newStatus,
                    ':client_id' => $clientId,
                    ':year' => $year,
                    ':month' => $month
                ]);
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Erreur lors du basculement du paiement: " . $e->getMessage());
            return false;
        }
    }
    
    // Récupérer les statistiques
    public function getStats($year, $month) {
        try {
            $stats = [];
            
            // Total des clients
            $sql = "SELECT COUNT(*) as total FROM clients";
            $stmt = $this->pdo->query($sql);
            $stats['totalClients'] = $stmt->fetch()['total'];
            
            // Payés ce mois
            $sql = "SELECT COUNT(*) as paid FROM payments 
                    WHERE year = :year AND month = :month AND is_paid = TRUE";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':year' => $year, ':month' => $month]);
            $stats['paidThisMonth'] = $stmt->fetch()['paid'];
            
            // En attente
            $stats['pendingPayments'] = $stats['totalClients'] - $stats['paidThisMonth'];
            
            // Revenus du mois
            $sql = "SELECT SUM(amount) as revenue FROM payments 
                    WHERE year = :year AND month = :month AND is_paid = TRUE";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':year' => $year, ':month' => $month]);
            $stats['monthlyRevenue'] = $stmt->fetch()['revenue'] ?? 0;
            
            // Revenus annuels
            $sql = "SELECT SUM(amount) as revenue FROM payments 
                    WHERE year = :year AND is_paid = TRUE";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':year' => $year]);
            $stats['yearlyRevenue'] = $stmt->fetch()['revenue'] ?? 0;
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Erreur lors du calcul des statistiques: " . $e->getMessage());
            return [
                'totalClients' => 0,
                'paidThisMonth' => 0,
                'pendingPayments' => 0,
                'monthlyRevenue' => 0,
                'yearlyRevenue' => 0
            ];
        }
    }
    
    // Initialiser les paiements pour une nouvelle année
    public function initializeYearPayments($clientId, $year) {
        try {
            $sql = "CALL InitializeClientPayments(:client_id, :year)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':client_id' => $clientId,
                ':year' => $year
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de l'initialisation des paiements: " . $e->getMessage());
            return false;
        }
    }
}
?>