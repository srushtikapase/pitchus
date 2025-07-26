<?php
// Configuration
$rapidapi_key = "61ee1c4518msh5d5083b2d788379p18c056jsn4fca2f1bb54c";
$rapidapi_host = "fresh-linkedin-profile-data.p.rapidapi.com";

// Function to make API calls
function makeApiCall($endpoint, $params = []) {
    global $rapidapi_key, $rapidapi_host;
    
    $url = "https://" . $rapidapi_host . "/" . $endpoint;
    if (!empty($params)) {
        $url .= "?" . http_build_query($params);
    }
    
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: " . $rapidapi_host,
            "X-RapidAPI-Key: " . $rapidapi_key
        ],
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    
    if ($err) {
        return false;
    } else {
        return json_decode($response, true);
    }
}

// Handle form submissions
$posts = [];
$companies = [];
$search_query = isset($_GET['search']) ? $_GET['search'] : 'startups';
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$time_filter = isset($_GET['time_filter']) ? $_GET['time_filter'] : 'week';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'recent';

// Popular startup and business LinkedIn profiles/companies to fetch data from
$startup_profiles = [
    'techcrunch' => 'TechCrunch',
    'ycombinator' => 'Y Combinator',
    'sequoiacap' => 'Sequoia Capital',
    'a16z' => 'Andreessen Horowitz',
    '500global' => '500 Global',
    'accel' => 'Accel',
    'greylock' => 'Greylock Partners',
    'founders-fund' => 'Founders Fund'
];

// Build API parameters for LinkedIn data
$api_params = [];
if (!empty($search_query)) {
    $api_params['q'] = $search_query;
}

// Try to fetch company posts or profile updates
$selected_profile = array_rand($startup_profiles); // Random profile for demo
$api_response = makeApiCall('get-profile-posts', ['linkedin_url' => 'https://www.linkedin.com/company/' . $selected_profile]);

if ($api_response && isset($api_response['data'])) {
    $posts = $api_response['data'];
} else {
    // Fallback: create sample startup news data if API fails
    $posts = [
        [
            'id' => '1',
            'author' => 'TechCrunch',
            'author_image' => 'https://via.placeholder.com/50x50/0077B5/FFFFFF?text=TC',
            'content' => 'Breaking: AI startup raises $50M Series A to revolutionize healthcare diagnostics. The company uses machine learning to analyze medical imaging with 95% accuracy.',
            'engagement' => ['likes' => 1250, 'comments' => 89, 'shares' => 156],
            'posted_time' => '2024-07-05T10:30:00Z',
            'media_type' => 'text',
            'category' => 'funding'
        ],
        [
            'id' => '2',
            'author' => 'Y Combinator',
            'author_image' => 'https://via.placeholder.com/50x50/FF6600/FFFFFF?text=YC',
            'content' => 'Meet the Winter 2024 batch! 250 startups from 40 countries are building the future across AI, fintech, climate tech, and more. Demo Day is next week!',
            'engagement' => ['likes' => 2100, 'comments' => 145, 'shares' => 89],
            'posted_time' => '2024-07-04T14:15:00Z',
            'media_type' => 'image',
            'category' => 'accelerator'
        ],
        [
            'id' => '3',
            'author' => 'Sequoia Capital',
            'author_image' => 'https://via.placeholder.com/50x50/1B4D3E/FFFFFF?text=SC',
            'content' => 'The future of work is here. Remote collaboration tools are seeing unprecedented growth as companies embrace hybrid models. Our portfolio companies are leading this transformation.',
            'engagement' => ['likes' => 890, 'comments' => 67, 'shares' => 234],
            'posted_time' => '2024-07-04T09:00:00Z',
            'media_type' => 'text',
            'category' => 'trends'
        ],
        [
            'id' => '4',
            'author' => 'Andreessen Horowitz',
            'author_image' => 'https://via.placeholder.com/50x50/8B4513/FFFFFF?text=A16Z',
            'content' => 'Crypto winter is over. DeFi protocols are showing strong recovery with total value locked reaching $80B. Our thesis on decentralized finance is playing out.',
            'engagement' => ['likes' => 1580, 'comments' => 203, 'shares' => 67],
            'posted_time' => '2024-07-03T16:45:00Z',
            'media_type' => 'text',
            'category' => 'crypto'
        ],
        [
            'id' => '5',
            'author' => '500 Global',
            'author_image' => 'https://via.placeholder.com/50x50/FF4500/FFFFFF?text=500',
            'content' => 'Southeast Asia startup ecosystem is booming! 🚀 Total funding reached $8.2B in H1 2024. E-commerce, fintech, and logistics are driving growth across the region.',
            'engagement' => ['likes' => 756, 'comments' => 45, 'shares' => 123],
            'posted_time' => '2024-07-03T11:20:00Z',
            'media_type' => 'text',
            'category' => 'markets'
        ],
        [
            'id' => '6',
            'author' => 'Greylock Partners',
            'author_image' => 'https://via.placeholder.com/50x50/4B0082/FFFFFF?text=GL',
            'content' => 'Enterprise AI adoption is accelerating faster than expected. 78% of Fortune 500 companies now have AI initiatives in production. The transformation is real.',
            'engagement' => ['likes' => 1890, 'comments' => 156, 'shares' => 289],
            'posted_time' => '2024-07-02T13:30:00Z',
            'media_type' => 'text',
            'category' => 'enterprise'
        ]
    ];
}

