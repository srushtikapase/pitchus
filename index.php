<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pitchus.ai | The Future of Startup Investing</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="logo">Pitchus<span>.ai</span></div>
        
        <nav class="nav-links">
            <a href="index.php" style="text-decoration: none;"><div class="nav-item active">Home</div></a>
            <a href="mes.php" style="text-decoration: none;"><div class="nav-item ">Message</div></a>
            <a href="news.php" style="text-decoration: none;"><div class="nav-item ">News</div></a>
            <a href="jobs.php" style="text-decoration: none;"><div class="nav-item ">Jobs</div></a>
            <a href="ai.php" style="text-decoration: none;"><div class="nav-item ">AI</div></a>
            <a href="doc.php" style="text-decoration: none;"><div class="nav-item ">Documents</div></a>
        </nav>
        
        <div class="user-controls">
            <div class="search-bar">
                <div class="search-icon">🔍</div>
                <input type="text" placeholder="Search startups...">
            </div>
            <div class="user-avatar">PD</div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Left Column -->
        <div class="left-column">
            <!-- Featured Pitch -->
            <div class="featured-pitch">
                <div class="featured-badge">LIVE NOW</div>
                <div class="featured-tag">AI Technology</div>
                <h2 class="featured-title">Join Live Call</h2>
                <p class="featured-desc">Pitch revolutionary idea interface technology that allows direct communication between the human brain and computers.</p>
                
                <div class="featured-meta">
                    <div class="featured-founder">
                        <div class="founder-avatar">EP</div>
                        <div class="founder-name">Elon Parker</div>
                    </div>
                    <div class="featured-stats">
                        <div class="stat">👁 1.2K</div>
                        <div class="stat">💬 84</div>
                        <div class="stat">💰 $2.5M</div>
                    </div>
                </div>
                
                <div class="featured-actions">
                    <button class="action-btn primary-btn">Join Pitch</button>
                    <button class="action-btn secondary-btn">+ Follow</button>
                </div>
            </div>

            <!-- Trending Pitches -->
            <h2 class="section-title">
                <div class="icon">🔥</div>
                Investors
            </h2>
            
            <div class="pitches-grid">
                <?php
                $result = $conn->query("SELECT * FROM investors ORDER BY created_at DESC");
                while($row = $result->fetch_assoc()) {
                    $initials = strtoupper(substr($row['name'], 0, 2));
                
                    echo "<div class='pitch-card'>";
                        echo "<div class='pitch-image'>";
                            echo "<img src='assets/uploads/investors/{$row['photo']}' height='150' style='width:100%; object-fit:cover; border-radius:10px;'>";
                            echo "<div class='pitch-badge'>INVESTOR</div>";
                        echo "</div>";
                
                        echo "<div class='pitch-content'>";
                            echo "<h3 class='pitch-title'>" . htmlspecialchars($row['name']) . "</h3>";
                            echo "<p class='pitch-desc'>" . nl2br(htmlspecialchars($row['description'])) . "</p>";
                
                            echo "<div class='pitch-meta'>";
                                echo "<div class='pitch-founder'>";
                                    echo "<div class='pitch-founder-avatar'>$initials</div>";
                                    echo "<div>" . htmlspecialchars($row['company']) . "</div>";
                                echo "</div>";
                                echo "<div class='pitch-stats'>";
                                    echo "<div class='pitch-stat'>📞 " . htmlspecialchars($row['contact']) . "</div>";
                                echo "</div>";
                            echo "</div>";
                
                        echo "</div>";
                    echo "</div>";
                }
                ?>
                </div>

            <!-- For You Section -->
            <h2 class="section-title">
                <div class="icon">✨</div>
                Trending Pitches
            </h2>
            
            <div class="pitches-grid">
                <?php
                $result = $conn->query("SELECT s.*, i.name as investor_name FROM startups s LEFT JOIN investors i ON s.investor_id = i.id ORDER BY s.created_at DESC");
                while($row = $result->fetch_assoc()) {
                    $progress = ($row['fund_raised'] / $row['pitch_amount']) * 100;
                    $progress = min(100, round($progress, 2));
                    $initials = strtoupper(substr($row['name'], 0, 2));
                    $status_badge = $progress >= 100 ? "FUNDED" : ($progress >= 50 ? "LIVE" : "UPCOMING");
                
                    echo "<div class='pitch-card'>";
                        echo "<div class='pitch-image'>";
                            echo "<div class='pitch-badge'>$status_badge</div>";
                            echo "<img src='assets/uploads/startups/{$row['product_photo']}' height='150' style='width:100%; object-fit:cover; border-radius:10px;'>";
                        echo "</div>";
                
                        echo "<div class='pitch-content'>";
                            echo "<h3 class='pitch-title'>" . htmlspecialchars($row['name']) . "</h3>";
                            echo "<p class='pitch-desc'>" . nl2br(htmlspecialchars($row['idea'])) . "</p>";
                
                            echo "<div class='pitch-meta'>";
                                echo "<div class='pitch-founder'>";
                                    echo "<div class='pitch-founder-avatar'>$initials</div>";
                                    echo "<div>" . htmlspecialchars($row['investor_name']) . "</div>";
                                echo "</div>";
                                echo "<div class='pitch-stats'>";
                                    echo "<div class='pitch-stat'>💰 {$row['fund_raised']} / {$row['pitch_amount']} ETH</div>";
                                    echo "<div class='pitch-stat'>📈 {$progress}%</div>";
                                echo "</div>";
                            echo "</div>";
                
                            echo "<progress value='$progress' max='100' style='width: 100%; height: 10px;'></progress>";
                            echo "<br><button onclick=\"fundStartup('{$row['wallet_address']}', {$row['id']}, {$row['pitch_amount']})\">💰 Fund This Startup</button>";
                
                            if (!empty($row['funding_tx'])) {
                                echo "<details><summary>Funding Logs</summary><pre>" . htmlspecialchars($row['funding_tx']) . "</pre></details>";
                            }
                        echo "</div>";
                    echo "</div>";
                }
                ?>
                </div>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- AI Assistant -->
    

            <!-- Crowdfunding Widget -->
            <div class="crowdfunding-widget">
                <h3>Join Now </h3>
                
                <a href="list_investor.php"><button class="cf-btn" >List Yourself as Investor</button></a>
                <br>
                <br>
                <a href="list_startup.php"><button class="cf-btn">Pitch Startup Idea</button></a>
            </div>

            <!-- Resources Widget -->
            <div class="resources-widget">
                <h3>Resources</h3>
                <div class="resource-item">
                    <div class="resource-icon">📚</div>
                    <div>
                        <div class="resource-title">Pitch Deck Guide</div>
                        <div class="resource-desc">How to create winning decks</div>
                    </div>
                </div>
                <div class="resource-item">
                    <div class="resource-icon">🎥</div>
                    <div>
                        <div class="resource-title">Pitch Videos</div>
                        <div class="resource-desc">Learn from the best</div>
                    </div>
                </div>
                <div class="resource-item">
                    <div class="resource-icon">💼</div>
                    <div>
                        <div class="resource-title">Investor Database</div>
                        <div class="resource-desc">Find perfect matches</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
<script>
    async function fundStartup(wallet, startupId, target) {
        if (typeof window.ethereum === 'undefined') {
            alert('Please install MetaMask.');
            return;
        }
    
        const [account] = await ethereum.request({ method: 'eth_requestAccounts' });
        const amount = prompt("Enter amount to fund in ETH:");
        if (!amount || isNaN(amount) || parseFloat(amount) <= 0) {
            alert("Invalid amount.");
            return;
        }
    
        const weiAmount = (parseFloat(amount) * 1e18).toString();
        const txParams = {
            to: wallet,
            from: account,
            value: '0x' + parseInt(weiAmount).toString(16)
        };
    
        try {
            const txHash = await ethereum.request({ method: 'eth_sendTransaction', params: [txParams] });
            const form = new FormData();
            form.append("startup_id", startupId);
            form.append("amount", amount);
            form.append("tx_hash", txHash);
    
            await fetch("record_funding.php", { method: "POST", body: form });
            alert("Transaction successful: " + txHash);
            location.reload();
        } catch (err) {
            alert("Transaction failed: " + err.message);
        }
    }
    </script>
</html>