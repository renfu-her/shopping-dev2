<footer class="bg-dark text-light py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>E-Commerce Store</h5>
                <p class="text-muted">Your trusted online shopping destination for quality products.</p>
            </div>
            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-muted text-decoration-none">Products</a></li>
                    <li><a href="{{ route('cart.index') }}" class="text-muted text-decoration-none">Cart</a></li>
                    <li><a href="{{ route('member.login') }}" class="text-muted text-decoration-none">Login</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Contact Us</h5>
                <ul class="list-unstyled text-muted">
                    <li><i class="fas fa-envelope me-2"></i>info@ecommerce.com</li>
                    <li><i class="fas fa-phone me-2"></i>+1 234 567 890</li>
                    <li><i class="fas fa-map-marker-alt me-2"></i>123 Store Street, City</li>
                </ul>
            </div>
        </div>
        <hr class="my-3">
        <div class="row">
            <div class="col-md-6">
                <p class="text-muted mb-0">&copy; {{ date('Y') }} E-Commerce Store. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="text-muted mb-0">
                    <a href="#" class="text-muted text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-muted text-decoration-none">Terms of Service</a>
                </p>
            </div>
        </div>
    </div>
</footer>