// Filter and sort posts
if ($category !== 'all') {
    $posts = array_filter($posts, function($post) use ($category) {
        return isset($post['category']) && $post['category'] === $category;
    });
}

// Sort posts
if ($sort_by === 'engagement') {
    usort($posts, function($a, $b) {
        $engagement_a = isset($a['engagement']) ? array_sum($a['engagement']) : 0;
        $engagement_b = isset($b['engagement']) ? array_sum($b['engagement']) : 0;
        return $engagement_b - $engagement_a;
    });
}

// Categories for filtering
$categories = [
    'all' => 'All Categories',
    'funding' => 'Funding News',
    'accelerator' => 'Accelerators',
    'trends' => 'Industry Trends',
    'crypto' => 'Crypto/Web3',
    'markets' => 'Markets',
    'enterprise' => 'Enterprise'
];

// Time filters
$time_filters = [
    'today' => 'Today',
    'week' => 'This Week',
    'month' => 'This Month',
    'all' => 'All Time'
];

// Helper function to format time
function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    $units = array(
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );
    
    foreach ($units as $unit => $val) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return ($val == 'second') ? 'just now' : 
               (($numberOfUnits > 1) ? $numberOfUnits . ' ' . $val . 's ago' : 'one ' . $val . ' ago');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LinkedIn Startup News Feed</title>
    <link rel="stylesheet" href="style.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f2ef;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        

        .linkedin-icon {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #0077B5;
            font-size: 1.2rem;
        }

        
        .controls {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .controls-form {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr auto;
            gap: 20px;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group select {
            padding: 12px;
            border: 2px solid #e1e9ee;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #0077B5;
            box-shadow: 0 0 0 3px rgba(0,119,181,0.1);
        }

        .search-btn {
            background: #0077B5;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .search-btn:hover {
            background: #005885;
        }

        .feed-container {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 30px;
        }

        .main-feed {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .feed-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e1e9ee;
            background: #f8f9fa;
        }

        .feed-stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .feed-count {
            font-size: 1.1rem;
            font-weight: 600;
            color: #0077B5;
        }

        .feed-refresh {
            background: #28a745;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
        }

        .post {
            padding: 25px;
            border-bottom: 1px solid #e1e9ee;
            transition: background-color 0.2s;
        }

        .post:hover {
            background-color: #f8f9fa;
        }

        .post:last-child {
            border-bottom: none;
        }

        .post-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #0077B5;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }

        .post-author-info {
            flex: 1;
        }

        .post-author {
            font-weight: 600;
            color: #333;
            font-size: 15px;
        }

        .post-time {
            color: #666;
            font-size: 12px;
        }

        .post-category {
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .post-content {
            margin-bottom: 20px;
            line-height: 1.6;
            color: #333;
            font-size: 14px;
        }

        .post-engagement {
            display: flex;
            gap: 25px;
            padding-top: 15px;
            border-top: 1px solid #e1e9ee;
        }

        .engagement-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #666;
            font-size: 12px;
            cursor: pointer;
            transition: color 0.2s;
        }

        .engagement-item:hover {
            color: #0077B5;
        }

        .engagement-icon {
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .sidebar-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .sidebar-card h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .trending-item {
            padding: 12px 0;
            border-bottom: 1px solid #e1e9ee;
        }

        .trending-item:last-child {
            border-bottom: none;
        }

        .trending-title {
            font-weight: 600;
            color: #333;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .trending-stats {
            color: #666;
            font-size: 11px;
        }

        .no-posts {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-posts h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .controls-form {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .feed-container {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .post-header {
                flex-wrap: wrap;
            }
            
            .post-category {
                margin-top: 5px;
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
            <a href="news.php" style="text-decoration: none;"><div class="nav-item active">News</div></a>
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
    <br><br><br><br><br>

    <div class="container">
        <div class="controls">
            <form class="controls-form" method="GET">
                <div class="form-group">
                    <label for="search">Search Topics</label>
                    <input type="text" id="search" name="search" placeholder="e.g. AI, fintech, climate tech" value="<?php echo htmlspecialchars($search_query); ?>">
                </div>
                
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category">
                        <?php foreach ($categories as $cat_value => $cat_label): ?>
                            <option value="<?php echo htmlspecialchars($cat_value); ?>" <?php echo $category === $cat_value ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat_label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="time_filter">Time Period</label>
                    <select id="time_filter" name="time_filter">
                        <?php foreach ($time_filters as $time_value => $time_label): ?>
                            <option value="<?php echo htmlspecialchars($time_value); ?>" <?php echo $time_filter === $time_value ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($time_label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="sort_by">Sort By</label>
                    <select id="sort_by" name="sort_by">
                        <option value="recent" <?php echo $sort_by === 'recent' ? 'selected' : ''; ?>>Most Recent</option>
                        <option value="engagement" <?php echo $sort_by === 'engagement' ? 'selected' : ''; ?>>Most Engaged</option>
                    </select>
                </div>
                
                <button type="submit" class="search-btn">Update Feed</button>
            </form>
        </div>

        <div class="feed-container">
            <div class="main-feed">
                <?php if ($api_response === false): ?>
                    <div class="error-message">
                        <strong>Note:</strong> Using sample data. LinkedIn API connection could not be established.
                    </div>
                <?php endif; ?>

                <div class="feed-header">
                    <div class="feed-stats">
                        <div class="feed-count">
                            <?php echo count($posts); ?> posts found
                        </div>
                        <button class="feed-refresh" onclick="window.location.reload()">Refresh Feed</button>
                    </div>
                </div>

                <?php if (empty($posts)): ?>
                    <div class="no-posts">
                        <h3>No posts found</h3>
                        <p>Try adjusting your search criteria or category filters.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="post">
                            <div class="post-header">
                                <div class="author-avatar">
                                    <?php echo strtoupper(substr($post['author'], 0, 2)); ?>
                                </div>
                                <div class="post-author-info">
                                    <div class="post-author"><?php echo htmlspecialchars($post['author']); ?></div>
                                    <div class="post-time"><?php echo timeAgo($post['posted_time']); ?></div>
                                </div>
                                <?php if (isset($post['category'])): ?>
                                    <div class="post-category"><?php echo htmlspecialchars($post['category']); ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="post-content">
                                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                            </div>
                            
                            <?php if (isset($post['engagement'])): ?>
                                <div class="post-engagement">
                                    <div class="engagement-item">
                                        <div class="engagement-icon">👍</div>
                                        <span><?php echo number_format($post['engagement']['likes']); ?> likes</span>
                                    </div>
                                    <div class="engagement-item">
                                        <div class="engagement-icon">💬</div>
                                        <span><?php echo number_format($post['engagement']['comments']); ?> comments</span>
                                    </div>
                                    <div class="engagement-item">
                                        <div class="engagement-icon">🔄</div>
                                        <span><?php echo number_format($post['engagement']['shares']); ?> shares</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="sidebar">
                <div class="sidebar-card">
                    <h3>Trending Topics</h3>
                    <div class="trending-item">
                        <div class="trending-title">AI Funding Surge</div>
                        <div class="trending-stats">2.5K posts • Trending</div>
                    </div>
                    <div class="trending-item">
                        <div class="trending-title">Remote Work Tools</div>
                        <div class="trending-stats">1.8K posts • Growing</div>
                    </div>
                    <div class="trending-item">
                        <div class="trending-title">Climate Tech</div>
                        <div class="trending-stats">1.2K posts • Hot</div>
                    </div>
                    <div class="trending-item">
                        <div class="trending-title">Fintech Innovation</div>
                        <div class="trending-stats">980 posts • Rising</div>
                    </div>
                </div>

                <div class="sidebar-card">
                    <h3>Top Startup Influencers</h3>
                    <?php foreach (array_slice($startup_profiles, 0, 5) as $profile => $name): ?>
                        <div class="trending-item">
                            <div class="trending-title"><?php echo htmlspecialchars($name); ?></div>
                            <div class="trending-stats">Following startup trends</div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="sidebar-card">
                    <h3>Quick Stats</h3>
                    <div class="trending-item">
                        <div class="trending-title">Total Posts Today</div>
                        <div class="trending-stats">1,247 posts</div>
                    </div>
                    <div class="trending-item">
                        <div class="trending-title">Active Discussions</div>
                        <div class="trending-stats">89 topics</div>
                    </div>
                    <div class="trending-item">
                        <div class="trending-title">Funding Announcements</div>
                        <div class="trending-stats">23 this week</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add interaction to engagement items
            const engagementItems = document.querySelectorAll('.engagement-item');
            engagementItems.forEach(item => {
                item.addEventListener('click', function() {
                    const icon = this.querySelector('.engagement-icon');
                    icon.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        icon.style.transform = 'scale(1)';
                    }, 150);
                });
            });

            // Auto-refresh functionality
            let refreshInterval;
            const refreshBtn = document.querySelector('.feed-refresh');
            
            function startAutoRefresh() {
                refreshInterval = setInterval(() => {
                    refreshBtn.textContent = 'Auto Refreshing...';
                    // In a real implementation, you'd fetch new data here
                    setTimeout(() => {
                        refreshBtn.textContent = 'Refresh Feed';
                    }, 2000);
                }, 300000); // 5 minutes
            }

            // Uncomment to enable auto-refresh
            // startAutoRefresh();

            // Form submission loading state
            const form = document.querySelector('.controls-form');
            const searchBtn = document.querySelector('.search-btn');
            
            form.addEventListener('submit', function() {
                searchBtn.textContent = 'Updating...';
                searchBtn.disabled = true;
            });

            // Simulate real-time updates
            function simulateRealTimeUpdates() {
                const posts = document.querySelectorAll('.post');
                posts.forEach(post => {
                    const engagementItems = post.querySelectorAll('.engagement-item span');
                    engagementItems.forEach(item => {
                        const currentValue = parseInt(item.textContent.replace(/[^0-9]/g, ''));
                        if (Math.random() > 0.95) { // 5% chance of update
                            const newValue = currentValue + Math.floor(Math.random() * 5) + 1;
                            item.textContent = item.textContent.replace(/[\d,]+/, newValue.toLocaleString());
                            item.parentElement.style.color = '#28a745';
                            setTimeout(() => {
                                item.parentElement.style.color = '';
                            }, 1000);
                        }
                    });
                });
            }

            // Run real-time updates every 30 seconds
            setInterval(simulateRealTimeUpdates, 30000);
        });
    </script>
</body>
</html>