<?php
// Configuration
$rapidapi_key = "61ee1c4518msh5d5083b2d788379p18c056jsn4fca2f1bb54c";
$rapidapi_host = "jsearch.p.rapidapi.com";

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
$jobs = [];
$search_query = isset($_GET['search']) ? $_GET['search'] : 'Software Engineer';
$location = isset($_GET['location']) ? $_GET['location'] : '';
$employment_type = isset($_GET['employment_type']) ? $_GET['employment_type'] : '';
$date_posted = isset($_GET['date_posted']) ? $_GET['date_posted'] : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Build API parameters for JSearch
$api_params = [];
$api_params['query'] = $search_query;
$api_params['page'] = $page;
$api_params['num_pages'] = 1;

if (!empty($location)) {
    $api_params['query'] .= ' in ' . $location;
}
if (!empty($employment_type)) {
    $api_params['employment_types'] = $employment_type;
}
if (!empty($date_posted)) {
    $api_params['date_posted'] = $date_posted;
}

// Fetch jobs from JSearch API
$api_response = makeApiCall('search', $api_params);
if ($api_response && isset($api_response['data'])) {
    $jobs = $api_response['data'];
}

// Get available filters for JSearch
$employment_types = ['FULLTIME', 'PARTTIME', 'CONTRACTOR', 'INTERN'];
$date_posted_options = ['all', 'today', '3days', 'week', 'month'];
$locations = ['United States', 'Canada', 'United Kingdom', 'Australia', 'Germany', 'France', 'India', 'Singapore'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Search Portal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        

        .search-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .search-form {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr auto;
            gap: 15px;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-group input,
        .form-group select {
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .search-btn {
            background: #667eea;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .search-btn:hover {
            background: #5a6fd8;
        }

        .results-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
        }

        .results-count {
            font-size: 1.2rem;
            font-weight: bold;
            color: #667eea;
        }

        .job-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .job-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .job-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }

        .job-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .job-company {
            font-size: 1.1rem;
            color: #667eea;
            margin-bottom: 5px;
        }

        .job-location {
            color: #666;
            font-size: 0.9rem;
        }

        .job-type {
            background: #e3f2fd;
            color: #1976d2;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            align-self: flex-start;
        }

        .job-description {
            margin: 15px 0;
            line-height: 1.6;
            color: #555;
        }

        .job-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 15px 0;
            padding: 15px;
            background: white;
            border-radius: 5px;
            border: 1px solid #e0e0e0;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
            font-size: 0.9rem;
        }

        .detail-value {
            color: #666;
            font-size: 0.9rem;
        }

        .apply-btn {
            background: #28a745;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
        }

        .apply-btn:hover {
            background: #218838;
        }

        .no-results {
            text-align: center;
            padding: 50px;
            color: #666;
        }

        .no-results h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 10px;
        }

        .pagination a {
            padding: 10px 15px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background: #5a6fd8;
        }

        .pagination .current {
            background: #333;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .search-form {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .job-header {
                flex-direction: column;
                align-items: start;
            }
            
            .job-type {
                margin-top: 10px;
            }
            
            .job-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="header">
        <div class="logo">Pitchus<span>.ai</span></div>
        
        <nav class="nav-links">
            <a href="index.php" style="text-decoration: none;"><div class="nav-item ">Home</div></a>
            <a href="mes.php" style="text-decoration: none;"><div class="nav-item ">Message</div></a>
            <a href="news.php" style="text-decoration: none;"><div class="nav-item ">News</div></a>
            <a href="jobs.php" style="text-decoration: none;"><div class="nav-item active">Jobs</div></a>
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
    <br>
    <br>
    <br><br><br>    

    <div class="container">
        <div class="search-section">
            <form class="search-form" method="GET">
                <div class="form-group">
                    <label for="search">Job Title or Keywords</label>
                    <input type="text" id="search" name="search" placeholder="e.g. Software Engineer, Marketing Manager" value="<?php echo htmlspecialchars($search_query); ?>">
                </div>
                
                <div class="form-group">
                    <label for="location">Location</label>
                    <select id="location" name="location">
                        <option value="">All Locations</option>
                        <?php foreach ($locations as $loc): ?>
                            <option value="<?php echo htmlspecialchars($loc); ?>" <?php echo $location === $loc ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($loc); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="employment_type">Employment Type</label>
                    <select id="employment_type" name="employment_type">
                        <option value="">All Types</option>
                        <?php foreach ($employment_types as $type): ?>
                            <option value="<?php echo htmlspecialchars($type); ?>" <?php echo $employment_type === $type ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars(ucfirst(strtolower($type))); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="date_posted">Date Posted</label>
                    <select id="date_posted" name="date_posted">
                        <option value="">Any Time</option>
                        <?php 
                        $date_labels = [
                            'all' => 'All',
                            'today' => 'Today',
                            '3days' => 'Last 3 Days',
                            'week' => 'This Week',
                            'month' => 'This Month'
                        ];
                        foreach ($date_posted_options as $option): ?>
                            <option value="<?php echo htmlspecialchars($option); ?>" <?php echo $date_posted === $option ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($date_labels[$option] ?? ucfirst($option)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" class="search-btn">Search Jobs</button>
            </form>
        </div>

        <div class="results-section">
            <?php if ($api_response === false): ?>
                <div class="error-message">
                    <strong>Error:</strong> Unable to connect to the job database. Please try again later.
                </div>
            <?php endif; ?>

            <div class="results-header">
                <div class="results-count">
                    <?php 
                    $total_jobs = count($jobs);
                    echo $total_jobs . ' job' . ($total_jobs !== 1 ? 's' : '') . ' found';
                    ?>
                </div>
            </div>

            <?php if (empty($jobs)): ?>
                <div class="no-results">
                    <h3>No jobs found</h3>
                    <p>Try adjusting your search criteria or removing some filters.</p>
                </div>
            <?php else: ?>
                <?php foreach ($jobs as $job): ?>
                    <div class="job-card">
                        <div class="job-header">
                            <div>
                                <div class="job-title"><?php echo htmlspecialchars($job['job_title'] ?? 'Job Title Not Available'); ?></div>
                                <div class="job-company"><?php echo htmlspecialchars($job['employer_name'] ?? 'Company Not Available'); ?></div>
                                <div class="job-location">📍 <?php echo htmlspecialchars($job['job_city'] ?? '') . ', ' . htmlspecialchars($job['job_state'] ?? '') . ' ' . htmlspecialchars($job['job_country'] ?? ''); ?></div>
                            </div>
                            <div class="job-type"><?php echo htmlspecialchars($job['job_employment_type'] ?? 'Full-time'); ?></div>
                        </div>
                        
                        <div class="job-description">
                            <?php 
                            $description = $job['job_description'] ?? 'No description available';
                            echo htmlspecialchars(substr($description, 0, 300)); 
                            ?>
                            <?php if (strlen($description) > 300): ?>...<?php endif; ?>
                        </div>
                        
                        <div class="job-details">
                            <?php if (isset($job['job_min_salary']) && isset($job['job_max_salary'])): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Salary Range</span>
                                    <span class="detail-value">
                                        $<?php echo number_format($job['job_min_salary']); ?> - $<?php echo number_format($job['job_max_salary']); ?>
                                        <?php if (isset($job['job_salary_period'])): ?>
                                            <?php echo ' / ' . htmlspecialchars($job['job_salary_period']); ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (isset($job['job_is_remote'])): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Remote Work</span>
                                    <span class="detail-value"><?php echo $job['job_is_remote'] ? 'Yes' : 'No'; ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (isset($job['job_posted_at_datetime_utc'])): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Posted Date</span>
                                    <span class="detail-value"><?php echo date('M j, Y', strtotime($job['job_posted_at_datetime_utc'])); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (isset($job['job_publisher'])): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Source</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($job['job_publisher']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (isset($job['job_apply_link'])): ?>
                            <a href="<?php echo htmlspecialchars($job['job_apply_link']); ?>" class="apply-btn" target="_blank">Apply Now</a>
                        <?php else: ?>
                            <button class="apply-btn" onclick="alert('Application link not available for this job.')">Apply Now</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <!-- Pagination -->
            <?php if (!empty($jobs)): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">Previous</a>
                    <?php endif; ?>
                    
                    <a href="#" class="current"><?php echo $page; ?></a>
                    
                    <?php if (count($jobs) >= 10): // Assuming 10 jobs per page ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">Next</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-submit form when filters change (optional)
            const filters = document.querySelectorAll('select[name="location"], select[name="job_type"]');
            filters.forEach(filter => {
                filter.addEventListener('change', function() {
                    // Uncomment the line below to auto-submit on filter change
                    // document.querySelector('.search-form').submit();
                });
            });
            
            // Add loading state to search button
            const searchForm = document.querySelector('.search-form');
            const searchBtn = document.querySelector('.search-btn');
            
            searchForm.addEventListener('submit', function() {
                searchBtn.innerHTML = 'Searching...';
                searchBtn.disabled = true;
            });
        });
    </script>
</body>
</html>