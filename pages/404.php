<?php include '../includes/error-message.php'; ?>


<section id="not-found" class="not-found section dark-background">
    <div class="not-found-content">
        <h1>404</h1>
        <h2>Oops! Page Not Found</h2>
        <p>Sorry, the page you are looking for does not exist.</p>
        <a href="/" class="btn-get-started">Go Home</a>
    </div>
</section>



<style>
    .not-found {
        min-height: 90vh;
        text-align: center;
        padding: 100px 20px;
        font-family: Arial, sans-serif;
    }

    .not-found-content h1 {
        font-size: 80px;
        color:rgb(151, 0, 0);
    }

    .not-found-content h2 {
        font-size: 36px;
        margin-bottom: 20px;
    }

    .not-found-content p {
        font-size: 18px;
        margin-bottom: 30px;
    }

    .btn-get-started {
        background: #007bff;
        color: white;
        padding: 12px 24px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 18px;
    }

    .btn-get-started:hover {
        background: #0056b3;
    }
</style>