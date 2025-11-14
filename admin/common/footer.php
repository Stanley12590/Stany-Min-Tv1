    </main>

    <script>
        // Disable right-click, text selection, and zoom
        document.addEventListener('contextmenu', function(e) { e.preventDefault(); });
        document.addEventListener('selectstart', function(e) { e.preventDefault(); });
        document.addEventListener('dragstart', function(e) { e.preventDefault(); });
        
        // Disable zoom
        document.addEventListener('touchstart', function(e) { if (e.touches.length > 1) e.preventDefault(); });
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function(e) {
            const now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) e.preventDefault();
            lastTouchEnd = now;
        }, false);

        // Auto-hide URL bar on mobile
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.scrollTo(0, 1);
            }, 0);
        });
    </script>
</body>
</html>
