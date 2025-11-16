<script>
document.addEventListener('DOMContentLoaded', () => {
  // Only target PHP code spans inside post snippets
  const spans = document.querySelectorAll('.post-snippet p span[class^="code-php-"]');

  spans.forEach(span => {
    const classes = span.classList;

    // Skip comments and strings completely
    if (
      classes.contains('code-php-comment-fc') ||
      classes.contains('code-php-string-fc')
    ) {
      return;
    }

    // For all other code-php* spans, replace spaces with &nbsp;
    // so spaces are preserved even with font-size: 0 hack, wrapping, etc.
    span.innerHTML = span.innerHTML.replace(/ /g, '&nbsp;');
  });
});
</script>
