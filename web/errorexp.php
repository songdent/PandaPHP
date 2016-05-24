<!doctype html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <title>error</title>
    <style type="text/css">
        * {
            font-family: Arial;
        }

        body {
            margin: 0;
            padding: 0;
        }

        #errorexp-wrap {
            overflow: hidden;
            margin-top: 30px;
        }

        #errorexp-container {
            width: 80%;
            min-width: 1200px;
            overflow: hidden;
            margin-right: auto;
            margin-left: auto;
            word-break: break-all; /*支持IE，chrome，FF不支持*/
            word-wrap: break-word; /*支持IE，chrome，FF*/
        }

        #errorexp-trace {
            width: inherit;
            color: #777777;
        }
    </style>
</head>
<body>
<div id="errorexp-wrap">
    <div id="errorexp-container">
        <p style="font-size: 28px;color: #666666;border-bottom: 1px solid #EAEAEA;padding-bottom: 20px;">
            <?php echo $e['message']; ?>
        </p>

        <div id="errorexp-trace">
            <?php if (isset($e['line'])) { ?>
                Some errors or exceptions occurred on line <?php echo $e['line']; ?> of the file <?php echo $e['file']; ?>.
            <?php } ?><?php if (isset($e['trace'])) { ?>
                <pre style="font-size: 16px;"><?php print_r($e['trace']); ?></pre>
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>