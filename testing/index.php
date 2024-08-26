<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Author Search</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <style>
    .ui-autocomplete {
      max-height: 200px;
      overflow-y: auto;
      overflow-x: hidden;
    }
  </style>

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word Wise</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/ff5868ab46.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="../img/wordwise.png">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/adstyle.css">
  </head>
</head>

<body>

  <form action="" method="post">
    <label for="author">Author:</label>
    <input class="form-control" type="text" id="author" name="author">
  </form>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <script>
    $(function() {
      $("#author").autocomplete({
        source: function(request, response) {
          $.ajax({
            url: "search_authors.php",
            type: "POST",
            data: {
              term: request.term
            },
            dataType: "json",
            success: function(data) {
              response(data);
            }
          });
        },
        minLength: 2
      });
    });
  </script>

</body>

</html>