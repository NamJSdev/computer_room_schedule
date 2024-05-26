<!DOCTYPE html>
<html>
<head>
    <title>Thời khóa biểu</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Thời khóa biểu</h1>
    <button id="scrapeButton">Crawl Dữ liệu</button>

    <div id="result"></div>

    <script>
        $(document).ready(function() {
            $('#scrapeButton').click(function() {
                $.ajax({
                    url: '/scrape',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.html) {
                            $('#result').html(response.html);
                        } else {
                            $('#result').html('<p>Không có dữ liệu thời khóa biểu.</p>');
                        }
                    },
                    error: function() {
                        $('#result').html('<p>Crawl dữ liệu thất bại.</p>');
                    }
                });
            });
        });
    </script>
</body>
</html>

