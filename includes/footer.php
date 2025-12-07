    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php
    // Compute web-accessible project root reliably from filesystem paths.
    $docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
    $projRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
    $baseUrl = '/' . trim(str_replace($docRoot, '', $projRoot), '/');
    if ($baseUrl === '/') { $jsPath = $baseUrl . 'assets/js/script.js'; }
    else { $jsPath = $baseUrl . '/assets/js/script.js'; }
    ?>
    <script src="<?php echo $jsPath; ?>"></script>
</body>
</html>