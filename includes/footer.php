        </main> <!-- Closing tag from header.php -->

        <!-- Footer -->
        <footer class="bg-dark text-white pt-5 pb-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <h5 class="mb-3">
                            <i class="fas fa-store me-2"></i> DigitalStore
                        </h5>
                        <p>Your trusted source for premium digital products since 2023. We provide instant downloads with 100% satisfaction guarantee.</p>
                        <div class="social-icons mt-3">
                            <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-white me-2"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-md-2 mb-4">
                        <h5 class="mb-3">Quick Links</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="../index.php" class="text-white">Home</a></li>
                            <li class="mb-2"><a href="../about.php" class="text-white">About Us</a></li>
                            <li class="mb-2"><a href="../contact.php" class="text-white">Contact</a></li>
                            <li class="mb-2"><a href="../privacy.php" class="text-white">Privacy Policy</a></li>
                            <li class="mb-2"><a href="../terms.php" class="text-white">Terms & Conditions</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <h5 class="mb-3">Categories</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="#" class="text-white">eBooks</a></li>
                            <li class="mb-2"><a href="#" class="text-white">Website Templates</a></li>
                            <li class="mb-2"><a href="#" class="text-white">Video Courses</a></li>
                            <li class="mb-2"><a href="#" class="text-white">Software</a></li>
                            <li class="mb-2"><a href="#" class="text-white">Graphics</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <h5 class="mb-3">Newsletter</h5>
                        <p>Subscribe to get updates on new products and offers.</p>
                        <form class="mt-3">
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="Your Email">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                        <div class="payment-methods mt-3">
                            <h6>We Accept:</h6>
                            <img src="https://via.placeholder.com/40x25?text=UPI" alt="UPI" class="me-1 mb-1">
                            <img src="https://via.placeholder.com/40x25?text=Visa" alt="Visa" class="me-1 mb-1">
                            <img src="https://via.placeholder.com/40x25?text=MC" alt="Mastercard" class="me-1 mb-1">
                        </div>
                    </div>
                </div>
                
                <hr class="my-4 bg-light">
                
                <div class="row">
                    <div class="col-md-6 text-center text-md-start">
                        <small>&copy; 2023 DigitalStore. All rights reserved.</small>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <small>
                            <a href="#" class="text-white me-2">Privacy Policy</a>
                            <a href="#" class="text-white me-2">Terms of Service</a>
                            <a href="#" class="text-white">Sitemap</a>
                        </small>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Back to Top Button -->
        <a href="#" class="btn btn-primary position-fixed bottom-0 end-0 m-4 rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-arrow-up"></i>
        </a>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/main.js"></script>
        <script>
            // Back to top button
            const backToTopButton = document.querySelector('.btn-position-fixed');
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTopButton.style.display = 'flex';
                } else {
                    backToTopButton.style.display = 'none';
                }
            });
            
            // Update cart count dynamically
            function updateCartCount(count) {
                document.querySelector('.cart-count').textContent = count;
            }
        </script>
    </body>
</html>