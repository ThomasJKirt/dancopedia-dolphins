<?php
session_start();
if (!isset($_SESSION["user_name"]) && !isset($_SESSION["admin_name"])) {
}
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</head>

<div class="toolBar" style="background:linear-gradient(135deg, #FFDF00, #009C3B);">
    <header class="p-3" style="background: transparent;">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <!-- Brazil Flag -->
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                    <img src="assets/images/brazil_flag.jpg" alt="Brazil Flag" style="width: 40px; height: auto; margin-right: 10px;">
                </a>

                <!-- Navigation Links -->
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="../public/index.html" class="nav-link px-2 text-white">Home</a></li>
                    <li><a href="../public/danceCategories.html" class="nav-link px-2" style="color: darkgreen;">Categories</a></li>
                    <li><a href="../public/regions.html" class="nav-link px-2" style="color: darkgreen;">Regions</a></li>
                    <li><a href="createDance.php" class="nav-link px-2" style="color: darkgreen;">Create A Dance</a></li>
                </ul>

                <!-- Search Box -->
                <div class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                    <form action="../public/search.html" method="get" class="d-flex" onsubmit="return handleSearch(event)">
                        <input type="text" name="q" id="toolbarSearchInput" class="form-control" placeholder="Search..." aria-label="Search">
                        <button type="submit" class="btn btn-light" style="border-color: #FFDF00; color: #FFDF00; margin-left: 5px;">Go</button>
                    </form>
                </div>

                <script>
                    function handleSearch(event) {
                        const query = document.getElementById("toolbarSearchInput").value.trim();
                        if (!query) {
                            event.preventDefault();
                            return false;
                        }
                        return true;
                    }
                </script>

                <!-- User Controls -->
                <div class="text-end">
                    <button type="button" class="btn btn-outline-light me-2" onclick="window.location.href='login.php'">Login</button>
                    <button type="button" class="btn"
                            style="background-color: lightgreen; color: darkgreen; border-color: darkgreen;" onclick="window.location.href='signup.php'">Sign-up</button>
                    <button type="button" class="btn btn-outline-light me-2">Settings</button>
                </div>



                <?php if (isset($_SESSION["user_name"]) || isset($_SESSION["admin_name"])): ?>
                    <div class="dropdown text-end">
                        <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="White" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                            </svg>
                            <?php echo isset($_SESSION["admin_name"]) ? $_SESSION["admin_name"] : $_SESSION["user_name"]; ?>
                        </a>
                        <ul class="dropdown-menu text-small">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Saved</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../public/logout.php">Sign out</a></li>
                        </ul>

                    </div>
                <?php endif; ?>

                </ul>
            </div>
        </div>
</div>
</header>
</div>