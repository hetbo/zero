<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zero File Manager</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        #zero-root { min-height: 100vh; background: #f5f5f5; }
    </style>
</head>
<body>
<div id="zero-root"></div>

duck

<script>
    window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    window.zeroApiUrl = '{{ url("/zero/api") }}';
</script>

<script src="{{ url('hetbo/zero/zero.umd.cjs') }}"></script>
</body>
</html>