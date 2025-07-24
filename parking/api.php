<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

// Récupérer la méthode HTTP et l'action
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

$clientManager = new ClientManager();
$paymentManager = new PaymentManager();

try {
    switch ($method) {
        case 'GET':
            handleGetRequest($action, $clientManager, $paymentManager);
            break;
        case 'POST':
            handlePostRequest($action, $clientManager, $paymentManager);
            break;
        case 'PUT':
            handlePutRequest($action, $clientManager, $paymentManager);
            break;
        case 'DELETE':
            handleDeleteRequest($action, $clientManager);
            break;
        default:
            sendResponse(405, 'Méthode non autorisée');
    }
} catch (Exception $e) {
    sendResponse(500, 'Erreur serveur: ' . $e->getMessage());
}

function handleGetRequest($action, $clientManager, $paymentManager) {
    switch ($action) {
        case 'clients':
            $search = $_GET['search'] ?? '';
            if ($search) {
                $clients = $clientManager->searchClients($search);
            } else {
                $clients = $clientManager->getAllClients();
            }
            sendResponse(200, 'Clients récupérés', $clients);
            break;
            
        case 'client':
            $id = $_GET['id'] ?? 0;
            $client = $clientManager->getClientById($id);
            if ($client) {
                sendResponse(200, 'Client récupéré', $client);
            } else {
                sendResponse(404, 'Client non trouvé');
            }
            break;
            
        case 'payments':
            $clientId = $_GET['client_id'] ?? 0;
            $year = $_GET['year'] ?? date('Y');
            $payments = $paymentManager->getClientPayments($clientId, $year);
            sendResponse(200, 'Paiements récupérés', $payments);
            break;
            
        case 'stats':
            $year = $_GET['year'] ?? date('Y');
            $month = $_GET['month'] ?? date('n');
            $stats = $paymentManager->getStats($year, $month);
            sendResponse(200, 'Statistiques récupérées', $stats);
            break;
            
        default:
            sendResponse(404, 'Action non trouvée');
    }
}

function handlePostRequest($action, $clientManager, $paymentManager) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    switch ($action) {
        case 'client':
            $required = ['name', 'phone', 'plate_number', 'monthly_fee'];
            if (!validateRequired($data, $required)) {
                sendResponse(400, 'Données manquantes');
                return;
            }
            
            $clientId = $clientManager->addClient($data);
            if ($clientId) {
                sendResponse(201, 'Client ajouté avec succès', ['id' => $clientId]);
            } else {
                sendResponse(400, 'Erreur lors de l\'ajout du client');
            }
            break;
            
        case 'toggle_payment':
            $required = ['client_id', 'year', 'month'];
            if (!validateRequired($data, $required)) {
                sendResponse(400, 'Données manquantes');
                return;
            }
            
            $result = $paymentManager->togglePayment($data['client_id'], $data['year'], $data['month']);
            if ($result) {
                sendResponse(200, 'Statut de paiement modifié');
            } else {
                sendResponse(400, 'Erreur lors de la modification du paiement');
            }
            break;
            
        case 'init_year_payments':
            $required = ['client_id', 'year'];
            if (!validateRequired($data, $required)) {
                sendResponse(400, 'Données manquantes');
                return;
            }
            
            $result = $paymentManager->initializeYearPayments($data['client_id'], $data['year']);
            if ($result) {
                sendResponse(200, 'Paiements initialisés pour l\'année');
            } else {
                sendResponse(400, 'Erreur lors de l\'initialisation');
            }
            break;
            
        default:
            sendResponse(404, 'Action non trouvée');
    }
}

function handlePutRequest($action, $clientManager, $paymentManager) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    switch ($action) {
        case 'client':
            $id = $_GET['id'] ?? 0;
            $required = ['name', 'phone', 'plate_number', 'monthly_fee'];
            if (!validateRequired($data, $required)) {
                sendResponse(400, 'Données manquantes');
                return;
            }
            
            $result = $clientManager->updateClient($id, $data);
            if ($result) {
                sendResponse(200, 'Client modifié avec succès');
            } else {
                sendResponse(400, 'Erreur lors de la modification du client');
            }
            break;
            
        default:
            sendResponse(404, 'Action non trouvée');
    }
}

function handleDeleteRequest($action, $clientManager) {
    switch ($action) {
        case 'client':
            $id = $_GET['id'] ?? 0;
            $result = $clientManager->deleteClient($id);
            if ($result) {
                sendResponse(200, 'Client supprimé avec succès');
            } else {
                sendResponse(400, 'Erreur lors de la suppression du client');
            }
            break;
            
        default:
            sendResponse(404, 'Action non trouvée');
    }
}

function validateRequired($data, $required) {
    foreach ($required as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            return false;
        }
    }
    return true;
}

function sendResponse($status, $message, $data = null) {
    http_response_code($status);
    $response = [
        'status' => $status,
        'message' => $message
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit;
}
?>