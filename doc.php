<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Startup Documents - Modern Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-title h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header-title .icon {
            font-size: 2.5rem;
            color: #667eea;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.9);
            color: #667eea;
            border: 1px solid rgba(102, 126, 234, 0.3);
        }

        .btn-secondary:hover {
            background: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .search-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .search-form {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .search-input {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 12px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-btn {
            padding: 15px 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .documents-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .doc-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .doc-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .doc-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .doc-card h3 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .doc-card h3 i {
            color: #667eea;
            font-size: 1.2rem;
        }

        .doc-card p {
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .doc-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .download-btn {
            padding: 10px 20px;
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.4);
        }

        .no-documents {
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 60px 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .no-documents i {
            font-size: 4rem;
            color: #cbd5e0;
            margin-bottom: 20px;
        }

        .no-documents h3 {
            font-size: 1.5rem;
            color: #4a5568;
            margin-bottom: 10px;
        }

        .no-documents p {
            color: #718096;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            z-index: 1000;
            animation: fadeIn 0.3s ease;
        }

        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 20px;
            padding: 0;
            z-index: 1001;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 500px;
            animation: slideIn 0.3s ease;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px;
            border-radius: 50%;
            transition: background 0.3s ease;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .modal-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2d3748;
            font-size: 14px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(248, 250, 252, 0.8);
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .btn-cancel {
            background: #e2e8f0;
            color: #4a5568;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #cbd5e0;
            transform: translateY(-1px);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { 
                opacity: 0;
                transform: translate(-50%, -60%);
            }
            to { 
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .header-title h1 {
                font-size: 2rem;
            }

            .search-form {
                flex-direction: column;
            }

            .documents-grid {
                grid-template-columns: 1fr;
            }

            .doc-actions {
                flex-direction: column;
            }

            .modal {
                width: 95%;
                margin: 10px;
            }
        }
    </style>
</head>
<body>
<header class="header">
        <div class="logo">Pitchus<span>.ai</span></div>
        
        <nav class="nav-links">
            <a href="index.php" style="text-decoration: none;"><div class="nav-item ">Home</div></a>
            <a href="mes.php" style="text-decoration: none;"><div class="nav-item ">Message</div></a>
            <a href="news.php" style="text-decoration: none;"><div class="nav-item ">News</div></a>
            <a href="jobs.php" style="text-decoration: none;"><div class="nav-item ">Jobs</div></a>
            <a href="ai.php" style="text-decoration: none;"><div class="nav-item ">AI</div></a>
            <a href="doc.php" style="text-decoration: none;"><div class="nav-item active">Documents</div></a>
        </nav>
        
        <div class="user-controls">
            <div class="search-bar">
                <div class="search-icon">🔍</div>
                <input type="text" placeholder="Search startups...">
            </div>
            <div class="user-avatar">PD</div>
        </div>
    </header>
    <div class="container">
    <button onclick="openModal()" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Add Document
                    </button>
                    <br>
<br>
        <div class="search-section">
            <form method="GET" class="search-form">
                <input type="text" 
                       name="search" 
                       class="search-input"
                       placeholder="Search documents by name or description..." 
                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                    Search
                </button>
            </form>
        </div>

        <div class="documents-grid">
            <?php
            $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
            $query = "SELECT * FROM startup_documents";
            if ($search != '') {
                $query .= " WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
            }
            $query .= " ORDER BY created_at DESC";

            $result = $conn->query($query);
            $documentCount = $result->num_rows;
            
            if ($documentCount > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='doc-card'>";
                    echo "<h3><i class='fas fa-file-pdf'></i>" . htmlspecialchars($row['name']) . "</h3>";
                    echo "<p>" . nl2br(htmlspecialchars($row['description'])) . "</p>";
                    echo "<div class='doc-actions'>";
                    echo "<a href='" . htmlspecialchars($row['download_link']) . "' target='_blank' class='download-btn'>";
                    echo "<i class='fas fa-download'></i> Download";
                    echo "</a>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='no-documents' style='grid-column: 1 / -1;'>";
                echo "<i class='fas fa-folder-open'></i>";
                echo "<h3>No Documents Found</h3>";
                if ($search != '') {
                    echo "<p>No documents match your search criteria. Try adjusting your search terms.</p>";
                } else {
                    echo "<p>Start by adding your first document to get organized!</p>";
                }
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal-overlay" class="modal-overlay"></div>
    <div id="modal" class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle"></i> Add New Document</h3>
            <button class="close-btn" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form action="submit_document.php" method="POST">
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-file-alt"></i> Document Name
                    </label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="description">
                        <i class="fas fa-align-left"></i> Description
                    </label>
                    <textarea id="description" name="description" required placeholder="Describe what this document contains..."></textarea>
                </div>

                <div class="form-group">
                    <label for="download_link">
                        <i class="fas fa-link"></i> Download Link (URL)
                    </label>
                    <input type="url" id="download_link" name="download_link" required placeholder="https://example.com/document.pdf">
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Add Document
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modal-overlay').style.display = 'block';
            document.getElementById('modal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('modal-overlay').style.display = 'none';
            document.getElementById('modal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking overlay
        document.getElementById('modal-overlay').addEventListener('click', closeModal);

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // Prevent modal from closing when clicking inside modal
        document.getElementById('modal').addEventListener('click', function(event) {
            event.stopPropagation();
        });

        // Auto-focus first input when modal opens
        function openModal() {
            document.getElementById('modal-overlay').style.display = 'block';
            document.getElementById('modal').style.display = 'block';
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                document.getElementById('name').focus();
            }, 100);
        }
    </script>
</body>
</html>