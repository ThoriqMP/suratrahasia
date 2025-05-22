// Untuk komponen JavaScript jika diperlukan
import './bootstrap';

// Tambahkan animasi scroll
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fadeIn');
            }
        });
    });

    document.querySelectorAll('.scroll-animate').forEach((element) => {
        observer.observe(element);
    });
});