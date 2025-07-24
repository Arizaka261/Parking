<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Parking</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .nav-tabs {
            display: flex;
            background: #f8f9fa;
            border-bottom: 2px solid #e1e5e9;
        }
        
        .nav-tab {
            flex: 1;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            border: none;
            background: transparent;
        }
        
        .nav-tab.active {
            background: white;
            color: #4CAF50;
            border-bottom: 3px solid #4CAF50;
        }
        
        .nav-tab:hover {
            background: rgba(76, 175, 80, 0.1);
        }
        
        .tab-content {
            display: none;
            padding: 30px;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #4CAF50;
        }
        
        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .form-section, .list-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .form-section h2, .list-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5em;
        }
        
        .input-group {
            margin-bottom: 20px;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        
        .input-group input, .input-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        .input-group input:focus, .input-group select:focus {
            outline: none;
            border-color: #4CAF50;
        }
        
        .btn {
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
        }
        
        .btn-danger {
            background: linear-gradient(45deg, #dc3545, #c82333);
        }
        
        .btn-danger:hover {
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }
        
        .btn-warning {
            background: linear-gradient(45deg, #ffc107, #e0a800);
        }
        
        .btn-warning:hover {
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
        }
        
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn-group .btn {
            width: auto;
            flex: 1;
        }
        
        .client-item {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            border-left: 4px solid #4CAF50;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .client-item:hover {
            background: #e8f5e8;
            transform: translateX(5px);
        }
        
        .client-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .client-details {
            font-size: 0.9em;
            color: #666;
        }
        
        .search-box {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .empty-state h3 {
            margin-bottom: 10px;
            color: #999;
        }
        
        .loading {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #666;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 15px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        
        .modal-header {
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-body {
            padding: 30px;
        }
        
        .close {
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .close:hover {
            color: #ddd;
        }
        
        .payment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .month-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .month-card.paid {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        
        .month-card.unpaid:hover {
            background: #fff3cd;
            transform: scale(1.05);
        }
        
        .month-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .month-status {
            font-size: 0.8em;
        }
        
        .client-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .client-info h3 {
            color: #4CAF50;
            margin-bottom: 15px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e1e5e9;
        }
        
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .payment-summary {
            background: white;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #4CAF50;
            margin-bottom: 20px;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .total {
            font-weight: bold;
            font-size: 1.2em;
            color: #4CAF50;
            border-top: 2px solid #4CAF50;
            padding-top: 10px;
        }
        
        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .payment-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .modal-content {
                width: 95%;
                margin: 2% auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚗 Parking Ezaka</h1>
            <p> Suivi des paiements de parking mensuels </p>
        </div>
        
        <div class="nav-tabs">
            <button class="nav-tab active" onclick="switchTab('dashboard')">📊 Tableau de Bord</button>
            <button class="nav-tab" onclick="switchTab('clients')">👥 Clients</button>
            <button class="nav-tab" onclick="switchTab('add-client')">➕ Nouveau Client</button>
        </div>
        
        <!-- Dashboard Tab -->
        <div id="dashboard" class="tab-content active">
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number" id="totalClients">-</div>
                    <div>Total Clients</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="paidThisMonth">-</div>
                    <div>Payés ce mois</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="pendingPayments">-</div>
                    <div>En attente</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="monthlyRevenue">- Ar</div>
                    <div>Revenus du mois</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="yearlyRevenue">- Ar</div>
                    <div>Revenus annuels</div>
                </div>
            </div>
            
            <div class="loading" id="statsLoading">Chargement des statistiques...</div>
            <div class="empty-state" id="dashboardEmpty" style="display: none;">
                <h3>📊 Tableau de bord vide</h3>
                <p>Ajoutez des clients pour voir les statistiques</p>
            </div>
        </div>
        
        <!-- Clients Tab -->
        <div id="clients" class="tab-content">
            <div class="list-section">
                <h2>👥 Liste des Clients</h2>
                <input type="text" class="search-box" id="searchBox" placeholder="🔍 Rechercher un client...">
                <div id="clientsList">
                    <div class="loading">Chargement des clients...</div>
                </div>
            </div>
        </div>
        
        <!-- Add Client Tab -->
        <div id="add-client" class="tab-content">
            <div class="form-section" style="max-width: 600px; margin: 0 auto;">
                <h2 id="formTitle">➕ Nouveau Client</h2>
                <div id="formMessage"></div>
                <form id="clientForm">
                    <input type="hidden" id="clientId" value="">
                    <div class="input-group">
                        <label for="clientName">Nom du client</label>
                        <input type="text" id="clientName" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="phoneNumber">Téléphone</label>
                        <input type="tel" id="phoneNumber" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="carModel">Modèle de voiture</label>
                        <input type="text" id="carModel">
                    </div>
                    
                    <div class="input-group">
                        <label for="plateNumber">Numéro de plaque</label>
                        <input type="text" id="plateNumber" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="monthlyFee">Tarif mensuel (Ariary)</label>
                        <input type="number" id="monthlyFee" value="30000" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="parkingSpot">Place de parking</label>
                        <input type="text" id="parkingSpot">
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn" id="submitBtn">Ajouter Client</button>
                        <button type="button" class="btn btn-warning" id="cancelBtn" style="display: none;" onclick="cancelEdit()">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal pour fiche client -->
    <div id="clientModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Fiche Client</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="loading">Chargement...</div>
            </div>
        </div>
    </div>

    <script>
        // Variables globales
        let currentYear = new Date().getFullYear();
        let currentMonth = new Date().getMonth() + 1;
        let availableYears = [2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030];
        let editingClientId = null;
        let searchTimeout = null;

        const months = [
            'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
        ];

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            loadClients();
            loadStats();
        });

        // API Helper Functions
        async function apiRequest(url, method = 'GET', data = null) {
            const options = {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                }
            };

            if (data) {
                options.body = JSON.stringify(data);
            }

            try {
                const response = await fetch(url, options);
                const result = await response.json();
                
                if (!response.ok) {
                    throw new Error(result.message || 'Erreur réseau');
                }
                
                return result;
            } catch (error) {
                console.error('Erreur API:', error);
                throw error;
            }
        }

        // Navigation entre onglets
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            document.querySelectorAll('.nav-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
            
            if (tabName === 'clients') {
                loadClients();
            } else if (tabName === 'dashboard') {
                loadStats();
            }
        }

        // Format des montants
        function formatCurrency(amount) {
            return new Intl.NumberFormat('fr-MG', {
                style: 'decimal',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount || 0) + ' Ar';
        }

        // Chargement des clients
        async function loadClients(searchTerm = '') {
            try {
                const url = searchTerm ? `api.php?action=clients&search=${encodeURIComponent(searchTerm)}` : 'api.php?action=clients';
                const response = await apiRequest(url);
                renderClients(response.data);
            } catch (error) {
                document.getElementById('clientsList').innerHTML = `
                    <div class="error">Erreur lors du chargement des clients: ${error.message}</div>
                `;
            }
        }

        // Affichage des clients
        function renderClients(clients) {
            const clientsList = document.getElementById('clientsList');
            
            if (!clients || clients.length === 0) {
                clientsList.innerHTML = `
                    <div class="empty-state">
                        <h3>👥 Aucun client</h3>
                        <p>Commencez par ajouter votre premier client</p>
                    </div>
                `;
                return;
            }

            clientsList.innerHTML = clients.map(client => `
                <div class="client-item" onclick="openClientModal(${client.id})">
                    <div class="client-name">${client.name}</div>
                    <div class="client-details">
                        📱 ${client.phone} • 🚗 ${client.car_model} (${client.plate_number})<br>
                        🅿️ Place ${client.parking_spot || 'Non assignée'} • ${formatCurrency(client.monthly_fee)}/mois
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-warning" onclick="event.stopPropagation(); editClient(${client.id})">✏️ Modifier</button>
                        <button class="btn btn-danger" onclick="event.stopPropagation(); deleteClient(${client.id}, '${client.name}')">🗑️ Supprimer</button>
                    </div>
                </div>
            `).join('');
        }

        // Chargement des statistiques
        async function loadStats() {
            try {
                const response = await apiRequest(`api.php?action=stats&year=${currentYear}&month=${currentMonth}`);
                const stats = response.data;
                
                document.getElementById('totalClients').textContent = stats.totalClients;
                document.getElementById('paidThisMonth').textContent = stats.paidThisMonth;
                document.getElementById('pendingPayments').textContent = stats.pendingPayments;
                document.getElementById('monthlyRevenue').textContent = formatCurrency(stats.monthlyRevenue);
                document.getElementById('yearlyRevenue').textContent = formatCurrency(stats.yearlyRevenue);
                
                document.getElementById('statsLoading').style.display = 'none';
                
                if (stats.totalClients === 0) {
                    document.getElementById('dashboardEmpty').style.display = 'block';
                } else {
                    document.getElementById('dashboardEmpty').style.display = 'none';
                }
            } catch (error) {
                document.getElementById('statsLoading').innerHTML = `
                    <div class="error">Erreur lors du chargement des statistiques: ${error.message}</div>
                `;
            }
        }

        // Gestion du formulaire
        document.getElementById('clientForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = {
                name: document.getElementById('clientName').value,
                phone: document.getElementById('phoneNumber').value,
                car_model: document.getElementById('carModel').value,
                plate_number: document.getElementById('plateNumber').value,
                monthly_fee: parseFloat(document.getElementById('monthlyFee').value),
                parking_spot: document.getElementById('parkingSpot').value
            };

            try {
                let response;
                if (editingClientId) {
                    response = await apiRequest(`api.php?action=client&id=${editingClientId}`, 'PUT', formData);
                } else {
                    response = await apiRequest('api.php?action=client', 'POST', formData);
                }
                
                showMessage(response.message, 'success');
                this.reset();
                document.getElementById('monthlyFee').value = '30000';
                cancelEdit();
                loadClients();
                loadStats();
            } catch (error) {
                showMessage(error.message, 'error');
            }
        });

        // Modifier un client
        async function editClient(id) {
            try {
                const response = await apiRequest(`api.php?action=client&id=${id}`);
                const client = response.data;
                
                document.getElementById('clientId').value = client.id;
                document.getElementById('clientName').value = client.name;
                document.getElementById('phoneNumber').value = client.phone;
                document.getElementById('carModel').value = client.car_model;
                document.getElementById('plateNumber').value = client.plate_number;
                document.getElementById('monthlyFee').value = client.monthly_fee;
                document.getElementById('parkingSpot').value = client.parking_spot;
                
                document.getElementById('formTitle').textContent = '✏️ Modifier Client';
                document.getElementById('submitBtn').textContent = 'Modifier Client';
                document.getElementById('cancelBtn').style.display = 'inline-block';
                
                editingClientId = id;
                switchTab('add-client');
            } catch (error) {
                showMessage(error.message, 'error');
            }
        }

        // Annuler la modification
        function cancelEdit() {
            document.getElementById('clientForm').reset();
            document.getElementById('monthlyFee').value = '30000';
            document.getElementById('formTitle').textContent = '➕ Nouveau Client';
            document.getElementById('submitBtn').textContent = 'Ajouter Client';
            document.getElementById('cancelBtn').style.display = 'none';
            editingClientId = null;
            clearMessage();
        }

        // Supprimer un client
        async function deleteClient(id, name) {
            if (confirm(`Êtes-vous sûr de vouloir supprimer le client "${name}" ? Cette action est irréversible.`)) {
                try {
                    const response = await apiRequest(`api.php?action=client&id=${id}`, 'DELETE');
                    showMessage(response.message, 'success');
                    loadClients();
                    loadStats();
                } catch (error) {
                    showMessage(error.message, 'error');
                }
            }
        }

        // Recherche
        document.getElementById('searchBox').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadClients(e.target.value);
            }, 300);
        });

        // Ouvrir la modal client
        async function openClientModal(clientId) {
            try {
                document.getElementById('clientModal').style.display = 'block';
                document.getElementById('modalBody').innerHTML = '<div class="loading">Chargement...</div>';
                
                const [clientResponse, paymentsResponse] = await Promise.all([
                    apiRequest(`api.php?action=client&id=${clientId}`),
                    apiRequest(`api.php?action=payments&client_id=${clientId}&year=${currentYear}`)
                ]);
                
                const client = clientResponse.data;
                const payments = paymentsResponse.data;
                
                document.getElementById('modalTitle').textContent = `Fiche de ${client.name}`;
                renderClientModal(client, payments);
            } catch (error) {
                document.getElementById('modalBody').innerHTML = `
                    <div class="error">Erreur lors du chargement: ${error.message}</div>
                `;
            }
        }

        // Afficher la modal client
        function renderClientModal(client, payments) {
            const paymentsByMonth = {};
            payments.forEach(payment => {
                paymentsByMonth[payment.month] = payment.is_paid;
            });
            
            const paidMonths = payments.filter(p => p.is_paid).length;
            const totalRevenue = paidMonths * client.monthly_fee;
            
            document.getElementById('modalBody').innerHTML = `
                <div class="client-info">
                    <h3>📋 Informations Client</h3>
                    <div class="info-row">
                        <span><strong>Nom:</strong></span>
                        <span>${client.name}</span>
                    </div>
                    <div class="info-row">
                        <span><strong>Téléphone:</strong></span>
                        <span>${client.phone}</span>
                    </div>
                    <div class="info-row">
                        <span><strong>Voiture:</strong></span>
                        <span>${client.car_model}</span>
                    </div>
                    <div class="info-row">
                        <span><strong>Plaque:</strong></span>
                        <span>${client.plate_number}</span>
                    </div>
                    <div class="info-row">
                        <span><strong>Place:</strong></span>
                        <span>${client.parking_spot || 'Non assignée'}</span>
                    </div>
                    <div class="info-row">
                        <span><strong>Tarif mensuel:</strong></span>
                        <span>${formatCurrency(client.monthly_fee)}</span>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label for="yearSelector" style="font-weight: bold; margin-right: 10px;">📅 Choisir l'année:</label>
                    <select id="yearSelector" onchange="changeYear(${client.id}, this.value)" style="padding: 8px; border-radius: 5px; border: 2px solid #4CAF50;">
                        ${availableYears.map(year => `
                            <option value="${year}" ${year === currentYear ? 'selected' : ''}>${year}</option>
                        `).join('')}
                    </select>
                </div>
                
                <div class="payment-summary">
                    <h3>💰 Résumé des paiements ${currentYear}</h3>
                    <div class="summary-item">
                        <span>Mois payés:</span>
                        <span>${paidMonths}/12</span>
                    </div>
                    <div class="summary-item">
                        <span>Revenus générés:</span>
                        <span>${formatCurrency(totalRevenue)}</span>
                    </div>
                    <div class="summary-item total">
                        <span>Revenus annuels potentiels:</span>
                        <span>${formatCurrency(client.monthly_fee * 12)}</span>
                    </div>
                </div>
                
                <h3>📅 Calendrier de paiement ${currentYear}</h3>
                <div class="payment-grid">
                    ${months.map((month, index) => {
                        const monthNum = index + 1;
                        const isPaid = paymentsByMonth[monthNum] || false;
                        return `
                            <div class="month-card ${isPaid ? 'paid' : 'unpaid'}" 
                                 onclick="togglePayment(${client.id}, ${monthNum})">
                                <div class="month-name">${month}</div>
                                <div class="month-status">
                                    ${isPaid ? '✅ Payé' : '❌ Non payé'}
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            `;
        }

        // Changer d'année
        async function changeYear(clientId, newYear) {
            currentYear = parseInt(newYear);
            
            // Initialiser les paiements pour cette année si nécessaire
            try {
                await apiRequest('api.php?action=init_year_payments', 'POST', {
                    client_id: clientId,
                    year: currentYear
                });
            } catch (error) {
                console.log('Paiements déjà initialisés pour cette année');
            }
            
            openClientModal(clientId);
        }

        // Basculer le paiement
        async function togglePayment(clientId, month) {
            try {
                await apiRequest('api.php?action=toggle_payment', 'POST', {
                    client_id: clientId,
                    year: currentYear,
                    month: month
                });
                
                openClientModal(clientId);
                loadStats();
            } catch (error) {
                showMessage(error.message, 'error');
            }
        }

        // Fermer la modal
        function closeModal() {
            document.getElementById('clientModal').style.display = 'none';
        }

        // Afficher un message
        function showMessage(message, type) {
            const messageDiv = document.getElementById('formMessage');
            messageDiv.className = type;
            messageDiv.textContent = message;
            messageDiv.style.display = 'block';
            
            setTimeout(() => {
                clearMessage();
            }, 5000);
        }

        // Effacer le message
        function clearMessage() {
            const messageDiv = document.getElementById('formMessage');
            messageDiv.style.display = 'none';
        }

        // Fermer la modal en cliquant à l'extérieur
        window.onclick = function(event) {
            const modal = document.getElementById('clientModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>