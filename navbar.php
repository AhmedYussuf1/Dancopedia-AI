<!-- Favicon -->
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="css/navbar.css">
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="images/logo.png" alt="Dance USA Logo" class="img-fluid" style="height: 50px;">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Home Link -->
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">Home</a>
                </li>

                <!-- Classical Dances Link -->
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'classical_dances.php' ? 'active' : ''; ?>" href="classical_dances.php">Classical Dances</a>
                </li>

                <!-- Folk Dances Link -->
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'folk_dances.php' ? 'active' : ''; ?>" href="folk_dances.php">Folk Dances</a>
                </li>

                <!-- Contemporary Dances Link -->
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contemporary_dances.php' ? 'active' : ''; ?>" href="contemporary_dances.php">Contemporary Dances</a>
                </li>

                <!-- Blog Link (always visible) -->
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'blog.php' ? 'active' : ''; ?>" href="blog.php">Blog</a>
                </li>

                <!-- Search Bar -->
                <li class="nav-item search-bar">
                    <input type="text" id="search-bar" class="form-control" placeholder="Search for dances..." onkeyup="searchDances()">
                </li>

                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item">
                        <div class="nav-item dropdown show"><a class="dropdown-toggle" aria-expanded="true" data-bs-toggle="dropdown" href="#"><?php echo $_SESSION['username'] ?></a>
                            <div class="dropdown-menu dropdown-menu-end" data-bs-popper="none">
                                <a class="dropdown-item" href="user_settings.php">User Settings</a>
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <li class="nav-item">
                                        <a class="dropdown-item <?php echo basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php' ? 'active' : ''; ?>" href="admin_dashboard.php">Admin</a>
                                    </li>
                                <?php endif; ?>
                                <a class="dropdown-item" href="logout.php">Logout</a>
                            </div>
                        </div>
                    </li>
                <?php else: ?>
                    <!-- Login Link -->
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : ''; ?>" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- JavaScript for Search Functionality -->
<script>
function searchDances() {
    var query = document.getElementById('search-bar').value.toLowerCase();
    var danceItems = document.querySelectorAll('.dance-item');
    var matchingItems = [];

    danceItems.forEach(function(item) {
        var danceName = item.querySelector('h5').textContent.toLowerCase();
        var danceDescription = item.querySelector('p').textContent.toLowerCase();

        if (danceName.includes(query) || danceDescription.includes(query)) {
            matchingItems.push(item);
        }
    });

    var resultsContainer = document.getElementById('search-results');
    resultsContainer.innerHTML = '';  // Clear previous results
    
    matchingItems.forEach(function(item) {
        resultsContainer.appendChild(item);
    });

    if (matchingItems.length === 0 && query.length > 0) {
        resultsContainer.innerHTML = 'No results found for: "' + query + '"';
    }
}
</script>
