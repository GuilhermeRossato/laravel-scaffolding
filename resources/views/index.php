<!DOCTYPE html>
<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='chrome=1'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title><?php echo config('app.name'); ?></title>
    <style>
        html,
        body {
            min-width: 100vw;
            min-height: 100%;
            display: flex;
            margin: 0;
            padding: 0;
        }

        .flex-center {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body class='flex-center'>
    <div><h2>Welcome to the <span style='font-weight: bold'><?php echo config('app.name'); ?> App</span></h2><p></p></div>
</body>
</html>