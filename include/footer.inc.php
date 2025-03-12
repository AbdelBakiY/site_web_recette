<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <!-- Section 1 : Liens rapides -->
            <div class="col-md-3">
                <h5>Navigation</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-white">Accueil</a></li>
                    <li><a href="recettes.php" class="text-white">Recettes</a></li>
                    <li><a href="categorie.php" class="text-white">Catégories</a></li>
                    <li><a href="blog.php" class="text-white">Blog</a></li>
                    <li><a href="contact.php" class="text-white">Contact</a></li>
                </ul>
            </div>

            <!-- Section 2 : Réseaux sociaux -->
            <div class="col-md-3">
                <h5>Suivez-nous</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white">📘 Facebook</a></li>
                    <li><a href="#" class="text-white">📸 Instagram</a></li>
                    <li><a href="#" class="text-white">▶️ YouTube</a></li>
                </ul>
            </div>

            <!-- Section 3 : Newsletter -->
            <div class="col-md-3">
                <h5>Newsletter</h5>
                <p>Recevez nos meilleures recettes chaque semaine !</p>
                <form action="newsletter.php" method="POST">
                    <input type="email" name="email" class="form-control mb-2" placeholder="Votre email" required>
                    <button type="submit" class="btn btn-primary">S'inscrire</button>
                </form>
            </div>

            <!-- Section 4 : Mentions légales -->
            <div class="col-md-3">
                <h5>Informations</h5>
                <ul class="list-unstyled">
                    <li><a href="mentions-legales.php" class="text-white">Mentions légales</a></li>
                    <li><a href="cgu.php" class="text-white">Conditions d'utilisation</a></li>
                    <li><a href="politique-confidentialite.php" class="text-white">Confidentialité</a></li>
                </ul>
            </div>
        </div>

        <hr class="bg-light">

        <!-- Copyright -->
        <div class="text-center">
            <p>&copy; <?php echo date('Y'); ?> MonSiteDeRecettes - Tous droits réservés.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>